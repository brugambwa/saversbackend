<?php

/**************************************
Elsa Technologies Limited
iNkwazi Payment Gateway Server.
Version 1.0.0
Modified BootStrap 6th January 2016.
**************************************/

class Bootstrap {

    private $_url = null;
    private $_controller = null;
    private $_errorID = null;
    private $_errorMsgDetail = null;

    function __construct() {
        $this->_GetURL();
        $this->_pageLoader();
    }

    private function _getURL() {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $this->_url = explode('/', $url);
    }

    function _pageLoader() {
        if (empty($this->_url[0])) {
            $this->_indexControllerLoader();
        } else {
            $this->_loadController();
            $this->_loadMethod();
        }
    }

    private function _loadController() {
        $file = 'controllers/' . $this->_url[0] . '.php';
        if (file_exists($file)) {
            require $file;
            $this->_controller = new $this->_url[0];
            $this->_controller->LoadModel($this->_url[0]);
        } else {
            $this->_errorID = 2;
            $this->_errorMsgDetail = 'The File ' . $file . ' does not exist';
            $this->_error();
            return false;
        }
    }

    private function _indexControllerLoader() {
        $file = 'controllers/index.php';
        if (file_exists($file)) {
            $this->_controller = new Index();
            $this->_controller->LoadModel('index');
            $this->_controller->index();
            return false;
        } else {
            $this->_errorID = 1;
            $this->_errorMsgDetail = 'There is no Index Controller';
            $this->_error();
            return false;
        }
    }

    private function _loadMethod() {
        if (empty($this->_url[1])) {
            $this->_url[1] = 'index';
        }
        if (method_exists($this->_controller, $this->_url[1])) {
            $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3], $this->_url[4], $this->_url[5]);
        } else {
            $this->_errorID = 3;
            $this->_errorMsgDetail = 'The Method ' . $this->_url[1] . ' does not exist in ' . $this->_url[0];
            $this->_error();
        }
    }

    private function _error() {
        require 'controllers/error.php';
        $this->_controller = new Error();
        $this->_controller->index($this->_errorID, $this->_errorMsgDetail);
        return false;
    }

}
