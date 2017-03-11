<?php

$get_account_balance = '<getaccountbalance>
         <vendor>MVend</vendor> 
            <apikey>8fe5a484312df9bc5b19e072246366c8fc6413f31</apikey>
            <query>
			<merchantref>' . $trans_data['merchant_ref'] . '</merchantref>
			<accountref>' . $trans_data['pay_account_ref'] . '</accountref>
                </query>
         </getaccountbalance>';

