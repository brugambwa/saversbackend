<?php
$mtn_req_pay_resp = '<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
    <soapenv:Body>
        <requestPaymentCompletedResponse xmlns="http://www.csapi.org/schema/momopayment/local/v1_0">
            <result>
                <resultCode xmlns="">'.$data['resultCode'].'</resultCode>
                <resultDescription xmlns="">'.$data['resultDescription'].'</resultDescription>
            </result>
            <extensionInfo>
                <item xmlns="">
                    <key>result</key>
                    <value>'.$data['resultDescription'].'</value>
                </item>
            </extensionInfo>
        </requestPaymentCompletedResponse>
    </soapenv:Body>
</soapenv:Envelope>';

