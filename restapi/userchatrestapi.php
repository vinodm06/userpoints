<?php

namespace RestAPI;



class UserChatRestAPI {
    var $dblink;
    var $response;
    var $user;
    var $userName;
    var $status = HTTPStatusCode::HTTP_OK;
    var $errorMessage;

    const KIND_USER = 1;
    const KIND_AGENT = 2;
    const KIND_FOR_AGENT = 3;
    const KIND_INFO = 4;
    const KIND_CONN = 5;
    const KIND_EVENTS = 6;
    const KIND_AVATAR = 7;
    const KIND_MMS = 8;
    const MAX_MESSAGE_COUNT = 50;

    function __construct($dblink, $apiKey ) {
        $this->dblink = $dblink;
        $this->response = new BaseResponse();

        if(!empty($apiKey)) {
            $this->user = $this->getContact($apiKey); 
            
            $this->userName = $this->user['name'] . ' - ' . $this->user['local_phone'];
            $this->response->set('userName',$this->userName);
        } else {
            $this->status = HTTPStatusCode::HTTP_UNAUTHORIZED;
            $this->errorMessage = 'Invalid key';
        }
    }
    public function setErrorMessage($errorMessage ) {
        return $this->errorMessage = $errorMessage;
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
        http_response_code($this->status);
        if(! $this->isOK()) {
            $this->response->set("message", $this->errorMessage);
        }
        echo $this->response->getJson();
    }

    public function post($message) {

        $thread = $this->getThread();
        if(empty($thread)) {
            $this->status = HTTPStatusCode::HTTP_BAD_REQUEST;
            $this->errorMessage = 'There is no open thread';
        } else {
            if (empty($message)) {
                $this->status = HTTPStatusCode::HTTP_BAD_REQUEST;
                $this->errorMessage = 'missing parameter';
            } else {
                $this->postMessage($thread, $message);
                $this->response->set("threadId",$thread['threadId']);
                $this->response->set("message",'sent a message');
            }
        }
    }

    private function postMessage ($thread, $message )
    {
        $isuser = true;
        $kind = self::KIND_USER;
        $from =  $thread['userName'] ;
        $to = $thread['agentId'];
        $threadid = $thread['threadId'];
        $issms = strtoupper($thread['userAgent']);
        $override = ($thread['initiatedBy'] == "user")? true : false;
        $scheduled_time = '';
        $timezone = '';
        
        $query = 'insert into chatmessage (threadid,ikind,tmessage,tname,agentId,
                                        dtmcreated,contactname,phonenumber,bgroupname,groupmessagecount) 
                    values (?,?,?,?,?,
                    CURRENT_TIMESTAMP,null,null,null,null) ';
                
        $result = execSQL($query, 
            array(
                'iissi',
                $threadid, $kind,  $message, $from, $to , 
            ),true, $this->dblink);

        $postedid =  mysqli_insert_id($this->dblink);

    }

        
    private function getThread() {
        $sql = 'select  userName, threadId, agentId, userid, agentName, userAgent,initiatedBy '
                . ' from chatthread  where username = ? order by threadid desc limit 1 ';
        $thread =  queryforone($sql,  array( 's', $this->userName) , $this->dblink);

        return $thread;
    }


    private function getContact($apiKey) {     
        $sql = "select id, phone, local_phone, name, status, source from contacts where apikey = ? ";
        $contact=  queryforone($sql,  array( 's', $apiKey) , $this->dblink);
        return $contact;
    }

    public function getUserMessages() {
        $sql = 'select messageid, ikind, dtmcreated as created, tname, tmessage '
                .' from chatmessage '
                .' where threadid in (select threadid from chatthread where userName= ? ) '
                .' and  ikind <> ? order by messageid desc limit ? ';

        $messages  = select_multilple_row($sql,  array( 'sii', $this->userName, self::KIND_FOR_AGENT, self::MAX_MESSAGE_COUNT+1) , $this->dblink);
        $this->response->set('more', (sizeOf($messages) > self::MAX_MESSAGE_COUNT) ? true: false);
        $this->response->set('conversations', array_slice($messages,0,self::MAX_MESSAGE_COUNT));
      
    }
}


?>