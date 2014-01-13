MyLIS-2
=======

The MyLIS Version 2.0 Based on CodeIgniter Framework and Twitter Bootstrap

Requirements

MyLIS 2.0 is based on CodeIgniter 2.1.4 and Bootstrap 2.3. As a result the requirements of the application are:

PHP version 5.1.6 or newer

MySQL version 5.0 or newer (maybe it can work with older MySQL version but it has not been tested for that)

Any web server that supports .htaccess (e.g Apache, ngix). For IIS the .htaccess files should be rewritten to valid web.config files. For the moment we have test it only for Apache.

Installation

- Download the package from Github.
- Unzip the package.
- Upload the files to your server. Normally the 	index.php file will be at your root.
- Open the application/config/database.php file with a text editor and change the following database settings appropriately:
	hostname
	username
	password
- Open the application/config/config.php and change the value of the 	following 2 parameters:
	$config['mysql_myroot_userbane'] = 'root';
	$config['mysql_myroot_password'] = 'mysqladmin';
	
You can use the MySQL root credentials or you can create another MySQL 	user that has the permission to create databases and tables.

You should also change (in the same config file) the username and the password of the default administrator account of the application. This is controlled by the following parameters:
	$config['mylis_admin_username']
	$config['mylis_admin_password']
	
- Login as admin in the admin area and go to the ManageDB module. Create the 4 databases (with their tables) that are needed for the application to work by clicking on the relevant "+tables" buttons.
