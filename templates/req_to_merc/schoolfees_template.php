<?php
$schoolfees_template = '<thirdpartypayment>
            <vendor>MVend</vendor> 
            <apikey>'.$processing_rules[0]['service_api_key'].'</apikey>
            <userid>'.$data['user_id'].'</userid> 
            <transaction>
                <transactionid>' . $trans_data['transaction_id'] . '</transactionid>
                <accountnumber>' . $trans_data['pay_serv_account'] . '</accountnumber>
                <accountref>' . $trans_data['pay_account_ref'] . '</accountref> 
                <amount>' . $trans_data['transaction_amount'] . '</amount>
                <payrefno>' . $trans_data['pay_serv_trans_ref'] . '</payrefno>
                <payservaccountno>' . $trans_data['pay_serv_account'] . '</payservaccountno>
            </transaction>
         </thirdpartypayment>';



