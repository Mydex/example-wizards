<?php

/**
 *  This file is the last step in the journey, it is the completion screen
 *  when the connection is successfully set up.
 */

include 'config.php';
include 'settings.php';
session_start();

if($debug != '0'){
  syslog(LOG_INFO,"Session: " . print_r($_SESSION,true));
}

/**
 * Get member connection details
 */
function _get_member_connection_details($mydexid) {
  global $debug;

  // Get the connection key, mydexid, drupal id
  $idp_url = MYDEX_IDP_PATH . '/' . APP_PATH . '/' . $mydexid . '/' . CONNECTION_NID;

  if($debug != '0'){
    syslog(LOG_INFO,"idpurl: " . print_r($idp_url,true));
  }

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $idp_url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $connectionDetailsRequest = curl_exec($ch);
  $connection_details = json_decode($connectionDetailsRequest);
  curl_close($ch);

  if($debug != '0'){
    syslog(LOG_INFO,"connection_details: " . print_r($connection_details,true));
  }

  return $connection_details;
}

/**
 * function to make API calls to get dataset data from the PDS
 * you can only get data from the datasets that the connection has access to.
 */
function _mydex_api_request($member_connection_details, $dataset) {
  global $debug;

  if($debug != '0'){
    syslog(LOG_INFO,"member_connection_details: " . print_r($member_connection_details,true));
  }

  $user_id = substr($member_connection_details->con_id, 0, (strpos($member_connection_details->con_id, '-')));

  $arguments_array = array(
    'api_key=' . API_KEY,
    'source_type=' . 'connection',
    'con_id=' . $member_connection_details->con_id,
    'key=' . $member_connection_details->con_key,
    'instance=' . '0',
    'dataset=' . $dataset,
  );

  $arguments = implode('&', $arguments_array);

  $mydex_api_url = MYDEX_API_PATH . '/api/pds/pds/' . $user_id . '.json?' . $arguments;

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $mydex_api_url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $mydex_request = curl_exec($ch);

  $mydex_request_decode = json_decode($mydex_request);
  curl_close($ch);

  return $mydex_request_decode;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo SETTINGS_APP_FULL_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="lib/bootstrap-3.2.0-dist/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="lib/jquery-ui-1.11.0.custom/jquery-ui.min.css" media="screen">
    <link rel="stylesheet" href="css/simulated-connection.css" media="screen">
  </head>
  <body class="simulated-connection-complete">

    <nav class="navbar navbar-default" role="navigation">
      <div class="top container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="#" class="active">Personal</a></li>
          </ul>

          <form class="navbar-form navbar-right" role="search">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Search">
              <button type="submit" class="btn"><i class="fa fa-search"></i></button>
            </div>
          </form>

          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Store locator</a></li>
            <?php if (isset($_SESSION['mydexid'])) : ?>
              <li><a href="logout.php">Sign out</a></li>
            <?php else: ?>
              <li><a href="#">Login</a></li>
            <?php endif; ?> 
          </ul>
        </div>
      </div>

       <div class="brand container">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img src="img/z9.png"/></a>
          </div>

          <div class="" id="">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#">Shop</a></li>
              <li><a href="#">Help</a></li>
              <li><a href="#"><?php echo SETTINGS_APP_PERSONAL_NAME; ?></a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
      
    <div class="main-content container">
      <header class="page-header">
        <div class="row">
          <h1>Congratulations you have successfully connected your Mydex PDS to <?php echo SETTINGS_APP_FULL_NAME; ?></h1>
        </div>
      </header>

      <main class="row">
        <section class="col-md-8">
          <?php if(!isset($_SESSION['ftc'])) : ?>
            <p>Congratulations you have completed your registration and connection. You now have a MydexID and Personal Data Store for life your <?php echo SETTINGS_APP_SHORT_NAME; ?> account, billing and itemised calls and SMS’ sent will be delivered to your PDS from now on. Simply click the button below to return to your <?php echo SETTINGS_APP_SHORT_NAME; ?> account.</p>
            <p>You can login to your Mydex Personal Data Store anytime to see your information or see when you used your MydexID online.</p>        
          <?php else: ?>
            <p>Congratulations you have completed your connection. Your <?php echo SETTINGS_APP_SHORT_NAME; ?> account, billing and itemised calls and SMS’ sent will be delivered to your PDS from now on. Simply click the button below to return to your <?php echo SETTINGS_APP_SHORT_NAME; ?> account.</p>
            <p>Remember to login to your Mydex Personal Data Store anytime to see your information or see when you used your MydexID online.</p>  
          <?php endif; ?>

          <div class="media">
            <a class="pull-left" href="#">
              <img class="media-object" src="img/mydex-keyring-shadow.png" alt="secure">
            </a>
            <div class="media-body">
              <ul class="list-unstyled mydex-secure-list">
                <li class="item first"><i class="fa fa-circle"></i><strong>Convenient</strong> - It helps you to manage many of the events in your life easily.</li>
                <li class="item"><i class="fa fa-circle"></i><strong>Complete control</strong> - You decide what you store, see and share.</li>
                <li class="item"><i class="fa fa-circle"></i><strong>Yours and yours alone...</strong> - Nobody can get to your data without your permission.</li>
                <li class="item last"><i class="fa fa-circle"></i><strong>Secure</strong> - The highest levels of security and encryption are used throughout.</li>
              </ul>
            </div>
          </div>
        </section>

        <section class="col-md-4">
          <div class="account-summary">
            <h3>Account details</h3>

            <ul class="list-group hr-border-bottom account-details-margin">
              <li class="list-group-item">
                Name: <span><?php echo SETTINGS_VAL_ACCOUNT_NAME; ?></span>
              </li>
              <li class="list-group-item">
                Account Number: <span><?php echo SETTINGS_VAL_CUSTOMER_NUM; ?></span>
              </li>
              <li class="list-group-item">
                Mobile Number: <span><?php echo SETTINGS_VAL_TEL_NUMBER; ?></span>
              </li>
              <li class="list-group-item">
                Contract Type: <span><?php echo SETTINGS_VAL_CONTRACT_LENGTH; ?></span>
              </li>
            </ul>

            <a href="<?php print MYDEX_PDS_PATH; ?>" type="button" class="btn btn-success btn-lg" target="_blank"><i class="fa fa-angle-double-right pull-right"></i>Go to your Mydex PDS</a>     

            <div class="find-out-more-about-mydex">
              <a href="http://mydex.org" type="button" class="btn btn-primary btn-lg mydex-link"><i class="fa fa-angle-double-right pull-right"></i>Find out more</a>
            </div>
          </div>
        </section>
      </main>
    </div>

    <script src="lib/jquery-1.11.1/jquery-1.11.1.min.js"></script>
    <script src="lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
  </body>
</html>