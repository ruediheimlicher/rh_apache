<?
/*

Admin confirm Datenbank chor
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
function toXML($data, $rootNodeName = 'data', $xml=null)
	{
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1)
		{
			ini_set ('zend.ze1_compatibility_mode', 0);
		}
 
		if ($xml == null)
		{
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}
 
		// loop through the data passed in.
		foreach($data as $key => $value)
		{
			// no numeric keys in our xml please!
			if (is_numeric($key))
			{
				// make string key...
				$key = "unknownNode_". (string) $key;
			}
 
			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z]/i', '', $key);
 
			// if there is another array found recrusively call this function
			if (is_array($value))
			{
				$node = $xml->addChild($key);
				// recrusive call.
				ArrayToXML::toXml($value, $rootNodeName, $node);
			}
			else 
			{
				// add single node.
                                $value = htmlentities($value);
				$xml->addChild($key,$value);
			}
 
		}
		// pass back as string. or simple xml object if you want!
		return $xml->asXML();
	}

	
	
	
   function toArray(SimpleXMLElement $xml) 
   {
   	#http://de3.php.net/simplexml
        $array = (array)$xml;

        foreach ( array_slice($array, 0) as $key => $value ) {
            if ( $value instanceof SimpleXMLElement ) {
                $array[$key] = empty($value) ? NULL : toArray($value);
            }
        }
        return $array;
    }
    
function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
    
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
    
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}

function objectsIntoArray_simple($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
    
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
    
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) 
        {
            #if (is_object($value) || is_array($value)) 
            #{
            #    $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            #}
            if (in_array($index, $arrSkipIndices)) 
            {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}


    
     /* 
  * James Earlywine - July 20th 2011 
  * 
  * Translates a jagged associative array 
  * to XML 
  * 
  * @param : $theArray - The jagged Associative Array 
  * @param : $tabCount - for persisting tab count across recursive function calls 
  */ 
function assocToXML ($theArray, $tabCount=2) 
{ 
    //echo "The Array: "; 
    //var_dump($theArray); 
    // variables for making the XML output easier to read 
    // with human eyes, with tabs delineating nested relationships, etc. 
    
    $tabCount++; 
    $tabSpace = ""; 
    $extraTabSpace = ""; 
     for ($i = 0; $i<$tabCount; $i++) { 
        $tabSpace .= "\t"; 
     } 
     
     for ($i = 0; $i<$tabCount+1; $i++) { 
        $extraTabSpace .= "\t"; 
     } 
     
     
    // parse the array for data and output xml 
    foreach($theArray as $tag => $val) { 
        if (!is_array($val)) { 
            $theXML .= PHP_EOL.$tabSpace.'<'.$tag.'>'.htmlentities($val).'</'.$tag.'>'; 
        } else { 
            $tabCount++; 
            $theXML .= PHP_EOL.$extraTabSpace.'<'.$tag.'>'.assocToXML($val, $tabCount); 
            $theXML .= PHP_EOL.$extraTabSpace.'</'.$tag.'>'; 
        } 
    } 
    
return $theXML; 
} 

# http://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml
// function defination to convert array to xml
function array_to_xml($student_info, &$xml_student_info) 
{
    foreach($student_info as $key => $value) 
    {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_student_info->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else{
                array_to_xml($value, $xml_student_info);
            }
        }
        else {
            $xml_student_info->addChild("$key","$value");
        }
    }
}


function array_to_xml_simple($student_info, &$xml_student_info) 
{
    foreach($student_info as $key => $value) 
    {
        print 'key: '.$key.' value: '.$value.'<br>';
        if(is_array($value)) 
        {
            {
                array_to_xml($value, $xml_student_info);
            }
        }
        else {
            $xml_student_info->addChild("$key","$value");
        }
       
    }
}



	print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form action="chor_admin.php" ><h3 class = "admin" ><input type="submit" class="links40" value="zurück zu Admin" name="textfile" style="width: 150px; margin-right:10px;"></h3></form>';

	print '<hr style=" width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

# http://floern.com/webscripting/xml-in-php-mit-simplexml

print_r($_POST);
$task =  $_POST['task'];
#print 'task: '.$task.'<br>';

$register="bass";
if (strlen($_POST['register']))
{
	$register = $_POST['register'];
}
#print 'taskradio: '.$taskradio.'<br>';
$changeoption = $_POST['changeoption'];
#print 'changeoption: '.$changeoption.'<br>';


$audiofilearray = array();
$eventarray = array();
$werkarray = array();

$serverpfad = "http://www.ruediheimlicher.ch";
$kirchenchorpfad = $serverpfad.'/Data/kirchenchor';

$audiopfad = '../Data/kirchenchor';


$feedlink = "../Data/kirchenchor";
$playpfad="";


print '
<form action="" method = "POST">
  <p>
    <select name="register[]"  multiple="multiple">
      <option>Sopran</option>
      <option>Alt</option>
      <option>Tenor</option>
      <option>Bass</option>
    </select>
  </p>
  <input type = "hidden" name = "task" value = "rss">
  <input type="submit"  value="rss anlegen" name="textfile" style="width: 100px; margin-left:10px;">
</form>';



$werk = "";
$event = "";
print 'Register: ';
foreach($register as $registerwahl)
{
print '&nbsp;'.$registerwahl.' ';
}
print'<br>';

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
	
}
closedir($handle);
# End Audiodaten holen


	# Eventmenu aufbauen
	
	foreach($eventarray as $event )
	{
		# Stimmen lesen
#		print '<div class = "eventabschnitt">';	
		#print '<p> eventabschnitt start</p>';
		# eventtitel setzen	
			
		#print '<br>Event: '.$event['event'].'<br>';
		
		# audiopfad aufbauen: Event anfuegen
		$eventaudiopfad = $audiopfad.'/'.$event['event'].'/';
		
		#print '<br>eventaudiopfad: '.$eventaudiopfad.'<br>';
		
		$eventaudiourl =  $kirchenchorpfad.'/'.$event['event'].'/';
		#print '<br>eventaudiourl: '.$eventaudiourl.'<br>';
		
		
		# leerfeed laden
		
		$beschreibung = "Podcast Kicho Dürnten";
		$titel = 'Kirchenchor Dürnten '.$register[0];


		print 'leerfeed laden<br><br>';
		print '<h3>Feed fuer Event: '.$event['event'].'</h3>';

		$xml_pfad = '../Data/kirchenchor_data/rss_feed/feed_leer.txt';

		print'xml_pfad: '.$xml_pfad.'<br>';
		if (file_exists($xml_pfad)) 
		{
			print'xml_pfad: file ist da<br>';
			$feed_handle = fopen($xml_pfad,"r+");
			
			$zeile = fgets($feed_handle);
			explode('\n',$zeile);
			$feedarray = array();
			while ($zeile = fgets($feed_handle)) 
  			{
   				 print'zeile: '.$zeile.'<br>';
   				 if (strlen($zeile))
   				 {
   				 	$feedarray[] = $zeile;
   				 }
  			}
			fclose($feed_handle);
			
			
			
			print'<pre>start feed raw<br>';
			print_r($feed_handle);
			print'end feed raw</pre>';
			
			$feed_xml_import = simplexml_load_file($xml_pfad);
			$feed_xml = new SimpleXMLElement($feed_xml_import->asXML());
			print'<pre>start feed raw<br>';
			print_r($feed_xml);
			print'end feed raw</pre>';
	
			$feed_xml->channel->title = $titel;
			$feed_xml->channel->link = $kirchenchorpfad;
			$feed_xml->channel->lastBuildDate = date("D, j M Y H:i:s ").'GMT';
	
			$feed_xml->channel->copyright= 'Copyright RH '.date("Y");
			#$feed_xml->channel->'itunes:subtitle' = 'Stimmen für den Chor';
			$feed_xml->channel->pubDate= date("D, j M Y H:i:s ").'GMT';
			print 'datum: '.date("D, j M Y H:i:s ").'GMT<br>' ;
	
			$zeile = $feed_xml->channel->link; # works
			#print 'zeile: '.$zeile.'<br>';
			#$xmlarray = (array)$xml;
			#print'<pre>';
			#print_r($xmlarray['channel']); # works
			#print'</pre>';

			#$channelarray = (array)$xmlarray['channel']; # works
			#print'<pre>';
			#print_r($channelarray['title']);
			#print 'Titel: '.$channelarray['title'].'<br>';
			#print 'link: '.$channelarray['link'].'<br>';
	
			#print 'link simple: '.$xml->channel.'<br>';
			print'<pre>start feed mod<br>';
			print_r($feed_xml);
			print'end feed mod</pre>';
			
			print'</pre>';
		}
		# navigation aufbauen
#		print '<ul class="werk-nav">';
		
		$werkindex=0;
		
		# Ebene 1: werke
		
		foreach($event['werke'] as $werkindex=>$einzelwerk)
		{
			#print ' <p>werkabschnitt</p>';
#			print '<div class = "werkabschnitt">werkabschnitt start';
			# audiopfad fuer werk aufbauen:
		
			print 'werkindex: '.$werkindex.' einzelwerk: '.$einzelwerk.'<br>';

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
						
#							print 'werksatz: '.$werksatz.' nummer: '.$werksatz[0].'<br>';
													
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
												$registerinit = strtolower($register[0]);
												$registerinit = '_'.$registerinit[0].'_';
												if (stripos($satzordner, $registerinit))
												{
													$satzdicarray["stimme"][] = $satzordner;
													$satzdicarray["stimmeurl"] = $satzordnerurl.$satzordner;
													
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
			$satzindex=0;
		
			#uksort($audiofiledicarray, "nummer");
			sort($audiofiledicarray);
		
			
			# audiofilearray fuer werk end
			#print 'werkabschnitt start<div class = "werkabschnitt">';
			
			#print '<div class = "werkabschnitt">';
		
				#werktitel setzen
				#print '<div class = "werktitelabschnitt">';
					print '<br> Einzelwerk: '.$einzelwerk.'<br>';
				#print '</div>'; # werktitelabschnitt
				
					# Navigation Saetze aufbauen
					if (strlen($register[0])) # Register ist ausgewaehlt, Rueckkehr von GET
					{
				
					# neu
				
			#			print '<ul class="satz-nav">';
						$werkindex=0;
						$satzindex=0;
						foreach($audiofiledicarray as $satzdic)
						{
							#Name des Satzes
							#print 'Satz: '.$satzdic["satz"].' ind: '.$satzindex.' nr: '.$satzdic["nummer"].'<br>';
							print '<br>Satz: '.$satzdic["satz"].' <br>';
			
							# Stimmen
							foreach($satzdic["stimme"] as $stimmetitel)
							{
								print 'stimme: '.$stimmetitel.' ';
								if (stripos($stimmetitel,"_mix"))
								{
									$mixangabe = "mix";
								}
								else
								{
									$mixangabe = "solo";
								}
								print 'mixangabe: '.$mixangabe.'<br>';
							}
						
							$satzindex++;
						
							$werkplaypfad = $werkordnerpfad.'/'.$satzdic["satz"].'/'.$stimmetitel;
							#print ' werkplaypfad: '.$werkplaypfad.'<br>';

							$werkurl = $werkordnerurl.'/'.$satzdic["satz"].'/'.$stimmetitel;
							print ' werkurl: '.$werkurl.'<br>';
							
							
							#print '<li class = "satz-nav">';
							#print '<div class = "satzabschnitt">';
					#print '<p> satzabschnitt</p>';
							#print '<div class = "satztitelabschnitt">';
								if ($satzdic["satz"] === "Satz")
								{
									$anzeigesatztitel = ">>";
								}
								else
								{
									$anzeigesatztitel = substr($satzdic["satz"],2);
								}
								print 'satztitel: '.$anzeigesatztitel.'<br>';
								#print '<div class = "mixabschnitt">';
									#print '<a href="chor.php?register='.$register.'&event='.$event['event'].'&werk='.$einzelwerk.'&stimme='.$stimme.'&playpfad='.$werkplaypfad.'">'.$mixangabe.'</a></p>';
								#print '</div>';	#mixabschnitt
								#print '</div>';	#satztitelabschnitt
							#print '</li>';
						} # foreach
					
				#		print '</ul>';
				#print '</div>';
					# end neu	
					
					
						# original
						#print '<ul class="werk-nav">';
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
					
					
						#print '</ul>';
					}
					
				#print '</div>'; # werkabschnitt

			#print '</div>';
		
		# feed-item aufbauen
		# leeritem laden

		print '<br>audiofiledicarray: abarbeiten<br>';
		foreach($audiofiledicarray as $satzindex => $satzdic)
		{
				#print_r($satzdic);
				#print '<br>';
				#Name des Satzes
			#print 'Satz: '.$satzdic["satz"].' index: '.$satzindex.' nummer: '.$satzdic["nummer"].'<br>';
			
				# Stimmen
				#foreach($satzdic["stimme"] as $stimmetitel)
				{
					#print 'stimme: '.$stimmetitel.'<br>';
				}
			
				$satzindex++;
		

				print 'leeritem laden<br><br>';
				#print '<p> werkurl: '.$werkurl.'</p>';
				$itemxml_pfad = '../Data/kirchenchor_data/rss_feed/item_leer.xml';
				if (file_exists($itemxml_pfad)) 
				{
					print'itemxml_pfad: file ist da<br>';
					print'satzindex: '.$satzindex.'<br>';
					print'<h3>werk: '.$einzelwerk.'</h3>';
					#$itemxmlimport = simplexml_load_file($itemxml_pfad);
					#$itemxml = new SimpleXMLElement($itemxmlimport->asXML());
					
					
					$itemxml = $feed_xml->channel->addChild('item');
					$itemxml->addChild('title',$einzelwerk);
					$guid = $satzdic['stimmeurl'];
					$itemxml->addChild('guid',$guid);
					$pubdate = date("D, j M Y H:i:s ").'GMT';
					$itemxml->addChild('pubDate',$pubdate);
					$description = $einzelwerk.' '.$satzdic['satz'];
					$itemxml->addChild('description',$description);
					$itemxml->addChild('link','http://www.refduernten.ch/kirchenchor');
					$itemxml->addChild('category',"podcasts");
					$autor = 'itunes:author';
					$itemxml->addChild($author,$description);
					
			 		
					
					
					
					/*
					<item>
						<title>Halleluja</title>
						<link>http://www.refduernten.ch/kirchenchor</link>
						<guid>http://www.ruediheimlicher.ch/Data/podcast/halleluia/halleluia_s.mp3</guid>
						<description>Mozart Halleluja Sopran</description>
						<enclosure url="http://www.ruediheimlicher.ch/Data/podcast/halleluia/halleluia_s.mp3" length="1559531" type="audio/mpeg"/>
						<category>Podcasts</category>
						<pubDate>Wed, 13 Mar 2013 00:15:00 -0500</pubDate>

						<itunes:author>RH @ ref</itunes:author>

						<itunes:explicit>No</itunes:explicit>
						<itunes:subtitle>Mozart Halleluja</itunes:subtitle>
						<itunes:summary>Sopran solo</itunes:summary>
						<itunes:duration>00:21:18</itunes:duration>
						<itunes:keywords>singen </itunes:keywords>
					</item>


					*/
					/*
					#$itemxml = simplexml_load_file($itemxml_pfad);
					$feed_xml->addChild('title',$einzelwerk);
					$guid = $satzdic['stimmeurl'];
					$feed_xml->addChild('guid',$guid);
					$pubdate =  date("D, j M Y H:i:s ").'GMT';
					
					#$pubdate = 'hall0';
					$feed_xml->addChild('pubDate',$pubdate);
					$description = $einzelwerk.' '.$satzdic['satz'];
					#$feed_xml->channel->addChild('description',$description);
					
					*/
					/*
					$itemxml->item->title = $einzelwerk;
					$itemxml->item->guid = $satzdic['stimmeurl'];
					$itemxml->item->pubDate= date("D, j M Y H:i:s ").'GMT';
					#$itemxml->item->enclosure->attributes()->url = $satzdic['stimmeurl'];
					$itemxml->item->description = $einzelwerk.' '.$satzdic['satz'];
					$itemxml->asXML('../Data/kirchenchor_data/rss_feed/item_mod1_'.$satzdic['satz'].$satzindex.'.xml');
					*/
					#print'<pre>start item mod '.$satzindex.'<br>';
					#print_r($itemxml);
					#print'end item mod</pre>';
					
					#$feed_xml->channel->addChild('item', $itemxml);
					
					#$feed_xml->channel->addChild('item', "Hallo");
					#$itemsNode = $feed_xml->channel;
					#$itemsNode->addChild('item', $itemxml);
					
					$feed_xml->asXML('../Data/kirchenchor_data/rss_feed/feed_mod1.xml');
				} # if item
		
		}
		
		# end feed-item aufbauen
		
		
		} # foreach $event["werke"] as $einzelwerk 

	#print '</div>'; # *eventabschnitt end
	
	} # foreach($eventarray as $event )

			#print'<pre>start feed fertig<br>';
			#print_r($feed_xml);
			#print'end feed fertig</pre>';


print'<pre>';
print '<div style = "margin-top:150px;">';
print 'start feed<br><br>';

$datapfad = "http://www.ruediheimlicher.ch/Data/podcast/";



$beschreibung = "Podcast Kicho Dürnten";
$titel = 'Kirchenchor Dürnten '.$register[0];

$itemtitel = "Te_Deum";
$itemkomponist = "Mozart";



# leerfeed laden





if ($task == 'new')
{
	#$item = 


} # if new


?>
</div>

</body>

</html>
