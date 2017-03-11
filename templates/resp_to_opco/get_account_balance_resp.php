<?php
$get_account_balance_resp = '<?xml version="1.0" encoding="UTF-8"?>
<operationResult type="getaccountbalanceresponse">
    <response>
                <requestid>' . $trans_data['requestid'] . '</requestid>
                <responsecode>' . $trans_data['pay_serv_resp_code'] . '</responsecode>
                <responsemsg>' . $trans_data['responsemsg'] . '</responsemsg>
                <accountref>' . $trans_data['accountid'] . '</accountref>
                <clientname>' . $trans_data['accountname'] . '</clientname>
                <invoicenumber>' . $trans_data['invoicenumber'] . '</invoicenumber>
                <amount>' . $trans_data['accountbalance'] . '</amount>
        </response>
    </operationResult>';
?>