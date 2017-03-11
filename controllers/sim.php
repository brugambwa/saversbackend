<?php

class Sim extends Controller {

    function __construct() {
        parent::__construct();
        define('PAYT_SVC_LOCATION', 'http://172.25.48.43:8323/mom/mt/paymentcompleted');
        $this->_client = new SoapClient('webservices/requestpaymentcompleted_service_1_0.wsdl', array('trace' => 1, 'soap_version' => 'SOAP_1_2', 'location' => PAYT_SVC_LOCATION));

        $auth = array(
            'Username' => '2500110003063',
            'PasswordDigest' => 'OOTFyyXF3/ptO6pszgmHr9sFCYo=',
            'Nonce' => 'WScqanjCEAC4mQoBE07sAQ==',
            'Created' => '2012-07-26T11:31:26',
        );
        $headers = new SoapHeader("http://schemas.xmlsoap.org/wsdl/", 'UsernameToken', $auth);

        $this->_client->__setSoapHeaders(array($headers));
    }

    public function index() {
        $request = array(
            'ProcessingNumber' => '458750',
            'MOMTransactionID' => '458750',
            'StatusCode' => '01',
            'StatusDesc' => 'COMPLETED',
            'ThirdPartyAcctRef' => '',
            'NamedParameterList' => '',
        );
        try {
            $resp = $this->_client->requestPaymentCompleted(array('requestPaymentCompleted' => $request));
            print_r($resp);
        } catch (Exception $e) {
            print_r($e);
        }
    }

    function PaymentCompleted() {

        $header = array('Content-Type: text/xml',
            'Authorization: WSSE realm="SDP",profile="UsernameToken"',
            'X-WSSE:UsernameToken Username="2500110002907",PasswordDigest="vyolxYPbT5nW8jV6GMaXyBZxwJU=",Nonce="66C92B11FF8A425FB8D4CCFE0ED9ED1F",Created="2016-01-08T12:08:25"',
            'X-RequestHeader: request ServiceId="",TransId="",LinkId="",FA=""',
            'Signature: 43AD232FD45FF',
            'Cookie: sessionid=default8fcee064690b45faa9f8f6c7e21c5e5a',
            'Msisdn: 250788386428',
            'X-HW-Extension: k1=v1;k2=v2'
        );
        $xmls = '<ns2:paymentcompletedrequest xmlns:ns2="http://www.ericsson.com/em/emm/sp/backend" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <transactionid>458750</transactionid>
   <providertransactionid>458750</providertransactionid>
   <status>COMPLETED</status>
</ns2:paymentcompletedrequest>';
        //$url = 'http://172.25.48.43:8323/mom/mt/gettransactionstatus';
        $url2 = 'http://172.25.48.43:8323/mom/mt/paymentcompleted';
        $this->SendByCURL($url2, $xmls, $header);
    }

    function SendByCURL($url, $xml, $header) {
        echo 'Making Request to ' . $url . ' With XML String ' . $xml . '<hr/>';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        $content = curl_exec($ch);

        if (!curl_errno($ch)) {
            $info = curl_getinfo($ch);
            echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'] . '<hr/>';
        } else {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);
    }

}
