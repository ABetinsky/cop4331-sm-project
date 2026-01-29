<?php

namespace Utils\Http;

class Request {
    public $method;
    public $uri;
    public $headers;
    public $body;

    private $has_json_body;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->headers = getallheaders();
        $this->body = (array) json_decode(file_get_contents('php://input'));

        $this->has_json_body = (json_last_error() == JSON_ERROR_NONE);
    }

    function hasJsonBody() {
        return $this->has_json_body;
    }
}

class Response {
    public $body = [];

    function send() {
        header('Content-Type: application/json');
        echo json_encode($this->body);
    }

    function sendResult($res) {
        $this->body = ["status" => "success", "result" => array_values((array)$res)];
        $this->send();
        exit;
    }

    function sendError($msg, $error_code = 500) {
        http_response_code($error_code);
        $this->body = ["status" => "error", "message" => $msg];
        $this->send();
        exit;
    }

    function sendSuccess() {
        $this->body = ["status" => "success"];
        $this->send();
        exit;
    }
};