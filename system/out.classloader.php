<?php

	function __autoload($filename) {
      require_once CLASSPATH."{$filename}.php";
   }

?>
