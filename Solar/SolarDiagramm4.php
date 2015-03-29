<?php 
	  /* pChart library inclusions */
 	include("pChart/class/pData.class.php");
 	include("pChart/class/pDraw.class.php");
 	include("pChart/class/pImage.class.php");

	$SolardatenPfad="../Data/SolarDaten.txt";
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
	$kopfzeilen = 5; // Kopfbereich der Solardaten
	$SolarIntervallArray; // Daten im Abstand von $intervall, nullbasiert
	
	$pumpedatenvorhanden = 0;
	$elektrodatenvorhanden = 0;
	
	for ($dataindex=0; $dataindex < $anzDatenzeilen; $dataindex+=$intervall)
	{
		
		if ($dataindex > $kopfzeilen)
		{
			$element= $Solardatenarray[$dataindex];
			
			$zeilenarray=explode("\t",$element);
				
			$minute=round(intval($zeilenarray[0])/60); // tagsekunden > minuten
			$restminute = $minute%60;
			$stunde= intval($minute/60);
			//echo "$zeilenarray[0]  	stunde: $stunde	minute: $minute restminute: $restminute<br>";
			
			if ($minute > $oldminute) // erste Datenzeile ist nicht bei 0
			{
				
				$SolarIntervallArray[$intervallindex] = $zeilenarray; // relevante Zeile
				$intervallindex++;
				
				$oldminute+= $intervall; // Schrittweite
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
		
		if ($datapos + $startminute < $intervallindex)
		{
			$zeilenarray= $SolarIntervallArray[$datapos + $startminute]; // Datenzeile an pos ($datapos + $startminute)
			//echo "$datapos: $datapos element: $element[0]<br>";
			//$zeilenarray=explode("\t",$element);
			$KollektorvorlaufDaten[$datapos]=intval($zeilenarray[1])/2;
			$KollektorruecklaufDaten[$datapos]=intval($zeilenarray[2])/2;
			$KollektortemperaturDaten[$datapos]=intval($zeilenarray[6])/2;
			//echo "$startindex h: $stunde min: $minute<br>";
		//echo "$datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] KollektortemperaturDaten: $KollektortemperaturDaten[$datapos]<br>";
			
			$BoileruntenDaten[$datapos]=intval($zeilenarray[3])/2;
			$BoilermitteDaten[$datapos]=intval($zeilenarray[4])/2;
			$BoilerobenDaten[$datapos]=intval($zeilenarray[5])/2;
			$tempSolarstatus =intval($zeilenarray[7]);
			$SolarstatusDaten[$datapos]=tempSolarstatus;
			if ($tempSolarstatus & 0x08) // Pumpe ist ON
			{
				$PumpestatusDaten[$datapos] = 1;
				$pumpedatenvorhanden++;
			}
			else
			{
				$PumpestatusDaten[$datapos] = VOID;
			}
			
			if ($tempSolarstatus & 0x10) // Pumpe ist ON
			{
				$ElektrostatusDaten[$datapos] = 1;
				$elektrodatenvorhanden++;
			}
			else
			{
				$ElektrostatusDaten[$datapos] = VOID;
			}
			$dataok++;

		}
		else // Daten mit VOID fuellen
		{
			$KollektorvorlaufDaten[$datapos]=VOID;
			$KollektorruecklaufDaten[$datapos]=VOID;
			$KollektortemperaturDaten[$datapos]=VOID;
			//echo "$startindex $KollektortemperaturDaten[$startindex]<br>";
		
			$BoileruntenDaten[$datapos]=VOID;
			$BoilermitteDaten[$datapos]=VOID;
			$BoilerobenDaten[$datapos]=VOID;
			//$SolarstatusDaten[$datapos]=0;
			//$ElektrostatusDaten[$datapos]=0;
			if ($datapos==0)
			{
				$PumpestatusDaten[0]=0;
				$ElektrostatusDaten[0]=0;
			}	
	
			else
			{
				$PumpestatusDaten[$datapos]=VOID;
				$ElektrostatusDaten[$datapos]=0;
			}
			//$ZeitachseDaten[$datapos] =intval($datapos/60) + ($startstunde);

		
		}
		$ZeitachseDaten[$datapos] =intval($datapos/60) + ($startstunde);
//		echo "$datapos: $datapos ZeitachseDaten: $ZeitachseDaten[$datapos] KollektortemperaturDaten: $KollektortemperaturDaten[$datapos]<br>";
	
	} // for datapos
	$ZeitachseDaten[] =$endstunde; // Letzte Stundenbez noch anfuegen
//	echo "max datapos: $datapos dataok: $dataok<br>";
	

	// Fontarrays definieren
	
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
	
	
	
	
?>
