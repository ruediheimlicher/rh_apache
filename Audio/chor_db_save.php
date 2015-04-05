<?
/*

Admin Datenbank Chor
*/


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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chor confirm</title>
 
<link href="chor.css" rel="stylesheet" type="text/css" />
</head>

<body class="basic">


<?
print '<div id="admin">';
print '<div id="adminContent">';
	
print '<h2 class="eventtitel ">Chor Archiv Datenbank</h2>';

print_r($_POST);
print '<br>';
var_dump($_POST);

$task =  $_POST['task'];
print 'task: '.$task.'<br>';
$taskradio = $_POST['changeradio'];
#print 'taskradio: '.$taskradio.'<br>';
$changeoption = $_POST['changeoption'];
#print 'changeoption: '.$changeoption.'<br>';

$deleteangabe = $_POST['delete'];
#print 'deleteangabe: '.$deleteangabe.'<br>';

$test = $_POST['test'];
$test=1;
print 'test: '.$test.'<br>';
/*
print 'task: '.$task.'<br>';
$choose =  array($_POST['index']);
#$choosestring = implode(', ',$choose);
print 'choose: '.$choose[1][1].' count: '.count($choose[0]).'<br>';
print_r($choose[0]);
print '<br>';
$index =  array($_POST['index']);
print_r($index);
print '<br>';
print 'index: '.count($index).' zeile:  '.$index[0][0].'<br>';

$test =  $_POST['test'];
print 'test: '.$test.'<br>';
*/

print '<form action="chor_sql.php"method="POST">';
print ' <input type="hidden"name="task"value="0"/>';
print ' <input type="submit"name="back"value="zurück"/>';
print '</form>';




# Kontrolle
$artwhitelist  =   array('Dokument','CD','Werkstatt');
$stufewhitelist = array('U','M','O',' ');
$gruppewhitelist = array('Holz','Ton','Papier','Karton','Textil','Nähen','Sticken','Draht','Stricken','Häkeln','Diverses');

$paketwhitelist = array(array('Holz'),array('Papier','Karton'),array('Nähen','Sticken'),
array('Stricken','Häkeln'),array('Ton'),array('Draht','Diverses'));

$namemuster = '/^[a-z]{2}$/';
$textmuster = '/^[0-9A-Za-zäöüÄÖÜ ,\.\?]+$/';
$preismuster = '/^[0-9.]*$/';
$stufemuster = '/^[UOMS ]*$/';
$teasermuster = '/^[0-9A-Za-zäöüÄÖÜ ,\.\?\+\(\)\*=]*$/';
$beschreibungmuster = '/^[0-9A-Za-zäöüÄÖÜ ,\.\?\+\(\)\*=\<\>]*$/';
$nummermuster = '/^[0-9]*$/';


/*
^ : start of string
[ : beginning of character group
a-z : any lowercase letter
A-Z : any uppercase letter
0-9 : any digit
_ : underscore
] : end of character group
* : zero or more of the given characters
$ : end of string
*/
$errtask="";
if ($task == 'new')
{

	#print'neuer Datensatz<br>';
	
	#print '<h2 class="lernmedien">neuer Datensatz eingef&uuml;gt</h2>';
	#$index = $_POST['index'];
	$name = $_POST['neuername'];
	$art = $_POST['neueart'];
	$gruppe = $_POST['neuegruppe'];
	$stufe = $_POST['neuestufe'];
	$preis = $_POST['neuerpreis']+0;
	$nummer = $_POST['neuenummer']+0;
	$teaser = $_POST['neuerteaser'];
	
	$beschreibung = $_POST['neuebeschreibung'];
	
	
#	print '<p class = "liste">index:*'. $index.'*</p>';
#	print '<p class = "liste">name:*"'. $name.'"*</p>';
#	print '<p class = "liste">art:*'. $art.'*</p>';
#	print '<p class = "liste">gruppe:*"'. $gruppe.'"*</p>';
#	print '<p class = "liste">stufe:*'. $stufe.'*</p>';
#	print '<p class = "liste">preis:*'. $preis.'*</p>';
#	print '<p class = "liste">nummer:*'. $nummer.'*</p>';
#	print '<p class = "liste">beschreibung:*"'. $beschreibung.'"*</p>';
	
	$eingabe=array($_POST['eingabe']);
	#print_r($eingabe);
	/*
	$name=mysql_real_escape_string($name);
	$art=mysql_real_escape_string($art);
	$gruppe=mysql_real_escape_string($gruppe);
	$beschreibung=mysql_real_escape_string($beschreibung);
	*/
	# Eingabe checken
	$resultat=0; 
	$eingabefehler='';
	$fehlerlaenge=strlen($eingabefehler);
	#print 'Fehlerstring start: '.$eingabefehler.'<br>';
	# name
	
	if (strlen($name)==0)
	{
		$eingabefehler .= 'Das Feld für den Namen ist leer.<br>'; 
	}
	 
	else if (!preg_match($textmuster,$name))
	{
		$eingabefehler .= 'Das Feld für den Namen enthält ungültige Zeichen.<br>'; 
	}
	
	# Art
	if (strlen($art)==0)
	{
		$eingabefehler .= 'Das Feld für die Art ist leer.<br>'; 
	}

	else if (! in_array($art, $artwhitelist) ) 
	{
		$eingabefehler .= 'Das Feld für die Art enthält einen falschen Begriff.<br>'; 
	}
	
	# stufe
	
	if (strlen($stufe)==0)
	{
		$eingabefehler .= 'Das Feld für die Stufe ist leer.<br>'; 
	}
	/*
	else if (! in_array($stufe, $stufewhitelist) ) 
	{
		$eingabefehler .= 'Das Feld für die Stufe enthält einen falschen Begriff.<br>'; 
	}
	*/
	else if (!preg_match($stufemuster,$stufe))
	{
		$eingabefehler .= 'Das Feld für die Stufe enthält ungültige Zeichen.<br>'; 
	}
	
	
	# Gruppe
	if (strlen($gruppe)==0)
	{
		$eingabefehler .= 'Das Feld für die Gruppe ist leer.<br>'; 
	}
	else 
	{
	# Gruppe aufteilen
		$trenner=" ";
		$gruppenarray = explode($trenner,$gruppe);
		$schnittmengearray = array_intersect($gruppenarray,$gruppewhitelist);
	
		#if (! in_array($gruppe, $gruppewhitelist) ) 
		if (count($schnittmengearray)==0)
		{
			$eingabefehler .= 'Das Feld für die Gruppe enthält einen falschen Begriff.<br>'; 
		}
	}
	
	
	# preis
	if ($preis==0)
	{
		$eingabefehler .= 'Der Wert für den Preis ist 0.<br>';
	}
	else if (!preg_match($preismuster,$preis))
	{
		$eingabefehler .= 'Das Feld für den Preis enthält ein falsches Zeichen.<br>'; 
	}
 
	
	# beschreibung
	if (strlen($beschreibung)==0)
	{
		$eingabefehler .= 'Das Feld für die Beschreibung ist leer.<br>'; 
	}
	else if (!preg_match($beschreibungmuster,$beschreibung))	
	{
		$eingabefehler .= 'Das Feld für die Beschreibung enthält ein falsches Zeichen.<br>'; 
	}

	# teaser
	if (strlen($teaser)==0)
	{
		$eingabefehler .= 'Das Feld für den Teaser ist leer.<br>'; 
	}
	else if (!preg_match($beschreibungmuster,$teaser))	
	{
		$eingabefehler .= 'Das Feld für den Teaser enthält ein falsches Zeichen.<br>'; 
	}
	
	# nummer
	if (strlen($nummer)==0)
	{
		$eingabefehler .= 'Das Feld für die Nummer ist leer.<br>'; 
	}
	else if (!preg_match($nummermuster,$nummer))	
	{
		$eingabefehler .= 'Das Feld für die Nummer enthält ein falsches Zeichen.<br>'; 
	}
	
	
	
	
	if (strlen($eingabefehler)> $eingabefehler)
	{	
		print '<h3 class="lernmedien">Bei der Eingabe sind Fehler aufgetreten:</h3>';
		print '<p class="liste">'. $eingabefehler.'</p';
		$errtask="new";
	}
	else
	{
	# Muster
	#$result_insert = mysql_query("INSERT INTO lernmedien (id, name, beschreibung, art, gruppe, preis, stufe, nummer) VALUES (NULL, 'Abc', 'ysdfghjkl', 'CD', 'Holz', '1', 'US MS', '1', '1', NULL)");
		#print ' <p>Neuer Datensatz:  name: '.$medien['name'].' nummer: '.$medien['nummer'].' teaser: '.$medien['teaser'].'</p>';;

	
	$result_insert = mysql_query("INSERT INTO lernmedien (id, name, beschreibung, teaser, art, gruppe, preis, stufe, nummer) VALUES (NULL, '$name', '$beschreibung', '$teaser', '$art', '$gruppe', '$preis', '$stufe', '$nummer')");
	
	
	$resultat=mysql_affected_rows($db);
	#print 'INSERT error: *'.mysql_error().'*<br>';
	#print 'resultat affected_rows: *'.$resultat.'*<br>';
	
	
	#print '<p>Rückgabe von INSERT: *'. $result_insert.'*</p>';
	
	}
	if ($resultat==1)
	{
	print '<div class = " adminconfirmabschnitt1">';
		print '<h2 class="lernmedien">neuer Datensatz eingefügt</h2>';
		
		$index = $_POST['index'];
		$name = $_POST['neuername'];
		$art = $_POST['neueart'];
		$gruppe = $_POST['neuegruppe'];
		$stufe = $_POST['neuestufe'];
		$preis = $_POST['neuerpreis'];
		$nummer = $_POST['neuenummer'];
		$teaser = $_POST['neuerteaser'];
		$beschreibung = $_POST['neuebeschreibung'];
		
		print '<p class = "liste"><b>neue Daten:</b></p>';
		print '<p class = "liste">index:*'. $index.'*</p>';
		print '<p class = "liste">name:*'. $name.'*</p>';
		print '<p class = "liste">art:*'. $art.'*</p>';
		print '<p class = "liste">gruppe:*'. $gruppe.'*</p>';
		print '<p class = "liste">stufe:*'. $stufe.'*</p>';
		print '<p class = "liste">preis:*'. $preis.'*</p>';
		print '<p class = "liste">nummer:*'. $nummer.'*</p>';
		print '<p class = "liste">teaser:*'. $teaser.'*</p>';
		print '<p class = "liste">beschreibung:*'. $beschreibung.'*</p>';
	}
	else
	{
		print '<h3 class="lernmedien" <strong>Beim Einsetzen ist ein Fehler ist aufgetreten:</strong></h3><br>';
		print '*'.mysql_error().'*<br>';
	}
	print '</div>';
} # if new

if ($task == 'change')
{
	print'Datensatz ändern<br>';

	$datum = $_POST['datum'];
	$jahr = $_POST['jahr'];
	$pfr = $_POST['pfr'];
	$mitwirkung = $_POST['mitwirkung'];
	$art = $_POST['art'];
	$begleitung = $_POST['begleitung'];
	$quelle = $_POST['quelle'];
	$nr = $_POST['nr'];
	$komponist_vn = $_POST['komponist_vn'];
	$komponist = $_POST['komponist'];
	$werk = $_POST['werk'];
	$teil = $_POST['teil'];
	$anmerkung = $_POST['anmerkung'];
	$datensatznummer = $_POST['datensatznummer'];
	
	#$result_change = mysql_query("UPDATE testarchiv SET datum = '$datum', jahr = '$jahr', pfr = '$pfr', mitwirkung = '$mitwirkung',art = '$art',begleitung = '$begleitung', quelle = '$quelle',nr = '$nr' ,komponist_vn = '$komponist_vn' ,komponist = '$komponist' ,werk = '$werk' ,teil = '$teil' ,anmerkung = '$anmerkung' ,datensatznummer = '$datensatznummer'  WHERE id = '$index'");
	$resultat=mysql_affected_rows($db);
	#print 'UPDATE error: *'.mysql_error().'*<br>';
	#print 'resultat affected_rows: *'.$resultat.'*<br>';
	#print '<p class = "liste">Rückgabe von UPDATE: *'. $result_change.'*</p>';
	
	if ($result_change==1)
	{
		print '<div class = "adminconfirmabschnitt1">';
		print '<h2 class="lernmedien">Datensatz wurde geändert</h2>';
		/*
		$index = $_POST['index'];
		$name = $_POST['changename'];
		$art = $_POST['changeart'];
		$gruppe = $_POST['changegruppe'];
		$stufe = $_POST['changestufe'];
		$preis = $_POST['changepreis'];
		$beschreibung = $_POST['changebeschreibung'];
		*/
		print '<p class = "liste"><b>neue Daten:</b></p>';
		#print '<p class = "liste">datum:*' $datum.'*</p>';
		print '<p class = "liste">jahr:*'.$jahr.'*</p>';
		print '<p class = "liste">pfr:*'.$pfr.'*</p>';
		print '<p class = "liste">mitwirkung:*'.$mitwirkung.'*</p>';
		print '<p class = "liste">art:*'.$art.'*</p>';
		print '<p class = "liste">begleitung:*'.$begleitung.'*</p>';
		print '<p class = "liste">quelle:*'.$quelle.'*</p>';
		print '<p class = "liste">nr:*'.$nr.'*</p>';
		print '<p class = "liste">komponist_vn:*'.$komponist_vn.'*</p>';
		print '<p class = "liste">komponist:*'.$komponist.'*</p>';
		print '<p class = "liste">werk:*'.$werk.'*</p>';
		print '<p class = "liste">teil:*'.$teil.'*</p>';
		print '<p class = "liste">anmerkung:*'.$anmerkung.'*</p>';
		print '<p class = "liste">datensatznummer:*'.$datensatznummer.'*</p>';
		print '</div>';
	}
	else
	{
		print '<h2 class="lernmedien"><strong>Beim Ändern ist ein Fehler ist aufgetreten:<br>';
		print '*'.mysql_error().'*<br></h2>';
	}
	
	
} # if change


if ($task == 'delete')
{
	#print'Datensatz loeschen<br>';
	print '<h2 class="lernmedien">Datensatz l&ouml;schen:</h2>';
	$index = $_POST['index'];
	#print 'Datensatz ID: '.$index.'*<br>';
	$deletename = $_POST['name'];
	#print 'deletename: '.$deletename.'<br>';

	$result_delete = mysql_query("DELETE FROM testarchiv  WHERE id = '$index'");
	$resultat=mysql_affected_rows($db);
	#print 'DELETE error: *'.mysql_error().'*<br>';
	#print 'resultat affected_rows: *'.$resultat.'*<br>';
	#print '<p>Rückgabe von DELETE: *'. $result_delete.'*</p>';
	if ($resultat==1)
	{
		print '<h3 class="lernmedien">Datensatz '.$deletename.' ist gelöscht.</h3><br>';
	}
	else
	{
		print '<h2 class="lernmedien"><strong>Beim Löschen ist ein Fehler ist aufgetreten:<br>';
		print '*'.mysql_error().'*<br></h2>';
	}
	
} # delete

# Daten aendern
# update students set first_name='Suba' where rec_id=678;


#$db->set_charset("utf8"); 

# ******************************
# Überprüfen
# ******************************

#print '<h2 class="lernmedien">Admin Kontrolle</h2>';
/* sql-abfrage schicken */
#$result_medien = mysql_query("SELECT * FROM lernmedien", $db);

/* resultat in einer schleife auslesen */


#print '<table width="759" border="1">';

#while ($medien = mysql_fetch_array($result_medien) )
#{
#	$x=$medien['name'];
#	print '<p>Das ist ein Eintrag. *'. $x.'*</p>';
#}

# neuer Datensatz
#print '<h2 class="lernmedien">neuer Datensatz</h2>';


#$index=0;

#mysql_data_seek($result_medien,$index);


// Tableheader
#$tableheaderstring  = '<th class="text" width="60">Name</th>';
#$tableheaderstring  = '<th class="text breite60">Name</th>';


#zu lerntest.pl
#print '<form action="/cgi-bin/lerntest.pl" method="post" accept-charset="utf-8">';


if ($task == 'upload')
{
  print_r($_FILES);
  if ($_FILES["tabelle"]["error"] > 0)
  {
  	print '<p class="nameneingabe" >File ist nicht da. Fehler :<br>'. $_FILES["tabelle"]["error"] .'</p>';
  }
else
{

	# Pfad fuer Tabelle suchen
	print '<form action="" method="post"
				enctype="multipart/form-data">
					<p class="nameneingabe" ><label for="file">Pfad für Tabelle suchen:</label>
						<input type="file" name="tabelle" id="tabellenfile" />
					</p>';
	
				# Pfade weitergeben
	print '<input type="hidden" name="task" value ="upload">';
	#print '		<input type="hidden" name="gruppenordnerpfad" value="'.$gruppenordnerpfad.'" type="file"/>'; 	# POST gruppenordnerpfad
	#print '		<input type="hidden" name="bildordnerpfad" value="'.$bildordnerpfad.'" type="file"/>'; 			# POST bildordnerpfad relevant
	#print '		<input type="hidden" name="medium" value="'.$mediumordner.'" type="file"/>'; 					# POST medium
	#print '		<input type="hidden" name="test" value ="'.$test.'">';
# zu adminconfirm schicken
print '		<input type="submit" class="links40" name="submit" value="Tabelle laden" />';
	
print '</form>';


#	print '<div class = " adminconfirmabschnitt1">';
	print '<h2 class="lernmedien">Das File ist da</h2>';
	print '<p class="nameneingabe" >Upload: '. $_FILES["tabelle"]["name"] .'</p>';
	print '<p class="nameneingabe" >Type: '. $_FILES["tabelle"]["type"] .'</p>';
	print '<p class="nameneingabe" >Size: '. $_FILES["tabelle"]["size"] .'</p>';
	print '<p class="nameneingabe" >Stored in: '. $_FILES["tabelle"]["tmp_name"] .'</p>';
	print '<p class="nameneingabe" >Error: '. $_FILES["tabelle"]["error"] .'</p>';

	# array mit Tabellenkopf	
	$result_tabellenkopf = mysql_query("SELECT * FROM tabellenkopf ", $db);
	$tabellenkopf=array();


	while ($kolonne = mysql_fetch_array($result_tabellenkopf) )
	{
		$pos = $kolonne['pos'];
		$bez = $kolonne['bez'];
		
		$tabellenkopf[] = array($pos => $bez);
	}
	print 'kolonnen tabellenkopf: '.count($tabellenkopf).'<br>';
	print_r($tabellenkopf);



	$anzahlkolonnen = 8;
	
	$myFile = $_FILES["tabelle"]["tmp_name"];
	$fh = fopen($_FILES["tabelle"]["tmp_name"], 'r');
#	$theData = fread($fh,filesize($myFile));
	# explode("\n", fread($fp, filesize('ccdata.txt'))); 
	
	
	#$firstline = fgets ($fh, 4096 );
	#print '<br>firstline<br>';
	#print_r($firstline);
	#print '<br>End<br>';

	fclose($fh);
	#print_r($theData);
	#print '<br>';
	$medienarray = file($myFile);
	$lfmuster = 's/\r/\n/';
	$rmuster = '[\r]';
	$nmuster = '[\n]';
	if (preg_match($rmuster ,$medienarray[0]))
	{
	print 'r da<br>';
	}
	if (preg_match($nmuster,$medienarray[0]))
	{
	print 'n da<br>';
	
	}
	$anzahl=0;
	#preg_replace('/\r/','/\n/',$medienarray[0],-1,&$anzahl);
	
	
	#print 'r ersetzt: '.$anzahl.'<br>';
	
	#preg_replace('/(\r|\n|\r\n){2,}/', '<br/>*<br/>', $medienarray);
	#perl -pi -e 's/\r/\n/' $medienarray;
	#print '<br>';
	#print '<br>';
	#print '<br>A<br>';
	#print_r($medienarray); #
	
	print '<br>C<br>';
	$index=0;
	$datensatzindex=0;
	$tempkeyarray = array_keys($tabellenkopf);
	print 'anzahl tabellenkopfkolonnen: '.count($tempkeyarray).'<br>';
	foreach ($medienarray as $line_num => $line) 
	{
		print '<br> neue Zeile index: '.$index.'<br>';
		print '<br>';
		$linearray = explode("\t",$line);
    	#echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
    	print_r($linearray);
    	print '<br>';
    	#print '<br>';
    	
		print 'anzahl datakolonnen: '.count($linearray).'<br>';
		$dvekt = array();
		$bvekt = array();
		$col=0;
		
		$zeilenid=0;
		
		foreach ($linearray as $num => $element) 
		{
			$tempkopf = $tabellenkopf[$num];
			
			print '<br>';
			#print_r($tempkopf);
			
			
			$tempbezeichnung = $tempkopf["$num"];
			
			
			$kolonne = $tempkeyarray[$num];
			$data = $linearray[$kolonne];
			
			print 'index: '.$index.' num: '.$num.' col: '.$col.' tempbezeichnung: *'.$tempbezeichnung.'* data: *'.$data.'*<br>';
			$dvekt[] = $data;
			$bvekt[] = $tempbezeichnung;
			#$result_insert = mysql_query("INSERT  INTO testarchiv ( name,  teaser, beschreibung, art, gruppe, preis, stufe, nummer) VALUES ( '$name', '$teaser', '$beschreibung', '$art', '$gruppe', '$preis', '$stufe', '$nummer') ");
			if ($num==0)
			{
				print 'col start<br> index: '.$index.' datensatznummer: '.$index.'<br>'; 
				$result_insert = mysql_query("INSERT  INTO testarchiv ($tempbezeichnung,datensatznummer) VALUES('$data','$index')");
				if (mysql_error())
				{
					print 'INSERT error: *'.mysql_error().'*<br>';
				}
			}
			else
			{
				print 'col: '.$col.' index: '.$index.' tempbezeichnung: '.$tempbezeichnung.' data: '.$data.'<br>'; 
				$result_change = mysql_query("UPDATE testarchiv SET $tempbezeichnung = '$data'  WHERE datensatznummer = '$index'");
				if (mysql_error())
				{
					print 'UPDATE error: *'.mysql_error().'*<br>';
				}
			}
			$resultat=mysql_affected_rows($db);
			$col++;
		}
		
		print '<br>';
		#print 'index: '.$index.' bez 0: '.$bvekt[0].' 1: '.$bvekt[1].' 2: '.$bvekt[2].'';
		#print  ''.$bvekt[3].' '.$bvekt[4].' '.$bvekt[5].' '.$bvekt[6].' '.$bvekt[7].'*'.$bvekt[8].' '.$bvekt[9].' '.$bvekt[10].' '.$bvekt[11].' '.$bvekt[12].'<br>';
		#print 'index: '.$index.' data 0: '.$dvekt[0].' 1: '.$dvekt[1].' 2: '.$dvekt[2].'';
		#print  ''.$dvekt[3].' '.$dvekt[4].' '.$dvekt[5].' '.$dvekt[6].' '.$dvekt[7].' '.$dvekt[8].' '.$dvekt[9].' '.$dvekt[10].' '.$dvekt[11].' '.$dvekt[12].'<br>';
			
			#$result_insert = mysql_query("INSERT  INTO archiv ( $bvekt[0],$bvekt[1],$bvekt[2],$bvekt[3],$bvekt[4],$bvekt[5],$bvekt[6],$bvekt[7],$bvekt[8],$bvekt[9],$bvekt[10],$bvekt[11],$bvekt[12]) VALUES('$dvekt[0]','$dvekt[1]','$dvekt[2]','$dvekt[3]','$dvekt[4]','$dvekt[5]','$dvekt[6]','$dvekt[7]','$dvekt[8]','$dvekt[9]','$dvekt[10]','$dvekt[11]','$dvekt[12]')");
			
			#$result_insert = mysql_query("INSERT  INTO archiv ($bvekt[0],$bvekt[1],$bvekt[2],$bvekt[3]) VALUES('$dvekt[0]','$dvekt[1]','$dvekt[2]','$dvekt[3]')");

			#print 'INSERT error: *'.mysql_error().'*<br>';
			$resultat=mysql_affected_rows($db);
			print 'index: '.$index.' resultat affected_rows: *'.$resultat.'*<br>';
#$result_insert = mysql_query("INSERT  INTO archiv ( name,  teaser, beschreibung, art, gruppe, preis, stufe, nummer) VALUES ( '$name', '$teaser', '$beschreibung', '$art', '$gruppe', '$preis', '$stufe', '$nummer') ");

		{
			$index++;
		}
		#print 'art: '.$art.' datum: '.$datum.'  pfr: '.$pfr.' teil: '.$teil.' komponist_vn: '.$komponist_vn.' komponist: '.$komponist.'  werk: '.$werk.'<br>';
	}
	print '<br>D<br>';
#	print '</div>';
}	# if file da
} # upload

print '<br><form action="choradmin.php" method = "post" >';
if ($errtask== "new") # Namen zurueckgeben
{
	print '<input type="hidden" name="task" value ="err">';
	print '<input type="hidden" name="sent" value ="err">';

	print '<input type="hidden" name="errtask" value ="new">';
	print '<input type="hidden" name="name" value ="'.$name.'">';
	print '<input type="hidden" name="art" value ="'.$art.'">';
	print '<input type="hidden" name="gruppe" value ="'.$gruppe.'">';
	print '<input type="hidden" name="stufe" value ="'.$stufe.'">';
	print '<input type="hidden" name="preis" value ="'.$preis.'">';
	print '<input type="hidden" name="nummer" value ="'.$nummer.'">';
	print '<input type="hidden" name="teaser" value ="'.$teaser.'">';
	print '<input type="hidden" name="beschreibung" value ="'.$beschreibung.'">';
	
	
	
}
else
{
	print '<input type="hidden" name="task" value ="done">';
	print '<input type="hidden" name="sent" value ="done">';

}
#Zurueck ohne PW

print '	<input type="hidden" name="test" value ="'.$test.'">';
print '<p class="nameneingabe"><input type="submit" value="back" name="textfile"></p></form>';




/*perlscript aufrufen*/
#$index=0;
#mysql_data_seek($result_medien,$index);
#$zeile= mysql_fetch_assoc($result_medien);
#print "<p>Ausgabe Zeile:</p>";
#print '<p>*name: *'.$zeile['name'].'* art: *'.$zeile['art'].'* PREIS: *'.$zeile['preis'].'*</p>';


?>

</div>	<!--# adminContent -->
</div>	<!--# # admin-->
 
</body>

</html>

