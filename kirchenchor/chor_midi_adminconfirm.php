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

$multcancel  = 0; # kein cancel by default
print_r($_POST);
print '<br>';

if (isset($_POST['task']))
{
$task =  $_POST['task'];
}
print 'task: '.$task.'<br>';
if (isset($_POST['changeradio']))
{
$taskradio = $_POST['changeradio'];
}
print 'taskradio: '.$taskradio.'<br>';
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
print 'deleteangabe: '.$deleteangabe.'<br>';

$multdelete = 0;
if (isset($_POST['multdelete']))
{
	$multdelete = 1;
	$task = 'multdelete';
}
print 'deleteangabe: '.$deleteangabe.'<br>';

if (isset($_POST['cancel']))
{
	$multcancel  = 1;
}


# new
$changeid=array();
$changesatz = array();
$changeregister = array();
$changearray = array();
if (isset($_POST['changeid']))
{
	$changearray['changeid'] = $_POST['changeid'];
	$changeid = $_POST['changeid'];
	print 'changeid<br>';
	print_r($_POST['changeid']);
	print'<br>';
}
if (isset($_POST['changesatz']))
{
	$changearray['changesatz'] = $_POST['changesatz'];
	$changesatz = $_POST['changesatz'];
	print 'changesatz<br>';
	print_r($_POST['changesatz']);
	print'<br>';
}

if (isset($_POST['changeregister']))
{
	$changearray['changeregister'] = $_POST['changeregister'];
	$changeregister = $_POST['changeregister'];
	print 'changeregister<br>';
	print_r($_POST['changeregister']);
	print'<br>';
}

	print 'changearray<br>';
	print_r($changearray);
	print'<br>';



# end new
if (isset($_POST['test']))
{
	$test = $_POST['test'];
	$test=1;
	#print 'test: '.$test.'<br>';
}
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

# link: http://www.refduernten.ch/www.zh.ref.ch/gemeinden/duernten/content/e14561/e12463/e15420/e15577/e2199/3_Domine_B.mp3?preview=preview
$linkmuster = '/^([0-9A-Za-z:=\_\-\.\/\?])*$/';
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
	$stimme1 = chop($_POST['neuestimme1']);
	$stimme2 = chop($_POST['neuestimme2']);
	$stimme3 = chop($_POST['neuestimme3']);
	$alle = chop($_POST['neuealle']);
	$anmerkung = chop($_POST['neueanmerkung']);
	
	# neu
	if (isset($_POST['neuesopranstimme1'])) {$sopranstimme1 = chop($_POST['neuesopranstimme1']);} else $sopranstimme1="";
	if (isset($_POST['neuesopranstimme2'])) {$sopranstimme2 = chop($_POST['neuesopranstimme2']);} else $sopranstimme2="";
	if (isset($_POST['neuesopranstimme3'])) {$sopranstimme3 = chop($_POST['neuesopranstimme3']);} else $sopranstimme3="";

	if (isset($_POST['neuealtstimme1'])) $altstimme1 = chop($_POST['neuealtstimme1']); else $altstimme1="";
	if (isset($_POST['neuealtstimme2'])) $altstimme2 = chop($_POST['neuealtstimme2']); else $altstimme2="";
	if (isset($_POST['neuealtstimme3'])) $altstimme3 = chop($_POST['neuealtstimme3']); else $altstimme3="";
	
	if (isset($_POST['neuetenorstimme1'])) $tenorstimme1 = chop($_POST['neuetenorstimme1']); else $tenorstimme1="";
	if (isset($_POST['neuetenorstimme2'])) $tenorstimme2 = chop($_POST['neuetenorstimme2']); else $tenorstimme2="";
	if (isset($_POST['neuetenorstimme3'])) $tenorstimme3 = chop($_POST['neuetenorstimme3']); else $tenorstimme3="";

	if (isset($_POST['neuebassstimme1'])) $bassstimme1 = chop($_POST['neuebassstimme1']); else $bassstimme1="";
	if (isset($_POST['neuebassstimme2'])) $bassstimme2 = chop($_POST['neuebassstimme2']); else $bassstimme2="";
	if (isset($_POST['neuebassstimme3'])) $bassstimme3 = chop($_POST['neuebassstimme3']); else $bassstimme3="";
	
	if (isset($_POST['neuealle'])) $alle = chop($_POST['neuealle']); else $alle="";
	
	print 'alle A: '.$alle.'<br>';
	# neu

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
	
	$linkcounter = 0; # Kontrolle: Mindestens ein Link muss vorhanden sein
	
	
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
		$eingabefehler .= 'Das Feld für die Bezeichnung enthält ein falsches Zeichen.<br>'; 
	}

	# anmerkung
	if (!preg_match($beschreibungmuster,$anmerkung))	
	{
		$eingabefehler .= 'Das Feld für die Anmerkung enthält ein falsches Zeichen.<br>'; 
	}
	
	# link
	if (!preg_match($linkmuster,$stimme1))
	{
		$eingabefehler .= 'Das Feld für Stimme 1 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$stimme2))
	{
		$eingabefehler .= 'Das Feld für Stimme 2 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$stimme3))
	{
		$eingabefehler .= 'Das Feld für Stimme 3 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$alle))
	{
		$eingabefehler .= 'Das Feld für Alle Stimmen enthält ein falsches Zeichen.<br>'; 
	}
	
	if (!preg_match($linkmuster,$sopranstimme1))
	{
		$eingabefehler .= 'Das Feld für Sopranstimme 1 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$sopranstimme2))
	{
		$eingabefehler .= 'Das Feld für Sopranstimme 2 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$sopranstimme3))
	{
		$eingabefehler .= 'Das Feld für Sopranstimme 3 enthält ein falsches Zeichen.<br>'; 
	}

	if (!preg_match($linkmuster,$altstimme1))
	{
		$eingabefehler .= 'Das Feld für Altstimme 1 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$altstimme2))
	{
		$eingabefehler .= 'Das Feld für Altstimme 2 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$altstimme3))
	{
		$eingabefehler .= 'Das Feld für Altstimme 3 enthält ein falsches Zeichen.<br>'; 
	}

	if (!preg_match($linkmuster,$tenorstimme1))
	{
		$eingabefehler .= 'Das Feld für Tenorstimme 1 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$tenorstimme2))
	{
		$eingabefehler .= 'Das Feld für Tenorstimme 2 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$tenorstimme3))
	{
		$eingabefehler .= 'Das Feld für Tenorstimme 3 enthält ein falsches Zeichen.<br>'; 
	}

	if (!preg_match($linkmuster,$bassstimme1))
	{
		$eingabefehler .= 'Das Feld für Bassstimme 1 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$bassstimme2))
	{
		$eingabefehler .= 'Das Feld für Bassstimme 2 enthält ein falsches Zeichen.<br>'; 
	}
	if (!preg_match($linkmuster,$bassstimme3))
	{
		$eingabefehler .= 'Das Feld für Bassstimme 3 enthält ein falsches Zeichen.<br>'; 
	}

	if (!preg_match($linkmuster,$alle))
	{
		$eingabefehler .= 'Das Feld für Alle Stimmen enthält ein falsches Zeichen.<br>'; 
	}

	print 'alle B: '.$alle.'<br>';
	
	if ((strlen($stimme1.$stimme2.$stimme3.$alle) == 0) && 
		(strlen($sopranstimme1.$sopranstimme2.$sopranstimme3) == 0)&&
		(strlen($altstimme1.$altstimme2.$altstimme3) == 0)&&
		(strlen($tenorstimme1.$tenorstimme2.$tenorstimme3) == 0) &&
		(strlen($bassstimme1.$bassstimme2.$bassstimme3) == 0)&&
		(strlen($alle) == 0))
	{
		$eingabefehler .= 'Mindestens ein Link muss vorhanden sein.<br>'; 
	}
	
	
	
	
	
	
	
		
	if (strlen($eingabefehler))
	{	
		print '<h3 class="lernmedien">Bei der Eingabe sind Fehler aufgetreten:</h3>';
		print '<p class="liste">'. $eingabefehler.'</p';
		
		$errtask="new";
	}
	else
	{
		# Muster
		#$result_insert = mysql_query("INSERT INTO testarchiv (id, name, beschreibung, art, gruppe, preis, stufe, nummer) VALUES (NULL, 'Abc', 'ysdfghjkl', 'CD', 'Holz', '1', 'US MS', '1', '1', NULL)");
			#print ' <p>Neuer Datensatz:  name: '.$medien['name'].' nummer: '.$medien['nummer'].' teaser: '.$medien['teaser'].'</p>';;

		# Einzelne Stimme einsetzen: Einzelfelder enthalten Text
		if (strlen($stimme1) || strlen($stimme2) || strlen($stimme3)) 
		{
	
			$result_insert = mysql_query("INSERT INTO audio (id,aktiv, event, werk, satznummer, satz, bezeichnung, register,  stimme1, stimme2,  stimme3,  anmerkung) 
								VALUES (NULL, '$aktiv', '$event','$werk', '$satznummer', '$satz', '$bezeichnung', '$register', '$stimme1','$stimme2',  '$stimme3',  '$anmerkung')");
	
			print 'INSERT error: *'.mysql_error().'*<br>';
			$resultat=mysql_affected_rows($db);
	
			print 'INSERT resultat affected_rows: *'.$resultat.'*<br>';
		
			print '<p>Rückgabe von INSERT: *'. $result_insert.'*</p>';
	
		
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
				$alle = $_POST['neuealle'];
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
				print '<p class = "liste">alle:*'.$alle.'*</p>';
	
			}
			else
			{
				print '<h3 class="lernmedien" <strong>Beim Einsetzen einer einzelnen Stimme ist ein Fehler ist aufgetreten:</strong></h3><br>';
				print '*'.mysql_error().'*<br>';
			}
	
		} #  strlen einzelne Stimme
	
		# Sammelupload

		$sopranregister = 'sopran';
		$altregister = 'alt';
		$tenorregister = 'tenor';
		$bassregister = 'bass';
		$alleregister = 'alle';
		
		
		# Sopran
		if (strlen($sopranstimme1) || strlen($sopranstimme2) || strlen($sopranstimme3))
		{
			$register = 'sopran';
			$result_insert = mysql_query("INSERT INTO audio (id,aktiv, event, werk, satznummer, satz, bezeichnung, register,  stimme1, stimme2,  stimme3, anmerkung) 
								VALUES (NULL, '$aktiv', '$event','$werk', '$satznummer', '$satz','$bezeichnung', '$sopranregister', '$sopranstimme1','$sopranstimme2',  '$sopranstimme3',  '$anmerkung')");
	
			print 'INSERT soptan error: *'.mysql_error().'*<br>';
			$sopranresultat=mysql_affected_rows($db);	
			#print 'INSERT sopran resultat affected_rows: *'.$sopranresultat.'*<br>';		
			print '<p>Rückgabe von INSERT sopran: *'. $result_insert.'*</p>';

		} # end Sopran
		else
		{
			$sopranresultat=0;
		}

		# Alt
		if (strlen($altstimme1) || strlen($altstimme2) || strlen($altstimme3))
		{
			$register = 'alt';
			$result_insert = mysql_query("INSERT INTO audio (id,aktiv, event, werk, satznummer, satz, bezeichnung, register,  stimme1, stimme2,  stimme3, anmerkung) 
								VALUES (NULL, '$aktiv', '$event','$werk', '$satznummer', '$satz','$bezeichnung', '$altregister','$altstimme1','$altstimme2','$altstimme3','$anmerkung')");
	
			print 'INSERT alt error: *'.mysql_error().'*<br>';
			$altresultat=mysql_affected_rows($db);	
			#print 'INSERT alt resultat affected_rows: *'.$altresultat.'*<br>';		
			print '<p>Rückgabe von INSERT alt: *'. $result_insert.'*</p>';

		} # end Alt
		else
		{
			$altresultat=0;
		}

		# Tenor
		if (strlen($tenorstimme1) || strlen($tenorstimme2) || strlen($tenorstimme3))
		{
			$register = 'tenor';
			$result_insert = mysql_query("INSERT INTO audio (id,aktiv, event, werk, satznummer, satz, bezeichnung, register,  stimme1, stimme2,  stimme3, anmerkung) 
								VALUES (NULL, '$aktiv', '$event','$werk', '$satznummer', '$satz','$bezeichnung', '$tenorregister','$tenorstimme1','$tenorstimme2','$tenorstimme3','$anmerkung')");
	
			print 'INSERT tenor error: *'.mysql_error().'*<br>';
			$tenorresultat=mysql_affected_rows($db);	
			#print 'INSERT tenor resultat affected_rows: *'.$tenorresultat.'*<br>';		
			print '<p>Rückgabe von INSERT tenor: *'. $result_insert.'*</p>';

		} # end Tenor
		else
		{
			$tenorresultat=0;
		}
		
		# Bass
		if (strlen($bassstimme1) || strlen($bassstimme2) || strlen($bassstimme3))
		{
			$register = 'bass';
			$result_insert = mysql_query("INSERT INTO audio (id,aktiv, event, werk, satznummer, satz, bezeichnung, register,  stimme1, stimme2,  stimme3, anmerkung) 
								VALUES (NULL, '$aktiv', '$event','$werk', '$satznummer', '$satz','$bezeichnung', '$bassregister','$bassstimme1','$bassstimme2','$bassstimme3','$anmerkung')");
	
			print 'INSERT bass error: *'.mysql_error().'*<br>';
			$bassresultat=mysql_affected_rows($db);	
			#print 'INSERT bass resultat affected_rows: *'.$bassresultat.'*<br>';		
			print '<p>Rückgabe von INSERT bass: *'. $result_insert.'*</p>';

		} # end Bass
		else
		{
			$bassresultat=0;
		}
		
		# alle
		if (strlen($alle))
		{
			$register = 'alle';
			$result_insert = mysql_query("INSERT INTO audio (id,aktiv, event, werk, satznummer, satz, bezeichnung, register,  stimme1, anmerkung) 
								VALUES (NULL, '$aktiv', '$event','$werk', '$satznummer', '$satz','$bezeichnung', '$alleregister','$alle','$anmerkung')");
	
			print 'INSERT alle error: *'.mysql_error().'*<br>';
			$alleresultat=mysql_affected_rows($db);	
			print 'INSERT alle resultat affected_rows: *'.$alleresultat.'*<br>';		
			print '<p>Rückgabe von INSERT alle: *'. $result_insert.'*</p>';

		} # end alle
		else
		{
			$alleresultat=0;
		}
		


		print '<div class = " adminconfirmabschnitt1">';
		print '<h2 class="lernmedien">neuer Datensatz eingefügt</h2>';

		print '<p class = "liste"><b>neue Daten:</b></p>';
		print '<p class = "liste">aktiv:*'.$aktiv.'*</p>';
		print '<p class = "liste">event:*'.$event.'*</p>';
		print '<p class = "liste">werk:*'.$werk.'*</p>';
		print '<p class = "liste">satznummer:*'.$satznummer.'*</p>';
		print '<p class = "liste">satz:*'.$satz.'*</p>';
		print '<p class = "liste">bezeichnung:*'.$bezeichnung.'*</p>';
		
		print '<p class = "liste">register:*'.$sopranregister.'*</p>';
		if ($sopranresultat==1)
		{				
			print '<p class = "liste">stimme1:*'.$sopranstimme1.'*</p>';
			print '<p class = "liste">stimme2:*'.$sopranstimme2.'*</p>';
			print '<p class = "liste">stimme3:*'.$sopranstimme3.'*</p>';
		}
		else
		{
		# keine Einträge
		#	print '<p class = "liste"> Beim Importieren der Sopranstimmen ist ein Fehler aufgetreten.</p>';
		}
		print '<p class = "liste">register:*'.$altregister.'*</p>';
		if ($altresultat==1)
		{
			print '<p class = "liste">stimme1:*'.$altstimme1.'*</p>';
			print '<p class = "liste">stimme2:*'.$altstimme2.'*</p>';
			print '<p class = "liste">stimme3:*'.$altstimme3.'*</p>';
		 }
		 else
		 {
			#print '<p class = "liste"> Beim Importieren der Altstimmen ist ein Fehler aufgetreten.</p>';
		 }
		print '<p class = "liste">register:*'.$tenorregister.'*</p>';
		if ($tenorresultat==1)
		{
			print '<p class = "liste">stimme1:*'.$tenorstimme1.'*</p>';
			print '<p class = "liste">stimme2:*'.$tenorstimme2.'*</p>';
			print '<p class = "liste">stimme3:*'.$tenorstimme3.'*</p>';
		}
		else
		{
			#print '<p class = "liste"> Beim Importieren der Tenorstimmen ist ein Fehler aufgetreten.</p>';

		}
		print '<p class = "liste">register:*'.$bassregister.'*</p>';
		if ($bassresultat==1)
		{
			print '<p class = "liste">stimme1:*'.$bassstimme1.'*</p>';
			print '<p class = "liste">stimme2:*'.$bassstimme2.'*</p>';
			print '<p class = "liste">stimme3:*'.$bassstimme3.'*</p>';
		}
		else
		{
			print '<p class = "liste"> Beim Importieren der Bassstimmen ist ein Fehler aufgetreten.</p>';
		}

		print '<p class = "liste">register:*'.$alleregister.'*</p>';
		if ($alleresultat==1)
		{
			print '<p class = "liste">alle:*'.$alle.'*</p>';
		}
		else
		{
			print '<p class = "liste"> Beim Importieren der Allestimme ist ein Fehler aufgetreten.</p>';
		}

	
	} # kein Eingabefehler
	
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
	$alle="";
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


print '<br>';



if ($task == 'multchange')
{

	if ($multcancel)
	{
		print '<h2 class="lernmedien">Die Datensätze wurden nicht geändert</h2>';
	}
	else
	{
		$multaktion = "";
		if (isset($_POST['multaktion']))
		{
			print 'task == multchange  multaktion: '.$_POST['multaktion'].'<br>';
			$multaktion = $_POST['multaktion'];
		
		}
		$changeid = array();
		if (isset($_POST['changeid']))
		{
			print ' changeid: '.print_r($_POST['changeid']).'<br>';
			$changeid = $_POST['changeid'];
		
		}
		$changesatz =  array();
		if (isset($_POST['changesatz']))
		{
			print ' changesatz: '.print_r($_POST['changesatz']).'<br>';
			$changesatz = $_POST['changesatz'];
		
		}
		$changeregister =  array();
	
	
		if (isset($_POST['changeregister']))
		{
			print ' changeregister: '.print_r($_POST['changeregister']).'<br>';
			$changeregister = $_POST['changeregister'];
		
		}
	
		print '<div class = "adminconfirmabschnitt1">';
		print '<h2 class="lernmedien">Die Datensätze wurden geändert</h2>';
		$index=0;
		if ($multaktion == "aktivieren")
		{
				print '<h3 class="lernmedien">Die Datensätze wurden aktiviert</h3>';
				$aktiv = 1;

		}
		elseif ($multaktion == "deaktivieren")
		{
			print '<h3 class="lernmedien">Die Datensätze wurden deaktiviert</h3';

			$aktiv = 0;
		}
		foreach ($changeid as $tempid)
		{ 
			print 'id: '.$tempid.' satz: '.$changesatz[$index].' register: '.$changeregister[$index].'<br>';

			#print 'tempid: '.$tempid.' changesatz: '.$changesatz[$index].' <br>';
			$result_change = mysql_query("UPDATE audio SET aktiv = '$aktiv'   WHERE id = '$tempid'");
			$resultat=mysql_affected_rows($db);
			#print 'UPDATE error: *'.mysql_error().'*<br>';
			#print 'resultat affected_rows: *'.$resultat.'*<br>';
			#print '<p class = "liste">Rückgabe von UPDATE: *'. $result_change.'*</p>';

			if ($result_change==1)
			{


			}
			
		}# foreach

		print '</div>';
	} # if multcancel
}# end multchange

if ($task == 'delete')
{
	print'Datensatz loeschen<br>';
	print '<h2 class="lernmedien">Datensatz l&ouml;schen:</h2>';
	$index = $_POST['index'];
	#print 'Datensatz ID: '.$index.'*<br>';
	$deletename = "**";
	if ($_POST['name'])
	{
		$deletename = $_POST['name'];
	}
	
	

	$result_delete = mysql_query("DELETE FROM audio  WHERE id = '$index'");
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

if ($task == 'multdelete')
{
	print '<h2 class="lernmedien"><strong>Mehrere Datensätze löschen:</strong></h2>';
	$multaktion = "";
	if (isset($_POST['multaktion']))
	{
		#print 'task == multdelete  multaktion: '.$_POST['multaktion'].'<br>';
		
		$multaktion = $_POST['multaktion'];
		
	}
	$changeid = array();
	if (isset($_POST['changeid']))
	{
		#print 'delete changeid: '.print_r($_POST['changeid']).'<br>';
		$changeid = $_POST['changeid'];
		
	}
	$changesatz =  array();
	if (isset($_POST['changesatz']))
	{
		#print 'delete changesatz: '.print_r($_POST['changesatz']).'<br>';
		$changesatz = $_POST['changesatz'];
		
	}
	$changeregister =  array();
	
	if (isset($_POST['changeregister']))
	{
		#print 'delete changeregister: '.print_r($_POST['changeregister']).'<br>';
		$changeregister = $_POST['changeregister'];
		
	}
	$index=0;
	foreach ($changeid as $tempid)
	{ 
		#print '<p>id: '.$tempid.' Satz: '.$changesatz[$index].' Register: '.$changeregister[$index].'</p>';
		$index = $index+1;
	
	} # foreach
	print '<div class = "adminconfirmabschnitt1">';
	
	$index=0;
	print '<p>';
	foreach ($changeid as $tempid)
	{ 
		
			#print 'id: '.$tempid.' satz: '.$changesatz[$index].' register: '.$changeregister[$index].'<br>';

			#print 'tempid: '.$tempid.' changesatz: '.$changesatz[$index].' <br>';
			$result_change = mysql_query("DELETE FROM audio   WHERE id = '$tempid'");
			$resultat=mysql_affected_rows($db);
			#print 'DELETE error: *'.mysql_error().'*<br>';
			#print 'resultat affected_rows: *'.$resultat.'*<br>';
			#print '<p class = "liste">Rückgabe von DELETE: *'. $result_change.'*</p>';
			
		if ($result_change==1)
		{
			print 'id: '.$tempid.' Satz: '.$changesatz[$index].' Register: '.$changeregister[$index].'<br>';

		}
		$index = $index+1;
		
		
	}# foreach
	print '</p>';
	print '<h3 class="lernmedien">Die Datensätze wurden gelöscht</h3>';

	print '</div>';
}# end multdelete



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
	
	print '<input type="hidden" name="eingabefehler" value ="'.$eingabefehler.'">';


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
	
	print '<input type="hidden" name="stimme1" value ="'.$sopranstimme1.'">';
	print '<input type="hidden" name="stimme1" value ="'.$sopranstimme2.'">';
	print '<input type="hidden" name="stimme1" value ="'.$sopranstimme3.'">';

	print '<input type="hidden" name="stimme1" value ="'.$altstimme1.'">';
	print '<input type="hidden" name="stimme1" value ="'.$altstimme2.'">';
	print '<input type="hidden" name="stimme1" value ="'.$altstimme3.'">';

	print '<input type="hidden" name="stimme1" value ="'.$tenorstimme1.'">';
	print '<input type="hidden" name="stimme1" value ="'.$tenorstimme2.'">';
	print '<input type="hidden" name="stimme1" value ="'.$tenorstimme3.'">';

	print '<input type="hidden" name="stimme1" value ="'.$bassstimme1.'">';
	print '<input type="hidden" name="stimme1" value ="'.$bassstimme2.'">';
	print '<input type="hidden" name="stimme1" value ="'.$bassstimme3.'">';

	print '<input type="hidden" name="anmerkung" value ="'.$anmerkung.'">';
	

	
	
}
else
{
	print '<input type="hidden" name="task" value ="done">';
	print '<input type="hidden" name="sent" value ="done">';

}
#Zurueck ohne PW

#print '	<input type="hidden" name="test" value ="'.$test.'">';
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

