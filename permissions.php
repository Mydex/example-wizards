<?php

/**
 *  This page displays the permissions datasets returned by the connection.
 *  It lists what access the Z9 Mobile connection will have to the member's PDS.
 */

  include 'config.php';
  include 'settings.php';

  session_start();

  if($debug != '0') {
    syslog(LOG_INFO,"session: " . print_r($_SESSION,true));
  }

  if (isset($_SESSION['mydexid'])) {
    $mydexid = $_SESSION['mydexid'];
  }
  else {
    $mydexid = null;
    $app_path = '/' . APP_PATH . '/';
    header('Location: index.php');
  }

  $api_key = API_KEY;
  $connection_nid = CONNECTION_NID;
  $connection_token = CONNECTION_TOKEN;

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
  <body class="simulated-connection-permissions">

    <nav class="navbar navbar-default" role="navigation">
      <div class="top container">
        <!-- Brand and toggle get grouped for better mobile display -->
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
            <?php if (isset($_SESSION['mydexid'])) :  ?>
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
          <h1>Your <?php echo SETTINGS_APP_SHORT_NAME; ?> Connection Permissions</h1>
        </div>

        <?php if(!isset($_SESSION['ftc'])) : ?>
          <div class="row">
            <div class="flow-steps clearfix">
              <ol class="progress-flow nav nav-pills nav-justified">
                <li class="flow-step inactive">
                  <div class="line"></div>
                  <div class="circle">
                    <div class="step-number">1</div>
                  </div>
                  <div class="step-label">Create MydexID</div>
                </li>
                <li class="flow-step inactive">
                  <div class="line"></div>
                  <div class="circle">
                    <div class="step-number">2</div>
                  </div>
                  <div class="step-label">MydexID Verification</div>
                </li>
                <li class="flow-step active">
                  <div class="line"></div>
                  <div class="circle">
                    <div class="step-number">3</div>
                  </div>
                  <div class="step-label">Permissions</div>
                </li>
                <li class="flow-step inactive">
                  <div class="line"></div>
                  <div class="circle">
                    <div class="step-number">4</div>
                  </div>
                  <div class="step-label">Return to <?php echo SETTINGS_APP_SHORT_NAME; ?></div>
                </li>
              </ol>
            </div>
          </div>
        <?php endif; ?>
      </header>

      <main class="row">
        <div class="col-md-12">
          <p>Mydex puts you in control of the information you exchange with us. Summarised below is the list of information we want to deliver to your personal data store directly and also the information we would like to be able to access ourselves, or be notified when it changes.</p>
          <?php
            /**
             * GET THE CONNECTION PERMISSIONS HERE
             * will have to get them from an endpoint on the idp app since
             * we should be able to verify the connection authentication
             */
            $api_idp_token_url = MYDEX_IDP_PATH . '/access-token/' . $connection_nid;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_idp_token_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $tokenRequest = curl_exec($ch);
            $short_access_token = json_decode($tokenRequest);
            curl_close($ch);

            if (isset($short_access_token->error)) {
              $return_data = array(
                'error' =>'Short access token error.',
              );
            }

            $authentication = hash('SHA512', $short_access_token.$connection_token);

            $idp_request_url = MYDEX_IDP_PATH . '/connection/' . $connection_nid . '/permissions';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $idp_request_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Authentication: ' . $authentication,
              'Short-Access-Token: ' . $short_access_token,
            ));

            $connectionPermissionsRequest = curl_exec($ch);
            $connection_permissions = json_decode($connectionPermissionsRequest, true);
            curl_close($ch);
          ?>

          <h2>Dataset Permissions for Z9</h2>

          <div class="permission-accordions">
          <?php if (isset($connection_permissions)) : ?>
            <?php foreach ($connection_permissions as $key => $dataset) { ?>
                <div class="accordion">
                  <h2 class="accordion-title"><?php echo $dataset['label']; ?></h2>
                  <div class="form-group">
                    <table class="table table-striped table-condensed permissions-table">
                      <thead>
                        <tr class="row odd">
                          <th class="table-head-field-name">Field name</th>
                          <th class="table-head-permission">Permission</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $i = 0;
                        foreach ($dataset['fields'] as $key => $fields) {
                          if ($key != 'id') {
                        ?>
                            <tr class="row <?php ($i % 2 == 0) ? 'even' : 'odd' ?>">
                              <td><?php echo $fields['label']; ?></td>
                              <?php
                                if (isset($fields['r']) && $fields['r'] && isset($fields['w']) && $fields['w']) {
                                  echo "<td>Read/Write</td>";
                                }
                                else if (isset($fields['r']) && $fields['r'] && !isset($fields['w'])) {
                                  echo "<td>Read</td>";
                                }
                                else if (!isset($fields['r']) && isset($fields['w']) && $fields['w']) {
                                  echo "<td>Write</td>";
                                }
                                else if (!isset($fields['r']) && !isset($fields['w'])) {
                                  echo "<td>None</td>";
                                }
                              ?>
                            </tr>
                        <?php
                          }
                          $i++;
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
            <?php } // end foreach permissions ?>
          <?php endif; ?>
          </div>

          <p>To approve the secure connection between your Z9 account and your personal data store please enter your Private Key in the secure window below to confirm your approval.</p>
          <p>You can change these settings at anytime from within your personal data store.</p>

          <?php

          // Check if the member is already connected.
          // NOTE!!!!
          // This bit needs to be done by the connection itself to wherever you keep your connection details!
          // This endpoint is for demo purposes only. It will not work with any other connection id.
          // In a real connection, the connection details should be stored in your database.
          // This would be an api call to query your own database.

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
          curl_close($ch);

          // if error is set then member connection details have not been found
          // so we need to ask for iframe url
          if (isset($authResult->error)) {
            echo "<p>Please enter your Private Key in the iframe below to initiate the First Time Connection to " . SETTINGS_APP_SHORT_NAME . "</p>";

            $request_data = array(
              'mydexid' => $mydexid,
              'api_key' => $api_key,
              'connection_nid' => $connection_nid,
              'connection_token_hash' => hash('SHA512', $connection_token),
              'iframe_expire' => '600',
              'iframe_content' =>array(
                'ftc-success-message' => 'Connection successful. Returning you now.',
              ),
            );

            $api_idp_token_url = MYDEX_IDP_PATH . '/access-token/' . $connection_nid;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_idp_token_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $tokenRequest = curl_exec($ch);
            $short_access_token = json_decode($tokenRequest);
            curl_close($ch);

            if (isset($short_access_token->error)) {
              $return_data = array(
                'error' =>'Short access token error.',
              );
            }

            $authentication = hash('SHA512', $short_access_token.$connection_token);

            $idp_request_url = MYDEX_IDP_PATH . '/api/connection';
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

            $ftcCreateRequest_raw = curl_exec($ch);            
            $ftcCreateRequest = json_decode($ftcCreateRequest_raw);
            $curl_error = curl_error($ch);
            
            curl_close($ch);

            if (!empty($curl_error)) {
              $return_data = array(
                'error' => $curl_error,
              );
            }else if (isset($ftcCreateRequest->error)) {
              $return_data = array(
                'error' => $ftcCreateRequest->error,
              );
            }else{
              $return_data = array(
                'iframeurl' => $ftcCreateRequest,
              );
            }

            if (isset($return_data['error'])) {
              echo "<p>There has been a problem retrieving the first time connection iframe to approve the connection. Please refresh the page.</p>";
            }
            else {
                // Demo check only - to see if https is being used. Normally it would always be https.
                if(isset($_SERVER['HTTPS'])) {
                  $isHttps = 'https://';
                }else{
                  $isHttps = 'http://';
                }
                $idp_iframe_url = $return_data['iframeurl'] . '#' . urlencode($isHttps . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            }

            if (isset($idp_iframe_url)) {
            ?>
              <iframe src="<?php echo $idp_iframe_url; ?>" name="idpIframe" id="idpIframe" width="100%" height="320px" frameborder="0" scrolling="auto" webkitallowfullscreen="false"></iframe>
        <?php
            } // end idp iframe url
          } //  end if user has already been connected
          else {
            echo "You are already connected to " . SETTINGS_APP_SHORT_NAME;
          }
        ?>
      </div>
    </main>
  </div>

    <script src="lib/jquery-1.11.1/jquery-1.11.1.min.js"></script>
    <script src="lib/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
    <script src="lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <script src="js/config.js"></script>
    <script src="js/mxd.js"></script>
    <script src="js/mydex-iframe.js"></script>

    <script>
      // Create permissions as accordion.
      $( ".accordion" ).accordion({collapsible : true, active : false, animate : 200, heightStyle: 'content'});

      // Hover states on the static widgets
      $( "#dialog-link, #icons li" ).hover(
        function() {
          $( this ).addClass( "ui-state-hover" );
        },
        function() {
          $( this ).removeClass( "ui-state-hover" );
        }
      );
    </script>
  </body>
</html>