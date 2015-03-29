<?php
	#$db=mysqli_connect('myni3576.sql.mhs.ch','myni3576','ruelczhedcu','meine_db');

	$db=mysql_connect('myni3576.sql.mhs.ch','myni3576','ruelczhedcu');

	
	if (!$db) 
	{
	print'ferienplan: keine db<br>';
	
	#die('Verbindung schlug fehl: ' . mysql_error());
    die('Connect Error (' . mysqli_connect_errno() . ') '
   #         . mysqli_connect_error());
	}
	else
	{
	print'ferienplan: Verbindung OK<br>';
	}

	mysql_set_charset('utf8',$db);
	
	
return $db;
?>
