<?
/*

chor rss dom
*/

/* verbinden mit db */
	
	$db=mysql_connect("localhost","ruediheimlicher","RivChuv4");
	/* nun ist der zugang zum db-server in der variable $db gespeichert */
	mysql_set_charset('utf8',$db);
	/* hier wird nun die eigentliche db ausgewählt */
	mysql_select_db("ruediheimlicher_kicho",$db); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Archiv RSS</title>
 
<link href="chor.css" rel="stylesheet" type="text/css" />
</head>

<body class="liste">
	

	
	


<div style = "margin-left: 20px;">
<h1 class="lernmedien">Chor RSS</h1>
	
<h2 class="lernmedien ">Admin rss</h2>
		
<?
print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

print '<form action="chor_admin.php" ><h3 class = "admin" ><input type="submit" class="links40" value="zurück zu Admin" name="textfile" style="width: 150px; margin-right:10px;"></h3></form>';

print '<hr style=" width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

# http://floern.com/webscripting/xml-in-php-mit-simplexml

print_r($_POST);
$task =  $_POST['task'];
#print 'task: '.$task.'<br>';
$registerarray = array('Sopran','Alt','Tenor','Bass');
$registeroption[]="Bass";

#print '<br>post register: '.$_POST['register'][0].'<br>';
if (strlen($_POST['register'][0]))
{
	if ($_POST['register'][0] == 'Alle')
	{
		#print '<br>post alle: <br>';
		$registeroption = $registerarray;
		#print_r($register);
	}
	else
	{
		#print '<br>post einzeln: <br>';
		$registeroption[0] = $_POST['register'][0];
	}
}


$audiofilearray = array();
$eventarray = array();
$werkarray = array();

$registerfeedarray = array();


$serverpfad = "http://www.ruediheimlicher.ch";
$kirchenchorpfad = $serverpfad.'/Data/kirchenchor';
$podcastname = 'Kirchenchor Dürnten';

$audiopfad = '../Data/kirchenchor';

$feedlink = 'feed://www.ruediheimlicher.ch/Data/kirchenchor_data/rss_feed/';
$playpfad="";


print '
<form action="" method = "POST">
  <p>
    <select name="register[]" >
      <option selected>Alle</option>
      <option>Sopran</option>
      <option>Alt</option>
      <option>Tenor</option>
      <option>Bass</option>
    </select>
  </p>
  <input type = "hidden" name = "task" value = "rss">
  <input type="submit"  value="rss anlegen" name="textfile" style="width: 100px; margin-left:10px;">
</form>';

# http://www.ruediheimlicher.ch/Data/kirchenchor_data/rss_feed/feed_dom.xml


$werk = "";
$event = "";


print '<h2>';
print 'Register: ';
foreach($registeroption as $register)
{
	print '&nbsp;'.$registerwahl.'&nbsp;';
}
print'</h2>';

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
	
				if ($werkhandle = opendir('../Data/kirchenchor/'.$filesauber.'/'))
				{
					$werkarray = array();
					while ($werkhandle && (false !== ($werkfile = readdir($werkhandle))) )
					{
					#print  '---werkfile: *'. $werkfile.'*<br>';
						$werksauber =  preg_replace($regex,'',$werkfile);
						
						if (strlen($werksauber))
						{
			
							#print  '---werk: *'. $werksauber.'*<br>'; # Ordner fuer Musikwerk
							$position = strpos($werksauber,"_");
							#print  'pos: "'.$position.'"*<br>';
							$werkarray[] = $werksauber;
							#array_push($eventdic,$werksauber);
						}
					}
					$eventdic["werke"] = $werkarray;
					#array_push($eventdic,$werkarray);
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
	
	} # while
	
} # opendir
closedir($handle);
# End Audiodaten holen

print 'eventarray:<br>';
print_r($eventarray);
print'<br>';


# Kopf des Feed aufbauen

#print'<pre>start dom<br>';
$xml_pfad = '../Data/kirchenchor_data/rss_feed/feed_leer.xml'; # Musterfeed ohne Items

if (!file_exists($xml_pfad)) 
{
	print '<h2>';
	print 'feed_leer nicht gefunden! ';
	print '</h2>';
	exit();
}

foreach($registeroption as $registerindex=>$register)

{
	print '<div class = "feedregisterabschnitt">';
	print '<br><h2 class = "feedregistertitel">Register: '.$register.'</h2>';
	$feed_dom = new DOMDocument('1.0', 'UTF-8');
	$feed_dom->preserveWhiteSpace = false;
	$feed_dom -> preserveWhiteSpace = false;
	$feed_dom->load($xml_pfad);

	$feed_dom-> formatOutput = true;

	# child 'channel' holen
	$channel = $feed_dom->getElementsByTagName('channel')->item(0);;

	# titelchild ersetzen
	$oldtitelnode  = $channel->getElementsByTagName('title')->item(0);
	$titelnode = $feed_dom->createElement('title',$podcastname.' '.date("Y").' '.$register);
	$channel-> replaceChild($titelnode, $oldtitelnode);

	# pubDate ersetzen
	$oldpubdatenode  = $channel->getElementsByTagName('pubDate')->item(0);
	$pubdatenode = $feed_dom->createElement('pubDate',date("D, j M Y H:i:s ").'GMT');
	$channel-> replaceChild($pubdatenode, $oldpubdatenode);

	# lastBuildDate ersetzen
	$oldlastdatenode  = $channel->getElementsByTagName('lastBuildDate')->item(0);
	$lastdatenode = $feed_dom->createElement('lastBuildDate',date("D, j M Y H:i:s ").'GMT');
	$channel-> replaceChild($lastdatenode, $oldlastdatenode);

	# copyright ersetzen
	$oldcrnode  = $channel->getElementsByTagName('copyright')->item(0);
	$crnode = $feed_dom->createElement('copyright','Copyright RH '.date("Y"));
	$channel-> replaceChild($crnode, $oldcrnode);


	foreach($eventarray as $event )
	{
		print '<div class = "feedeventabschnitt">';
		# Stimmen lesen
	
		#print '<div class = "eventabschnitt">';	
		#print '<p> eventabschnitt start</p>';
		# eventtitel setzen	
		
		#print '<br>Event: '.$event['event'].'<br>';
	
		# audiopfad aufbauen: Event anfuegen
		$eventaudiopfad = $audiopfad.'/'.$event['event'].'/';
	
		#print '<br>eventaudiopfad: '.$eventaudiopfad.'<br>';
	
		# url fuer Datei aufbauen
		$eventaudiourl =  $kirchenchorpfad.'/'.$event['event'].'/';
		#print '<br>eventaudiourl: '.$eventaudiourl.'<br>';
	
	
		# leerfeed laden
		#print 'leerfeed laden<br><br>';
	
		print '<h2 class = "feedtitel">Feed fuer Event: '.$event['event'].'</h2>';
	
		$beschreibung = "Podcast Kirchenchor Dürnten";
	
		$titel = 'Kirchenchor Dürnten '.$register; # Titel des Podcasts. Fuer jedes Register ein eigener Podcast



	
		$werkindex=0;

		# Ebene 1: werke

		foreach($event['werke'] as $werkindex=>$einzelwerk)
		{
			#print ' <p>werkabschnitt</p>';
			print '<div class = "feedwerkabschnitt">';
			# audiopfad fuer werk aufbauen:

			#print 'werkindex: '.$werkindex.' einzelwerk: '.$einzelwerk.'<br>';
			print '<h3 class = "feeduntertitel">Werk: '.$einzelwerk.'</h3>';

			# Pfad zum Ordner des Einzelwerks
			$werkordnerpfad = $eventaudiopfad.$einzelwerk;	
			#print ' werkordnerpfad: '.$werkordnerpfad.'<br>';
			$werkordnerurl = $eventaudiourl.$einzelwerk;
			#print ' werkordnerurl: '.$werkordnerurl.'<br>';
	
			#audiofilearray fuer werk > start
			$audiofilearray=array();
			$audiofileda=0;
			$audiopfadarray = array(); # Pfade der Audiofiles

			$audiofiledicarray=array(); # Pfade der Audiofiles in Satz-Ordnern

			if (($werksatzhandle = opendir($werkordnerpfad)))
			{
				#print '<p><br><br>audio da: '.$werkordnerpfad.'</p>';
		
				# werksatzhandle lesen: Ordner der Saetze
				$satzindex=0;
				while ($werksatzhandle && (false !== ($werksatzfile = readdir($werksatzhandle))) ) 
				{
					if (!($werksatzfile === ".DS_Store"))
					{
						$regex ='/^\.{1,2}/'; # Punkte am Anfang entfernen
			
						# Satz des werks
						$werksatz = preg_replace($regex,'',$werksatzfile);
			
						if (strlen($werksatz))
						{
							$satzdicarray = array("satz"=> $werksatz, "werk"=> $einzelwerk, "event"=> $event['event']);
				
							$satzdicarray["nummer"] = $werksatz[0];
							$satzdicarray["register"] = $register;
				
	#						print 'werksatz: '.$werksatz.' nummer: '.$werksatz[0].'<br>';
											
							# Pfad zum Ordner des Satzes
							$satzordnerpfad = $werkordnerpfad.'/'.$werksatz.'/';
							$satzordnerurl = $werkordnerurl.'/'.$werksatz.'/';
							#$satzdicarray["satzordnerurl"] = $satzordnerurl;
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
												$registerinit = '_'.$registerinit[0].'_'; # erster Buchstabe
												if (stripos($satzordner, $registerinit))
												{
													$satzdicarray["stimme"][] = $satzordner;
													$satzdicarray["stimmeurl"] = $satzordnerurl.$satzordner;
											
												}
											}# if strlen
										}
									}
								}
					
								# DicArray in Array einsetzen
								$audiofiledicarray[] = $satzdicarray;
							}
				
							else # nur registerstimmen
							{
				
								$registerinit = strtolower($register);
								$registerinit = '_'.$registerinit[$registerindex].'_';
								if (stripos($werksatzsauber, $registerinit))
								{
									#$audiofileda =1;
									#print '<p>registerinit: '.$registerinit.' werksatzsauber da: '.$werkordnerpfad.'/'.$werksatzsauber.'</p>';
									$audiofilearray[] = $werksatz;
								}
							}
				
						}#if strlen
					}
				}#while werksatzhandle
			closedir($werksatzhandle);
			}
			else
			{
				print '<p>audio nicht da</p>';
			}

			#print 'audiofiledicarray:<br>';
			#print_r($audiofiledicarray);
			#print'<br>';
		
			$satzindex=0;

			#uksort($audiofiledicarray, "nummer");
			sort($audiofiledicarray);
	
			# audiofilearray fuer werk end
			#print 'werkabschnitt start<div class = "werkabschnitt">';
			#print '<div class = "werkabschnitt">';

			#werktitel setzen
			#print '<div class = "werktitelabschnitt">';
			#print '<br> Einzelwerk: '.$einzelwerk.'<br>';
		
			#print '</div>'; # werktitelabschnitt
		
			# Navigation Saetze aufbauen
			if (strlen($register)) # Register ist ausgewaehlt, Rueckkehr von GET
			{

			# neu
				$werkindex=0;
				$satzindex=0;
				foreach($audiofiledicarray as $satzdic)
				{
					#Name des Satzes
					#print 'Satz: '.$satzdic["satz"].' ind: '.$satzindex.' nr: '.$satzdic["nummer"].'<br>';
					print 'Satz: '.$satzdic["satz"].' <br>';

					# Stimmen
					foreach($satzdic["stimme"] as $stimmetitel)
					{
						print '&nbsp;&nbsp;stimme: '.$stimmetitel.' ';
						if (stripos($stimmetitel,"_mix"))
						{
							$mixangabe = "mix";
						}
						else
						{
							$mixangabe = "solo";
						}
						print '&nbsp;&nbsp;mixangabe: '.$mixangabe.'<br>';
					}
		
					$satzindex++;
		
					$werkplaypfad = $werkordnerpfad.'/'.$satzdic["satz"].'/'.$stimmetitel;
					#print ' werkplaypfad: '.$werkplaypfad.'<br>';

					$werkurl = $werkordnerurl.'/'.$satzdic["satz"].'/'.$stimmetitel;
					#print ' werkurl: '.$werkurl.'<br>';
			
					#print '<div class = "satzabschnitt">';
					#print '<div class = "satztitelabschnitt">';
				
					if ($satzdic["satz"] === "Satz") # Stück besteht nur aus einem  Satz, Satzbezeichnung ist 'Satz'
					{
						$anzeigesatztitel = ">>";
					}
					else
					{
						$anzeigesatztitel = substr($satzdic["satz"],2); # Anzeige Satztitel ohne Nummer
					}
					print '&nbsp;&nbsp;satztitel: '.$anzeigesatztitel.'<br>';
				
				
					#print '<div class = "mixabschnitt">';
					#print '</div>';	#mixabschnitt
					#print '</div>';	#satztitelabschnitt
				} # foreach
	
				#print '</div>';
			# end neu	
	
	
			# original
			foreach($audiofilearray as $stimme)
			{
				#$werkbezeichnung = $audiofilearray[$werkindex];
				#print ' <p>werkbezeichnung: '.$werkbezeichnung.'</p>';
				if (stripos($stimme,"_mix"))
				{
					$mixangabe = "mix";
				}
				else
				{
					$mixangabe = "solo";
				}
	
				$werkplaypfad = $werkordnerpfad.'/'.$stimme;	
				#print '<a href="chor.php?register='.$register.'&event='.$event['event'].'&werk='.$einzelwerk.'&stimme='.$stimme.'&playpfad='.$werkplaypfad.'">'.$mixangabe.'</a>';
				$werkindex++;
			}# foreach $audiofilearray as $einzelwerk
	
			# end original
			}
			
				#print '</div>'; # werkabschnitt

			#print '</div>';

			# feed-item aufbauen
			# leeritem laden
			print '<br><h3 class = "feeddatentitel">Daten fuer Feed: '.$einzelwerk.'</h3>';
			#print '<br>audiofiledicarray: abarbeiten<br>';
			foreach($audiofiledicarray as $satzindex => $satzdic)
			{
				#print 'satzdic<br>';
				#print_r($satzdic);
				#print '<br>';
				#Name des Satzes
				print 'Satz: '.$satzdic["satz"].' index: '.$satzindex.' nummer: '.$satzdic["nummer"].' url: '.$satzdic["stimmeurl"].'<br>';

				# Stimmen
				#foreach($satzdic["stimme"] as $stimmetitel)
				{
					#print 'stimme: '.$stimmetitel.'<br>';
				}
			
				# feed-item start
			
				#print 'leeritem laden<br><br>';
				$satz = $satzdic["satz"];
				$stimmeurl = $satzdic["stimmeurl"];
				$registerinit = substr($satzdic["register"],0,1);
				$satz_blank = substr($satzdic["satz"],2);
				$satztitel = $satzdic["werk"].'_'.$satzdic["satz"].'_'.$registerinit;
				$beschreibung = $satzdic["werk"].' '.$satzdic["satz"].' '.$satzdic["register"];
				$length = 5000000;
			
				#print ' stimmeurl: '.$stimmeurl.' titel: '.$satztitel.'<br>';
				
				# nicht verwendet	
				#$itemxml_pfad = '../Data/kirchenchor_data/rss_feed/item_leer.xml'; # Muster-Item zum Anpassen
			
			
				{
					#print'itemxml_pfad: file ist da<br>';
					#print'satzindex: '.$satzindex.'<br>';
					#print'satztitel: '.$satztitel.'<br>';
					#print'<h3>werk: '.$einzelwerk.'</h3>';
				
					/*
					# Muster
					<item>
						<title>Erstanden ist der heilig Christ Sopran</title>
						<link>http://www.refduernten.ch/kirchenchor</link>
						<guid>http://www.ruediheimlicher.ch/Data/podcast/erstanden_ist/erstanden_ist_s_mix.mp3</guid>
						<description>Mozart Halleluja Sopran</description>
						<enclosure url="http://www.ruediheimlicher.ch/Data/podcast/erstanden_ist/erstanden_ist_s_mix.mp3" length="578060" type="audio/mpeg"/>
						<category>Podcasts</category>
						<pubDate>Wed, 13 Mar 2013 00:15:00 -0500</pubDate>

						<itunes:author>RH @ ref</itunes:author>

						<itunes:explicit>No</itunes:explicit>
						<itunes:subtitle>Erstanden ist der heilig Christ</itunes:subtitle>
						<itunes:summary>Sopran mix</itunes:summary>
						<itunes:duration>00:21:18</itunes:duration>
						<itunes:keywords>singen </itunes:keywords>
					</item>

					*/
					
					/*
					// Tip fuer iTunes: http://stackoverflow.com/questions/11612712/reading-itunes-xml-file-with-php-dom-method
					// Initialize XPath    
					$xpath = new DOMXpath( $doc);
					// Register the itunes namespace
					$xpath->registerNamespace( 'itunes', 'http://www.itunes.com/dtds/podcast-1.0.dtd');

					$items = $doc->getElementsByTagName('item');    
					foreach( $items as $item) {
						$title = $xpath->query( 'title', $item)->item(0)->nodeValue;
						$author = $xpath->query( 'itunes:author', $item)->item(0)->nodeValue;
						$enclosure = $xpath->query( 'enclosure', $item)->item(0);
						$url = $enclosure->attributes->getNamedItem('url')->value;

						echo "$title - $author - $url\n";
					}
					*/
					# channel von Feed holen
					$itemchannel = $feed_dom->getElementsByTagName('channel')->item(0);
				
					# Item einfuegen
					$itemnode = $feed_dom->createElement('item');
					$item = $channel-> appendChild($itemnode);
				
					# Elemente des Items einfuegen
					$itemtitelnode = $feed_dom->createElement('title',$satztitel);
					$item-> appendChild($itemtitelnode);
				
					$linknode = $feed_dom->createElement('link',$kirchenchorpfad);
					$item-> appendChild($linknode);

					$guidnode = $feed_dom->createElement('guid',$stimmeurl);							
					$item-> appendChild($guidnode);

					$beschreibungnode = $feed_dom->createElement('description',$stimmeurl);							
					$item-> appendChild($beschreibungnode);
				
					# enclosure einfuegen
					$encnode = $feed_dom->createElement('enclosure');							
					
						#Attribute in enclosuere einfuegen
						$urlattr = $feed_dom->createAttribute('url');
						$urlattr->value = $stimmeurl;// Value for the created attribute
						$encnode->appendChild($urlattr);

						$lengthattr = $feed_dom->createAttribute('length');
						$lengthattr->value = $length;// Value for the created attribute
						$encnode->appendChild($lengthattr);

						$typeattr = $feed_dom->createAttribute('type');
						$typeattr->value = 'audio/mpeg';// Value for the created attribute
						$encnode->appendChild($typeattr);
				
					# enclosuere anfuegen
					$item-> appendChild($encnode);
				
					$pubdatenode = $feed_dom->createElement('pubDate',date("D, j M Y H:i:s ").'GMT');					
					$item-> appendChild($pubdatenode);
			
				
			
				} # if item
			#item end



		}

		# end feed-item aufbauen

		print '</div>'; #feedwerkabschnitt

		} # foreach $event["werke"] as $einzelwerk 

		

			

		print '</div>'; # *feedeventabschnitt end
	} # foreach($eventarray as $event )
	print '</div>'; # feedregisterabschnitt
	
	$registerfeedlink = $feedlink.'feed_'.strtolower($register).'.xml';
	$registerfeedarray[] = $registerfeedlink;

	$feed_dom->saveXML() . "\n";
		
	$feed_dom-> formatOutput = true;

	$feed_dom->save('../Data/kirchenchor_data/rss_feed/feed_'.strtolower($register).'.xml');

} # for register

print_r($registerfeedarray);


print'<br>feedlink: '.$feedlink.'<br>';

#$feeddatei = fopen('../Data/kirchenchor_data/rss_feed/Kirchenchor_Feed_'.$register.'.doc',"w");
$feeddatei = fopen('../Data/kirchenchor_data/rss_feed/Kirchenchor_Feed.doc',"w");

$feedtext = '
PODCAST EINRICHTEN 

Vorgehen zum Abonnieren des Podcasts:
- Feed-Link fuer das gewuenschte Register kopieren (GANZE Zeile)
- Im Podcast-Programm die Funktion zum Abonnieren eines Podcasts aufrufen
	Mac:	In iTunes: Ablage-> Podcast abonnieren
	PC:	in iTunes: gleich wie Mac';

foreach($registerfeedarray as $index=>$registerfeedzeile)
{
$feedtext = $feedtext.'

'.$registerarray[$index].': 
------------------------------------------------------------------------------
'.$registerfeedzeile.'
------------------------------------------------------------------------------
';
}
$feedtext = $feedtext.'

Viel Erfolg
Ruedi Heimlicher';



fwrite($feeddatei, $feedtext);
fclose($feeddatei);



#print'<pre>';
#print '<div style = "margin-top:150px;">';
#print 'start feed<br><br>';

$datapfad = "http://www.ruediheimlicher.ch/Data/podcast/";



$beschreibung = "Podcast Kicho Dürnten";
$titel = 'Kirchenchor Dürnten '.$register[$registerindex];

$itemtitel = "Te_Deum";
$itemkomponist = "Mozart";



# leerfeed laden


if ($task == 'new')
{
#$item = 


} # if new

#print'</div>';
?>


</body>

</html>
