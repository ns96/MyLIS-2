<?php

class Filemanager extends CI_Model {
  var $user = null;
  var $table = '';
  var $file_directory = '';
  var $file_url = '';
  var $plugin_directory = '';
  var $plugin_url = '';
  
  public function initialize($params) {
    $this->user = $params['user'];
    $this->table = $params['account'].'_files';
    $home_dir = CIPATH."/accounts/mylis_".$params['account']."/";
    $home_url = base_url()."accounts/mylis_".$params['account']."/";
    
    $this->file_directory = $home_dir.'files/';
    $this->file_url = $home_url.'files/';
    $this->plugin_directory = $home_dir.'plugins/';
    $this->plugin_url = $home_url.'plugins/';
  }
  
  // function to display the upload. number is used if multiple file browse are used
  function getFileUploadField($field_id) {
    $html = 'Select Type :
    <select name="filetype_'.$field_id.'">
    <option value="none">No File</option>
    <option value="pdf">PDF</option>
    <option value="doc">Word</option>
    <option value="ppt">Powerpoint</option>
    <option value="xls">Excel</option>
    <option value="rtf">RTF</option>
    <option value="odt">OO Text</option>
    <option value="odp">OO Impress</option>
    <option value="ods">OO Calc</option>
    <option value="zip">Zip</option>
    <option value="other">Other</option>
    </select>
    <input type="file" name="fileupload_'.$field_id.'">';
    
    return $html;
  }
  
  // function to display the upload form with the option of url upload. 
  // number is used if multiple file browse are used
  function getURLFileUploadField($field_id) {
    $html = 'Select Type :
    <select name="filetype_'.$field_id.'">
    <option value="none">No File</option>
    <option value="url">Website URL</option>
    <option value="pdf">PDF</option>
    <option value="doc">Word</option>
    <option value="ppt">Powerpoint</option>
    <option value="xls">Excel</option>
    <option value="rtf">RTF</option>
    <option value="odt">OO Text</option>
    <option value="odp">OO Impress</option>
    <option value="ods">OO Calc</option>
    <option value="zip">Zip</option>
    <option value="other">Other</option>
    </select>
    <input type="file" name="fileupload_'.$field_id.'">
    <input size="52" name="url_'.$field_id.'" value="enter website url here">';
    
    return $html;
  }
  
  // function to get plane file upload with only two choices
   function getPlainURLFileUploadField($field_id) {
     $html = 'Select Type :
     <select name="filetype_'.$field_id.'">
     <option value="other">File</option>
     <option value="url">Website URL</option>
     </select>
     <input type="file" name="fileupload_'.$field_id.'">
     <input size="52" name="url_'.$field_id.'" value="enter website url here">';
     
     return $html;
   }
  
  // function to add to the main table
  function uploadFile($field_id, $table_name, $table_id) {
    
    // check to see if there is storage space avialable
    if(!$this->hasSpace()) {
      return;
    }
    
    $file_id = '';
    $field = 'fileupload_'.$field_id;
    $file_type	 = $this->input->post('filetype_'.$field_id);
    $url	 = $this->input->post('url_'.$field_id); // used when linking to url
    $description = $this->input->post('description_'.$field_id);
    
    $tmp_name	= $_FILES[$field]['tmp_name'];
    $filename	= $_FILES[$field]['name'];
    $size	= $_FILES[$field]['size'];
    $userid = $this->user->userid;
    
    if(is_uploaded_file($tmp_name)) {
      $ext = '';
      if($file_type == 'other') {
        $ext = $this->getFileType($filename);
        $file_type = $ext;
      }
      else {
        $ext = $file_type;
      }
      $lisdb = $this->load->database('lisdb',TRUE);
      $sql = "INSERT INTO $this->table VALUES('', '$ext','$description', '', '$table_name', '$table_id', '$size', '$userid', '$userid')";
      $lisdb->query($sql);
      $file_id = $lisdb->insert_id();
      
      $new_name = $this->file_directory.'file_'.$file_id.'.'.$ext;
      move_uploaded_file($tmp_name, $new_name) or die ("Couldn't Upload file");
    }
    else if($file_type == 'url') {
      $url = checkURL($url);
      
      // add file info to files table now
      $sql = "INSERT INTO $this->table VALUES('', 'url','$description', '$url', '$table_name', '$table_id', '$size', '$userid', '$userid')";
      $lisdb->query($sql);
      $file_id = $lisdb->insert_id();
    }
    
    return $file_id;
  }
  
  // function to update a file
  function updateFile($field_id, $file_id) {
    global $conn;
    
    // check to see if there is storage space avialable
    if(!$this->hasSpace()) {
      return;
    }
    
    $field = 'fileupload_'.$field_id;
    $file_type	 = $this->input->post('filetype_'.$field_id);
    $url	 = $this->input->post('url_'.$field_id); // used when linking to url
    $description = $this->input->post('description_'.$field_id);
    
    $tmp_name	= $_FILES[$field]['tmp_name'];
    $filename	= $_FILES[$field]['name'];
    $size	= $_FILES[$field]['size'];
    $userid = $this->user->userid;
    
    if(is_uploaded_file($tmp_name)) {
      $this->deleteFileOnly($file_id); // delete any file that's there
      
      $ext = '';
      if($file_type == 'other') {
        $ext = $this->getFileType($filename);
        $file_type = $ext;
      }
      else {
        $ext = $file_type;
      }
      
      // add file info to files table now
      $lisdb = $this->load->database('lisdb',TRUE);
      $sql = "UPDATE $this->table SET type = '$ext', description = '$description', url = '$url', size = '$size' WHERE file_id = '$file_id'";
      $lisdb->query($sql);
      
      $new_name = $this->file_directory.'file_'.$file_id.'.'.$ext;
      move_uploaded_file($tmp_name, $new_name) or die ("Couldn't Upload file");
    }
    else if($file_type == 'url') {
      $url = checkURL($url);
      
      // add file info to files table now
      $lisdb = $this->load->database('lisdb',TRUE);
      $sql = "UPDATE $this->table SET type = 'url', description = '$description',url = '$url', size = '$size' WHERE file_id = '$file_id'";
      $lisdb->query($sql);
    }
  }
  
  // function to delete a file
  function deleteFile($file_id) {
    $file_info = $this->getFileInfo($file_id);
    
    if($file_info['type'] != 'url') {
	$fullname = $this->file_directory.'file_'.$file_id.'.'.$file_info['type'];
	if(file_exists($fullname)) {
	    unlink($fullname); //or die ("Coudn't delete file ...");
	}
    }
    
    // drop from database
    $lisdb = $this->load->database('lisdb',TRUE);
    $sql = "DELETE FROM $this->table WHERE file_id = '$file_id'";
    $lisdb->query($sql);
  }
  
  // function to just delete the file and not the database entry
  function deleteFileOnly($file_id) {
    $file_info = $this->getFileInfo($file_id);
    $fullname = $this->file_directory.'file_'.$file_id.'.'.$file_info['type'];
    if(file_exists($fullname)) {
      unlink($fullname); //or die ("Coudn't delete file ...");
    }
  }
  
  // function to return the file url
  function getFileURL($file_id) {
    $file_info = $this->getFileInfo($file_id);
    $file_url = '';
    if($file_info['type'] == 'url') {
      $file_url = $file_info['url'];
    }
    else {
      $file_url = $this->file_url.'file_'.$file_id.'.'.$file_info['type'];
    }
    return $file_url;
  }
  
  // fuction to return a file info from the files table
  function getFileInfo($file_id) {
    $lisdb = $this->load->database('lisdb',TRUE);
    $sql = "SELECT * FROM $this->table WHERE file_id=$file_id";
    $lisdb->where('file_id',$file_id);
    $records = $lisdb->get($this->table)->result_array();
    
    return $records[0];
  }
  
  // function to get the file extention aka file type
  function getFileType($filename) {
    $filetype = "txt"; // default is just .txt
    $sp = strrpos($filename, ".");
    if($sp !== false) {
      $filetype = substr($filename, $sp + 1);
    }
    return $filetype;
  }
  
  // function to return space usage
  function getFileSpaceUsage() {
    
    $lisdb = $this->load->database('lisdb',TRUE);
    $sql = "SELECT size, SUM(size) AS Total FROM $this->table";
    $records = $lisdb->query($sql)->result_array();
    $usage = $records[0]['Total'];
    return $usage;
  }
  
  // function to check to see if there is more file storage space
  function hasSpace() {
    $quota = $this->properties['storage.quota']*1048576; // convert to bytes
    $usage = $this->getFileSpaceUsage();
    if($usage < $quota) {
      return true;
    }
    else {
      return false;
    }
  }
  
  // function to return the quota usage text
  function getStorageQuotaText() {
    // now dislay the file usage information
    $usage = round($this->getFileSpaceUsage()/1048576, 2);
    $quota = $this->properties['storage.quota'];
    $per = round(($usage/$quota)*100);
    
    return 'Using '.$usage.'MB ('.$per.'%) of '.$quota.'MB file storage space ...';
  }
  
  function getQuotaUsage(){
    $usage = round($this->getFileSpaceUsage()/1048576, 2);
    $total = $this->properties['storage.quota'];
    $quota['used'] = $usage;
    $quota['total'] = $total;
    return $quota;
  }
  
  // function to update the initiation file
  function modifyInitiationFile($new_props) {
    foreach ($this->properties as $key => $value) {
      if(isset($new_props[$key])) {
        $this->properties[$key] = $new_props[$key];
      }
    }
    
    $this->writeInitiationFile();
  }
  
  // function to write out the initiation file
  function writeInitiationFile() {
    $home_dir = CIPATH."/application/accounts/".$params['account']."/";
    $init_file = $home_dir.'conf/lis.ini';
    
    $fp = fopen($init_file, "w") or die("Couldn't open $init_file");
    
    foreach ($this->properties as $key => $value) {
      if(!empty($key) && $this->saveKey($key)) {
        $text = $key.'='."$value\n";
        fwrite($fp, $text);
      }
    }
    
    fclose($fp);
  }
  
  // function to see if to save a certain key
  function saveKey($key) {
    $savekey = true;
    
    if(strstr($key, 'database')) { // don't save any database info
      $savekey = false;
    }
    else if(strstr($key, 'storage.cost')) { // don't save any cost info
      $savekey = false;
    }
    else if(strstr($key, 'home.')) { // don't save any url
      $savekey = false;
    }
    else if($key == 'script') { // don't save any script info
      $savekey = false;
    }
    
    return $savekey;
  }
  
  // function to get the files in the pulgin directory
  function loadPluginFiles() {
    if(!is_dir($this->plugin_directory)) {  // no plugin directory so just return
      return;
    }
    
    $dir = dir($this->plugin_directory);
    while($file = $dir->read()) {
        if($file != '.' && $file != '..' && $file != 'index.php' && strstr($file, '.php') && !strstr($file, '.php~'))  {
          require_once $this->plugin_directory.$file;
       }
    }
    
    // load any plugins under developement
    if(!is_dir($this->plugin_dev_directory)) {  // no plugin directory so just return
      return;
    }
    
    $dir = dir($this->plugin_dev_directory);
    while($file = $dir->read()) {
        if($file != '.' && $file != '..' && $file != 'index.php' && strstr($file, '.php') && !strstr($file, '.php~'))  {
          require_once $this->plugin_dev_directory.$file;
       }
    }
  }
}?>
