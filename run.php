<?php

	include("telnet.class.php");	

	$obj = new CTELNET($argv[1], $argv[2], $argv[3]);

	unset($obj);

?>