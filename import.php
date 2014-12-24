<?php

/**
 *  This file triggers the import of the dummy data from
 *  the 'data' folder in to the member's PDS.
 */

include 'config.php';
include 'settings.php';

session_start();

$member_connection_details = _get_member_connection_details($_SESSION['mydexid']);

// if access is not set, then just use the default 0 instance everywhere like we used to do before.
if (isset($member_connection_details->access)) {
  $access = unserialize($member_connection_details->access);
}
else {
  $access = NULL;
}

$user_id = substr($member_connection_details->con_id, 0, (strpos($member_connection_details->con_id, '-')));

// 1. Import Name into Personal Details dataset
if (isset($access)) {
  $instance_fdpd = array_keys($access['field_ds_personal_details']);
}
else {
  $instance_fdpd[0] = 0;
}

$data = array();
$data['field_ds_personal_details'][$instance_fdpd[0]]['field_personal_fname'] = SETTINGS_VAL_PERSONAL_FNAME;
$data['field_ds_personal_details'][$instance_fdpd[0]]['field_personal_faname'] = SETTINGS_VAL_PERSONAL_FANAME;
// need to do this for PUT
$data = (is_array($data)) ? http_build_query($data) : $data;

$arguments_array = array(
  'api_key=' . API_KEY,
  'source_type=' . 'connection',
  'con_id=' . $member_connection_details->con_id,
  'key=' . $member_connection_details->con_key,
);
$arguments = implode('&', $arguments_array);

_mydex_update_json_dataset($user_id, $data, $arguments);

// 2. Import Account number, Telephone number and any other field from field_ds_utility we want in the PDS
if (isset($access)) {
  $instance_fdu = array_keys($access[SETTINGS_FIELD_DATASET_UTILITY]);
}
else {
  $instance_fdu[0] = 0;
}

// This data should be randomised in future iterations so the same data isn't always imported.
$data = array();
$data[SETTINGS_FIELD_DATASET_UTILITY][$instance_fdu[0]][SETTINGS_FIELD_SERVICE] = SETTINGS_VAL_SERVICE;
$data[SETTINGS_FIELD_DATASET_UTILITY][$instance_fdu[0]][SETTINGS_FIELD_SUPPLIER_NAME] = SETTINGS_VAL_SUPPLIER_NAME;
$data[SETTINGS_FIELD_DATASET_UTILITY][$instance_fdu[0]][SETTINGS_FIELD_TEL_NUMBER] = SETTINGS_VAL_TEL_NUMBER;
$data[SETTINGS_FIELD_DATASET_UTILITY][$instance_fdu[0]][SETTINGS_FIELD_CUSTOMER_NUM] = SETTINGS_VAL_CUSTOMER_NUM;
$data[SETTINGS_FIELD_DATASET_UTILITY][$instance_fdu[0]][SETTINGS_FIELD_ACCOUNT_NAME] = SETTINGS_VAL_ACCOUNT_NAME;
$data[SETTINGS_FIELD_DATASET_UTILITY][$instance_fdu[0]][SETTINGS_FIELD_UTILITY_PAYMENT_METHOD] = SETTINGS_VAL_PAYMENT_METHOD;
$data = (is_array($data)) ? http_build_query($data) : $data;

_mydex_update_json_dataset($user_id, $data, $arguments);

// 3. Import the transaction data from JSON file.
$data = array();

if (isset($access)) {
  $instance_dutc = array_keys($access[SETTINGS_TRANSACTION_DATASET]);
}
else {
  $instance_dutc[0] = 0;
}

$filename = SETTINGS_TRANSACTION_DATA_PATH;
$handle = fopen($filename, "r");
$transactional_data = json_decode(fread($handle, filesize($filename)));

$arguments_transactional_array = array(
  'uid=' . $user_id,
  'api_key=' . API_KEY,
  'source_type=' . 'connection',
  'con_id=' . $member_connection_details->con_id,
  'key=' . $member_connection_details->con_key,
  'instance=' . $instance_dutc[0],
  'dataset=' . SETTINGS_TRANSACTION_DATASET,
);
$arguments_transactional = implode('&', $arguments_transactional_array);

$transactional_data_chunks = array_chunk($transactional_data, 50);
foreach ($transactional_data_chunks as $data) {
  $data = (is_array($data)) ? http_build_query($data) : $data;

  _mydex_update_transactional_dataset($user_id, $data, $arguments_transactional);
}

$_SESSION['data_import'] = true;

$app_path = '/' . APP_PATH . '/';

// If its First time connection, the member doesn't need to see the rest of the steps so skip straight to complete.
if (isset($_SESSION['ftc'])) {
  if ($_SESSION['ftc'] == 'true') {
    header('Location: complete.php');
  }
  else {
    // This is the PDS create journey and the member needs to follow more steps.
    header('Location: confirmation.php');
  }
}
else {
  // Catch instances where the session variable may not be set.
  header('Location: confirmation.php');
}

/**
 *  Update JSON dataset
 */
function _mydex_update_json_dataset($user_id, $data, $arguments) {
  global $debug;
  
  $mydex_api_url = MYDEX_API_PATH . "/api/pds/pds/" . $user_id . ".json?" . $arguments;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $mydex_api_url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  $mydex_request = curl_exec($ch);
  $mydex_request_decode = json_decode($mydex_request);
  curl_close($ch);
  
  if($debug != "0"){
    syslog(LOG_INFO,"mydexRequest: " . print_r($mydex_request_decode,true));
  }
}
 
/**
 * CAUTION! because this is a POST it will CREATE new transactional rows in the table
 * it means that if you run the script several times you will end up with duplicate data in the table
 */
function _mydex_update_transactional_dataset($user_id, $data, $arguments) {
  global $debug;
  
  $mydex_api_url = MYDEX_API_PATH . "/api/pds/transaction.json?" . $arguments;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $mydex_api_url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  $mydex_request = curl_exec($ch);
  $mydex_request_decode = json_decode($mydex_request);
  curl_close($ch);

  if($debug != "0"){
    syslog(LOG_INFO,"mydexRequest: " . print_r($mydex_request_decode,true));
  }
}

/**
 * Get Member Connection Deails
 */
function _get_member_connection_details($mydexid) {
  global $debug;

  // here get the connection key, mydexid
  $idp_url = MYDEX_IDP_PATH . '/' . APP_PATH . '/' . $mydexid . '/' . CONNECTION_NID;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $idp_url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authentication: ' . MEMBER_CONNECTION_KEY
  ));
  $connectionDetailsRequest = curl_exec($ch);
  $connection_details = json_decode($connectionDetailsRequest);
  curl_close($ch);

  if($debug != "0"){
    syslog(LOG_INFO,"GET connection_details: " . print_r($connection_details,true));
  }

  return $connection_details;
}
