<?
/* verbinden mit db */	
	$db=mysql_connect("myni3576.sql.mhs.ch","myni3576","ruelczhedcu");
	/* nun ist der zugang zum db-server in der variable $db gespeichert */
	mysql_set_charset('utf8',$db);
	/* hier wird nun die eigentliche db ausgewählt */
	mysql_select_db("myni3576_kicho",$db); 
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
<link href="chor.css"rel="stylesheet"type="text/css"/>
</head>
<body class="basic">

<?

$result_home = mysql_query("SELECT * FROM settings WHERE id= 0", $db)or die(print '<p>Beim Suchen nach home ist ein Fehler passiert: '.mysql_error().'</p>');
print mysql_error();
$set = mysql_fetch_array($result_home);


print '<div  class = "adminabschnitt">';
print '<h2 class="eventtitel ">Chor Besucher</h2>';
print '<form action="chor_admin.php" ><h3 class = "admin" ><input type="submit" class="links40" value="zurück zu Admin" name="textfile" style="width: 150px; margin-right:10px;"></h3></form>';

#POST abfragen
#print_r($_POST);
#print '<br>home_ip: '.$set['home_ip'].' ip: '.$set['ip'].'<br>';
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
		print '<th class = "text" width = "120px">IP Besucher</td>';
		print '<th class = "text" width = "300px">IP Session</td>';
		print '<th class = "text" width = "100px">Zeit</td>';
		print '<th class = "text" width = "100px">Datum</td>';
		print '<th class = "text" width = "150px">Anzahl Besuche</td>';

		
		
		while ($editdaten = mysql_fetch_array($result_editdaten) )
		{
			print '<tr>';
			print '<td class = "text_right">'.$editdaten['ip'].'</td>';
			print '<td class = "text">'.$editdaten['session_id'].'</td>';
			print '<td class = "text_center">'.$editdaten['zeit'].'</td>';
			print '<td class = "text_center"> '.$editdaten['datum'].'</td>';
			print '<td class = "text_center">'.$editdaten['besuch'].'</td>';
			print '</td>';
			
			
			$zeilendic["komponist"] = $komponistdaten['komponist'];
			$zeilendic["komponist_vn"] = $komponistdaten['komponist_vn'];
			$zeilendic["werk"] = $komponistdaten['werk'];
			print '</tr>';
		}
		
		print '</table><br>';
	

print '</div>';
print '</div>';

?>
    </body>
</html>
