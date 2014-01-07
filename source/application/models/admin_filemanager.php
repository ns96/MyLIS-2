<?php
class Admin_filemanager extends CI_Model {
    
    var $properties;
    var $base_dir;
    var $trash_dir;
    var $accounts_dir;
    var $conn;
    var $user;
    var $lismdb = null;
    
    public function initialize($params) {
	$this->properties = $params['properties'];
	$this->user = $params['user'];
	$this->base_dir = CIPATH;
	$this->accounts_dir = CIPATH."/accounts/";

	// create the trash directory if it doesn't exist
	$this->trash_dir = CIPATH.'/accounts/trash';
	if(!is_dir($this->trash_dir)) {
	    mkdir($this->trash_dir, 0775);
	}
    }

    public function get_logs(){
	$this->lismdb = $this->load->database('lismdb',TRUE);
	$sql = "SELECT * FROM update_log WHERE type='file'";
	$records = $this->lismdb->query($sql)->result_array();
	return $records;
    }

    public function remove_all_logs(){
	$this->lismdb = $this->load->database('lismdb',TRUE);
	$sql = "DELETE FROM update_log WHERE update_type='file'";
	$this->lismdb->query($sql);

	// if there are no records left in the database reset the count to zero
	$sql = "SELECT * FROM update_log";
	$records = $this->lismdb->query($sql)->result_array();

	if(count($records) == 0) {
	    $sql = "ALTER TABLE update_log AUTO_INCREMENT=0";
	    $this->lismdb->query($sql);
	}
    }
  
    public function remove_log_entries($entries){
	foreach($entries as $entry) {
	    $sql = "DELETE FROM update_log WHERE update_id='$entry'";
	    $this->lismdb->query($sql);
	}
    }
    
    // function to add a log entry
    function add_log($account_ids, $files, $type, $notes, $manager_id) {
	$ids;
	foreach($account_ids as $id) {
	    $ids .= $id.' ';
	}

	$f_list;
	foreach($files as $file) {
	    $f_list .= $file.' ';
	}

	if(empty($notes)) {
	    $notes = 'none';
	}

	$dt = getLISDateTime();

	$sql = "INSERT INTO update_log VALUES(' ', '$dt', 'file', '$ids', '$f_list', '$notes', '$manager_id')";
	$this->lismdb->query($sql);
    }
    
    // function to modifiy the initiation file for a new account
    function modify_initiation_file($account_id, $new_props) {
    $props = $this->readInitiationFile($account_id);
    
    foreach ($props as $key => $value) {
      if(isset($new_props[$key])) {
        $props[$key] = $new_props[$key];
      }
    }
    
    $this->writeInitiationFile($account_id, $props);
  }
  
    // function to read in the initiation file of an accounts
    function read_initiation_file($account_id) {
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
    function write_initiation_file($account_id, $props) {
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
    function move_to_trash($account_id) {
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
    and sets up files mode $chmod taken from http://us3.php.net/copy */
    function copy_dir($fromDir,$toDir,$chmod = 0757,$verbose = false) {
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
    function del_dir($dirName) {
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
    function create_MyLIS_directory($account_id, $props) {
	$lis_dir = $this->accounts_dir.'mylis_'.$account_id; // create the directory
	if(!is_dir($lis_dir)) {
	    mkdir($lis_dir, 0755);
	}

	// now copy the files into this directory
	$lis_default = $this->accounts_dir.$this->properties['lis.default.account'];
	$this->copyDir($lis_default, $lis_dir, 0755, false);
	$this->modifyInitiationFile($account_id, $props);
    }
    
    // function to get the file list that can be updated
    function get_file_list() {
	$f_list = array();
	$f_list[] = 'index.php;Web Page;Login Web Page';
	$f_list[] = 'password.png;PNG Image;Image for login page';
	return $f_list;
    }

    // function to get the list of files or directories in the trash directory
    function get_trash_files() {
	$files = array();

	$handle = opendir($this->trash_dir);
	if($handle) {
	    while(false !== ($file = readdir($handle))) {
		if($file != "." && $file != "..") {
		    $full_name = $this->trash_dir.$file;
		    $mdate = getLISDateTimeFrom(filemtime($full_name));

		    $type;
		    if(is_dir($full_name)) {
			$type = "Directory";
		    } else {
			$type = "Regular File";
		    }

		    $description;
		    if(eregi("mylis_",$full_name)) {
			$description = "Removed MyLIS Account";
		    } else {
			$description = "Unkown";
		    }

		    $files[] = "$file;$type;$mdate;$description";
		}
	    }

	    sort($files);
	    $this->files = $files; //?
	    closedir($handle);
	}

	return $files;
    }

    public function get_account_ids(){
	$sql = "SELECT account_id FROM accounts";
	$records = $this->lismdb->query($sql)->result_array();
	return $records;
    }
}