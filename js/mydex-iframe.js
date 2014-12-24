// Handle messages returned from the iframe.
MXD.receiveMessage(function(message){

  // Security - Only continue if the origin of the call mathces our idp url (prevent).
  if(message.origin === window.appSettings.API_IDP_URL) {

    mydex_response = JSON.parse(message.data);

    // Only execute the pdsCreateSuccess function if iframe return success 1.
    if (mydex_response.success && mydex_response.success == 1){
      if (mydex_response.type == 'pds-create'){
        // Email sent to member, which directs on to verification.php.
        // Create session to store MydexID for data retrieval later.
        simulatedConnectionStepOne.pdsCreateSuccess();
      }
      else if (mydex_response.type == 'ftc'){
        // FTC can be triggered from two places:
        //  - login and ftc.
        //  - pds register and then ftc on permissions page.

        if (document.location.href.indexOf('permissions.php') >= 0) {
          window.location.href = windowLocationOrigin + '/import.php';
        }
        else {
          // First time connection success.
          window.location.href = windowLocationOrigin + '/complete.php';
        }
      }
    }
    else if (mydex_response.success === 0){
      if (mydex_response.type == 'pds-create' && mydex_response.error == 'session'){
        // Error on the iframe.
        simulatedConnectionStepOne.pdsCreateSessionTimeoutError();
      }
      else if (mydex_response.type == 'ftc' && mydex_response.error == 'session'){
        // First time connection private key error.        
        window.location = window.location;
      }
    }
    else {
      // Handle error.
    }
  }
}, window.appSettings.API_IDP_URL);
