<?php

class Model {

    function __construct() {
        $this->db = new Database();
        $this->log = new Log();
        $this->tdc = new Core2D();
    }

    function MakeKey() {
        $key = rand(1, 9999999999999) . rand(1, 9999999999999);
        return $key;
    }

    function ServicePairing($sid, $psid) {
        return $this->db->SelectData("SELECT * FROM mvd_pay_serv_pairing WHERE service_id=:sv 
                AND pay_serv_id=:psid", array('sv' => $sid, 'psid' => $psid));
    }

    function GetTransaction($id) {
        return $this->db->SelectData("SELECT * FROM mvd_payment_transactions t JOIN mvd_aggregated_services s ON
		t.service_id = s.service_id WHERE transaction_id=:tid", array('tid' => $id));
    }

    function GetTransactionByPSPID($tid) {
        return $this->db->SelectData("SELECT * FROM mvd_payment_transactions t JOIN mvd_aggregated_services s ON
		t.service_id = s.service_id WHERE pay_serv_transaction_id=:tid", array('tid' => $tid));
    }

    function GetTransactionByMercID($id) {
        return $this->db->SelectData("SELECT * FROM mvd_payment_transactions t JOIN mvd_aggregated_services s ON
		t.service_id = s.service_id WHERE pay_serv_transaction_id=:tid", array('tid' => $id));
    }

    function TransactionStatusCheck($id) {
        return $this->db->SelectData("SELECT * FROM mvd_payment_transactions
		WHERE psp_reconciliation_status=0 AND pay_serv_id=:tid", array('tid' => $id));
    }

    function ProcessingRules($request, $pt, $rt) {
        return $this->db->SelectData("SELECT * FROM mvd_aggreg_service_parameters WHERE service_id=:sv 
                AND pay_serv_id=:psid AND parameter_type=:pt AND request_type=:rt", array('sv' => $request['service_id'], 'psid' => $request['pay_serv_id'], 'pt' => $pt, 'rt' => $rt));
    }

    function BillingRules($sid, $psid, $amount) {
        return $this->db->SelectData("SELECT * FROM mvd_service_billing_rules WHERE serv_id=:sv
                AND pay_serv_id=:psid AND :amt BETWEEN min_amount AND max_amount", array('sv' => $sid, 'psid' => $psid, 'amt' => $amount));
    }

    function GetTransactionLogFileIndex($tid) {
        return $this->db->SelectData("SELECT * FROM sm_log_file_index WHERE transaction_id=:tid", array('tid' => $tid));
    }

    function GetResponseKeySet($request, $rid) {
        $variables = array('pspid' => $request['pay_serv_id'], 'sid' => $request['service_id'], 'src' => $rid);
        return $this->db->SelectData("SELECT * FROM mvd_service_responses WHERE 
                    pay_serv_id =:pspid AND service_id=:sid AND merch_resp_code=:src", $variables);
    }

    function GetAccounts($sid, $psid) {
        return $this->db->SelectData("SELECT * FROM mvd_accounts WHERE service_id=:sv
                AND pay_serv_id=:psid", array('sv' => $sid, 'psid' => $psid));
    }

    function CreateTransactionRecord($data) {
        $now = date('Y-m-d H:i:s');
        $postData = array(
            'transaction_date' => $now,
            'service_id' => $data['service_id'],
            'pay_serv_id' => $data['pay_serv_id'],
            'pay_serv_account' => $data['pay_serv_account'],
            'serv_account_ref' => $data['pay_account_ref'],
            'serv_payment_ref' => $data['pay_serv_pay_ref'],
            'transaction_amount' => $data['transaction_amount'],
            'pay_serv_transaction_id' => $data['pay_serv_trans_ref']
        );
        $trans_id = $this->db->InsertData("mvd_payment_transactions", $postData, 'transaction_id');
        $this->log->ExeLog($data, 'Model::CreateTransactionRecord Transction Recorded Under ID ' . $trans_id[0]['transaction_id'], 2);
        $this->RecordTransactionFile('req_from_opco', $data, $trans_id[0]['transaction_id']);
        return $trans_id[0]['transaction_id'];
    }

    function PSPCompletePayment($stan_array) {
        $now = date('Y-m-d H:i:s');
        if ($stan_array['responsecode'] == '01') {
            $trans_resp = array(
                'state' => 'Completed',
                'merc_resp_code' => '100',
                'aggreg_resp_code' => '100',
            );
            //Make Posting in Accounts Day Book:
            $this->AccountDayBookPosting($req_array, $transaction);
        } else {
            $trans_resp = array(
                'state' => 'Failed'
            );
        }
        $postData = array(
            'pay_serv_transaction_id' => $stan_array['pay_serv_trans_ref'],
            'transaction_status' => $trans_resp['state'],
            'aggreg_resp_code' => $trans_resp['aggreg_resp_code'],
            'merchant_resp_code' => $trans_resp['resp_code'],
            'pay_serv_resp_code' => $stan_array['responsecode'],
            'processing_response' => $stan_array['responsemsg'],
            'last_updated' => $now
        );
        $this->db->UpdateData('mvd_payment_transactions', $postData, "transaction_id = {$stan_array['transaction_id']}");
    }

    function CloseTransaction($req_array, $merc_resp, $transaction) {
        $now = date('Y-m-d H:i:s');
        if ($merc_resp['aggreg_resp_code'] == '100') {
            $trans_state = 'Completed';
            //Make Posting in Accounts Day Book:
            $this->AccountDayBookPosting($req_array, $transaction);
        } elseif ($merc_resp['aggreg_resp_code'] == '190') {
            $trans_state = 'Rolled Back';
        } else {
            $trans_state = 'Failed';
        }
        $postData = array(
            'merchant_transaction_id' => $merc_resp['merch_pay_ref'],
            'transaction_status' => $trans_state,
            'merchant_resp_code' => $merc_resp['resp_code'],
            'pay_serv_resp_code' => $merc_resp['pay_serv_resp_code'],
            'processing_response' => $merc_resp['aggreg_resp_message'],
            'last_updated' => $now
        );
        $this->db->UpdateData('mvd_payment_transactions', $postData, "transaction_id = {$transaction[0]['transaction_id']}");
    }

    function ReconcileTransaction($req_array, $transaction) {
        
    }

    function AccountDayBookPosting($req_array, $trans) {
        $bill_rules = $this->BillingRules($trans[0]['service_id'], $trans[0]['pay_serv_id'], $trans[0]['transaction_amount']);
        $agg_fees = $bill_rules[0]['agg_fee_share'] * ($trans[0]['transaction_amount'] / 1.18);
        $psp_fees = $bill_rules[0]['payserv_fee_share'] * ($trans[0]['transaction_amount'] / 1.18);
        $serv_coll = $trans[0]['transaction_amount'] - ($agg_fees + $psp_fees);
        $accounts = $this->GetAccounts($trans[0]['service_id'], $trans[0]['pay_serv_id']);
        $this->CreditAccounts($accounts, $trans, $agg_fees, $psp_fees, $serv_coll);
    }

    function CreditAccounts($accounts, $trans, $agg_fees, $psp_fees, $serv_coll) {
        //Post For ALL Accounts & Update Account Balances:
        $now = date('Y-m-d G:i:s');
        foreach ($accounts as $key => $value) {
            if ($value['account_type'] == 'Service Wallet') {
                $amount = $serv_coll;
                $new_bal = $value['account_balance'] + $amount;
            }
            if ($value['account_type'] == 'PSP Fees Wallet') {
                $amount = $psp_fees;
                $new_bal = $value['account_balance'] + $amount;
            }
            if ($value['account_type'] == 'Fees Wallet') {
                $amount = $agg_fees;
                $new_bal = $value['account_balance'] + $amount;
            }
            $journal = array(
                'daybook_date' => $now,
                'account_id' => $value['account_id'],
                'account_balance' => $value['account_balance'],
                'transaction_amount' => $amount,
                'transaction_ref_id' => $trans[0]['transaction_id'],
                'transaction_type' => 'CR',
                'new_balance' => $new_bal,
                'description' => 'Sucessful Payment Settlement'
            );
            $this->db->InsertData("mvd_accounts_daybook", $journal);
            $account = array(
                'account_balance' => $new_bal,
                'last_updated' => $now
            );
            $this->db->UpdateData('mvd_accounts', $account, "account_id = {$value['account_id']}");
        }
    }

    function PrepareServiceProviderResponse($request, $transaction, $r_type, $t_type) {
        $this->log->ExeLog($request, 'Model::PrepareServiceProviderResponse Function Call ', 2);
        $processing_rules = $this->ProcessingRules($transaction, $r_type, $t_type);
        $this->log->ExeLog($request, 'Model::PrepareServiceProviderResponse ProcessingRules Request Returning ' . var_export($processing_rules, true), 2);
        $response_xml = $this->WriteGeneralXMLFile($request, $r_type, $processing_rules, $transaction);
        $this->log->ExeLog($request, 'Model::PrepareServiceProviderResponse Response ' . $response_xml, 3);
        return $response_xml;
    }

    function WriteGeneralXMLFile($request, $type, $processing_rules, $trans_data) {
        $f_template = $processing_rules[0]['format_template'];
        $template = 'templates/' . $type . '/' . $f_template . '.php';
        require($template);
        $trans_xml = ${$f_template};
        $file_name = $this->log->LogXML($request['vendor'], $request['service_id'], $processing_rules[0]['parameter_type'], $trans_xml);
        $this->log->ExeLog($request, 'Model::WriteGeneralXMLFile File For ' . $processing_rules[0]['parameter_type'] . ' Saved Under ' . $file_name, 2);
        $trans_data['xmlfilename'] = $file_name;
        if ($processing_rules[0]['request_type'] == 'payment') {
            $this->RecordTransactionFile($processing_rules[0]['parameter_type'], $trans_data, $trans_data['transaction_id']);
        }
        return $trans_xml;
    }

    function WriteHeaders($processing_rules) {
        $f_template = $processing_rules[0]['header_template'];
        $template = 'templates/headers/' . $f_template . '.php';
        require($template);
        $trans_xml = ${$f_template};
        return $trans_xml;
    }

    function SendByCURL($request, $url, $xml, $headers) {
        $this->log->ExeLog($request, 'Model::SendByCURL Making Request To ' . $url . ' With Data ' . $xml, 2);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        $content = curl_exec($ch);
        return $content;
    }

    function RecordTransactionFile($type, $data, $trans) {
        //Find If Transaction Record Already Exists
        $res = $this->db->SelectData("SELECT * FROM sm_log_file_index WHERE transaction_id=:tid", array('tid' => $trans));
        if (count($res) > 0) {
            $postData = array(
                $type => $data['xmlfilename']
            );
            $this->db->UpdateData('sm_log_file_index', $postData, "record_id = {$res[0]['record_id']}");
        } else {
            $this->log->ExeLog($data, 'Model::RecordTransactionFile initial File Recording For  ' . $trans, 2);
            $today = date("Y-m-d H:i:s");
            $postFile = array(
                'invent_date' => $today,
                'log_file_key' => $data['logfilekey'],
                'transaction_id' => $trans,
                $type => $data['xmlfilename']
            );
            $this->db->InsertData('sm_log_file_index', $postFile);
        }
    }

}
