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

$result_home = mysql_query("SELECT * FROM settings WHERE id= 1", $db)or die(print '<p>Beim Suchen nach home ist ein Fehler passiert: '.mysql_error().'</p>');
#print mysql_error();
$set = mysql_fetch_array($result_home);
#print 'home_ip: '.$set['home_ip'].'<br>';
$home_ip = $set['home_ip'];

$remote_ip=$_SERVER['REMOTE_ADDR'];
$home_ip = $set['home_ip'];
$zeit = $_SERVER['REQUEST_TIME'];
#print_r($_SESSION);
#print 'home_ip: '.$set['home_ip'].' remote_ip: '.$remote_ip.'<br>';
#echo $_SERVER['HTTP_HOST'];  
#print 'session_id: '.session_id().'<br>';
$session_id = session_id();
#print 'ip: '.$remote_ip.'<br>';
#print 'zeit: '.$zeit.'<br>';

$datum = date("d.m.Y",$zeit);
$uhrzeit = date("H:i",$zeit);
#echo $datum,"  ",$uhrzeit," Uhr<br>";

$besucher = get_current_user();
#print_r($_SERVER);
$besucher = gethostbyaddr($_SERVER['REMOTE_ADDR']);
#echo $besucher," <br>";

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

$register = "sopran";
if (isset($_GET['register']))
{
$register = $_GET['register']; # beim Klick auf Register
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
					#$sopran3=$mididaten['sopran3'];
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
					#$sopran2=$mididaten['sopran2'];
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
					#$sopran1=$mididaten['sopran1'];
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
$eventdicarray = array();

print '<div class = "fileabschnitt">';
print '<h2 class = "menutitel">Event</h2>';	

print '<p>check</p>';
foreach ($aktiveventliste as $activevent)
{
	print '<div class = "eventabschnitt">';	// Div fuer jeden Event

	#print '<br>';
	print '<h2 class = "eventtitel ">'.$activevent.'</h2>';

	#print '<br>event: '.$activevent.'<br>';
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
   			
		</script>';

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

				print '<div class="playerabschnitt">';
				#pfad: ftp://ruediheimlicher:@ruediheimlicher.ch//public_html/Audio/Player/jplayer/jplayer.blue.monday.css
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

			print '</div> ';
				# Ende Player
	
				
				
				print '<br>';
				print '	<h3 class = "erklaerung ">Mit der Maus lässt sich die Abspielposition setzen. Ein Mausklick an eine Stelle verschiebt die Position dorthin.<br>Der Massstab erleichtert das Wiederauffinden einer Stelle, die wiederholt werden soll.<br>
				<br>Die Lautstärke lässt sich mit einem Klick in den oberen Balken anpassen.</h3>';

				
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

