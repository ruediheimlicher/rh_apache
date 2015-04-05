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
if (strlen($a) ==0 && strlen($b)==0)
{
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
print '<h2 class="archivtitel ">Chor Archiv</h2>';

#POST abfragen
#print_r($_POST);
#print_r($_GET);
#print '<br>';
$suchname="Zelenka";
$suchjahr = 2011;
$suchart="GD";

if (strlen($_POST['names'][0]))
{
	$suchname = $_POST['names'][0];
	#print'suchname: '. $_POST['names'][0].'<br>';
}
elseif (strlen($_POST['suchname']))
{
	$suchname = $_POST['suchname'];
}

if (strlen($_POST['jahre'][0]))
{
	$suchjahr = $_POST['jahre'][0];
	print'suchjahr: '. $_POST['jahre'][0].'<br>';
}
elseif (strlen($_POST['suchjahr']))
{
	$suchjahr = $_POST['suchjahr'];
}

if (strlen($_POST['arten'][0]))
{
	$suchart = $_POST['arten'][0];
	#print'suchart: '. $_POST['arten'][0].'<br>';
}
elseif (strlen($_POST['suchart']))
{
	$suchart = $_POST['suchart'];
}


# POST nach Filter durchsuchen
$filterkomponist=array();
if (strlen($_POST['filterkomponist'][0]))
{
	$filterkomponist = $_POST['filterkomponist'];
	#print'filterkomponist: '. $_POST['filterkomponist'][0].'<br>';
}

if (($_POST['komponistleeren']=="clear"))
{
	$filterkomponist = array();
	#print'filterkomponist: '. $_POST['filterkomponist'][0].'<br>';
}



$filterauftrittraw=array();
if (strlen($_POST['filterauftritt'][0]))
{
	$filterauftrittraw = $_POST['filterauftritt'];
	#print'filterauftritt: '. $_POST['filterauftritt'][0].'<br>';
}

$filterauftritt = array();
for ($k=0;$k < count($filterauftrittraw);$k++)
{
	$filterauftritt[] = trim($filterauftrittraw[$k]);
}

if (($_POST['auftrittleeren']=="clear"))
{
	$filterauftritt = array();
	#print'filterkomponist: '. $_POST['filterkomponist'][0].'<br>';
}


$filterjahr=array();
if (strlen($_POST['filterjahr'][0]))
{
	$filterjahr = $_POST['filterjahr'];
	#print'filterjahr: '. $_POST['filterjahr'][0].'<br>';
}

if (($_POST['jahrleeren']=="clear"))
{
	$filterjahr = array();
	#print'filterkomponist: '. $_POST['filterkomponist'][0].'<br>';
}


$filteranmerkungraw=array();
if (strlen($_POST['filteranmerkung'][0]))
{
	$filteranmerkungraw = $_POST['filteranmerkung'];
	#print'filteranmerkung: *'. $_POST['filteranmerkung'][0].'*<br>';
}
$filteranmerkung = array();
for ($k=0;$k < count($filteranmerkungraw);$k++)
{
	$filteranmerkung[] = trim($filteranmerkungraw[$k]);
}

if (strlen($_POST['filtersuche']))
{
	$filteranmerkung[] = $_POST['filtersuche'];
	$anmerkungsuchbegriff = $_POST['filtersuche'];
}

$ganzeliste=0;
if (strlen($_POST['listezeigen']))
{
	$listezeigen = $_POST['listezeigen'];
}
#print'listezeigen: '. $_POST['listezeigen'].'<br>';


if (($_POST['anmerkungleeren']=="clear"))
{
	$filteranmerkung = array();
	$anmerkungsuchbegriff = "";
	#print'filteranmerkung: '. $_POST['filteranmerkung'][0].'<br>';
}


print '<h2 class="suchtitel ">Archiv durchsuchen:</h2>';


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
while ($auftrittzeile = mysql_fetch_array($result_auftritt) )
{
$auftrittarray[] = $auftrittzeile['event'];
$auftrittlistearray[] = $auftrittzeile['event'];
#print'*'.$auftrittzeile['event'].'*<br>';
}
#print_r($auftrittlistearray);
#print '<br>';


#Art-Daten suchen


$result_artdaten = mysql_query("SELECT * FROM archiv WHERE art LIKE '%$suchart%'", $db)or die(print '<p  >Beim Suchen nach artdaten ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'SELECT suchname error:';
	print mysql_error();
	print '<br>';
}



$artdatenarray = array();
while ($artdatenzeile = mysql_fetch_array($result_artdaten) )
{
	#print_r($artdatenzeile);
	#print '<br>';

	$zeilendic["komponist"] = $artdatenzeile['komponist'];
	$zeilendic["komponist_vn"] = $artdatenzeile['komponist_vn'];
	$zeilendic["werk"] = $artdatenzeile['werk'];
	$zeilendic["jahr"] = $artdatenzeile['jahr'];

	$zeilendic["teil"] = $artdatenzeile['teil'];
	$zeilendic["datum"] = $artdatenzeile['datum'];
	$zeilendic["anmerkung"] = $artdatenzeile['anmerkung'];
	
	#print_r($komponistdaten);
	#print '<br>';
	#print 'name: '.$komponistdaten['komponist'].' Werk: '.$komponistdaten['werk'].' Teil: '.$komponistdaten['teil'].' Datum: '.$komponistdaten['tag'].'<br>';
	$artdatenarray[] = $zeilendic;
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
while ($anmerkungzeile = mysql_fetch_array($result_anmerkung) )
{
	$anmerkungarray[] = $anmerkungzeile['anmerkung'];
	$anmerkunglistearray[] = $anmerkungzeile['anmerkung'];
	#print'*'.$anmerkungzeile['anmerkung'].'*<br>';
}
#print_r($anmerkunglistearray);
#print '<br>';


# Anmerkung-Daten lesen

$result_anmerkungdaten = mysql_query("SELECT * FROM archiv WHERE anmerkung LIKE '%$suchanmerkung%'", $db)or die(print '<p  >Beim Suchen nach artdaten ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'SELECT suchanmerkung error:';
	print mysql_error();
	print '<br>';
}

$anmerkungdatenarray = array();
$zeilendic = array();
while ($anmerkungdatenzeile = mysql_fetch_array($result_anmerkungdaten) )
{
	#print_r($artdatenzeile);
	#print '<br>';

	$zeilendic["komponist"] = $anmerkungdatenzeile['komponist'];
	$zeilendic["komponist_vn"] = $anmerkungdatenzeile['komponist_vn'];
	$zeilendic["werk"] = $anmerkungdatenzeile['werk'];
	$zeilendic["jahr"] = $anmerkungdatenzeile['jahr'];

	$zeilendic["teil"] = $anmerkungdatenzeile['teil'];
	$zeilendic["datum"] = $anmerkungdatenzeile['datum'];
	$zeilendic["anmerkung"] = $anmerkungdatenzeile['anmerkung'];
	
	#print_r($komponistdaten);
	#print '<br>';
	#print 'name: '.$komponistdaten['komponist'].' Werk: '.$komponistdaten['werk'].' Teil: '.$komponistdaten['teil'].' Datum: '.$komponistdaten['tag'].'<br>';
	$anmerkungdatenarray[] = $zeilendic;
}



	#print_r($anmerkungdatenarray);
	#print '<br>';

#print 'suchart: '.$suchart.' artdatenarray: '.count($artdatenarray).'<br>';





$result_komponist = mysql_query("SELECT * FROM archiv ", $db)or die(print '<p  >Beim Suchen nach komponist ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'select * error:';
	print mysql_error();
	print '<br>';
}

$komponistenarray = array();

# array der ip gleich wie $ip
while ($komponist = mysql_fetch_array($result_komponist) )
{
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


$jahrarray = array();

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
			$jahrarray[]=($tempjahr);
			#print '*** tempdatum:*'.$tempdatum['datum'].'* l: '.strlen($zeile).'<br>';
		}
	}
}

asort($jahrarray,SORT_STRING);


$artarray = array();

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
			$artarray[]=($zeile);
			#print '*** tempdatum:*'.$tempdatum['datum'].'* l: '.strlen($zeile).'<br>';
		}
	}
}

asort($artarray,SORT_STRING);


$anmerkungenarray = array();
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
			$anmerkungenarray[]=(trim($zeile));
			#print '*** tempanmerkung:*'.$tempanmerkung['anmerkung'].'* l: '.strlen($zeile).'<br>';
		}
	}
}

asort($anmerkungenarray,SORT_STRING);

 

print '<div class = "archivselectabschnitt">';
print '<table style = " top: 0px;">';
print '<tr style = "height:30px; ">';
print '<th style = "border:none; width: 120px;padding-top: 8px;" >';
print '<h2 class="selectuntertitel ">Filter </h2>';
print '</th>';
print '<th style = "border:none; padding-bottom: 0px;">';
print '<form method="POST" action="">';
print '<input type="submit"   name = "ganzeliste" 
style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0;  margin-left:15px margin-top:4px" value="ganze Liste" >';
print '<input type = "hidden" name = "listezeigen" value = 1>';
print '</form>';
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
	print '<div class = "tasteabschnitt"> <input type="submit" name = "komponistleeren" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0;  margin-left:0px" value="clear" ></div></div>';
	print '</th>';
	print '<th class = "text" width = "160px">';
	print '<div class = "headerabschnitt">Auftritt';
	print ' <div class = "tasteabschnitt"><input type="submit" name = "auftrittleeren" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0;  margin-left:15px" value="clear" ></div></div>';
	print '</td>';
	print '<th class = "text" width = "120px">';
	print '<div class = "headerabschnitt">Jahr';
	print ' <div class = "tasteabschnitt"><input type="submit"   name = "jahrleeren" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0;  margin-left:15px" value="clear" ></div></div>';
	#print '</div>';
	print '</td>';
	print '<th class = "text" >';
	print '<div class = "headerabschnitt">Anmerkungen';	
	
		print '<div class = "tasteabschnitt">suchen:<input type = "text" value = "'.$anmerkungsuchbegriff.'" style = font-size:8pt;top:0px;bottom:0px;height:12px;" name = "filtersuche" size = "10">';
			print ' <input type="submit" name = "anmerkungleeren" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0;  margin-left:15px" value="clear" >';
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
		sort($komponistenarray,SORT_STRING);
		for ($i =1;$i < count($komponistenarray);$i++)
		{
			if (in_array($komponistenarray[$i],$filterkomponist))
			{
			print '<input type="checkbox" name="filterkomponist[]" value="'.$komponistenarray[$i].'" checked = "yes">'.$komponistenarray[$i].'<br>';
			if (strlen($filterkomponistliste))
				{
					$filterkomponistliste= $filterkomponistliste.', '.$komponistenarray[$i]; 
				}
				else
				{
					$filterkomponistliste= $komponistenarray[$i];
				}
			}
			else
			{
			print '<input type="checkbox" name="filterkomponist[]" value="'.$komponistenarray[$i].'" >'.$komponistenarray[$i].'<br>';
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
		sort($jahrarray,SORT_NUMERIC);
		
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


print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:24pt; width: 180px; margin-left:20px;margin-top:10px;margin-bottom:10px;" value="Filtern" >';

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

print '<div class = "archivtabelleabschnitt">';

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
$wherearray = array();# array mit filtersuch*
if (count($filtersuchkomponist))
{
	$wherearray[] = ' komponist IN ('.$filtersuchkomponist.') ';
}
if (count($filtersuchauftritt))
{
	$wherearray[] = ' art IN ('.$filtersuchauftritt.') ';
}
if (count($filtersuchjahr))
{
	$wherearray[] = ' jahr IN ('.$filtersuchjahr.') ';
}

if (count($filtersuchanmerkung))
{
	$wherearray[] = ' anmerkung REGEXP ('.$filtersuchanmerkung.') ';
}

$wherestring = implode('AND',$wherearray);
#print 'wherestring: '.$wherestring.'<br>';
if ($listezeigen == 1)
{
	#print 'Liste zeigen ';
	
		$result_komponistdaten = mysql_query("SELECT * FROM archiv 
	", $db)or die(print '<p  >Beim Suchen nach komponistdaten ist ein Fehler passiert: '.mysql_error().'</p>');

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
		$zeilendic["datum"] = $komponistdaten['datum'];
		$zeilendic["anmerkung"] = $komponistdaten['anmerkung'];
		$filterdatenarray[] = $zeilendic;
	}
		#print 'komponistdatenarray: ';
		#print_r($filterkomponist);
		#print '*<br>';

	usort($filterdatenarray,"cmp_datum");
	#sort($filterdatenarray);
	#print '<br>';
	print '<br><h3 class="auswahluntertitel">Alle Auftritte </h3><br>';

	print '<table class = "archivtable">';
	print '<tr height = 24px>';
	print '<th class = "text" width = "70px">Datum</td>';
	print '<th class = "text" width = "80px">Komponist</td>';
	print '<th class = "text" width = "180px">Werk</td>';
	print '<th class = "text" width = "300px">Teil</td>';
	print '<th class = "text" width = "100px">Auftritt</td>';
	print '<th class = "text" >Anmerkungen</td>';
	
	print '</tr>';
	$lastdatum=0;
	foreach($filterdatenarray as $datenzeile)
	{
		print '<tr height = 20px>';
		if ($datenzeile['datum'] == $lastdatum)
		{
			print '<td class = "archivtd"></td>';
			
		}
		else
		{
			print '<td class = "archivtd">'.$datenzeile['datum'].'</td>';
			$lastdatum = $datenzeile['datum'];
		}
		print '<td class = "archivtd">'.$datenzeile['komponist'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['werk'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['teil'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['art'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['anmerkung'].'</td>';
	
		print '</tr>';

	}# foreach
	print '</table><br>';

	
	
}

elseif (count($filterkomponist) || count($filterauftritt) || count($filterjahr) || count($filteranmerkung) ) # es gibt eine Suchanfrage
	{
	/*
	$result_komponistdaten = mysql_query("SELECT * FROM archiv WHERE 
	komponist IN ($filtersuchkomponist)
	AND jahr IN ($filtersuchjahr)
	", $db)or die(print '<p  >Beim Suchen nach komponistdaten ist ein Fehler passiert: '.mysql_error().'</p>');
	*/
	$result_komponistdaten = mysql_query("SELECT * FROM archiv WHERE 
	$wherestring
	", $db)or die(print '<p  >Beim Suchen nach komponistdaten ist ein Fehler passiert: '.mysql_error().'</p>');

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
		$zeilendic["datum"] = $komponistdaten['datum'];
		$zeilendic["anmerkung"] = $komponistdaten['anmerkung'];
		$filterdatenarray[] = $zeilendic;
	}
		#print 'komponistdatenarray: ';
		#print_r($filterkomponist);
		#print '*<br>';

	usort($filterdatenarray,"cmp_datum");
	#sort($filterdatenarray);
	#print '<br>';
	
	# Filterdaten aufzeigen	
	
	##
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
	#$wherestring
	if (strlen($filtersuchanmerkung))
	{
		print '<th style = "border:none; width: auto;padding-top: 4px;padding-bottom: 4px;" >';
		print '<h3 class="auswahluntertitel">Suchbegriff:<br>'.$anmerkungsuchbegriff.'</h3>';
		print '</th>';
	}
	

	print '</tr>';
	print '</table>';
	
	
	
	##
	/*
	if (strlen($filterkomponistliste))
	{
		print '<h3 class="auswahluntertitel">Komponisten: '.$filterkomponistliste.'</h3>';
	}
	if (strlen($filterauftrittliste))
	{
		print '<h3 class="auswahluntertitel"> Auftritte: '.$filterauftrittliste.'</h3>';
	}
	if (strlen($filterjahrliste))
	{
		print '<h3 class="auswahluntertitel"> Jahre: '.$filterjahrliste.'</h3>';
	}
	if (strlen($filteranmerkungenliste))
	{
		print '<h3 class="auswahluntertitel"> Anmerkungen: '.$filteranmerkungenliste.'</h3>';
	}
	#print '<br>';
	*/
	
	print '<table class = "archivtable">';
	print '<tr height = 24px>';
	print '<th class = "text" width = "70px">Datum</td>';
	print '<th class = "text" width = "80px">Komponist</td>';
	print '<th class = "text" width = "180px">Werk</td>';
	print '<th class = "text" width = "300px">Teil</td>';
	print '<th class = "text" width = "100px">Auftritt</td>';
	print '<th class = "text" >Anmerkungen</td>';
	
	print '</tr>';
	$lastdatum=0;
	foreach($filterdatenarray as $datenzeile)
	{
		print '<tr height = 20px>';
		if ($datenzeile['datum'] == $lastdatum)
		{
			print '<td class = "archivtd"></td>';
			
		}
		else
		{
			print '<td class = "archivtd">'.$datenzeile['datum'].'</td>';
			$lastdatum = $datenzeile['datum'];
		}
		print '<td class = "archivtd">'.$datenzeile['komponist'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['werk'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['teil'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['art'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['anmerkung'].'</td>';
	
		print '</tr>';

	}# foreach
	print '</table><br>';
}


print '</div>'; #archivtabelleabschnitt;




print '</div>'; # archivselectabschnitt

$result_jahrdaten = mysql_query("SELECT * FROM archiv WHERE jahr = '$suchjahr'", $db)or die(print '<p  >Beim Suchen nach komponistdaten ist ein Fehler passiert: '.mysql_error().'</p>');
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
	$zeilendic["datum"] = $jahrdatenzeile['datum'];
	$zeilendic["anmerkung"] = $jahrdatenzeile['anmerkung'];
	
	#print_r($komponistdaten);
	#print '<br>';
	#print 'name: '.$komponistdaten['komponist'].' Werk: '.$komponistdaten['werk'].' Teil: '.$komponistdaten['teil'].' Datum: '.$komponistdaten['tag'].'<br>';
	$jahrdatenarray[] = $zeilendic;
}
	#print_r($komponistdatenarray);
	#print '<br>';




print '</div>';	# adminContent
print '</div>';	# admin
?>
</body>
</html>
