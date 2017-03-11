<?php

class Merchant_Model extends Model {

    function __construct() {
        parent::__construct();
    }
    
    function ParseRequest($xml_post) {
        $standard_array = $this->stan->ParseXMLRequest($xml_post);
        return $standard_array;
    }
    
    function ProcessRequest($xml, $request_array){
        
    }
    
    function Test(){
        $order['order_id'] = 1234560;
        $order['amount'] = 300;
        $order['description'] = "Test Order One";
        $this->tdc->processPayment($order);
    }
    
    function NewTest(){
        $order['order_id'] = 1234560;
        $order['amount'] = 300;
        $order['description'] = "Test Order One";
        $this->tdc2->process_payment($order);
    }

}

