<?php
/**
Handels the loading of custom code from the account directory
Copyright (c) 2005-2009 Nathan Stevens

@author Nathan Stevens
@version 0.1 8-20-08
*/

class plugin_loader {
  var $properties = null;
  var $user = null;
  var $plugins = null;
  
  public function __construct($params) {
    $this->properties = $params['properties'];
    $this->user = $params['user'];
  }
  
  // Method to execute code of a certain plugin
  function doTask($task) {
    connectToDB();
    $class = $_GET['class']; // get the class name and create new instance
    $instance = new $class($this->properties, $this->user);
    $instance->doTask($task);
    closeDB();
  }
  
  // function to get the plugins
  function getPlugins() {
    return $this->plugins;
  }
  
  //function to add a plugin
  function addPlugin($class_name, $name, $link) {
    $this->plugins["$class_name"] = array (0=> "$name", 1=> "$link");
  }
}
?>
