<?
/* verbinden mit db */	
	$db=mysql_connect("localhost","ruediheimlicher","RivChuv4");
	/* nun ist der zugang zum db-server in der variable $db gespeichert */
	mysql_set_charset('utf8',$db);
	/* hier wird nun die eigentliche db ausgewählt */
	mysql_select_db("ruediheimlicher_kicho",$db); 
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
print '<h2 class="eventtitel ">Chor Archiv</h2>';

#POST abfragen
print_r($_POST);
#print_r($_GET);
print '<br>';
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
	print'filterkomponist: '. $_POST['filterkomponist'][0].'<br>';
}



print '<h2 class="suchtitel ">Archiv durchsuchen:</h2>';

$result_auftritt = mysql_query("SELECT * FROM auftritt ", $db)or die(print '<p  >Beim Suchen nach auftritt ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'SELECT auftritt error:';
	print mysql_error();
	print '<br>';
}

$auftrittarray = array();
while ($auftrittzeile = mysql_fetch_array($result_auftritt) )
{
$auftrittarray[] = $auftrittzeile['event'];
}
#print_r($auftrittarray);
#print '<br>';



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
		$tempjahr = explode('/',$zeile);
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


# 

print '<div class = "archivselectabschnitt">';
print '<h2 class="selectuntertitel ">Filter</h2>';
#print_r($filterkomponist);
#print'<br>';

print '<form method="POST" action="">';

	print '<table class = "archivtable">';
	print '<tr height = 24px>';
	print '<th class = "text" width = "300px">Komponist</td>';
	print '<th class = "text" width = "300px">Auftritt</td>';
	print '<th class = "text" width = "100px">Jahr</td>';
	
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
		
		}
		else
		{
		print '<input type="checkbox" name="filterkomponist[]" value="'.$komponistenarray[$i].'" >'.$komponistenarray[$i].'<br>';
		}
		}
		print '</div>'; #filterabschnitt
		print '</td>';
	
		print '<td class = "archivtd">';
		print '<div class = "filterabschnitt">';
		for ($i =1;$i < count($auftrittarray);$i++)
		{
			print '<input type="checkbox" name="filterauftritt[]" value="'.$auftrittarray[$i].' "> '.$auftrittarray[$i].'<br>';
		
		}
		
		print '</div>'; #filterabschnitt
		
		print '</td>';
		print '<td class = "archivtd">';
		print '<div class = "filterabschnitt">';
		sort($jahrarray,SORT_NUMERIC);
		for ($i =1;$i < count($jahrarray);$i++)
		{
			print '<input type="checkbox" name="filterjahr[]" value="'.$jahrarray[$i].'"> '.$jahrarray[$i].'<br>';
		
		}
		
		print '</div>'; #filterabschnitt
		
		print '</td>';
	
	
	
	print '</tr>';
	

	


	print '</table>';


print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" value="Filtern" >';

print '</form>';

print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" value="Leeren" >';
print '</div>'; #archivselectabschnitt;
print '<div class = "archivtabelleabschnitt">';

print '</div>'; #archivtabelleabschnitt;


print '<div class = "archivselectabschnitt">';
print '<h2 class="selectuntertitel ">nach Komponist</h2>';
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

if ($suchjahr)
{
	print '<input type = "hidden" name = "suchjahr" value = '.$suchjahr.'>';
}
if ($suchart)
{
	print '<input type = "hidden" name = "suchart" value = '.$suchart.'>';
}


print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" value="suchen" >';
print '</form>';

print '</div>'; #archivtabelleabschnitt;


$result_komponistdaten = mysql_query("SELECT * FROM archiv WHERE komponist = '$suchname'", $db)or die(print '<p  >Beim Suchen nach komponistdaten ist ein Fehler passiert: '.mysql_error().'</p>');
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
	$zeilendic["art"] = $komponistdaten['art'];

	$zeilendic["teil"] = $komponistdaten['teil'];
	$zeilendic["datum"] = $komponistdaten['datum'];
	$zeilendic["anmerkung"] = $komponistdaten['anmerkung'];
	
	#print_r($komponistdaten);
	#print '<br>';
	#print 'name: '.$komponistdaten['komponist'].' Werk: '.$komponistdaten['werk'].' Teil: '.$komponistdaten['teil'].' Datum: '.$komponistdaten['tag'].'<br>';
	$komponistdatenarray[] = $zeilendic;
}
	#print_r($komponistdatenarray);
	


if (strlen($suchname) && count($komponistdatenarray) ) # Daten vorhanden
{
	print '<div class = "archivtabelleabschnitt">';
	#
	usort($komponistdatenarray,"cmp_datum");
	#sort($komponistdatenarray);

	print '<br><h3 class="auswahluntertitel">Komponist: '.$zeilendic["komponist_vn"].' '.$suchname.'</h3><br>';
	
	print '<table class = "archivtable">';
	print '<tr height = 24px>';
	print '<th class = "text" width = "80px">Datum</td>';
	
	print '<th class = "text" width = "300px">Werk</td>';
	print '<th class = "text" width = "300px">Teil</td>';
	print '<th class = "text" width = "200px">Art</td>';
	
	print '</tr>';

	foreach($komponistdatenarray as $datenzeile)
	{
		print '<tr height = 20px>';
		print '<td class = "archivtd">'.$datenzeile['datum'].'</td>';
	
		print '<td class = "archivtd">'.$datenzeile['werk'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['teil'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['art'].'</td>';
	
		print '</tr>';

	}# foreach
	print '</table><br>';
}

print '</div>'; # archivtabelleabschnitt

print '<div class = "archivselectabschnitt">';
print '<h2 class="selectuntertitel ">nach Jahr</h2>';
print '<form method="POST" action="">';
print ' <select size="1" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" name="jahre[] ">';
	
foreach($jahrarray as $j)
{
	print 'j:'.$j.'<br>';
	if ($j == $suchjahr)
	{
		print '<option value="'.$j.'" selected>'.$j.'</option>';
	}
	else
	{
		print '<option  value="'.$j.'">'.$j.'</option>';
	}
}

print ' </select>';
if ($suchname)
{
	print '<input type = "hidden" name = "suchname" value = '.$suchname.'>';
}

if ($suchart)
{
	print '<input type = "hidden" name = "suchart" value = '.$suchart.'>';
}

print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" value="suchen" >';
print '</form>';

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


if ($suchjahr && count($jahrdatenarray) ) # Daten vorhanden
{

#if ($jahrdatenarray) # Daten vorhanden
	print '<div class = "archivtabelleabschnitt">';
	#
	#usort($jahrarray,"cmp_datum");
	#sort($komponistdatenarray);

	print '<br><h3 class="auswahluntertitel ">Jahr: '.$suchjahr.'</h3><br>';
	
	print '<table class = "archivtable">';
	print '<tr height = 24px>';
	print '<th class = "text" width = "100px">Datum</td>';
	print '<th class = "text" width = "300px">Komponist</td>';
	print '<th class = "text" width = "300px">Werk</td>';
	print '<th class = "text" width = "300px">Teil</td>';
	print '<th class = "text" width = "300px">Art</td>';
	
	print '</tr>';

	foreach($jahrdatenarray as $datenzeile)
	{
		print '<tr height = 24px>';
		print '<td class = "archivtd">'.$datenzeile['datum'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['komponist_vn'].' '.$datenzeile['komponist'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['werk'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['teil'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['art'].'</td>';
	
		print '</tr>';

	}# foreach
	print '</table><br>';
}

print '</div>';


print '<div class = "archivselectabschnitt">';
print '<h2 class="selectuntertitel ">nach Art</h2>';
print '<form method="POST" action="">';
print ' <select size="1" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" name="arten[] ">';

foreach($auftrittarray as $tempart)
{
	#print 'komponist:'.$name.'<br>';
	if ($tempart == $suchart)
	{
		print '<option value="'.$tempart.'" selected>'.$tempart.'</option>';
	}
	else
	{
		print '<option  value="'.$tempart.'">'.$tempart.'</option>';
	}
}
print ' </select>';

if ($suchjahr)
{
	print '<input type = "hidden" name = "suchjahr" value = '.$suchjahr.'>';
}

if ($suchname)
{
	print '<input type = "hidden" name = "suchname" value = '.$suchname.'>';
}



print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" value="suchen" >';
print '</form>';

print '</div>'; #archivtabelleabschnitt;


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
	#$zeilendic["art"] = $artdatenzeile['art'];

	$zeilendic["teil"] = $artdatenzeile['teil'];
	$zeilendic["datum"] = $artdatenzeile['datum'];
	$zeilendic["anmerkung"] = $artdatenzeile['anmerkung'];
	
	#print_r($komponistdaten);
	#print '<br>';
	#print 'name: '.$komponistdaten['komponist'].' Werk: '.$komponistdaten['werk'].' Teil: '.$komponistdaten['teil'].' Datum: '.$komponistdaten['tag'].'<br>';
	$artdatenarray[] = $zeilendic;
}
	#print_r($komponistdatenarray);
	#print '<br>';

#print 'suchart: '.$suchart.' artdatenarray: '.count($artdatenarray).'<br>';
if ($suchart && count($artdatenarray) ) # Daten vorhanden
{
	usort($artdatenarray,"cmp_datum");
	
	#print 'artjahr da. artdatenarray: '.count($artdatenarray).'<br>';
	print '<div class = "archivtabelleabschnitt">';
	#
	#usort($jahrarray,"cmp_datum");
	#sort($komponistdatenarray);

	print '<br><h3 class="auswahluntertitel ">Art: '.$suchart.'</h3><br>';
	
	print '<table class = "archivtable">';
	print '<tr height = 24px>';
	print '<th class = "text" width = "100px">Datum</td>';
	print '<th class = "text" width = "300px">Komponist</td>';
	print '<th class = "text" width = "300px">Werk</td>';
	print '<th class = "text" width = "300px">Teil</td>';
	#print '<th class = "text" width = "300px">Art</td>';
	
	print '</tr>';

	foreach($artdatenarray as $datenzeile)
	{
		print '<tr height = 24px>';
		print '<td class = "archivtd">'.$datenzeile['datum'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['komponist_vn'].' '.$datenzeile['komponist'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['werk'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['teil'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['art'].'</td>';
	
		print '</tr>';

	}# foreach
	print '</table><br>';
}

print '</div>';

print '</div>';	# adminContent
print '</div>';	# admin
?>
</body>
</html>
