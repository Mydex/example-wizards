<?php

/**
 * This is the landing page for the demo.
 * There are two journeys possible:
 * 1. 'Connect Z9 to my PDS' is for members' who already have an account on sbx.mydex.org.
 * 2. 'Register for a MydexID' allows a user to set up a Mydex PDS and create a Z9 connection in one journey.
 */

include 'config.php';
include 'settings.php';
session_start();

if($debug != '0') {
  syslog(LOG_INFO,"session: " . print_r($_SESSION,true));
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
  <body class="js-account-statement">

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
          <h1><?php echo SETTINGS_APP_PERSONAL_NAME; ?> account statement delivery options</h1>
        </div>
      </header>

      <main class="row">
        <section class="about-text col-md-8">
          <p class="summary">With <?php echo SETTINGS_APP_PERSONAL_NAME; ?> you can manage your <?php echo SETTINGS_APP_SHORT_NAME; ?> products, edit your preferences and update your details
          securely. You can manage each of your <?php echo SETTINGS_APP_SHORT_NAME; ?> products by selecting it from the list below.</p>

          <div class="mydex-online">
            <h4><strong>Online and Mydex</strong></h4>
            <p>Get your statements delivered securely and automatically to your personal data store provided by Mydex.</p>
            <br>
            <h4><strong>We have teamed up with Mydex!</strong></h4>
            <p>We will transfer your account information, billing history and itemised call and SMS history to your own Mydex service. Mydex also provide you with portable and reusable MydexID and a set of utilities and tools to make your life easier.</p>
          </div>

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

            <div id="loginWrapper" class="login-wrapper hr-border-bottom">
              <h3>Connect <?php echo SETTINGS_APP_SHORT_NAME; ?> to my PDS</h3>
              <div id="loginFormWrapper clearfix">
                <form method="post" action="login.php" id="mydexLogin" class="login">
                  <div class="">
                    <div class="form-group" id="mydexIdLogin">
                      <label class="control-label" for="mydexid">MydexID</label>
                      <input class="form-control" type="text" id="mydexid" name="mydexid">
                    </div>
                    <div class="form-group" id="passwordLogin">
                      <label class="control-label" for="password">Password</label>
                      <input class="form-control" type="password" id="password" name="password">
                    </div>

                    <div class="form-group last">
                      <button type="submit" id="mydexLoginSubmit" name="" class="btn btn-success btn-lg mydex-login-submit pull-right"><i class="fa fa-angle-double-right pull-right"></i>Connect to my PDS</button>
                    </div>
                  </div>
                </form>
              </div>

              <div id="idpIframeWrapper" style="display:none;">
                <h5><strong>Please enter your Mydex Private Key</strong></h5>
                <p>After entering your Private Key, you will see the <?php echo SETTINGS_APP_SHORT_NAME; ?> permissions where you can set the permissions for the connection and add it to your PDS.</p>
                <iframe id="idpIframe" name="idpIframe" src="" height="125px" width="100%" frameborder="0" allowfullscreen="false"></iframe>
                <a href="#" id="retryIframe" type="button" style="display:none;" class="btn btn-success btn-lg"><i class="fa fa-angle-double-right pull-right"></i>Retry</a>
              </div>
            </div>

            <div id="alreadyConnected" class="pds-already-connected hr-border-bottom" style="display:none;">
              <h3>Already connected!</h3>
              <p>You have already connected to <?php echo SETTINGS_APP_FULL_NAME; ?>. If you need to make changes or update your permissions, please login to your Mydex PDS.</p>
              <a href="https://sbx.mydex.org" type="button" class="btn btn-success btn-lg" target="_blank"><i class="fa fa-angle-double-right pull-right"></i>Go to your Mydex PDS</a>  
            </div>

            <div class="register-for-mydexid hr-border-bottom">
              <h3>Register for a MydexID</h3>
              <a href="pds-create.php" type="button" class="btn btn-primary btn-lg"><i class="fa fa-angle-double-right pull-right"></i>Get a MydexID and PDS and Connect</a>
            </div>

            <div class="find-out-more-about-mydex">
              <a href="http://mydex.org" type="button" class="btn btn-primary btn-lg mydex-link"><i class="fa fa-angle-double-right pull-right"></i>Find out more</a>
            </div>
          </div>
        </section>
      </main>
    </div>

    <script src="lib/jquery-1.11.1/jquery-1.11.1.min.js"></script>
    <script src="lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <script src="js/config.js"></script>
    <script src="js/mxd.js"></script>
    <script src="js/mydex-iframe.js"></script>
    <script src="js/z9-mobile.js"></script>

  </body>
</html>