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
print '<div id="admin">';
print '<div id="adminContent">';
print '<div  class = "adminabschnitt">';
print '<h2 class="eventtitel ">Chor Archiv save</h2>';
	print '<form action="chor_admin.php" ><h3 class = "admin" ><input type="submit" class="links40" value="zurück zu Admin" name="textfile" style="width: 150px; margin-right:10px;"></h3></form>';

#POST abfragen
print_r($_POST);
#print_r($_GET);
print '<br>';
$suchname=0;
if (strlen($_POST['names'][0]))
{
	$suchname = $_POST['names'][0];
	#print'suchname: '. $_POST['names'][0].'<br>';
}
elseif (isset($_POST['suchname']))
{
	$suchname = $_POST['suchname'];
}
$editrow=0;
$editid=-1;
if (isset($_POST['editrow']) && isset($_POST['editid']))
{
	$suchname = $_POST['suchname'];
	$editid = $_POST['editid'];
	#print 'editid: '.$editid.'<br>';
}
print '<h2 class="audiotitel ">suchen</h2>';

$result_komponist = mysql_query("SELECT * FROM testarchiv ", $db)or die(print '<p  >Beim Suchen nach komponist ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'select * error:';
	print mysql_error();
	print '<br>';
}

$komponistenarray = array();
$datenarray = array();
# array der ip gleich wie $ip
while ($komponist = mysql_fetch_array($result_komponist) )
{
	$datenarray[] = $komponist;
	#print_r($komponist);
	#print '<br>';
	$zeile = $komponist['komponist'];
	trim($zeile);
	
	#print 'zeile:'.$komponist['komponist'].'<br>';
	if (strlen($zeile)&&!(in_array(trim($zeile),$komponistenarray)) )
	{
		$komponistenarray[]=trim($zeile);
		#print '*** komponist:*'.$komponist['komponist'].'* l: '.strlen($zeile).'<br>';
	}
	
}

asort($komponistenarray,SORT_STRING);

	print '<form method="POST" action="">';
	print ' <select size="1" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" name="names[] ">';
	
	foreach($komponistenarray as $name)
	{
		#print 'komponist:'.$name.'<br>';
		if ($name == $suchname)
		{
			print '<option value="'.$name.'" selected>'.$name.'</option>';
		}
		else
		{
			print '<option  value="'.$name.'">'.$name.'</option>';
		}
	}

	print ' </select>';
	print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" value="suchen" >';
print '</form>';

print '</div>';

print '<div class = "listeabschnitt">';
$result_komponistdaten = mysql_query("SELECT * FROM testarchiv WHERE komponist = '$suchname'", $db)or die(print '<p  >Beim Suchen nach komponistdaten ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'SELECT suchname error:';
	print mysql_error();
	print '<br>';
}



$komponistdatenarray = array();
while ($komponistdaten = mysql_fetch_array($result_komponistdaten) )
{
	$zeilendic["komponist"] = $komponistdaten['komponist'];
	$zeilendic["komponist_vn"] = $komponistdaten['komponist_vn'];
	$zeilendic["werk"] = $komponistdaten['werk'];

	$zeilendic["teil"] = $komponistdaten['teil'];
	$zeilendic["datum"] = $komponistdaten['datum'];
	$zeilendic["anmerkung"] = $komponistdaten['anmerkung'];
	$zeilendic["id"] = $komponistdaten['id'];
	

	#print_r($komponistdaten);
	#print '<br>';
	#print 'name: '.$komponistdaten['komponist'].' Werk: '.$komponistdaten['werk'].' Teil: '.$komponistdaten['teil'].' Datum: '.$komponistdaten['tag'].'<br>';
	$komponistdatenarray[] = $zeilendic;
}
	#print_r($komponistdatenarray);
	#print '<br>';


if ($komponistdatenarray) # Daten vorhanden
{
	function cmp_name($a, $b)
	{
		if ($a['komponist'] == $b['komponist']) 
		{
			return 0;
		}
		return ($a['komponist'] < $b['komponist']) ? -1 : 1;
	}

	function cmp_datum($a, $b)
	{
	#print 'datum a: '.$a['datum'].' datum b: '.$b['datum'].'<br>';
	$del = '.';
		if (strpos($a['datum'],'/'))
		{
		$del = '/';
		}
		elseif(strpos($a['datum'],'.'))
		{
		$del = '.';
		}
		elseif(strpos($a['datum'],':'))
		{
		$del = ':';
		}
		$aa = explode($del,$a['datum']);
		#print_r($aa);
		#print '<br';

		if (strpos($b['datum'],'/'))
		{
		$del = '/';
		}
		elseif(strpos($b['datum'],'.'))
		{
		$del = '.';
		}
		elseif(strpos($b['datum'],':'))
		{
		$del = ':';
		}
		
		$bb = explode($del,$b['datum']);
		#print_r($bb);
		#print '<br';
	
		if ($aa[2] == $bb[2]) #Jahr ist gleich, weiter untersuchen
		{
			if ($aa[1] == $bb[1]) #Monat ist gleich, weiter untersuchen
			{
				return ($aa[0] < $bb[0]) ? -1 : 1;
			}
			else
			{
				return ($aa[1] < $bb[1]) ? -1 : 1;
			}
	
		}
		else
		{
		return ($aa[2] < $bb[2]) ? -1 : 1;
		}
	
		
		return -1;
	 }# cmp_datum


	#
	usort($komponistdatenarray,"cmp_datum");
	#sort($komponistdatenarray);
	
	print '<div class = "kopfabschnitt">';
	
	#print '<br><h3 class="erklaerung">Komponist: '.$zeilendic["komponist_vn"].' '.$suchname.'</h3><br>';

	print '<div class = "tabelleabschnitt">';


		print '<table >';
			print '<tr height = 24px>';
			print '<th class = "text" width = "100px">Datum</td>';
	
			print '<th class = "text" width = "300px">Werk</td>';
			print '<th class = "text" width = "300px">Teil</td>';
			print '<th class = "text" width = "100px">Art</td>';
			
	
			print '</tr>';

			foreach($komponistdatenarray as $datenzeile)
			{
				print '<tr height = 24px>';
				print '<td class = "drucktabellecenter">'.$datenzeile['datum'].'</td>';
	
				print '<td class = "drucktabelle">'.$datenzeile['werk'].'</td>';
				print '<td class = "drucktabelle">'.$datenzeile['teil'].'</td>';
				print '<td class = "drucktabellecenter">'.$datenzeile['art'].'</td>';

				print '</tr>';

			}# foreach
		print '</table><br>';
}# if komponistdatenarray

print '</div>'; # tabelleabschnitt

#datentabelle


print '<div class = "editabschnitt">';

	if ($editrow == "edit")
	{
	
		print 'editid: '.$editid.'<br>';
		$result_editdaten = mysql_query("SELECT * FROM testarchiv WHERE id = '$editid' ", $db)or die(print '<p  >Beim Suchen nach editdaten ist ein Fehler passiert: '.mysql_error().'</p>');
		if (mysql_error())
		{
			print 'SELECT edit error:';
			print mysql_error();
			print '<br>';
		}



		$editdatenarray = array();
		print '<table >';
		print '<tr height = 24px>';
		print '<th class = "text" width = "100px">Datum</td>';
		print '<th class = "text" width = "300px">Werk</td>';
		print '<th class = "text" width = "400px">Teil</td>';
		print '<th class = "text" width = "100px">Art</td>';

		
		
		while ($editdaten = mysql_fetch_array($result_editdaten) )
		{
			print '<tr>';
			print '<td class = "drucktabellecenter"><input type = "text" value = '.$editdaten['datum'].'>';
			print '<td class = "drucktabellecenter"><input type = "text">';
			print '<td class = "drucktabellecenter"><input type = "text">';
			print '</td>';
			
			
			$zeilendic["komponist"] = $komponistdaten['komponist'];
			$zeilendic["komponist_vn"] = $komponistdaten['komponist_vn'];
			$zeilendic["werk"] = $komponistdaten['werk'];
			print '</tr>';
		}
		
		print '</table><br>';
	}

print '<div class = "archivtabkopfabschnitt">';

print '<table >';
	print '<tr height = 24px>';
	print '<th class = "text" width = "100px">Datum</td>';
	
	print '<th class = "text" width = "300px">Werk</td>';
	print '<th class = "text" width = "400px">Teil</td>';
	print '<th class = "text" width = "100px">Art</td>';
	print '<th class = "text" width = "50px">edit</td>';
	print '</tr>';
print '</table><br>';

print '</div>'; # archivtabkopfabschnitt

print '<div class = "archivtababschnitt">';
print '<table >';
foreach($datenarray as $datenzeile)
{
	print '<tr height = 24px>';
	print '<td class = "drucktabellecenter" width = "100px">'.$datenzeile['datum'].'</td>';
	
	print '<td class = "drucktabelle" width = "300px">'.$datenzeile['werk'].'</td>';
	print '<td class = "drucktabelle" width = "400px">'.$datenzeile['teil'].'</td>';
	print '<td class = "drucktabellecenter" width = "100px">'.$datenzeile['art'].'</td>';
	print '<td class = "drucktabellecenter" width = "50px"><form action = ""; name = "editrow" method = "post">';
	print '	<input type="hidden" name="editrow" value ="1">';
	print '	<input type="hidden" name="suchname" value ='.$suchname.'>';
	print '	<input type="hidden" name="editid" value ='.$datenzeile['id'].'>';
	print '<input type="submit" class="links40" name="editrow" value="edit"></form></td>';
	
	print '</tr>';

}# foreach
	print '</table><br>';



print '<form action="chor_db_save.php"method="POST">';
print ' <input type="hidden"name="task"value="upload"/>';
print '	<input type="hidden" name="archivpfad" value="'.$archivpfad.'" type="file"/>'; 	# POST archivpfad
print ' <input type="submit"name="back"value="Daten laden"/>';
print '</form>';


print '<form action="choradmin.php"method="POST">';
print ' <input type="hidden"name="uploadok"value="0"/>';
print ' <input type="submit"name="back"value="zurück"/>';
print '</form>';



print '<br>';
# einführung Radiobutton

# change
print '<form action="chor_db_save.php"method="POST">';
# Datensatz ändern. Bringt die Daten in die Eingabefelder
print '<p class="nameneingabe" ><input type="radio" name="changeradio" method="post" value="change" checked >Datensatz ändern ';

# Datensatz löschen
print '<input type="radio" class="links40" name="changeradio" method="post" value="delete" >Datensatz löschen</p>';
print '	<input type="hidden" name="task" value ="change">';
print '	<input type="hidden" name="edit" value ="1">';
print '<input type="submit" class="links40" name="change" value="Weiterfahren"></form>';

#print '</div>'; # admingruppenabschnitt
#print '</div>'; # adminabschnitt1

print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

print '<h2 class="lernmedien">Tabelle neu laden</h2>';
print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';
# Tabelle laden

print '<p class="nameneingabe" >';
print '<form action="adminconfirm.php" method="post"
			enctype="multipart/form-data">
				<p class="nameneingabe" ><label for="file">Pfad für Tabelle suchen:</label>
					<input type="file" name="tabelle" id="tabellenfile" />
				</p>';

			# Pfade weitergeben
print '<input type="hidden" name="task" value ="upload">';
print '		<input type="hidden" name="gruppenordnerpfad" value="'.$gruppenordnerpfad.'" type="file"/>'; 	# POST gruppenordnerpfad
print '		<input type="hidden" name="bildordnerpfad" value="'.$bildordnerpfad.'" type="file"/>'; 			# POST bildordnerpfad relevant
print '		<input type="hidden" name="medium" value="'.$mediumordner.'" type="file"/>'; 					# POST medium
print '	<input type="hidden" name="test" value ="'.$test.'">';
# zu adminconfirm schicken
print '		<input type="submit" class="links40" name="submit" value="Tabelle laden" />';
	
print '</form>';

print '</div>'; # archivtababschnitt

print '</div>'; # editabschnitt
# change end






print '</div>';	# adminContent
print '</div>';	# admin
?>
    </body>
</html>
