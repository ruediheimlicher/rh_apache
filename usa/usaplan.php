<?php
	$db=mysql_connect('myni3576.sql.mhs.ch','myni3576','ruelczhedcu');
	#$db=mysqli_connect('myni3576.sql.mhs.ch','myni3576','ruelczhedcu','meine_db');

	mysql_set_charset('utf8',$db);
	mysql_select_db("myni3576_usa", $db); 
return $db;
?>
