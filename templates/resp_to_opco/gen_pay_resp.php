<?php
$gen_pay_resp = '<?xml version="1.0" encoding="UTF-8"?><operationResult>
        <response>
                <responsecode>' . $trans_data['pay_serv_resp_code'] . '</responsecode>
                <responsemsg>' . $trans_data['processing_response'] . '</responsemsg>
                <transactionid>' . $trans_data['pay_serv_trans_ref'] . '</transactionid>
                <paymentref>' . $trans_data['transaction_id'] . '</paymentref>
        </response>
    </operationResult>';        
