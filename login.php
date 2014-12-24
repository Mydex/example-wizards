<?php

/**
 *  This is a proxy file to allow an existing Mydex member to login to the Mydex platform
 *  and authorise the Z9 mobile connection to connect with their PDS.
 */

include 'config.php';

session_start();

header('Content-type: application/json');

//Read raw POST data and decode JSON
$postdata_raw = file_get_contents("php://input");
$postdata = json_decode($postdata_raw);

$mydexid = $postdata->mydexid;
$password = $postdata->password;

$api_key = API_KEY;
$connection_nid = CONNECTION_NID;
$connection_token = CONNECTION_TOKEN;

// Check for valid member credentials.
$auth_data = array(
  'mydexid' => $mydexid,
  'password' => $password
  );

$api_idp_auth_url = MYDEX_IDP_PATH . '/' . APP_PATH . '/login?mydexid=' . $mydexid . '&password=' . $password;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_idp_auth_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Authentication: ' . MEMBER_CONNECTION_KEY
));

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode((object)$auth_data));

$authRequest = curl_exec($ch);

$authResult = json_decode($authRequest);

$curl_error = curl_error($ch);

curl_close($ch);

if (isset($authResult->error)) {

  $return_data = array(
    'error' => $authResult->error
    );

  print json_encode($return_data);
}else{

  // Check if the member is already connected.
  $api_idp_conn_url = MYDEX_IDP_PATH . '/' . APP_PATH . '/' . $mydexid . '/' . $connection_nid;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_idp_conn_url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authentication: ' . MEMBER_CONNECTION_KEY
  ));

  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

  $authRequest = curl_exec($ch);
  $authResult = json_decode($authRequest);
  $curl_error = curl_error($ch);

  curl_close($ch);

  // if there is error coming from getting member connection details
  // then it means member is not connection and details are not in the DB.
  if (isset($authResult->error)) {
    // Put the mydexid in the session for after page navigation.
    $_SESSION['mydexid'] = $auth_data['mydexid'];
    $_SESSION['ftc'] = 'true';
    $return_data = array(
      'mydexid' => $auth_data['mydexid']
    );
    print json_encode($return_data);
  }
  else {
    // Connection already exists, so return 'connected'.
    $return_data = array(
      'connected' => 'Already connected!'
    );

    print json_encode($return_data);
  }
}
