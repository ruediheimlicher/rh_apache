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
	header("location:choradmin.php?upda=1");
}
if (isset($_POST['upda']))
{
	$upda=1;
	header("location:choradmin.php");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"content="text/html;charset=UTF-8"/>
<link href="chor.css"rel="stylesheet"type="text/css"/>
</head>
<body class="admin">
<?
?>
<div id="container">
<title>Kirchenchor</title>
<?
#POST abfragen
print_r($_POST);
#print_r($_GET);
print '<br>sql_upload: '.$sql_upload.'<br>';
print 'upda: '.$upda.'<br>';

$task =  $_POST['task'];
print 'task: '.$task.'<br>';

$result_home = mysql_query("SELECT * FROM settings WHERE id= 0", $db)or die(print '<p  >Beim Suchen nach home ist ein Fehler passiert: '.mysql_error().'</p>');
print mysql_error();
$stat = mysql_fetch_array($result_home);
print 'home_ip: '.$stat['home_ip'].'<br>';
while ($stat = mysql_fetch_array($result_home) )
{
#print_r($stat);
}
#print_r($home_ip['home_ip']);

$ip=$_SERVER['REMOTE_ADDR'];
$zeit = $_SERVER['REQUEST_TIME'];

#print 'ip: '.$ip.'<br>';
#print 'zeit: '.$zeit.'<br>';

$datum = date("d.m.Y",$zeit);
$uhrzeit = date("H:i",$zeit);
#echo $datum,"  ",$uhrzeit," Uhr";

$besucher = get_current_user();

$result_besuche = mysql_query("SELECT * FROM besucher WHERE ip  = '$ip'", $db)or die(print '<p  >Beim Suchen nach IP ist ein Fehler passiert: '.mysql_error().'</p>');
print mysql_error();
#$statistik = mysql_fetch_assoc($result_besuche);
 
#$besuche = $statistik['besuche'];
#print 'besuche: '.$besuche.'<br>';

#print 'anzahl: '.mysql_num_rows($result_besuche).'<br>';

$besucherarray = array();

# array der ip gleich wie $ip
while ($stat = mysql_fetch_array($result_besuche) )
{
	if (($stat['ip'] == $ip) && !(in_array($ip,$besucherarray)) )
	{
		$besucherarray[]=$stat['besuch'];
	}
}
$anzahlbesuche = $besucherarray[0]+1;
#print 'besuche: '.$anzahlbesuche.'<br>';

if (count($besucherarray))
{
	mysql_query("UPDATE besucher SET besuch = '$anzahlbesuche' ,zeit = '$uhrzeit', datum = '$datum'  WHERE ip = '$ip'"); 
}
else
{
	$besuche = 1;
	$result_insert = mysql_query("INSERT INTO besucher (id,name,besuch,ip,zeit,datum) VALUES 		(NULL,'$besucher','$besuche','$ip','$zeit','$datum')");
}


if ($sql_upload ==1)
{
	print 'upload start<br>'; 
#header("location: choradmin.php?sql_upload=0");
	#$result_insert = mysql_query("INSERT INTO lernmedien (id, name, beschreibung, teaser, art, gruppe, preis, stufe, nummer) VALUES (NULL, '$name', '$beschreibung', '$teaser', '$art', '$gruppe', '$preis', '$stufe', '$nummer')");
	print 'upload end<br>'; 
}	

print '<form action="chor_sql.php"method="POST">';
print ' <input type="hidden"name="sql_upload"value="1"/>';
print ' <input type="submit"name="up"value="SQL"/>';
print '</form>';
print '<form action="chor_sql_save.php"method="POST">';
print ' <input type="hidden"name="sql_upload"value="1"/>';
print ' <input type="submit"name="up"value="SQL save"/>';
print '</form>';	
	
$resultat=mysql_affected_rows($db);

#$result_insert = mysql_query("INSERT INTO besucher (id,name,besuch,ip) VALUES (NULL,'$besucher','$besuche+1','$ip')");
	
$resultat=mysql_affected_rows($db);
#print 'INSERT error: *'.mysql_error().'*<br>';
#print 'resultat affected_rows: *'.$resultat.'*<br>';


$pfadfehler=0;
$audiopfad = "../Data/kirchenchor";
$playpfad="";
$register="";
$register = $_GET['register'];

$stueck = "";
$event = "";
$playpfad = "";
if ($_GET['playpfad'])
{
	$playpfad = $_GET['playpfad'];
	$pfadfehler = 1;
	$audiofileda=1;
		 if (strlen($_GET['stueck']))
		{
			#print '<br>playstueck da ';
			$playstueck = $_GET['stueck'];
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
		 if (strlen($_GET['stimme']))
		{
			#print '<br>stueck da ';
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
#print '<br>audiopfad: '.$audiopfad.' pfadfehler: '.$pfadfehler.'<br>';
#print '<br>playpfad: '.$playpfad.'<br>';
$task = $_POST['task'];	
#print 'task: '.$task.'<br>';

$index = $_POST['index'];	
$name = $_POST['name'];
$task="";
if (isset($_POST['task']))
{
	$task = $_POST['task'];	
}

$registernamenarray = array("Sopran","Alt","Tenor","Bass");
?>

<div id="mainContent">
      
	<h1 class = "titel"> Kirchenchor Dürnten</h1>
   
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

<?
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

<? 	
	
#print ' <div class = "verzeichnisabschnitt">';
#print '<p>verzeichnisabschnitt</p>';
# Audiodaten holen 
$audiofilearray = array();
$eventarray = array();
$stueckarray = array();



if ($handle = opendir('../Data/kirchenchor/')) 
{
	/* Audiodaten holen */
	
	/*NAmen der Events lesen*/

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
	#print_r($eventarray[0]);
	#print '<br>';
	#print 'audiofilearray<br>';
	#print_r($audiofilearray);
	#print '<br>';
	#print '<p>fileabschnitt</p>';
	#print '<ul class="main-nav">';
	print '<h2 class = "menutitel">Event</h2>';
	
	# Eventmenu aufbauen
	
	foreach($eventarray as $event )
	{
		# Stimmen lesen
		print '<div class = "eventabschnitt">';	
		#print '<p> eventabschnitt start</p>';
		# eventtitel setzen		
		print '<h2 class = "eventtitel ">'.$event['event'].'</h2>';
		
		# audiopfad aufbauen: Event anfuegen
		$eventaudiopfad = $audiopfad.'/'.$event['event'].'/';
		
		# navigation aufbauen
		print '<ul class="stueck-nav">';
		
		$stueckindex=0;
		
		# Ebene 1: Stuecke
		
		foreach($event['stuecke'] as $einzelstueck)
		{
			#print ' <p>stueckabschnitt</p>';
#			print '<div class = "stueckabschnitt">stueckabschnitt start';
			# audiopfad fuer stueck aufbauen:
		
#			print '<p>einzelstueck: '.$einzelstueck.'</p>';

			# Pfad zum Ordner des Einzelstuecks
			$stueckordnerpfad = $eventaudiopfad.$einzelstueck;	
		
							
			#audiofilearray fuer stueck > start
			$audiofilearray=array();
			$audiofileda=0;
			$audiopfadarray = array(); # Pfade der Audiofiles

			$audiofiledicarray=array(); # Pfade der Audiofiles in Satz-Ordnern
		
			if (($stuecksatzhandle = opendir($stueckordnerpfad)))
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
							$satzdicarray = array("satz"=> $stuecksatz);
						
							$satzdicarray["nummer"] = $stuecksatz[0];
						
#							print 'stuecksatz: '.$stuecksatz.' nummer: '.$stuecksatz[0].'<br>';
													
							# Pfad zum Ordner des Satzes
							$satzordnerpfad = $stueckordnerpfad.'/'.$stuecksatz.'/';
							if (is_dir($satzordnerpfad)) # Es hat einzelne Saetze mit registerstimmen
							{
								#print '<p>Ordner: '.$satzordnerpfad.' ist Ordner</p>';
								if (($satzordnerhandle = opendir($satzordnerpfad)))
								{
									while ($satzordnerhandle && (false !== ($satzordnerfile = readdir($satzordnerhandle))) ) 
									{
										if (!($satzordnerfile === ".DS_Store"))
										{
											$regex ='/^\.{1,2}/'; # Punkte am Anfang entfernen

											$satzordner = preg_replace($regex,'',$satzordnerfile);
										
											# Stimmen im Satzordner lesen
											if (strlen($satzordner))
											{
												#print '<p>so: '.$satzordner.'</p>';

												# stimme in Dic einsetzen
												$registerinit = strtolower($register);
												$registerinit = '_'.$registerinit[0].'_';
												if (stripos($satzordner, $registerinit))
												{
													$satzdicarray["stimme"][] = $satzordner;
												}
											}# if strlen
										}
									}
								}
							
								# DicArray einsetzen
								$audiofiledicarray[] = $satzdicarray;
							}
						
							else # nur registerstimmen
							{
						
								$registerinit = strtolower($register);
								$registerinit = '_'.$registerinit[0].'_';
								if (stripos($stuecksatzsauber, $registerinit))
								{
									#$audiofileda =1;
									#print '<p>registerinit: '.$registerinit.' stuecksatzsauber da: '.$stueckordnerpfad.'/'.$stuecksatzsauber.'</p>';
									$audiofilearray[] = $stuecksatz;
								}
							}
						
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
		
			#print_r($audiofiledicarray);
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
			
			print '<div class = "stueckabschnitt">';
		
				#Stuecktitel setzen
				print '<div class = "stuecktitelabschnitt">';
					print '<h2 class = "stuecktitel">'.$einzelstueck.'</h2>';
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
							#print 'Satz: '.$satzdic["satz"].' ind: '.$satzindex.' nr: '.$satzdic["nummer"].'<br>';
							#print 'Satz: '.$satzdic["satz"].' <br>';
			
							# Stimmen
							foreach($satzdic["stimme"] as $stimmetitel)
							{
								#print 'st: '.$stimmetitel.'<br>';
								if (stripos($stimmetitel,"_mix"))
								{
									$mixangabe = "mix";
								}
								else
								{
									$mixangabe = "solo";
								}
								#print '<p>'.$mixangabe.'</p>';
							}
						
							$satzindex++;
						
							$stueckplaypfad = $stueckordnerpfad.'/'.$satzdic["satz"].'/'.$stimmetitel;
							#print '<p> stueckplaypfad: '.$stueckplaypfad.'</p>';
							print '<li class = "satz-nav">';
							#print '<div class = "satzabschnitt">';
					#print '<p> satzabschnitt</p>';
							print '<div class = "satztitelabschnitt">';
								if ($satzdic["satz"] === "Satz")
								{
									$anzeigesatztitel = ">>";
								}
								else
								{
									$anzeigesatztitel = substr($satzdic["satz"],2);
								}
								print '<p class = "satztitel">'.$anzeigesatztitel.' ';
								print '<div class = "mixabschnitt">';
									print '<a href="chor.php?register='.$register.'&event='.$event['event'].'&stueck='.$einzelstueck.'&stimme='.$stimme.'&playpfad='.$stueckplaypfad.'">'.$mixangabe.'</a></p>';
								print '</div>';	#mixabschnitt
								print '</div>';	#satztitelabschnitt
							print '</li>';
						} # foreach
					
						print '</ul>';
				#print '</div>';
					# end neu	
					
					
						# original
						print '<ul class="stueck-nav">';
						foreach($audiofilearray as $stimme)
						{
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
	#print '?? end';
			#print '</div>';
		
		} # foreach $event["stuecke"] as $einzelstueck 

		print '</ul>';
		//print ' <p>stueckordnerpfad: '.$stueckordnerpfad.'</p>';
	print '</div>'; # *eventabschnitt end
	}

print '</div>'; # fileabschnitt

print '<div class = "audioabschnitt">	';

#print ' <p>audioabschnitt</p>	';

	#print '	<h2 class = "audiotitel ">'.$stueck.'</h2>';
	if (strlen($playpfad))
	{
		$index=0;
		print '	<h2 class = "audiotitel ">'.$playstueck.'</h2>';
		
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
				#print ' <p>stueck: '.$playpfad.'</p>';
				print '	<embed type="application/x-shockwave-flash" flashvars="audioUrl='.$playpfad.'" src="http://www.google.com/reader/ui/3523697345-audio-player.swf"  width="500" height="27" allowscriptaccess="never"  quality="best" ></embed>';
				
						print '	<h3 class = "erklaerung ">Mit der Maus lässt sich die Abspielposition verschieben. Ein Mausklick an eine Stelle verschiebt die Position dorthin.<br>Mit dem Schieber rechts lässt sich die Lautstärke anpassen.</h3>';

				
			print '</div>'; # stimmeabschnitt
		}

	}# if audiofileda
	else
	{
		print '	<h3 class = "erklaerung ">Wähle zuerst oben das Register aus.<br>Klicke dann beim gewünschten Stück auf die Solo- oder die Mix-Taste.<br>Der Player erscheint. Drücke nun auf die Starttaste. <br></h3>';
		print '	<br><h3 class = "erklaerung ">Wenn der Player nicht erscheint oder eine Warnung angezeigt wird, kannst du ihn mit diesem Link herunterladen: <a href = http://www.adobe.com/go/getflashplayer ><strong><i>Flashplayer</i></strong></a></h3>';
		
	}

print '	</div>'; # audioabschnitt



#print '</div>'; # <!-- audioabschnitt -->
?>





<?
?>



 	</div>
 	
   
    </body>
</html>
