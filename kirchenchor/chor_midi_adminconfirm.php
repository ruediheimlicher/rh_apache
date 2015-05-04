<?php
/*

Admin confirm Datenbank chor
*/


/* verbinden mit db */
	#$db = include "../bank.php";

	$db = include "chor_db.php";
	#mysql_set_charset('utf8',$db);
	#mysql_select_db("midi", $db); 

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
		
	

	

<?php
$task = "";
$taskradio="";
$changeoption="";
$deleteangabe ="";
print_r($_POST);
print '<br>';
if (isset($_POST['task']))
{
$task =  $_POST['task'];
}
#print 'task: '.$task.'<br>';
if (isset($_POST['changeradio']))
{
$taskradio = $_POST['changeradio'];
}
#print 'taskradio: '.$taskradio.'<br>';
if (isset($_POST['changeoption']))
{
$changeoption = $_POST['changeoption'];
print 'changeoption: '.$changeoption.'<br>';
}
#print 'changeoption: '.$changeoption.'<br>';
if (isset($_POST['delete']))
{
$deleteangabe = $_POST['delete'];
}
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
	$datum="";
	#$datum = $_POST['neuesdatum'];
	$jahr = 0;#$_POST['neuesjahr'];
	if (strlen($jahr)==0) # kein jahr, extrahieren aus datum
		{
			$tempdatumarray = explode('.',$datum);
			$jahr = $tempdatumarray[2];
		}


	$aktiv = $_POST['neuesaktiv'];
	$event = $_POST['neuerevent'];
	$werk = $_POST['neueswerk'];
	$satznummer = $_POST['neuesatznummer'];
	$satz = $_POST['neuersatz'];
	$bezeichnung = $_POST['neuebezeichnung'];
	$register = $_POST['neuesregister'];
	$stimme1 = $_POST['neuestimme1'];
	$stimme2 = $_POST['neuestimme2'];
	$stimme3 = $_POST['neuestimme3'];
	$anmerkung = $_POST['neueanmerkung'];
	
	
	
	#$eingabe=array($_POST['eingabe']);
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
	
	# event
	if (strlen($event)==0)
	{
		$eingabefehler .= 'Das Feld für den Event ist leer.<br>'; 
	}
	 
	else if (!preg_match($textmuster,$event))
	{
		$eingabefehler .= 'Das Feld für den Event enthält ungültige Zeichen.<br>'; 
	}
	
	# Werk
	if (strlen($werk)==0)
	{
		$eingabefehler .= 'Das Feld für das Werk ist leer.<br>'; 
	}

	else if (!preg_match($textmuster,$werk))
	{
		$eingabefehler .= 'Das Feld für das Werk enthält einen falschen Begriff.<br>'; 
	}
	
		# satz
	if (strlen($satz)==0)
	{
		$eingabefehler .= 'Das Feld für den Satz ist leer.<br>'; 
	}

	else if (!preg_match($textmuster,$satz))
	{
		$eingabefehler .= 'Das Feld für den Satz enthält einen falschen Begriff.<br>'; 
	}
	

	
	
	
	# satznummer
	if ($satznummer==0)
	{
		$eingabefehler .= 'Der Wert für die Satznummer ist 0.<br>';
	}
	else if (!preg_match($preismuster,$satznummer))
	{
		$eingabefehler .= 'Das Feld für die Satznummer enthält ein falsches Zeichen.<br>'; 
	}
 
	
	# bezeichnung
 	if (!preg_match($beschreibungmuster,$bezeichnung))	
	{
		$eingabefehler .= 'Das Feld für die Beschreibung enthält ein falsches Zeichen.<br>'; 
	}

	# anmerkung
	if (!preg_match($beschreibungmuster,$anmerkung))	
	{
		$eingabefehler .= 'Das Feld für die Anmerkung enthält ein falsches Zeichen.<br>'; 
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

	
	$result_insert = mysql_query("INSERT INTO audio (id,aktiv, event, werk, satznummer, satz, bezeichnung, register,  stimme1, stimme2,  stimme3, anmerkung) 
						VALUES (NULL, '$aktiv', '$event','$werk', '$satznummer', '$satz','$bezeichnung', '$register', '$stimme1','$stimme2',  '$stimme3',  '$anmerkung')");
	
	print 'INSERT error: *'.mysql_error().'*<br>';
	$resultat=mysql_affected_rows($db);
	
	print 'INSERT resultat affected_rows: *'.$resultat.'*<br>';
	
	
	print '<p>Rückgabe von INSERT: *'. $result_insert.'*</p>';
	
	}
	if ($resultat==1)
	{
	print '<div class = " adminconfirmabschnitt1">';
		print '<h2 class="lernmedien">neuer Datensatz eingefügt</h2>';
		

		
		$event = $_POST['neuerevent'];
		
		$aktiv = $_POST['neuesaktiv'];
		
		$werk = $_POST['neueswerk'];
		$satznummer = $_POST['neuesatznummer'];
		$satz = $_POST['neuersatz'];
		$bezeichnung = $_POST['neuebezeichnung'];
		$register = $_POST['neuesregister'];
		$stimme1 = $_POST['neuestimme1'];
		$stimme2 = $_POST['neuestimme2'];
		$stimme3 = $_POST['neuestimme3'];
		$anmerkung = $_POST['neueanmerkung'];
		
		
		print '<p class = "liste"><b>neue Daten:</b></p>';
		print '<p class = "liste">aktiv:*'.$aktiv.'*</p>';
		print '<p class = "liste">event:*'.$event.'*</p>';
		print '<p class = "liste">werk:*'.$werk.'*</p>';
		print '<p class = "liste">satznummer:*'.$satznummer.'*</p>';
		print '<p class = "liste">satz:*'.$satz.'*</p>';
		print '<p class = "liste">bezeichnung:*'.$bezeichnung.'*</p>';
		print '<p class = "liste">register:*'.$register.'*</p>';
		print '<p class = "liste">stimme1:*'.$stimme1.'*</p>';
		print '<p class = "liste">stimme2:*'.$stimme2.'*</p>';
		print '<p class = "liste">stimme3:*'.$stimme3.'*</p>';
	
	
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
					$aktiv=0;
					$event="";
					$werk="";
					$satznummer=0;
					$satz="";
					$bezeichnung="";
					$register="";
					$stimme1="";
					$stimme2="";
					$stimme3="";
					$anmerkung = "";

	$index = $_POST['index'];
	$aktiv = $_POST['changeaktiv'];
	$event = $_POST['changeevent'];
	$werk = $_POST['changewerk'];
	$satznummer = $_POST['changesatznummer'];
	$satz = $_POST['changesatz'];
	$bezeichnung = $_POST['changebezeichnung'];
	$register = $_POST['changeregister'];
	$stimme1 = $_POST['changestimme1'];
	$stimme2 = $_POST['changestimme2'];
	$stimme3 = $_POST['changestimme3'];
	
	
	$result_change = mysql_query("UPDATE audio SET aktiv = '$aktiv', event = '$event', werk = '$werk', satznummer = '$satznummer',satz = '$satz',bezeichnung = '$bezeichnung', register = '$register',stimme1 = '$stimme1', stimme2 = '$stimme2', stimme3 = '$stimme3'  WHERE id = '$index'");
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
		#print '<p class = "liste">index:*' $index.'*</p>';
		print '<p class = "liste">aktiv:*'.$aktiv.'*</p>';
		print '<p class = "liste">event:*'.$event.'*</p>';
		print '<p class = "liste">werk:*'.$werk.'*</p>';
		print '<p class = "liste">satznummer:*'.$satznummer.'*</p>';
		print '<p class = "liste">satz:*'.$satz.'*</p>';
		print '<p class = "liste">bezeichnung:*'.$bezeichnung.'*</p>';
		print '<p class = "liste">register:*'.$register.'*</p>';
		print '<p class = "liste">stimme1:*'.$stimme1.'*</p>';
		print '<p class = "liste">stimme2:*'.$stimme2.'*</p>';
		print '<p class = "liste">stimme3:*'.$stimme3.'*</p>';
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

}	# if file da
} # upload

print '<br><form action="chor_midi_admin.php" method = "post" >';
if ($errtask== "new") # Namen zurueckgeben
{
	print '<input type="hidden" name="task" value ="err">';
	print '<input type="hidden" name="sent" value ="err">';

	print '<input type="hidden" name="errtask" value ="new">';
	print '<input type="hidden" name="aktiv" value ="'.$aktiv.'">';
	print '<input type="hidden" name="event" value ="'.$event.'">';
	print '<input type="hidden" name="werk" value ="'.$werk.'">';
	print '<input type="hidden" name="satznummer" value ="'.$satznummer.'">';
	print '<input type="hidden" name="satz" value ="'.$satz.'">';
	print '<input type="hidden" name="bezeichnung" value ="'.$bezeichnung.'">';
	print '<input type="hidden" name="register" value ="'.$register.'">';
	print '<input type="hidden" name="stimme1" value ="'.$stimme1.'">';
	print '<input type="hidden" name="stimme2" value ="'.$stimme2.'">';
	print '<input type="hidden" name="stimme3" value ="'.$stimme3.'">';
	
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

