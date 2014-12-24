<?php
/**
 *	This file contains the local application variables for this Simulated Connection.
 */

//Path to the json telephone data that will be imported
define('SETTINGS_TRANSACTION_DATA_PATH', 'data/tel_calls1.json');

// App title / name.
define('SETTINGS_APP_FULL_NAME', 'Z9 Mobile');
define('SETTINGS_APP_SHORT_NAME', 'Z9');
define('SETTINGS_APP_PERSONAL_NAME', 'My Z9');
define('SETTINGS_APP_LOGO_PATH', 'img/z9.png');

// Values for personal information.
define('SETTINGS_VAL_PERSONAL_FNAME', '[Your first name will be here]');
define('SETTINGS_VAL_PERSONAL_FANAME', '[Your family name will be here]');

// Dataset for the utility.
define('SETTINGS_FIELD_DATASET_UTILITY', 'field_ds_utility');
// Transaction data set.
define('SETTINGS_TRANSACTION_DATASET', 'ds_utility_tel_calls');

// Data field names.
define('SETTINGS_FIELD_SERVICE', 'field_utility_service');
define('SETTINGS_FIELD_SUPPLIER_NAME', 'field_utility_supplier_name');
define('SETTINGS_FIELD_TEL_NUMBER', 'field_utility_tel_number');
define('SETTINGS_FIELD_CUSTOMER_NUM', 'field_utility_customer_num');
define('SETTINGS_FIELD_ACCOUNT_NAME', 'field_utility_account_name');
define('SETTINGS_FIELD_UTILITY_PAYMENT_METHOD', 'field_utility_payment_method');

// Data values for the field names (a future iteration would make this dynamic and pulled from the session, but this is only a demo for now).
define('SETTINGS_VAL_SERVICE', 'Telephone');
define('SETTINGS_VAL_SUPPLIER_NAME', SETTINGS_APP_FULL_NAME);
define('SETTINGS_VAL_USERNAME', 'Z9user');
define('SETTINGS_VAL_TEL_NUMBER', '07777123456');
define('SETTINGS_VAL_CUSTOMER_NUM', 'Z9-1234567890');
define('SETTINGS_VAL_ACCOUNT_NAME', '[Your name will be here]');
define('SETTINGS_VAL_PAYMENT_METHOD', 'Direct Debit');
define('SETTINGS_VAL_CONTRACT_LENGTH', '18 Months');