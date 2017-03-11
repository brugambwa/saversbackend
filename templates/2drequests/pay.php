<?php

$pay = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<request md5="'. $req_data['enc_string'] . '">
    <stan>'. $req_data['stan'] .'</stan>
    <datetime>' . $req_data['today'] . '</datetime>
    <type>2D</type>
    <session>' . $req_data['sessionid'] . '</session>
    <data>
        <amount>' . $req_data['amount'] . '</amount>
        <event>Pay</event>
        <code>' . $req_data['paymentcode'] . '</code>
    </data>
</request>';
