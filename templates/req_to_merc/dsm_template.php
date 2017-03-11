<?php
$dsm_template = '
    <thirdpartypayment>
            <vendor>MVend</vendor> 
            <apikey>'.$processing_rules[0]['service_api_key'].'</apikey>
            <userid>'.$processing_rules[0]['service_user_id'].'</userid> 
            <transaction>
               <transactionid>' . $trans_data['transaction_id'] . '</transactionid>
               <broadcasterid>' . $trans_data['service_name'] . '</broadcasterid>
                <cardnumber>' . $trans_data['serv_account_ref'] . '</cardnumber> 
                <bouquetid>' . $trans_data['serv_payment_ref'] . '</bouquetid> 
                <amount>' . $trans_data['transaction_amount'] . '</amount>
                <payrefno>' . $trans_data['pay_serv_transaction_id'] . '</payrefno>
            </transaction>
         </thirdpartypayment>';

