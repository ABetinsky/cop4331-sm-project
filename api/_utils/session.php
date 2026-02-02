<?php
session_start();

function is_logged_in()
{
  return $_SESSION["logged_in"] ?? false;
}

function session_login($user_id)
{
  session_regenerate_id(true);
  $_SESSION["user_id"] = $user_id;
  $_SESSION["logged_in"] = true;
}

function session_logout()
{
  session_unset();
  session_destroy();
}
