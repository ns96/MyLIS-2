<?php

/**
 * Manages the information related to group file uploading and storing, as well as 
 * the generation of html related to file uploading.
 * 
 * Used only by group controllers
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis 
 */
class Filemanager extends CI_Model {
  var $user = null;
  var $table = '';
  var $file_directory = '';
  var $file_url = '';
  var $plugin_directory = '';
  var $plugin_url = '';
  var $home_dir;
  var $properties;
  
  public function initialize($params) {
    $this->user = $params['user'];
    $this->table = $params['account'].'_files';
    $this->properties = $params['properties'];
    $this->home_dir = CIPATH."/accounts/mylis_".$params['account']."/";
    $home_url = base_url()."accounts/mylis_".$params['account']."/";
    
    $this->file_directory = $this->home_dir.'files/';
    $this->file_url = $home_url.'files/';
    $this->plugin_directory = $this->home_dir.'plugins/';
    $this->plugin_url = $home_url.'plugins/';
  }
  
  /**
   * Displays the upload fields. 
   * 
   * Number is used if multiple file browse are used
   * 
   * @param int $field_id
   * @return string 
   */
  function get_file_upload_field($field_id) {
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
  
  /**
   * Displays the upload form with the option of url upload.
   * 
   * Number is used if multiple file browse are used
   */
  function get_url_file_upload_field($field_id) {
    $html = 'Select Type :
    <select name="filetype_'.$field_id.'" class="input-smallmedium">
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
    <label for="fileupload_'.$field_id.'" class="control-label">:</label>
	<input id="fileupload_'.$field_id.'" name="fileupload_'.$field_id.'" class="filestyle" type="file" data-icon="false" style="position: fixed; left: -500px;">  
    </label><br>
    <input type="text" name="url_'.$field_id.'" placeholder="enter website url here" class="input-block-level" style="margin-top:6px">';
    
    return $html;
  }
  
  /**
   * Gets plane file upload with only two choices
   */
   function get_plain_url_file_upload_field($field_id) {
     $html = 'Select Type :
     <select name="filetype_'.$field_id.'">
     <option value="other">File</option>
     <option value="url">Website URL</option>
     </select>
     <input type="file" name="fileupload_'.$field_id.'">
     <input size="52" name="url_'.$field_id.'" value="enter website url here">';
     
     return $html;
   }
  
  /**
   * Uploads the file and adds it to the files table
   */
  function upload_file($field_id, $table_name, $table_id) {
    
    // check to see if there is storage space avialable
    if(!$this->has_space()) {
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
        $ext = $this->get_file_type($filename);
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
  
  /**
   * Updates a file
   * 
   * @param int $field_id
   * @param int $file_id
   */
  function update_file($field_id, $file_id) {
    
    // check to see if there is storage space avialable
    if(!$this->has_space()) {
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
      $this->delete_file_only($file_id); // delete any file that's there
      
      $ext = '';
      if($file_type == 'other') {
        $ext = $this->get_file_type($filename);
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
  
  /**
   * Deletes a file
   * 
   * @param int $file_id 
   */
  function delete_file($file_id) {
    $file_info = $this->get_file_info($file_id);
    
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
  
  /**
   * Deletes just the file and not the database entry
   * 
   * @param int $file_id 
   */
  function delete_file_only($file_id) {
    $file_info = $this->get_file_info($file_id);
    $fullname = $this->file_directory.'file_'.$file_id.'.'.$file_info['type'];
    if(file_exists($fullname)) {
      unlink($fullname); //or die ("Coudn't delete file ...");
    }
  }
  
  /**
   * Returns the file url
   * 
   * @param int $file_id
   * @return string 
   */
  function get_file_url($file_id) {
    $file_info = $this->get_file_info($file_id);
    $file_url = '';
    if($file_info['type'] == 'url') {
      $file_url = $file_info['url'];
    }
    else {
      $file_url = $this->file_url.'file_'.$file_id.'.'.$file_info['type'];
    }
    return $file_url;
  }
  
  /**
   * Returns a file info from the files table
   * 
   * @param int $file_id
   * @return array 
   */
  function get_file_info($file_id) {
    $lisdb = $this->load->database('lisdb',TRUE);
    $sql = "SELECT * FROM $this->table WHERE file_id=$file_id";
    $lisdb->where('file_id',$file_id);
    $records = $lisdb->get($this->table)->result_array();
    
    return $records[0];
  }
  
  /**
   * Gets the file extention (aka file type)
   * 
   * @param string $filename
   * @return string 
   */
  function get_file_type($filename) {
    $filetype = "txt"; // default is just .txt
    $sp = strrpos($filename, ".");
    if($sp !== false) {
      $filetype = substr($filename, $sp + 1);
    }
    return $filetype;
  }
  
  /**
   * Returns the current space usage
   * 
   * @return int 
   */
  function get_file_space_usage() {
    
    $lisdb = $this->load->database('lisdb',TRUE);
    $sql = "SELECT size, SUM(size) AS Total FROM $this->table";
    $records = $lisdb->query($sql)->result_array();
    $usage = $records[0]['Total'];
    return $usage;
  }
  
  /**
   * Checks to see if there is more file storage space
   * 
   * @return boolean 
   */
  function has_space() {
    $quota = $this->properties['storage.quota']*1048576; // convert to bytes
    $usage = $this->get_file_space_usage();
    if($usage < $quota) {
      return true;
    }
    else {
      return false;
    }
  }
  
  /**
   * Returns the quota usage text
   * 
   * @return string 
   */
  function get_storage_quota_text() {
    // now dislay the file usage information
    $usage = round($this->get_file_space_usage()/1048576, 2);
    $quota = $this->properties['storage.quota'];
    $per = round(($usage/$quota)*100);
    
    return 'Using '.$usage.'MB ('.$per.'%) of '.$quota.'MB file storage space ...';
  }
  
  function get_quota_usage(){
    $usage = round($this->get_file_space_usage()/1048576, 2);
    $total = $this->properties['storage.quota'];
    $quota['used'] = $usage;
    $quota['total'] = $total;
    return $quota;
  }
  
  /**
   * Updates the initiation file
   * 
   * @param array $new_props 
   */
  function modify_initiation_file($new_props) {
    foreach ($this->properties as $key => $value) {
      if(isset($new_props[$key])) {
        $this->properties[$key] = $new_props[$key];
      }
    }
    $this->writeInitiationFile();
  }
  
  /**
   * Writes out the initiation file
   * 
   * @param array $newProperties 
   */
  function write_initiation_file($newProperties) {
    $init_file = $this->home_dir.'conf/lis.ini';
    
    $fp = fopen($init_file, "w") or die("Couldn't open $init_file");
    
    foreach ($newProperties as $key => $value) {
      if(!empty($key) && $this->save_key($key)) {
        $text = $key.'='."$value\n";
        fwrite($fp, $text);
      }
    }
    
    fclose($fp);
  }
  
  /**
   *  Checks if a certain key should be saved
   * 
   * @param string $key
   * @return boolean 
   */
  function save_key($key) {
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
  
  /**
   * Gets the files in the pulgin directory
   */
  function load_plugin_files() {
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
