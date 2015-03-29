<?php
	#$planfile = "/plans/archivplan.txt";
	#$fh = fopen($planfile, 'r');
	$planfile = "../../plans/archivplan.txt";
	if (file_exists($planfile)) 
	{
		$sommer = file_get_contents($planfile);
		#print_r($contents);
		#print'<br>';
		
		print'planfile exists: '.$sommer.'<br>';
	}
	else
	{
		print'kein planfile an: '.$planfile.'<br>';
	}


#	$db=mysql_connect("localhost","ruediheimlicher",$sommer);
#	mysql_set_charset('utf8',$db);
	
	
#return $db;
?>