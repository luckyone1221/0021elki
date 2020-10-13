<?php
// HTTP
define('HTTP_SERVER', 'http://elki.berdicheva.net/');

// HTTPS
define('HTTPS_SERVER', 'http://elki.berdicheva.net/');

// DIR
define('DIR_APPLICATION', '/home/elki/domains/elki.berdicheva.net/public_html/catalog/');
define('DIR_SYSTEM', '/home/elki/domains/elki.berdicheva.net/public_html/system/');
define('DIR_IMAGE', '/home/elki/domains/elki.berdicheva.net/public_html/image/');
define('DIR_STORAGE', '/home/elki/domains/elki.berdicheva.net/storage/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'elki_db');
define('DB_PASSWORD', 'o6JHN0DE');
define('DB_DATABASE', 'elki_db');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');