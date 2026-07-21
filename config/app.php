<?php

/**
 * Application root relative to the web server document root.
 *
 * Change this value if the project folder is renamed or deployed under a
 * different subdirectory. Leave it as an empty string when the application
 * is deployed at the document root.
 */
define('BASE_URL', '/mental-wellness-support-system');

/**
 * Local development shows a one-time reset link on screen because email
 * delivery is not configured yet. Set APP_ENV=production before deployment.
 */
define('APP_ENV', getenv('APP_ENV') ?: 'local');
define('PASSWORD_RESET_TTL_MINUTES', 30);
