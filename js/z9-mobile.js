

// Initialise credentials object.
var credentialsData = {};

// Application controller - controls logic on various pages (defined by class on the body tag).
var appStepController = {
  init : function() {

    if($('body').hasClass('js-account-statement')) {
      // Instatiate the member login.
      memberLogin.init();
    }

    if($('body').hasClass('js-pds-create')) {
      // Instantiate app on the first step.
      simulatedConnectionStepOne.init();
    }

  }
};

// Handles the first step of the First Time Connection for members.
//  - Initiate member login
//  - Check connection status (if it already exists)
//  - Log in and connect, or notify connection is already enabled.
var memberLogin = {

  init : function() {
    $('#mydexLoginSubmit').click(function(e) {
      e.preventDefault();
      memberLogin.memberLoginFtc();
      $('.error').remove();
    });

    $('#mydexid, #password').focus(function(){
      $('.error').remove();
    });
  },

  setIdpIframe : function(url) {

    var $iframe = $('#idpIframe');

    // hide form / show iframe.
    $('#mydexLogin').hide();
    $('#idpIframeWrapper').show();

    // Add return url for mxd to be able to talk to the iframe
    var returnUrl = url + '#' + encodeURIComponent(document.location.href);

    // set iframe url & show it
    // - iframe loads private key and mydex-iframe.js listens for a response.
    // -- on success - calls memberLogin.goToPermissions();
    // -- on error - calls memberLogin.privateKeyError();
    $iframe.attr('src', returnUrl).show();
  },

  //this does the manipulation of the DOM on success
  memberLoginFtc : function(loginSuccessActions){
    // Create data object to pass to api.
    var data = {
      'mydexid' : $("#mydexid").val(),
      'password' : $("#password").val()
    };

    this.login(data,
      function(response) {
          if(response.error){
            var message = response.error.message;
            switch (message) {
              case "Following fields are missing or empty: password" :
                $('#password').after('<p class="error">' + "Please enter your password" + '</p>');
                break;

              case "Following fields are missing or empty: mydexid" :
                $('#password').after('<p class="error">' + "Please enter your MydexID" + '</p>');
                break;

              case "Following fields are missing or empty: mydexid, password" :
                $('#password').after('<p class="error">' + "Please enter your MydexID and password" + '</p>');
                break;

              case "Member does not exist." :
                $('#password').after('<p class="error">' + "Incorrect MydexID - please try again" + '</p>');
                break;

              case "Password is incorrect." :
                $('#password').after('<p class="error">' + "Incorrect password - please try again" + '</p>');
                break;

              default:
                $('#password').after('<p class="error">' + message + ' Please try again' + '</p>');
                break;
            }
          }else if (response.iframeurl) {
            memberLogin.setIdpIframe(response.iframeurl);
          }else if (response.connected) {
            memberLogin.alreadyConnected();
          }
          //  redirect the user to the permissions.php page if successful
          else if (response.mydexid) {
            window.location.href = windowLocationOrigin + '/permissions.php';
          }else {
            $('#password').after('<p class="error">' + 'Error logging in: ' + ' Please try again' + '</p>');
          }
        },
        //currently error messages in the PDS is not very informative-- have to go on what we have for now...
        function(xhr, errorType, error) {
          $('#password').after('<p class="error">Login error: please try again</p>');
          throw new Error(error);
        }
    );
  },

  // Check for user credentials using idp module.
  // Ths function does the api call
  login : function(data, success, failure){
    // Call the proxy php file to avoid cross-domain api issues.
    $.ajax({
      'url' : window.appSettings.LOGIN_URL,
      'type' : 'POST',
      'data' : JSON.stringify({ mydexid : data.mydexid, password : data.password }),
      'contentType' : 'application/json',
      'dataType' : 'json',

      success : function(response) {
          success.call(this,response);
      },
      error : failure
    });
  },

  goToPermissions : function() {
    window.location.href = window.appSettings.PERMISSIONS_URL;
  },

  privateKeyError : function() {
    //Log and handle errors.
  },

  alreadyConnected : function() {
    $('#loginWrapper').hide();
    $('.register-for-mydexid').hide();
    $('#alreadyConnected').show();
  }

};

// PDS Create.
//  - submits the pds create form.
//  - hides the pds create form on success and shows the Private Key form.
//  - On Private Key form success, page goes to confirmation email page - verification.php
var simulatedConnectionStepOne = {

  iframeUrl : '',
  ajaxProgress : '<span id="ajax-progress-indicator" class="ajax-progress"></span>',

  init : function() {

    $('#saveMydexID').click(function(e) {
      e.preventDefault();
      if( $('#pdsCreateForm').valid() ) {
        $('#saveMydexID').hide();
        simulatedConnectionStepOne.addAjaxProgress('#saveMydexID');
        simulatedConnectionStepOne.submitPdsCreate();
      }
    });

    // This button is only shown on timeout error - for reloading the iframe if session times out.
    $('#retryPdsCreate').click(function(e) {
      e.preventDefault();
      $('#retryPdsCreate').hide();
      simulatedConnectionStepOne.addAjaxProgress('#retryPdsCreate');
      simulatedConnectionStepOne.submitPdsCreate();
    });
  },

  addAjaxProgress: function(el){
    $(el).after(simulatedConnectionStepOne.ajaxProgress);
    $('#form-messages').remove();
  },

  removeAjaxProgress: function() {
    $('.ajax-progress').remove();
  },

  submitPdsCreate : function() {
    var termsAccepted = false;
    // Add terms of service ot var so value is passed for backend handling.
    if( $('#acceptTermsOfService').prop('checked') === true) {
      termsAccepted = true;
    }

    credentialsData = {
      terms     : termsAccepted,
      mydexid   : $('#createMydexID').val(),
      email     : $('#newEmail').val(),
      password  : $('#newPassword').val()
    };

    // create ajax call to check credentials
    this.createPds( credentialsData,

      function(response) {

        if(response.error){

          simulatedConnectionStepOne.removeAjaxProgress();
          $('#saveMydexID').show();

          var message = response.error.message;

          var messageHTML = '<div id="form-messages" class="form-alert-messages"><h4>The following need to be corrected before you can proceed:</h4><ul>';

          if (message == "Email already in use."){
            messageHTML += '<li>The email address supplied is already in use. Please specify an alternate email address.</li>';
          }
          else if (message == "Mydexid already in use."){
            messageHTML += '<li>The MydexID supplied is already in use. Please specify an alternate MydexID.</li>';
          }
          else {
            messageHTML += '<li>' + message + ' Please try again.</li>';
          }
          messageHTML += '</ul></div>';
          $('#saveMydexID').before(messageHTML);
        }
        else {
          // ftc = 0 so that it doesn't add the connection after the pds create
          // the connection is added on the permissions page by showing the FTC iframe.
          simulatedConnectionStepOne.iframeUrl = response + '&return_to=' + encodeURIComponent(window.appSettings.RETURN_URL) + '?id=' + credentialsData.mydexid + '&ftc=0' + '#' + encodeURIComponent(document.location.href);
          simulatedConnectionStepOne.showPrivateKeySubmit();
        }
      }
    );
  },

  // This is called from mydex-iframe.js.
  pdsCreateSuccess : function() {

    // Hide private key text and the iframe.
    $('#private-key-description, #idpIframe').hide();

    // Show success message.
    $('#pdsCreateSuccessMessage').show();

    this.createSession();
  },

  createSession : function() {

    $.ajax({
      'url' : window.appSettings.APP_SESSION_URL,
      'type' : 'POST',
      'contentType' : 'application/json',

      data : JSON.stringify({
        mydexid : credentialsData.mydexid
      }),

      'success' : function(response) {
        // Session valid
      }
    });
  },

  createPds : function(data, success, failure) {

    // Get iframe on success
    $.ajax({
      'url' : window.appSettings.PROXY_PDS_CREATE,
      'type' : 'POST',
      'contentType' : 'application/json',

      data : JSON.stringify({
        mydexid : data.mydexid,
        email: data.email,
        password : data.password,
        legal : 'TRUE',
        api_key : window.appSettings.API_KEY,
        connection_nid : window.appSettings.CONN_ID,
        api_idp_url : window.appSettings.API_IDP_URL
      }),

      'success' : function(response) {        
        if (success) {
          success.call(this,response);
        }
      },
      'error' : function(response) {
        // Handle Error
      }
    });
  },

  pdsCreateSessionTimeoutError : function() {
    var messageHTML = '<div id="form-messages" class="form-alert-messages"><h4>The request timed out - please try again.</h4><ul>';
    $('#idpIframe').height(60);
    $('#retryPdsCreate').before(messageHTML);
    $('#retryPdsCreate').show();
  },

  showPrivateKeySubmit : function() {
    $('#pdsCreateForm').hide();
    $('#privateKeyCreate').show();
    $('#idpIframe').attr('src', simulatedConnectionStepOne.iframeUrl).on('load', function(){
      simulatedConnectionStepOne.removeAjaxProgress();
    });
  },
};

// Initiate the application.
appStepController.init();