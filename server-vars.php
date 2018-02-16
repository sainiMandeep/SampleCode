<?php
defined('APP_ENV') || define('APP_ENV', getenv('APP_ENV'));

define('SES_AUTH', getenv('SES_AUTH'));
define('SES_SSL', getenv('SES_SSL'));
define('SES_PORT', getenv('SES_PORT'));
define('SES_HOST', getenv('SES_HOST'));
define('SES_USERNAME', getenv('SES_USERNAME'));
define('SES_PASSWORD', getenv('SES_PASSWORD'));

define('DEV_EMAIL', getenv('DEV_EMAIL'));

define('DB_HOST', getenv('DB_HOST'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_NAME', getenv('DB_NAME'));
