<?
/*

Chor Admin Datenbank 
*/

#include("pwd.php");

/* verbinden mit db */
	$db=mysql_connect("myni3576.sql.mhs.ch","myni3576","ruelczhedcu");
	/* nun ist der zugang zum db-server in der variable $db gespeichert */
	mysql_set_charset('utf8',$db);
	/* hier wird nun die eigentliche db ausgewählt */
	mysql_select_db("myni3576_kicho",$db); 

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Chor Admin</title>
 
<link href="chor.css" rel="stylesheet" type="text/css" />
</head>

<body class="liste">
	

<div><h1 class="lernmedien">Chor Admin</h1></div>
	
<!--<h2 class="lernmedien">Admin</h2>-->

<?	
#print '<p>benutzer;: '. $benutzer.'* pw: '. $passwort.'*</p>';

$pass = $_POST['pass'];
$user = $_POST['user'];
$test = $_POST['test'];
#print '<p>test: '.$test.'  pass: *'. $pass.'*</p>';
$user = "admin";

if (($test == 1) || ($passwort == "$pass"))
	{
	# print '<p class = "nameneingabe">Login OK</p>';
	print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form   action="chor.php" method = "post">';
	print '<input type="hidden" name="task" value ="">';
	print '<h3 class = "admin" >';
	print '<input type="submit" class="links40" name="textadmin" value="Chor Audio"style="width: 150px; margin-right:10px;"> Chor Audio';
	print '</h3>';
	print '</form>';

	print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form   action="chor_sql.php" method = "post">';
	print '<input type="hidden" name="task" value ="">';
	print '<h3 class = "admin" >';
	print '<input type="submit" class="links40" name="textadmin" value="Archiv" style="width: 150px; margin-right:10px;"> Chor Archiv Research';
	print '</h3>';
	print '</form>';

	print '<hr style=" width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form  action="chor_sql_save.php" method = "post">';

	print '<input type="hidden" name="task" value ="">';
	print '	<input type="hidden" name="test" value =1>';
	print '<h3 class = "admin" >';
	print '<input type="submit" class="links40" name="archivliste" value="ArchivListe" style="width: 150px; margin-right:10px;"> Archiv Liste';
	print '</h3>';
	print '</form>';

	print '<hr style=" width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form  action="chor_archiv_filter.php" method = "post">';
	print '<input type="hidden" name="task" value ="">';
	print '	<input type="hidden" name="test" value =1>';
	print '<h3 class = "admin" >';
	print '<input type="submit" class="links40" name="archivfilter" value="Archiv Filter" style="width: 150px; margin-right:10px;"> Archiv Filter';
	print '</h3>';
	print '</form>';

	print '<hr style=" width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form   action="chor_besucher.php" method = "post">';
	print '<input type="hidden" name="task" value ="besucher">';
	print '<h3 class = "admin"  >';
	print '<input type="submit" class="links40" name="statistik" value="Besucher"style="width: 150px; margin-right:10px;"> Besucher';
	print '</h3>';
	print '</form>';


	print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form action="chor_admin.php" ><h3 class = "admin" ><input type="submit" class="links40" value="zurück zu Admin" name="textfile" style="width: 150px; margin-right:10px;"></h3></form>';

	print '<hr style=" width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	?>

	<?

	$sent='no';
	$zeilenname=0;

	#$db->set_charset("utf8"); 
	/* sql-abfrage schicken */
	$result_medien = mysql_query("SELECT * FROM testarchiv ORDER BY nummer", $db);

	/* resultat in einer schleife auslesen */


	#print '<table width="759" border="1">';

	#while ($medien = mysql_fetch_array($result_medien) )
	{
		#$x=$medien['name'];
		#print '<p>Das ist ein Eintrag. Er ist: '. $x.' Punkt</p>';
	}

	# neuer Datensatz
	#print '<h2 class="lernmedien">neuer Datensatz</h2>';
	{
	}
	#$index=0;

	#mysql_data_seek($result_medien,$index);


	// Tableheader
	#$tableheaderstring  = '<th class="text" width="60">Name</th>';
	#$tableheaderstring  = '<th class="text breite60">Name</th>';


	#zu lerntest.pl
	#print '<form action="/cgi-bin/lerntest.pl" method="post" accept-charset="utf-8">';


	# Datensatz ändern
	$sent='no';
	$zeilenid = $_POST['edit'];
	$editid=$zeilenid[0];

	$editname=$zeilenid[1];
	$nn=$editname;

	$sent = $_POST['sent'];	
	#print 'sent: '.$sent.'<br>';
		
	if ($sent == 'yes') 
	{
		print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

		if ( isset($_POST['edit']))
		{
			print 'edit da<br>';
			if (isset($_POST['changeradio']))
			{
				$changeradio = $_POST['changeradio'];	
				#print 'changeradio: '.$changeradio.'<br>';
				#print 'editid: '.$editid.'<br>';
	
				$zeilenidstring = implode(', ',$zeilenid);
				#print 'zeilenid[0]: *'.$zeilenid[0].'*  zeilenidstring: '.$zeilenidstring.' <br>';
				$index=0;
				mysql_data_seek($result_medien,$index);
				#$zeile= mysql_fetch_assoc($result_medien);
			
				$result_idzeile = mysql_query("SELECT * FROM testarchiv WHERE id = $editid", $db);
			
				#$zeilenarray = mysql_fetch_row($result_idzeile);
				$zeilenarray = mysql_fetch_assoc($result_idzeile);
			
					#i: 0 data: 130
					#i: 1 data: Drahtbaum
					#i: 2 data: Ein Baum aus einem Stück Drahtseil mit silbrigen Blättern.
					#i: 3 data: Dokument
					#i: 4 data:
					#i: 5 data: Draht
					#i: 6 data: 1.00
					#i: 7 data: M O
					#i: 8 data: 12

				if ($changeradio == 'change')
				{
					#print 'CHANGE<br>';
					#for ($i=0;$i<count($zeilenarray);$i++)
					#{
					#print ' <p>i: '.$i.' data: '.$zeilenarray[$i].'</p>';;
					#}
					$changename=trim($zeilenarray['name']);
					$changebeschreibung=trim($zeilenarray['beschreibung']);
					$changeart=$zeilenarray['art'];
					$changegruppe=$zeilenarray['gruppe'];
					$changepreis=$zeilenarray['preis'];
					$changestufe=htmlentities($zeilenarray['stufe']);
					$changenummer=$zeilenarray['nummer'];
					$changeteaser=$zeilenarray['teaser'];
				
				
					print '<form action="adminconfirm.php" method="post">';
					$changedatensatzstring = '<h1 style="margin-left:40px">Daten</h1>';
					#$changedatensatzstring .= '<p class="nameneingabe">Name:<br><input size="40" maxlength="40" name="neuername" value =Hans"></p>';
					$changedatensatzstring .= '<p class="nameneingabe">Name:<br><input size="40" maxlength="40" name="changename" value ="'.$changename.'"></p>';
					$changedatensatzstring .= '<p class="nameneingabe">Art:<br><input size="40" maxlength="40" name="changeart" value ="'.$changeart.'"></p>';
					$changedatensatzstring .= '<p class="nameneingabe">Stufe:<br><input size="40" maxlength="40" name="changestufe" value ="'.$changestufe.'"></p>';
					$changedatensatzstring .= '<p class="nameneingabe">Gruppe:<br><input size="40" maxlength="40" name="changegruppe" value ="'.$changegruppe.'"></p>';
					$changedatensatzstring .= '<p class="nameneingabe">Preis:<br><input size="40" maxlength="40" name="changepreis" value ="'.$changepreis.'"></p>';
					$changedatensatzstring .= '<p class="nameneingabe">Nummer:<br><input size="40" maxlength="40" name="changenummer" value ="'.$changenummer.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Teaser:<br><input size="40" maxlength="40" name="changeteaser" value ="'.$changeteaser.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Teaser:<br><textarea rows="2" cols="45" name="changeteaser" </textarea>'.$changeteaser.'</textarea></textarea></p>';
					$changedatensatzstring .= '<p class="nameneingabe">Teaser:<br><textarea rows="2" cols="45" name="changeteaser">'.$changeteaser.'</textarea></p>';
					$changedatensatzstring .= '<p class="nameneingabe">Beschreibung:<br><textarea rows="5" cols="45" name="changebeschreibung">'.$changebeschreibung.'</textarea></p>';
					print '<input type="hidden" name="index" value ="'.$editid.'">';
					print '<input type="hidden" name="test" value =1>';
					print '<input type="hidden" name="task" value ="change">';
					print $changedatensatzstring;
			
			
			
					print '<br><p class="nameneingabe" ><input type="submit" name="speichern" value="Datensatz neu speichern"></p>';
					print '</form><br>';
				
				} # end if change
		
			
			
				if ($changeradio == 'delete')
				{ 	# delete
					#print '>DELETE<br>';
					print '<form action="adminconfirm.php"  method="post">';
					$deleteid=$zeilenid[0];
			
					$deletename=$zeilenarray['name'];
					print '	<input type="hidden" name="test" value ="'.$test.'">';
					print '<input type="hidden" name="name" value ="'.$deletename.'">';
					print '<input type="hidden" name="index" value ="'.$editid.'">';
					print '<input type="hidden" name="task" value ="delete">';
					# <h2 class="lernmedien">Fehler im neuen Datensatz</h2>
					print '<p ><h2 class="lernmedien"> Datensatz <strong>*'.$deletename.'*</strong> wirklich löschen?</h2>';
					print '<input type="submit"  class="links40" name="speichern"  value="Datensatz löschen"></p>';
					print '</form>';
					print '<form action=""  method="post">';
					print '<input type="hidden" name="task" value ="new">';
					print '<input type="hidden" name="sent" value ="no">';
				
					print '<input type="submit" class="links40"  name="speichern"  value="Abbrechen"></form>';
				
					
					
				}# end if delete
		
			}
			else
			{
				print 'kein changeradio: <br>';
			}

	
		} 
		else
		{
			print '<p class = "liste">kein Auftrag</p><br>';
		
		}
	}
	else if ($sent == 'err') # falsche Eingabe, Felder wieder laden
	{
		print '<h2 class="lernmedien">Fehler im neuen Datensatz</h2>';
	
		print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';
	
					print 'ERR<br>';
					/*
					$errname=$zeilenarray[1];
					#$changename=htmlentities($zeilenarray[1]);
					$errbeschreibung=$zeilenarray[2];
					$errart=$zeilenarray[3];
					$errgruppe=$zeilenarray[5];
					$errpreis=$zeilenarray[6];
					$errstufe=htmlentities($zeilenarray[7]);
					*/
					$errname = $_POST['name'];
					$errbeschreibung = $_POST['beschreibung'];
					$errart = $_POST['art'];
					$errgruppe = $_POST['gruppe'];
					$errpreis = $_POST['preis'];
					$errstufe = $_POST['stufe'];
					$errnummer = $_POST['nummer'];
					$errteaser = $_POST['teaser'];
	
				
					print '<form action="adminconfirm.php" method="post">';
					$errdatensatzstring = '<h1 style="margin-left:40px">Daten</h1>';
					#$changedatensatzstring .= '<p class="nameneingabe">Name:<br><input size="40" maxlength="40" name="neuername" value =Hans"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Name:<br><input size="40" maxlength="40" name="neuername" value ="'.$errname.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Art:<br><input size="40" maxlength="40" name="neueart" value ="'.$errart.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Stufe:<br><input size="40" maxlength="40" name="neuestufe" value ="'.$errstufe.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Gruppe:<br><input size="40" maxlength="40" name="neuegruppe" value ="'.$errgruppe.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Preis:<br><input size="40" maxlength="40" name="neuerpreis" value ="'.$errpreis.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Nummer:<br><input size="40" maxlength="40" name="neuenummer" value ="'.$errnummer.'"></p>';
					#$errdatensatzstring .= '<p class="nameneingabe">Teaser:<br><input size="40" maxlength="160" name="neuerteaser" value ="'.$errteaser.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Teaser:<br><textarea rows="2" cols="45" name="neuerteaser" <textarea>'.$errteaser.'</textarea></textarea></p>';

					$errdatensatzstring .= '<p class="nameneingabe">Beschreibung:<br><textarea rows="5" cols="45" name="neuebeschreibung" <textarea>'.$errbeschreibung.'</textarea></textarea></p>';
				
				
				
					#print '<input type="hidden" name="index" value ="'.$editid.'">';
					print '	<input type="hidden" name="test" value ="'.$test.'">';
					print '<input type="hidden" name="task" value ="new">';
					print $errdatensatzstring;
					print '<br><p class="nameneingabe" ><input type="submit" name="speichern" value="Datensatz neu speichern"></p>';
					print '</form><br>';

	}




	else
	{
		# neuer Datensatz
		print '<h2 class="lernmedien">neuer Datensatz</h2>';
		print '<form action="adminconfirm.php" method="post">';
		$neuerdatensatzstring = '<h1 style="margin-left:40px">Daten</h1>';
		$neuerdatensatzstring .= '<p class="nameneingabe">Name:<br><input size="40" maxlength="40" name="neuername"></p>';
		$neuerdatensatzstring .= '<p class="nameneingabe">Art:<br><input size="40" maxlength="40" name="neueart"></p>';
		$neuerdatensatzstring .= '<p class="nameneingabe">Stufe:<br><input size="40" maxlength="40" name="neuestufe"></p>';
		$neuerdatensatzstring .= '<p class="nameneingabe">Gruppe:<br><input size="40" maxlength="40" name="neuegruppe"></p>';
		$neuerdatensatzstring .= '<p class="nameneingabe">Preis:<br><input size="40" maxlength="40" name="neuerpreis"></p>';
		$neuerdatensatzstring .= '<p class="nameneingabe">Nummer:<br><input size="40" maxlength="40" name="neuenummer"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Teaser:<br><input size="40" maxlength="40" name="neuerteaser"></p>';
		$neuerdatensatzstring .= '<p class="nameneingabe">Teaser:<br><textarea rows="2" cols="45" name="neuerteaser"></textarea></p>';
		$neuerdatensatzstring .= '<p class="nameneingabe">Beschreibung:<br><textarea rows="5" cols="45" name="neuebeschreibung"></textarea></p>';
		print $neuerdatensatzstring;
		print '<input type="hidden" name="task" value ="new">';
		print '	<input type="hidden" name="test" value ="'.$test.'">';
		print '<br><p class="nameneingabe" ><input type="submit" name="speichern" value="neuen Datensatz speichern"></p>';
		print '</form><br>';

		#print 'kein sent<br>';
	}



	#print_r($zeilenname);
	print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';
	print '<h2 class="lernmedien">Daten redigieren oder löschen</h2>';
	print '<div class="adminabschnitt1">';

	#print '<div class = "admingruppenabschnitt>';

	print '<table class="admintabelle">';
	print '<tr >';


	// Tableheader
	#$tableheaderstring  = '<th class="text" width="60">Name</th>';
	$tableheaderstring  = '<th class="text breite30">Nr</th>';
	$tableheaderstring .= '<th class="text breite100">Name</th>';
	$tableheaderstring .= '<th class="text breite150">Teaser</th>';
	$tableheaderstring .= '<th class="text breite150">Beschreibung</th>';
	#$tableheaderstring .= '<th class="icon">Bilder</th>';
	$tableheaderstring .= '<th class="text breite60" >Art</th>';
	#$tableheaderstring .= '<th class="text breite60 zentriert" ><p class="tableheader">Stufe</p></th>';
	$tableheaderstring .= '<th class="text breite40 zentriert" >Stufe</th>';
	$tableheaderstring .= '<th class="text breite60 " >Gruppe</th>';
	$tableheaderstring .= '<th class="text breite40 zentriert" >Preis</th>';

	#$tableheaderstring .= '<th width="50" align="center" ><p class="tableheader">Icon</p></th>';


	$tableheaderstring .= '<th class="text breite30 zentriert" >edit</th>';
	$tableheaderstring .= '	</tr>';


	print $tableheaderstring;

	print '<form action="" method="post">';
	#print '<form action="/cgi-bin/lerntest.pl" method="post" accept-charset="UTF8">';
	$zeile=0;
	while ($medien = mysql_fetch_assoc($result_medien) )
	{
	
		#print ' <p>zeile: '.$zeile.' name: '.$medien['name'].' nummer: '.$medien['nummer'].' teaser: '.$medien['teaser'].'</p>';;
				

		print '<tr class = "admintabelletablerow">';
		//print '<td align="center">'.'<input type = "hidden" name= ' . $medien['id'] . '>';
		$ordnernummer=sprintf("%03d",$medien['id']);
		$bildnummer=$ordnernummer."0010";
		$bildlink = "Bilder/".$ordnernummer."/".$ordnernummer."0010".".jpg";
		print '<td class="listetabellenzahl">'.$medien['nummer'] . '</td>';
		print '<td class="listetabellentext">'. $medien['name'] . '</td>';
		print '<td class="listetabellentext">'.$medien['teaser'] . '</td>';
		print '<td class="listetabellentext">'.$medien['beschreibung'] . '</td>';
		#print '<td class="listetabellenicon"><img class="icon" src='. $bildlink .' alt="Icon" ></td>';
		print '<td class="listetabellentext">'.$medien['art'] . '</td>';
		print '<td class="listetabellentext">'.$medien['stufe'] . '</td>';
		print '<td class="listetabellentext">'.$medien['gruppe'] . '</td>';
		print '<td class="listetabellenzahl">'.$medien['preis'] . '</td>';
	
		print '<td class="listetabellenzahl">'.'<input type="checkbox" name="edit[]" method="post" value='. $medien['id'].' >'. '</td>';
		print '	</tr>';
	
		print '	<input type="hidden" name="test" value ="'.$test.'">';
		print '<input type="hidden" name=index[] value ='.$medien['id'].'>';
		print '<input type="hidden" name=name[] value ="'.$medien['name'].'">';
		print '<input type="hidden" name=preis[] value ='.$medien['preis'].'>';

	$zeile++;
	}
	print '<input type="hidden" name="sent" value="yes">';

	print '</table>';
	print '</div>'; # adminabschnitt1
	print '<br>';
	# einführung Radiobutton

	# change

	# Datensatz ändern. Bringt die Daten in die Eingabefelder
	print '<p class="nameneingabe" ><input type="radio" name="changeradio" method="post" value="change" checked >Datensatz ändern ';

	# Datensatz löschen
	print '<input type="radio" class="links40" name="changeradio" method="post" value="delete" >Datensatz löschen</p>';
	print '	<input type="hidden" name="test" value =1>';
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

	# change end

	print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form action="index.php" ><input type="submit" class="links40" value="zurück zur Startseite" name="textfile"></form>';
	mysql_close($db);

}
else
{
#print 'Nichts OK Eingabe falsch.';
print '<p class = "links40">Eingabe falsch! <form action="index.php" ><input type="submit" class="links40" value="zurück zur Startseite"></form></p> ';
}	

?>

</body>

</html>

