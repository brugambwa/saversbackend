<?php

/*
* Database Credentials
*/
ini_set('memory_limit', '-1');


/*
* Development Environment DB Parameters
* *
*/
define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost', true);
define('DB_PORT', '3306', true);
define('DB_USER', 'root', true);
define('DB_PASS', 'root', true);
define('DB_NAME', '2016_mvend_schoolfees', true);


/*
* Log Directory
*/

define('LOG_DIR', 'systemlog/');
define('EXECUTION_LOG', 'systemlog/tmp/');
/*
* Encryption Algo
*/

define('ENC', 'sha256');

/*
* System Hash Keys
*/
define('HASH_PASS_KEY', '4[nM#X#e2IGA&ZNA.ud@2R2}W|P0xU[KrrrT6ulN%Sw2dg~7,@;)2<~Z^EZciP');
define('HASH_GEN_KEY', '4[nM#X#ezAg,L.2CS.ud@2R2}W|P0xU[KrrrT6ulN%Sw2dg~7,@;)2<~Z^EZciP');

/*
* System Paths and Other Important URLs
*/

/*
* Internal Applications
*/
define('SMS_SERVER', 'http://192.168.4.3:7473/EasySMS/');


/*
* XML FORMATING TAGS
*/
define('XMLPLAIN', '<?xml version="1.0" encoding="UTF-8"?>');
define('XMLSTART', '<?xml version="1.0" encoding="UTF-8"?><operationResult>');
define('XMLEND', '</operationResult>');
