<?php
session_start();

define('WEB_ROOT', '');
define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('HTML_EOL', "\n");

define('DB_HOST', '173.201.136.194');
define('DB_DATABASE', 'meocracy2');
define('DB_USERNAME', 'meocracy2');
define('DB_PASSWORD', 'Badlsd22');
define('DB_PORT', 3306);

define('SALT', 'f1b0nacci');
define('TODAY_DATE', date('Y-m-d'));
define('TODAY_DATETIME', date('Y-m-d H:i:s'));
define('DATE_FORMAT', 'F j, Y');

define('LOGO_PATH', '/images/photos/logo/');
define('ANNOUNCEMENT_IMAGE_PATH', '/images/photos/announcement/');
define('SPONSOR_IMAGE_PATH', '/images/photos/personnel/');
define('REGION_CITY', 4);
define('REGION_COUNTY', 3);
define('REGION_STATE', 2);
define('REGION_NATION', 1);

require_once(DOC_ROOT . '/includes/page_text.php');
?>
