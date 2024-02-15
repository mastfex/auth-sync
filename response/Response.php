<?php

class Response
{
    private $code;
    private $message;
    private $timestamp;

    // Constructor
    public function __construct($code = null, $message = null, $timestamp = null)
    {
        $this->code = $code;
        $this->message = $message;
        $this->timestamp = $timestamp === null ? time() : $timestamp;
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'timestamp' => $this->timestamp
        ];
    }
}
