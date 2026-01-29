<?php

namespace Utils\Http;

class ClientRequest {
    public $method;
    public $uri;
    public $headers;
    public $body;

    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->headers = getallheaders();
        $this->body = file_get_contents('php://input');
    }

    function getJsonBody() {
        $json_body = json_decode($this->body);
        if(json_last_error() == JSON_ERROR_NONE) {
            return (array) $json_body;
        } else {
            return null;
        }
    }
}

class ServerResponse {
    public $body = [];

    function sendJson() {
        header('Content-Type: application/json');
        echo json_encode($this->body);
        exit;
    }

    function sendResult($res) {
        $this->body = ["status" => "success", "result" => array_values((array)$res)];
        $this->sendJson();
    }

    function sendError($msg, $error_code = 500) {
        http_response_code($error_code);
        $this->body = ["status" => "error", "message" => $msg];
        $this->sendJson();
    }

    function sendSuccess() {
        $this->body = ["status" => "success"];
        $this->sendJson();
    }
};