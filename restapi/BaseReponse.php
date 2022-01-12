<?php

namespace RestAPI;

class BaseResponse {
    protected $body;
    protected $keyMap = array();


    public function __construct(
        $body = null
    ) {
        $this->body = '';
    }


    public function set($key, $value) {
        $this->keyMap[$key] = $value;
    }

    public function getJson() {
        return json_encode( $this->keyMap);
    }

}