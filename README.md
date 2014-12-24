# Z9-Mobile Simulated Connection
================================

## DESCRIPTION

Z9 Mobile is a fictional mobile telecoms company we have created to simulate the way in which a telecoms brand could connect to the Mydex platform and enable their customers to receive and view their account and transactional data in their PDS. The demonstration application shows the simplicity of allowing your users to:

* create a connection with your service to their Mydex PDS, or
* create a Mydex PDS and connect if they are not already registered.

It runs using iframes to connect to the Mydex sandbox environments. Accounts created through this demo can be accessed on [https://sbx.mydex.org](https://sbx.mydex.org).

We have attempted to make the code as simple as possible throughout this demo. Everything is raw PHP, HTML, CSS and jQuery to enable you and your development team to see exactly how to deconstruct the code. We have purposely avoided OOP and complex libraries, opting to only use jQuery, Bootstrap and Font Awesome. There is no compilation needed, it is simply a case of clone the repository, set up the site on your local webserver, set the return url in the js file and launch the demo.

For the purposes of this demo we have created a generic API Key, Connection Token and Connection ID. When you apply for a Mydex Developer account on [http://dev.mydex.org](http://dev.mydex.org), you will be supplied your own unique API Key. A connecting organisation will be provided their connection details when setting up their connection with Mydex. Please note, the generic API key, Connection Token and Connection ID supplied with this demo will only work for the purposes of this Z9 application.

## INSTALLATION

Clone this repository to your local web server and set it up as you would with any other site in your preferred webroot. Please note, there is no database required to run this demo - it is already set up using demo authentication details that authenticate with the Mydex platform.

The only configuration required is to set the local environment to match your web root or virtual host for the demo. This variable is important because it is used as the return redirect url when creating an account - it will be passed to the api and appear in the confirmation email when creating an account:

1. Open js/config.js
2. Inside the window.appVars object, change the local_env variable to match your web root e.g. 'http://z9.local:8888' (this is set as the default variable).

To launch the demo, simply enter the url to your chosen webroot for the demo, and the Z9 Mobile index.php will be displayed.

## CUSTOMISE FOR YOUR CONNECTION

No configuration is required for the Z9 demo to run. Configuration is only required if you are re-using this code for your organisation. There are two main config files which contain constants for your organisation's connection and authentication information: config.php and js/config.js.

####config.php
This is the main application configuration file. When setting up your connection with Mydex, you should have received the following details - which need to be saved in the config file: 

API&#95;KEY - Your developer API Key.

CONNECTION&#95;TOKEN - Connection Token.

CONNECTION&#95;ID - Your organisation's Connection ID.

There is also global $debug variable that can be activated by setting the value string to anything other than '0'. Setting this var to '0' switches off the debug.

####js/config.js
This is the main config file for the frontend. When setting up your connection you also need to change the follwoing constants:

API&#95;KEY - Your developer API Key.

CONN&#95;ID - Your organisation's Connection ID.

Don't forget to ensure the window.appVars.local_env variable is set to match your web root - see 'Installation' above.

### REAL-WORLD application

Inside permissions.php you will notice a curl request to check if the member is already connected. This endpoint is for demo purposes only. In a real-world application, you would store the variable and check if the member is already connected in your environment.

## FEEDBACK, SUPPORT AND HELPFUL LINKS

Mydex is a Community Interest Company and is dedicated to empowering individuals. We appreciate your feedback to continually improve and build our platform. If you have questions, feedback, or require support working with our platform, please visit the following links:

####The Mydex Developer Documentation site
[https://dev.mydex.org/](https://dev.mydex.org/) - Here you can read about our API's, Data Schema, request new datasets and apply for your API key.

####The Mydex Sandbox site
[https://sbx.mydex.org/](https://sbx.mydex.org/) - Here you can register for a PDS in our developer sandbox environment.

####The Mydex Community site
[https://community.mydex.org/](https://community.mydex.org/) - Answers to commonly asked questions can be found here, or new questions raised and discussed. The community site is open to anything from basic questions to technical questions.

####The Mydex PDS
[https://pds.mydex.org](https://pds.mydex.org) - Register for your own personal PDS and start taking control of your personal data today!

####Mydex Corporate site
[https://mydex.org](https://mydex.org) - Find out more about Mydex CIC

####Contacting Mydex
[https://dev.mydex.org/support.html](https://dev.mydex.org/support.html) - Other means to contact us can be found here.