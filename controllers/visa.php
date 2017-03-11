<?php

class Visa extends Controller {

    function __construct() {
        parent::__construct();
    }

    function Index() {
        echo 'Visa Tests:<hr/>';
        $authString = $userId . ":" . $password;
        $authStringBytes = utf8_encode($authString);
        $authloginString = base64_encode($authStringBytes);
        $authHeader = "Authorization:Basic " . $authloginString;
        $header = (array("Accept: application/json", "Content-Type: application/json", $authHeader));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSLCERT, $certificatePath);
        curl_setopt($ch, CURLOPT_SSLKEY, $privateKey);
        $content = curl_exec($ch);
        echo $content;
    }

}
