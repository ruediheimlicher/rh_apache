<?
/*

Admin confirm Datenbank chor
*/


/* verbinden mit db */
	$db=mysql_connect('localhost','ruedihei_db','rueti8630');

	mysql_set_charset('utf8',$db);
	mysql_select_db("ruedihei_kicho", $db); 

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Archiv Adminconfirm</title>
 
<link href="chor.css" rel="stylesheet" type="text/css" />
</head>

<body class="liste">
	

	
	


<div style = "margin-left: 20px;">
<h1 class="lernmedien">Chor Archiv</h1>
	
<h2 class="lernmedien ">Admin confirm</h2>
		
	

	

<?

#print_r($_POST);
$task =  $_POST['task'];
#print 'task: '.$task.'<br>';
$taskradio = $_POST['changeradio'];
#print 'taskradio: '.$taskradio.'<br>';
$changeoption = $_POST['changeoption'];
#print 'changeoption: '.$changeoption.'<br>';

$deleteangabe = $_POST['delete'];
#print 'deleteangabe: '.$deleteangabe.'<br>';

$test = $_POST['test'];
$test=1;
#print 'test: '.$test.'<br>';
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
	$datum = $_POST['neuesdatum'];
	$jahr = $_POST['neuesjahr'];
	if (strlen($jahr)==0) # kein jahr, extrahieren aus datum
		{
			$tempdatumarray = explode('.',$datum);
			$jahr = $tempdatumarray[2];
		}


	$pfr = $_POST['neuerpfr'];
	$mitwirkung = $_POST['neuemitwirkung'];
	$quelle = $_POST['neuequelle'];
	$nr = $_POST['neuenr'];
	$begleitung = $_POST['neuebegleitung'];
	$komponist = $_POST['neuerkomponist'];
	$komponist_vn = $_POST['neuerkomponist_vn'];
	$werk = $_POST['neueswerk'];
	$teil = $_POST['neuerteil'];
	$art = $_POST['neueart'];
	$anmerkung = $_POST['neueanmerkung'];
	
	
	
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
	
	if (strlen($komponist)==0)
	{
		$eingabefehler .= 'Das Feld für den Komponist ist leer.<br>'; 
	}
	 
	else if (!preg_match($textmuster,$komponist))
	{
		$eingabefehler .= 'Das Feld für den Komponist enthält ungültige Zeichen.<br>'; 
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
	
	
	/*
	
	if (strlen($eingabefehler)> $eingabefehler)
	{	
		print '<h3 class="lernmedien">Bei der Eingabe sind Fehler aufgetreten:</h3>';
		print '<p class="liste">'. $eingabefehler.'</p';
		$errtask="new";
	}
	else
	*/
	{
	
	# Muster
	#$result_insert = mysql_query("INSERT INTO testarchiv (id, name, beschreibung, art, gruppe, preis, stufe, nummer) VALUES (NULL, 'Abc', 'ysdfghjkl', 'CD', 'Holz', '1', 'US MS', '1', '1', NULL)");
		#print ' <p>Neuer Datensatz:  name: '.$medien['name'].' nummer: '.$medien['nummer'].' teaser: '.$medien['teaser'].'</p>';;

	
	#$result_insert = mysql_query("INSERT INTO testarchiv (id,datum, jahr, pfr, mitwirkung, art, begleitung, quelle,  nr, komponist_vn,  komponist, werk, teil, anmerkung) VALUES (NULL, '$datum', '$jahr','$pfr', '$mitwirkung', '$art','$begleitung', '$quelle', '$nr','$komponist_vn',  '$komponist', '$werk', '$teil',  '$anmerkung')");
	$result_insert = mysql_query("INSERT INTO archiv (id,datum, jahr, pfr, mitwirkung, art, begleitung, quelle,  nr, komponist_vn,  komponist, werk, teil, anmerkung) VALUES (NULL, '$datum', '$jahr','$pfr', '$mitwirkung', '$art','$begleitung', '$quelle', '$nr','$komponist_vn',  '$komponist', '$werk', '$teil',  '$anmerkung')");
	
	print 'INSERT error: *'.mysql_error().'*<br>';
	$resultat=mysql_affected_rows($db);
	
	print 'INSERT resultat affected_rows: *'.$resultat.'*<br>';
	
	
	print '<p>Rückgabe von INSERT: *'. $result_insert.'*</p>';
	
	}
	if ($resultat==1)
	{
	print '<div class = " adminconfirmabschnitt1">';
		print '<h2 class="lernmedien">neuer Datensatz eingefügt</h2>';
		
		$datum = $_POST['neuesdatum'];
		$jahr = $_POST['neuesjahr'];
		if (strlen($jahr)==0) # kein jahr, extrahieren aus datum
		{
			$tempdatumarray = explode('.',$datum);
			$jahr = $tempdatumarray[2];
		}

		
		$pfr = $_POST['neuerpfr'];
		
		$mitwirkung = $_POST['neuemitwirkung'];
		
		$komponist = $_POST['neuerkomponist'];
		$komponist_vn = $_POST['neuerkomponist_vn'];
		$quelle = $_POST['neuequelle'];
		$nr = $_POST['neuenr'];
		$werk = $_POST['neueswerk'];
		$teil = $_POST['neuerteil'];
		$art = $_POST['neueart'];
		$anmerkung = $_POST['neueanmerkung'];
		
		
		print '<p class = "liste"><b>neue Daten:</b></p>';
		print '<p class = "liste">datum:*'. $datum.'*</p>';
		print '<p class = "liste">jahr:*'. $jahr.'*</p>';
		print '<p class = "liste">pfr:*'. $pfr.'*</p>';
		print '<p class = "liste">mitwirkung:*'. $mitwirkung.'*</p>';
		print '<p class = "liste">komponist:*'. $komponist.'*</p>';
		print '<p class = "liste">vorname:*'. $komponist_vn.'*</p>';
		print '<p class = "liste">quelle:*'. $quelle.'*</p>';
		print '<p class = "liste">nr:*'. $nr.'*</p>';
		print '<p class = "liste">art:*'. $art.'*</p>';
		print '<p class = "liste">werk:*'. $werk.'*</p>';
		print '<p class = "liste">teil:*'. $teil.'*</p>';
		print '<p class = "liste">anmerkung:*'. $anmerkung.'*</p>';
	
	
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
	#print'Datensatz ändern<br>';

		$datum = $linearray[1];
		$jahr = $linearray[2];
		if (strlen($jahr)==0) # kein jahr, extrahieren aus datum
		{
			$tempdatumarray = explode('.',$datum);
			$jahr = $tempdatumarray[2];
		}

		$pfr = $linearray[3];
		$mitwirkung = $linearray[4];
		$art = $linearray[5];
		$begleitung = $linearray[6];
		$quelle = $linearray[7];
		$nr = $linearray[8];
		$komponist_vn = $linearray[9];
		$komponist = $linearray[10];
		$werk = $linearray[11];
		$teil = $linearray[12];
		$anmerkung = $linearray[13];
		
		$index = $_POST['index'];

		$datum = $_POST['changedatum'];
		$jahr = $_POST['changejahr'];
		$komponist = $_POST['changekomponist'];
		$komponist_vn = $_POST['changekomponist_vn'];
		$pfr = $_POST['changepfr'];
		$quelle = $_POST['changequelle'];
		$nr = $_POST['changenr'];
		$mitwirkung = $_POST['changemitwirkung'];;
		
		$werk = $_POST['changewerk'];
		$teil = $_POST['changeteil'];
		$art = $_POST['changeart'];
		$anmerkung = $_POST['changeanmerkung'];
	
		#$result_change = mysql_query("UPDATE testarchiv SET datum = '$datum', jahr = '$jahr', komponist = '$komponist', pfr = '$pfr', quelle = '$quelle', nr = '$nr',mitwirkung = '$mitwirkung', komponist_vn = '$komponist_vn', werk = '$werk', teil = '$teil', art = '$art', anmerkung = '$anmerkung'    WHERE id = '$index'");
		$result_change = mysql_query("UPDATE archiv SET datum = '$datum', jahr = '$jahr', komponist = '$komponist', pfr = '$pfr', quelle = '$quelle', nr = '$nr',mitwirkung = '$mitwirkung', komponist_vn = '$komponist_vn', werk = '$werk', teil = '$teil', art = '$art', anmerkung = '$anmerkung'    WHERE id = '$index'");
		$resultat=mysql_affected_rows($db);
		#
		if ($resultat)
		{
		print 'UPDATE error: *'.mysql_error().'* rows: '.$resultat.'<br>';
		}
	#print 'resultat affected_rows: *'.$resultat.'*<br>';
	#print '<p class = "liste">Rückgabe von UPDATE: *'. $result_change.'*</p>';
	
	if ($result_change==1)
	{
		print '<div class = "adminconfirmabschnitt1">';
		print '<h2 class="lernmedien">Datensatz wurde geändert</h2>';
		print '<p class = "liste"><b>neue Daten:</b></p>';
		#print '<p class = "liste">index:*' $index.'*</p>';
		print '<p class = "liste">datum:*'.$datum.'*</p>';
		print '<p class = "liste">jahr:*'.$jahr.'*</p>';
		print '<p class = "liste">komponist:*'.$komponist.'*</p>';
		print '<p class = "liste">komponist_vn:*'.$komponist_vn.'*</p>';
		print '<p class = "liste">pfr:*'.$pfr.'*</p>';
		print '<p class = "liste">quelle:*'.$quelle.'*</p>';
		print '<p class = "liste">nr:*'.$nr.'*</p>';
		print '<p class = "liste">mitwirkung:*'.$mitwirkung.'*</p>';
		print '<p class = "liste">werk:*'.$werk.'*</p>';
		print '<p class = "liste">teil:*'.$teil.'*</p>';
		print '<p class = "liste">art:*'.$art.'*</p>';
		print '<p class = "liste">anmerkung:*'.$anmerkung.'*</p>';
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
	$deletekomponist = $_POST['deletekomponist'];
	#print 'deletekomponist: '.$deletekomponist.'<br>';

	#$result_delete = mysql_query("DELETE FROM testarchiv  WHERE id = '$index'");
	$result_delete = mysql_query("DELETE FROM archiv  WHERE id = '$index'");
	$resultat=mysql_affected_rows($db);
	#print 'DELETE error: *'.mysql_error().'*<br>';
	#print 'resultat affected_rows: *'.$resultat.'*<br>';
	#print '<p>Rückgabe von DELETE: *'. $result_delete.'*</p>';
	if ($resultat==1)
	{
		print '<h3 class="lernmedien">Datensatz '.$deletekomponist.' ist gelöscht.</h3><br>';
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
#$result_medien = mysql_query("SELECT * FROM testarchiv", $db);

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




if ($task == 'upload')
{
	#print_r($_FILES);
	if ($_FILES["tabelle"]["error"] > 0)
	{
		print '<p class="nameneingabe" >File ist nicht da. Fehler :<br>'. $_FILES["tabelle"]["error"] .'</p>';
	}
	else
	{
	#	print '<div class = " adminconfirmabschnitt1">';
		print '<h3 class="lernmedien">Das File ist da</h3>';
	#	print '<p class="nameneingabe" >Upload: '. $_FILES["tabelle"]["name"] .'</p>';
	#	print '<p class="nameneingabe" >Type: '. $_FILES["tabelle"]["type"] .'</p>';
	#	print '<p class="nameneingabe" >Size: '. $_FILES["tabelle"]["size"] .'</p>';
	#	print '<p class="nameneingabe" >Stored in: '. $_FILES["tabelle"]["tmp_name"] .'</p>';
		$url = $_FILES["tabelle"]["tmp_name"];
		
		$pfad = $_FILES["tabelle"]["tmp_name"];
	
	#	print '<p class="nameneingabe" >URL: '. $url .'</p>';
	
	# Groesse überprüfen
	$dateigroesse = $_FILES["tabelle"]["size"];
	# Dateiname überprüfen
	$dateiname =  $_FILES["tabelle"]["name"];
	
	$dateinamearray = explode('_',$dateiname); # Name muss 2 underscore haben
	print '<p class="nameneingabe">Dateiname: '.$dateiname.'</p>';
	#print_r($dateinamearray);
	#print '<br>';

if (($dateigroesse) < 10)
{
	
	print 'Dateipfad: '.$dateiname.' Die Datei ist zu klein: '.$dateigroesse.' Bytes<br>';
	exit;

}

if (($dateigroesse) > 1000000)
{
	$dateigroesse /= 1000;
	print 'Dateipfad: '.$dateiname.' Die Datei ist zu gross: '.$dateigroesse.'KB<br>';
	exit;

}

if (!(count($dateinamearray)==3))
{
	print 'Dateipfad: '.$dateiname.' Der Dateiname hat nicht die richtige Form.<br>';
	exit;
}

if (!(strlen($dateinamearray[0]) == 4))
{
	print 'dateiname: '.$dateinamearray[0].' Die Jahrzahl hat nicht die richtige Form.<br>';
	exit;
}

if (!(strlen($dateinamearray[1]) == 2))
{
	print 'dateiname: '.$dateinamearray[1].' Die Monatszahl hat nicht die richtige Form.<br>';
	exit;
}

$bezeichnungarray = explode('.',$dateinamearray[2]);

if (!(count($bezeichnungarray)==2))
{
	print 'dateiname: '.$dateiname.' Der Dateiname hat nicht die richtige Form.<br>';
	exit;
}
#print '<br>';
#print_r($bezeichnungarray);
print '<br>';
if (strcmp($bezeichnungarray[0],"Plakat")) // Name ist nicht Plakat
{
	if (strcmp($bezeichnungarray[0],"Programm"))
	{
		if (strcmp($bezeichnungarray[0],"ProgrammTitel"))
		{
			print 'dateiname: '.$bezeichnungarray[0].' Der Dateiname hat nicht die richtige Form.<br>';
			exit;
			
		}
	}
}
//print '<p class="nameneingabe">dateiname: '.$dateiname.'</p>';
print '<h3 class="lernmedien">Der Dateiname hat die richtige Form.</h3><br>';

$zielstring = "../Data/kirchenchor_data/konzert/".$dateiname;

#print '<p class="nameneingabe">Pfad: '.$pfad.' zielstring: '.$zielstring.' </p><br>';

if ( move_uploaded_file ( $pfad , $zielstring ))
{
	print '<h3  class="lernmedien">Die Datei ist an der Adresse: '.$zielstring.' </h3><br>';
}
else
{
	print '<h3  class="lernmedien">Die Datei ist nicht an der Adresse: '.$zielstring.' </h3><br>';
}


# Bezeichnung richtig?



exit;
		# array mit Namen	
	#	$result_namen = mysql_query("SELECT name FROM testarchiv ", $db);
	#	$oldname=array();

		
	#	while ($medien = mysql_fetch_array($result_namen) )
	#	{
	#		$oldname[] = $medien['name'];
	#	}
	#	print_r($oldname);
	
	
	/*
		$anzahlkolonnen = 8;
	
	
		
		#$archivarray = fgetcsv($myFile);
		#print '<br>';
		#print '<br>';
		#print_r($archivarray);
		#print '<br>';
		foreach ($archivarray as $line_num => $line) 
		{
			#echo "Line #<b>{$line_num}</b> : " . ($line) . "<br />\n";
			#$line = utf8_decode($line);
			#echo "Line neu #<b>{$line_num}</b> : " . ($line) . "<br />\n";
			#$linearray = explode("</Row>",$line);
			#$anz = substr_count($line,';',0);
			#print'substr_count ;: *'.$anz.'*<br>';
			
			# Apostroph entfernen
			if(substr_count($line,'\''))
			{
				#echo "Line #<b>{$line_num}</b> : " . ($line) . "<br />\n";

				#print 'kolonne: '.$num.' Hochkomma<br>';
				$line = str_replace('\'', ' ', $line);
				#echo "Line neu #<b>{$line_num}</b> : " . ($line) . "<br />\n";
			}

			
			if (substr_count($line,';',0) > 10 )
			{
				if (substr_count($line,'; ',0)) # semicolon in  Daten
				{
					$templinearray = str_replace('; ', '*', $line);
					$templinearray = explode(';',$templine);
					$linearray = array();
					foreach ($templinearray as $num => $tempelement) 
					{
						#print 'kolonne: '.$num.' element: '.$element.'<br>';
						if (substr_count($tempelement,'*',0)) # semicolon in  Daten wieder einsetzen
						{
							$tempelement = str_replace('*', "; ", $tempelement);
							$tempelement = str_replace('"', "", $tempelement);
							#print 'kolonne: '.$num.' tempelement: '.$tempelement.'<br>';
							
						}
						$linearray [] = $tempelement;
					}
				}
				$linearray = explode(';',$line);
			}
			elseif (substr_count($line,',',0) > 10)
			{
				if (substr_count($line,', ',0)) # komma in  Daten
				{
					#print 'komma da anz: '.substr_count($line,", ",0).'<br>';
					$templine = str_replace(', ', '*', $line);
					#print_r($templine);
					#print'<br>';
					$templinearray = explode(',',$templine);
					$linearray = array();
					foreach ($templinearray as $num => $tempelement) 
					{
						#print 'kolonne: '.$num.' element: '.$element.'<br>';
						if (substr_count($tempelement,'*',0)) # semicolon in  Daten wieder einsetzen
						{
							$tempelement = str_replace('*', ", ", $tempelement);
							$tempelement = str_replace('"', "", $tempelement);
							#print 'kolonne: '.$num.' tempelement: '.$tempelement.'<br>';
						}
						$linearray [] = $tempelement;
					}
				}
				else
				{
					$linearray = explode(',',$line);
				}
				
				if (substr_count($line,'*',0)) # semicolon in  Daten wieder einsetzen
				{
					#$templinearray = str_replace('*', ", ", $line);
				
				}

			}
			
			else
			{
				print '<h2>Kein gueltiger Delimiter</h2><br>';
			}
			
			
			

			
			#$linearray = explode(';',$line);
			#print 'Zeilen: '.count($linearray).'<br>';
		
		
			foreach ($linearray as $num => $element) 
			{
				$data = $element;
				#print 'kolonne: '.$num.' element: '.$element.'<br>';
				
				if (substr_count($element,'*',0)) # semicolon in  Daten wieder einsetzen
				{
					$element = str_replace('*', ", ", $element);
					#print 'kolonne: '.$num.' element: '.$element.'<br>';
				}

			}
		
		
			{
	
				$datum = $linearray[1];
				#print 'kolonne: '.$num.' datum: '.$datum.'<br>';
				if (substr_count($datum,'/',0)) # falscher Delimiter in Datum
				{
				
					$tempdatumarray = explode('/',$datum);
					$datum = implode('.',$tempdatumarray);
					#print 'kolonne: '.$num.' datum korr: '.$datum.'<br>';
				
				}
			
			
				#if ($num%$anzahlkolonnen==0) # Zeile fertig
				{
				#print '<br>';
				#print_r($tempzeilenarray);

				#
				#print 'kolonne: '.$num.' data: '.$data.'<br>';
			
				if (strlen($datum)) # datensatz vorhanden
				{
					print 'kolonne: '.$num.' datum SQL: '.$datum.'<br>';
					$jahr = $linearray[2];
					if (strlen($jahr)==0) # kein jahr, extrahieren aus datum
					{
						$tempdatumarray = explode('.',$datum);
						$jahr = $tempdatumarray[2];
					}
			
					$pfr = $linearray[3];
					$mitwirkung = $linearray[4];
					$art = $linearray[5];
					$begleitung = $linearray[6];
					$quelle = $linearray[7];
					$nr = $linearray[8];
					$komponist_vn = $linearray[9];
					$komponist = $linearray[10];
					$werk = $linearray[11];
					$teil = $linearray[12];
					$anmerkung = $linearray[13];

			
				
					#$result_insert = mysql_query("INSERT  INTO testarchiv ( datum,  jahr, pfr, mitwirkung, art, begleitung, quelle, nr, komponist_vn, komponist, werk, teil, anmerkung) 
					#VALUES ( '$datum', '$jahr', '$pfr', '$mitwirkung', '$art', '$begleitung', '$quelle', '$nr', '$komponist_vn', '$komponist', '$werk', '$teil', '$anmerkung') ");

					$result_insert = mysql_query("INSERT  INTO archiv ( datum,  jahr, pfr, mitwirkung, art, begleitung, quelle, nr, komponist_vn, komponist, werk, teil, anmerkung) 
					VALUES ( '$datum', '$jahr', '$pfr', '$mitwirkung', '$art', '$begleitung', '$quelle', '$nr', '$komponist_vn', '$komponist', '$werk', '$teil', '$anmerkung') ");


					if (mysql_error())
					{
						print 'Fehler beim Upload der Daten: *'.mysql_error().'*<br>';
						print 'UPDATE error: *'.mysql_error().'*<br>';
					}
					else
					{
						#print 'kolonne: '.$num.' INSERT ok<br>';
					}
					$resultat=mysql_affected_rows($db);
					#print 'INSERT resultat affected_rows: *'.$resultat.'*<br>';
				#	print '<p class = "liste">Rückgabe von INSERT: *'. $result_insert.'*</p>';
					$korr=0;
					if(strpos(mysql_error(),"Duplicate")!==false)
					{
						print ' Duplikat<br>'; 
						#$result_change = mysql_query("UPDATE testarchiv 
						#SET  datum=$datum, jahr=$jahr, pfr=$pfr, mitwirkung=$mitwirkung, art=$art, begleitung=$begleitung, quelle=$quelle, nr=$nr, komponist_vn=$komponist_vn, komponist=$komponist, werk=$werk, teil=$teil, anmerkung=$anmerkung  
						#WHERE datum = '$datum'");
						#$resultat=mysql_affected_rows($db);
					#	print '<p class = "liste">Datensatz <b>'. $name.'</b> wurde angepasst.</p>';
						$korr++;
					} 
					else
					{
						#print 'Fehler beim Upload der Daten: *'.mysql_error().'*<br>';
					}
				
					}
					#print '<p class = "liste">'. $korr.' Datensätze <b></b> wurden angepasst.</p>';
			#	print '<br>';
				$tempzeilenarray=array(); # leerer ARRAY
				$kolonnenindex=0;
			
				}
			
				$tempzeilenarray[$kolonnenindex] = $tempelement;
				$kolonnenindex ++;
		
			} # 
			#mysql_data_seek($linearray,0);
		
	
		}
		*/
		###
		#echo $theData;
	#	print '</div>';
	}	# if file da
} # upload

print '<br><form action="chor_admin.php" method = "post" >';
if ($errtask== "new") # Namen zurueckgeben
{
	print '<input type="hidden" name="task" value ="err">';
	print '<input type="hidden" name="sent" value ="err">';

	print '<input type="hidden" name="errtask" value ="new">';
	print '<input type="hidden" name="datum" value ="'.$datum.'">';
	print '<input type="hidden" name="jahr" value ="'.$jahr.'">';
	print '<input type="hidden" name="pfr" value ="'.$pfr.'">';
	print '<input type="hidden" name="mitwirkung" value ="'.$mitwirkung.'">';
	print '<input type="hidden" name="art" value ="'.$art.'">';
	print '<input type="hidden" name="begleitung" value ="'.$begleitung.'">';
	print '<input type="hidden" name="quelle" value ="'.$quelle.'">';
	print '<input type="hidden" name="nr" value ="'.$nr.'">';
	print '<input type="hidden" name="komponist" value ="'.$komponist.'">';
	print '<input type="hidden" name="komponist_vn" value ="'.$komponist_vn.'">';
	print '<input type="hidden" name="werk" value ="'.$werk.'">';
	
	print '<input type="hidden" name="anmerkung" value ="'.$anmerkung.'">';
	

	
	
}
else
{
	print '<input type="hidden" name="task" value ="done">';
	print '<input type="hidden" name="sent" value ="done">';

}
#Zurueck ohne PW

print '	<input type="hidden" name="test" value ="'.$test.'">';
print '<p class="nameneingabe"><input type="submit" value="zurück" name="textfile"></p></form>';




/*perlscript aufrufen*/
#$index=0;
#mysql_data_seek($result_medien,$index);
#$zeile= mysql_fetch_assoc($result_medien);
#print "<p>Ausgabe Zeile:</p>";
#print '<p>*name: *'.$zeile['name'].'* art: *'.$zeile['art'].'* PREIS: *'.$zeile['preis'].'*</p>';


?>
</div>

</body>

</html>

