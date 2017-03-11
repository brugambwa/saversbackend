<?php
$signon = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<request md5="'. $req_data['enc_string'] . '">
    <stan>'. $req_data['stan'] .'</stan>
    <datetime>' . $req_data['today'] . '</datetime>
    <type>SignOn</type>
    <data>
        <identifier>' . $req_data['apikey'] . '</identifier> 
    </data>
</request>';
