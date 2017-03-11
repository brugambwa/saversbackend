<?php

$request_code_monitor = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<request md5="'. $req_data['enc_string'] . '">
    <stan>'. $req_data['stan'] .'</stan>
    <datetime>' . $req_data['today'] . '</datetime>
    <type>2D</type>
    <session>' . $req_data['sessionid'] . '</session>
    <data>
        <amount>' . $req_data['amount'] . '</amount>
        <description>' . $req_data['description'] . '</description>
        <event>New</event>
        <monitor>true</monitor>
    </data>
</request>';

