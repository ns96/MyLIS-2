<?php
class Admin_filemanager extends CI_Model {
    
    var $properties;
    var $base_dir;
    var $trash_dir;
    var $accounts_dir;
    var $conn;
    var $user;
    
public function initialize($params) {
    $this->properties = $params['properties'];
    $this->user = $params['user'];
    $this->base_dir = CIPATH;
    $this->accounts_dir = CIPATH."/accounts/";
    
    // create the trash directory if it doesn't exist
    $this->trash_dir = $this->accounts_dir.'trash/';
    if(!is_dir($this->trash_dir)) {
      mkdir($this->trash_dir, 0775);
    }
}

// function to modifiy the initiation file for a new account
  function modifyInitiationFile($account_id, $new_props) {
    $props = $this->readInitiationFile($account_id);
    
    foreach ($props as $key => $value) {
      if(isset($new_props[$key])) {
        $props[$key] = $new_props[$key];
      }
    }
    
    $this->writeInitiationFile($account_id, $props);
  }
  
  // function to read in the initiation file of an accounts
  function readInitiationFile($account_id) {
    $lis_dir = $this->accounts_dir.'mylis_'.$account_id.'/'; // the directory name
    $init_file = $lis_dir.'conf/lis.ini';
    
    $fp = fopen($init_file, "r") or die("Couldn't open $init_file");
    
    $props = array();
    while(!feof($fp)) {
      $line = fgets($fp, 1024);
      if(strstr($line, '=')) {
        $sa  = explode('=', $line);
        $props[trim($sa[0])] = trim($sa[1]);
      }
    }
    fclose($fp);
    
    return $props;
  }
  
  // function to write out the initiation file
  function writeInitiationFile($account_id, $props) {
    $lis_dir = $this->accounts_dir.'mylis_'.$account_id.'/'; // the directory name
    $init_file = $lis_dir.'conf/lis.ini';
    
    $fp = fopen($init_file, "w") or die("Couldn't open $init_file");
    
    foreach ($props as $key => $value) {
      $text = $key.'='."$value\n";
      fwrite($fp, $text);
    }
    
    fclose($fp);
  }

    // function to delete or rather move the files of an account to the trash directory
    function moveToTrash($account_id) {
	$lis_dir = $this->accounts_dir.'mylis_'.$account_id; // the directory name

	// copy this to the trah directory
	$lis_newdir = $this->trash_dir.'mylis_'.$account_id;
	if(!is_dir($lis_newdir)) {
	mkdir($lis_newdir, 0755);
	}
	$this->copyDir($lis_dir, $lis_newdir, 0755, false);
	$this->delDir($lis_dir);

	return $lis_newdir.'/';
    }
  
    /* function to copy a directory to a new directory
    copies everything from directory $fromDir to directory $toDir
    and sets up files mode $chmod
    taken from http://us3.php.net/copy */
    function copyDir($fromDir,$toDir,$chmod = 0757,$verbose = false) {
	//* Check for some errors
	$errors=array();
	$messages=array();
	if (!is_writable($toDir)) {
	$errors[]='target '.$toDir.' is not writable';
	}
	if (!is_dir($toDir)) {
	$errors[]='target '.$toDir.' is not a directory';
	}
	if (!is_dir($fromDir)) {
	$errors[]='source '.$fromDir.' is not a directory';
	}
	if (!empty($errors)) {
	if ($verbose) {
	    foreach($errors as $err) {
	    echo '<strong>Error</strong>: '.$err.'<br />';
	    }
	}
	return false;
	}

	$exceptions = array('.','..');

	// Processing
	$handle = opendir($fromDir);
	while (false !== ($item = readdir($handle))) {
	if (!in_array($item,$exceptions)) {
	    //* cleanup for trailing slashes in directories destinations
	    $from = str_replace('//','/',$fromDir.'/'.$item);
	    $to = str_replace('//','/',$toDir.'/'.$item);
	    //*/
	    if (is_file($from)) {
	    if (@copy($from,$to)) {
		chmod($to,$chmod);
		touch($to,filemtime($from)); // to track last modified time
		$messages[]='File copied from '.$from.' to '.$to;
	    }
	    else {
		$errors[]='cannot copy file from '.$from.' to '.$to;
	    }
	    }

	    if (is_dir($from)) {
	    if (@mkdir($to)) {
		chmod($to,$chmod);
		$messages[]='Directory created: '.$to;
	    }
	    else {
		$errors[]='cannot create directory '.$to;
	    }
	    $this->copyDir($from,$to,$chmod,$verbose);
	    }
	}
	}

	closedir($handle);
	// print any outputes
	if ($verbose) {
	foreach($errors as $err) {
	    echo '<strong>Error</strong>: '.$err.'<br />';
	}
	foreach($messages as $msg) {
	    echo $msg.'<br />';
	}
	}
	return true;
    }

    // function to completely del a directory
    function delDir($dirName) {
	if(empty($dirName)) {
	return;
	}
	if(file_exists($dirName)) {
	$dir = dir($dirName);
	while($file = $dir->read()) {
	    if($file != '.' && $file != '..') {
	    if(is_dir($dirName.'/'.$file)) {
		$this->delDir($dirName.'/'.$file);
	    } else {
		@unlink($dirName.'/'.$file) or die('File '.$dirName.'/'.$file.' couldn\'t be deleted!');
	    }
	    }
	}
	@rmdir($dirName.'/'.$file) or die('Folder '.$dirName.'/'.$file.' couldn\'t be deleted!');
	} else {
	//echo 'Folder "<b>'.$dirName.'</b>" doesn\'t exist.';
	}
    }
  
    /* function below this point can be considered static functions */
    // function to create a directory for a new account
    function createMyLISDirectory($account_id, $props) {
	$lis_dir = $this->accounts_dir.'mylis_'.$account_id; // create the directory
	if(!is_dir($lis_dir)) {
	    mkdir($lis_dir, 0755);
	}

	// now copy the files into this directory
	$lis_default = $this->accounts_dir.$this->properties['lis.default.account'];
	$this->copyDir($lis_default, $lis_dir, 0755, false);
	$this->modifyInitiationFile($account_id, $props);
    }
    
    
}