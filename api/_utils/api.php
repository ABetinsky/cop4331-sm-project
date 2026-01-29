<?php

# Allow only one HTTP method
function http_allow_only(string $req_type) {
  if ($_SERVER["REQUEST_METHOD"] !== $req_type) {
    http_response_code(500);
    echo "Method not allowed";
    exit;
  }
}

function sanitize_input(string $input) {
   $input = trim($input);
   $input = htmlspecialchars($input);
   return $input;
}

function validate_password(string $password)
{
  if (strlen($password) < 8) {
    return false;
  }

  # Check if pw contains numbers
  if (!preg_match("~[0-9]+~", $password)) {
    return false;
  }

  return true;
}

function validate_email(string $email)
{
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  }

  return true;
}
