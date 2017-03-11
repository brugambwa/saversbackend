<?php

class Merchant extends Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        $xml_post = file_get_contents('php://input');
        if (empty($xml_post)) {
            $this->view->render('index');
        } else {
            $key = $this->model->MakeKey();
            $standard_array = $this->model->ParseRequest($xml_post);
            $standard_array['logfilekey'] = $key;
            $response_xml = $this->model->ProcessRequest($xml_post, $standard_array);
            header('Content-Type: application/xml; charset=UTF-8');
            echo $response_xml;
        }
    }

    public function Test() {
        $this->model->Test();
    }

}
