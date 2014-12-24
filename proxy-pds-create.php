<?php

/**
 *  Proxy file used for the PDS create API calls.
 *  The calls are made via ajax from js/z9-mobile.js.
 */

include 'config.php';

header('Content-Type: application/json');

//Read raw POST data and decode JSON
$postdata_raw = file_get_contents("php://input"); 
$postdata = json_decode($postdata_raw);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $api_idp_url = $postdata->api_idp_url;

  $connection_token = CONNECTION_TOKEN;

  $request_data = array(
    'mydexid' => $postdata->mydexid,
    'email' => $postdata->email,
    'password' => $postdata->password,
    'accept_legal' => $postdata->legal,
    'api_key' => $postdata->api_key,
    'connection_nid' => $postdata->connection_nid,
    'connection_token_hash' => hash('SHA512', $connection_token),
    'iframe_expire' => '300'
  );

  $api_idp_token_url = $api_idp_url . '/access-token/' . $postdata->connection_nid;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $api_idp_token_url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $tokenRequest = curl_exec($ch);
  $short_access_token = json_decode($tokenRequest);
  curl_close($ch);

  if (isset($short_access_token->error)) {
    return $short_access_token;
  }

  $authentication = hash('SHA512', $short_access_token.$connection_token);

  $idp_request_url = MYDEX_IDP_PATH . '/api/pds';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $idp_request_url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authentication: ' . $authentication,
    'Short-Access-Token: ' . $short_access_token,
  ));
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode((object)$request_data));

  $pdsCreateRequest = curl_exec($ch);

  $curl_error = curl_error($ch);

  curl_close($ch);

  if (isset($pdsCreateRequest->error)) {
    print $pdsCreateRequest;
    exit;
  }

  if (!empty($curl_error)) {
    print $curl_error;
    exit;
  }

  print $pdsCreateRequest;
}
