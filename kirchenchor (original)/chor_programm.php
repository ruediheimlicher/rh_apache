<?
/* verbinden mit db */	
	$db=mysql_connect('localhost','ruedihei_db','rueti8630');

	mysql_set_charset('utf8',$db);
	mysql_select_db("ruedihei_kicho", $db); 
	
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

function cmp_jahr_monat($a, $b)
{
    if ($a['jahr'] == $b['jahr']) 
    {
    	if ($a['monat'] == $b['monat'])
    	{
        return 0;
        }
        else
        {
        	return ($a['monat'] < $b['monat']) ? -1 : 1;
        }
    }
    
    
    return ($a['jahr'] < $b['jahr']) ? -1 : 1;
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








#POST abfragen
#print_r($_POST);
#print_r($_GET);
#print '<br>';



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




if (($_POST['auftrittleeren']=="clear"))
{
	$filterauftritt = array();
	#print'filterkomponist: '. $_POST['filterkomponist'][0].'<br>';
}


if (strlen($_POST['programmtag']))
{
	$programmtag = $_POST['programmtag'];
}


if (strlen($_POST['programmmonat']))
{
	$programmmonat = $_POST['programmmonat'];
}

if (strlen($_POST['programmjahr']))
{
	$programmjahr = $_POST['programmjahr'];
}
#print'programmjahr: '. $_POST['programmjahr'].'<br>';

if (strlen($_POST['alle']))
{
	$alle = $_POST['alle'];
}
#print'alle: '. $_POST['alle'].'* '.$alle.'<br>';
#print 'Datum: '.$programmtag.'.'.$programmmonat.'.'.$programmjahr.'<br>';
$konzertdatum =  $programmtag.'.'.$programmmonat.'.'.$programmjahr;
#print $konzertdatum;





print '<div id="admin">';
print '<div id="adminContent">';

print '<table style = "margin-left: 10px; border:0px solid blue; width:780px; ">';
print '<tr style = "height:40px; ">';
print '<td style = "border:none; width: 480px;  padding-right: 10px; vertical-align:top;" >';
print '<h2 class="archivtitel ">Chor Programmarchiv</h2>';
print '</td>';
print '<form action = "chor_programm.php" method = "POST">';
print '<td style = "border:none; width: 200px;  padding-right: 0px; vertical-align:middle;padding-top:18px;" >';


if ($alle)
{
	print '<input type="submit" name = "abc" style="font-size:12pt; color:#0000C0;  margin-left:6px vertical-align:middle;" value="zurück zu '.$programmjahr.'" >';
	print '<input type = "hidden" name = "programmjahr" value = "'.$programmjahr.'">';
	print '<input type = "hidden" name = "programmtag" value = "'.$programmmonat.'">';
	print '<input type = "hidden" name = "programmmonat" value = "'.$programmtag.'">';

	print '<input type = "hidden" name = "alle" value = "0">';
}
else
{
	print '<input type="submit" name = "alleprogramme" style="font-size:12pt; color:#0000C0;  margin-left:0px vertical-align:middle;" value="alle Programme" >';
	print '<input type = "hidden" name = "programmjahr" value = "'.$programmjahr.'">';
	print '<input type = "hidden" name = "programmtag" value = "'.$programmmonat.'">';
	print '<input type = "hidden" name = "programmmonat" value = "'.$programmtag.'">';
	
	
	print '<input type = "hidden" name = "alle" value = "1">';
}


print '</td>';
print '</form>';
print '<form action = "chor_archiv.php" method = "POST" onsubmit = "javascript:window.close();">';
#print '<form action = "chor_archiv_filter.php" method = "POST" onsubmit = "javascript:window.close();">';
print '<td style = "border:none; width: 200px;  padding-right: 20px; vertical-align:middle;padding-top:18px;" >';
print '<input type="submit" name = "backtoarchiv" style = "font-size:12pt;  color:#0000C0;  margin-left:0px vertical-align:middle;" value="zurück zum Archiv" >';
#print '<a href="javascript:window.close();"> back </a>';
print '<input type = "hidden" name = "programmjahr" value = "back">';

print '</td>';

print '</form>';
print '</tr>';
print '</table>';

$regex ='/^\.{1,2}/'; # Punkte am Anfang entfernen

$programmorderpfad = '../Data/kirchenchor_data/konzert';
$programmorderhandle = opendir($programmorderpfad);

$bildpfadarray = array();
while($programmorderhandle && (false !== ($bildfileraw = readdir($programmorderhandle))) )
{
	$tempbildarray = array();
	$bildfile =  preg_replace($regex,'',$bildfileraw);
	if (strlen($bildfile))
	{
		#print $bildfile.'<br>';
		$pfadelemente = explode('_',$bildfile);
		if ($alle)
		{
			$tempbildarray['jahr'] = $pfadelemente[0];
			if (count($pfadelemente)>2)
			{
				$tempbildarray['monat'] = $pfadelemente[1];
				$tempbildarray['art'] = $pfadelemente[2];
				$tempbildarray['pfad'] = $bildfile;
				#print_r($tempbildarray);
				#print '<br>';
				$bildpfadarray[] = $tempbildarray;
			}

		}
		else
		{
			if ($programmjahr == $pfadelemente[0] && $programmmonat == $pfadelemente[1])
			{
				$tempbildarray['jahr'] = $pfadelemente[0];
				if (count($pfadelemente)>2)
				{
					$tempbildarray['monat'] = $pfadelemente[1];
					$tempbildarray['art'] = $pfadelemente[2];
					$tempbildarray['pfad'] = $bildfile;
					#print_r($tempbildarray);
					#print '<br>';
					$bildpfadarray[] = $tempbildarray;
				}
		
			}
		}
	}
	
}

closedir($programmorderhandle);

uasort($bildpfadarray,"cmp_jahr_monat");
#print_r($bildpfadarray);
#print 'count bildpfadarray: '.count($bildpfadarray).'<br>';


#print '<h2 class="suchtitel ">**</h2>';


#print '<div style = "position: absolute;left: 30px; top: 60px; width : auto;height: auto;border:0px solid blue; padding:10px;">';

#if ($programmjahr == "alle")


/*
if ($alle)
{
print '<div style = "position: absolute;left: 30px; top: 260px; width : auto;height: 600px;border:0px solid blue; padding:0px;  overflow:scroll;">';

	#print '<h2 class="selectuntertitel ">Alle</h2>';
	print '<table style = " padding-top: 10px;padding-bottom: 10px; left: 40px;border:0px solid blue; background:#D8D8D8;">';

	for ($jahrindex = 1995;$jahrindex < 2020;$jahrindex++)
	{
		$tempprogrammtitelpfad = '../Data/kirchenchor_data/konzert/'.$jahrindex.'_ProgrammTitel.jpg';
		#print 'jahr: '.$jahrindex.' tempprogrammtitelpfad: '.$tempprogrammtitelpfad.'<br>';
		#$tempimg = fopen($tempprogrammtitelpfad,r);
		if(is_file($tempprogrammtitelpfad))
		{
		 #print $tempprogrammtitelpfad.' ist da</h2><br>';
		 	print '<tr style = "height:400px; ">';
			print '<td style = "border:none; width: 180px;  padding-right: 20px; vertical-align:top;" >';
			print '<h2 class="selectuntertitel ">'.$jahrindex.'</h2>';
			print '</td>';
			print '<td style = " background: #C8C8C8; width: 400px;padding: 8px; height:auto;" >';

			print '<img src='.$tempprogrammtitelpfad.' width="400"  alt="Programm">';

			print '</td>';
			print '<td style = "border:none; width: 180px;padding: 8px; vertical-align:top;" >';
		
		
			print '<h3 class="programmlink ">Originale</h3>';
		
			$tempprogrammpfad = '../Data/kirchenchor_data/konzert/'.$jahrindex.'_Programm.pdf';
			if(is_file($tempprogrammpfad))
			{
				print ' <a href = '.$tempprogrammpfad.' class = "programm" target="_blank">Programm</a>';
				print '<br>';
			}
		
			$tempplakatpfad = '../Data/kirchenchor_data/konzert/'.$jahrindex.'_Plakat.pdf';
		
			if(is_file($tempplakatpfad))		
			{
				print ' <a href = '.$tempplakatpfad.' class = "programm" target="_blank">Plakat</a>';
			}		
			print '</td>';
			print '</tr>';

		 
		}
	


	}
	print '</table>';
}
else
*/
{
	print '<div style = "position: absolute;left: 30px; top: 60px; width : auto;height: auto;border:0px solid blue; padding:10px;">';

	print '<table style = " top: 4px; left: 40px;padding-top: 20px;padding-bottom: 30px; border:0px solid blue; background:#D8D8D8;">';
	
	# eventuell mehrere Konzerte in verschiedenen Monaten
	foreach($bildpfadarray as $zeilenarray)
	{
		if (strpos($zeilenarray['art'],'Titel') !== false)
		{
			print '<tr style = "height:400px; ">';
			print '<td style = "border:none; width: 180px;  padding-right: 20px; vertical-align:top;" >';
			print '<h2 class="selectuntertitel ">'.$zeilenarray['jahr'].'</h2>';
			print '</td>';
			$tempmonat = $zeilenarray['monat'];
			$tempjahr = $zeilenarray['jahr'];
			print '<td style = " background: #C8C8C8; width: 400px;padding: 8px; height:auto;" >';
	
			$programmtitelpfad = '../Data/kirchenchor_data/konzert/'.$tempjahr.'_'.$tempmonat.'_ProgrammTitel.jpg';

			print '<img src='.$programmtitelpfad.' width="400"  alt="Programm">';
	
			print '</td>';
			print '<td style = "border:none; width: 180px;padding: 8px; vertical-align:top;" >';
			print '<h3 class="programmlink ">Originale</h3>';
	
			$programmpfad = '../Data/kirchenchor_data/konzert/'.$tempjahr.'_'.$tempmonat.'_Programm.pdf';
			#print $programmpfad;
			if(is_file($programmpfad))
			{
				print ' <a href = '.$programmpfad.' class = "programm" target="_blank">Programm</a>';
				print '<br>';
			}
	
			$plakatpfad = '../Data/kirchenchor_data/konzert/'.$tempjahr.'_'.$tempmonat.'_Plakat.pdf';
			if(is_file($plakatpfad))
			{
			print ' <a href = '.$plakatpfad.' class = "programm" target="_blank">Plakat</a>';
			}
			print '</td>';
			print '</tr>';
		}
	}
	print '</table>';


}
#print '</div>';


#print $programmpfad;




#print '<div class = "archivtabelleabschnitt">';



if ($programmmonat && $programmjahr) # es gibt eine Suchanfrage
	{
	
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
	/*
	print '<table class = "archivtable">';
	print '<tr height = 24px>';
	print '<th class = "text" width = "70px">Datum</td>';
	print '<th class = "text" width = "80px">Komponist</td>';
	print '<th class = "text" width = "180px">Werk</td>';
	print '<th class = "text" width = "300px">Teil</td>';
	print '<th class = "text" width = "120px">Auftritt</td>';
	print '<th class = "text" >Anmerkungen</td>';
	
	print '</tr>';
	$lastdatum = 0;
	$lastart = "";
	$neuerauftritt = 1;
	foreach($filterdatenarray as $datenzeile)
	{
		
		if ($datenzeile['datum'] == $lastdatum)
		{
			print '<tr height = "20px" >';
			print '<td class = "archivtd"></td>';
			$neuerauftritt = 0;
		}
		else
		{
			print '<tr height = "20px" bgcolor="#ddf" >';
			print '<td class = "archivtd" >'.$datenzeile['datum'].'</td>';
			$lastdatum = $datenzeile['datum'];
			$neuerauftritt = 1;
			
		}
		
		print '<td class = "archivtd">'.$datenzeile['komponist'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['werk'].'</td>';
		print '<td class = "archivtd">'.$datenzeile['teil'].'</td>';
		
		if ($neuerauftritt)
		{
			#print '<td class = "archivtd">+'.$lastart.'+</td>';
			if ($datenzeile['art'] == "Konzert")
			{
				print ' <td class = "archivtd">'.$datenzeile['art'];
				
				print '<input type="submit" name = "programm" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0;  margin-left:15px" value="Prog" > ';
				
				print '</td>';
			
			}
			else
			{
				print '<td class = "archivtd">'.$datenzeile['art'].'</td>';
				
			}
			
		}
		else
		{
			#print '<td class = "archivtd">*'.$datenzeile['art'].'*</td>';
			
			
			#$lastart = $datenzeile['art'] ;
			
			print '<td class = "archivtd"></td>';
		}
		
		print '<td class = "archivtd">'.$datenzeile['anmerkung'].'</td>';
	
		print '</tr>';

	}# foreach
	print '</table><br>';
	*/
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
