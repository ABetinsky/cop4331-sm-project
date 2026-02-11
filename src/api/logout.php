<?php
require_once __DIR__ . '/_utils/session.php';

session_logout();

http_response_code(200);