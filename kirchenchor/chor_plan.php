<?php
$db=mysql_connect('heimlich.mysql.db.internal','heimlich_admin','Ideur/0047!');

	mysql_set_charset('utf8',$db);
	mysql_select_db("heimlich_kicho", $db); 
	return $db;
?>