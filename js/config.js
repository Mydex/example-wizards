// This file holds global app config variables.

// Set the environment variables based on the url.
var windowLocationOrigin = document.location.protocol + '//' + document.location.host;
// Set global app config vars.
window.appVars = {
  name : 'z9-mobile',
  local_env : 'http://sim.local.mixcic.eu:8080' // The URL for your local environment.
};
// appSettings global variable - to set up all proxy calls.
// - added to window to counter issues experienced in IE.
window.appSettings = {
  API_IDP_URL     : 'https://sbx-idp.mydexid.org',
  APP_SESSION_URL : 'session.php',
  API_KEY         : 'dkhg834kfbhs9dehngs04hng9sfgh34k', // For demo purposes only. 
  CONN_ID         : '4042', // For demo purposes only.
  PERMISSIONS_URL : appVars.local_env+'/permissions.php',
  LOGIN_URL       : 'login.php',
  PROXY_PDS_CREATE: 'proxy-pds-create.php',
  RETURN_URL      : appVars.local_env+'/verification.php'
};
