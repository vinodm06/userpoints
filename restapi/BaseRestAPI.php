<?php

namespace RestAPI;



class BaseRestAPI {
    protected $dblink;
    protected $response;
    protected $status = HTTPStatusCode::HTTP_OK;
    protected $errorMessage;
    protected $errorContent;
    protected $apiKey;
    protected $params;

    function __construct($dblink) {
        global $settings;

        $this->dblink = $dblink;
        $this->response = new BaseResponse();
        $headers = $this->getHeader();
        print_r($headers);
        $this->apiKey = "test";//$headers['APIKEY'];
        $this->apiId = "this";$headers['APIID'];

        if(empty($this->apiKey) || empty($this->apiId) ) {
            $this->setError( HTTPStatusCode::HTTP_UNAUTHORIZED,'Unauthorized',
                 'Your request is missing API credentials.  Please add the API Id and the API Key to the request.');
        //} else if($this->apiKey != $settings['apikey'] || $this->apiId  != $settings['apiid'] ) {
            } else if($this->apiKey != "test" || $this->apiId  != "this" ) {
            $this->setError( HTTPStatusCode::HTTP_UNAUTHORIZED,'Unauthorized',
                 'API credentials doesnot match.  Please check the API Id and the API Key.');
        } else {
            $json = file_get_contents('php://input');
            $this->params = json_decode($json);
            $this->params =$this->getRequestBody($json);    
        }

    }

    public function setError( $httpStatus, $errorMessage, $errorContent="" ) {
        $this->status = $httpStatus;
        $this->errorMessage = $errorMessage;
        $this->errorContent = $errorContent;
    }

    public function setStatus($status ) {
        return $this->status = $status;
    }
    public function setResponse($key, $value) {
        return $this->response->set($key, $value);
    }

    public function isOK() {
        return ($this->status === HTTPStatusCode::HTTP_OK) ? true : false;
    }

    /**
     * write response
     */
    public function writeResponse() {
        if(! $this->isOK()) {
            $this->response->set("message", $this->errorMessage);
            $this->response->set("content", $this->errorContent);
        }
        http_response_code($this->status);
        echo $this->response->getJson();
    }

    public function getRequestBody($body) {
        $params = json_decode($body, true);
        if (json_last_error() !== JSON_ERROR_NONE or empty($params)) {
        return false;
        }
        return $params;
    }

    private function getHeader() {
        $headers = []; 
        foreach ($_SERVER as $name => $value) 
        { 
            if (substr($name, 0, 5) == 'HTTP_') 
            { 
                $headers[str_replace(' ', '-', str_replace('_', ' ', substr($name, 5)))] = $value; 
            } 
        } 
        return $headers; 
    }

}