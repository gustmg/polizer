<?php

namespace App\Traits;
use App\UserProcessedXml;

trait PolicyTrait
{
    public static function writePolicyFooter ($sheet){
        $sheet->row($GLOBALS['row_index'], array(
            '',
            'FIN_PARTIDAS'
        ));
    }

    public static function validateNextCfdi() {
        if($GLOBALS['cfdi_key']+1 < count($GLOBALS['jsonFiles'])){
            return true;
        }
        else{
            return false;
        }
    }

    public static function generatePolicyItemRows($sheet, $cfdi) {
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

    public static function formateaDescripcion ($descripcion){
        $len_descripcion=strlen($descripcion);
        $concepto=substr($descripcion,-$len_descripcion,20);
        return $concepto;
    }

    public static function comparePrevRfcProvider() {
        if($GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']-1]->emisor->rfcEmisor === $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]->emisor->rfcEmisor){
            return true;
        }
        else{
            return false;
        }
    }

    public static function comparePrevRfcClient() {
        if($GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']-1]->receptor->rfcReceptor === $GLOBALS['jsonFiles'][$GLOBALS['cfdi_key']]->receptor->rfcReceptor){
            return true;
        }
        else{
            return false;
        }
    }

    public static function saveUserProcessedXML($user_id, $company_id, $xml_amount){
        $user_processed_xml = new UserProcessedXml;
        $user_processed_xml->user_id = $user_id;
        $user_processed_xml->company_id = $company_id;
        $user_processed_xml->processed_at = now();
        $user_processed_xml->xml_amount = $xml_amount;
        $user_processed_xml->save();
    }
}
