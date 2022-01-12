<?php

namespace RestAPI;

class OperatorChatRestAPI extends \RestAPI\BaseRestAPI  {
    var $accountDb;

    const KIND_USER = 1;
    const KIND_AGENT = 2;

    function __construct($dblink) {
        parent::__construct($dblink);
        //$this->accountDb=$accountDb;

        
    }

    public function post() {
        global $settings, $mysqlprefix, $current_locale, $state_closed;

        if(!$this->params) {    
            $this->setError(HTTPStatusCode::HTTP_BAD_REQUEST, 'Invalid Request');
            return false;
        }

        
      

      $this->setStatus(HTTPStatusCode::HTTP_OK);
      $this->setResponse("success","Message has been queued.");

    }

    
    
}


?>