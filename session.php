<?php

/**
 *  Create a session, and adds a session variable if a cookie called 'mydexid' exists
 */

session_start();

header('Content-Type: application/json');
$postdata_raw = file_get_contents("php://input");
$postdata = json_decode($postdata_raw);

$_SESSION['mydexid'] = $postdata->mydexid;