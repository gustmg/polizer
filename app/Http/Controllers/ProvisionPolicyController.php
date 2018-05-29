<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\AccountingAccount;
use App\AccountingAccountType;
use App\Company;
use App\Provider;
use View;
use Session;
use Excel;

class ProvisionPolicyController extends Controller
{
    public function index(){
    	$providers=Provider::with('counterpart_account')->where('company_id', session()->get('company_workspace_id'))->get();
    	$companies=Company::where('user_id', Auth::user()->id)->get();
    	$accounting_account_types=AccountingAccountType::all();
    	$accounting_accounts=AccountingAccount::where(function($query){
    	    $query->where('accounting_account_type_id', 2)
    	    ->orWhere('accounting_account_type_id', 5)
    	    ->orWhere('accounting_account_type_id', 6);
    	})->where('company_id', session()->get('company_workspace_id'))->get();
    	
    	return view::make('provision_policy.index',['providers'=>$providers,'companies'=>$companies,'accounting_accounts'=>$accounting_accounts,'accounting_account_types'=>$accounting_account_types]);
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
            $GLOBALS['provision_type'] = $request->provisionType;
            $GLOBALS['generate_by_provider'] = $request->generateByProvider;;
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
            
            $file_name = 'DR-'.date("Y-m-d-H-i-s").'-'.Session::get('company_workspace');

            Excel::create($file_name, function($excel){
                $excel->sheet('Libro 1', function($sheet) {
                    ProvisionPolicyController::generatePolicy($sheet, $GLOBALS['jsonFiles'][0]);
                });
            })->store('xlsx', storage_path('app/public'));
            //})->store('xlsx', public_path('storage'));

            $url = Storage::url($file_name.'.xlsx');
            //$url = 'https://www.polizer.mx/polizer_app/storage/'.$file_name.'.xlsx';
            return $url;
        }
    }

    public function generatePolicy($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            'Dr',
            $GLOBALS['cfdi_index_serie'],
            'CREACIÓN DE PASIVO - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
            substr($cfdi->comprobante->fecha,-11,2)
        ));
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        $GLOBALS['cfdi_index_serie'] = $GLOBALS['cfdi_index_serie']+1;
        ProvisionPolicyController::generatePolicyItem($sheet, $cfdi);
    }

    public function generatePolicyItem($sheet, $cfdi) {
        if($GLOBALS['provision_type']==1 || $GLOBALS['provision_type']==3 || $GLOBALS['provision_type']==4){ //STANDARD AND HONORARIUM PROVISION
            foreach($cfdi->concepto->descripciones as $key => $concepto){
                $sheet->row($GLOBALS['row_index'], array(
                    '',
                    $cfdi->concepto->contrapartidas[$key],
                    '0',
                    'CREACIÓN DE PASIVO - '.ProvisionPolicyController::formateaDescripcion($cfdi->concepto->descripciones[$key]).' - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                    '1',
                    $cfdi->concepto->importes[$key],
                    '',
                    '0',
                    '0'
                ));
                
                $GLOBALS['row_index']=$GLOBALS['row_index']+1;
                ProvisionPolicyController::generatePolicyItemRows($sheet, $cfdi);
            }

            ProvisionPolicyController::generatePolicyVatItem($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
        }
        else if($GLOBALS['provision_type']==2){ //IEPS PROPVISION
            $GLOBALS['ieps'] = number_format((float)$cfdi->comprobante->total-($cfdi->traslado->importe/0.16)-$cfdi->traslado->importe, 2, '.', '');

            foreach($cfdi->concepto->descripciones as $key => $concepto){
                $sheet->row($GLOBALS['row_index'], array(
                    '',
                    $cfdi->concepto->contrapartidas[$key],
                    '0',
                    'CREACIÓN DE PASIVO - '.ProvisionPolicyController::formateaDescripcion($cfdi->concepto->descripciones[$key]).' - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                    '1',
                    number_format((float)$cfdi->concepto->importes[$key]-$GLOBALS['ieps'], 2, '.', ''),
                    '',
                    '0',
                    '0'
                ));

                $GLOBALS['row_index']=$GLOBALS['row_index']+1;
                ProvisionPolicyController::generatePolicyItemRows($sheet, $cfdi);
            }

            ProvisionPolicyController::generatePolicyIepsItem($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
        }
    }

    public function generatePolicyVatItem($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            '',
            $GLOBALS['company'][0]->pending_creditable_vat_account,
            '0',
            'CREACIÓN DE PASIVO - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            $cfdi->traslado->importe,
            '',
            '0',
            '0'
        ));
            
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ProvisionPolicyController::generatePolicyItemRows($sheet, $cfdi);
        ProvisionPolicyController::generatePolicyProviderItem($sheet, $cfdi);
    }

    public function generatePolicyIepsItem($sheet, $cfdi) {
        $sheet->row($GLOBALS['row_index'], array(
            '',
            $cfdi->concepto->contrapartidas[$key],
            '0',
            'CREACIÓN DE PASIVO - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
            '1',
            $GLOBALS['ieps'],
            '',
            '0',
            '0'
        ));

        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ProvisionPolicyController::generatePolicyItemRows($sheet, $cfdi);
        ProvisionPolicyController::generatePolicyVatItem($sheet, $cfdi);
    }

    public function generatePolicyProviderItem($sheet, $cfdi) {
        if($GLOBALS['provision_type']==1 || $GLOBALS['provision_type']==2){ //STANDARD AND IEPS PROVISION
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $cfdi->proveedor->cuentaContable[0],
                '0',
                'CREACIÓN DE PASIVO - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                '',
                $cfdi->comprobante->total,
                '0',
                '0'
            ));
        }
        else if($GLOBALS['provision_type']==3){ //HONORARIUM PROVISION
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $cfdi->proveedor->cuentaContable[0],
                '0',
                'CREACIÓN DE PASIVO - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                '',
                $cfdi->comprobante->subtotal + $cfdi->traslado->importe,
                '0',
                '0'
            ));
        }
        else if($GLOBALS['provision_type']==4){ //FREIGHT PROVISION
            $sheet->row($GLOBALS['row_index'], array(
                '',
                $cfdi->proveedor->cuentaContable[0],
                '0',
                'CREACIÓN DE PASIVO - '.$cfdi->emisor->nombreEmisor.' -  CFDI: '.$cfdi->comprobante->folio,
                '1',
                '',
                $cfdi->comprobante->total + ($cfdi->concepto->importes[0]*.04),
                '0',
                '0'
            ));
        }

        
        $GLOBALS['row_index']=$GLOBALS['row_index']+1;
        ProvisionPolicyController::generatePolicyItemRows($sheet, $cfdi);

        if(ProvisionPolicyController::validateNextCfdi()){
            $GLOBALS['cfdi_key']=$GLOBALS['cfdi_key']+1;
            if($GLOBALS['generate_by_provider']=='1'){
                if(ProvisionPolicyController::comparePrevRfc()){
                    ProvisionPolicyController::generatePolicyItem($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
                }
                else{
                    ProvisionPolicyController::writePolicyFooter($sheet);
                    $GLOBALS['row_index']=$GLOBALS['row_index']+1;
                    ProvisionPolicyController::generatePolicy($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
                }
            }
            else{
                ProvisionPolicyController::writePolicyFooter($sheet);
                $GLOBALS['row_index']=$GLOBALS['row_index']+1;
                ProvisionPolicyController::generatePolicy($sheet, $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]);
            }
        }
        ProvisionPolicyController::writePolicyFooter($sheet);
    }

    public function generatePolicyItemRows($sheet, $cfdi) {
        $diaFecha=substr($cfdi->comprobante->fecha,-11,2);
        $mesFecha=substr($cfdi->comprobante->fecha,-14,2);
        $añoFecha=substr($cfdi->comprobante->fecha,-19,4);

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

    public function formateaDescripcion ($descripcion){
        $len_descripcion=strlen($descripcion);
        $concepto=substr($descripcion,-$len_descripcion,20);
        return $concepto;
    }

    public function writePolicyFooter ($sheet){
        $sheet->row($GLOBALS['row_index'], array(
            '',
            'FIN_PARTIDAS'
        ));
    }

    public function validateNextCfdi() {
        if($GLOBALS['cfdi_key']+1 < count($GLOBALS['jsonFiles'])){
            return true;
        }
        else{
            return false;
        }
    }

    public function comparePrevRfc() {
        if($GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']-1]->emisor->rfcEmisor === $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]->emisor->rfcEmisor){
            return true;
        }
        else{
            return false;
        }
    }
}