<?php
$title = "MyLIS Admin Login Error";
  
  echo "<html>";
  echo "<head>";
  echo "<title>$title</title>";
  echo "</head>";
  echo "<body bgcolor=\"white\">";
  echo "<center>";
  echo "<h2>";
  echo "Invalid username or password <br>";
  echo "To try again, click <a href='".base_url()."group/login?group=".$groupname."'>here</a>";
  echo "</h2>";
  echo "</center>";
  echo "</body>";
  echo "</html>";