<?php

/**
 *  This config file contains the global variables used throughout this application.
 */

// Debug - to enable debugging change to '1'
$debug = '0';

// Get and set environment vars.
$pos_dash = strpos($_SERVER['HTTP_HOST'], '-');
$pos_dot = strpos($_SERVER['HTTP_HOST'], '.');

if (empty($pos_dash)) {
  $environment = substr($_SERVER['HTTP_HOST'], 0, $pos_dot);
} else {
  $environment = substr($_SERVER['HTTP_HOST'], ($pos_dash+1) , ($pos_dot - $pos_dash - 1));
}
// Set up the environment variables.
// API Key is given to you when you create an account through https://dev.mydex.org
define('API_KEY', 'dkhg834kfbhs9dehngs04hng9sfgh34k');

// Token and Connection ID are provided when your connection is set up with Mydex.
// The token and connection ID below are purely for demo purposes and they belong
// to the Z9 Mobile demonstration connection: https://sbx.mydex.org/connections/z9-mobile
define('CONNECTION_TOKEN', 'LPJKWIOGt5TKic6jj9ZhyRMaBzgIEF5q');
define('CONNECTION_NID', '4042');

// MEMEBER_CONNECTION_KEY - This is used to authenticate the member login credentials and connection details.
// This key is only used in this demo. For a real-world example, the connection details would be stored in your database.
define('MEMBER_CONNECTION_KEY', 'sckj34dfpoqa5912klcv91fnv9w4tvps');

// Mydex platform specific paths.
define('MYDEX_API_PATH', 'https://sbx-api.mydex.org/');
define('MYDEX_IDP_PATH', 'https://sbx-idp.mydexid.org/');
define('MYDEX_DEV_PATH', 'https://dev.mydex.org');
define('MYDEX_PDS_PATH', 'https://sbx.mydex.org');

// The root folder for the application on the Mydex IDP.        
define('APP_PATH', 'simulated-connections');

// Plain text variables.
define('GLOBAL_TXT_TERMS_DEFAULT', 'Click here to accept the Mydex Terms of Service.');
define('GLOBAL_TXT_TERMS_SELECT', 'Thank you, you have accepted the Mydex Terms of Service.');
define('GLOBAL_TXT_TERMS_LINK', 'Read the Mydex Terms of Service here.');
