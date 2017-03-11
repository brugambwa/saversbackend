<?php
$mtn_getfri_resp = '<?xml version="1.0" encoding="UTF-8"?><ns2:getfinancialresourceinformationresponse xmlns:ns2="http://www.ericsson.com/em/emm/sp/backend">
    <extension>
        <amount_due>' . $trans_data['amountdue'] . '</amount_due>
        <minimum_amount>' . $trans_data['minimumamount'] . '</minimum_amount>
        <student_name>' . $trans_data['accountname'] . '</student_name>
        <year>2014</year>
        <class>Senior 1</class>
        <period>TERM 1</period>
        <ThirdPartyTransactionID>92</ThirdPartyTransactionID>
        <TransId>240000204</TransId>
        <statuscode>' . $trans_data['pay_serv_resp_code'] . '</statuscode>
    </extension>
    <message>' . $trans_data['responsemsg'] . '</message>
</ns2:getfinancialresourceinformationresponse>';
?>
