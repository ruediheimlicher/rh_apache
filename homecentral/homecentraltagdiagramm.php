<?php 
	require_once "phplot.php";
 	
 	$heutemonat=date('m');
	$heutejahr = date('Y');
	$heutetagdesmonats = date('d');

 	
 	$AnzeigetagPfad="../Data/homediagrammtag.txt";
		
		
	$AnzeigeHandle = fopen($AnzeigetagPfad,"r") or die("Can't open file Solardiagrammtag for read");
		$AnzeigeDatum= fread($AnzeigeHandle,filesize($AnzeigetagPfad));
		$Zeilenarray=explode("-",$AnzeigeDatum);
		$anzeigejahr = $Zeilenarray[0];
		$anzeigemonat = $Zeilenarray[1];
		$anzeigetagdesmonats = $Zeilenarray[2];

	fclose($AnzeigeHandle);	
	if (($heutejahr == $anzeigejahr) && ($heutemonat == $anzeigemonat) && ($heutetagdesmonats == $anzeigetagdesmonats))#es ist heute
	{
	
		$HomedatenPfad = "../Data/HomeDaten.txt";
		$Titelstring = 'Homdaten von heute';
	}
	else
	{
				
		if (strlen($anzeigemonat) < 2) # muss zweistellig sein
		{
			$anzeigemonat = '0'.$anzeigemonat;
		}
		
		if (strlen($anzeigetagdesmonats) < 2) # muss zweistellig sein
		{
			$anzeigetagdesmonats = '0'.$anzeigetagdesmonats;
		}


		$anzeigejahrkurz = $anzeigejahr - 2000;
		$HomedatenPfad = '../Data/HomeDaten/'.$anzeigejahr.'/HomeDaten'.$anzeigejahrkurz.$anzeigemonat.$anzeigetagdesmonats.'.txt';
		$Titelstring = 'Homedaten vom '.$anzeigetagdesmonats.'.'.$anzeigemonat.'.'.$anzeigejahr;
	}
	$savestring = $anzeigetagdesmonats.'_'.$anzeigemonat.'_'.$anzeigejahr;
	$HomedatenHandle = fopen($HomedatenPfad,"r") or die("Can't open file");
	$HomedatenText= fread($HomedatenHandle,filesize($HomedatenPfad));
	fclose($HomedatenHandle);	
	//echo '*<br>';
	$Homedatenarray=explode("\n",$HomedatenText);
		# Erste Zeile mit Daten
	$Daten = explode("\t",$Homedatenarray[6]);
	#echo nl2br($Daten[0]);
	
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
	
	#echo "max intervallindex: $intervallindex anzminuten: $anzMinuten startminute: $startminute<br>";

	# DiagrammDatenarrays mit Werten aus HomeIntervallArray fuellen
	
	$datapos=0;
	$dataok=0;
	
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
			$AussentemperaturDaten[$datapos]=($zeilenarray[3]-32)/2;
			#echo "$pos min: $minute<br>";
			
			
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
				$BrennerstatusDaten[$datapos] = 66;
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
					$UhrstatusDaten[$datapos] = 68;
				}
			}
			elseif(	$tempUhrstatus ==2)
			{
				if ($stundenteil ==0)
				{
					$UhrstatusDaten[$datapos] = 68;
				}
			}
			elseif ($tempUhrstatus ==3)
			{
				$UhrstatusDaten[$datapos] = 68;
			}
			
			$NullDaten[$datapos]=0;
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
				$NullDaten[$datapos]
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
				$UhrstatusDaten[$datapos],
				$NullDaten[$datapos]
				);
		
			}
			

		}
		
		else // Daten mit VOID fuellen
		{
		
			$VorlaufDaten[$datapos]='';
			$RuecklaufDaten[$datapos]='';
			$AussentemperaturDaten[$datapos]='';
			#echo "$startindex $KollektortemperaturDaten[$startindex]<br>";
		
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
			if ($datapos+$startminute<1440)
			{
				$NullDaten[$datapos]=0;
				$LeistungDaten[]=array(' ',$datapos+$startminute,
				$VorlaufDaten[$datapos],
				$RuecklaufDaten[$datapos],
				$AussentemperaturDaten[$datapos],
				$InnentemperaturDaten[$datapos],
				$BrennerstatusDaten[$datapos],
				$UhrstatusDaten[$datapos],
				$NullDaten[$datapos]
				);
}
			
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
		
		$NullDaten[$datapos]=0;
		#$ZeitachseDaten[$datapos] =intval($datapos/60) + ($startstunde);
		$tempminute = $datapos%60;
		#echo "datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] min: $tempminute Vorlauf: $VorlaufDaten[$datapos] Ruecklauf: $RuecklaufDaten[$datapos] Aussen: $AussentemperaturDaten[$datapos] Innen: $InnentemperaturDaten[$datapos] Brenner: $BrennerstatusDaten[$datapos] Uhr: $UhrstatusDaten[$datapos] Homestatus: $HomestatusDaten[$datapos]<br>";
	
	} // for datapos
	$datapos=1;
	#$LeistungDaten[$anzDatenzeilen]=array(' ',$datapos+$startminute+1, 10);
	$ZeitachseDaten[] =$endstunde; // Letzte Stundenbez noch anfuegen
#	echo "max datapos: $datapos dataok: $dataok<br>";


$plot = new PHPlot(1000, 300);
$plot->SetImageBorderType('plain');
$plot->SetXTickIncrement(30);
$plot->SetYTickIncrement(10);
#$plot->SetDrawXGrid(True);

$plot->SetPlotType('lines');

$plot->SetIsInline(true);
$plot->SetDrawPlotAreaBackground('true');


$plot->SetPlotBgColor('white');
$plot->SetMarginsPixels(NULL, 100);
$plot->SetDataType('data-data');
$plot->SetDataColors(array(array(252,25,8),array(85,142,199),array(52,112,201),array(93,238,46),array(253,117,9),array(231,81,141),array(0,0,0)));
$plot->SetLineWidths(array(1,1,1,1,2,2));
$plot->SetXAxisPosition(-20);
$plot->SetDataValues($LeistungDaten);
$plot-> SetDrawBrokenLines(True);
#$ruecklauf = 'Zur&uuml;cksetzen';
$charset = "UTF-8";
$ruecklauf = htmlentities("Rücklauf", ENT_QUOTES, "UTF-8");
$ruecklauf = "Ruecklauf";

$plot->SetLegend(array('Vorlauf', $ruecklauf, 'Aussen', 'Innen', 'Brenner', 'Uhr'));
$plot->SetLegendPosition(1, 0, 'image', 1, 0, -10, 25);
# Main plot title:
$plot->SetTitle("HomeCentral $savestring ");
#$plot->SetTitle($Titelstring);


# Make sure Y axis starts at 0:
#$plot->SetPlotAreaWorld(NULL, 0, NULL, NULL);

$plot->SetPlotAreaWorld(NULL, -20, 1440, 70);
$plot->SetFileFormat("jpg");
#$output_file='../Data/HomeDatendiagramm_'.$savestring.'.jpg';
#$output_file='../Data/diagramme/HomeDatendiagramm_'.$savestring.'.jpg';
$output_file='../Data/diagramme/HomeDatendiagramm.jpg';

$plot->DrawGraph();	 
$plot->SetOutputFile($output_file);
$plot->PrintImage();
#$plot->DrawGraph();	 


	
	
?>
