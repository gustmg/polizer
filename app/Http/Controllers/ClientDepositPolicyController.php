<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\AccountingAccount;
use App\AccountingAccountType;
use App\BankAccount;
use App\Bank;
use App\Company;
use App\Client;
use View;
use Session;
use Excel;
use App\Traits\PolicyTrait;

class ClientDepositPolicyController extends Controller
{
    public function index()
    {
        $clients=Client::with('counterpart_account')->where('company_id', session()->get('company_workspace_id'))->get();
        $bank_accounts=BankAccount::with('counterpart_account', 'bank')->where('company_id', session()->get('company_workspace_id'))->get();
        $companies=Company::where('user_id', Auth::user()->id)->get();
        $accounting_account_types=AccountingAccountType::all();
        $banks=Bank::all();
        $accounting_accounts=AccountingAccount::where(function($query){
            $query->where('accounting_account_type_id', 3);
        })->where('company_id', session()->get('company_workspace_id'))->get();
        
        return view::make('client_deposit_policy.index',['clients'=>$clients,'companies'=>$companies,'accounting_accounts'=>$accounting_accounts,'accounting_account_types'=>$accounting_account_types, 'bank_accounts'=>$bank_accounts, 'banks'=>$banks]);
    }

    public function handler(Request $request){
        if($request->handler==="getClient"){
            $client = Client::with('counterpart_account')->where([
                ['company_id', '=', session()->get('company_workspace_id')],
                ['client_rfc', '=', $request->client_rfc]
            ])->get();
            return $client;
        }
        else if($request->handler==="newClient"){
            $validatedData = $request->validate([
                'client_name' => 'required|max:255',
                'client_rfc' => 'required|max:255',
                'client_accounting_account' => 'required|max:255',
            ]);

            $client=new Client;
            $client->client_name=$request->client_name;
            $client->client_rfc=$request->client_rfc;
            $client->client_accounting_account=$request->client_accounting_account;
            $client->company_id=Session::get('company_workspace_id');
            $client->counterpart_accounting_account_id=$request->counterpart_accounting_account_id;

            $client->save();

            $client_saved = Client::with('counterpart_account')->where([
                ['company_id', '=', session()->get('company_workspace_id')],
                ['client_rfc', '=', $request->client_rfc]
            ])->get();
            return $client_saved;
        }
        else if($request->handler==="export"){
            $GLOBALS['provision_type'] = $request->policyType;
            $GLOBALS['generate_by_client'] = $request->generateByClient;
            $GLOBALS['cfdi_index_serie'] = $request->cfdiIndexSerie;
            $GLOBALS['jsonFiles'] = array();
            $GLOBALS['row_index'] = 3;
            $GLOBALS['company'] = Company::where('company_id', session()->get('company_workspace_id'))->get();
            $GLOBALS['cfdi_key'] = 0;


            foreach ($request->jsonFiles as $key => $cfdi) {
                array_push($GLOBALS['jsonFiles'], json_decode($cfdi));
            }

            if($GLOBALS['generate_by_client']=='1'){
                usort($GLOBALS['jsonFiles'], function($a,$b){
                    return $a->emisor->rfcEmisor < $b->emisor->rfcEmisor ? -1 : 1;
                });    
            }
            
            $file_name = 'DEPOSITO DE CLIENTES -'.date("Y-m-d-H-i-s").'-'.Session::get('company_workspace');

            Excel::create($file_name, function($excel){
                $excel->sheet('Libro 1', function($sheet) {
                    ClientDepositPolicyController::generatePolicy($sheet, $GLOBALS['jsonFiles'][0]);
                });
            // })->store('xlsx', storage_path('app/public'));
            })->store('xlsx', public_path('storage'));
            PolicyTrait::saveUserProcessedXML(Auth::user()->id, session()->get('company_workspace_id'), $GLOBALS['cfdi_key']+1);
            // $url = Storage::url($file_name.'.xlsx');
            $url = 'https://www.polizer.com.mx/polizer_app/storage/'.$file_name.'.xlsx';
            return $url;
        }
    }

    public function generatePolicy($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            'Ig',
            $GLOBALS['cfdi_index_serie'],
            'DEPOSITO DE CLIENTES - '.$cfdi->receptor->nombreReceptor.' -  CFDI: '.$cfdi->comprobante->folio,
            substr($cfdi->comprobante->fecha,-2,2)
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        $GLOBALS['cfdi_index_serie'] = $GLOBALS['cfdi_index_serie']+1;
        ClientDepositPolicyController::generatePolicyBankAccountItem($sheet, $cfdi);
    }

    public function generatePolicyBankAccountItem($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            '',
            $cfdi->datosOrigen->cuentaContableOrigen,
            '0',
            'DEPOSITO DE CLIENTES - '.$cfdi->receptor->nombreReceptor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            $cfdi->comprobante->total,
            '',
            '0',
            '0'
        ));
            
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ClientDepositPolicyController::generatePolicyItemRows($sheet, $cfdi);
        ClientDepositPolicyController::generatePolicyTransferredVatItem($sheet, $cfdi);
    }

    public function generatePolicyTransferredVatItem($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            '',
            $GLOBALS['company'][0]->transferred_vat_account,
            '0',
            'DEPOSITO DE CLIENTES - '.$cfdi->receptor->nombreReceptor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            round(($cfdi->comprobante->total/1.16)*.16, 2),
            '',
            '0',
            '0'
        ));
            
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ClientDepositPolicyController::generatePolicyItemRows($sheet, $cfdi);
        ClientDepositPolicyController::generatePolicyChargedTransferredVatItem($sheet, $cfdi);
    }

    public function generatePolicyChargedTransferredVatItem($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            '',
            $GLOBALS['company'][0]->charged_transferred_vat_account,
            '0',
            'DEPOSITO DE CLIENTES - '.$cfdi->receptor->nombreReceptor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            '',
            round(($cfdi->comprobante->total/1.16)*.16, 2),
            '0',
            '0'
        ));
            
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ClientDepositPolicyController::generatePolicyItemRows($sheet, $cfdi);
        ClientDepositPolicyController::generatePolicyClientItem($sheet, $cfdi);
    }

    public function generatePolicyClientItem($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            '',
            $cfdi->cliente->cuentaContable[0],
            '0',
            'DEPOSITO DE CLIENTES - '.$cfdi->receptor->nombreReceptor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            '',
            $cfdi->comprobante->total,
            '0',
            '0'
        ));
            
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ClientDepositPolicyController::generatePolicyItemRows($sheet, $cfdi);

        if(PolicyTrait::validateNextCfdi()){
            $GLOBALS['cfdi_key']=$GLOBALS['cfdi_key']+1;
            PolicyTrait::writePolicyFooter($sheet);
            $GLOBALS['row_index']=$GLOBALS['row_index']+1;
            ClientDepositPolicyController::generatePolicy($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
        }
        else{
            PolicyTrait::writePolicyFooter($sheet);
        }
    }

    public function generatePolicyItemRows($sheet, $cfdi) {
        $diaFecha=substr($cfdi->comprobante->fecha,-2,2);
        $mesFecha=substr($cfdi->comprobante->fecha,-5,2);
        $añoFecha=substr($cfdi->comprobante->fecha,-10,4);

        $sheet->row($GLOBALS['row_index'], array(
            '',
            '',
            'INICIO_CFDI'
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;

        $sheet->row($GLOBALS['row_index'], array(
            '',
            '',
            $diaFecha."/".$mesFecha."/".$añoFecha,
            $cfdi->comprobante->serie,
            $cfdi->comprobante->folio,
            $cfdi->emisor->rfcEmisor,
            $cfdi->receptor->rfcReceptor,
            $cfdi->comprobante->total,
            $cfdi->timbreFiscalDigital->uuid
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        
        $sheet->row($GLOBALS['row_index'], array(
            '',
            '',
            'FIN_CFDI'
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
    }

    public function generatePolicyBankAccountRows($sheet, $cfdi) {
        $diaFecha=substr($cfdi->comprobante->fecha,-2,2);
        $mesFecha=substr($cfdi->comprobante->fecha,-5,2);
        $añoFecha=substr($cfdi->comprobante->fecha,-10,4);

        $sheet->row($GLOBALS['row_index'], array(
            '',
            '',
            'INICIO_INFOPAGO'
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;

        $sheet->row($GLOBALS['row_index'], array(
            '',
            '',
            $diaFecha."/".$mesFecha."/".$añoFecha,
            $cfdi->datosOrigen->bancoOrigen,
            $cfdi->datosOrigen->cuentaBancariaOrigen,
            $cfdi->comprobante->formaPago,
            $cfdi->datosDestino->numeroCheque,
            $cfdi->comprobante->total,
            $cfdi->emisor->rfcEmisor
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        
        $sheet->row($GLOBALS['row_index'], array(
            '',
            '',
            'FIN_INFOPAGO'
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
    }
}
