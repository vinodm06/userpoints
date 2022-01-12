<?php
namespace RestAPI;

use RestAPI\BaseResponse;

class ChatRestAPI {
    var $dblink;
    var $response;
    var $user;
    var $status = HTTPStatusCode::HTTP_OK;
    var $errorMessage;

    function __construct($dblink, $apiKey ) {
        $this->dblink = $dblink;
        $this->response = new BaseResponse();
        

        /*if(!empty($apiKey)) {
            $this->user = $this->getContact($apiKey); 
            $this->response->set('user',$this->operator['name']);
        } else {
            $this->status = HTTPStatusCode::HTTP_UNAUTHORIZED;
            $this->errorMessage = 'Invalid key';
        }*/
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

    public function post($chatThreadId, $message) {

        if(empty($chatThreadId)) {
            $this->status = HTTPStatusCode::HTTP_BAD_REQUEST;
            $this->errorMessage = 'threadId is missing';
        } else if (empty($message)) {
            $this->status = HTTPStatusCode::HTTP_BAD_REQUEST;
            $this->errorMessage = 'missing parameter';
        }
        
        $thread = $this->getThread($chatThreadId, $this->operator['operatorid']) ;
        if(!isset ($thread)) {
            $this->status = HTTPStatusCode::HTTP_BAD_REQUEST;
            $this->response->set('message','wrong thread');
        } else {
            // postMessage($threadid, $kind, $message, $link, $from,   $operatorid);           
           
            
            $this->postMessage($thread,  $message,   $this->operator['operatorid']);
            $this->response->set("message",'sent a message');
        }
    }

    private function postMessage ($thread,  $message,   $opid = null,
        $utime = null,   $contactname = null, $phonenumber = null,  $bgroupname = null, $groupmessagecount = null)
    {
        $isuser = true;
        $kind = 2;
        $from = $isuser ? $thread['userName'] : $thread['agentName'];
        $to = $thread['userid'];
        $threadid = $thread['threadId'];
        $issms = strtoupper($thread['userAgent']);
        $override = ($thread['initiatedBy'] == "user")? true : false;
        $scheduled_time = '';
        $timezone = '';
        // logic ceme from thread.php
        //1.  check ban_for_addr_
        $query = 'insert into chatmessage (threadid,ikind,tmessage,tname,agentId,
                                        dtmcreated,contactname,phonenumber,bgroupname,groupmessagecount) 
                    values (?,?,?,?,?,
                    CURRENT_TIMESTAMP,null,null,null,null) ';
                
        $result = execSQL($query, 
            array(
                'iissi',
                $threadid, $kind,  $message, $from, $opid ? $opid : "0" , 
            ),true, $this->dblink);

        $postedid =  mysqli_insert_id($this->dblink);
        $this->commit_thread( $threadid,  $postedid);
        // //           D:\work\library\ext\2.1\vendor\tivoka\tivoka\lib\Tivoka\Client\Connection\AbstractConnection.php
        // require_once( '/work/library/ext/2.1/vendor/tivoka/tivoka/lib/Tivoka/Client/Connection/ConnectionInterface.php');
        // require_once( '/work/library/ext/2.1/vendor/tivoka/tivoka/lib/Tivoka/Client/Connection/AbstractConnection.php');
        // //           D:\work\library\ext\2.1\vendor\tivoka\tivoka\lib\Tivoka\Client.php
        // require_once( '/work/library/ext/2.1/vendor/tivoka/tivoka/lib/Tivoka/Client.php');
        // require_once( '/work/library/ext/2.1/vendor/symfony/polyfill-mbstring/Mbstring.php');
        // require_once( '/work/library/ext/2.1/vendor/symfony/polyfill-mbstring/bootstrap.php');
        // require_once( '../../libs/contact.php');
        // require_once( '../../libs/chat.php');
        // require_once( '../../libs/notify.php');
        

        // sendSms($message, $to, $threadid, $override, $postedid, false,null,null, $scheduled_time, $timezone);

    }

    // private function queueSMS() {
    //           // error_log ( var_export($msg, true));
    //   $safemesssage = \emanant\resources\SMS::messageToGSM7($msg['message']);

    //   // set call parameters
    //   $message = array(
    //     'company'   => ( $account->name ),
    //     'api'       => ( !empty($core_config['environment']) ? $core_config['environment'] : 'live' ),
    //     'from'      => $account->phone,
    //     'to'        => $params['phone'],
    //     'message'   => $safemesssage,
    //     'direction' => 'outgoing',
    //     'sp'        => 'twilio',
    //     'pace'      => ( !empty($core_config['pacing']) ? $core_config['pacing'] : 5 ),
    //   );

    //   // error_log("message:". var_export($message, true));

    //   if (!empty($msg['timestamp'])) {
    //     $message['scheduled_time'] = $msg['timestamp'];
    //   }

    //   $smsqueue = new \emanant\resources\Queue($this->db['queues'], 'sms');
    // }
    
    public function getThreads() {    
        $sql = "select  userName, threadId, userid, ltoken, userAgent from chatthread where  agentId =? and istate=2 ";
        $chatThreads =  select_multilple_row($sql,  array( 'i', $this->operator['operatorid']) , $this->dblink);        
        $this->response->set('threads', $chatThreads);
    }

    
    private function getThread($chatThreadId) {
        $sql = 'select  userName, threadId, userid, agentName, userAgent,initiatedBy '
                . ' from chatthread  where threadId = ? and agentId = ?';
        $chatThreads =  queryforone($sql,  array( 'ii', $chatThreadId, $this->operator['operatorid']) , $this->dblink);

        return $chatThreads;
    }

    public function getConversations($chatThreadId) {
        $thread = $this->getThread($chatThreadId);
        $this->response->set('thread', $chatThreadId);
        if(empty($thread)) {
            $this->status = HTTPStatusCode::HTTP_BAD_REQUEST;
            $this->errorMessage = 'wrong thread';
        } else {        
            $sql = "select  agentId, tmessage, dtmcreated , tname from chatmessage where  threadId =?   order by messageid desc ";
            $conversations  = select_multilple_row($sql,  array( 'i', $chatThreadId) , $this->dblink);
            
            $this->response->set('conversations', $conversations);
            return $conversations;
        }
    }


    private function getContact($apiKey) {     
        $sql = "select phone, local_phone, name, status, source from contacts where  apikey = ? ";
        return queryforone($sql,  array( 's', $apiKey) , $this->dblink);
    }

    private function getOperator($apiKey) {     
        $sql = "select * from chatoperator where  mobileaccesstoken = ? ";
        return queryforone($sql,  array( 's', $apiKey) , $this->dblink);
    }

    private function commit_thread($threadid, $shownmessageid) {
        $nextId = $this->next_revision();
        $query = "update chatthread t set lrevision = ?, shownmessageid = ?, dtmmodified = CURRENT_TIMESTAMP "
                ." WHERE threadid = ? ";
        $result = execSQL($query, array('iii',$nextId, $shownmessageid, $threadid), true, $this->dblink );
        return   $result;  
    }

    private function next_revision() {    
        global $mysqlprefix;    
        $this->perform_query("update ${mysqlprefix}chatrevision set id=LAST_INSERT_ID(id+1)", $this->dblink);
        $val = mysqli_insert_id($this->dblink);
        return $val;
    }

    private function perform_query($query, $link) {
        mysqli_query($link, $query) or die('perform_query - Query failed: '.$query.' / ' . mysqli_error($link));
    }  
}


?>