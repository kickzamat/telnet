<?php

  // Example: php run.php 172.17.12.251 login passwd

	include("telnet.class.php");	

  if (!isset($argv[1]) || !isset($argv[2]) || !isset($argv[3])) {
    
    die (" # Error: input param is empty. Example: php run.php host login passwd\n");
    
  } // if 


	$obj = new CTELNET($argv[1], $argv[2], $argv[3]);

	var_dump($obj);

	unset($obj);

?>