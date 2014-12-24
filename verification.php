<?php 

/**
 *  This is the page the member is returned to when they receive a confirmation email
 *  for creating a new PDS account with Mydex. It is a UX page to confirm what the member
 *  has done, and direct them to what they need to do next.
 */

include 'config.php';
include 'settings.php';
session_start();

if(!isset($_SESSION['mydexid'])){
  //Set the mydex id from the url.
  $_SESSION['mydexid'] = $_GET['id'];
}

if($debug != '0'){
  syslog(LOG_INFO,"session: " . print_r($_SESSION,true));
  syslog(LOG_INFO,"post: " . print_r($_POST,true));
  syslog(LOG_INFO,"server: " . print_r($_SERVER,true));
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
  <body class="simulated-connection-verification">

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
          <h1>MydexID Verification</h1>
        </div>

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
              <li class="flow-step active">
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
          <div class="form-group">
            <h2>You have successfully verified your MydexID and initiated a connection to <?php echo SETTINGS_APP_FULL_NAME; ?>.</h2>
            <p>Please continue to set the permissions for your new connection.</p>
          </div>
          <div class="form-group">
            <a href="permissions.php" class="btn btn-success btn-lg"><i class="fa fa-angle-double-right pull-right"></i>Continue</a>
          </div>
        </div>
      </main>
    </div>

    <script src="lib/jquery-1.11.1/jquery-1.11.1.min.js"></script>
    <script src="lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
  </body>
</html>
