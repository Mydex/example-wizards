<?php

/**
 *  This file simply ends the session on Z9 mobile and is called
 *  from clicking the 'Logout' link on the top-right of the demo.
 */


include 'config.php';

session_start();
session_destroy();

header('Location: index.php');