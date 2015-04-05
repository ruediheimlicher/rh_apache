<?php
/*

Chor Admin Datenbank 
*/

include("pwd.php");

/* verbinden mit db */
	$db=mysql_connect('localhost','root','Ideur0047');

	mysql_set_charset('utf8',$db);
	mysql_select_db("midi", $db); 

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chor MIDI</title>
<link href="chor.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="../Audio/Player/jplayer//blue.monday/jplayer.blue.monday.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>
<script type="text/javascript" src="../Audio/Player/jplayer/jquery.jplayer.min.js"></script>
<script type="text/javascript">
var playerwerk = "http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3";
var playerstimme = "Bass";
    
$(document).ready(function(){
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
        solution: "html, flash",
        swfPath: "js",
        supplied: "mp3, oga",
        wmode: "window",
        preload: 'metadata'
      });
    });
</script>
<link href="chor.css" rel="stylesheet" type="text/css" />
<link href="jplayer.css" rel="stylesheet" type="text/css" />
</head>

<body class="liste">

<div><h1 class="lernmedien">Chor MIDI</h1></div>

<?php
#phpinfo();
$datum="";
print '<p>benutzer;: '. $benutzer.'* pw: '. $passwort.'*</p>';
print'POST<br>';
print_r($_POST);
print'<br>';
print'GET<br>';print_r($_GET);
print'<br>';
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

$result_home = mysql_query("SELECT * FROM settings WHERE id= 1", $db)or die(print '<p>Beim Suchen nach home ist ein Fehler passiert: '.mysql_error().'</p>');
print mysql_error();
$set = mysql_fetch_array($result_home);
#print 'home_ip: '.$set['home_ip'].'<br>';
$home_ip = $set['home_ip'];

$remote_ip=$_SERVER['REMOTE_ADDR'];
$home_ip = $set['home_ip'];
$zeit = $_SERVER['REQUEST_TIME'];
#print_r($_SESSION);
print 'home_ip: '.$set['home_ip'].' remote_ip: '.$remote_ip.'<br>';
#echo $_SERVER['HTTP_HOST'];  
#print 'session_id: '.session_id().'<br>';
$session_id = session_id();
#print 'ip: '.$remote_ip.'<br>';
#print 'zeit: '.$zeit.'<br>';

$datum = date("d.m.Y",$zeit);
$uhrzeit = date("H:i",$zeit);
echo $datum,"  ",$uhrzeit," Uhr<br>";

$besucher = get_current_user();
#print_r($_SERVER);
$besucher = gethostbyaddr($_SERVER['REMOTE_ADDR']);
echo $besucher," <br>";

#CREATE TABLE neu_test AS SELECT * FROM test;
$test=1;
$pass = $passwort;
#if (($test == 1))# || ($passwort == "$pass"))
	
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

$register = "sopran";
if (isset($_GET['register']))
{
$register = $_GET['register']; # beim Klick auf Register
}

if (isset($_GET['playpfad']) && $_GET['playpfad'])
{
	$playpfad = $_GET['playpfad'];
	$pfadfehler = 1;
	$audiofileda=1;
	if (strlen($_GET['werk']))
	{
		#print '<br>playwerk da ';
		$playwerk = $_GET['werk'];
	}
}
else
{
	if (count($_GET)>1)
	{
		if ($_GET['event'])
		{
			print '<br>event da ';
			$event = $_GET['event'];
			$aktivevent = $_GET['event'];
			$audiopfad = $audiopfad.'/'.$_GET['event'];
		}
		else
		{
			print '<br>event nicht da ';
			$pfadfehler=1;
		}
		 if (strlen($_GET['werk']))
		{
			print '<br>werk da ';
			$werk = $_GET['werk'];
			$audiopfad = $audiopfad.'/'.$_GET['werk'].'/';
		}
		else
		{
			print '<br>werk nicht da ';
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
			print '<br>stimme nicht da ';
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

$registernamenarray = array("Sopran","Alt","Tenor","Bass");

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
	#$db->set_charset("utf8"); 
	/* sql-abfrage schicken */
	$result_midi = mysql_query("SELECT * FROM audio ", $db)or die(print '<p  >Beim Suchen nach mididaten ist ein Fehler passiert: '.mysql_error().'</p>');
	if (mysql_error())
	{
		print 'SELECT suchname error:';
		print mysql_error();
		print '<br>';
	};

	/* resultat in einer schleife auslesen */


	#print '<table width="759" border="1">';

while ($mididaten = mysql_fetch_array($result_midi) )
{
	if (isset($mididaten['event']))
	{
		$event=$mididaten['event'];
		#print '<p>Event: '. $event.'</p>';
		$eventdic = array("event"=>  $event);
		
		if (isset($mididaten['werk']))
		{
			$werk=$mididaten['werk'];
			#print '<p>werk: '. $werk.'</p>';
			$eventdic['werk'] = $werk;
			$werkarray = array();
			$werkdic = array("werk" => $werk);
			if (isset($mididaten['satz']))
			{
				$satz=$mididaten['satz'];
				#print '<p>satz: '. $satz.'</p>';
				$eventdic['satz'] = $satz;
				$satzdic = array("satz" => $satz);
				# Sopran		
				$sopranstimmen=3;
				if (isset($mididaten['sopran3']))
				{
					$sopran3=$mididaten['sopran3'];
					if (strlen($sopran3))
					{
						#print '<p>Sopran 3: '. $sopran3.'</p>';
						$eventdic['sopran3'] = $sopran3;
						$satzdic['sopran3'] = $sopran3;
						
					}
					else
					{
						$sopranstimmen--;
					}
				}

				if (isset($mididaten['sopran2']))
				{
					$sopran2=$mididaten['sopran2'];
					if (strlen($sopran2))
					{
						#print '<p>Sopran 2: '. $sopran2.'</p>';
						$eventdic['sopran2'] = $sopran2;
						$satzdic['sopran2'] = $sopran2;
					}
					else
					{
						$sopranstimmen--;
					}
				}

				if (isset($mididaten['sopran1']))
				{
					$sopran1=$mididaten['sopran1'];
					if (strlen($sopran1))
					{
						if ($sopranstimmen ==1)
						{
							#print '<p>Sopran: '. $sopran1.'</p>';
						}
						else
						{
							#print '<p>Sopran 1: '. $sopran1.'</p>';
						}
						$eventdic['sopran1'] = $sopran1;
						$satzdic['sopran1'] = $sopran1;
					}
				}

		# Alt

				$altstimmen=3;
				if (isset($mididaten['alt3']))
				{
					$alt3=$mididaten['alt3'];
	
					if (strlen($alt3))
					{
						#print '<p>Alt 3: '. $alt3.'</p>';
						$eventdic['alt3'] = $alt3;
					}
					else
					{
						#print '<p>kein Alt 3</p>';
						$altstimmen--;
					}
				}

				if (isset($mididaten['alt2']))
				{
					$alt2=$mididaten['alt2'];
					if (strlen($alt2))
					{
						#print '<p>Alt 2: '. $alt2.'</p>';
						$eventdic['alt2'] = $alt2;
					}
					else
					{
					#print '<p>kein Alt 2</p>';
						$altstimmen--;
					}
				}

				if (isset($mididaten['alt1']))
				{
					$alt1=$mididaten['alt1'];
					if (strlen($alt1))
					{
						if ($altstimmen ==1)
						{
							#print '<p>Alt: '. $alt1.'</p>';
							$eventdic['alt1'] = $alt1;
						}
						else
						{
							#print '<p>Alt 1: '. $x.'</p>';
						}
					}
				}

				$tenorstimmen=3;
				if (isset($mididaten['tenor3']))
				{
					$tenor3=$mididaten['tenor3'];
	
					if (strlen($tenor3))
					{
						#print '<p>Tenor 3: '. $tenor3.'</p>';
						$eventdic['tenor3'] = $tenor3;
					}
					else
					{
						#print '<p>kein tenor3 </p>';
						$tenorstimmen--;
					}
				}

				if (isset($mididaten['tenor2']))
				{
					$tenor2=$mididaten['tenor2'];
					if (strlen($tenor2))
					{
						#print '<p>Tenor 2: '. $tenor2.'</p>';
						$eventdic['tenor2'] = $tenor2;
					}
					else
					{
					#print '<p>kein Alt 2</p>';
						$tenorstimmen--;
					}
				}

				if (isset($mididaten['tenor1']))
				{
					$tenor1=$mididaten['tenor1'];
					if (strlen($tenor1))
					{
						if ($tenorstimmen ==1)
						{
							#print '<p>Tenor: '. $tenor1.'</p>';
							
						}
						else
						{
							#print '<p>Tenor 1: '. $tenor1.'</p>';
						}
						$eventdic['tenor1'] = $tenor1;
					}
				}

		# Bass
				$bassstimmen=3;
				if (isset($mididaten['bass3']))
				{
					$bass3=$mididaten['bass3'];
	
					if (strlen($bass3))
					{
						#print '<p>Bass 3: '. $bass3.'</p>';
						$eventdic['bass3'] = $bass3;
					}
					else
					{
						#print '<p>kein Alt 3</p>';
						$bassstimmen--;
					}
				}

				if (isset($mididaten['bass2']))
				{
					$bass2=$mididaten['bass2'];
					if (strlen($bass2))
					{
						#print '<p>Bass 2: '. $bass2.'</p>';
						$eventdic['bass2'] = $bass2;
					}
					else
					{
					#print '<p>kein Alt 2</p>';
						$bassstimmen--;
					}
				}

				if (isset($mididaten['bass1']))
				{
					$bass1=$mididaten['bass1'];
					if (strlen($bass1))
					{
						if ($bassstimmen ==1)
						{
							#print '<p>Bass: '. $bass1.'</p>';
							
						}
						else
						{
							#print '<p>Bass 1: '. $bass1.'</p>';
						}
						$eventdic['bass1'] = $bass1;
						
					}
				}
			} # if werk
		}# if satz
		#print 'eventdic von '.$event.'<br>';
		#print_r($eventdic);
		#print '<br>';print '<br>';
		#$eventarray[] = $eventdic;
	} # if event
	
}#while 
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

foreach ($aktiveventliste as $activevent)
{

print '<br>*** activevent: '.$activevent.' ';
print 'register: '.$register.'<br>';
# Ergebnisse fuer register
$result_event = mysql_query("SELECT * FROM audio WHERE event = '$activevent' AND register = '$register'", $db)or die(print '<p  >Beim Suchen nach mididaten ist ein Fehler passiert: '.mysql_error().'</p>');
#print 'result_event: '.$result_event.'<br>';




while ($eventdaten = mysql_fetch_array($result_event))
{
	if (isset($eventdaten['event']))
	{
		$tempevent = $eventdaten['event'];
		# ist schon ein dic des events im eventarray?
		if (!isset($eventarray[$tempevent]))
		{
			print '*** neuer event: '.$tempevent.' <br>'; 
			#$eventarray[] = array($tempevent => $tempevent);
		}
		else
		{
			print 'event '.$tempevent.' ist schon  da <br>'; 
		}
		
		#$eventdic['event'] = $eventdaten['event'];
		print 'event: '.$eventdaten['event'].'<br>';
		
		# Ordner 'werke fuer event schon da?
		if (!isset($eventarray[$tempevent]['werkarray']))
		{
			print '*** neuer werkarray fuer '.$tempevent.'<br>'; 
			$eventarray[$tempevent]['werkarray'] = array(); # Array der Werke;
		}
		else
		{
			print 'werkarray fuer '.$tempevent.' schon da <br>'; 
			
		}
		
		# werk in eventdaten?		
		if (isset($eventdaten['werk']))
		{
			print 'tempwerk: '.$eventdaten['werk'].'<br>';
			$tempwerk = $eventdaten['werk'];
			
			if (!isset($eventarray[$tempevent]['werkarray']['.$tempwerk.']))
			{
					print '*** neuer satzarray fuer '.$tempwerk.' in werkarray <br>'; 
					$eventarray[$tempevent]['werkarray'][$tempwerk] = array('satzarray'=> array() );
					
			}
			else
			{
					print 'satzarray fuer '.$tempwerk.' in werkarray schon da<br>'; 
			}

			
			# saetze fuer tempwerk lesen
			if (isset($eventdaten['satz']))
			{
				print 'tempsatz: '.$eventdaten['satz'].'<br>';
				$tempsatz = $eventdaten['satz'];
				
				
				# registerstimmen fuer satz lesen
				$tempstimme1 = "";
				$tempstimme2 = "";
				$tempstimme3 = "";
				
				$tempsatzarray = array();
				
				if (isset($eventdaten['stimme1']) && strlen($eventdaten['stimme1']))
				{
					$tempstimme1 = $eventdaten['stimme1'];
					#print ' stimme 1: '.$register.' stimme1 URL: '.$eventdaten['stimme1'].'<br>';
					$tempsatzarray[] = $tempstimme1;
				} # if stimme1 
				if (isset($eventdaten['stimme2']) && strlen($eventdaten['stimme2']))
				{
					$tempstimme2 = $eventdaten['stimme2'];
					#print ' stimme 2: '.$register.' stimme2 URL: '.$eventdaten['stimme2'].'<br>';
					$tempsatzarray[] = $tempstimme2;
				} # if stimme1 
				if (isset($eventdaten['stimme3']) && strlen($eventdaten['stimme3']))
				{
					$tempstimme3 = $eventdaten['stimme3'];
					#print ' stimme 3: '.$register.' stimme3 URL: '.$eventdaten['stimme3'].'<br>';
					$tempsatzarray[] = $tempstimme3;
				} # if stimme1 
				
				foreach ($tempsatzarray as $tempstimme)
				{
					print ' stimme: '.$tempstimme.'<br>';
				}
				
				# satz schon in Satzarray?
				if (!isset($eventarray[$tempevent]['werkarray'][$tempwerk]['satzarray'][$tempsatz]))
				{
					print '*** neuer stimmearray fuer '.$tempsatz.' in satzarray <br>'; 
					$eventarray[$tempevent]['werkarray'][$tempwerk]['satzarray'][$tempsatz] = $tempsatzarray;
					
				}
				else
				{
					print 'stimmearray fuer '.$tempsatz.' in satzarray schon da<br>'; 
				}
				
			} # if satz
			# werk schon in werkarray?
			
			
		} # if werk
		#$eventarray[] = $eventdic;

		
	} # if event
	
		if (in_array($tempevent,array_keys($eventliste)))
		{
			print ' '.$tempevent.' ist schon in Liste <br>'; 
			$eventliste['daten'][] =  $eventarray[$tempevent];
		}
		else
		{
			print ' '.$tempevent.' ist noch nicht in Liste <br>'; 
			$eventliste[] = array('event'=>$tempevent, 'daten' => $eventarray[$tempevent]);
		}

	
	#print '<br><br>eventliste:<br>';
	#print_r($eventliste);
	#print '<br>end eventliste anz: '.count($eventarray).' end<br>';

	print '<br><br>eventarray:<br>';
	print_r($eventarray);
	print '<br>end eventarray anz: '.count($eventarray).'<br>';
	
	print '*Keys:<br>';
	print_r(array_keys($eventarray));
	print '*<br>';
	
	$satzindex=0;
	foreach($eventarray  as $tempeventarraydic)
	{
		print 'index: '.$satzindex.' tempeventarraydic: *<br>';
		print_r($tempeventarraydic);
		print '*<br>';
		$aaa = "Karfreitag";
		
		print 'werkarray:<br>';
		foreach($tempeventarraydic['werkarray']  as $tempevent)
		{
			print_r($tempevent);
			print '*<br>';
		}# for tempevent

		#Name des events
		
		if (isset($tempeventarraydic[$aaa]))
		{
			print_r($tempeventarraydic);
			print '*<br>';
			print 'event: '.$tempeventarraydic['event'].'<br>';
			
			$aaa = $tempeventarraydic['event'];
			print 'aaa: '.$aaa.'<br>';
			print '--werkarray: '.$tempeventarraydic[$aaa].'--<br>';
			
			#if (is_array($values))
			{
			print 'werkarray:<br>';
			foreach($tempeventarraydic['event']['werkarray']  as $tempevent)
			{
				print_r($tempevent);
				print '*<br>';
			}# for tempevent
			}
		}# if tempdic event
		print '*<br>';
	}# for tempdic
	
				# Stimmen
				#foreach($satzdic["stimme"] as $stimmetitel)
				#{
					#print 'stimme: '.$stimmetitel.'<br>';
				#}
			
				$satzindex++;
		#print '*<br>';
	



	##print '<br><br>--- <br>';

	print '<br><br>--- <br>';

} # while eventdaten

} # for aktivevent

print '<br><br>Schluss:<br>';
	print '<br><br>eventliste:<br>';
	print_r($eventliste);
	print '<br>end eventliste anz: '.count($eventliste).' end<br><br>Teile<br>';
	foreach($eventliste as $teil)
	{
		print_r($teil);
		print '*<br><br>';
	}

	print '<br><br>eventarray:<br>';
	print_r($eventarray);
	print '<br>end eventarray anz: '.count($eventarray).'<br>';
	
	print '*Keys:<br>';
	print_r(array_keys($eventarray));
	print '*<br>';


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

# db lesen



# end db lesen	
	
#print ' <div class = "verzeichnisabschnitt">';
#print '<p>verzeichnisabschnitt</p>';

# Eventmenu aufbauen 

$eventtitel = array();
/*
foreach($eventliste as $event )
{
print 'event<br>';
print_r($event['daten']['werkarray']);
print '*<br><br>';
		foreach($event['daten']['werkarray']  as $tempevent)
		{
			print_r($tempevent);
			print '*<br>';
		}
}
*/

print '<div class = "fileabschnitt">';
print '<h2 class = "menutitel">Event</h2>';	

print '<div class = "stueckabschnitt">';
		
		#Stuecktitel setzen
#		print '<div class = "stuecktitelabschnitt">';
		print '<h2 class = "stuecktitel">'.$einzelwerk.'</h2>';
		
		if (strlen($register)) # Register ist ausgewaehlt, Rueckkehr von GET
		{
			print '<ul class="satz-nav">';
			$stueckindex=0;
			$satzindex=0;
			foreach($eventarray as $satzdic)
			{
				#Name des Satzes
				/*
				print 'index: '.$satzindex.'<br>';
				print 'satz: '.$satzdic['stimmearray'].'<br>';
				print_r($satzdic);
				if (isset($satzdic["stimmearray"]) && count($satzdic["stimmearray"]))
				{
					$satzindex=0;
					foreach($satzdic["stimmearray"] as $stimmetitel)
					{
						print 'stimmetitel: '.$stimmetitel.'<br>';
					}
					
				}
				*/
				$satzindex++;
			}#foreach satzdic
			print '</ul>';
		}#strlen
		
		print '</div>'; # stuecktitelabschnitt

print '</div> '; # stueckabschnitt

print '</div> '; # fileabschnitt

?>


</div > <!-- adminContent -->
</body>

</html>

