<?php

/**
 * Supports the use of plugins
 * 
 * @author Nathan Stevens
 * @author Alexandros Gougousis
 */
class plugin_loader {
  var $properties = null;
  var $user = null;
  var $plugins = null;
  
  public function __construct($params) {
    $this->properties = $params['properties'];
    $this->user = $params['user'];
  }
  
  /**
   * Returns a list of plugins
   * 
   * @return array
   */
  function getPlugins() {
    return $this->plugins;
  }
  
  /**
   * Adds a plugin
   * 
   * @param string $class_name
   * @param string $name
   * @param string $link
   */ 
  function addPlugin($class_name, $name, $link) {
    $this->plugins["$class_name"] = array (0=> "$name", 1=> "$link");
  }
}
?>
