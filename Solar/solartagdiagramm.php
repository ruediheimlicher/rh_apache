<?php 
	require_once "phplot.php";
 	#date_default_timezone_set("Europe/Zurich");
 	$heutemonat=date('m');
	$heutejahr = date('Y');
	$heutetagdesmonats = date('d');

 	
 	$AnzeigetagPfad="../Data/solardiagrammtag.txt";
		
		
	$AnzeigeHandle = fopen($AnzeigetagPfad,"r") or die("Can't open file Solardiagrammtag for read");
		$AnzeigeDatum= fread($AnzeigeHandle,filesize($AnzeigetagPfad));
		$Zeilenarray=explode("-",$AnzeigeDatum);
		$anzeigejahr = $Zeilenarray[0];
		$anzeigemonat = $Zeilenarray[1];
		$anzeigetagdesmonats = $Zeilenarray[2];

	fclose($AnzeigeHandle);	
	if (($heutejahr == $anzeigejahr) && ($heutemonat == $anzeigemonat) && ($heutetagdesmonats == $anzeigetagdesmonats)) # es ist heute
	{
		$SolardatenPfad = "../Data/solardiagrammdaten.txt";
		$Titelstring = 'Solardaten von heute';
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
		$SolardatenPfad = '../Data/SolardiagrammDaten/'.$anzeigejahr.'/solardiagrammdaten'.$anzeigejahrkurz.$anzeigemonat.$anzeigetagdesmonats.'.txt';
		$Titelstring = 'Solardaten vom '.$anzeigetagdesmonats.'.'.$anzeigemonat.'.'.$anzeigejahr;
	}
	
	#$SolardatenPfad="../Data/solardiagrammdaten.txt";
	$SolardatenHandle = fopen($SolardatenPfad,"r") or die("Can't open file Solardatenpfad");
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
	//echo nl2br($StatusText);
	
	// $Daten = explode("\t",$SolardatenText);
	//echo '*Status<br>';
	
	$Laufzeit;
	$KollektorvorlaufDaten;
	$KollektorruecklaufDaten;
	$KollektortemperaturDaten;
	$BoileruntenDaten;
	$BoilermitteDaten;
	$BoilerobenDaten;
	$SolarstatusDaten;
	$PumpestatusDaten;
	
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
	$oldminute =0;
	for ($dataindex=0; $dataindex < $anzDatenzeilen; $dataindex+=$intervall)
	{
		
		if ($dataindex > $kopfzeilen)
		{
			$element= $Solardatenarray[$dataindex];
			
			$zeilenarray=explode("\t",$element);
				
			$minute=$zeilenarray[4]; // tagsekunden > minuten
			$restminute = $minute%60;
			$stunde=$zeilenarray[3];
			//echo "$zeilenarray[0]  	stunde: $stunde	minute: $minute restminute: $restminute<br>";
			
			if (!($minute == $oldminute)) // erste Datenzeile ist nicht bei 0
			{
				
				$SolarIntervallArray[$intervallindex] = $zeilenarray; // relevante Zeile
				$intervallindex++;
				
				$oldminute = $minute; // Schrittweite
			}
			
			
		} 
		
	} //for dataindex
	
	//echo "max intervallindex: $intervallindex anzminuten: $anzMinuten startminute: $startminute<br>";
	
	
	// DiagrammDatenarrays mit Werten aus SolarIntervallArray fuellen
	
	$datapos=0;
	$dataok=0;
	for ($datapos=0; $datapos < $anzMinuten; $datapos++)
	{
		// SolarIntervallArray an (datapos + startminute vorhanden?
		$zeilenarray= $SolarIntervallArray[$datapos + $startminute]; // Datenzeile an pos ($datapos + $startminute)
		#if ($datapos + $startminute < $intervallindex)
		if ($zeilenarray[0])
		{
			
			//echo "$datapos: $datapos element: $element[0]<br>";
			//$zeilenarray=explode("\t",$element);
			$KollektorvorlaufDaten[$datapos]=($zeilenarray[5])/2;
			$KollektorruecklaufDaten[$datapos]=($zeilenarray[6])/2;
			$KollektortemperaturDaten[$datapos]=($zeilenarray[10])/2;
			//echo "$startindex h: $stunde min: $minute<br>";
		//echo "$datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] KollektortemperaturDaten: $KollektortemperaturDaten[$datapos]<br>";
			
			$BoileruntenDaten[$datapos]=intval($zeilenarray[7])/2;
			$BoilermitteDaten[$datapos]=intval($zeilenarray[8])/2;
			$BoilerobenDaten[$datapos]=intval($zeilenarray[9])/2;
			$tempSolarstatus =intval($zeilenarray[11]);
			$SolarstatusDaten[$datapos]=tempSolarstatus;
			if ($tempSolarstatus & 0x08) // Pumpe ist ON
			{
				$PumpestatusDaten[$datapos] = 128;
				$pumpedatenvorhanden++;
			}
			else
			{
				$PumpestatusDaten[$datapos] = NULL;
			}
			
			if ($tempSolarstatus & 0x10) // Pumpe ist ON
			{
				$ElektrostatusDaten[$datapos] = 126;
				$elektrodatenvorhanden++;
			}
			else
			{
				$ElektrostatusDaten[$datapos] = NULL;
			}
			$dataok++;
			if ($zeilenarray[4]==0)
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
				);
			}
			else
			{
				$LeistungDaten[]=array(' ',$datapos+$startminute, $KollektortemperaturDaten[$datapos]
				,
				$KollektorvorlaufDaten[$datapos],
				$KollektorruecklaufDaten[$datapos],
				$BoileruntenDaten[$datapos],
				$BoilermitteDaten[$datapos],
				$BoilerobenDaten[$datapos],
				$PumpestatusDaten[$datapos],
				$ElektrostatusDaten[$datapos],
				);
		
			}

		}
		
		else // Daten mit VOID fuellen
		{
		
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
//		echo "$datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] KollektortemperaturDaten: $KollektortemperaturDaten[$datapos]<br>";
	
	} // for datapos
	#$LeistungDaten[$anzDatenzeilen]=array(' ',$datapos+$startminute+1, 10);
	$ZeitachseDaten[] =$endstunde; // Letzte Stundenbez noch anfuegen
//	echo "max datapos: $datapos dataok: $dataok<br>";
	
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
$plot->SetTitle("$Titelstring ");

# Make sure Y axis starts at 0:
#$plot->SetPlotAreaWorld(NULL, 0, NULL, NULL);

$plot->SetPlotAreaWorld(NULL, 0, 720, 129);

$plot->DrawGraph();	 


## old Diagramm
	// Fontarrays definieren
/*	
	$Schrift = "pChart/fonts/MankSans.ttf";
	
	$DiagrammFont = array("FontName"=>$Schrift,"FontSize"=>12);
	$AchsenFont = array("FontName"=>$Schrift,"FontSize"=>8);
	$LegendenFont = array("FontName"=>$Schrift,"FontSize"=>10);

	 // SolarData definition 
	$SolarData = new pData;
	 
	 $SolarData->addPoints($ZeitachseDaten,"Stunde");
	 $SolarData->addPoints($KollektortemperaturDaten,"Kollektortemperatur");
	 $SolarData->addPoints($BoilerobenDaten,"BoilerOben");
	 $SolarData->addPoints($BoilermitteDaten,"BoilerMitte");
	 $SolarData->addPoints($BoileruntenDaten,"BoilerUnten");
	 if ($pumpedatenvorhanden)
	 {
	 	$SolarData->addPoints($PumpestatusDaten,"Pumpe");
	 }
	 
	 if ($elektrodatenvorhanden)
	 {
		$SolarData->addPoints($ElektrostatusDaten,"Elektro");
	 }
	//  $SolarData->setSerieWeight("Kollektortemperatur",8);
	 
	$SolarData->setAxisName(0,"Temperatur");
	$SolarData->setSerieDescription("BoilerOben","Boiler oben");
	$SolarData->setSerieDescription("BoilerMitte","Boiler mitte");
	$SolarData->setSerieDescription("BoilerUnten","Boiler unten");

	$SolarData->setSerieDescription("Pumpe","Pumpe");
	$SolarData->setAbscissa("Stunde");
	
	
	 // Initialise the graph
	 $SolarDiagramm = new pImage(750,250,$SolarData);
	 
	 
	 $SolarData->setSerieWeight("Pumpe",1);
	 $SolarData->setSerieWeight("Elektro",1);
	  
	
	 //$myPicture->setFontProperties(array("FontName"=>"../fonts/pf_arma_five.ttf","FontSize"=>6));
	 $SolarDiagramm->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>8));
 	
	//Turn of Antialiasing *
 	$SolarDiagramm->Antialias = TRUE;
	$SolarDiagramm->drawRectangle(7,7,730,223,240,240,240);
	
	#http://www.ruediheimlicher.ch/Solar/SolarDiagramm.php
	 //$SolarDiagramm->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>9));
	$SolarDiagramm->setGraphArea(60,30,680,180);
	$Diagrammfarbe = array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>15);
	$SolarDiagramm->drawFilledRectangle(60,30,680,180,$Diagrammfarbe);
 
	// $SolarDiagrammSkala=array("R"=>191,"G"=>215,"B"=>59);
//	$SolarDiagrammSkala["Pos"] = SCALE_POS_TOPBOTTOM;
	$SolarDiagrammSkala["DrawSubTicks"] = FALSE;
	// $SolarDiagrammSkala["GridAlpha"] = 10;
	$SolarDiagrammSkala["LabelSkip"] = 59;
	$SolarDiagrammSkala["CycleBackground"] = TRUE;
	$SolarDiagrammSkala["SkippedOuterTickWidth"] = 0;
	$SolarDiagrammSkala["InnerTickWidth"] = 0;
	// Skala zeichnen
	$SolarDiagramm->drawScale($SolarDiagrammSkala);
	
	// Nulllinie zeichnen
	$SolarDiagramm->drawThreshold(0,array("Ticks"=>1));

	$SolarDiagramm->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>12));
	$SolarDiagramm->Antialias = TRUE;
	$Config = array("BreakVoid"=>TRUE);
	
	$SolarDiagramm->drawLineChart($Config);
	
	// Legende zeichnen
	$SolarDiagramm->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>10));
	$SolarDiagramm->drawLegend(250,15,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

	$SolarDiagramm->autoOutput("SolarDiagramm.png");
	
*/	
	
	
?>
