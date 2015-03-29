<?

#include "../sources/libTeeChart.php";

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script language="javascript" type="text/javascript" src="../flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="../flot/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="datumpicker.js">
    var javindex = 3;
    </script>
    
<link href="homecentral.css" rel="stylesheet" type="text/css" />




<title>Homedaten</title>
<style type="text/css">
    <!--
    body 
    {
    	font: 100% Verdana, Arial, Helvetica, sans-serif;
    	background: #666666;
    	height: auto;
		margin: 0; /* Es empfiehlt sich, margin (Rand) und padding (Auffüllung) des Body-Elements auf 0 einzustellen, um unterschiedlichen Browser-Standardeinstellungen Rechnung zu tragen. */
    	padding: 0;
    	text-align: center; /* Hierdurch wird der Container in IE 5*-Browsern zentriert. Dem Text wird dann im #container-Selektor die Standardausrichtung left (links) zugewiesen. */
    	color: #0000000;
		font-size:10px;
    }
    
    


		-->
 </style>
    
 </head>

<body class="basic">

 <div id="container">
    <div id="mainContent">
		<h1>HomeCentral</h1>
	   
		<?
		#print_r($_POST);
		#print '<br>';

		#phpinfo(); 	
		$postjahr=0;
		if (isset($_POST['jahr']))
		{
			$postjahr = $_POST['jahr'];	
		}
		$postmonat=0;
		if (isset($_POST['monat']))
		{
			$postmonat = $_POST['monat'];	
		}
	
		$postwahltag=0;
		if (isset($_POST['wahltag']))
		{
			$postwahltag = $_POST['wahltag'];	
		}
	
		$postheute=0;
		if (isset($_POST['heute']))
		{
			$postheute = 1;	
		}
	
	
		$posttask="none";
		if (isset($_POST['task']))
		{
			$posttask = $_POST['task'];	
		}
		
		$postdatumfehler=0;
		if (isset($_POST['datumfehler']))
		{
			$postdatumfehler = $_POST['datumfehler'];	
		}
	
		#print '<br>';
		#print 'posttask: '.$posttask.' postmonat: '.$postmonat.' postjahr: '.$postjahr.' postwahltag: '.$postwahltag.'<br>';
	
		
		$wahlmonat=1;
		$wahljahr=1999;
		#print "heute: ".date('d').'*'.date('m').'*'.date('Y').'*'.date('D').'*'.date('N').'<br>';
		$heutedatum = date('Y-m-d');
		#print "heutedatum: ".$heutedatum.'<br>';
		$heutemonat=date('m');
		$heutejahr = date('Y');
		$heutewochentag = date('D');
		$heutewochentagzahl = date('N');
		$heutetagdesmonats = date('d');
		$Datumstring = $heutetagdesmonats.'.'.$heutemonat.'.'.$heutejahr;
		
		#print "heutetagdesmonats: ".$heutetagdesmonats.'<br>';
		
		#if ($posttask == "none") # neu
		{
			#$AnzeigetagPfad="../Data/solardiagrammtag.txt";
			#$WriteAnzeigeHandle = fopen($AnzeigetagPfad,"w") or die("Can't open file Solardiagrammtag for write");
			#fwrite($WriteAnzeigeHandle,$heutedatum);
			#fclose($WriteAnzeigeHandle);	
		}
		#else
		{	
			$heutedatum = date('Y-m-d');
			$AnzeigetagPfad="../Data/homediagrammtag.txt";
			#print 'AnzeigetagPfad: '.$AnzeigetagPfad.'<br>';
			$AnzeigeHandle = fopen($AnzeigetagPfad,"r") or die("Can't open file homediagrammtag for read");
			$AnzeigeText= fread($AnzeigeHandle,filesize($AnzeigetagPfad));
			#print 'AnzeigeText: ';
			#echo nl2br($AnzeigeText);
			#print $AnzeigeText;
			#print '<br>';
			$Zeilenarray=explode("-",$AnzeigeText);
			#print 'Teile: ';
			#print '<br>'.$Zeilenarray[0].'*'.$Zeilenarray[1].'*'.$Zeilenarray[2].'*';
			#print '<br>';
			$zeitstempel = mktime(0,0,0, intval($Zeilenarray[2]), intval($Zeilenarray[1]), intval($Zeilenarray[0]));
			
			#print 'zeitstempel: '.$zeitstempel.'<br>';
			$anzeigejahr = $Zeilenarray[0];
			$anzeigemonat = $Zeilenarray[1];
			if (strlen($anzeigemonat) < 2) # muss zweistellig sein
			{
				$anzeigemonat = '0'.$anzeigemonat;
			}
			$anzeigetagdesmonats = $Zeilenarray[2];
			if (strlen($anzeigetagdesmonats) < 2) # muss zweistellig sein
			{
				$anzeigetagdesmonats = '0'.$anzeigetagdesmonats;
			}
			
			fclose($AnzeigeHandle);			
			
			$anzeigedatumstring = $anzeigejahr."-".$anzeigemonat."-".$anzeigetagdesmonats;
	
			#print '<br>';
			$anzeigezeitstempel = date('Y-m-d', strtotime($anzeigedatumstring));# Wahlzeitstempel
		
			#print ' anzeigezeitstempel: ';
			#print_r($anzeigezeitstempel);
			#print '<br>';
	
			
			#if ($heutedatum == $zeitstempel)
			#print 'postheute: '.$postheute.'<br>';
			if ($postheute || (($heutejahr == $anzeigejahr) && ($heutemonat == $anzeigemonat) && ($heutetagdesmonats == $anzeigetagdesmonats)))
			{
				#print '<br>heute<br>';
				#$HomedatenPfad = "../Data/HomeDaten.txt";
			}
			else
			{
				#print '<br>nicht heute<br>';
				$anzeigejahrkurz = $anzeigejahr - 2000;
				$HomedatenPfad = '../Data/HomeDaten/'.$anzeigejahr.'/HomeDaten'.
				$anzeigejahrkurz.$anzeigemonat.$anzeigetagdesmonats.'.txt';
			}
			
			#print 'HomedatenPfad:'.$HomedatenPfad.'<br>' ;
			
		}
		
		//$AlarmPfad="Solar.txt"; 
		$AlarmPfad="../Data/AlarmDaten.txt";
		
		$AlarmHandle = fopen($AlarmPfad,"r") or die("Can't open file");
		$AlarmText= fread($AlarmHandle,filesize($AlarmPfad));
		//echo nl2br($AlarmText);
		$Zeilenarray=explode("\n",$AlarmText);
		//echo "<br>Zeilenarray: $Zeilenarray[2]";
		
		fclose($AlarmHandle);			
			 
		$LastDataPfad="../Data/LastData.txt";
		$LastDataHandle = fopen($LastDataPfad,"r") or die("Can't open file");
		$LastDataText= fread($LastDataHandle,filesize($LastDataPfad));
		fclose($LastDataHandle);	
		//echo '*<br>';
		
		#echo nl2br($LastDataText);
		
		$Daten = explode("\t",$LastDataText);
		#echo "Daten: $Daten[5]<br>";
		$index=0;
		foreach ($Daten as $element) 
		{
			//echo "$element<br>";
			$index++;
		}
		//echo '<br>';
		
		#LastData lesen
		
		# Stunde: position 5  Minute: position 6 (64 subtrahieren)
		$stunde = $Daten[5];
		$minute = $Daten[6]-64;
		
		#$DatumstringArray = explode(" ",$Daten[0]);
		#$DatumTag=$DatumstringArray[1];
		$DatumZeit = $stunde.':'.$minute;
		$BrennerStatus =  "OFF";
		//echo "Status: $Daten[7]<br>";
		$bit= $Daten[8]& 0x10;
		//echo "A $bit  <br>";
		$TestDaten=15;
		$bit=$TestDaten & 0x08;
		//echo "B $bit  <br>";
		
		
		if ( $Daten[4]& 0x04)
		{
			$BrennerStatus =  "OFF";
		}
		else
		{
			$BrennerStatus =  "ON";
		}
		
		
		# Rinne: Bit 6,7
		if ( $Daten[4]& 0xC0)
		{
			$RinneStatus =  "ON";
		}
		else
		{
			$RinneStatus =  "OFF";
		}
	
		# Datum fuer Homediagramm holen
		
	
		?>
			  
		<!--
		<a href="../index.php">zurück</a>
		-->
	
		<div class="solarmenuabschnitt">
		
			<ul class="main-nav">
				<li><a href="../index.php">Home</a></li>
				<li><a href="../Strom/strom.php">Strom</a></li>
				<li><a href="../Solar/Solar.php">Solar</a></li>
			
			</ul>
	
		
		</div> <!-- solarmenuabschnitt --!>
	
	
	
		<div class = "solardatenabschnitt">		
			<table class = ohne  border="0" >
			  <tr class = "text">
				<td width="146px"><div align="left">Datum:</div></td>
				<td width="40px"><div align="right"><?echo $Datumstring;?></div></td>
				<td width="18px">&nbsp;</td>
				<td width="94px"><div align="left">Zeit:</div></td>
				<td><div align="right"><?echo $DatumZeit;?></div></td>
				<td>&nbsp;</td>
			  </tr>
			</table>
	
		
		
			<table class=ohne border="0" cellpadding="0px">
			  <tr class="text">
				<td width="154" class="basic"><div align="left">Vorlauf</div></td>
				<td width="30" class="basic"><div align="right"><?echo number_format(($Daten[1]/2),1) ;?></div></td>
				<td width="16" class="basic"><div align="right"><span class="Stil3">°C</span></div></td>
				<td width="21" class="basic">&nbsp;</td>
				<td width="80" class="basic"><div align="left">Brenner</div></td>
				<td width="40" class="basic"><?echo $BrennerStatus ?></td>
			  </tr>
			  <tr class="text">
				<td width="154" class="basic"><div align="left">Rücklauf</div></td>
				<td width="30" class="basic"><div align="right"><?echo number_format(($Daten[2]/2),1);?></div></td>
				<td width="16" class="basic"><div align="right"><span class="Stil3">°C</span></div></td>
			  </tr>
			  <tr class="text">
				<td width="154" class="basic"><div align="left">Innentemperatur</div></td>
				<td width="30" class="basic"><div align="right"><?echo number_format(($Daten[8]/2),1);?></div></td>
				<td width="16" class="basic"><div align="right"><span class="Stil3">°C</span></div></td>
			  </tr>
		
			  <tr class="text">
				<td width="154" class="basic"><div align="left">Aussentemperatur</div></td>
				<td width="30" class="basic"><div align="right"><?echo number_format((($Daten[3]-32)/2),1);?></div></td>
				<td width="16" class="basic"><div align="right"><span class="Stil3">°C</span></div></td>
				<td width="21" class="basic">&nbsp;</td>
				<td width="80" class="basic"><div align="left">Rinnenheizung</div></td>
				<td width="40" class="basic"><?echo $RinneStatus ?></td>
			  </tr>
			  

			</table>
	   
		</div> <!-- solardatenabschnitt --!>
			
		<div class = "kalenderabschnitt">	
		<?
	
			# POST auswerten
			#print 'Datumstring: '.$Datumstring.'<br>';
			
			$aktuellesdatum = $heutedatum;
			$datumfehler=0;
			$aktuellesjahr = $heutejahr;
			$aktuellermonat = $heutemonat;
			$aktuellertagdesmonats = $heutetagdesmonats;
			
			/*
			if ($postdatumfehler)
			{
			
			}
			else
			*/
			{
			
			#print 'heutejahr: '.$heutejahr.' postjahr: '.$postjahr.'<br>';
			
			if (($postjahr > 0))#&& ($postjahr <= $heutejahr)) # return von form
			{
				if ($postjahr > $heutejahr)
				{
					$datumfehler++;
				}
				else
				{
					$aktuellesjahr = $postjahr;
				}
				
			}
			
		
			
			if ($postmonat>0) # return von form 
			{
				if (($postjahr == $heutejahr) && ($postmonat > $heutemonat))
				{
					$datumfehler++;
				}
				else
				{
					$aktuellermonat = $postmonat;
				}
				
			}
			
			
		   if ($postwahltag > 0) # return von form
			{
				if (($postjahr == $heutejahr) && ($postmonat == $heutemonat) && ($postwahltag > $heutetagdesmonats))
				{
					$datumfehler++;
				}
				else
				{
					$aktuellertagdesmonats = $postwahltag;
					#print 'postwahltag : *'.$postwahltag.'*<br>';
				}
			}
			
			
			
			
		#	print 'datumfehler: '.$datumfehler.'<br>';
			
			if ($datumfehler>0)
			{
		#	print '<a  href="javascript:datumfehler('.$datumfehler.')" ></a>';
			$datumfehler=0;
			}
			
			$postwahltag=0;
			
			$postmonat=0;
			}
			
			# aktueller Tag ist heute
			$heuteok = 0;
			
			$aktuellerdatumstring = $aktuellesjahr."-".$aktuellermonat."-".$aktuellertagdesmonats;
			#print 'aktuellerdatumstring: '.$aktuellerdatumstring.'<br>';
			#print_r($aktuellerdatumstring);
		
			#print '<br>';
			$aktuellerzeitstempel = date('Y-m-d', strtotime($aktuellerdatumstring));# Aktueller timestamp
			#print 'aktuellerzeitstempel: '.$aktuellerzeitstempel.'<br>';
		#	print_r($aktuellerzeitstempel);
		#	print '<br>';
			
			
			if (($aktuellesjahr == $heutejahr) && ($aktuellermonat == $heutemonat) && ($aktuellertagdesmonats == $heutetagdesmonats))
			{
				$heuteok=1;
			}
			
			
			
			
			
			# ******************
		#	print 'Ist es heute? : *'.$heuteok.'*<br>';
			# ******************
			
			
			
			# task auswerten
			$monatholentask = "monatholen";
			
			if ($posttask == $monatholentask)
			{
				
		#		print 'Task: '.$posttask.' aktuellesjahr: '.$aktuellesjahr.' aktuellermonat: '.$aktuellermonat;
		#		print '<br>';
				$aktuellertagdesmonats = -1; # keinen Tag im Monatskalender auszeichnen
			}
			
#			if ($posttask == "tagholen")
			{
				#print 'Task: '.$posttask.' aktuellesjahr: '.$aktuellesjahr.' aktuellermonat: '.$aktuellermonat.'<br>';
				
				# Datum fuer Diagramm setzen
				$AnzeigetagPfad="../Data/homediagrammtag.txt"; # file mit Datum
				$WriteAnzeigeHandle = fopen($AnzeigetagPfad,"w") or die("Can't open file Solardiagrammtag for write");
				$tagholendatum = $aktuellesjahr;
				if (strlen($aktuellermonat) < 2)
				{
					$tagholendatum .= '-0'.$aktuellermonat; 
				}
				else
				{
					$tagholendatum .= '-'.$aktuellermonat; 
				}
				
				if (strlen($aktuellertagdesmonats) < 2)
				{
					$tagholendatum .= '-0'.$aktuellertagdesmonats; 
				}
				else
				{
					$tagholendatum .= '-'.$aktuellertagdesmonats; 
				}
				
				
		#		print ' tagholendatum: '.$tagholendatum;
				fwrite($WriteAnzeigeHandle,$tagholendatum);
				fclose($WriteAnzeigeHandle);	
		
		
			}
			
			
			# Form Wahl Jahr/Monat aufbauen
			
			print '<form action=" " method = "POST">'; # form Jahr/Monat
				
				
				print '<input type="hidden"  name="task" value = "monatholen">'; # ausgewaehlten Monat/Jahr holen
				
				print '<input type="hidden"  name="monat" value = "'.$aktuellermonat.'" >';
				print '<input type="hidden"  name="jahr" value = "'.$aktuellesjahr.'">';
				print '<input type="hidden"  name="aktuellertagdesmonats" value = "'.$aktuellertagdesmonats.'">';
				if ($postjahr > $heutejahr)
				{
					print '<input type="hidden"  name="jahrfehler" value = "1">';
				}
				else
				{
					print '<input type="hidden"  name="jahrfehler" value = "0">';
				}
		
				#print '<input type="hidden"  name="datumfehler" value = "'.$datumfehler.'">';
				
				?>
				<p class = "kalender">Daten holen:</p>
				<table >
					<tr>
						<td>
							<select onchange="this.form.monat.value = this.value"style="width: 55px"> 
							<?
							# Monat-Pop setzen, heutemonat selektieren
							$monatarray= array("Jan","Feb","Mrz","Apr","Mai","Juni","Juli","Aug","Sept","Okt","Nov","Dez");
							for ($mon=0;$mon<12;$mon++)
							{
								if ($aktuellermonat == $mon+1)
									{
										print '<option value = '.($mon+1).' selected="selected">'.$monatarray[$mon].'</option>';
									}
								else
									{
										print '<option value = '.($mon+1).'>'.$monatarray[$mon].'</option>';
									}
							}
							
							?>
							</select>
						</td>
						
						<td>
							<select onchange="this.form.jahr.value = this.value"style="width: 60px"> 
							<?
							# Jahr-Pop setzen, heutejahr selektieren
							$jahrarray= array("2010","2011","2012","2013","2014","2015","2016","2017","2018");
		
							for ($jahr=0;$jahr<count($jahrarray);$jahr++)
							{
								
								if ($aktuellesjahr == $jahr+2010) # Jahr passt
									{
										print '<option value = '.$jahrarray[$jahr].' selected="selected">'.$jahrarray[$jahr].'</option>';
									}
								else
									{
										print '<option value = '.$jahrarray[$jahr].'>'.$jahrarray[$jahr].'</option>';
									}
							}
							
							?>
							
							</select>
						</td>
		
						<td>
							<?
							#print '<input type="submit" name = "go" value="go" onClick="datumfehler('.$postjahr.','.$heutejahr.')"/>';
							print '<input type="submit" name = "go" value="go"/>';
							?>
						</td>
				
					</tr>
				</table>
			</form> <!--# Jahr/Monat -->
			
			
			<form action = "" name = "heute" method = "POST">
				<input type="submit" class = "kalender" name = "heute" value="Heute" style="width: 150px"/>
			</form> <!--# Heute -->
			<?
			
			$postjahr=0;
			# Kalender zeigen#	
			#if ($posttask == "tagholen")
			
			
			{
				#	$heutewochentag = date('D');
				#	$heutewochentagzahl = date('N');
				$datumstring = $aktuellesjahr."-".$aktuellermonat."-"."1";
				#print 'Datumstring: ';
				#print_r($datumstring);
			
				#print '<br>';
				$wahldatum = date('Y-m-d', strtotime($datumstring));# Wahlzeitstempel
				
				#	print ' Wahldatum: ';
				#	print_r($wahldatum);
				
			
				$postwochentagzahl = date('N', strtotime($datumstring));# Ergibt Wochentagzahl
				$postkalendertaganzahl = date('t', strtotime($datumstring));
				#$startwochentagzahl
				
				#print '<input type= "text" name = "startwochentagfeld" size="20"  id = 14 value = "'.$wahldatum.'"  >';
			
			
				#print '<input type= "text" name = "startwochentagfeld" size="2"  id = 14 value = "'.$postwochentagzahl.'"  >';
			
				print '<form name = "kalenderindex" id = "kalenderform" action="" method = "POST">';
				print '<table class = kalender >';
				print '<input type="hidden"  name="task" value = "tagholen">'; # ausgewaehlten Tag holen
				$tagarray= array("MO","DI","MI","DO","FR","SA","SO");
				#print 'wahlindex: <input type="text" size="8" name="wahlindex" label = "wahlindex" readonly="readonly">';
			
				
				# Monatskalender aufbauen
				$kalendertag=1;
			
				for ($zeile=0;$zeile<=6;$zeile++)
				{
					print '<tr class = "kalender" >';
					for ($kolonne=1;$kolonne<=7;$kolonne++)
					{
						
						if ($zeile==0)
						{
							print ' <th class = "kalender"  >'.$tagarray[$kolonne-1].'</td>';
						}
						else
						{
							$index = 7*($zeile-1) + $kolonne;
							if (($kalendertag == $aktuellertagdesmonats) && ($index >= $postwochentagzahl))
							{
								print '<td class = "kalenderheute" >';
							}
							else
							{
								print '<td class = "kalender" >';
							}
						
						
							print '<input type="hidden" name = "tastenindex" value = "'.$index.'">';
							print '<input type="hidden" name = "jahr" value = "'.$aktuellesjahr.'">';
							print '<input type="hidden" name = "monat" value = "'.$aktuellermonat.'">';
							$tagindex = "Tag".$index;
							
							if (($index >= $postwochentagzahl) && ($kalendertag <= $postkalendertaganzahl )) # Monat hat begonnen
							{
								
								#print '<a  href="javascript:indexschreiben('.$kalendertag.')" onClick="window.location.reload()">';
								if (($heutejahr == $aktuellesjahr) && ($heutemonat == $aktuellermonat))
								{
								if ($kalendertag <= $heutetagdesmonats)
									{
										print '<a  href="javascript:indexschreiben('.$kalendertag.')" >';
										print '<b>'.$kalendertag.'</b>';
									}
									else
									{
										print '<b>'.$kalendertag.'</b>';
									}
									
								}
								else
								{
										print '<a  href="javascript:indexschreiben('.$kalendertag.')" >';
										print '<b>'.$kalendertag.'</b>';
								
								}
								
								
								$kalendertag++;
							
								#print '	</div>';
								#print '</div>';
							} # if 
						
						} # if zeile >0
						
						#print '<input type= "button" width="6" value = '.$index.' onClick="indexschreiben('.$index.')">';
						print '</td>';
					} # for kolonne
			
					print '</tr>';
				
				} # for zeile
			
			
				print ' <br> ';
				#print_r($aktuellertagdesmonats);
				print '<input type= "hidden" name = "wahltag" size = "2" value = "'.$aktuellertagdesmonats.'"  id = "20"  >';
				
				#print ' heutetagdesmonats: ';
				#print '<input type= "text" name = "wahltagfeld" size="2"  value = "'.$heutetagdesmonats.'" id = "19"  >';
				
				
				print '</table>';
				
				
				print '</form>';
				}
				?>
	
		</div> <!-- kalenderdiv--!>
	
		
		
			<!--
	 		<textarea name="Solardaten" cols="40" rows="5" ><?echo ($SolardatenText);?></textarea>
	 		-->
	 		
	 		<? 
			 #print 'Jahr:';
			 
			 #$tempzeit = strtotime("10 September 2000");
			 #print $tempzeit .'<br>';
			#$tempzeit = strtotime("18 November 2012");
			 #print $tempzeit.'<br>';
			 #print date('d',$tempzeit).'*'.date('D',$tempzeit).'<br>';
			#print "heute: ".date('d').'*'.date('m').'*'.date('Y').'*'.date('D').'*'.date('N').'<br>';
			 #print 'wahljahr: '.$wahljahr.'<br>';
			 
			?>
			
			<?
			
			
			if (($aktuellertagdesmonats >0) && !($aktuellertagdesmonats == $heutetagdesmonats) )
			{
				print '<div class = "solardiagrammabschnitt">';
				print '<h2>History:</h2>';
				
# test
					
# end test

				print '<img alt="Laden von homecentraltagdiagramm misslungen" src="homecentraltagdiagramm.php"/> </p>';
				
				print '<div class="savemenuabschnitt">
						<ul class="main-nav-save">
							<li title = "Download"><a href="http://www.ruediheimlicher.ch/homecentral/diagrammdownload.php">Download</a></li>
						</ul>
					</div> <!-- savemenuabschnitt --!>';
	

				
				print '</div> <!-- solardiagrammabschnitt-->';
			}
			print '<div class = "solardiagrammabschnitt">';	
			
			
				print '<h2>Heizung:</h2>';
				print '	<img alt="Laden von homecentraldiagramm misslungen" src="homecentraldiagramm.php"/> </p>';
			
				#print '<div class="savemenuabschnitt">';
				#print '			<ul class="main-nav-save">';
				#print '				<li title = "Download"><a href="http://www.ruediheimlicher.ch/homecentral/heutediagrammdownload.php">Download</a></li> ';
				#print '			</ul>';
				#print '		</div> <!-- savemenuabschnitt --!>';

			
			
			
			print '</div> <!-- solardiagrammabschnitt-->';
			
			 ?>
			 
			 
			
			<div  class = "solarertragabschnitt"> <!-- solardiagrammabschnitt-->
						<!--	<img alt="Laden von Solardiagramm misslungen" src="SolarDiagramm.php"/> </p>-->
			 <!--
			 <h2>Ertrag:</h2>
			
			<img alt="Laden von HomeCentraldiagramm misslungen" src="homecentraldiagramm.php"/>
			 -->
			
			 </div> <!-- solardiagrammabschnitt-->

			<?
			
	$HomedatenPfad="../Data/HomeDaten.txt";
	$HomedatenHandle = fopen($HomedatenPfad,"r") or die("Can't open file HomeDaten");
	#echo 123;
	#echo filesize($HomedatenPfad);
	#echo '*<br>';
	$HomedatenText= fread($HomedatenHandle,filesize($HomedatenPfad));
	fclose($HomedatenHandle);	
	//echo '*<br>';
	$Homedatenarray=explode("\n",$HomedatenText);
	#echo nl2br($HomedatenText);
	
	# Erste Zeile mit Daten
	$Daten = explode("\t",$Homedatenarray[6]);
	#echo nl2br($Daten[0]);
	#echo '*<br>';
	$TagArray;
	$LeistungDaten=array();
	
	$startindex=0;
	$index=0;
	$oldminute=0;
	$startstunde = 0;
	$endstunde = 24;
	$oldstunde = $startstunde;
	$SkalaDaten;
	$ZeitachseDaten;

	$intervall=1;
	$anzDatenzeilen =  count($Homedatenarray);
	$anzMinuten = ($endstunde-$startstunde)*(60 + $intervall);
	$maxMinuten = 1440; // 24 h
	$startminute = $startstunde*60;
	
// Homedatenarrayelemente auf Intervallabstand reduzieren
	
	$dataindex=0;
	$intervallindex=0;
	$kopfzeilen = 5; // Kopfbereich der Homedaten
	$HomeIntervallArray; // Daten im Abstand von $intervall, nullbasiert
	
	$pumpedatenvorhanden = 0;
	$elektrodatenvorhanden = 0;
	$oldminute =0;
	
	$offsetsekunde=$Homedatenarray[$kopfzeilen+1][0];
	$oldminute = $offsetsekunde/60;
	
	for ($dataindex=0; $dataindex < $anzDatenzeilen; $dataindex+=$intervall)
	{
		
		if ($dataindex > $kopfzeilen)
		{
			$element= $Homedatenarray[$dataindex];
			
			$zeilenarray=explode("\t",$element);
				
			$minute=$zeilenarray[6]-64; // tagsekunden > minuten
			$restminute = $minute%60;
			$stunde=$zeilenarray[5];
			
			$minutedestages = ($zeilenarray[0]-$offsetsekunde)/60;
			
			
			if (!($minute == $oldminute)) // erste Datenzeile ist nicht bei 0
			{
				#echo " 	stunde: $stunde	minute: $minute zeilenarray: $zeilenarray[1]<br>";
				
				# Minute korrigieren
				$zeilenarray[6] = $minute;
				$HomeIntervallArray[$intervallindex] = $zeilenarray; // relevante Zeile
				$intervallindex++;
				
				$oldminute = $minute; // Schrittweite
			}
			
			
		} 
		
	} //for dataindex
	
	//echo "max intervallindex: $intervallindex anzminuten: $anzMinuten startminute: $startminute<br>";

	// DiagrammDatenarrays mit Werten aus HomeIntervallArray fuellen
	
	$datapos=0;
	$dataok=0;
	for ($datapos=0; $datapos < $anzMinuten; $datapos++)
	{
		// HomeIntervallArray an (datapos + startminute vorhanden?
		$zeilenarray= $HomeIntervallArray[$datapos + $startminute]; // Datenzeile an pos ($datapos + $startminute)
		#if ($datapos + $startminute < $intervallindex)
		if ($zeilenarray[0]) # Zeit
		{
			
			#print_r($zeilenarray);
			#print'<br>';
			#$zeilenarray=explode("\t",$element);
			$VorlaufDaten[$datapos]=($zeilenarray[1])/2;
			$RuecklaufDaten[$datapos]=($zeilenarray[2])/2;
			$AussentemperaturDaten[$datapos]=($zeilenarray[3])/2;
			#echo "$pos: $datapos aussen: $AussentemperaturDaten[$datapos] <br>";
			#echo "$pos min: $minute<br>";
			#echo "$datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] AussentemperaturDaten: $AussentemperaturDaten[$datapos]<br>";
			
			$InnentemperaturDaten[$datapos]=intval($zeilenarray[8])/2;

			$tempHomestatus =intval($zeilenarray[4]);
			$HomestatusDaten[$datapos]=$tempHomestatus;
			$tempUhrstatus ==0;
			$stundenteil = $tempHomestatus;
			$stundenteil &= 0x08;	// Bit 3: 0: erste halbe Stunde
			$stundenteil >>=3;		// verschieben an Pos 0
			
			if ($tempHomestatus & 0x04) // Brenner ist OFF
			{
				$BrennerstatusDaten[$datapos] = NULL;
				
			}
			else
			{
				$BrennerstatusDaten[$datapos] = 118;
				$brennerdatenvorhanden++;
			}
			
			$tempUhrstatus = $tempHomestatus & 0x03;
			
			
			#echo "tempHomestatus: $tempHomestatus Stundenteil: $Stundenteil tempUhrstatus: $tempUhrstatus<br>";
			$UhrstatusDaten[$datapos] = NULL;
			
			if ($tempUhrstatus ==0) // Uhr ist OFF
			{
				#$uhrdatenvorhanden++;
			}
			elseif ($tempUhrstatus ==1)
			{
				if ($stundenteil)
				{
					$UhrstatusDaten[$datapos] = 120;
				}
			}
			elseif(	$tempUhrstatus ==2)
			{
				if ($stundenteil ==0)
				{
					$UhrstatusDaten[$datapos] = 120;
				}
			}
			elseif ($tempUhrstatus ==3)
			{
				$UhrstatusDaten[$datapos] = 120;
			}
			
			$dataok++;
			if ($zeilenarray[6]==0) # ganze Stunde angeben
			{
			#echo "datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] min: $tempminute Vorlauf: $VorlaufDaten[$datapos] Ruecklauf: $RuecklaufDaten[$datapos] Aussen: $AussentemperaturDaten[$datapos] Innen: $InnentemperaturDaten[$datapos] Brenner: $BrennerstatusDaten[$datapos] Uhr: $UhrstatusDaten[$datapos] Homestatus: $HomestatusDaten[$datapos]<br>";
				$LeistungDaten[]=array($zeilenarray[5].':00',$datapos+$startminute, 
				$VorlaufDaten[$datapos],
				$RuecklaufDaten[$datapos],
				$AussentemperaturDaten[$datapos],
				$InnentemperaturDaten[$datapos],
				$BrennerstatusDaten[$datapos],
				$UhrstatusDaten[$datapos],
				
				);
				
			}
			
			else # nur Daten, keine Zeit
			{
				$LeistungDaten[]=array(' ',$datapos+$startminute,
				$VorlaufDaten[$datapos],
				$RuecklaufDaten[$datapos],
				$AussentemperaturDaten[$datapos],
				$InnentemperaturDaten[$datapos],
				$BrennerstatusDaten[$datapos],
				$UhrstatusDaten[$datapos]
				);
		
			}
			
			$tempRinnestatus ==0;
			$tempRinnestatus = $tempHomestatus;
			$tempRinnestatus &= 0xc0;	// Bit 6,7
			$tempRinnestatus >>=6;		// verschieben an Pos 0
			
			if ($tempRinnestatus)
			{
				$RinnestatusDaten[$datapos] = 64;
			}
			else
			{
				$RinnestatusDaten[$datapos] = 0;
			}
			#echo "datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] min: RinnestatusDaten: $RinnestatusDaten[$datapos]<br>";

			

		}
		
		else // Daten mit VOID fuellen
		{
		
			$VorlaufDaten[$datapos]='';
			$RuecklaufDaten[$datapos]='';
			$AussentemperaturDaten[$datapos]='';
			//echo "$startindex $KollektortemperaturDaten[$startindex]<br>";
		
			$InnentemperaturDaten[$datapos]='';
			if ($datapos==0)
			{
				$BrennerstatusDaten[0]=0;
				$UhrstatusDaten[0]=0;
			}	
	
			else
			{
				#$PumpestatusDaten[$datapos]=NULL;
				#$ElektrostatusDaten[$datapos]=NULL;
			}
			$NullDaten[$datapos]=0;
			
			//$ZeitachseDaten[$datapos] =intval($datapos/60) + ($startstunde);

		#$LeistungDaten[]=array(' ',$datapos+$startminute, ''
		#,
		#$VorlaufDaten[$datapos],
		#$KollektorruecklaufDaten[$datapos],
		#$BoileruntenDaten[$datapos],
		#$BoilermitteDaten[$datapos],
		#$BoilerobenDaten[$datapos],
		#);

		}
		#print_r($Leistungdaten[$datapos]);
			# Datenarray aufbauen
			/*
		$LeistungDaten[]=array(' ',$datapos+$startminute, $KollektortemperaturDaten[$datapos]
		#,
		#$VorlaufDaten[$datapos],
		#$KollektorruecklaufDaten[$datapos],
		#$BoileruntenDaten[$datapos],
		#$BoilermitteDaten[$datapos],
		#$BoilerobenDaten[$datapos],
		);
		
		*/
		
		
		$ZeitachseDaten[$datapos] =intval($datapos/60) + ($startstunde);
		$tempminute = $datapos%60;
		#echo "datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] min: $tempminute Vorlauf: $VorlaufDaten[$datapos] Ruecklauf: $RuecklaufDaten[$datapos] Aussen: $AussentemperaturDaten[$datapos] Innen: $InnentemperaturDaten[$datapos] Brenner: $BrennerstatusDaten[$datapos] Uhr: $UhrstatusDaten[$datapos] Homestatus: $HomestatusDaten[$datapos] NullDaten: $NullDaten[$datapos]<br>";
	
	} // for datapos
	$datapos=1;
	#echo "LeistungDaten: $LeistungDaten[0] $LeistungDaten[1]<br>";
	
	foreach($LeistungDaten as $element) 
	{
	#print_r($element);
	#print'<br>';
	}

	#echo "datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] min: $tempminute Vorlauf: $VorlaufDaten[$datapos] Ruecklauf: $RuecklaufDaten[$datapos] Aussen: $AussentemperaturDaten[$datapos] Innen: $InnentemperaturDaten[$datapos] Brenner: $BrennerstatusDaten[$datapos] Uhr: $UhrstatusDaten[$datapos] Homestatus: $HomestatusDaten[$datapos] <br>";

			
			
			?>
			
			
			
				<!--
			<div id="placeholder" style="width:600px;height:300px;"></div>        
			-->
				
		
	  <!-- end #mainContent -->
	  </div>
	  
		<!-- end #container -->
</div>
</body>
</html>
