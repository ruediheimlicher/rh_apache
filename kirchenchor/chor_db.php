<?php
	$db = include "../bank.php";
	mysql_set_charset('utf8',$db);
	mysql_select_db("midi", $db); 
	return $db;
?>