<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\AccountingAccount;
use App\AccountingAccountType;
use App\Company;
use App\Client;
use View;
use Session;
use Excel;
use App\Traits\PolicyTrait;

class BillingPolicyController extends Controller
{
    public function index(){
        $clients=Client::with('counterpart_account')->where('company_id', session()->get('company_workspace_id'))->get();
        $companies=Company::where('user_id', Auth::user()->id)->get();
        $accounting_account_types=AccountingAccountType::all();
        $accounting_accounts=AccountingAccount::where(function($query){
            $query->where('accounting_account_type_id', 3);
        })->where('company_id', session()->get('company_workspace_id'))->get();
        
        return view::make('billing_policy.index',['clients'=>$clients,'companies'=>$companies,'accounting_accounts'=>$accounting_accounts,'accounting_account_types'=>$accounting_account_types]);
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
                    return $a->receptor->rfcReceptor < $b->receptor->rfcReceptor ? -1 : 1;
                });    
            }
            
            $file_name = 'IG-'.date("Y-m-d-H-i-s").'-'.Session::get('company_workspace');

            Excel::create($file_name, function($excel){
                $excel->sheet('Libro 1', function($sheet) {
                    BillingPolicyController::generatePolicy($sheet, $GLOBALS['jsonFiles'][0]);
                });
            // })->store('xlsx', storage_path('app/public'));
            })->store('xlsx', public_path('storage'));

            // $url = Storage::url($file_name.'.xlsx');
            $url = 'https://www.polizer.mx/polizer_app/storage/'.$file_name.'.xlsx';
            return $url;
        }
    }

    public function generatePolicy($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            'Ig',
            $GLOBALS['cfdi_index_serie'],
            'FACTURACIÓN A CLIENTES - '.$cfdi->receptor->nombreReceptor.' -  CFDI: '.$cfdi->comprobante->folio,
            substr($cfdi->comprobante->fecha,-11,2)
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        $GLOBALS['cfdi_index_serie'] = $GLOBALS['cfdi_index_serie']+1;
        BillingPolicyController::generatePolicyClientItem($sheet, $cfdi);
    }

    public function generatePolicyClientItem($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            '',
            $cfdi->cliente->cuentaContable[0],
            '0',
            'FACTURACIÓN A CLIENTES - '.$cfdi->receptor->nombreReceptor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            $cfdi->comprobante->total,
            '',
            '0',
            '0'
        ));
        
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        PolicyTrait::generatePolicyItemRows($sheet, $cfdi);
        BillingPolicyController::generatePolicyVatItem($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
    }

    public function generatePolicyVatItem($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            '',
            $GLOBALS['company'][0]->transferred_vat_account,
            '0',
            'FACTURACIÓN A CLIENTES - '.$cfdi->receptor->nombreReceptor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            '',
            $cfdi->traslado->importe,
            '0',
            '0'
        ));
            
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        PolicyTrait::generatePolicyItemRows($sheet, $cfdi);
        BillingPolicyController::generatePolicyItem($sheet, $cfdi);
    }

    public function generatePolicyItem($sheet, $cfdi) {
        foreach($cfdi->concepto->descripciones as $key => $concepto){
            $sheet->row($GLOBALS['row_index'], array(
            '',
            $cfdi->concepto->contrapartidas[$key],
            '0',
            'FACTURACIÓN A CLIENTES - '.PolicyTrait::formateaDescripcion($cfdi->concepto->descripciones[$key]).' - '.$cfdi->receptor->nombreReceptor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            '',
            $cfdi->concepto->importes[$key],
            '0',
            '0'
            ));

            $GLOBALS['row_index']=$GLOBALS['row_index']+1;
            PolicyTrait::generatePolicyItemRows($sheet, $cfdi);
        }

        if(PolicyTrait::validateNextCfdi()){
            $GLOBALS['cfdi_key']=$GLOBALS['cfdi_key']+1;
            if($GLOBALS['generate_by_client']=='1'){
                if(PolicyTrait::comparePrevRfcClient()){
                    BillingPolicyController::generatePolicyClientItem($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
                }
                else{
                    PolicyTrait::writePolicyFooter($sheet);
                    $GLOBALS['row_index']=$GLOBALS['row_index']+1;
                    BillingPolicyController::generatePolicy($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
                }
            }
            else{
                PolicyTrait::writePolicyFooter($sheet);
                $GLOBALS['row_index']=$GLOBALS['row_index']+1;
                BillingPolicyController::generatePolicy($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
            }
        }
        PolicyTrait::writePolicyFooter($sheet);
    }
}