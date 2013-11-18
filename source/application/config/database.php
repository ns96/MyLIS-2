<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

// $active_group = 'default';
$active_record = TRUE;

$db['lisdb']['hostname'] = 'localhost';
$db['lisdb']['username'] = 'root';
$db['lisdb']['password'] = 'mysqladmin';
$db['lisdb']['database'] = 'mylis0_lisdb';
$db['lisdb']['dbdriver'] = 'mysql';
$db['lisdb']['dbprefix'] = '';
$db['lisdb']['pconnect'] = FALSE;
$db['lisdb']['db_debug'] = TRUE;
$db['lisdb']['cache_on'] = FALSE;
$db['lisdb']['cachedir'] = '';
$db['lisdb']['char_set'] = 'utf8';
$db['lisdb']['dbcollat'] = 'utf8_general_ci';
$db['lisdb']['swap_pre'] = '';
$db['lisdb']['autoinit'] = TRUE;
$db['lisdb']['stricton'] = FALSE;

$db['lismdb']['hostname'] = 'localhost';
$db['lismdb']['username'] = 'root';
$db['lismdb']['password'] = 'mysqladmin';
$db['lismdb']['database'] = 'mylis0_lismdb';
$db['lismdb']['dbdriver'] = 'mysql';
$db['lismdb']['dbprefix'] = '';
$db['lismdb']['pconnect'] = FALSE;
$db['lismdb']['db_debug'] = TRUE;
$db['lismdb']['cache_on'] = FALSE;
$db['lismdb']['cachedir'] = '';
$db['lismdb']['char_set'] = 'utf8';
$db['lismdb']['dbcollat'] = 'utf8_general_ci';
$db['lismdb']['swap_pre'] = '';
$db['lismdb']['autoinit'] = TRUE;
$db['lismdb']['stricton'] = FALSE;

$db['lispdb']['hostname'] = 'localhost';
$db['lispdb']['username'] = 'root';
$db['lispdb']['password'] = 'mysqladmin';
$db['lispdb']['database'] = 'mylis0_lispdb';
$db['lispdb']['dbdriver'] = 'mysql';
$db['lispdb']['dbprefix'] = '';
$db['lispdb']['pconnect'] = FALSE;
$db['lispdb']['db_debug'] = TRUE;
$db['lispdb']['cache_on'] = FALSE;
$db['lispdb']['cachedir'] = '';
$db['lispdb']['char_set'] = 'utf8';
$db['lispdb']['dbcollat'] = 'utf8_general_ci';
$db['lispdb']['swap_pre'] = '';
$db['lispdb']['autoinit'] = TRUE;
$db['lispdb']['stricton'] = FALSE;

$db['lissdb']['hostname'] = 'localhost';
$db['lissdb']['username'] = 'root';
$db['lissdb']['password'] = 'mysqladmin';
$db['lissdb']['database'] = 'mylis0_lissdb';
$db['lissdb']['dbdriver'] = 'mysql';
$db['lissdb']['dbprefix'] = '';
$db['lissdb']['pconnect'] = FALSE;
$db['lissdb']['db_debug'] = TRUE;
$db['lissdb']['cache_on'] = FALSE;
$db['lissdb']['cachedir'] = '';
$db['lissdb']['char_set'] = 'utf8';
$db['lissdb']['dbcollat'] = 'utf8_general_ci';
$db['lissdb']['swap_pre'] = '';
$db['lissdb']['autoinit'] = TRUE;
$db['lissdb']['stricton'] = FALSE;
/* End of file database.php */
/* Location: ./application/config/database.php */