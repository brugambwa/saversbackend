<?php
$sf_get_account_info_resp = '<?xml version="1.0" encoding="UTF-8"?>
<operationResult type="getaccountinformationresponse">
    <response>
        <requestid>' . $trans_data['requestid'] . '</requestid>
        <responsecode>' . $trans_data['pay_serv_resp_code'] . '</responsecode>
        <responsemsg>' . $trans_data['responsemsg'] . '</responsemsg>
        <merchantref>' . $trans_data['merchantid'] . '</merchantref>
        <accountref>' . $trans_data['accountid'] . '</accountref>
        <merchantname>' . $trans_data['merchantname'] . '</merchantname>
        <clientname>' . $trans_data['accountname'] . '</clientname>
        <accountbalance>' . $trans_data['accountbalance'] . '</accountbalance>
    </response>
</operationResult>';
?>
