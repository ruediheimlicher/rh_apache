<?php
/* verbinden mit db */	
	$db=mysql_connect('localhost','root','Ideur0047');
	mysql_set_charset('utf8',$db);
	mysql_select_db("kicho", $db); 

if (isset($_POST['uploadok']))
{
	$upda=1;
	#ßheader("location:chor_upload.php?");
}
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
	if (strlen($a['datum']) ==0 && strlen($b['datum'])==0)
	{
	print 'null<br>';
	return 0;
	}
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
	
 }
function cmp_datum_desc($a, $b)
{
	#print 'datum a: '.$a['datum'].' datum b: '.$b['datum'].'<br>';
	if (strlen($a['datum']) ==0 && strlen($b['datum'])==0)
	{
	print 'null<br>';
	return 0;
	}
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
			return ($aa[0] < $bb[0]) ? 1 : -1;
		}
		else
		{
			return ($aa[1] < $bb[1]) ? 1 : -1;
		}
	
	}
	else
	{
	return ($aa[2] < $bb[2]) ? 1 : -1;
	}
	
 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"content="text/html;charset=UTF-8"/>
<title>Chor Archiv</title>

<link href="chor.css"rel="stylesheet"type="text/css"/>
</head>
<body class="archiv">
<!--<body class="archiv">-->
<?php
#POST abfragen
#print_r($_POST);
#print_r($_GET);
#print '<br>';
$suchname="Zelenka";
$suchjahr = 2011;
$suchart="GD";
$suchanmerkung = "";
$filtersuchauftritt = "";
$filtersuchjahr = 0;
$filtersuchanmerkung = "";
$listezeigen = "";
$filterauftrittliste = "";
$filterjahrliste = "";
$filterkomponistliste = "";
$filteranmerkungliste = "";
$filteranmerkungenliste = "";
$anmerkungsuchbegriff = "";
$index = 0;

if (isset($_POST['names']) && strlen($_POST['names'][0]))
{
	$suchname = $_POST['names'][0];
	#print'suchname: '. $_POST['names'][0].'<br>';
}
elseif (isset($_POST['suchname']) && strlen($_POST['suchname']))
{
	$suchname = $_POST['suchname'];
}
if (isset($_POST['jahre']) && strlen($_POST['jahre'][0]))
{
	$suchjahr = $_POST['jahre'][0];
	print'suchjahr: '. $_POST['jahre'][0].'<br>';
}
elseif (isset($_POST['suchjahr']) && strlen($_POST['suchjahr']))
{
	$suchjahr = $_POST['suchjahr'];
}
if (isset($_POST['arten']) && strlen($_POST['arten'][0]))
{
	$suchart = $_POST['arten'][0];
	#print'suchart: '. $_POST['arten'][0].'<br>';
}
elseif (isset($_POST['suchart']) && strlen($_POST['suchart']))
{
	$suchart = $_POST['suchart'];
}
# POST nach Filter durchsuchen
$filterkomponist=array();
if (isset($_POST['filterkomponist']) && strlen($_POST['filterkomponist'][0]))
{
	$filterkomponist = $_POST['filterkomponist'];
	#print'filterkomponist: '. $_POST['filterkomponist'][0].'<br>';
}
if (isset($_POST['komponistleeren']) && $_POST['komponistleeren']=="clear")
{
	$filterkomponist = array();
	#print'filterkomponist: '. $_POST['filterkomponist'][0].'<br>';
}
$filterauftrittraw=array();
if (isset($_POST['filterauftritt']) && strlen($_POST['filterauftritt'][0]))
{
	$filterauftrittraw = $_POST['filterauftritt'];
	#print'filterauftritt: '. $_POST['filterauftritt'][0].'<br>';
}
$filterauftritt = array();
for ($k=0;$k < count($filterauftrittraw);$k++)
{
	$filterauftritt[] = trim($filterauftrittraw[$k]);
}
if ((isset($_POST['auftrittleeren']) && $_POST['auftrittleeren']=="clear"))
{
	$filterauftritt = array();
	#print'filterkomponist: '. $_POST['filterkomponist'][0].'<br>';
}
$filterjahr=array();
if (isset($_POST['filterjahr']) && strlen($_POST['filterjahr'][0]))
{
	$filterjahr = $_POST['filterjahr'];
	#print'filterjahr: '. $_POST['filterjahr'][0].'<br>';
}
if ((isset($_POST['jahrleeren']) && $_POST['jahrleeren']=="clear"))
{
	$filterjahr = array();
	#print'filterkomponist: '. $_POST['filterkomponist'][0].'<br>';
}
$filteranmerkungraw=array();
if (isset($_POST['filteranmerkung']) && strlen($_POST['filteranmerkung'][0]))
{
	$filteranmerkungraw = $_POST['filteranmerkung'];
	#print'filteranmerkung: *'. $_POST['filteranmerkung'][0].'*<br>';
}
$filteranmerkung = array();
for ($k=0;$k < count($filteranmerkungraw);$k++)
{
	$filteranmerkung[] = trim($filteranmerkungraw[$k]);
}
if (isset($_POST['filtersuche']) && strlen($_POST['filtersuche']))
{
	$filteranmerkung[] = $_POST['filtersuche'];
	$anmerkungsuchbegriff = $_POST['filtersuche'];
}
$ganzeliste=0;
if (isset($_POST['listezeigen']) && strlen($_POST['listezeigen']))
{
	$listezeigen = $_POST['listezeigen'];
	#print'listezeigen: '. $_POST['listezeigen'].'<br>';
}

if ((isset($_POST['anmerkungleeren']) && $_POST['anmerkungleeren']=="clear"))
{
	$filteranmerkung = array();
	$anmerkungsuchbegriff = "";
	#print'filteranmerkung: '. $_POST['filteranmerkung'][0].'<br>';
}
print '<h2 class="suchtitel ">Archiv durchsuchen:</h2>';

$anz=0;
$maxanz=100;
#Auftritt-Vorgaben lesen
$result_auftritt = mysql_query("SELECT * FROM auftritt ", $db)or die(print '<p  >Beim Suchen nach auftritt ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'SELECT auftritt error:';
	print mysql_error();
	print '<br>';
}
$auftrittarray = array();
$auftrittlistearray = array();
$anz = 0;
while ($auftrittzeile = mysql_fetch_array($result_auftritt) )
{
if ($anz < $maxanz)
{
	$auftrittarray[] = $auftrittzeile['event'];
	#print'*'.$auftrittzeile['event'].'*<br>';
	#print_r($auftrittlistearray);
	#print '<br>';
$anz++;
}

}

#Anmerkungen-Vorgaben lesen
$result_anmerkung = mysql_query("SELECT * FROM anmerkung ", $db)or die(print '<p  >Beim Suchen nach auftritt ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'SELECT anmerkung error:';
	print mysql_error();
	print '<br>';
}
$anmerkungarray = array();
$anmerkunglistearray = array();
$anz = 0;
while ($anmerkungzeile = mysql_fetch_array($result_anmerkung) )
{
if ($anz < $maxanz)
{

	$anmerkungarray[] = $anmerkungzeile['anmerkung'];
	$anmerkunglistearray[] = $anmerkungzeile['anmerkung'];
	#print'*'.$anmerkungzeile['anmerkung'].'*<br>';
	$anz++;
	}
}

#print_r($anmerkungdatenarray);
#print '<br>';
#print 'suchart: '.$suchart.' artdatenarray: '.count($artdatenarray).'<br>';
# *************************
# Daten kontrollieren: Daten mit jahr = 0 entfernen
# *************************
$result_shit = mysql_query("DELETE FROM archiv WHERE jahr = '0' ", $db)or die(print '<p  >Beim Suchen nach jahr ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'select * error:';
	print mysql_error();
	print '<br>';
}
# *************************
# Alle Daten lesen
# *************************
$result_komponist = mysql_query("SELECT * FROM archiv ", $db)or die(print '<p  >Beim Suchen nach komponist ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'select * error:';
	print mysql_error();
	print '<br>';
}
$komponistenarray = array();
# array der ip gleich wie $ip
$anz = 0;
while ($komponist = mysql_fetch_array($result_komponist) )
{
	#print_r($komponist);
	#print '<br>';
	$zeile = $komponist['komponist'];
	trim($zeile);
	
	#print 'zeile:'.$komponist['komponist'].'<br>';
	if (strlen($zeile)&&!(in_array(trim($zeile),$komponistenarray)) )
	{
		if ($anz < $maxanz)
		{

			$komponistenarray[]=trim($zeile);
			#print '*** komponist:*'.$komponist['komponist'].'* l: '.strlen($zeile).'<br>';
			$anz++;
		}
	}
}
asort($komponistenarray,SORT_STRING);
$jahrarray = array();
$anz = 0;
mysql_data_seek($result_komponist,0);
while ($tempdatum = mysql_fetch_array($result_komponist) )
{
	#print_r($komponist);
	#print '<br>';
	$zeile = $tempdatum['datum'];
	if (strlen($zeile))
	{
		$tempjahr = explode('.',$zeile);
		$tempjahr = $tempjahr[2];
	
	
		#print 'zeile:'.$komponist['komponist'].'<br>';
		if ($tempjahr && !(in_array(trim($tempjahr),$jahrarray)) )
		{
			if ($anz < $maxanz)
			{

				$jahrarray[]=($tempjahr);
				#print '*** tempdatum:*'.$tempdatum['datum'].'* l: '.strlen($zeile).'<br>';
				$anz++;
			}
		}
	}# if strlen
}
asort($jahrarray,SORT_STRING);
$artarray = array();
$anz = 0;
mysql_data_seek($result_komponist,0);
while ($tempart = mysql_fetch_array($result_komponist) )
{
	#print_r($komponist);
	#print '<br>';
	$zeile = $tempart['art'];
	if (strlen($zeile))
	{
		#print 'zeile:'.$komponist['komponist'].'<br>';
		if ($zeile && !(in_array(trim($zeile),$artarray)) )
		{
			if ($anz < $maxanz)
			{
				$artarray[]=($zeile);
				#print '*** tempdatum:*'.$tempdatum['datum'].'* l: '.strlen($zeile).'<br>';
				$anz++;
			}
		}
	}
}
asort($artarray,SORT_STRING);
$anmerkungenarray = array();
$anz = 0;
mysql_data_seek($result_komponist,0);
while ($tempanmerkung = mysql_fetch_array($result_komponist) )
{
	#print_r($tempanmerkung);
	#print '<br>';
	$zeile = $tempanmerkung['anmerkung'];
	#print 'zeile:'.$zeile.'<br>';
	if (strlen($zeile))
	{
		#print 'zeile:'.$komponist['komponist'].'<br>';
		if ($zeile && !(in_array(trim($zeile),$anmerkungenarray)) )
		{
		if ($anz < $maxanz)
		{

			$anmerkungenarray[]=(trim($zeile));
			#print '*** tempanmerkung:*'.$tempanmerkung['anmerkung'].'* l: '.strlen($zeile).'<br>';
			$anz++;
			}
		}
	}
}


asort($anmerkungenarray,SORT_STRING);
 
print '<div class = "archivselectabschnitt">';
print '<table style = " top: 0px; width:97% ">';
print '<tr style = "height:30px; ">';
print '<th style = "border:none; width: 120px;padding-top: 8px;" >';
print '<h2 class="selectuntertitel ">Auswahl </h2>';
print '</th>';
print '<th style = "border:none; padding-bottom: 0px; width: 250px;">';
print '<form method="POST" action="">';
print '<input type="submit"   name = "ganzeliste"  style="font-size:14pt;color:#0000C0;  margin-left:15px margin-top:5px" value="ganze Liste" >';
print '<input type = "hidden" name = "listezeigen" value = 1>';
print '</form>';
print '</th>';
print '<th class = "anleitung">';
print 'Klicke in einer oder mehreren Kolonnen auf den Begriff, der ausgewählt werden soll.<br>';
print 'Klicke dann auf die Taste <strong>Suchen</strong>.<br>';
print '&nbsp;&nbsp;Nach dem Begriff im Suchfeld wird in der Kolonne <strong>Anmerkungen</strong> gesucht.<br>';
print '&nbsp;&nbsp;Ein Klick auf die <strong>clear</strong>-Taste löscht die Auswahl in der betreffenden Kolonne.<br>';
print '&nbsp;&nbsp;Bei den Konzerten führt die Taste <strong>Prog</strong> zum Programm und zum Plakat des Konzerts.';
print '</th>';
print '</tr>';
print '</table>';
#print_r($filterkomponist);
#print'<br>';
print '<form method="POST" action="">';
#print '<input type = "hidden" name = "komponistleeren" value = 0>';
print '<table class = "archivtable">';
print '<tr height = 28px>';
print '<th class = "text" width = "180px">';
print '<div class = "headerabschnitt">Komponist';
print '<div class = "tasteabschnitt"> <input type="submit" name = "komponistleeren" style=" color:#0000C0;  margin-left:0px" value="clear" ></div></div>';
print '</th>';
print '<th class = "text" width = "160px">';
print '<div class = "headerabschnitt">Auftritt';
print ' <div class = "tasteabschnitt"><input type="submit" name = "auftrittleeren" style=" color:#0000C0;  margin-left:15px" value="clear" ></div></div>';
print '</td>';
print '<th class = "text" width = "120px">';
print '<div class = "headerabschnitt">Jahr';
print ' <div class = "tasteabschnitt"><input type="submit"   name = "jahrleeren" style=" color:#0000C0;  margin-left:15px" value="clear" ></div></div>';
#print '</div>';
print '</td>';
print '<th class = "text" >';
print '<div class = "headerabschnitt">Anmerkungen';	

	print '<div class = "tasteabschnitt">suchen:<input type = "text" value = "'.$anmerkungsuchbegriff.'" style = font-size:8pt;top:0px;bottom:0px;height:12px;" name = "filtersuche" size = "10">';
		print ' <input type="submit" name = "anmerkungleeren" style=" color:#0000C0;  margin-left:15px" value="clear" >';
	print ' </div>';
print ' </div>';
print '</td>';

#print '<th class = "text" width = "200px">Art</td>';

print '</tr>';

# Checkboxen aufbauen
# http://aktuell.de.selfhtml.org/artikel/php/checkboxen/
print '<tr height = 200px>';
print '<td class = "archivtd">';
	
print '<div class = "filterabschnitt">';


#sort($komponistenarray,SORT_STRING);

$komponistlistearray = $komponistenarray;
sort($komponistlistearray,SORT_STRING);

for ($i =1;$i < count($komponistlistearray);$i++)
{
	if (in_array($komponistlistearray[$i],$filterkomponist))
	{
	print '<input type="checkbox" name="filterkomponist[]" value="'.$komponistlistearray[$i].'" checked = "yes">'.$komponistlistearray[$i].'<br>';
	if (strlen($filterkomponistliste))
		{
			$filterkomponistliste= $filterkomponistliste.', '.$komponistlistearray[$i]; 
		}
		else
		{
			$filterkomponistliste= $komponistlistearray[$i];
		}
	}
	else
	{
	print '<input type="checkbox" name="filterkomponist[]" value="'.$komponistlistearray[$i].'" >'.$komponistlistearray[$i].'<br>';
	}
	
}
print'<br>';
print '</div>'; #filterabschnitt
print '</td>';

print '<td class = "archivtd">';
print '<div class = "filterabschnitt">';

for ($i =0;$i < count($auftrittarray);$i++)
{
	#print 'auftrittlistearray: *'.$auftrittlistearray[$i].'*<br>';
	
	if (in_array($auftrittarray[$i],$filterauftritt))
	{
		print '<input type="checkbox" name="filterauftritt[]" value="'.$auftrittarray[$i].' " checked = "yes"> '.$auftrittarray[$i].' <br>';
		if (strlen($filterauftrittliste))
		{
			$filterauftrittliste= $filterauftrittliste.', '.$auftrittarray[$i]; 
		}
		else
		{
			$filterauftrittliste= $auftrittarray[$i];
		}
		
	}
	else
	{
		print '<input type="checkbox" name="filterauftritt[]" value="'.$auftrittarray[$i].' " > '.$auftrittarray[$i].' <br>';
	}
}

print'<br>';
print '</div>'; #filterabschnitt

print '</td>';

print '<td class = "archivtd">';
print '<div class = "filterabschnitt">';
rsort($jahrarray,SORT_NUMERIC);
for ($i =0;$i < count($jahrarray);$i++)
{
	if (in_array($jahrarray[$i],$filterjahr))
	{
		print '<input type="checkbox" name="filterjahr[]" value="'.$jahrarray[$i].'" checked = "yes"> '.$jahrarray[$i].'<br>';
	if (strlen($filterjahrliste))
		{
			$filterjahrliste= $filterjahrliste.', '.$jahrarray[$i]; 
		}
		else
		{
			$filterjahrliste= $jahrarray[$i];
		}
		
	}
	else
	{
		print '<input type="checkbox" name="filterjahr[]" value="'.$jahrarray[$i].'"> '.$jahrarray[$i].'<br>';
	}
}
print'<br>';
print '</div>'; #filterabschnitt

print '</td>';

print '<td class = "archivtd">';
print '<div class = "filterabschnitt">';

for ($i =0;$i < count($anmerkungarray);$i++)
{
	#print 'anmerkungenarray: *'.$anmerkungarray[$i].'*<br>';
	
	if (in_array($anmerkungarray[$i],$filteranmerkung))
	{
		print '<input type="checkbox" name="filteranmerkung[]" value="'.$anmerkungarray[$i].' " checked = "yes"> '.$anmerkungarray[$i].' <br>';
		if (strlen($filteranmerkungliste))
		{
			$filteranmerkungenliste= $filteranmerkungenliste.', '.$anmerkungarray[$i]; 
		}
		else
		{
			$filteranmerkungenliste= $anmerkungarray[$i];
		}
		
	}
	else
	{
		print '<input type="checkbox" name="filteranmerkung[]" value="'.$anmerkungarray[$i].' " > '.$anmerkungarray[$i].' <br>';
	}
}
print'<br>';
print '</div>'; #filterabschnitt

print '</td>';

print '</tr>';

print '</table>';
print ' <input type="submit" style="color:#0000C0; font-size:20pt; width: 180px; margin-left:20px;margin-top:10px;margin-bottom:10px;" value="Suchen" >';
print '</form>';
#print'filterkomponist: ';
#print_r($filterkomponist);
#print'* * filterauftritt: ';
#print_r($filterauftritt);
#print'* * filterjahr: ';
#print_r($filterjahr);
#print'* * filteranmerkung: ';
#print_r($filteranmerkung);
#print'<br>';
print '</div>'; #archivselectabschnitt;
# Liste der Filterergebnisse
print '<div class = "archivtabelleabschnitt">';
$filtersuchkomponist='';
if (count($filterkomponist))
{
	# http://stackoverflow.com/questions/907806/php-mysql-using-an-array-in-where-clause
	foreach($filterkomponist as $filterkomponistzeile) 
	{
		$filterkomponistzeile_sql[] = '\''.$filterkomponistzeile.'\'';
    }
    $filtersuchkomponist = implode(',',$filterkomponistzeile_sql);
}
if (count($filterauftritt))
{
	# http://stackoverflow.com/questions/907806/php-mysql-using-an-array-in-where-clause
	foreach($filterauftritt as $filterauftrittzeile) 
	{
		$filterauftrittzeile_sql[] = '\''.$filterauftrittzeile.'\'';
    }
    $filtersuchauftritt = implode(',',$filterauftrittzeile_sql);
}
if (count($filterjahr))
{
	# http://stackoverflow.com/questions/907806/php-mysql-using-an-array-in-where-clause
	foreach($filterjahr as $filterjahrzeile) 
	{
		$filterjahrzeile_sql[] = '\''.$filterjahrzeile.'\'';
    }
    $filtersuchjahr = implode(',',$filterjahrzeile_sql);
}

if (count($filteranmerkung))
{
	# http://stackoverflow.com/questions/907806/php-mysql-using-an-array-in-where-clause
	foreach($filteranmerkung as $filteranmerkungzeile) 
	{
		#$filteranmerkungzeile_sql[] = '\'%'.$filteranmerkungzeile.'%\'';
		#$filteranmerkungzeile_sql[] = '\''.$filteranmerkungzeile.'\'';
		$filteranmerkungzeile_sql[] = ''.$filteranmerkungzeile.'';
    }
    $filtersuchanmerkung = implode('|',$filteranmerkungzeile_sql);
    $filtersuchanmerkung = '\''.$filtersuchanmerkung.'\'';
}
$wherestring ="";
$wherearray = array();# array mit filtersuche*
#print_r($filtersuchkomponist);
#print '<br>';
#print 'filtersuchkomponist: *'.$filtersuchkomponist.'*<br>';
if (strlen($filtersuchkomponist))
{
	$wherearray[] = ' komponist IN ('.$filtersuchkomponist.') ';
}
#print '*<br>';
#print_r($filtersuchauftritt);
#print '<br>*';
#print 'filtersuchauftritt: *'.$filtersuchauftritt.'*<br>';
if (strlen($filtersuchauftritt))
{
	$wherearray[] = ' art IN ('.$filtersuchauftritt.') ';
}
if (strlen($filtersuchjahr)>1)
{
	$wherearray[] = ' jahr IN ('.$filtersuchjahr.') ';
}
if (strlen($filtersuchanmerkung))
{
	$wherearray[] = ' anmerkung REGEXP ('.$filtersuchanmerkung.') ';
}
$wherestring = implode('AND',$wherearray);
#print_r($wherearray);
#print '<br>';
#print 'wherestring: '.$wherestring.'<br>';
if ($listezeigen == 1)
{
	#print 'Liste zeigen ';
	$result_komponistdaten = mysql_query("SELECT * FROM archiv 
	", $db)or die(print '<p  >Beim Suchen nach komponistdaten zeile 1636 ist ein Fehler passiert: '.mysql_error().'</p>');
	if (mysql_error())
	{
		print 'SELECT suchname error:';
		print mysql_error();
		print '<br>';
	}
	$filterdatenarray = array();
	while ($komponistdaten = mysql_fetch_array($result_komponistdaten) )
	{
		$zeilendic["komponist"] = $komponistdaten['komponist'];
		$zeilendic["komponist_vn"] = $komponistdaten['komponist_vn'];
		$zeilendic["werk"] = $komponistdaten['werk'];
		$zeilendic["art"] = $komponistdaten['art'];
		$zeilendic["teil"] = $komponistdaten['teil'];
		$zeilendic["quelle"] = $komponistdaten['quelle'];
		$zeilendic["datum"] = $komponistdaten['datum'];
		$zeilendic["anmerkung"] = $komponistdaten['anmerkung'];
		$filterdatenarray[] = $zeilendic;
		
	}
		#print 'komponistdatenarray: ';
		#print_r($filterkomponist);
		#print '*<br>';
	#usort($filterdatenarray,"cmp_datum");
	#sort($filterdatenarray);
	#print '<br>';
	print '<br><h3 class="auswahluntertitel">Alle Auftritte </h3><br>';
	print '<table class = "archivtable">';
	print '<tr height = 24px>';
	print '<th class = "text" width = "70px">Datum</td>';
	print '<th class = "text" width = "80px">Komponist</td>';
	print '<th class = "text" width = "180px">Werk</td>';
	print '<th class = "text" width = "250px">Teil</td>';
	
	
	
	print '<th class = "text" width = "120px">Auftritt</td>';
	print '<th class = "text" >Anmerkungen</td>';
	
	print '</tr>';
	
	$lastdatum=0;
	$lastanmerkung = "";
	
	foreach($filterdatenarray as $datenzeile)
	{
		# neues Datum detektieren
		if ($datenzeile['datum'] == $lastdatum)
		{
			$neuerauftritt = 0;
		}
		else
		{
			$lastdatum = $datenzeile['datum'];
			$neuerauftritt = 1;
			$index++;
		}
		
		# Auftritte unterschiedlich faerben
		if ($index%2)
		{
			print '<tr height = "20px" bgcolor="#ffe0ff" >';
		}
		else
		{
			print '<tr height = "20px" bgcolor="#fff" >';
		}
	
		if ($neuerauftritt)
		{
			print '<td class = "archivtd" >'.$datenzeile['datum'].'</td>';
		
			print '<td class = "archivtd">'.$datenzeile['komponist'].'</td>';
			print '<td class = "archivtd">'.$datenzeile['werk'].'</td>';
			print '<td class = "archivtd">'.$datenzeile['teil'].'</td>';
		
			if (($datenzeile['art'] == "Konzert") || ($datenzeile['art'] == "Serenade"))
			{
				print '<form action = "chor_programm.php" method = "POST" target = "_blank">';
				print ' <td class = "archivtd">'.$datenzeile['art'];
				
				# Taste Prog
				print '<input type="submit" name = "programm" style=" color:#0000C0;  margin-left:15px" value="Prog" target = "_blank" >';
				$tempdatum = explode('.',$datenzeile['datum']);
				#
				print '<input type = "hidden" name = "programmtag" value = "'.$tempdatum[0].'">';
				print '<input type = "hidden" name = "programmmonat" value = "'.$tempdatum[1].'">';
				print '<input type = "hidden" name = "programmjahr" value = "'.$tempdatum[2].'">';
				print '<input type = "hidden" name = "alle" value = "0">';
				print '</td>';
				print '</form>';
			}
			else
			{
				# nur Art
				print '<td class = "archivtd">'.$datenzeile['art'].'</td>';
			}
			print '<td class = "archivtd">'.$datenzeile['anmerkung'].'</td>';
		}
		else
		{
			print '<td class = "archivtd"></td>'; # Datum leer
			print '<td class = "archivtd">'.$datenzeile['komponist'].'</td>';
			print '<td class = "archivtd">'.$datenzeile['werk'].'</td>';
			print '<td class = "archivtd">'.$datenzeile['teil'].'</td>';
			print '<td class = "archivtd"></td>'; # art leer
			print '<td class = "archivtd"></td>'; # Anmerkungen leer
		}
		print '</tr>';
	}# foreach
	print '</table><br>';
}
else #if (count($filterkomponist) || count($filterauftritt) || count($filterjahr) || count($filteranmerkung) ) # es gibt eine Suchanfrage
	{
	/*
	$result_komponistdaten = mysql_query("SELECT * FROM archiv WHERE 
	komponist IN ($filtersuchkomponist)
	AND jahr IN ($filtersuchjahr)
	", $db)or die(print '<p  >Beim Suchen nach komponistdaten ist ein Fehler passiert: '.mysql_error().'</p>');
	*/
	#print '<p  >result_komponistdaten wherestring: : '.$wherestring.'</p>';
	$filterdatenarray = array();
	#print 'wherestring: *'.$wherestring.'*<br>';
	if (strlen($wherestring))
		{
		$result_komponistdaten = mysql_query("SELECT * FROM archiv WHERE $wherestring ", $db) OR die(print '<p  >Beim Suchen nach komponistdaten zeile 849 ist ein Fehler passiert: '.mysql_error().'</p>');
		if (mysql_error())
		{
			print 'SELECT suchname error:';
			print mysql_error();
			print '<br>';
		}
		while ($komponistdaten = mysql_fetch_array($result_komponistdaten) )
		{
			$zeilendic["komponist"] = $komponistdaten['komponist'];
			$zeilendic["komponist_vn"] = $komponistdaten['komponist_vn'];
			$zeilendic["werk"] = $komponistdaten['werk'];
			$zeilendic["art"] = $komponistdaten['art'];
			$zeilendic["teil"] = $komponistdaten['teil'];
			$zeilendic["quelle"] = $komponistdaten['quelle'];
			$zeilendic["nr"] = $komponistdaten['nr'];
			$zeilendic["datum"] = $komponistdaten['datum'];
			$zeilendic["anmerkung"] = $komponistdaten['anmerkung'];
			$filterdatenarray[] = $zeilendic;
		}
		usort($filterdatenarray,"cmp_datum_desc");
		#print 'komponistdatenarray: <br>';
		#var_dump($filterdatenarray);
		#print '*<br>';
		foreach($filterdatenarray as $datenzeile)
		{
			#print 'datenzeile: '.$datenzeile['datum'].'<br>';
		}
		#print '*<br>';
	
		#sort($filterdatenarray);
		#print '<br>';
		# Filterdaten aufzeigen	
	
	
		print '<table style = " top: 0px;">';
		print '<tr style = "height:40px; ">';
		if (strlen($filterkomponistliste))
		{
			print '<th style = "border:none; width: auto;padding-top: 4px;padding-bottom: 4px;" >';
			print '<h3 class="auswahluntertitel">Komponisten:<br>'.$filterkomponistliste.'</h3>';
			print '</th>';
		}
		if (strlen($filterauftrittliste))
		{
			print '<th style = "border:none; width: auto;padding-top: 4px;padding-bottom: 4px;" >';
			print '<h3 class="auswahluntertitel">Auftritte:<br>'.$filterauftrittliste.'</h3>';
			print '</th>';
		}
		if (strlen($filterjahrliste))
		{
			print '<th style = "border:none; width: auto;padding-top: 4px;padding-bottom: 4px;" >';
			print '<h3 class="auswahluntertitel">Jahre:<br>'.$filterjahrliste.'</h3>';
			print '</th>';
		}
		if (strlen($filteranmerkungenliste))
		{
			print '<th style = "border:none; width: auto;padding-top: 4px;padding-bottom: 4px;" >';
			print '<h3 class="auswahluntertitel">Anmerkungen:<br>'.$filteranmerkungenliste.'</h3>';
			print '</th>';
		}
	
		#$wherestring fuer Filter
		if (strlen($filtersuchanmerkung))
		{
			print '<th style = "border:none; width: auto;padding-top: 4px;padding-bottom: 4px;" >';
			print '<h3 class="auswahluntertitel">Suchbegriff:<br>'.$anmerkungsuchbegriff.'</h3>';
			print '</th>';
		}
	
		print '</tr>';
		print '</table>';
	
		print '<table class = "archivtable">';
		print '<tr height = 24px>';
		print '<th class = "text" width = "70px">Datum</td>';
		print '<th class = "text" width = "80px">Komponist</td>';
		print '<th class = "text" width = "180px">Werk</td>';
		print '<th class = "text" width = "300px">Teil</td>';
		print '<th class = "text" width = "60px">Quelle</td>';
		print '<th class = "text" width = "120px">Auftritt</td>';
	
		print '<th class = "text" >Anmerkungen</td>';
	
		print '</tr>';
		$lastdatum = 0;
		$lastart = "";
		$lastanmerkung = "";
		$neuerauftritt = 1;
		$index=0;
		foreach($filterdatenarray as $datenzeile)
		{
			#print 'index: '.$index.'<br>';
		
			# neues Datum detektieren
			if ($datenzeile['datum'] == $lastdatum)
			{
				$neuerauftritt = 0;
			}
			else
			{
				$lastdatum = $datenzeile['datum'];
				$neuerauftritt = 1;
				$index++;
			}
		
			# Auftritte unterschiedlich faerben
			if ($index%2)
			{
			print '<tr height = "20px" bgcolor="#ffe0ff" >';
			}
			else
			{
			print '<tr height = "20px" bgcolor="#fff" >';
			}
		
			if ($neuerauftritt)
			{
				print '<td class = "archivtd" >'.$datenzeile['datum'].'</td>';
				print '<td class = "archivtd">'.$datenzeile['komponist'].'</td>';
				print '<td class = "archivtd">'.$datenzeile['werk'].'</td>';
				print '<td class = "archivtd">'.$datenzeile['teil'].'</td>';
			
				print '<td class = "archivtd">'.$datenzeile['quelle'].' '.$datenzeile['nr'].'</td>';
			
				if (($datenzeile['art'] == "Konzert") || ($datenzeile['art'] == "Serenade"))
				{
					print '<form action = "chor_programm.php" method = "POST" target = "_blank">';
					print ' <td class = "archivtd">'.$datenzeile['art'];
					print '<input type="submit" name = "programm" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0;  margin-left:15px" value="Prog" target = "_blank" >';
					$tempdatum = explode('.',$datenzeile['datum']);
					#
					print '<input type = "hidden" name = "programmtag" value = "'.$tempdatum[0].'">';
					print '<input type = "hidden" name = "programmmonat" value = "'.$tempdatum[1].'">';
					print '<input type = "hidden" name = "programmjahr" value = "'.$tempdatum[2].'">';
					print '<input type = "hidden" name = "alle" value = "0">';
			
					print '</td>';
					print '</form>';
				}
				else
				{
					# nur Art
					print '<td class = "archivtd">'.$datenzeile['art'].'</td>';
				}
				print '<td class = "archivtd">'.$datenzeile['anmerkung'].'</td>';
		
		
			}
			else # nicht neuer Auftritt
			{
				print '<td class = "archivtd"></td>'; # Datum leer
				print '<td class = "archivtd">'.$datenzeile['komponist'].'</td>';
				print '<td class = "archivtd">'.$datenzeile['werk'].'</td>';
				print '<td class = "archivtd">'.$datenzeile['teil'].'</td>';
				$quellestring = $datenzeile['quelle'];
			
				#print '<td title = "KGB: Kirchengesangbuch" class = "archivtd">'.$datenzeile['quelle'].' '.$datenzeile['nr'].'</td>';
				print '<td  class = "archivtd">'.$datenzeile['quelle'].' '.$datenzeile['nr'].'</td>';
			
				print '<td class = "archivtd"></td>'; # art leer
				print '<td class = "archivtd"></td>'; # Anmerkungen leer
			}	
			print '</tr>';
		}# foreach
	print '</table><br>';
} # if wherestring
else
{
	print '<h2 class="suchtitel ">Die Suchanfrage ergab keine Ergebnisse.</h2>';

}



print '</div>'; #archivtabelleabschnitt;
print '</div>'; # archivselectabschnitt
$result_jahrdaten = mysql_query("SELECT * FROM archiv WHERE jahr = '$suchjahr' ORDER BY datum", $db)or die(print '<p  >Beim Suchen nach jahrdaten ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'SELECT suchname error:';
	print mysql_error();
	print '<br>';
}
$jahrdatenarray = array();
while ($jahrdatenzeile = mysql_fetch_array($result_jahrdaten) )
{
	$zeilendic["komponist"] = $jahrdatenzeile['komponist'];
	$zeilendic["komponist_vn"] = $jahrdatenzeile['komponist_vn'];
	$zeilendic["werk"] = $jahrdatenzeile['werk'];
	$zeilendic["art"] = $jahrdatenzeile['art'];
	$zeilendic["teil"] = $jahrdatenzeile['teil'];
	
	$zeilendic["quelle"] = $jahrdatenzeile['quelle'];
	
	$zeilendic["datum"] = $jahrdatenzeile['datum'];
	$zeilendic["anmerkung"] = $jahrdatenzeile['anmerkung'];
	
	#print_r($komponistdaten);
	#print '<br>';
	#print 'name: '.$komponistdaten['komponist'].' Werk: '.$komponistdaten['werk'].' Teil: '.$komponistdaten['teil'].' Datum: '.$komponistdaten['tag'].'<br>';
	$jahrdatenarray[] = $zeilendic;
}

}
	#print_r($komponistdatenarray);
	#print '<br>';
print '</div>';	# adminContent
print '</div>';	# admin
?>
</body>
</html>

