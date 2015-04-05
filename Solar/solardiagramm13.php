<?php 

	require_once "phplot.php";

	$SolardatenPfad="../Data/solardiagrammdaten.txt";
	$SolardatenHandle = fopen($SolardatenPfad,"r") or die("Can't open file");
	$SolardatenText= fread($SolardatenHandle,filesize($SolardatenPfad));
	fclose($SolardatenHandle);	
	//echo '*<br>';
	$Solardatenarray=explode("\n",$SolardatenText);
	/*
	$SolarErtragPfad="../Data/SolarTagErtrag.txt";
	$SolardErtragHandle = fopen($SolarErtragPfad,"r") or die("Can't open file");
	$SolarErtragText= fread($SolarErtragHandle,filesize($SolarErtragPfad));
	fclose($SolarErtragHandle);	
	//echo '*<br>';
	$Solarertragarray=explode("\n",$SolarErtragText);
	*/
	#echo nl2br($SolardatenText);
	
	 $Daten = explode("\t",$SolardatenText);
	#echo '*Status<br>';
	
	$Laufzeit;
	$KollektorvorlaufDaten = array();
	$KollektorruecklaufDaten = array();
	$KollektortemperaturDaten = array();
	$BoileruntenDaten = array();
	$BoilermitteDaten = array();
	$BoilerobenDaten = array();
	$SolarstatusDaten = array();
	$PumpestatusDaten = array();
	
	$TagArray;
	
	$startindex=0;
	$index=0;
	$oldminute=0;
	$startstunde = 0;
	$endstunde = 24;
	$oldstunde = $startstunde;
	$SkalaDaten;
	$ZeitachseDaten;
	
	//$endstunde -=1;
	$intervall=1;
	$anzDatenzeilen =  count($Solardatenarray);
	$anzMinuten = ($endstunde-$startstunde)*(60 + $intervall);
	$maxMinuten = 1440; // 24 h
	$startminute = $startstunde*60;
	$maxDatenzeilen = $anzMinuten / $intervall;
	
	// Datenarrays fuellen
	
	
	//$ZeitachseDaten[] =$endstunde;
	
	//echo "Anzahl Elemente: $i<br>";
	
	//echo "startindex: $startindex<br>";
	//echo count($Solardatenarray);
	//foreach ($Solardatenarray as $element) 


// Solardatenarrayelemente auf Intervallabstand reduzieren
	
	
	$dataindex=0;
	$intervallindex=0;
	$kopfzeilen = 0; // Kopfbereich der Solardaten
	$SolarIntervallArray; // Daten im Abstand von $intervall, nullbasiert
	
	$pumpedatenvorhanden = 0;
	$elektrodatenvorhanden = 0;
	$minute =0;
	$oldminute =0;
	
	$zeilennummer=0;
	for ($dataindex=0; $dataindex < $anzDatenzeilen; $dataindex+=$intervall)
	{
		
		if ($dataindex > $kopfzeilen)
		{
			$element= $Solardatenarray[$dataindex];
			$zeilenarray = array();
			$zeilenarray=explode("\t",$element);
			if(count($zeilenarray) > 2)  # leere Zeilen ergeben undefined index
			{
				$minute = $zeilenarray[4]; # tagsekunden > minuten
				$restminute = $minute%60;
				$stunde=$zeilenarray[3];
				$anz=count($zeilenarray);
				#echo "elemente:  \t$anz \t $zeilenarray[0] \tstunde: $stunde	minute: $minute restminute: $restminute<br>";
			
				if (!($minute == $oldminute)) // erste Datenzeile ist nicht bei 0, Daten nur einmal pro minute
				{
				
					$SolarIntervallArray[$intervallindex] = $zeilenarray; // relevante Zeile
					$intervallindex++;
				
					$oldminute = $minute; // Schrittweite
#					echo "$zeilennummer\t*\t";
#					foreach($zeilenarray as $teil)
#					{
						
#						echo "$teil\t";
		
#					}
#					echo "<br>";
					$zeilennummer++;
				}
			
			}
		} 
		
	} //for dataindex
	$zeilennummer--;
#	echo "max intervallindex: $intervallindex anzminuten: $anzMinuten startminute: $startminute<br>";
	#Rest des Array fuellen
	for ($dataindex=$intervallindex; $dataindex < $zeilennummer; $dataindex++)
	{
		
		$SolarIntervallArray[$intervallindex] = 0;
	}
	
	// DiagrammDatenarrays mit Werten aus SolarIntervallArray fuellen
	
	$datapos=0;
	$dataok=0;
	$Maxordinate;
	$SolarstatusDaten = array();
	$PumpestatusDaten = array();
	$ElektrostatusDaten = array();
	$anz = count($SolarIntervallArray);
#	echo "startminute: $startminute  anz: $anz<br>";
	
	#for ($datapos=0; $datapos < $anzMinuten ; $datapos++)
	for ($datapos=0; $datapos < $zeilennummer ; $datapos++)
	{
		#echo "datapos: $datapos  <br>";
		// SolarIntervallArray an (datapos + startminute vorhanden?
		
		{
			$zeilenarray= $SolarIntervallArray[$datapos + $startminute]; // Datenzeile an pos ($datapos + $startminute)
		
			#if ($datapos + $startminute < $intervallindex)
			$tempSolarstatus =0;
			#if (count($zeilenarray)&&$zeilenarray[0])
			#if ($datapos < $anz)
			if ($datapos < $intervallindex)
			{
				#$Maxordinate[$datapos]=150;
				#echo "$datapos: $datapos <br>";
				#print_r($zeilenarray);
				//$zeilenarray=explode("\t",$element);
				$KollektorvorlaufDaten[$datapos]=($zeilenarray[5])/2;
				$KollektorruecklaufDaten[$datapos]=($zeilenarray[6])/2;
				$KollektortemperaturDaten[$datapos]=($zeilenarray[10])/2;
				//echo "$startindex h: $stunde min: $minute<br>";
			//echo "$datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] KollektortemperaturDaten: $KollektortemperaturDaten[$datapos]<br>";
			
				$BoileruntenDaten[$datapos]=intval($zeilenarray[7])/2;
				$BoilermitteDaten[$datapos]=intval($zeilenarray[8])/2;
				$BoilerobenDaten[$datapos]=intval($zeilenarray[9])/2;
				#print_r($zeilenarray);
#				echo "$datapos: zeilenarray 11: $zeilenarray[11]<br>";
				$tempSolarstatus=0;
				#if ($zeilenarray[11])
				{
					$PumpestatusDaten[$datapos]=0.0;
					$tempSolarstatus =intval($zeilenarray[11]);
					$SolarstatusDaten[$datapos] = $tempSolarstatus;
					if ($tempSolarstatus & 0x08) // Pumpe ist ON
					{
						$PumpestatusDaten[$datapos] = 128;
						$pumpedatenvorhanden++;
					}
					else
					{
						#$PumpestatusDaten[$datapos] = NULL;
					}
					$ElektrostatusDaten[$datapos]=0.0;
					if ($tempSolarstatus & 0x10) // Pumpe ist ON
					{
						$ElektrostatusDaten[$datapos] = 126;
						$elektrodatenvorhanden++;
					}
					else
					{
						$ElektrostatusDaten[$datapos] = NULL;
					}
				}
#				echo "PumpestatusDaten an $datapos: $PumpestatusDaten[$datapos]<br>";
				$dataok++;
				if ($zeilenarray[4]==0) # Minute ist 0, Stunde angeben
				{
		#		if (count($PumpestatusDaten)>$datapos && count($ElektrostatusDaten)>$datapos && count($PumpestatusDaten)>$datapos)
				{
					$LeistungDaten[]=array($zeilenarray[3].':00',$datapos+$startminute, $KollektortemperaturDaten[$datapos]
					,
					$KollektorvorlaufDaten[$datapos],
					$KollektorruecklaufDaten[$datapos],
					$BoileruntenDaten[$datapos],
					$BoilermitteDaten[$datapos],
					$BoilerobenDaten[$datapos],
					$PumpestatusDaten[$datapos],
					$ElektrostatusDaten[$datapos],
					#$Maxordinate[$datapos],
					);
					}
				}
				else # nur Daten, ohne Stunden
				{
					#if (count($PumpestatusDaten))
					{
					#if (count($PumpestatusDaten)>$datapos)
					{
					$LeistungDaten[]=array(' ',$datapos+$startminute, $KollektortemperaturDaten[$datapos],
					$KollektorvorlaufDaten[$datapos],
					$KollektorruecklaufDaten[$datapos],
					$BoileruntenDaten[$datapos],
					$BoilermitteDaten[$datapos],
					$BoilerobenDaten[$datapos],
					$PumpestatusDaten[$datapos],
					$ElektrostatusDaten[$datapos],
					#$Maxordinate[$datapos],
					);
					}
					}
				}

			}
		
			else // Daten mit VOID fuellen
			{
				$Maxordinate[$datapos]='';
				$KollektorvorlaufDaten[$datapos]='';
				$KollektorruecklaufDaten[$datapos]='';
				$KollektortemperaturDaten[$datapos]='';
				//echo "$startindex $KollektortemperaturDaten[$startindex]<br>";
		
				$BoileruntenDaten[$datapos]='';
				$BoilermitteDaten[$datapos]='';
				$BoilerobenDaten[$datapos]='';
				//$SolarstatusDaten[$datapos]=0;
				//$ElektrostatusDaten[$datapos]=0;
				if ($datapos==0)
				{
					$PumpestatusDaten[0]=0;
					$ElektrostatusDaten[0]=0;
				}	
	
				else
				{
					#$PumpestatusDaten[$datapos]=NULL;
					#$ElektrostatusDaten[$datapos]=NULL;
				}
				//$ZeitachseDaten[$datapos] =intval($datapos/60) + ($startstunde);

			#$LeistungDaten[]=array(' ',$datapos+$startminute, ''
			#,
			#$KollektorvorlaufDaten[$datapos],
			#$KollektorruecklaufDaten[$datapos],
			#$BoileruntenDaten[$datapos],
			#$BoilermitteDaten[$datapos],
			#$BoilerobenDaten[$datapos],
			#);

			}
		
			# Datenarray aufbauen
			
			$LeistungDaten[]=array(' ',$datapos+$startminute, $KollektortemperaturDaten[$datapos]
			#,
			#$KollektorvorlaufDaten[$datapos],
			#$KollektorruecklaufDaten[$datapos],
			#$BoileruntenDaten[$datapos],
			#$BoilermitteDaten[$datapos],
			#$BoilerobenDaten[$datapos],
		
			);
		
			$ZeitachseDaten[$datapos] =intval($datapos/60) + ($startstunde);
#			echo "datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] KollektortemperaturDaten: $KollektortemperaturDaten[$datapos]<br>";
	
		} # if datapos
	
	} // for datapos
	$LeistungDaten[$anzDatenzeilen]=array(' ',$datapos+$startminute+1, 10);
	$ZeitachseDaten[] =$endstunde; // Letzte Stundenbez noch anfuegen
//	echo "max datapos: $datapos dataok: $dataok<br>";
	$zeilennummer=0;
	for ($dataindex=0; $dataindex < count($SolarIntervallArray); $dataindex++)
	{
		if ( $SolarIntervallArray[$dataindex])
		{
		foreach( $SolarIntervallArray[$dataindex] as $teil)
		{
#			echo "$zeilennummer\t$teil\t";
		
		}
		}
		else
		{
#		echo "$zeilennummer\tleer\t";
		}
#		echo "<br>";
		$zeilennummer++;
	}

	
$plot = new PHPlot(1000, 300);
$plot->SetImageBorderType('plain');
$plot->SetXTickIncrement(30);
$plot->SetYTickIncrement(20);
#$plot->SetDrawXGrid(True);
$plot->SetLineWidth(2);
$plot->SetPlotType('lines');
$plot->SetDrawPlotAreaBackground('true');
$plot->SetPlotBgColor('white');
$plot->SetMarginsPixels(NULL, 100);
$plot->SetDataType('data-data');
$plot->SetDataValues($LeistungDaten);
$plot-> SetDrawBrokenLines(True);
#$ruecklauf = 'Zur&uuml;cksetzen';
$charset = "UTF-8";
$ruecklauf = htmlentities("Rücklauf", ENT_QUOTES, "UTF-8");
$ruecklauf = "Ruecklauf";

$plot->SetLegend(array('Koll.temp','Vorlauf', $ruecklauf, 'B.unten', 'B.mitte', 'B.oben', 'Pumpe', 'Elektro'));
$plot->SetLegendPosition(1, 0, 'image', 1, 0, -10, 25);

# Main plot title:
#$plot->SetTitle("Solar $anzDatenzeilen $anzMinuten ");
$plot->SetTitle("Solardaten von heute");
# Make sure Y axis starts at 0:
#$plot->SetPlotAreaWorld(NULL, 0, NULL, NULL);

$plot->SetPlotAreaWorld(NULL, 0, 720, 129);

$plot->DrawGraph();	 

	
	
?>
