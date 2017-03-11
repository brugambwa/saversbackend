<?php

class Error extends Controller {

    function __construct() {
        parent::__construct();
    }
    
    function Index($error_id, $error_message){
        $this->view->errormsg = $this->ProcessErrorCodes($error_id, $error_message);
        $this->view->render('error_page');
    }
    
    function ProcessErrorCodes($id, $msg){
        $string = $msg;
        return $string;
    }

}

