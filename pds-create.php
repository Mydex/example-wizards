<?php 

/**
 *  This file allows a Z9 mobile user to become a Mydex Member and create a PDS.
 *  Users simply enter their details to create an account on https://sbx.mydex.org.
 *  Iframe calls are made from js/z9-mobile.js to update this page.
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
    <link rel="stylesheet" href="css/simulated-connection.css" media="screen">
  </head>

  <body class="js-pds-create">

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
          
          <div>
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
          <h1>There are four easy steps to creating a MydexID</h1>
        </div>

        <div class="row">        
          <div class="flow-steps clearfix">
            <ol class="progress-flow nav nav-pills nav-justified">
              <li class="flow-step active">
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
              <li class="flow-step inactive">
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
      </header>

      <main class="row">
        <div class="col-md-12">
          <div class="form-wrapper">
            <form method="post" action="" id="pdsCreateForm" class="pds-create-form">
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group first terms">
                    <div class="checkbox">
                      <label for="acceptTermsOfService" class="control-label ">
                        <input type="checkbox" id="acceptTermsOfService" name="acceptTermsOfService" value="1">
                        <span id="termsMessageDefault" for=""><?php echo GLOBAL_TXT_TERMS_DEFAULT ?></span>
                        <span id="termsMessageConfirm" style="display:none; color: #5CB85C; font-weight:bold;"><?php echo GLOBAL_TXT_TERMS_SELECT ?></span>
                      </label>
                    </div>
                    <div class="terms-link form-field-help">
                      <a href="http://sbx.mydex.org/terms" target="_blank"><?php echo GLOBAL_TXT_TERMS_LINK ?></a>
                    </div>
                  </div>
                  <div class="form-group" id="createMydexIdContainer">
                    <label class="control-label" for="createMydexID">Create a MydexID</label>
                    <div class="form-field-help">This is the name you will use to log in with in future and is yours for life</div>
                    <input class="form-control" type="text" id="createMydexID" name="createMydexID">
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="newEmail">Your Email address</label>
                    <input class="form-control" type="text" id="newEmail" name="newEmail">
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="newPassword">Create a Password</label>
                    <div class="mydex-password-strength form-field-help" id="mydex-pw-strength">
                      <div class="password-strength-title">Password strength:</div>
                      <div class="password-strength-text"></div>
                      <div class="password-indicator">
                        <div class="indicator"></div>
                      </div>
                    </div>
                    <input class="form-control" type="password" id="newPassword" name="newPassword">
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="newPasswordConfirm">Confirm Password</label>
                    <input class="form-control" type="password" id="newPasswordConfirm" name="newPasswordConfirm">
                  </div>

                  <div class="form-group hidden" id="authLogin">
                    <input class="form-control" type="password" id="auth" value="pdscreate" name="auth">
                  </div>
                    
                  <div class="form-group last">
                    <div>
                      <p>Next you will need to create a Private Key for your Mydex PDS</p>
                      <button type="submit" id="saveMydexID" name="" class="btn btn-success btn-lg"><i class="fa fa-angle-double-right pull-right"></i>Proceed</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class="form-wrapper">
            <form method="post" action="" id="privateKeyCreate" class="private-key-create" style="display:none;">
              <div class="row">
                <div class="col-md-8">
                  <div id="private-key-description" class="form-group" class="iframe-complete">
                    <div>
                      <p>Please enter a Private Key. This is really important as your private key is what is used to encrypt your Personal Data Store (PDS) and it is something ONLY you will know. We hold no record of it at all.</p>
                      <p>When you submit the Private Key form below, we will send you an email which you will need to read in order to verify your new MydexID.</p>
                      <p>Your Private Key must be:</p>
                      <ul>
                        <li>Different to your password</li>
                        <li>At least 8 characters in length</li>
                      </ul>
                    </div>
                  </div>

                  <div class="form-group">
                    <iframe src="" name="idpIframe" id="idpIframe" width="100%" height="320px" frameborder="0" scrolling="auto" webkitallowfullscreen="false">
                    </iframe>
                  </div>

                  <a href="#" id="retryPdsCreate" target="idpIframe" style="display: none;" name="retryPdsCreate" class="btn btn-success btn-lg"><i class="fa fa-angle-double-right"></i>Retry</a>
                </div>
              </div>
            </form>

            <div id="pdsCreateSuccessMessage" class="col-md-12" style="display:none; margin-bottom:80px;">
              <div class="media">
                <a class="pull-left" href="#">
                  <span class="glyphicon glyphicon-ok" style="font-size:65px; margin-right:15px; color: rgb(92, 184, 92);"></span>
                </a>
                <div class="media-body">
                  <h2 class="media-heading" style="margin-top:10px;">Thank you your MydexID and Personal Data Store have been created</h2>
                  <p>Email confirmation to verify your MydexId has been sent to the email address you provided, please go and click the link to take you to the next step</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <script src="lib/jquery-1.11.1/jquery-1.11.1.min.js"></script>
    <script src="lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
    <script src="lib/jquery-validation-1.13.0/jquery.validate.min.js"></script>
    <script src="js/config.js"></script>
    <script src="js/form-validation.js"></script>
    <script src="js/z9-mobile.js"></script>
    <script src="js/mxd.js"></script>
    <script src="js/mydex-iframe.js"></script>
  </body>
</html>
