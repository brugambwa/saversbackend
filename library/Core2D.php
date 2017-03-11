<?php

class Core2D {

    var $_serverip = '41.190.84.148';
    var $_port = '50525';
    var $_apikey = 'A0B1C2D3E4G3';
    var $_timeout = 30;
    var $_sessionID;

    function __construct() {
        
    }

    public function processPayment($order) {
        $sk = fsockopen($this->_serverip, $this->_port, $errnum, $errstr, $this->_timeout);
        if (!is_resource($sk)) {
            wc_add_notice('Connection Error', 'error');
        } else {
            $signOnVars = $this->signOn($sk);
            $this->_sessionID = $signOnVars->data->session;
            $transactionDetails = $this->requestPaymentCode($sk, $order);
            $transactionCode = (int) $transactionDetails->data->code;
            print_r($transactionDetails);
            fclose($sk);
            die();
            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url($order)
            );
        }
    }

    public function process2DRequest($request) {
        $sk = fsockopen($this->_serverip, $this->_port, $errnum, $errstr, $this->_timeout);
        if (!is_resource($sk)) {
            wc_add_notice('Connection Error', 'error');
        } else {
            $signOnVars = $this->signOn($sk);
            $this->_sessionID = $signOnVars->data->session;
            if ($signOnVars->resultcode == 000) {
                //Call Any Method
                $response = $this->{$request['request_type']}($sk, $request);
            } else {
                
            }
            fclose($sk);
        }
    }

    public function signOn($sk) {
        $data['apikey'] = $this->_apikey;
        $data['today'] = date("y-m-d h:i:s");
        $data['stan'] = round(microtime(true) * 100);

        $hashThis = $data['stan'] . $data['today'] . "SignOn" . $data['apikey'];
        $data['enc_string'] = md5($hashThis);

        $calldata = $this->WriteGeneralXMLFile('signon', $data);
        $s_response = $this->make2DRequest($sk, $calldata);

        return $s_response;
    }

    public function ping2D($sk) {
        $data['today'] = date("y-m-d h:i:s");
        $data['stan'] = round(microtime(true) * 100);

        $hashThis = $data['stan'] . $data['today'] . "Ping";
        $data['enc_string'] = md5($hashThis);

        $calldata = $this->WriteGeneralXMLFile('ping', $data);
        $s_response = $this->make2DRequest($sk, $calldata);
        return $s_response;
    }

    private function requestPaymentCode($sk, $order) {
        $data['sessionid'] = $this->_sessionID;
        $data['today'] = date("y-m-d h:i:s");
        $data['stan'] = round(microtime(true) * 100);
        $data['amount'] = number_format($order['amount'], 2, '', '');
        $data['description'] = 'Order Number ' . $order['order_id'];

        $hashThis = $data['stan'] . $data['today'] . "2D" . $data['sessionid'] . "New" . $data['amount'] . "" . $data['description'];
        $data['enc_string'] = md5($hashThis);

        $calldata = $this->WriteGeneralXMLFile('request_code', $data);

        $s_response = $this->make2DRequest($sk, $calldata);

        return $s_response;
    }

    public function codeStatusCheck($sk, $code) {
        $data['sessionid'] = $this->_sessionID;
        $data['today'] = date("y-m-d h:i:s");
        $data['stan'] = round(microtime(true) * 100);

        $hashThis = $data['stan'] . $data['today'] . "2D" . $data['sessionid'] . "Find" . $data['paymentcode'];
        $data['enc_string'] = md5($hashThis);

        $calldata = $this->WriteGeneralXMLFile('status', $data);
        $s_response = $this->make2DRequest($sk, $calldata);

        return $s_response;
    }

    public function payCode($sk) {
        $data['today'] = date("y-m-d h:i:s");
        $data['stan'] = round(microtime(true) * 100);

        $hashThis = $data['stan'] . $data['today'] . "2D" . $data['sessionid'] . $data['amount'] . "Pay" . $data['paymentcode'];
        $data['enc_string'] = md5($hashThis);

        $calldata = $this->WriteGeneralXMLFile('pay', $data);
        $s_response = $this->make2DRequest($sk, $calldata);
        return $s_response;
    }

    public function findCode($sk) {
        $data['today'] = date("y-m-d h:i:s");
        $data['stan'] = round(microtime(true) * 100);

        $hashThis = $data['stan'] . $data['today'] . "2D" . $data['sessionid'] . "Find" . $data['paymentcode'];
        $data['enc_string'] = md5($hashThis);

        $calldata = $this->WriteGeneralXMLFile('find', $data);
        $s_response = $this->make2DRequest($sk, $calldata);
        return $s_response;
    }

    public function endOfDay($sk) {
        $data['today'] = date("y-m-d h:i:s");
        $data['stan'] = round(microtime(true) * 100);

        $hashThis = $hashThis = $data['stan'] . $data['today'] . "2D" . $data['sessionid'] . "EOD";
        $data['enc_string'] = md5($hashThis);

        $calldata = $this->WriteGeneralXMLFile('eod', $data);
        $s_response = $this->make2DRequest($sk, $calldata);
        return $s_response;
    }

    public function signOff($sk) {
        $data['apikey'] = $this->_apikey;
        $data['today'] = date("y-m-d h:i:s");
        $data['stan'] = round(microtime(true) * 100);

        $hashThis = $data['stan'] . $data['today'] . "SignOff" . $data['sessionid'];
        $data['enc_string'] = md5($hashThis);

        $calldata = $this->WriteGeneralXMLFile('signon', $data);
        $s_response = $this->make2DRequest($sk, $calldata);

        return $s_response;
    }

    function make2DRequest($sk, $data) {
        $byte0 = ((strlen($data) >> 24) & 0xFF);
        $byte1 = ((strlen($data) >> 16) & 0xFF);
        $byte2 = ((strlen($data) >> 8) & 0xFF);
        $byte3 = (strlen($data) & 0xFF);

        fwrite($sk, chr($byte0));
        fflush($sk);
        fwrite($sk, chr($byte1));
        fflush($sk);
        fwrite($sk, chr($byte2));
        fflush($sk);
        fwrite($sk, chr($byte3));

        fputs($sk, $data);
        fflush($sk);

        sleep(1);

        $rbyte0 = fread($sk, 1);
        $rbyte1 = fread($sk, 1);
        $rbyte2 = fread($sk, 1);
        $rbyte3 = fread($sk, 1);
        $readVal = (ord($rbyte0) << 24) + (ord($rbyte1) << 16) + (ord($rbyte2) << 8) + ord($rbyte3);

        $read = 0;
        $readStr = "";
        while ($read < $readVal) {
            $readStr .= fread($sk, 1);
            $read++;
        }
        $s_response = simplexml_load_string($readStr);

        return $s_response;
    }

    function WriteGeneralXMLFile($temp, $req_data) {
        $f_template = $temp;
        $template = 'templates/2drequests/' . $f_template . '.php';
        require($template);
        $trans_xml = ${$f_template};
        return $trans_xml;
    }

}
