<?php

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
    
<link href="solarstyle.css" rel="stylesheet" type="text/css" />




<title>Solardaten</title>
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
		<h1>Solaranlage</h1>
	   
		<?php
		date_default_timezone_set("Europe/Zurich"); 

		#phpinfo(); 	
		#print_r($_POST);
		#print '<br>';
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
			$AnzeigetagPfad="../Data/solardiagrammtag.txt";
			$AnzeigeHandle = fopen($AnzeigetagPfad,"r") or die("Can't open file Solardiagrammtag for read");
			$AnzeigeText= fread($AnzeigeHandle,filesize($AnzeigetagPfad));
		#	print 'AnzeigeText: ';
			#echo nl2br($AnzeigeText);
		#	print $AnzeigeText;
		#	print '<br>';
			$Zeilenarray=explode("-",$AnzeigeText);
		#	print 'Teile: ';
		#	print '<br>'.$Zeilenarray[0].'*'.$Zeilenarray[1].'*'.$Zeilenarray[2].'*';
		#	print '<br>';
			$zeitstempel = mktime(0,0,0, $Zeilenarray[0], $Zeilenarray[1], $Zeilenarray[2]);
			
			
			
			
			
			
			#print 'zeitstempel: '.$zeitstempel.'*<br>';
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
		
		#	print ' anzeigezeitstempel: ';
		#	print_r($anzeigezeitstempel);
		#	print '<br>';
	
			
			#if ($heutedatum == $zeitstempel)
		#		print 'postheute: '.$postheute.'<br>';
			if ($postheute || (($heutejahr == $anzeigejahr) && ($heutemonat == $anzeigemonat) && ($heutetagdesmonats == $anzeigetagdesmonats)))
			{
		#			print 'heute<br>';
				$SolardatenPfad = "../Data/solardiagrammdaten.txt";
			}
			else
			{
		#			print 'nicht heute<br>';
				$anzeigejahrkurz = $anzeigejahr - 2000;
				$SolardatenPfad = '../Data/SolardiagrammDaten/'.$anzeigejahr.'/solardiagrammdaten'.
				$anzeigejahrkurz.$anzeigemonat.$anzeigetagdesmonats.'.txt';
			}
			
			#print 'SolardatenPfad:'.$SolardatenPfad.'<br>' ;
			
			
		}
	
		
		
		
		//$AlarmPfad="Solar.txt"; 
		$AlarmPfad="../Data/AlarmDaten.txt";
		
		$AlarmHandle = fopen($AlarmPfad,"r") or die("Can't open file");
		$AlarmText= fread($AlarmHandle,filesize($AlarmPfad));
		//echo nl2br($AlarmText);
		$Zeilenarray=explode("\n",$AlarmText);
		//echo "<br>Zeilenarray: $Zeilenarray[2]";
		
		fclose($AlarmHandle);			
			 
	 
		$StatusPfad="../Data/SolarStatus.txt";
		$StatusHandle = fopen($StatusPfad,"r") or die("Can't open file");
		$StatusText= fread($StatusHandle,filesize($StatusPfad));
		fclose($StatusHandle);	
		//echo '*<br>';
		
		//echo nl2br($StatusText);
		
		$Daten = explode("\t",$StatusText);
		//echo "Statustext: $Statustext<br>";
		$index=0;
		foreach ($Daten as $element) 
		{
			//echo "$element<br>";
			$index++;
		}
		//echo '<br>';
		$DatumstringArray = explode(" ",$Daten[0]);
		$DatumTag=$DatumstringArray[1];
		$DatumZeit = $DatumstringArray[2];
		$PumpeStatus =  "OFF";
		//echo "Status: $Daten[7]<br>";
		$bit= $Daten[8]& 0x10;
		//echo "A $bit  <br>";
		$TestDaten=15;
		$bit=$TestDaten & 0x08;
		//echo "B $bit  <br>";
		
		
		if ( $Daten[8]& 0x08)
		{
			$PumpeStatus =  "ON";
		}
		else
		{
			$PumpeStatus =  "OFF";
		}
		
		if ( $Daten[8]& 0x10)
		{
			$ElektroStatus =  "ON";
		}
		else
		{
			$ElektroStatus =  "OFF";
		}
	
		# Datum fuer Solardiagramm holen
		
	
		?>
			  
		<!--
		<a href="../index.php">zurück</a>
		-->
	
		<div class="solarmenuabschnitt">
			<ul class="main-nav">
				<li><a href="../index.php">Home</a></li>
				<li><a href="../Strom/strom.php">Strom</a></li>
				<li><a href="../homecentral/homecentral.php">HomeCentral</a></li>
			
			</ul>
	
		
		</div> <!-- solarmenuabschnitt --!>
	
	
	
		<div class = "solardatenabschnitt">		
			<table class = ohne  border="0" >
			  <tr class = "text">
				<td width="146px"><div align="left">Datum:</div></td>
				<td width="40px"><div align="right"><?php echo $DatumTag;?></div></td>
				<td width="18px">&nbsp;</td>
				<td width="94px"><div align="left">Zeit:</div></td>
				<td><div align="right"><?php echo $DatumZeit;?></div></td>
				<td>&nbsp;</td>
			  </tr>
			</table>
	
		
			<table class=ohne border="0" cellpadding="0px">
			  <tr class="text">
				<td width="154" class="basic"><div align="left">Kollektor-Vorlauf</div></td>
				<td width="30" class="basic"><div align="right"><?php echo number_format(($Daten[2]/2),1) ;?></div></td>
				<td width="16" class="basic"><div align="right"><span class="Stil3">°C</span></div></td>
				<td width="21" class="basic">&nbsp;</td>
				<td width="80" class="basic"><div align="left">Umwälzpumpe</div></td>
				<td width="40" class="basic"><?php echo $PumpeStatus ?></td>
			  </tr>
			  <tr class="text">
				<td width="154" class="basic"><div align="left">Kollektor-Rücklauf</div></td>
				<td width="30" class="basic"><div align="right"><?php echo number_format(($Daten[3]/2),1);?></div></td>
				<td width="16" class="basic"><div align="right"><span class="Stil3">°C</span></div></td>
			  </tr>
			  <tr class="text">
				<td width="154" class="basic"><div align="left">Kollektortemperatur</div></td>
				<td width="30" class="basic"><div align="right"><?php echo number_format(($Daten[7]/2),1);?></div></td>
				<td width="16" class="basic"><div align="right"><span class="Stil3">°C</span></div></td>
			  </tr>
		
			  <tr class="text">
				<td width="154" class="basic"><div align="left">Boiler oben</div></td>
				<td width="30" class="basic"><div align="right"><?php echo number_format(($Daten[6]/2),1);?></div></td>
				<td width="16" class="basic"><div align="right"><span class="Stil3">°C</span></div></td>
				<td width="21" class="basic">&nbsp;</td>
				<td width="80" class="basic"><div align="left">Elektroeinsatz</div></td>
				<td width="40" class="basic"><?php echo $ElektroStatus ?></td>
			  </tr>
			  <tr class="text">
				<td width="154" class="basic"><div align="left">Boiler mitte</div></td>
				<td width="30" class="basic"><div align="right"><?php echo number_format(($Daten[5]/2),1);?></div></td>
				<td width="16" class="basic"><div align="right"><span class="Stil3">°C</span></div></td>
			  </tr>
		
			  <tr class="text">
				<td width="154" class="basic"><div align="left">Boiler unten</div></td>
				<td width="30" class="basic"><div align="right"><?php echo number_format(($Daten[4]/2),1);?></div></td>
				<td width="16" class="basic"><div align="right"><span class="Stil3">°C</span></div></td>
			  </tr>
			</table>
	   
		</div> <!-- solardatenabschnitt --!>
		
		<div class = "solarboilerabschnitt">
		
			<div style="position:relative; top:8px; left:10px;">
			<img src="../Bilder/Solar.png" width="140" height=auto alt="Falkenstr. 20 8630 Rüti">
			
			</div><!--Bild Boiler--!>
			
			<div style="position:absolute; top:8px; left:80px;">
			<p class = "boilerrot"><?php echo number_format(($Daten[7]/2),1) ;?> °C</p>
			</div><!--Koll temp --!>

			<div style="position:absolute; top:80px; left:110px;">
			<p class = "boilerrot"><?php echo number_format(($Daten[2]/2),1) ;?></p>
			</div><!--Koll Vorlauf --!>

			<div style="position:absolute; top:80px; left: 20px;">
			<p class = "boilerblau"><?php echo number_format(($Daten[3]/2),1) ;?></p>
			</div><!--Koll Ruecklauflauf --!>

			<div style="position:absolute; top:92px; left: 64px;">
			<p class = "boilerweiss" ><?php echo number_format(($Daten[6]/2),1) ;?></p>
			</div><!--Boiler oben --!>

			<div style="position:absolute; top:128px; left: 64px;">
			<p class = "boilerweiss" ><?php echo number_format(($Daten[5]/2),1) ;?></p>
			</div><!--Boiler mitte --!>

			<div style="position:absolute; top:162px; left: 64px;">
			<p class = "boilerweiss" ><?php echo number_format(($Daten[4]/2),1) ;?></p>
			</div><!--Boiler unten --!>


		</div ><!--solarboilerabschnitt"--!>

			
		<div class = "kalenderabschnitt">	
		<?php 
			
			
			# POST auswerten
			
			
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
			
		#	print 'heutejahr: '.$heutejahr.' postjahr: '.$postjahr.'<br>';
			
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
			#print 'aktuellerdatumstring: ';
			#print_r($aktuellerdatumstring);
		
			#print '<br>';
			$aktuellerzeitstempel = date('Y-m-d', strtotime($aktuellerdatumstring));# Aktueller timestamp
		#	print 'aktuellerzeitstempel: ';
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
			
			if ($posttask == "tagholen")
			{
		#		print 'Task: '.$posttask.' aktuellesjahr: '.$aktuellesjahr.' aktuellermonat: '.$aktuellermonat;
				
		#		print '<br>';
				
				# Datum fuer Diagramm setzen
				$AnzeigetagPfad="../Data/solardiagrammtag.txt"; # file mit Datum
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
							<?php
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
							<?php
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
							<?php
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
			<?php
			
			$postjahr=0;
			# Kalender zeigen#	
			#if ($posttask == "tagholen")
			
			
			{
			#	$heutewochentag = date('D');
			#	$heutewochentagzahl = date('N');
			$datumstring = $aktuellesjahr."-".$aktuellermonat."-"."1";
			#	print 'Datumstring: ';
			#	print_r($datumstring);
			
				#print '<br>';
			$wahldatum = date('Y-m-d', strtotime($datumstring));# Wahlzeitstempel
				
			#	print ' Wahldatum: ';
			#	print_r($wahldatum);
				
			
			$postwochentagzahl = date('N', strtotime($datumstring));# Ergibt Wochentagzahl
			$postkalendertaganzahl = date('t', strtotime($datumstring));
			#$startwochentagzahl
				
			#print '<input type= "text" name = "startwochentagfeld" size="20"  id = 14 value = "'.$wahldatum.'"  >';
			
			
			#print '<input type= "text" name = "startwochentagfeld" size="2"  id = 14 value = "'.$postwochentagzahl.'"  >';
			
			print '<form name = "kalenderindex" id = "kalenderform"action="" method = "POST">';
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
	 		<textarea name="Solardaten" cols="40" rows="5" ><?php echo ($SolardatenText);?></textarea>
	 		-->
	 		
	 		<?php
			 #print 'Jahr:';
			 
			 #$tempzeit = strtotime("10 September 2000");
			 #print $tempzeit .'<br>';
			#$tempzeit = strtotime("18 November 2012");
			 #print $tempzeit.'<br>';
			 #print date('d',$tempzeit).'*'.date('D',$tempzeit).'<br>';
			#print "heute: ".date('d').'*'.date('m').'*'.date('Y').'*'.date('D').'*'.date('N').'<br>';
			 #print 'wahljahr: '.$wahljahr.'<br>';
			 
			?>
			
			<?php
			
			
			if (($aktuellertagdesmonats >0) && !($aktuellertagdesmonats == $heutetagdesmonats) )
			{
			 	$AnzeigetagPfad="../Data/solardiagrammtag.txt";
		
		
				$AnzeigeHandle = fopen($AnzeigetagPfad,"r") or die("Can't open file Solardiagrammtag for read");
				$AnzeigeDatum= fread($AnzeigeHandle,filesize($AnzeigetagPfad));
				$Zeilenarray=explode("-",$AnzeigeDatum);
				$anzeigejahr = $Zeilenarray[0];
				$anzeigemonat = $Zeilenarray[1];
				$anzeigetagdesmonats = $Zeilenarray[2];

				fclose($AnzeigeHandle);	
				if (strlen($anzeigemonat) < 2) # muss zweistellig sein
				{
					$anzeigemonat = '0'.$anzeigemonat;
				}
		
				if (strlen($anzeigetagdesmonats) < 2) # muss zweistellig sein
				{
					$anzeigetagdesmonats = '0'.$anzeigetagdesmonats;
				}
				$anzeigejahrkurz = $anzeigejahr - 2000;
				$SolardatenPfad = '../Data/SolardiagrammDaten/'.$anzeigejahr.'/solardiagrammdaten'.$anzeigejahrkurz.$anzeigemonat.$anzeigetagdesmonats.'.txt';
				$SolarfilePfad = dirname(__FILE__).'/Data/SolardiagrammDaten/'.$anzeigejahr.'/solardiagrammdaten'.$anzeigejahrkurz.$anzeigemonat.$anzeigetagdesmonats.'.txt';
				
#				print '<div class = "solardiagrammfehlerabschnitt">';
				#$SolardatenHandle = fopen($SolardatenPfad,"r") or die("Can't open file Solardatenpfad");
				
#				if ( @fopen($SolardatenPfad,"r"))
#				{
				
#				print '<p class = pfadfehler>SolardatenPfad nach f-op OK<br>';
#				}
#				else
#				{
#					print '<p class = pfadfehler>Keine Daten für '.$anzeigetagdesmonats.'.'.$anzeigemonat.'.'.$anzeigejahr.' vorhanden.';
#					print '<pclass = pfadfehler>SolardatenPfad: '.$SolardatenPfad;

#				}
				

#					print '</div> <!-- solardiagrammfehlerabschnitt-->';

				if ( @fopen($SolardatenPfad,"r"))
				{
					
					print '<div class = "solardiagrammabschnitt">';
					#print 'SolardatenPfad OK<br>';
					print '<h2>History:</h2>';
					#print 'SolardatenPfad: '.$SolardatenPfad.'<br>';

				
					print '<img alt="Laden von Solartagdiagramm misslungen" src="solartagdiagramm.php"/> </p>';
					print '</div> <!-- solardiagrammabschnitt-->';
				}
				else
				{
					print '<div class = "solardiagrammfehlerabschnitt">';
					print '<h2>History:</h2>';
					print '<p class = pfadfehler>Keine Daten für '.$anzeigetagdesmonats.'.'.$anzeigemonat.'.'.$anzeigejahr.' vorhanden.<br>';
					print ' SolardatenPfad: '.$SolardatenPfad;
					print '</div> <!-- solardiagrammfehlerabschnitt-->';
				}


				
				
				
			}
			print '<div class = "solardiagrammabschnitt">';	
			print '<h2>Solaranlage:</h2>';
			print '	<img alt="Laden von Solardiagramm misslungen" src="solardiagramm.php"/> </p>';
			print '</div> <!-- solardiagrammabschnitt-->';
			
			 ?>
			 
			 
			
			<div  class = "solarertragabschnitt"> <!-- solardiagrammabschnitt-->
			<!--	<img alt="Laden von Solardiagramm misslungen" src="SolarDiagramm.php"/> </p>-->
			 <h2>Ertrag:</h2>
			<img alt="Laden von Solardiagramm misslungen" src="TagErtragDiagramm.php"/> 
			 </div> <!-- solardiagrammabschnitt-->
			
				<!--
			<div id="placeholder" style="width:600px;height:300px;"></div>        
			-->
				
		
	  <!-- end #mainContent -->
	  </div>
	  
		<!-- end #container -->
</div>
</body>
</html>
