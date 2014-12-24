<?php

/**
 *  This file confirms the connection has been successfully setup
 *  and allows the member to return to their Z9 account screen.
 */

include 'config.php';
include 'settings.php';
session_start();
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
<body class="simulated-connection-confirmation">
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
              <li class="flow-step inactive">
                <div class="line"></div>
                <div class="circle">
                  <div class="step-number">3</div>
                </div>
                <div class="step-label">Permissions</div>
              </li>
              <li class="flow-step active">
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

    <main>
      <div class="row">
        <div class="col-md-12">
          <h1>Congratulations</h1>
          <?php if(!isset($_SESSION['ftc'])) : ?>
            <p>Congratulations you have completed your registration and connection. You now have a MydexID and Personal Data Store for life your <?php echo SETTINGS_APP_SHORT_NAME; ?> account, billing and itemised calls and SMS’ sent will be delivered to your PDS from now on. Simply click the button below to return to your <?php echo SETTINGS_APP_SHORT_NAME; ?> account.</p>
            <p>You can login to your Mydex Personal Data Store anytime to see your information or see when you used your MydexID online.</p>
          <?php else: ?>
            <p>Congratulations you have completed your connection. Your <?php echo SETTINGS_APP_SHORT_NAME; ?> account, billing and itemised calls and SMS’ sent will be delivered to your PDS from now on. Simply click the button below to return to your <?php echo SETTINGS_APP_SHORT_NAME; ?> account.</p>
            <p>Remember to login to your Mydex Personal Data Store anytime to see your information or see when you used your MydexID online.</p>
          <?php endif; ?>
          
          <a href="complete.php" class="btn btn-success btn-lg"><i class="fa fa-angle-double-right pull-right"></i>Return to <?php echo SETTINGS_APP_SHORT_NAME; ?> account</a>
        </div>
      </div>
    </main>
  </div>

  <script src="lib/jquery-1.11.1/jquery-1.11.1.min.js"></script>
  <script src="lib/jquery-ui-1.11.0.custom/jquery-ui.min.js"></script>
  <script src="lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
</body>
</html>
