<?php

class Standardizer {

    public $_match_up_array = array(
        'responsecode' => 'responsecode',
        'responsemsg' => 'responsemsg',
        'payrefno' => 'pay_serv_transaction_id',
        'paymentref' => 'merchant_payment_ref',
        'requesttype' => 'requesttype',
        'type' => 'requesttype',
        'requestid' => 'requestid',
        'serviceid' => 'service_id',
        'transactionid' => 'aggreg_transaction_id',
        'accountnumber' => 'pay_serv_account',
        'vendor' => 'vendor',
        'merchantref' => 'merchant_ref',
        'accountref' => 'pay_account_ref',
        'amount' => 'transaction_amount',
        'broadcasterid' => 'broadcaster',
        'cardnumber' => 'card_number',
        'bouquetid' => 'bouquet',
        'paymentreason' => 'bouquet',
        'userid' => 'pay_serv_id',
        'apikey' => 'api_key',
        'statuscode' => 'responsecode',
    );

    function __construct() {
        
    }
    
    function ParseXMLFromURL($url){
        $xmlp = simplexml_load_file($url);
        $p_array = $this->ObjectToArray($xmlp);
        return $p_array;
    }

    function ParseXMLRequest($xml_post, $level = false, $source = false, $serv_id = false) {
        if ($level) {
            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($xml_post);
            libxml_clear_errors();
            $xmln = $doc->saveXML($doc->documentElement);
        } else {
            $xmln = $xml_post;
        }
        $xmlp = simplexml_load_string($xmln);
        $p_array = $this->ObjectToArray($xmlp);
        $request_array = $this->ArrayFlattener($p_array);
        $standard_array = $this->Standardize($request_array);
        return $standard_array;
    }

    function Standardize($data_array) {
        //Convert to Single
        $result_array = array();
        foreach ($data_array as $key => $value) {
            $standard_key = $this->_match_up_array[$key];
            if (!empty($standard_key)) {
                $result_array[$standard_key] = $value;
            }
        }
        return $result_array;
    }

    function ObjectToArray($obj) {
        if (!is_array($obj) && !is_object($obj))
            return $obj;
        if (is_object($obj))
            $obj = get_object_vars($obj);
        return array_map(__METHOD__, $obj);
    }

    function ArrayFlattener($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->ArrayFlattener($value));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

}
