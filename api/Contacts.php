<?php

require __DIR__ . '/_utils/Database.php';
require __DIR__ . '/_utils/Http.php';
require dirname(__DIR__) . '/vendor/autoload.php';

use Utils\Database;
use Utils\Http\Request;
use Utils\Http\Response;
use Dotenv\Dotenv;

# Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

# Init http util
$request = new Request();
$response = new Response();

# Send error if request body isn't json formatted
if($request->method != 'GET' && !$request->hasJsonBody()) {
    $response->sendError("Invalid data format");
}

# Retrieve contact information
$user_id = $request->headers['user_id'] ?? null;
$contact_id = $request->body['contact_id'] ?? null;
$first_name = $request->body['first_name'] ?? null;
$last_name = $request->body['last_name'] ?? null;
$phone = $request->body['phone'] ?? null;
$email = $request->body['email'] ?? null;

# Setup mysql connection
$conn = Database::connect();

# Confirm session (temporarily commented out for debugging until login api is implemented)
/*session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $cont->user_id) {
    $conn->close();
    $response->sendError("Invalid session");
}*/

#----------------------
#  POST - add contact
#----------------------

if($request->method == 'POST') {

    try {
        $stmt = "INSERT INTO contacts (user_id, first_name, last_name, phone, email) VALUES ('$user_id', '$first_name', '$last_name', '$phone', '$email')";
        $res = $conn->query($stmt);

    } catch(mysqli_sql_exception $e) {
        $conn->close();
        $response->sendError("Internal server error");
    }

    $conn->close();
    $response->sendSuccess();
} 

#------------------------
#  PUT - update contact
#------------------------

if($request->method == 'PUT') {

    try{
        $stmt = "UPDATE contacts SET first_name = '$first_name', last_name = '$last_name', phone = '$phone', email = '$email' WHERE id = '$contact_id' AND user_id = '$user_id'";
        $res = $conn->query($stmt);

    } catch(mysqli_sql_exception $e) {
        $conn->close();
        $response->sendError("Internal server error");
    }

    if($conn->affected_rows == 0) {
        $conn->close();
        $response->sendError("Contact does not exist, existing values are already equal to new values, or user does not have this contact");
    }

    $conn->close();
    $response->sendSuccess();
}

#---------------------------
#  DELETE - remove contact
#---------------------------

else if($request->method == 'DELETE') {

    try {
        $res = $conn->query("DELETE FROM contacts WHERE id = $contact_id AND user_id = $user_id");
    } catch(mysqli_sql_exception $e) {
        $conn->close();
        $response->sendError("Internal server error");
    }

    if($conn->affected_rows == 0) {
        $conn->close();
        $response->sendError("Contact does not exist or user does not have this contact");
    }

    $conn->close();
    $response->sendSuccess();
}

#----------------------
#  GET - get contacts
#----------------------

else if($request->method == 'GET') {

    try {
        $res = $conn->query("SELECT * FROM contacts WHERE user_id = $user_id");
    } catch(mysqli_sql_exception $e) {
        $conn->close();
        $response->sendError("Internal server error");
    }

    $contacts = $res->fetch_all(MYSQLI_ASSOC);

    $conn->close();
    $response->sendResult($contacts);

#---------------------------
#  Invalid request method
#---------------------------

} else {
    $conn->close();
    $response->sendError("Invalid request method");
};
