<?php
require "../_utils/session.php";

header("Content-Type: application/json");

echo json_encode([
    "logged_in" => is_logged_in()
]);