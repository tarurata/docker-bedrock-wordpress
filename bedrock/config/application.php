<?php

/** @var string Directory containing all of the site's files */
$root_dir = dirname(__DIR__);

/** @var string Document Root */
$webroot_dir = $root_dir . '/web';

/**
 * Use Dotenv to set required environment variables and load .env file in root
 */
$dotenv = new Dotenv\Dotenv($root_dir);
$test = $root_dir . '/.env.'.$_SERVER['HTTP_HOST'];
if (file_exists($root_dir . '/.env.'.$_SERVER['HTTP_HOST'])) {
    $dotenv = new Dotenv\Dotenv($root_dir, '.env.'.$_SERVER['HTTP_HOST']);
    $dotenv->load();
    $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD', 'WP_HOME', 'WP_SITEURL']);
}
else if (file_exists($root_dir . '/.env')) {
    $dotenv->load();
    $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD', 'WP_HOME', 'WP_SITEURL']);
}

/**
 * Set up our global environment constant and load its config first
 * Default: production
 */
define('WP_ENV', getenv('WP_ENV') ?: 'production');

$env_config = __DIR__ . '/environments/' . WP_ENV . '.php';

if (file_exists($env_config)) {
    require_once $env_config;
}

/**
 * URLs
 */
define('WP_HOME', getenv('WP_HOME'));
define('WP_SITEURL', getenv('WP_SITEURL'));

/**
 * Custom Content Directory
 */
define('CONTENT_DIR', '/app');
define('WP_CONTENT_DIR', $webroot_dir . CONTENT_DIR);
define('WP_CONTENT_URL', WP_HOME . CONTENT_DIR);

/**
 * DB settings
 */
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix = getenv('DB_PREFIX') ?: 'wp_';

/**
 * Authentication Unique Keys and Salts
 */
define('AUTH_KEY', getenv('AUTH_KEY'));
define('SECURE_AUTH_KEY', getenv('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY', getenv('LOGGED_IN_KEY'));
define('NONCE_KEY', getenv('NONCE_KEY'));
define('AUTH_SALT', getenv('AUTH_SALT'));
define('SECURE_AUTH_SALT', getenv('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT', getenv('LOGGED_IN_SALT'));
define('NONCE_SALT', getenv('NONCE_SALT'));

/**
 * Custom Settings
 */
define('AUTOMATIC_UPDATER_DISABLED', true);
define('DISABLE_WP_CRON', getenv('DISABLE_WP_CRON') ?: false);
define('DISALLOW_FILE_EDIT', true);

/**
 * Bootstrap WordPress
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', $webroot_dir . '/wp/');
}

/* Customization to make this sytem workable for any projects */
$matches = [];
$dbname = getenv('DB_NAME');
if( preg_match('/([a-z]+)\.[a-z]+$/', $_SERVER['HTTP_HOST'], $matches)) {
    $dbname = $maches[1];
}

if( preg_match('/(cn|en|ja|kr|fr)\.'.$dbname.'\.[a-z]+$/', $_SERVER['HTTP_HOST'], $matches)) {
    $dbname = $maches[1]."_".$dbname;
    putenv('DB_NAME='.$dbname);
}


if($_SERVER['HTTP_HOST'] === 'localhost') {

}
else{
    if(WP_ENV !== 'production') {
        $dbname = WP_ENV.'_'.$dbname;
        putenv('DB_NAME='.$dbname);
    }

    if($_SERVER['HTTP_HOST']){
        putenv( 'WP_HOME=https://' . $_SERVER['HTTP_HOST'] );
        putenv( 'WP_SITEURL=https://' . $_SERVER['HTTP_HOST'] );
    }
}
define('FS_METHOD', "direct");
#define('UPLOADS', '../app/uploads/'.$_SERVER['HTTP_HOST'] );
define('UPLOADS', '/app/uploads/' );
