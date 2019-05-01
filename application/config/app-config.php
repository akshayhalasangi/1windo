<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|   http://example.com/
|
| If this is not set then CodeIgniter will try guess the protocol, domain
| and path to your installation. However, you should always configure this
| explicitly and never rely on auto-guessing, especially in production
| environments.
|
*/

define('APP_BASE_URL','http://1windo.com/App/');
//define('APP_BASE_URL','http://localhost/1Windo/');

/*
|--------------------------------------------------------------------------
| Encryption Key
| IMPORTANT: Don't change this EVER
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info. 
|
| http://codeigniter.com/user_guide/libraries/encryption.html
|
| Auto updated added on install
*/

define('APP_ENC_KEY','8b354ec165695581c8cfed57f8e93097');

/* Database credentials - Auto added on install */

/* The hostname of your database server. */
define('APP_DB_HOSTNAME','localhost');
/* The username used to connect to the database */
define('APP_DB_USERNAME','root');
/* The password used to connect to the database */
 //define('APP_DB_PASSWORD','1Windo@root');
define('APP_DB_PASSWORD','');
/* The name of the database you want to connect to */
 //define('APP_DB_NAME','1windo_app');
//define('APP_DB_NAME','newschema1');
define('APP_DB_NAME','1windo');