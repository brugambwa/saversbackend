<?php

$status = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<type>2D</type>
<request md5="'. $req_data['enc_string'] . '">
    <stan>'. $req_data['stan'] .'</stan>
    <datetime>' . $req_data['today'] . '</datetime>
    <session>' . $req_data['sessionid'] . '</session>
    <data>
        <event>Status</event>
        <code>' . $req_data['paymentcode'] . '</code>
    </data>
</request>';

