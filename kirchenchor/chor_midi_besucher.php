<?php

include("pwd.php");


/* verbinden mit db */	
	$db = include "chor_db.php";

if (isset($_POST['uploadok']))
{
	$upda=1;
	#ßheader("location:chor_upload.php?");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"content="text/html;charset=UTF-8"/>
<title>Chor MIDI Besucher</title>
<link href="chor.css"rel="stylesheet"type="text/css"/>
</head>
<body class="basic">

<?php
#date_default_timezone_set(Europe-Zurich);
$zeit = $_SERVER['REQUEST_TIME'];

$datum = date("d.m.Y",$zeit);
$uhrzeit = date("H:i",$zeit);
echo "Datum: ",$datum,"  Zeit: ",$uhrzeit," Uhr<br>";

$result_home = mysql_query("SELECT * FROM settings WHERE id= 1", $db)or die(print '<p>Beim Suchen nach home ist ein Fehler passiert: '.mysql_error().'</p>');
print 'mysql_error: *';
print mysql_error();
print '*<br>';

$set = mysql_fetch_array($result_home);
#print ' +';
#print_r($set);
#print ' +<br>';
if (isset($set['home_ip']))
{
print 'home_ip: '.$set['home_ip'].'<br>';
}
if (isset($set['ip']))
{
print 'ip: '.$set['ip'].'<br>';
}


print '<div  class = "adminabschnitt">';
print '<h2 class="eventtitel ">Apache Chor Besucher</h2>';
print '<form action="chor_admin.php" ><h3 class = "admin" ><input type="submit" class="links40" value="zurück zu Admin" name="textfile" style="width: 150px; margin-right:10px;"></h3></form>';
print '<form action="chor_midi.php" ><h3 class = "admin" ><input type="submit" class="links40" value="zurück zu Chor_MIDI" name="textfile" style="width: 150px; margin-right:10px;"></h3></form>';

#POST abfragen
#print_r($_POST);
print '</div>';

print '<div  class = "besucherabschnitt">';
print '<div  class = "besucherlisteabschnitt">';


$result_editdaten = mysql_query("SELECT * FROM besucher  ORDER BY besuch DESC", $db)or die(print '<p  >Beim Suchen nach editdaten ist ein Fehler passiert: '.mysql_error().'</p>');
		if (mysql_error())
		{
			print 'SELECT edit error:';
			print mysql_error();
			print '<br>';
		}



		$editdatenarray = array();
		
		
		print '<table >';
		print '<tr height = 24px>';
		print '<th class = "text" width = "200px">IP Besucher</td>';
		print '<th class = "text" width = "250px">IP Session</td>';
		print '<th class = "text" width = "80px">Zeit</td>';
		print '<th class = "text" width = "100px">Datum</td>';
		print '<th class = "text" width = "80px">Besuche</td>';
		print '<th class = "text" width = "80px">Register</td>';
		print '<th class = "text" width = "20px">S</td>';
		print '<th class = "text" width = "20px">A</td>';
		print '<th class = "text" width = "20px">T</td>';
		print '<th class = "text" width = "20px">B</td>';
		print '<th class = "text" width = "30px">all</td>';

		
		
		while ($editdaten = mysql_fetch_array($result_editdaten) )
		{
			print '<tr>';
			print '<td class = "text_right">'.$editdaten['ip'].'</td>';
			print '<td class = "text">'.$editdaten['session_id'].'</td>';
			print '<td class = "text_center">'.$editdaten['zeit'].'</td>';
			#date('d.m.Y', strtotime($row['Datum'])); 
			$home_datum = date('d.m.Y', strtotime($editdaten['datum']));
			#print '<td class = "text_center"> '.date('d.m.Y', strtotime($editdaten['datum'])).'</td>';
			print '<td class = "text_center"> '.$home_datum.'</td>';
			print '<td class = "text_center">'.$editdaten['besuch'].'</td>';
			print '<td class = "text_center">'.$editdaten['register'].'</td>';
			print '<td class = "text_center">'.$editdaten['sopran'].'</td>';
			print '<td class = "text_center">'.$editdaten['alt'].'</td>';
			print '<td class = "text_center">'.$editdaten['tenor'].'</td>';
			print '<td class = "text_center">'.$editdaten['bass'].'</td>';
			print '<td class = "text_center">'.$editdaten['alle'].'</td>';
			print '</td>';
			
			
			#$zeilendic["komponist"] = $komponistdaten['komponist'];
			#$zeilendic["komponist_vn"] = $komponistdaten['komponist_vn'];
			#$zeilendic["werk"] = $komponistdaten['werk'];
			print '</tr>';
		}
		
		print '</table><br>';
	

print '</div>';
print '</div>';

?>
    </body>
</html>
