<?php
session_start();
/*
Datenbank kicho
*/


/* verbinden mit db */
	
	#$db=mysql_connect('localhost','ruedihei_db','rueti8630');
$db = include "../bank.php";
	mysql_set_charset('utf8',$db);
	mysql_select_db("kicho", $db); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--    <script language="javascript" type="text/javascript" src="../flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="../flot/jquery.flot.js"></script>-->
<!--    
<META HTTP-EQUIV="refresh" content="10; URL=http://www.ruediheimlicher.ch/Strom/strom.php/">

<script type="text/javascript" language="javascript" src="niftyplayer.js"></script>
-->
<!--
<script type="text/javascript" src="Player/audio-player.js"></script>  
<script type="text/javascript">  
            AudioPlayer.setup("Player/player.swf", 
            {  
            file: "/uploads/example.mp4",
        height: 360,
        image: "/uploads/example.jpg",
        width: 640
               
            });  
</script>  
-->
<!--
<script type="text/javascript" src="http://mediaplayer.yahoo.com/js"></script>
-->
<!--
<script language="javascript" type="text/javascript" src="datumpicker.js">
</script>
-->
	
 <link type="text/css" href="../Audio/Player/jplayer//blue.monday/jplayer.blue.monday.css" rel="stylesheet" />
 
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>

  <script type="text/javascript" src="../Audio/Player/jplayer/jquery.jplayer.min.js"></script>
  
   <script type="text/javascript">
   	var playerstueck = "http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3";
    var playerstimme = "Bass";
    
    
    $(document).ready(function(){
    
      $("#jquery_jplayer_1").jPlayer(
      {
        ready: function () 
        {
          $(this).jPlayer("setMedia", 
          {
            //mp3: "../Data/kirchenchor/Konzert_2013/Missa/1_Kyrie/Kyrie_A_mix.mp3",
            mp3: playerstueck,
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

<title>Kirchenchor</title>
</head>


<body class="basic">

<script type="text/javascript">
function submitform(registername)
{
	document.write("Register");
	document.write(registername );
}
</script>

<?php
#POST abfragen

print '<div id="container">';
print 'POST:<br>';
print_r($_POST);
print '<br>';
print 'GET: ';
print_r($_GET);
print '<br>';


$result_home = mysql_query("SELECT * FROM settings WHERE id= 0", $db)or die(print '<p>Beim Suchen nach home ist ein Fehler passiert: '.mysql_error().'</p>');
print mysql_error();
$set = mysql_fetch_array($result_home);
#print 'home_ip: '.$set['home_ip'].'<br>';
$home_ip = $set['home_ip'];
while ($stat = mysql_fetch_array($result_home) )
{
	#print_r($stat);
}
#print_r($home_ip['home_ip']);

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
#echo $datum,"  ",$uhrzeit," Uhr";

$besucher = get_current_user();
#print_r($_SERVER);
#$besucher = gethostbyaddr($_SERVER['REMOTE_ADDR']);



#$result_count = mysql_query("SELECT * FROM besucher WHERE ip  = '$remote_ip'", $db)or die(print '<p>Beim #Suchen nach IP ist ein Fehler passiert: '.mysql_error().'</p>');

$result_besuche = mysql_query("SELECT * FROM besucher WHERE ip  = '$remote_ip'", $db)or die(print '<p>Beim Suchen nach IP ist ein Fehler passiert: '.mysql_error().'</p>');
print mysql_error();
$rowkontrolle = mysql_num_rows($result_besuche);
if ($rowkontrolle > 1)
{
print 'rowkontrolle: '.$rowkontrolle.'<br>';
}
$besucherarray = array();
#$besucher_session_id=1;
# array der ip gleich wie $remote_ip

while ($stat = mysql_fetch_array($result_besuche) ) #
{

print 'in while stat:  stat[ip]: *'.$stat['ip'].'*  remote_ip: '.$remote_ip.' besuche: '.$stat['besuch'].'<br>';

	if (($stat['ip'] == $remote_ip)   && !(in_array($remote_ip,$besucherarray)) ) 
	{
		#print_r($stat);
		#print '<br>';
		#print 'in besuchbesucherarray: '.$stat['besuch'].'<br>';
		$besucherarray[]=$stat['besuch']; # 
		$besucher_session_id = $stat['session_id'];
	}
	
}
$anzahlbesuche=0;
if (count($besucherarray))
{
	$anzahlbesuche = $besucherarray[0]+1;
}
#print 'besuche neu: '.$anzahlbesuche.' besucher_session_id: '.$besucher_session_id.'<br>';
if ($remote_ip == $home_ip) # nur at home anzeigen
{
	print 'besucher: '.$besucher.' * ';
	print 'home_ip: '.$set['home_ip'].' remote_ip: '.$remote_ip.' ';
	print 'besuche neu: '.$anzahlbesuche.' ';
	print 'session_id: '.$session_id.' besucher_session_id: '.$besucher_session_id.'<br>';
}


#if (!($session_id == $besucher_session_id)) #Neue  Session

{
	if (count($besucherarray) )
	{
		#print '<br>ip: '.$stat['ip'].'  home_ip: '.$set['home_ip'].'<br>';
		
		#if ( ! ($remote_ip == $home_ip))
		{
			mysql_query("UPDATE besucher SET besuch = '$anzahlbesuche' ,zeit = '$uhrzeit', datum = '$datum', session_id = '$session_id'  WHERE ip = '$remote_ip'"); 
		}
	}
	else
	
	{
		#print 'ip: *'.$stat['ip'].'*  home_ip: '.$set['home_ip'].'<br>';
		$besuche = $anzahlbesuche+1;
		$result_insert = mysql_query("INSERT INTO besucher (id,name,besuch,ip,zeit,datum,session_id) VALUES (NULL,'$besucher','$besuche','$remote_ip','$zeit','$datum',$session_id)");
	}

}
//print_r($besucherarray);



#$result_insert = mysql_query("INSERT INTO besucher (id,name,besuch,ip) VALUES (NULL,'$besucher','$besuche+1','$remote_ip')");
	
$resultat=mysql_affected_rows($db);
#print 'INSERT error: *'.mysql_error().'*<br>';
#print 'resultat affected_rows: *'.$resultat.'*<br>';

$task ;
$pfadfehler=0;
$audiopfad = "../Data/kirchenchor";
$playpfad="";
$register="";
$aktuellersatztitel="";

if (isset($_GET['register']))
{
$register = $_GET['register']; # beim Klick auf Register
}
$stueck = "";
$event = "";
$playpfad = "";
$stimme  = "";

if (isset($_GET['playpfad']) && $_GET['playpfad'])
{
	$playpfad = $_GET['playpfad'];
	$pfadfehler = 1;
	$audiofileda=1;
	if (strlen($_GET['stueck']))
	{
		print '<br>playstueck da ';
		$playstueck = $_GET['stueck'];
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
			$audiopfad = $audiopfad.'/'.$_GET['event'];
		}
		else
		{
			print '<br>event nicht da ';
			$pfadfehler=1;
		}
		 if (strlen($_GET['stueck']))
		{
			print '<br>stueck da ';
			$stueck = $_GET['stueck'];
			$audiopfad = $audiopfad.'/'.$_GET['stueck'].'/';
		}
		else
		{
			print '<br>stueck nicht da ';
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
if ($remote_ip == $home_ip) # nur at home anzeigen
{
	print '<form action="chor_admin.php" ><input type="submit" class="links40" value=">> Admin" name="textfile" style=" margin-left:20px;"></form>';
}
?>

<!--		<p>
http://stackoverflow.com/questions/6791238/send-post-request-on-click-of-href-in-jsp     
<form name="submitForm" method="POST" action="">
    <input type="hidden" name="param1" value="Hans">
    <A HREF="javascript:document.submitForm.submit()">Click Me</A>
</form>
<form name="submitForm2" method="POST" action="">
    <input type="hidden" name="param2" value="Fritz">
    <A HREF="javascript:document.submitForm2.submit()">Click Me Too</A>
</form>

</p>-->
		<!-- navmenuabschnitt --!>

<?php
print '	<div class="registerwahlabschnitt"> ';
		#print '<p>registerwahlabschnitt</p>';
		print '<ul class="register-nav">';
		for ($i=0;$i< count($registernamenarray);$i++)
		{
			if ($register == $registernamenarray[$i])
			{
				print '	<li  class = "register-nav"><a href="chor.php?register='.$registernamenarray[$i].'">'.$registernamenarray[$i].'</a></li>';
			}
			else
			{
				print '	<li><a href="chor.php?register='.$registernamenarray[$i].'">'.$registernamenarray[$i].'</a></li>';
			}
		} # for i

		#print '	<li><a href="../index.php">Home</a></li>';				
		print '	<li><a href="http://www.refduernten.ch/content/e14561/e12463/e15420/e15549/index_ger.html">zurück  </a></li>';
		print ' </ul>';
	
		#print '<br><p>register: '.$register.'</p>';
print '</div> ';
?>

<?php 	
	
#print ' <div class = "verzeichnisabschnitt">';
#print '<p>verzeichnisabschnitt</p>';
# Audiodaten holen 
$audiofilearray = array();
$eventarray = array();
$stueckarray = array();



if ($handle = opendir('../Data/kirchenchor/')) 
{
	/* Audiodaten holen */
	
	/*Namen der Events lesen*/

	while ($handle && (false !== ($file = readdir($handle))) ) 
	{
		#$regex ='/[^a-zA-ZäöüÄÖÜß0-9]/';
		$regex ='/^\.{1,2}/'; # Punkte am Anfang entfernen

		$filesauber = preg_replace($regex,'',$file);
		if (strlen($filesauber))
		{
			if (is_dir('../Data/kirchenchor/'.$filesauber.'/'))
			{
				#print  'event: *'. $filesauber.'*<br>';
				$position = strpos($filesauber,"_");
				#print  'pos: '.$position.'*<br>';

				$eventdic = array("event"=>  $filesauber);
	
				if ($stueckhandle = opendir('../Data/kirchenchor/'.$filesauber.'/'))
				{
					$stueckarray = array();
					while ($stueckhandle && (false !== ($stueckfile = readdir($stueckhandle))) )
					{
					#print  '---stueckfile: *'. $stueckfile.'*<br>';
						$stuecksauber =  preg_replace($regex,'',$stueckfile);
						
						if (strlen($stuecksauber))
						{
			
							#print  '---stueck: *'. $stuecksauber.'*<br>'; # Ordner fuer Musikstueck
							$position = strpos($stuecksauber,"_");
							#print  'pos: "'.$position.'"*<br>';
							$stueckarray[] = $stuecksauber;
							#array_push($eventdic,$stuecksauber);
						}
					}
					$eventdic["stuecke"] = $stueckarray;
					#array_push($eventdic,$stueckarray);
				}
				# Vorhandene Stuecke:
				#print 'eventdic: ';
				#print_r($eventdic);
				#print '<br>';
				$eventarray[] = $eventdic;
			}
			else
			{
				#print  'file: *'. $filesauber.'*<br>';
				#in array einsetzen
				#$audiofilearray[] = $file;
				#print '<li><a href="'.$filesauber.'">'.$filesauber.'</a></li>';
			}
		} # if strlen
	
		/*
		# macht dasselbe
		if ( ($file != "." && $file != ".."))
		{
			#print 'l: '.strlen($file).' '. $file.'  '. $filesauber.'<br>';
			#$audiofilearray[] = $file;
		}
		*/
	} # while
	
}
closedir($handle);
# End Audiodaten holen


print '</div>';

print '<div class = "fileabschnitt">';	

# Stuecke der Events lesen

#eventarray: array mit :
#event-name
#array mit [stuecke] : array mit namen des stuecks

	#print 'eventarray<br>';
	print_r($eventarray[0]);
	print '<br>';
	#print 'audiofilearray<br>';
	#print_r($audiofilearray);
	#print '<br>';
	#print '<p>fileabschnitt</p>';
	#print '<ul class="main-nav">';
	print '<h2 class = "menutitel">Event</h2>';
	
	# Eventmenu aufbauen Eventarray enthaelt Dics mit string 'event', array 'stuecke'
	
	
	foreach($eventarray as $event )
	{
		# Stimmen lesen
		print '<div class = "eventabschnitt">';	// Div fuer jeden Event
		print ' eventabschnitt start';
		# eventtitel setzen		
		print '<h2 class = "eventtitel ">'.$event['event'].'*</h2>';
		
		# audiopfad aufbauen: Event anfuegen
		$eventaudiopfad = $audiopfad.'/'.$event['event'].'/';
		
		# navigation aufbauen
		print '<ul class="stueck-nav">';
		
		$stueckindex=0;
		
		# Ebene 1: Stuecke
		$registerinit = array();
		foreach($event['stuecke'] as $einzelstueck)
		{
		
		
		{
			#print ' <p>stueckabschnitt</p>';
#			print '<div class = "stueckabschnitt">stueckabschnitt start';
			# audiopfad fuer stueck aufbauen:
		
#			print '<p>einzelstueck: '.$einzelstueck.'</p>';

			# Pfad zum Ordner des Einzelstuecks
			$stueckordnerpfad = $eventaudiopfad.$einzelstueck;	
			$pfadok=1;
			if (strpos($stueckordnerpfad,'DS_Store'))
			{
			$pfadok=0;
			}
							
			#audiofilearray fuer stueck > start
			$audiofilearray=array();
			$audiofileda=0;
			$audiopfadarray = array(); # Pfade der Audiofiles

			$audiofiledicarray=array(); # Pfade der Audiofiles in Satz-Ordnern
		
			if ($pfadok && ($stuecksatzhandle = opendir($stueckordnerpfad)))
			{
				#print '<p><br><br>audio da: '.$stueckordnerpfad.'</p>';
				
				# stuecksatzhandle lesen: Ordner der Saetze
				$satzindex=0;
				while ($stuecksatzhandle && (false !== ($stuecksatzfile = readdir($stuecksatzhandle))) ) 
				{
					if (!($stuecksatzfile === ".DS_Store"))
					{
						$regex ='/^\.{1,2}/'; # Punkte am Anfang entfernen
					
						# Satz des Stuecks
						
						$stuecksatz = preg_replace($regex,'',$stuecksatzfile);
					
						if (strlen($stuecksatz))
						{
							
							#print '<p>stuecksatzfile: '.$stuecksatzfile.'</p>';
							
							
							$satzdicarray = array("satz"=> $stuecksatz);
							
							
							if (count($satzdicarray)==1)
							{
								#print 'stuecksatz: '.$stuecksatz.' <br>Nur ein Satz<br>';
							}
							else
							{
							$satzdicarray["nummer"] = $stuecksatz[0];
							
							# Satz im Stueck zu register. Eventuell mehrere Stimmen
							# nummer steht an erster Stelle in stuecksatz
							#print 'stuecksatz: '.$stuecksatz.' nummer: '.$stuecksatz[0].'<br>';
							}
													
							# Pfad zum Ordner des Satzes
							$satzordnerpfad = $stueckordnerpfad.'/'.$stuecksatz.'/';
							if (is_dir($satzordnerpfad)) # Es hat einzelne Saetze mit registerstimmen im Ordner an Satzordnerpfad
							{
								#print '<p>Ordner: '.$satzordnerpfad.' ist Ordner</p>';
								if (($satzordnerhandle = opendir($satzordnerpfad)))
								{
									while ($satzordnerhandle && (false !== ($satzordnerfile = readdir($satzordnerhandle))) ) 
									{
										if (!($satzordnerfile === ".DS_Store"))
										{
											#print 'satzordnerfile: '.$satzordnerfile.'<br>';
											$regex ='/^\.{1,2}/'; # Punkte am Anfang entfernen

											$satzordner = preg_replace($regex,'',$satzordnerfile);
										
											# Stimmen im Satzordner lesen
											if (strlen($satzordner))
											{
												
												
												# stimme in Dic einsetzen
												$registerinit = strtoupper($register);
												
												# Register auswaehlen mit Initiale des Registers
												$registerinit0="";
												if ($registerinit &&count($registerinit))
												{
													#print 'satzordner: '.$satzordner.' registerinit[0]: '.$registerinit[0].'<br>';
													$registerinit0 = '_'.$registerinit[0].'_';
												
													#print 'satzordner: '.$satzordner.' registerinit[0]: '.$registerinit[0].'<br>';
												
													# neu 130822: Register 1, 2 detektieren
													$registerinit1 = '_'.$registerinit[0].'1_'; # nur Unterstrich vor Initiale
													$registerinit2 = '_'.$registerinit[0].'2_'; # nur Unterstrich vor Initiale
												
													# Fehlerkorrektur 140325:
													$registerinit3 = '_'.$registerinit[0].'.'; # Unterstrich vor Initiale, Punkt nachher 
												
												
													#print 'registerinit0: *'.$registerinit0.'* registerinit1: *'.$registerinit1.'* registerinit2: *'.$registerinit2.'* registerinit3: *'.$registerinit3.'*<br>';
												
												
												
													if (count($satzordner)&&(stripos($satzordner, $registerinit0))||(stripos($satzordner, $registerinit1)) || (stripos($satzordner, $registerinit2)))
													{
														#print 'satzordner rev: '.$satzordner.'<br>';
														$satzdicarray["stimme"][] = $satzordner;
													}
													elseif (stripos($satzordner, $registerinit3))
													{
														#print '<strong>Register stimmt: satzordner rev3: '.$satzordner.'</strong><br>';
														$satzdicarray["stimme"][] = $satzordner;
													}
												}
												#print '<br>';
											}# if strlen
										}
									}
								}
							
								# DicArray  mit Stimmen einsetzen
								$audiofiledicarray[] = $satzdicarray;
							}
						
							else # nur registerstimmen
							{
						
								$registerinit = strtolower($register);
								#print '<p>registerinit raw: '.$registerinit .' </p>';
								$registerinit = '_'.$registerinit[0].'_';
								#print '<p>registerinit rev 1: '.$registerinit .' </p>';
								if (stripos($stuecksatzsauber, $registerinit))
								{
									#$audiofileda =1;
									#print '<p>registerinit: '.$registerinit.' stuecksatzsauber da: '.$stueckordnerpfad.'/'.$stuecksatzsauber.'</p>';
									$audiofilearray[] = $stuecksatz;
								}
							}
							#print '<p>audiofiledicarray: <br>';
						#var_dump($audiofiledicarray);
						#print '<p>audiofiledicarray end <br>';
						}#if strlen
					}
				}#while stuecksatzhandle
			closedir($stuecksatzhandle);
			}
			else
			{
				print '<p>audio nicht da</p>';
			}
		
			#print 'audiofiledicarray:<br>';
		
			#var_dump($audiofiledicarray);
			$satzindex=0;
		
			#uksort($audiofiledicarray, "nummer");
			sort($audiofiledicarray);
		
		
			foreach($audiofiledicarray as $satzdic)
			{
				#Name des Satzes
				#print 'Satz: '.$satzdic["satz"].' index: '.$satzindex.' nummer: '.$satzdic["nummer"].'<br>';
			
				# Stimmen
				#foreach($satzdic["stimme"] as $stimmetitel)
				{
					#print 'stimme: '.$stimmetitel.'<br>';
				}
			
				$satzindex++;
			}
			
			# audiofilearray fuer stueck end
			#print 'stueckabschnitt start<div class = "stueckabschnitt">';
			if ((strpos($einzelstueck,'DS_Store')===false))
			{
			print '<div class = "stueckabschnitt">';
		
				#Stuecktitel setzen
				print '<div class = "stuecktitelabschnitt">';
				#print 'Stueck: '.$einzelstueck.'Store: '.strpos($einzelstueck,'DS_Store').'<br>';
				print '<h2 class = "stuecktitel">'.$einzelstueck.'*</h2>';
				print '</div>'; # stuecktitelabschnitt
				
					# Navigation Saetze aufbauen
					if (strlen($register)) # Register ist ausgewaehlt, Rueckkehr von GET
					{
				
					# neu
				
						print '<ul class="satz-nav">';
						$stueckindex=0;
						$satzindex=0;
						foreach($audiofiledicarray as $satzdic)
						{
							#Name des Satzes
							#print '<br>';
							#var_dump($satzdic);
							#print 'Satz: '.$satzdic["satz"].' ind: '.$satzindex.' nr: '.$satzdic["nummer"].' stimme: '.$satzdic["stimme"][0].'<br>';
							#print 'Satz: '.$satzdic["satz"].' <br>';
			
							# Stimmen
							if (count($satzdic["stimme"]))
							{
							$satzindex=0;
								foreach($satzdic["stimme"] as $stimmetitel)
								{
									print 'stimmetitel: '.$stimmetitel.'<br>';
									if (stripos($stimmetitel,"_mix"))
									{
										$mixangabe = ">>";
									}
									else
									{
										$mixangabe = ">>";
									}
									#print '<p>'.$mixangabe.'</p>';
							
						
									$satzindex++;
						
									$stueckplaypfad = $stueckordnerpfad.'/'.$satzdic["satz"].'/'.$stimmetitel;
									#print '<p> stueckplaypfad: '.$stueckplaypfad.'</p>';
									print '<li class = "satz-nav">';
									#print '<div class = "satzabschnitt">';
									#print '<p> satzabschnitt '.count($satzdic['stimme']).'</p>';
									if (count($satzdic['stimme'])>1)
									{
										print '<p>count > 1</p>';
										print '<div class = "satzregisterabschnitt">';
										print '<p class = "satztitel">**'.$register[0].' '.$satzindex.'</p>';
										print '</div>';	#satzregisterabschnitt
						
									}
										
										print '<div class = "satztitelabschnitt">';
											if ($satzdic["satz"] === "Satz")
											{
												$anzeigesatztitel = ">>";
											}
											else
											{
												$anzeigesatztitel = substr($satzdic["satz"],2);
											}
									
											if (count($satzdic['stimme'])>1)
											{
												#$anzeigesatztitel = $anzeigesatztitel.' '.$register.' '.$satzindex;
											}
									
											print '<p class = "satztitel">*'.$anzeigesatztitel.' ';
											print '<div class = "mixabschnitt">';
											
											print '<a class = "mix" href="chor.php?register='.$register.'&event='.$event['event'].'&stueck='.$einzelstueck.'&stimme='.$stimme.'&playpfad='.$stueckplaypfad.'">'.$mixangabe.'</a>';
											print '</p>';
											print '</div>';	#mixabschnitt
											
										print '</div>';	#satztitelabschnitt
									
								
									print '</li>';
									
						
								} # foreach stimmetitel
								#if (count($satzdic['stimme'])>1)
								{
								print '<br>';
								}
							} # if count
						} # foreach satzdic
					
					
					
						print '</ul>';
				#print '</div>';
					# end neu	
					
					
						# original
						print '<ul class="stueck-nav">';
						foreach($audiofilearray as $stimme)
						{
							#print ' <p>stimme: '.$stimme.'</p>';
							#$stueckbezeichnung = $audiofilearray[$stueckindex];
							#print ' <p>stueckbezeichnung: '.$stueckbezeichnung.'</p>';
							if (stripos($stimme,"_mix"))
							{
								$mixangabe = "mix";
							}
							else
							{
								$mixangabe = "solo";
							}
						
							$stueckplaypfad = $stueckordnerpfad.'/'.$stimme;	
							print '<li><a href="chor.php?register='.$register.'&event='.$event['event'].'&stueck='.$einzelstueck.'&stimme='.$stimme.'&playpfad='.$stueckplaypfad.'">'.$mixangabe.'</a></li>';
							$stueckindex++;
						}# foreach $audiofilearray as $einzelstueck
					
						# end original
					
					
						print '</ul>';
					}
					
				print '</div>'; # stueckabschnitt
				
				}
	#print '?? end';
			#print '</div>';
		}
		} # foreach $event["stuecke"] as $einzelstueck 

		print '</ul>';
		#print ' <p>stueckordnerpfad: '.$stueckordnerpfad.'</p>';
	print '</div>'; # *eventabschnitt end
	}
	
# Stueck setzen
?>
   		<script type="text/javascript">
   			//playerstueck = "../Data/kirchenchor/Konzert_2013/Missa/1_Kyrie/Kyrie_B_mix.mp3";
   			playerstueck  = "<?php echo $playpfad; ?>";
   			
		</script>
<?php
print '<p> playpfad: '.$playpfad.'</p>';
print '</div>'; # fileabschnitt

print '<div class = "audioabschnitt">	';

  #print ' <p>audioabschnitt</p>	';
	#print '	<h2 class = "audiotitel ">'.$stueck.'</h2>';
	if (strlen($playpfad))
	{
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
		
//		print '	<h2 class = "audiotitel ">'.$playstueck.': '.$aktuellersatz[1].'</h2>';
		print '	<h2 class = "audiotitel ">**'.$playstueck.': '.$aktuellersatztitel.'</h2>';
		
		#print '</div>';
		
		#foreach($audiofilearray as $stimme )
		{
	
			#print '<br><p> stimme: '.$stimme.' stück: '.$stueck.' pfad: :'.$audiopfad.$stimme.'</p>';
			#print '<li><a href="../Data/kirchenchor/'.$stimme.'">'.$stimme.'</a></li>';
			#print '	<li><a href="../index.php">Home</a></li>';

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
	

				
				#print ' <p>stueck: '.$playpfad.'</p>';
				#print '	<embed type="application/x-shockwave-flash" flashvars="audioUrl='.$playpfad.'" src="http://www.google.com/reader/ui/3523697345-audio-player.swf"  width="500" height="27" allowscriptaccess="never"  quality="best" ></embed>';

				#print '<br>';
				
				
				
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
				#print '	<h3 class = "link" ><i>Flashplayer laden</i></h3>';
		
	}
	$playstueck = "xyzabc";
	?>
	 
	<!--
	<script type="text/javascript">
		var stueck  = "<?php echo $playstueck; ?>";
    	document.write("Das Stueck heisst: "+ stueck);
  	</script>
     
    <script src="test.js" type="text/javascript">
    </script>
    <script type="text/javascript">
            document.write("Dies ist eine Ausgabe.");
    </script>
    -->
    <?php


print '	</div>'; # audioabschnitt



#print '</div>'; # <!-- audioabschnitt -->

?>








 	</div>
 	
   
    </body>
</html>
