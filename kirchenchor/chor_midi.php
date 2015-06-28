<?php
session_start();
/*

r/Sites/rh_apache/public_html/kirchenchor/chor_midi.php on line 475

*/

include("pwd.php");

/* verbinden mit db */
	#$db=mysql_connect('localhost','root','Ideur0047');
	#$db = include "../bank.php";
	$db = include "chor_db.php";
	#mysql_set_charset('utf8',$db);
	#mysql_select_db("midi", $db); 

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chor MIDI</title>
<link href="chor.css" rel="stylesheet" type="text/css" />

<link type="text/css" href="../Audio/Player/jplayer//blue.monday/jplayer.blue.monday.css" rel="stylesheet" />

<!--
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
<script type="text/javascript" src="../Audio/Player/jplayer/jquery.jplayer.min.js"></script>
-->

<link type="text/css" href="../skin/pink.flag/scss/jplayer.pink.flag.css" rel="stylesheet" />

<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery.jplayer.min.js"></script>



<script type="text/javascript">
//var playerwerk = "http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3";

//var playerwerk = "../Data/kirchenchor/Lieder/Test/Requiem2_A.mp3";

// vom KG-Netz
var playerwerk = "http://www.refduernten.ch/www.zh.ref.ch/gemeinden/duernten/content/e14561/e12463/e15420/e15577/e1903/Requiem2_A.mp3";

// von home
//var playerwerk = "http://www.ruediheimlicher.ch/Data/kirchenchor/Lieder/Test/Requiem2_A.mp3";


//var playerwerk = "../Data/kirchenchor/Lieder/Test/Cum_sanctis_S.mp3";
//var playerwerk = "http://www.refduernten.ch/www.zh.ref.ch/gemeinden/duernten/content/e14561/e12463/e15420/e15577/e1839/Cum_sanctis_S.mp3";


var playerstimme = "Bass";
    
$(document).ready(function()
{
    $("#jquery_jplayer_1").jPlayer(
    {
    ready: function () 
    {
    	$(this).jPlayer("setMedia", 
          	{
            	//mp3: "../Data/kirchenchor/Konzert_2013/Missa/1_Kyrie/Kyrie_A_mix.mp3",
            	mp3: playerwerk,
            	oga: "../Data/kirchenchor/Konzert_2013/Missa/1_Kyrie/Kyrie_A_mix.ogg"
          	});
        },
        swfPath: "http://www.jplayer.org/latest/js/Jplayer.swf",
        solution: "html, flash",
        swfPath: "js",
        supplied: "mp3, oga",
        smoothPlayBar: true,
        wmode: "window",
        preload: 'metadata'
      });
    });
    
    onload = function()
    {
 		field = document.getElementById('number_field')
 		field.onkeydown = keyhit
		field.focus()
	}

<!--http://www.webdeveloper.com/forum/showthread.php?122389-keyPress-event-in-php -->
function keyhit(e)
{
 thisKey = e ? e.which : window.event.keyCode
 switch (thisKey) 
 {
  case 38: key = 'UP'
   break
  case 40: key = 'DOWN'
   break
   case 69: key = 'localhost'
   break;
  default: key = thisKey
 }
 
 if(key)
 {
  field = document.getElementById('number_field')
  if(isNaN(field.value))
  {
   field.value = 0
  }
  if(key == 'UP')
  {
   field.value++
  }
  else if(key == 'DOWN' && field.value > 0)
  {
   field.value--
  }
  else if (key ==  'localhost')
  {
  	field.value = 'localhost'
  }
  else
  {
  field.value = thisKey
  }
 }
}

</script>

<link href="chor.css" rel="stylesheet" type="text/css" />
<link href="jplayer.css" rel="stylesheet" type="text/css" />
</head>

<body class="liste">
<script type="text/javascript">
function submitform(registername)
{
	document.write("Register");
	document.write(registername );
}
</script>

<!--<div><h1 class="lernmedien">Chor MIDI</h1></div> -->

<?php
#phpinfo();
#date_default_timezone_set(Europe/Zurich);

$besucher = gethostbyaddr($_SERVER['REMOTE_ADDR']);
#echo "besucher: ", $besucher," <br>";
$localhost=0;
if ($besucher === "localhost")
{
	$localhost=1;
}
#$localhost=0;
#echo "besucher: ", $besucher, " localhost: ", $localhost," <br>";

$safaribrowser = strpos(getenv('HTTP_USER_AGENT'),"Safari");
#echo '<br>'.$browser.' pos: '.strpos("Safari",$browser).'<br>';
#if (strpos($browser, "Safari"))
if ($safaribrowser)
{
	#echo "<br>Browser ist Safari<br>";
}
else
{
	#echo "<br>Browser ist nicht Safari<br>";

}
$datum="";
#print '<p>benutzer;: '. $benutzer.' pw: '. $passwort.'*</p>';
#print'POST<br>';
#print_r($_POST);
#print'<br>';
#print'GET<br>';print_r($_GET);
#print'<br>';
$pass = 0;
if (isset($_POST['passwort']))
{
	$pass = $_POST['passwort'];
}
$user=0;
if (isset($_POST['user']))
{
	$user = $_POST['user'];
}


$session_id = session_id();

$result_home = mysql_query("SELECT * FROM settings WHERE id= 1", $db)or die(print '<p>Beim Suchen nach home ist ein Fehler passiert: '.mysql_error().'</p>');
#print mysql_error();
$set = mysql_fetch_array($result_home);
#print 'home_ip: '.$set['home_ip'].'<br>';
$home_ip = $set['home_ip'];
$lastsession = "";
$lastaktuellesession = "";
if (isset($set['lastsession']))
{
	$lastsession = $set['lastsession'];
}
if (isset($set['aktuellesession']))
{
	$lastaktuellesession = $set['aktuellesession'];
}


if (!($lastaktuellesession === $session_id)) # neue Session
{
	$neuesession = 1;
	# aktuellesession aktualisieren
	mysql_query("UPDATE settings SET aktuellesession = '$session_id' ,  lastsession = '$lastaktuellesession ' WHERE id= 1"); 

}
else
{
	$neuesession=0;
}

$remote_ip=$_SERVER['REMOTE_ADDR'];
$home_ip = $set['home_ip'];
$zeit = $_SERVER['REQUEST_TIME'];

#print_r($_SESSION);
#print 'home_ip: '.$set['home_ip'].' remote_ip: '.$remote_ip.'<br>';
#echo $_SERVER['HTTP_HOST'],"<br>"; 

if ($localhost)
{
#print_r(session_get_cookie_params() );
#print '<br>';
#print '<form action="'.session_destroy().'" ><h3 class = "admin" ><input type="submit" class="links40" value="kill session" name="textfile" style="width: 150px; margin-right:10px;"></h3></form>';

#print '<form action="'.session_destroy().'">';
	#print '<form action="" method="post"><p><input type="text" id="number_field" name="number_field" value="0"></p></form>';
}
$aktuellestunde = date("H",$zeit);
$aktuelleminute = date("i",$zeit);
$neuerminutenevent=0;
$neuerevent=0;
 if (isset($_SESSION['minute']))
 {
 if ($localhost)
 	{
 		print ' aktuelleminute: '.$aktuelleminute.' gesetzte session-minute: '.$_SESSION['minute'].' ';
 	}
 	 if ($_SESSION['minute'] == $aktuelleminute) # gleicher Anlauf
 	{
 	 	if ($localhost)
 		{
 		print 'gleicher Minuten-Anlauf<br>';
 		}

 		$neuerminutenevent=0;
 	}
 	else # weiterer Anlauf
 	{
 		if ($localhost)
 		{
 		print 'weiterer Minuten-Anlauf<br>';
 		}
 		$neuerminutenevent=1;
 		$_SESSION['minute'] = date("i",$zeit);
 	}
 	
 	if ($localhost)
 	{
 		print 'sessionminute: '.$_SESSION['minute'].'<br>';
 	}
 }
 else
 {
  	if ($localhost)
 	{
 		print 'neue sessionminute: '.date("i",$zeit).'<br>';
 	}
 	$_SESSION['minute'] = date("i",$zeit);
 	$neuerminutenevent=1;

 }


 if (isset($_SESSION['stunde']))
 {
  	if ($localhost)
 	{
 	 	print ' aktuellestunde: '.$aktuellestunde.' gesetzte session-stunde: '.$_SESSION['stunde'].' ';
		
 	}

 	if ($_SESSION['stunde'] == $aktuellestunde) # gleicher Anlauf
 	{
 	 	if ($localhost)
 		{
 		print 'gleicher Anlauf<br>';
 		}

 		$neuerevent=0;
 	}
 	else # weiterer Anlauf
 	{
 		if ($localhost)
 		{
 		print 'weiterer Anlauf<br>';
 		}
 		$neuerevent=1;
 		$_SESSION['stunde'] = date("H",$zeit);
 	}
 	//print 'sessionstunde: '.$_SESSION['stunde'].'<br>';
 	
 }
 else
 {
 	if ($localhost)
 	{
 		print 'neue sessionstunde: '.date("H",$zeit).'<br>';
 	}
 	$_SESSION['stunde'] = date("H",$zeit);
 	$neuerevent=1;
 }
 
 
 
 
if ($localhost)
 {
 	print ' nach isset: aktuellestunde: '.$aktuellestunde.' session-stunde: '.$_SESSION['stunde'].'<br>';
 
 	print ' neuerevent: '.$neuerevent.'<br>';
 	
 	print ' neuerminutenevent: '.$neuerminutenevent.'<br>';

	#print ' session_id: '.$session_id.'<br>';
	#print 'ip: '.$remote_ip.'<br>';
	#print 'zeit: '.$zeit.'<br>';
 }
$datum = date("d.m.Y",$zeit);
$uhrzeit = date("H:i",$zeit);



#http://www.traum-projekt.com/forum/19-traum-dynamik/131064-datum-format-yyyy-mm-dd.html


$sql_datum = date('Y-m-d', strtotime($datum));

$kalenderjahr = date("Y")*1;
$kalendermonat = date("m")*1;
$kalendertag = date("d")*1;

if ($localhost)
 {
 		echo "Datum: ",$datum,"  Zeit: ",$uhrzeit," Uhr<br>";
	#print 'kalenderjahr: '.$kalenderjahr.' kalendermonat: '.$kalendermonat.' kalendertag: '.$kalendertag.'<br>';

}
$besucher = get_current_user();
#print_r($_SERVER);

$result_besuche = mysql_query("SELECT * FROM besucher WHERE ip  = '$remote_ip'", $db)or die(print '<p>Beim Suchen nach IP ist ein Fehler passiert: '.mysql_error().'</p>');
#print mysql_error();
$rowkontrolle = mysql_num_rows($result_besuche);
if ($rowkontrolle > 1)
{
	print 'rowkontrolle: '.$rowkontrolle.'<br>';
}
$besucherarray = array();

$register = "";
$sopran=0;
$alt=0;
$tenor=0;
$bass=0;
$alle=0;

while ($stat = mysql_fetch_array($result_besuche) ) #
{

	print 'in while stat:  stat[ip]: *'.$stat['ip'].'*  remote_ip: '.$remote_ip.' besuche: '.$stat['besuch'].'<br>';

	if (($stat['ip'] == $remote_ip)   && !(in_array($remote_ip,$besucherarray)) ) 
	{
	
		#print_r($stat);
		#print '<br>';
		if ($localhost)
 		{
 			#print 'if stat[ip] == $remote_ip OK<br>';
			#print 'in besuchbesucherarray: '.$stat['besuch'].'<br>';
		}
		$besucherarray[]=$stat['besuch']; # 
		if (isset($stat['session_id']))
		{
			$besucher_session_id = $stat['session_id'];
			#print 'session_id: '.$session_id.' besucher_session_id: '.$besucher_session_id.'<br>';
		}
		if (isset($stat['register']))
		{
			$register=$stat['register'];
			if ($localhost)
 			{
				print 'register: '.$register.' | ';
			}
		}
		if (isset($stat['sopran']))
		{
			$sopran=$stat['sopran'];
			if ($localhost)
 			{
				print 'S: '.$sopran.' | ';
			}
		}
		if (isset($stat['sopran']))
		{
			$alt=$stat['alt'];
			if ($localhost)
 			{
				print 'A: '.$alt.' | ';
			}
		}
		if (isset($stat['sopran']))
		{
			$tenor=$stat['tenor'];
			if ($localhost)
 			{
				print 'T: '.$tenor.' | ';
				}
		}
		if (isset($stat['bass']))
		{
			$bass=$stat['bass'];
			if ($localhost)
 			{
				print 'B: '.$bass.' | ';
			}
		}
		if (isset($stat['alle']))
		{
			$bass=$stat['alle'];
			if ($localhost)
 			{
				print 'alle: '.$alle.'<br>';
			}
		}

	}
	
}

$anzahlbesuche=0;
if (count($besucherarray))
{
	$anzahlbesuche = $besucherarray[0];
	if ($neuesession)
	{
		$anzahlbesuche = $anzahlbesuche +1;
		$neuesession = 0;
	}	
}
#print 'besuche neu: '.$anzahlbesuche.' besucher_session_id: '.$besucher_session_id.'<br>';

#if ($remote_ip == $home_ip) # nur at home anzeigen
if ($localhost)
 {
	#print 'besucher: '.$besucher.' * ';
	print 'home_ip: '.$set['home_ip'].' remote_ip: '.$remote_ip.' ';
	print 'besuche neu: '.$anzahlbesuche.' ';
	
	print '<br>aktuelle session_id: '.$session_id.'<br>session_id aus DB besucher: '.$besucher_session_id.'<br>';

}

#if (!($session_id == $besucher_session_id)) #Neue  Session

{
	if (count($besucherarray) )
	{
	if ($localhost)
 		{
		print 'bisherige IP ip: *'.$stat['ip'].'*  home_ip: *'.$set['home_ip'].'* datum: '.$datum.'<br>';
		}
		#if ( ! ($remote_ip == $home_ip))
		{
			mysql_query("UPDATE besucher SET besuch = '$anzahlbesuche' ,zeit = '$uhrzeit', datum = '$sql_datum', session_id = '$session_id'  WHERE ip = '$remote_ip'"); 
			#print 'error: ';
			print_r(mysql_error());
			#print '<br>';
			mysql_query("UPDATE besucher SET  kalendertag = '$kalendertag',kalendermonat = '$kalendermonat' ,kalenderjahr = '$kalenderjahr'   WHERE ip = '$remote_ip'"); 

			
		}
	}
	else // auswaerts
	{
		$besuche = $anzahlbesuche+1;
		if ($localhost)
 		{
			print 'neue IP ip: *'.$stat['ip'].'*  home_ip: '.$set['home_ip'].'<br>';
			print 'name: '.$besucher.' ';
			print 'besuch: '.$besuche.' ';
			print 'remote_ip: '.$remote_ip.' ';
			print 'zeit: '.$uhrzeit.' ';
			print 'datum: '.$datum.' ';
			print 'aktuelle session_id: '.$session_id.'<br>';
		}
		
		$result_insert = mysql_query("INSERT INTO besucher (id,name,besuch,ip,zeit,datum,kalenderjahr, kalendermonat,kalendertag,session_id) VALUES (NULL,'$besucher','$besuche','$remote_ip','$uhrzeit','$sql_datum','$kalenderjahr','$kalendermonat','$kalendertag','$session_id')");
		print_r(mysql_error());
		print '<br>';
		
	}

}
//print_r($besucherarray);


#CREATE TABLE neu_test AS SELECT * FROM test;
$test=1;
$pass = $passwort;
#if (($test == 1))# || ($passwort == "$pass"))
/*	
# print '<p class = "nameneingabe">Login OK</p>';
print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

print '<form   action="chor.php" method = "post">';
	print '<input type="hidden" name="task" value ="">';
	print '<h3 class = "admin" >';
	print '<input type="submit" class="links40" name="textadmin" value="Chor Audio"style="width: 150px; margin-right:10px;"> Chor Audio';
	print '</h3>';
print '</form>';

print '<hr style=" width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

print '<form  action="chor_archiv.php" method = "post">';
	print '<input type="hidden" name="task" value ="">';
	print '	<input type="hidden" name="test" value =1>';
	print '<h3 class = "admin" >';
	print '<input type="submit" class="links40" name="archivfilter" value="Archiv" style="width: 150px; margin-right:10px;"> Archiv';
	print '</h3>';
print '</form>';
*/
	
##
$task ;
$pfadfehler=0;
$audiopfad = "../Data/kirchenchor";
$playpfad="";
$register="";
$aktuellersatztitel="";

$werk = "";
$event = "";
$playpfad = "";
$stimme  = "";




$register = "";
if (isset($_GET['register']) )
{
	$register = $_GET['register']; # beim Klick auf Register
	mysql_query("UPDATE besucher SET register = '$register'   WHERE ip = '$remote_ip'"); 
	if ($register === "Sopran")
	{
		if ($neuerevent)
		{
			print 'sopran neuerevent<br>';
			$sopran = $sopran + 1;
			mysql_query("UPDATE besucher SET sopran = '$sopran'   WHERE ip = '$remote_ip'"); 
		}
		if ($neuerminutenevent)
		{
			print 'sopran neuerminutenevent<br>';
			$sopran = $sopran + 1;
			mysql_query("UPDATE besucher SET sopran = '$sopran'   WHERE ip = '$remote_ip'"); 
		}
		
	}

	if ($register === "Alt")
	{
		if ($neuerevent)
		{
			print 'alt neuerevent<br>';
			$alt = $alt + 1;
			mysql_query("UPDATE besucher SET alt = '$alt'   WHERE ip = '$remote_ip'"); 
		}
		if ($neuerminutenevent)
		{
			print 'alt neuerminutenevent<br>';
			$alt = $alt + 1;
			mysql_query("UPDATE besucher SET alt = '$alt'   WHERE ip = '$remote_ip'"); 

		}
	}

	if ($register === "Tenor")
	{
		if ($neuerevent)
		{
			print 'tenor neuerevent<br>';
			$tenor = $tenor + 1;
			mysql_query("UPDATE besucher SET tenor = '$tenor'   WHERE ip = '$remote_ip'");
		} 
	}


	if ($register === "Bass")
	{
		if ($neuerevent)
		{
			print 'bass neuerevent<br>';
			$bass = $bass + 1;
			mysql_query("UPDATE besucher SET bass = '$bass'   WHERE ip = '$remote_ip'"); 
		}
	}

	if ($register === "Alle")
	{
		if ($neuerevent)
		{
		$alle = $alle + 1;
		mysql_query("UPDATE besucher SET alle = '$alle'   WHERE ip = '$remote_ip'"); 
		}
	}
		if ($neuerminutenevent)
		{
			$alle = $alle + 1;
			mysql_query("UPDATE besucher SET alle = '$alle'   WHERE ip = '$remote_ip'"); 

		}


}


$playwerk="";
if (isset($_GET['playpfad']) && $_GET['playpfad'])
{
	$playpfad = $_GET['playpfad'];
	$pfadfehler = 1;
	$audiofileda=1;
	if (isset($_GET['werk']) && strlen($_GET['werk']))
	{
		#print '<br>playwerk da ';
		$playwerk = $_GET['werk'];
	}
	if (isset($_GET['satz']) && strlen($_GET['satz']))
	{
		#print '<br>playsatz da ';
		$playsatz = $_GET['satz'];
	}

}
else
{
	if (count($_GET)>1)
	{
		if ($_GET['event'])
		{
			#print '<br>event da ';
			$event = $_GET['event'];
			$aktivevent = $_GET['event'];
			$audiopfad = $audiopfad.'/'.$_GET['event'];
		}
		else
		{
			#print '<br>event nicht da ';
			$pfadfehler=1;
		}
		 if (strlen($_GET['werk']))
		{
			#print '<br>werk da ';
			$werk = $_GET['werk'];
			$audiopfad = $audiopfad.'/'.$_GET['werk'].'/';
		}
		else
		{
			#print '<br>werk nicht da ';
			$pfadfehler=1;
		}
		
		 if (isset($_GET['stimme'])&&strlen($_GET['stimme']))
		{
			#print '<br>stimme da ';
			$stimme = $_GET['stimme'];
			$audiopfad = $audiopfad.'/'.$_GET['stimme'].'/';
		}
		else
		{
			#print '<br>stimme nicht da ';
			$pfadfehler=1;
		}
	
	}
	else
	{
	$pfadfehler = 1;
	}
}
#print '<br>audiopfad: '.$audiopfad.' register: '.$register.'<br>';
#print '<br>audiopfad: '.$audiopfad.' pfadfehler: '.$pfadfehler.'<br>';
#print '<br>playpfad: '.$playpfad.'<br>';
if (isset($_POST['index']))
{
	$index = $_POST['index'];	
}

if (isset($_POST['name']))
{
	$name = $_POST['name'];
}
$task="";
if (isset($_POST['task']))
{
	$task = $_POST['task'];	
}
#print 'task: '.$task.'<br>';

$registernamenarray = array("Sopran","Alt","Tenor","Bass","Alle");

?>
	
<div id="adminContent">

  
	<h1 class = "titel"> Kirchenchor Dürnten</h1>
	<?php
	$sent='no';
	$zeilenname=0;
	$mediumordner="";
	
	# Audiodaten holen 
	$audiofilearray = array();
	$eventarray = array();
	$werkarray = array();
	$satzarray = array();

	#$index=0;
#print 'eventarray<br>';print '<br>';
#print_r($eventarray);
$event = "Karfreitag";
# Audiodaten holen 
$audiofilearray = array();
$eventarray = array();
$werkarray = array();
$einzelwerk = "werktitel";

$tempaktivevent = "";
$aktiveventliste = array();

# Aktiven Event holen
$result_aktiv = mysql_query("SELECT * FROM audio WHERE aktiv = 1", $db)or die(print '<p  >Beim Suchen nach events ist ein Fehler passiert: '.mysql_error().'</p>');
while ($aktivdaten = mysql_fetch_array($result_aktiv))
{
	if (isset($aktivdaten['aktiv']))
	{
		if (isset($aktivdaten['event']))
		{
			#print 'tempaktivevent: '.$aktivdaten['event'].' ';
			$tempaktivevent = $aktivdaten['event'];
			if (!in_array($aktivdaten['event'],$aktiveventliste))
			{
			$aktiveventliste[] = $aktivdaten['event'];
			}
		}
		#print 'aktiv: '.$aktivdaten['aktiv'].'<br>';
	}

}# while aktivdaten

$eventliste = array();
$eventdic = array();
$eventdicarray = array();

print '<div class = "fileabschnitt">';
print '<h2 class = "menutitel">Event</h2>';	

#print '<p>check</p>';
foreach ($aktiveventliste as $activevent)
{
	print '<div class = "eventabschnitt">';	# Div fuer jeden Event

	#print '<br>';
	print '<h2 class = "eventtitel ">'.$activevent.'</h2>';

	#print '<br>event: '.$activevent.' register: '.$register.'<br>';
 	$werkarray = array();
	$result_werk = mysql_query("SELECT werk FROM audio WHERE event = '$activevent' AND register = '$register'", $db)or die(print '<p  >Beim Suchen nach mididaten ist ein Fehler passiert: '.mysql_error().'</p>');
	while ($werkdaten = mysql_fetch_array($result_werk))
	{

	#print_r($werkdaten);
		if (isset($werkdaten['werk']))
		{
			$tempwerk = $werkdaten['werk'];

			$satzarray = array();
			if (!in_array($tempwerk,$werkarray))
			{
				print '<div class = "stueckabschnitt">';
				#Stuecktitel setzen
				print '<div class = "stuecktitelabschnitt">';
				print '<h2 class = "stuecktitel">'.$tempwerk.'</h2>';
				print '</div>'; # stuecktitelabschnitt
				if (strlen($register)) # Register ist ausgewaehlt, Rueckkehr von GET
				{
					$werkarray[] = $tempwerk;
					#print 'werk: '.$tempwerk.'<br>';
					$result_satz = mysql_query("SELECT satz FROM audio WHERE werk = '$tempwerk' AND event = '$activevent' AND register = '$register'", $db)or die(print '<p  >Beim Suchen nach mididaten ist ein Fehler passiert: '.mysql_error().'</p>');
					print '<ul class="stueck-nav">';
					while ($satzdaten = mysql_fetch_array($result_satz))
					{
						
						#print_r($satzdaten);
						#print '<br>';
						if (isset($satzdaten['satz']))
						{
							$tempsatz = $satzdaten['satz'];
							$stimmearray = array();
							if (!in_array($tempsatz,$satzarray))
							{
								print '<li class = "satz-nav">';
								print '<div class = "satztitelabschnitt">';
								$satzarray[] = $tempsatz;
								#print 'werk: '.$tempwerk.' satz: '.$tempsatz.'<br>';
								$result_stimme = mysql_query("SELECT * FROM audio WHERE satz = '$tempsatz' AND werk = '$tempwerk' AND event = '$activevent' AND register = '$register'", $db)or die(print '<p  >Beim Suchen nach mididaten ist ein Fehler passiert: '.mysql_error().'</p>');
							
								#print '<div class = "satzregisterabschnitt">';
								#print '<p class = "satztitel">'.$tempsatz.' </p>';
								#print '</div>';	#satzregisterabschnitt

								while ($stimmedaten = mysql_fetch_array($result_stimme))
								{
									if (isset($stimmedaten['stimme1']))
									{
										$tempstimme1 = $stimmedaten['stimme1'];
										if (!in_array($tempstimme1, $stimmearray))
										{
											#print 'werk: '.$tempwerk.'<br> satz: '.$tempsatz.'<br> stimme 1: '.$tempstimme1.'<br>';
											$stimmearray[] = $tempstimme1;

										}
									
									}
									if (isset($stimmedaten['stimme2']))
									{
										$tempstimme2 = $stimmedaten['stimme2'];
										if ((strlen($tempstimme2) && (!in_array($tempstimme2, $stimmearray))))
										{
											#print 'werk: '.$tempwerk.' satz: '.$tempsatz.' stimme 2: '.$tempstimme2.'<br>';
											$stimmearray[] = $tempstimme2;
										}
									}
									if (isset($stimmedaten['stimme3']))
									{
										$tempstimme3 = $stimmedaten['stimme3'];
										if ((strlen($tempstimme3) && (!in_array($tempstimme3, $stimmearray))))
										{
											#print 'werk: '.$tempwerk.' satz: '.$tempsatz.' stimme 3: '.$tempstimme3.'<br>';
											$stimmearray[] = $tempstimme3;
										}
									}
									if (count($stimmearray) > 1)
									{
										#print '<div class = "satzregisterabschnitt">';
											#print '<p class = "satztitel">'.$register.' '.$satzindex.'</p>';
										#print '<p class = "satztitel">'.$register.'</p>';
										#print '</div>';	#satzregisterabschnitt
						
									}
									
									#print '<div class = "satztitel">';
											print '<p class = "satztitel">'.$tempsatz.' ';
											print '<div class = "mixabschnitt">';
											#$playpfad = "../Data/kirchenchor/GD 1. Februar 15/Chumm mir wei/2_Teil_B/RG571B_A.mp3";
											#print '<a class = "mix" href="chor_midi.php?register='.$register.'&event='.$activevent.'&werk='.$tempwerk.'&satz='.$tempsatz.'&stimme=abc&playpfad='.$tempstimme1.'">>></a>';
											print '<a class = "mix" href="chor_midi.php?register='.$register.'&event='.$activevent.'&werk='.$tempwerk.'&satz='.$tempsatz.'&stimme=abc&playpfad='.$tempstimme1.'">>></a>';
											#print '<a class = "mix" href="'.$tempstimme1.'">>></a>';
											print '</p>';
											print '</div>';	#mixabschnitt

									#print '</div>';	#satztitel
									
								
								}
								print '</div>';	#satztitelabschnitt
								print '</li>';
							} # in array tempsatz
								print '<br>';
						}#if isset satz
					
						
					}#while satzdaten
					print '</ul>';# stueck_nav
					#print '$satzarray<br>';
					#print_r($satzarray);
					print '<br>';
				
					print '</div> ';# stueckabschnitt
				}# if strlen register
			}
			
		}
		
	}
	
	
	
	$result_event = mysql_query("SELECT * FROM audio WHERE event = '$activevent' AND register = '$register'", $db)or die(print '<p  >Beim Suchen nach mididaten ist ein Fehler passiert: '.mysql_error().'</p>');
	#print 'result_event: '.$result_event.'<br>';


	
	$eventarray = array();
	$bezeichnung = "TODO";
	$werkdicarray = array();
	$oldwerk="";
	$satzdicarray = array();
	print '</div> ';# eventabschnitt 

} # for aktivevent
print '</div> '; # fileabschnitt				
?>

<?php
print '	<div class="registerwahlabschnitt"> ';
		#print '<p>registerwahlabschnitt</p>';
		print '<ul class="register-nav">';
		for ($i=0;$i< count($registernamenarray);$i++)
		{
			if ($register == $registernamenarray[$i])
			{
				print '	<li  class = "register-nav"><a href="chor_midi.php?register='.$registernamenarray[$i].'">'.$registernamenarray[$i].'</a></li>';
			}
			else
			{
				print '	<li><a href="chor_midi.php?register='.$registernamenarray[$i].'">'.$registernamenarray[$i].'</a></li>';
			}
		} # for i

		#print '	<li><a href="../index.php">Home</a></li>';				
		print '	<li><a href="http://www.refduernten.ch/content/e14561/e12463/e15420/e15549/index_ger.html">zurück  </a></li>';
		
		print ' </ul>';
	
		#print '<br><p>register: '.$register.'</p>';
print '</div> '; # registerwahlabschnitt

?>
   <script type="text/javascript">
   	playerwerk  = "<?php echo $playpfad; ?>";
   	//playerwerk  = "http://www.refduernten.ch/www.zh.ref.ch/gemeinden/duernten/content/e14561/e12463/e15420/e15577/e1839/Cum_sanctis_S.mp3";
	</script>



<?php
print '<div class = "audioabschnitt">	';

	#$playpfad =  "../Data/kirchenchor/GD 1. Februar 15/Chumm mir wei/1_Teil_A/RG571A_A.mp3";
	if (strlen($playpfad))
	{
		#$playerwerk = "http://www.refduernten.ch/www.zh.ref.ch/gemeinden/duernten/content/e14561/e12463/e15420/e15577/e1874/Requiem2_S.mp3";
		$index=0;
		$aktuellersatz = explode('/',$playpfad); # Satzbezeichnung
		#print_r($aktuellersatz);
		$aktuellersatz = explode('_',$aktuellersatz[5]);
		unset($aktuellersatz[0]);
		
		#print '	<p> anzahl: '.count($aktuellersatz).' '.$aktuellersatz[1].'</p>';
		
		foreach ($aktuellersatz as $satzteil)
		{
		#print '	<p> satzteil: '.$satzteil.'</p>';
		$aktuellersatztitel .= $satzteil;
		$aktuellersatztitel .= " ";
		}
		
		print '	<h2 class = "audiotitel ">'.$playwerk.': '.$playsatz.'</h2>';
		
		{
	
			print '	<div class = "stimmeabschnitt">';
				if (stripos($stimme, "_mix"))
				{
					print '	<p class = "stimmetitel">'.$register.' mix</p>';
				}
				else
				{
					print '	<p class = "stimmetitel">'.$register.'</p>';
				}
				
				# Beginn Player

				
				#pfad: ftp://ruediheimlicher:@ruediheimlicher.ch//public_html/Audio/Player/jplayer/jplayer.blue.monday.css
				$safaribrowser=1;
				if ($safaribrowser)
				{
				print '<div class="safariplayerabschnitt">';
				
				$playerwerk = "mp3=http://www.ruediheimlicher.ch/Data/kirchenchor/Lieder/Test/Requiem2_A.mp3&amp;showstop=1";
				?>
				
				<!-- http://flash-mp3-player.net/players/maxi/generator/ -->
				<config>
					<param name="mp3" value="http%3A//flash-mp3-player.net/medias/another_world.mp3"/>
					<param name="showstop" value="1" />
					<param name="showvolume" value="1"/>
					<param name="sliderwidth" value="3"/>
					<param name="sliderheight" value="20"/>
				</config>

					<object type="application/x-shockwave-flash" data="http://flash-mp3-player.net/medias/player_mp3_maxi.swf" width="600" height="60">
					<param name="movie" value="http://flash-mp3-player.net/medias/player_mp3_maxi.swf" />
					<param name="bgcolor" value="#3afff6" />

					<param name="FlashVars" value="mp3=<?php echo $playpfad ?>&amp;showstop=1&amp;sliderwidth=3&amp;sliderheight=20&amp;bgcolor1=aabbcc&amp;bgcolor2=446688&amp;slidercolor1=6688aa&amp;slidercolor2=224466&amp;sliderovercolor=44ca00" />
				</object>
				
				<?php
				print '<div class="safarimarkerabschnitt">';
									# Lineal-Tabelle
	
					print '<table class = "markertabelle">';
					print '<tr>';
					for ($mark=0;$mark<21;$mark++)
					{
						if ($mark & 1)
						{
						print '<th class = "marker">\'';
						print '</th>';
						}
						else
						{
						print '<th class = "marker">|';
						print '</th>';
						}
					}

					print '</tr>';
					print '<tr>';
					for ($mark=0;$mark<21;$mark++)
					{
						if ($mark & 1)
						{
						print '<td class = "markerindex">';
						print '</td>';
					
						}
						else
						{
						$marker = $mark/2;
						print '<td class = "markerindex">'.$marker.'';
						print '</td>';
						}
					}

					print '</tr>';
					print '</table>';

				print '</div> ';#safarimarkerabschnitt
				print '</div> ';#safariplayerabschnitt
				}
				else
				{
				/*
				# Alter Player
				print '<div class="playerabschnitt">';
				
				print '
				  <div id="jquery_jplayer_1" class="jp-jplayer"></div>
				  
				  <div id="jp_container_1" class="jp-audio">
					<div class="jp-type-single">
					  <div class="jp-gui jp-interface">
						<ul class="jp-controls">
						  <li><a href="javascript:;" class="jp-play" tabindex="1" title="play">play</a></li>
						  <li><a href="javascript:;" class="jp-pause" tabindex="1">pause title="pause"</a></li>
		  
						  <li><a href="javascript:;" class="jp-stop" tabindex="1" title="stop">stop</a></li>
						
						
						  <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
						  <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
						  <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
						
						
						</ul>
						<div class="jp-progress">
						  <div class="jp-seek-bar">
							<div class="jp-play-bar"></div>
						  </div>
						</div>
						<div class="jp-volume-bar" >
						  <div class="jp-volume-bar-value"></div>
						</div>
						<div class="jp-time-holder">
						  <div class="jp-current-time"></div>
						  <div class="jp-duration"></div>
						  <ul class="jp-toggles">
						  <!--
							<li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
							<li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
							-->
						  </ul>
						</div>
					  </div>
					  <!--
					  <div class="jp-title">
						<ul>
						  <li>'.$register.'</li>
						</ul>
					  </div>
					  -->
					  <div class="jp-no-solution">
						<span>Update Required</span>
						To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
					  </div>
					</div>
				  </div>

				';
				
				
				print '<div class="markerabschnitt">';
					# Lineal-Tabelle
	
					print '<table class = "markertabelle">';
					print '<tr>';
					for ($mark=0;$mark<21;$mark++)
					{
						if ($mark & 1)
						{
						print '<th class = "marker">\'';
						print '</th>';
						}
						else
						{
						print '<th class = "marker">|';
						print '</th>';
						}
					}

					print '</tr>';
					print '<tr>';
					for ($mark=0;$mark<21;$mark++)
					{
						if ($mark & 1)
						{
						print '<td class = "markerindex">';
						print '</td>';
					
						}
						else
						{
						$marker = $mark/2;
						print '<td class = "markerindex">'.$marker.'';
						print '</td>';
						}
					}

					print '</tr>';
					print '</table>';
				print '</div>';	# Markerabschnitt			

			print '</div> ';#playerabschnitt
			*/
			 }
				# Ende Player
	
				
				
				print '<br>';
				print '<br>';
				print '	<h3 class = "erklaerung ">Mit der Maus lässt sich die Abspielmarke verschieben.<br>
				Mit der Leertaste der Tastatur kann die Wiedergabe angehalten und wieder fortgesetzt werden.
				<br><br>Der Massstab erleichtert das Wiederauffinden einer Stelle, die wiederholt werden soll.<br>
				</h3>';

				
			print '</div>'; # stimmeabschnitt
		}

	}# if audiofileda
	else
	{
		print '	<h3 class = "auswahlerklaerung ">Wähle zuerst oben das Register aus.<br>Klicke dann beim gewünschten Stück auf die Solo- oder die Mix-Taste.<br>Der Player erscheint. Drücke nun auf die Starttaste. <br></h3>';
		print '	<br><br><br><h3 class = "playererklaerung "line-height:1.0>Wenn der Player nicht erscheint oder eine Warnung angezeigt wird, kannst du ihn mit diesem Link herunterladen: </h3>';
		
		print '	<h3 class = "link" ><a href = "http://www.adobe.com/go/getflashplayer"  ><i>Flashplayer laden</i></a></h3>';
		
	}
	?>
	 
    <?php


print '	</div>'; # audioabschnitt

?>


</div > <!-- adminContent -->
</body>

</html>

