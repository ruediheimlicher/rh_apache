<?php
	$db=mysql_connect('localhost','root','Ideur0047');

	mysql_set_charset('utf8',$db);
	#mysql_select_db("nova", $db); 
	return $db;
?>