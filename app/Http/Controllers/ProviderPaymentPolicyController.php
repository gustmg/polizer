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
use App\Provider;
use View;
use Session;
use Excel;
use App\Traits\PolicyTrait;

class ProviderPaymentPolicyController extends Controller
{
    public function index()
    {
        $providers=Provider::with('counterpart_account')->where('company_id', session()->get('company_workspace_id'))->get();
        $bank_accounts=BankAccount::with('counterpart_account', 'bank')->where('company_id', session()->get('company_workspace_id'))->get();
        $companies=Company::where('user_id', Auth::user()->id)->get();
        $accounting_account_types=AccountingAccountType::all();
        $banks=Bank::all();
        $accounting_accounts=AccountingAccount::where(function($query){
            $query->where('accounting_account_type_id', 2)
            ->orWhere('accounting_account_type_id', 5)
            ->orWhere('accounting_account_type_id', 6);
        })->where('company_id', session()->get('company_workspace_id'))->get();
        
        return view::make('provider_payment_policy.index',['providers'=>$providers,'companies'=>$companies,'accounting_accounts'=>$accounting_accounts,'accounting_account_types'=>$accounting_account_types, 'bank_accounts'=>$bank_accounts, 'banks'=>$banks]);
    }

    public function handler(Request $request){
        if($request->handler==="getProvider"){
            $provider = Provider::with('counterpart_account')->where([
                ['company_id', '=', session()->get('company_workspace_id')],
                ['provider_rfc', '=', $request->provider_rfc]
            ])->get();
            return $provider;
        }
        else if($request->handler==="newProvider"){
            $validatedData = $request->validate([
                'provider_name' => 'required|max:255',
                'provider_rfc' => 'required|max:255',
                'provider_accounting_account' => 'required|max:255',
            ]);

            $provider=new Provider;
            $provider->provider_name=$request->provider_name;
            $provider->provider_rfc=$request->provider_rfc;
            $provider->provider_accounting_account=$request->provider_accounting_account;
            $provider->company_id=Session::get('company_workspace_id');
            $provider->counterpart_accounting_account_id=$request->counterpart_accounting_account_id;

            $provider->save();

            $provider_saved = Provider::with('counterpart_account')->where([
                ['company_id', '=', session()->get('company_workspace_id')],
                ['provider_rfc', '=', $request->provider_rfc]
            ])->get();
            return $provider_saved;
        }
        else if($request->handler==="export"){
            $GLOBALS['provision_type'] = $request->policyType;
            $GLOBALS['generate_by_provider'] = $request->generateByProvider;
            $GLOBALS['cfdi_index_serie'] = $request->cfdiIndexSerie;
            $GLOBALS['jsonFiles'] = array();
            $GLOBALS['row_index'] = 3;
            $GLOBALS['company'] = Company::where('company_id', session()->get('company_workspace_id'))->get();
            $GLOBALS['cfdi_key'] = 0;


            foreach ($request->jsonFiles as $key => $cfdi) {
                array_push($GLOBALS['jsonFiles'], json_decode($cfdi));
            }

            if($GLOBALS['generate_by_provider']=='1'){
                usort($GLOBALS['jsonFiles'], function($a,$b){
                    return $a->emisor->rfcEmisor < $b->emisor->rfcEmisor ? -1 : 1;
                });    
            }
            
            if($GLOBALS['provision_type'] == 1){
                $file_name = 'PAGO A PROVEEDOR -'.date("Y-m-d-H-i-s").'-'.Session::get('company_workspace');
            }
            else if($GLOBALS['provision_type'] == 2){
                $file_name = 'PAGO DE HONORARIOS -'.date("Y-m-d-H-i-s").'-'.Session::get('company_workspace');   
            }

            Excel::create($file_name, function($excel){
                $excel->sheet('Libro 1', function($sheet) {
                    ProviderPaymentPolicyController::generatePolicy($sheet, $GLOBALS['jsonFiles'][0]);
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
        if($cfdi->comprobante->formaPago=="02"){
            if($GLOBALS['provision_type'] == 1){
                $sheet->row($GLOBALS['row_index'], array(
                    ProviderPaymentPolicyController::setPolicyType($cfdi->comprobante->formaPago),
                    $cfdi->datosDestino->numeroCheque,
                    'PAGO A PROVEEDOR - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                    substr($cfdi->comprobante->fecha,-2,2)
                ));
            }
            else if($GLOBALS['provision_type'] == 2){
                $sheet->row($GLOBALS['row_index'], array(
                    ProviderPaymentPolicyController::setPolicyType($cfdi->comprobante->formaPago),
                    $cfdi->datosDestino->numeroCheque,
                    'PAGO DE HONORARIOS - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                    substr($cfdi->comprobante->fecha,-2,2)
                ));
            }
            $GLOBALS['cfdi_index_serie'] = $GLOBALS['cfdi_index_serie']-1;
        }
        else{
            if($GLOBALS['provision_type'] == 1){
                $sheet->row($GLOBALS['row_index'], array(
                    ProviderPaymentPolicyController::setPolicyType($cfdi->comprobante->formaPago),
                    $GLOBALS['cfdi_index_serie'],
                    'PAGO A PROVEEDOR - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                    substr($cfdi->comprobante->fecha,-2,2)
                ));
            }
            else if($GLOBALS['provision_type'] == 2){
                $sheet->row($GLOBALS['row_index'], array(
                    ProviderPaymentPolicyController::setPolicyType($cfdi->comprobante->formaPago),
                    $GLOBALS['cfdi_index_serie'],
                    'PAGO DE HONORARIOS - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                    substr($cfdi->comprobante->fecha,-2,2)
                ));
            }
        }
        
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        $GLOBALS['cfdi_index_serie'] = $GLOBALS['cfdi_index_serie']+1;
        ProviderPaymentPolicyController::generatePolicyProviderItem($sheet, $cfdi);
    }

    public function generatePolicyProviderItem($sheet, $cfdi){
        if($GLOBALS['provision_type'] == 1){
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $cfdi->proveedor->cuentaContable[0],
                '0',
                'PAGO A PROVEEDOR - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                $cfdi->comprobante->total,
                '',
                '0',
                '0'
            ));
        }
        else if($GLOBALS['provision_type'] == 2){
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $cfdi->proveedor->cuentaContable[0],
                '0',
                'PAGO DE HONORARIOS - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                $cfdi->comprobante->subtotal*1.16,
                '',
                '0',
                '0'
            ));
        }       

        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ProviderPaymentPolicyController::generatePolicyItemRows($sheet, $cfdi);
        ProviderPaymentPolicyController::generatePolicyPaidVatItem($sheet, $cfdi);
    }

    public function generatePolicyPaidVatItem($sheet, $cfdi) {
        if($GLOBALS['provision_type'] == 1){
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $GLOBALS['company'][0]->paid_creditable_vat_account,
                '0',
                'PAGO A PROVEEDOR - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                round(($cfdi->comprobante->total/1.16)*.16, 2),
                '',
                '0',
                '0'
            ));
        }
        else if($GLOBALS['provision_type'] == 2){
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $GLOBALS['company'][0]->paid_creditable_vat_account,
                '0',
                'PAGO DE HONORARIOS - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                round(($cfdi->comprobante->subtotal)*.16, 2),
                '',
                '0',
                '0'
            ));
        }   
            
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ProviderPaymentPolicyController::generatePolicyItemRows($sheet, $cfdi);
        ProviderPaymentPolicyController::generatePolicyPendingVatItem($sheet, $cfdi);
    }

    public function generatePolicyPendingVatItem($sheet, $cfdi) {
        if($GLOBALS['provision_type'] == 1){
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $GLOBALS['company'][0]->pending_creditable_vat_account,
                '0',
                'PAGO A PROVEEDOR - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                '',
                round(($cfdi->comprobante->total/1.16)*.16, 2),
                '0',
                '0'
            ));

            $GLOBALS['row_index']=$GLOBALS['row_index']+1;
            ProviderPaymentPolicyController::generatePolicyItemRows($sheet, $cfdi);
            ProviderPaymentPolicyController::generatePolicyBankAccountItem($sheet, $cfdi);
        }
        else if($GLOBALS['provision_type'] == 2){
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $GLOBALS['company'][0]->pending_creditable_vat_account,
                '0',
                'PAGO DE HONORARIOS - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                '',
                round(($cfdi->comprobante->subtotal)*.16, 2),
                '0',
                '0'
            ));

            $GLOBALS['row_index']=$GLOBALS['row_index']+1;
            ProviderPaymentPolicyController::generatePolicyItemRows($sheet, $cfdi);
            ProviderPaymentPolicyController::generatePolicyFeesRetentionItem($sheet, $cfdi);
        }
    }

    public function generatePolicyFeesRetentionItem($sheet, $cfdi){
        $sheet->row($GLOBALS['row_index'], array(
            '',
            $GLOBALS['company'][0]->fees_retention_isr_account,
            '0',
            'PAGO DE HONORARIOS - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            '',
            round($cfdi->comprobante->subtotal*.10,2),
            '0',
            '0'
        ));

        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ProviderPaymentPolicyController::generatePolicyItemRows($sheet, $cfdi);
        ProviderPaymentPolicyController::generatePolicyVatRetentionItem($sheet, $cfdi);
    }

    public function generatePolicyVatRetentionItem($sheet, $cfdi){
        $sheet->row($GLOBALS['row_index'], array(
            '',
            $GLOBALS['company'][0]->fees_retention_vat_account,
            '0',
            'PAGO DE HONORARIOS - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            '',
            round($cfdi->comprobante->subtotal*.106667,2),
            '0',
            '0'
        ));

        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ProviderPaymentPolicyController::generatePolicyItemRows($sheet, $cfdi);
        ProviderPaymentPolicyController::generatePolicyBankAccountItem($sheet, $cfdi);
    }

    public function generatePolicyBankAccountItem($sheet, $cfdi) {
        if($GLOBALS['provision_type'] == 1){
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $cfdi->datosOrigen->cuentaContableOrigen,
                '0',
                'PAGO A PROVEEDOR - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                '',
                $cfdi->comprobante->total,
                '0',
                '0'
            ));
        }
        elseif($GLOBALS['provision_type'] == 2){
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $cfdi->datosOrigen->cuentaContableOrigen,
                '0',
                'PAGO DE HONORARIOS - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                '',
                $cfdi->comprobante->total,
                '0',
                '0'
            ));
        }
            
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ProviderPaymentPolicyController::generatePolicyBankAccountRows($sheet, $cfdi);

        if(PolicyTrait::validateNextCfdi()){
            $GLOBALS['cfdi_key']=$GLOBALS['cfdi_key']+1;
            PolicyTrait::writePolicyFooter($sheet);
            $GLOBALS['row_index']=$GLOBALS['row_index']+1;
            ProviderPaymentPolicyController::generatePolicy($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
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

        $provider = Provider::with('bank')->where([
            ['company_id', '=', session()->get('company_workspace_id')],
            ['provider_rfc', '=', $cfdi->emisor->rfcEmisor]
        ])->first(); 

        if(is_null($provider->bank_id)){
            $bancoDestino=0;
        }
        else{
            $bancoDestino=$provider->bank->bank_sat_key;
        }
        if(is_null($provider->provider_bank_account_number)){
            $cuentaDestino=0;
        }
        else{
            $cuentaDestino=$provider->provider_bank_account_number;
        }

        $sheet->row($GLOBALS['row_index'], array(
            '',
            '',
            'INICIO_INFOPAGO'
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;

        $sheet->setColumnFormat(array(
            'D' => '@',
            'E' => '#0',
            'L' => '#0',
            'K' => '@'
        ));

        $sheet->row($GLOBALS['row_index'], array(
            '',
            '',
            $diaFecha."/".$mesFecha."/".$añoFecha,
            $cfdi->datosOrigen->bancoOrigen,
            $cfdi->datosOrigen->cuentaBancariaOrigen,
            $cfdi->comprobante->formaPago,
            $cfdi->datosDestino->numeroCheque,
            $cfdi->comprobante->total,
            $cfdi->emisor->rfcEmisor,
            $cfdi->emisor->nombreEmisor,
            $bancoDestino,
            $cuentaDestino
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        
        $sheet->row($GLOBALS['row_index'], array(
            '',
            '',
            'FIN_INFOPAGO'
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
    }

    public function setPolicyType($payment_form){
        if($payment_form=='02'){
            return "Ch";
        }
        else if($payment_form=='03'){
            return "Tr";
        }
        else{
            return "Ef";
        }
    }
}