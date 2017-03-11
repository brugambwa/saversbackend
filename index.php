<?php

/**************************************
* Elsa Technologies Limited
 *  iNkwazi Payment Gateway Server.
Version 2.0.1
Deployed 1st April 2015.
**************************************/
require 'config.php';
require 'library/settings.php';

function __autoload($class) {
    require LIBS . $class . ".php";
}

$app = new Bootstrap();
?>
