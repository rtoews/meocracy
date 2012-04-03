<?php
session_start();

define('HOST', 'http://' . $_SERVER['HTTP_HOST']);
define('WEB_ROOT', '');
define('DOC_ROOT', $DOC_ROOT); // $DOC_ROOT initialized in global.php
define('HTML_EOL', "\n");

// Push Notifications via Urban Airship
//define('APPKEY','MBP_hBF2S6Ojh1r6_ZeuEw'); 
//define('PUSHSECRET', 'x3dFvI2VRfW6SrjpFPWQbA'); // Master Secret
//define('PUSHURL', 'https://go.urbanairship.com/api/push/broadcast/'); 

// SMS Notifications via SMSified
define('PUSHURL', 'https://api.smsified.com/v1/smsmessaging/outbound/17344300621/requests '); 
define('SMS_USER', 'retorick');
define('SMS_PWD', '142857');
define('SMS_SENDER', '17344300621');

// Database access
define('DB_HOST', 'localhost');
define('DB_DATABASE', 'meocracy');
define('DB_USERNAME', 'meo');
define('DB_PASSWORD', 'Badlsd22');
define('DB_PORT', 3306);

define('PHONEGAP', false);
define('SALT', 'f1b0nacci');
define('TODAY_DATE', date('Y-m-d'));
define('TODAY_DATETIME', date('Y-m-d H:i:s'));
define('DATE_FORMAT', 'F j, Y');

define('LOGO_PATH', '/images/logo/');
define('ANNOUNCEMENT_IMAGE_PATH', '/images/content/');
define('SPONSOR_IMAGE_PATH', '/images/people/');
define('REGION_CITY', 4);
define('REGION_COUNTY', 3);
define('REGION_STATE', 2);
define('REGION_NATION', 1);

define('ANNOUNCEMENT_TYPE', 1);
define('LEGISLATION_TYPE', 2);
?>
