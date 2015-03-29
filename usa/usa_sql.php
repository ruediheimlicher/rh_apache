<?
/* verbinden mit db */	
#	$db=mysqli_connect('localhost','ruedihei','iZs1B1c7y4');
	$db=mysqli_connect('localhost','ruedihei_db','rueti8630','ruedihei_usa');
	if (mysqli_connect_errno()) 
	{
		print 'Verbindung zu db misslungen: ';
    	print 'error: '.mysqli_connect_error().'<br>';
    }
    else
    {
    	print 'Verbindung zu db gelungen<br>';
    }
    
    if ($result = mysqli_query($db, "SELECT DATABASE()")) 
    {
    	$row = mysqli_fetch_row($result);
    	print 'Default database ist '.$row[0].'<br>';
    	#print_r($row);
    	mysqli_free_result($result);
	}



	mysqli_set_charset('utf8',$db);
	
	/*
	mysqli_select_db("ruedihei_usa", $db); 
	
	if (mysqli_error($ok)) 
	{
		print 'Verbindung zu usa misslungen: ';
    	print 'error: '.mysqli_error().'<br>';
    }
    else
    {
    	print 'Verbindung zu usa gelungen<br>';
    }
*/

#echo mysqli_error($db);


#$db = include "usa_bank.php";

#print 'nach include db<br>';
if (isset($_POST['uploadok']))
{
	$upda=1;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"content="text/html;charset=UTF-8" />

<link href="usa.css"rel="stylesheet"type="text/css" />
</head>
<body class="basic">
<?
print '<div id="admin">';
print '<div id="adminContent">';
print '<h2 class="eventtitel ">USA Campground</h2>';

#POST abfragen
#print'POST<br>';
#print_r($_POST);
#print_r($_GET);
#print '<br>';

/*
$suchcode=-1;
if (strlen($_POST['codes'][0]))
{
	$suchcode = $_POST['codes'][0];
	#print'suchcode: '. $_POST['codes'][0].'<br>';
}
elseif (isset($_POST['suchcode']))
{
	$suchcode = $_POST['suchcode'];
}
#print'suchcode: '. $_POST['codes'][0].'<br>';
*/
$suchregion=0;
if (strlen($_POST['regions'][0]))
{
	$suchregion = $_POST['regions'][0];
	#print'suchregion: '. $_POST['regions'][0].'<br>';
}
elseif (isset($_POST['suchregion']))
{
	$suchregion = $_POST['suchregion'];
}
#print'suchregion: '. $_POST['regions'][0].'<br>';

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

$campgroundid=-1;
if (isset($_POST['regionsuchen']) && isset($_POST['campgroundid']))
{
	$suchregion = $_POST['suchregion'];
	$campgroundid = $_POST['campgroundid'];
	#print 'campgroundid: '.$campgroundid.'<br>';
}

#print '<h2 class="audiotitel ">suchen</h2>';

$result_campground = mysqli_query($db, "SELECT * FROM campground ");#or die(print '<p  >Beim Suchen nach campground 1 ist ein Fehler passiert: *'.mysqli_error($db).'*</p>');
if (mysqli_error($result_campground))
{
	print 'result_campground select * error:';
	print mysqli_error($result_campground);
	print '<br>';
}
else
{
	print 'result_campground ist OK<br>';
	$zeilen = mysqli_num_rows($db, $result_campground);
	print ' anz. Zeilen: *'.$zeilen.'*<br>';
	print ' anz. Felder: *'.mysqli_num_fields($result_campground).'*<br>';
}

$task = "show";
if (isset($_POST['task']) )
{
	$task = $_POST['task'];
	#print_r($_POST);
	#print '<br>';

}
#print 'task: '.$task.'<br>';
# regiondaten suchen
print '<form method="POST" action="usa_routen.php">';
print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:24pt; margin-left:10px" value="> Routen" >';
print '</form>';

/*
$result_campground = mysqli_query("SELECT * FROM campground ", $db)or die(print '<p  >Beim Suchen nach campground 2 ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'select * error:';
	print mysqli_error();
	print '<br>';
}
*/


$regionarray = array();
$cgdatenarray = array();
# array der ip gleich wie $ip
mysqli_data_seek($result_campground, 0);
if (mysql_error())
{
	print 'seek * error:';
	print mysqli_error();
	print '<br>';
}
else
{
	print 'seek OK';
}

while ($campground = mysqli_fetch_array($result_campground) )
{

	$cgdatenarray[] = $campground;
	#print '* ';
	#print_r($campground);
	#print '*<br>';
	$cgzeile = $campground['region'];
	trim($zeile);
	
	#print 'zeile:'.$campground['region'].'<br>';
	if (strlen($cgzeile)&&!(in_array(trim($cgzeile),$regionarray)) )
	{
		$regionarray[]=trim($cgzeile);
		#print '*** region:*'.$campground['region'].'* l: '.strlen($cgzeile).'<br>';
	}
	
}

asort($regionarray,SORT_STRING);
print '<form method="POST" action="">';
print ' <select size="1" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" name="regions[] ">';

foreach($regionarray as $region)
{
	#print 'komponist:'.$name.'<br>';
	if ($region == $suchregion)
	{
		print '<option value="'.$region.'" selected>'.$region.'</option>';
	}
	else
	{
		print '<option  value="'.$region.'">'.$region.'</option>';
	}
}
print '<option  value="alle">Alle</option>';
print ' </select>';
print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:24pt; margin-left:10px" value="region suchen" >';
print '</form>';

# regiondaten zu suchregion lesen

if ($suchregion == "alle")
{
$result_regiondaten = mysqli_query($db,"SELECT * FROM campground ") ;#or die(print '<p  >Beim Suchen nach alle regiondaten ist ein Fehler passiert: '.mysql_error().'</p>');

}
else

{
$result_regiondaten = mysqli_query($db,"SELECT * FROM campground WHERE region = '$suchregion'")or die(print '<p  >Beim Suchen nach regiondaten ist ein Fehler passiert: '.mysql_error().'</p>');
}
if (mysqli_error())
{
	print 'SELECT regiondaten error:';
	print mysqli_error();
	print '<br>';
}



$regiondatenarray = array();
while ($regiondaten = mysqli_fetch_array($result_regiondaten) )
{
	$regionzeilendic["name"] = $regiondaten['name'];
	$regionzeilendic["state"] = $regiondaten['state'];
	$regionzeilendic["region"] = $regiondaten['region'];

	$regionzeilendic["mail"] = $regiondaten['mail'];
	$regionzeilendic["url"] = $regiondaten['url'];
	$regionzeilendic["tel"] = $regiondaten['tel'];
	$regionzeilendic["id"] = $regiondaten['id'];
	$regionzeilendic["tel_free"] = $regiondaten['tel_free'];
	$regionzeilendic["adresse"] = $regiondaten['adresse'];
	$regionzeilendic["rate"] = $regiondaten['rate'];
	$regionzeilendic["ort"] = $regiondaten['ort'];
	$regionzeilendic["code"] = $regiondaten['code'];
		

	#print_r($campgrounddaten);
	#print '<br>';
	#print 'name: '.$regiondaten['name'].' Werk: '.$regiondaten['werk'].' Teil: '.$regiondaten['teil'].' Datum: '.$regiondaten['tag'].'<br>';
	$regiondatenarray[] = $regionzeilendic;
}


if ($regiondatenarray) # Daten vorhanden
{
	print '<div class = "regionabschnitt">';

	print_r($campgrounddatenarray);
	print '<br>';

	


		print '<table class = "drucktabelle">';
			print '<tr height = 24px>';
			print '<th class = "text" width = "30px">state</td>';
	
			#print '<th class = "text" width = "100px">region</td>';
			print '<th class = "text" width = "350px">name</td>';
			print '<th class = "text" width = "150px">ort</td>';
			print '<th class = "text" width = "30px">nr</td>';
			print '<th class = "drucktabellecenter" width = "60px">';
			print '<form action=""method="POST">';
			print ' <input type="hidden"name="task"value="new"/>';
			print ' <input type="submit"name="neu"value="new"/>';
			print '</form>';
			print '</td>';
			
	
			print '</tr>';

			foreach($regiondatenarray as $regiondatenzeile)
			{
				print '<tr class = "drucktabelle">';
				print '<td class = "drucktabelle">'.$regiondatenzeile['state'].'</td>';
	
				#print '<td class = "drucktabelle">'.$regiondatenzeile['region'].'</td>';
				print '<td class = "drucktabelle">'.$regiondatenzeile['name'].'</td>';
				print '<td class = "drucktabelle">'.$regiondatenzeile['ort'].'</td>';
				print '<td class = "drucktabellecenter">'.$regiondatenzeile['code'].'</td>';
				print '<td class = "drucktabellecenter" width = "50px"><form action = ""; name = "regionwahl" method = "post">';
				print '	<input type="hidden" name="regionsuchen" value ="1">';
				print '	<input type="hidden" name="suchregion" value ='.$suchregion.'>';
				print '	<input type="hidden" name="campgroundid" value ='.$regiondatenzeile['id'].'>';
				print '<input type="submit" class="links40" name="regionwahl" value=">>"></form></td>';

				print '</tr>';

			}# foreach
		print '</table><br>';


		print '</div>'; # regionabschnitt

}# if campgrounddatenarray

/*
# regiondaten zu suchregion lesen
$result_regiondaten = mysql_query($db,"SELECT * FROM campground WHERE region = '$suchregion'")or die(print '<p  >Beim Suchen nach regiondaten ist ein Fehler passiert: '.mysql_error().'</p>');

if (mysql_error())
{
	print 'SELECT regiondaten error:';
	print mysql_error();
	print '<br>';
}



$regiondatenarray = array();
while ($regiondaten = mysql_fetch_array($result_regiondaten) )
{
	$regionzeilendic["name"] = $regiondaten['name'];
	$regionzeilendic["state"] = $regiondaten['state'];
	$regionzeilendic["region"] = $regiondaten['region'];

	$regionzeilendic["mail"] = $regiondaten['mail'];
	$regionzeilendic["url"] = $regiondaten['url'];
	$regionzeilendic["tel"] = $regiondaten['tel'];
	$regionzeilendic["id"] = $regiondaten['id'];
	$regionzeilendic["tel_free"] = $regiondaten['tel_free'];
	$regionzeilendic["adresse"] = $regiondaten['adresse'];
	$regionzeilendic["rate"] = $regiondaten['rate'];
	$regionzeilendic["ort"] = $regiondaten['ort'];
	$regionzeilendic["code"] = $regiondaten['code'];
	
	

	#print_r($campgrounddaten);
	#print '<br>';
	#print 'name: '.$regiondaten['name'].' Werk: '.$regiondaten['werk'].' Teil: '.$regiondaten['teil'].' Datum: '.$regiondaten['tag'].'<br>';
	$regiondatenarray[] = $regionzeilendic;
}


if ($regiondatenarray) # Daten vorhanden
{
	print '<div class = "regionabschnitt">';

	print_r($campgrounddatenarray);
	print '<br>';

	


		print '<table class = "drucktabelle">';
			print '<tr height = 24px>';
			print '<th class = "text" width = "30px">state</td>';
	
			#print '<th class = "text" width = "100px">region</td>';
			print '<th class = "text" width = "350px">name</td>';
			print '<th class = "text" width = "150px">ort</td>';
			print '<th class = "text" width = "30px">nr</td>';
			print '<th class = "drucktabellecenter" width = "60px">';
			print '<form action=""method="POST">';
			print ' <input type="hidden"name="task"value="new"/>';
			print ' <input type="submit"name="neu"value="new"/>';
			print '</form>';
			print '</td>';
			
	
			print '</tr>';

			foreach($regiondatenarray as $regiondatenzeile)
			{
				print '<tr class = "drucktabelle">';
				print '<td class = "drucktabelle">'.$regiondatenzeile['state'].'</td>';
	
				#print '<td class = "drucktabelle">'.$regiondatenzeile['region'].'</td>';
				print '<td class = "drucktabelle">'.$regiondatenzeile['name'].'</td>';
				print '<td class = "drucktabelle">'.$regiondatenzeile['ort'].'</td>';
				print '<td class = "drucktabellecenter">'.$regiondatenzeile['code'].'</td>';
				print '<td class = "drucktabellecenter" width = "50px"><form action = ""; name = "regionwahl" method = "post">';
				print '	<input type="hidden" name="regionsuchen" value ="1">';
				print '	<input type="hidden" name="suchregion" value ='.$suchregion.'>';
				print '	<input type="hidden" name="campgroundid" value ='.$regiondatenzeile['id'].'>';
				print '<input type="submit" class="links40" name="regionwahl" value=">>"></form></td>';

				print '</tr>';

			}# foreach
		print '</table><br>';


		print '</div>'; # regionabschnitt

}# if campgrounddatenarray


*/

if ($task == "save")
{
	print 'save start neue Daten<br>';
	#print_r($_POST);
	print 'save neue Daten<br>';
	$neuerstate = $_POST['state'];
	$neueregion = $_POST['region'];
	$neuername = $_POST['name'];
	$neuemail = $_POST['mail'];
	$neueurl = $_POST['url'];
	$neuetel = $_POST['tel'];
	$neuetel_free = $_POST['tel_free'];
	$neuerort = $_POST['ort'];
	$neuerate = $_POST['rate'];
	$neueadresse = $_POST['adresse'];
	$neuegmap = $_POST['gmap'];
	$neueanmerkung = $_POST['anmerkung'];
	#print 'save INSERT neue Daten<br>';
	$result_insert = mysql_query($db,"INSERT  INTO campground ( state,  region, name, mail, url, tel, tel_free, ort, rate, adresse, gmap, anmerkung) VALUES ( '$neuerstate', '$neueregion', '$neuername', '$neuemail','$neueurl', '$neuetel', '$neuetel_free', '$neuerort', '$neuerate', '$neueadresse', '$neuegmap', '$neueanmerkung') "); 
					
	print 'Fehler beim Upload der Daten: *'.mysql_error().'*<br>';
	#$resultat=mysql_affected_rows($db);
					#print 'UPDATE error: *'.mysql_error().'*<br>';
	$neuerstate = 0;
	$neueregion = 0;
	$neuername = 0;
	$neuemail = 0;
	$neueurl = 0;
	$neuetel = 0;
	$neuetel_free = 0;
	$neuerort = 0;
	$neuerate = 0;
	$neueadresse = 0;
	$neuegmap = 0;
	$neueanmerkung = 0;

	$task = "new";
}

if ($task == "show")
{
# campgrounddaten suchen

$campgroundarray = array();
$datenarray = array();
# array der ip gleich wie $ip
while ($campground = mysql_fetch_array($result_campground) )
{
	$datenarray[] = $campground;
	#print_r($campground);
	#print '<br>';
	$zeile = $campground['name'];
	trim($zeile);
	
	#print 'zeile:'.$campground['name'].'<br>';
	if (strlen($zeile)&&!(in_array(trim($zeile),$campgroundarray)) )
	{
		$campgroundarray[]=trim($zeile);
		#print '*** name:*'.$campground['name'].'* l: '.strlen($zeile).'<br>';
	}
	
}




asort($campgroundarray,SORT_STRING);

/*
	print '<form method="POST" action="">';
	print ' <select size="1" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" name="names[] ">';
	
	foreach($campgroundarray as $name)
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
*/

$result_campgrounddaten = mysqli_query($db,"SELECT * FROM campground WHERE id = '$campgroundid'");#or die(print '<p  >Beim Suchen nach campgrounddaten ist ein Fehler passiert: '.mysql_error().'</p>');

if (mysql_error())
{
	print 'SELECT suchname error:';
	print mysql_error();
	print '<br>';
}

$result_mapdaten = mysqli_query($db, "SELECT gmap FROM campground WHERE id = '$campgroundid'");#or die(print '<p  >Beim Suchen nach mapdaten ist ein Fehler passiert: '.mysql_error().'</p>');

if (mysql_error())
{
	print 'SELECT map error:';
	print mysql_error();
	print '<br>';
}



$campgrounddatenarray = array();
while ($campgrounddaten = mysql_fetch_array($result_campgrounddaten) )
{
	$zeilendic["name"] = $campgrounddaten['name'];
	$zeilendic["state"] = $campgrounddaten['state'];
	$zeilendic["region"] = $campgrounddaten['region'];
	$zeilendic["code"] = $campgrounddaten['code'];

	$zeilendic["mail"] = $campgrounddaten['mail'];
	$zeilendic["url"] = $campgrounddaten['url'];
	$zeilendic["tel"] = $campgrounddaten['tel'];
	$zeilendic["id"] = $campgrounddaten['id'];
	$zeilendic["tel_free"] = $campgrounddaten['tel_free'];
	$zeilendic["adresse"] = $campgrounddaten['adresse'];
	$zeilendic["rate"] = $campgrounddaten['rate'];
	$zeilendic["ort"] = $campgrounddaten['ort'];
	if (strlen($campgrounddaten['anmerkung']))
	{
		$zeilendic["anmerkung"] = $campgrounddaten['anmerkung'];
	}
	else
	{
		$zeilendic["anmerkung"] = '*';
	}
	
	$zeilendic["map"] = $campgrounddaten['map'];
	$zeilendic["gmap"] = $campgrounddaten['gmap'];
	

	#print_r($zeilendic["map"]);
	#print '<br>';
	#print 'name: '.$campgrounddaten['name'].' Werk: '.$campgrounddaten['werk'].' Teil: '.$campgrounddaten['teil'].' Datum: '.$campgrounddaten['tag'].'<br>';
	$campgrounddatenarray[] = $zeilendic;
}
	#print_r($campgrounddatenarray);
	#print '<br>';



if ($campgrounddatenarray) # Daten vorhanden
{
	function cmp_name($a, $b)
	{
		if ($a['name'] == $b['name']) 
		{
			return 0;
		}
		return ($a['name'] < $b['name']) ? -1 : 1;
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
	#usort($campgrounddatenarray,"cmp_datum");
	#sort($campgrounddatenarray);


	print '<div class = "tabelleabschnitt">';
		print '<h3 class="erklaerung">Campground: '.$zeilendic["name"].'</h3>';
		
		$campgroundlegende = array("ort","adresse","tel","tel_free","url","mail","rate","anmerkung","gmap","code");
		print '<table class = "drucktabelle">';
			$cgindex=0;
			
			foreach($campgroundlegende as $legende)
			{
				#print_r($legende);
				#print '<br>';
				print '<tr class = "drucktabelle">';
				if ($campgroundlegende[$cgindex])
				{
				print '<td class = "drucktabelle" width = "400px">'.$legende.'</td>';
				if ($legende == "url")
				{
				print '<td class = "drucktabelle" width = "700px"><a href = "'.$zeilendic[$legende].'"target="_blank">homepage</a></td>';
				#print '<td class = "drucktabelle" width = "350px">'.$zeilendic[$legende].'</td>';
				}
				elseif ($legende == "gmap")
				{
				print '<td class = "drucktabelle" width = "700px"><a href = "'.$zeilendic[$legende].'"target="_blank">Karte</a></td>';
				#print '<td class = "drucktabelle" width = "350px">'.$zeilendic[$legende].'</td>';
				}

				else
				{
					print '<td class = "drucktabelle" width = "700px">'.$zeilendic[$legende].'</td>';
				}
				}
				print '</tr>';
				$cgindex++;
			}# foreach
			
		print '</table><br>';
		
		
		print '<br>';


		print '</div>'; # tabelleabschnitt
}# if campgrounddatenarray
#print_r($zeilendic[$map]);





#datentabelle
}


if ($task == "new")
{
print '<div class = "editabschnitt">';
	# neuer Datensatz
	print '<h2 class="untertitel">neuer Datensatz</h2>';
	print '<form action="" method="post">';
	
	print '<table class = "eingabetabelle">';
	print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">State:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="state"></td>';
	print '</tr>';
		print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">region:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="region"></td>';
	print '</tr>';
	print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">name:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="name"></td>';
	print '</tr>';
	print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">mail:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="mail"></td>';
	print '</tr>';
	print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">url:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="url"></td>';
	print '</tr>';

	print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">tel:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="tel"></td>';
	print '</tr>';
	print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">tel free:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="tel_free"></td>';
	print '</tr>';
	print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">ort:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="ort"></td>';
	print '</tr>';
		print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">rate:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="rate"></td>';
	print '</tr>';

	print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">adresse:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="adresse"></td>';	
	print '</tr>';

	print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">Anmerkung:</td>';
	print '<td class = "drucktabelle" ><textarea rows="2" cols="45" name="anmerkung"></textarea></p></td>';
	print '</tr>';

	print '<tr height = 24px>';
	print '<td class = "drucktabelle" width = "100px">gmap:</td>';
	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="gmap"></td>';
	print '</tr>';

	print '</table>';

	print ' <input type="hidden"name="task"value="save"/>';
	print ' <input type="submit"name="savedata" value="Daten sichern"/>';
	print '</form>';


print '<form action=""method="POST">';
print ' <input type="hidden"name="task"value="show"/>';
print ' <input type="submit"name="back"value="zurück"/>';
print '</form>';



print '<br>';

print '</div>'; # editabschnitt
}

/*
print '<div class = "archivtababschnitt">';

print '<form action=""method="POST">';
print ' <input type="hidden"name="task"value="new"/>';
print ' <input type="submit"name="back"value="neue Daten*"/>';
print '</form>';


# Tabelle laden


print '</div>'; # archivtababschnitt
*/

# change end






print '</div>';	# adminContent
print '</div>';	# admin
?>
    </body>
</html>
