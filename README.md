MyLIS-2
=======

The MyLIS Version 2.0 Based on CodeIgniter Framework and Twitter Bootstrap

**Requirements**

MyLIS 2.0 is based on CodeIgniter 2.1.4 and Bootstrap 2.3. As a result the requirements of the application are:

PHP version 5.1.6 or newer with **short_open_tag** turned On either in the php.ini or .htaccess file

MySQL version 5.0 or newer (maybe it can work with older MySQL version but it has not been tested for that)

Any web server that supports .htaccess (e.g Apache, ngix). For IIS the .htaccess files should be rewritten to valid web.config files. For the moment we have test it only for Apache.

**Installation**

1. Download the package from Github.
2. Unzip the package.
3. Upload the files to your server. Normally the index.php file will be at your root web directory. You can install under a users public_html directory but the "RewriteBase" in .htaccess file needs to be set to something like /~username/mylis2_install_directory/ 
4. Open the application/config/database.php file with a text editor and change the following database settings: Please note, the MySQL user requires the permission to create databases and tables. 
	* hostname
	* username
	* password

5. You should also change (in the same config file) the username and the password of the default administrator account of the application. This is controlled by the following parameters:
	* $config['mylis_admin_username']
	* $config['mylis_admin_password']
	
6. Now login as admin in the admin area and go to the ManageDB module. Create the 4 databases (with their tables) that are needed for the application to work by clicking on the relevant "+tables" buttons.
