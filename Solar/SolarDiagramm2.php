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
	
	$startindex=0;
	$index=0;
	$oldminute=0;
	$startstunde = 0;
	$endstunde = 24;
	$oldstunde = $startstunde;
	$SkalaDaten;
	$ZeitachseDaten;
	
	$anzDatenzeilen =  count($Solardatenarray);
	$anzMinuten = ($endstunde-$startstunde)*60;
	// Datenarrays fuellen
	$i=0;
	for ($i=0;$i< $anzMinuten;$i++) // Anzahl Minuten im Bereich
	{
		$Laufzeit[] = 0;
		$KollektorvorlaufDaten[]=VOID;
		$KollektorruecklaufDaten[]=VOID;
		$KollektortemperaturDaten[]=VOID;
		//echo "$startindex $KollektortemperaturDaten[$startindex]<br>";
		
		$BoileruntenDaten[]=VOID;
		$BoilermitteDaten[]=VOID;
		$BoilerobenDaten[]=VOID;
		$SolarstatusDaten[]=0;
		if ($i==0)
		{
			$PumpestatusDaten[0]=0;
			$ElektrostatusDaten[$i]=0;
		}	
	
		else
		{
			$PumpestatusDaten[$i]=VOID;
			$ElektrostatusDaten[$i]=0;
		}
		$ZeitachseDaten[] =intval($i/60) + ($startstunde);
		
		//$ZeitachseDaten[]="";
		/*
		if ($i%60 ==0) // Stundenmarker setzen
		{
			$oldstunde +=1;
			//$x =  intval($i/60) + ($startstunde)+1;
			$ZeitachseDaten[] =  intval($i/60) + ($startstunde)+1;
			$SkalaDaten[] = intval($i/60) + ($startstunde);
			$x=intval($i/60) + ($startstunde);
			//echo "$i $x<br>";
			//$oldstunde =  intval($i/60) + ($startstunde);
		}
		else
		{
			$ZeitachseDaten[] =intval($i/60) + ($startstunde)+1;
			$SkalaDaten[] ="";
		}
		*/
		//echo "$i $ZeitachseDaten[$i]<br>";
	}
	$PumpestatusDaten[0]=0;
	$ElektrostatusDaten[0]=0;
	//$ZeitachseDaten[] =$endstunde;
	
	//echo "Anzahl Elemente: $i<br>";
	
	//echo "startindex: $startindex<br>";
	//echo count($Solardatenarray);
	foreach ($Solardatenarray as $element) 
	//for ($i=0; $i< anzDatenzeilen;$i++)
	{
		//$element=
		if ($index>5)
		{
			//echo" $startindex  $element<br>";
			
			
			$zeilenarray=explode("\t",$element);
			
			$minute=round(intval($zeilenarray[0])/60);
			$restminute = $minute%60;
			$stunde= intval($minute/60);
			//echo "$zeilenarray[0]  	stunde: $stunde	minute: $minute restminute: $restminute<br>";
			
			//if ($minute > $oldminute)
			if ($stunde >= $startstunde && $stunde <= $endstunde)
			{
				//if ($stunde >= $startstunde && $stunde <= $endstunde)
				if ($minute > $oldminute)
				{
					//echo "index: $index startindex: $startindex zeilenarray0: $zeilenarray[0] stunde: $stunde	minute: $minute restminute: $restminute<br>";
					$oldminute = $minute;
					$Laufzeit[$startindex] = $zeilenarray[0];
					
					
					$KollektorvorlaufDaten[$startindex]=intval($zeilenarray[1])/2;
					$KollektorruecklaufDaten[$startindex]=intval($zeilenarray[2])/2;
					$KollektortemperaturDaten[$startindex]=intval($zeilenarray[6])/2;
					//echo "$startindex h: $stunde min: $minute<br>";
					
					$BoileruntenDaten[$startindex]=intval($zeilenarray[3])/2;
					$BoilermitteDaten[$startindex]=intval($zeilenarray[4])/2;
					$BoilerobenDaten[$startindex]=intval($zeilenarray[5])/2;
					$tempSolarstatus =intval($zeilenarray[7]);
					$SolarstatusDaten[$startindex]=tempSolarstatus;
					if ($tempSolarstatus & 0x08) // Pumpe ist ON
					{
						$PumpestatusDaten[$startindex] = 1;
					
					}
					else
					{
						$PumpestatusDaten[$startindex] = VOID;
					}
					
					if ($tempSolarstatus & 0x10) // Pumpe ist ON
					{
						$ElektrostatusDaten[$startindex] = 1;
					
					}
					else
					{
						$ElektrostatusDaten[$startindex] = VOID;
					}
					
					// bei Startstunde =6: $stunde+1 
					$ZeitachseDaten[$startindex] = $stunde;
					
					//echo "$zeilenarray[0] $startindex  Laufzeit: $Laufzeit[$startindex]  Stunde: $stunde Minute: $oldminute<br>";
					$startindex++;
				}// if oldminute
				else
				{
					$ElektrostatusDaten[$startindex] = VOID;
				}

				
				
			}// startstunde
		}
		//echo "$KollektorvorlaufDaten[index]<br>";
		$index++;
	}
		
		
		// 
		
//		echo "Stunde: $stunde Minute: $oldminute";
//		echo "Anzahl Zeilen: $index Anzahl geladene Daten: $startindex<br>";
		
		
		
	


	 /*
		 Example6 : A simple filled line graph
	 */
	/*
	 // Standard inclusions   
	 include("pChart/pData.class");
	 include("pChart/pChart.class");
	*/ 
	  /* pChart library inclusions */
	  /*
 	include("pChart/class/pData.class.php");
 	include("pChart/class/pDraw.class.php");
 	include("pChart/class/pImage.class.php");

	*/
	
	// Fontarrays definieren
	
	$Schrift = "pChart/fonts/MankSans.ttf";
	
	$DiagrammFont = array("FontName"=>$Schrift,"FontSize"=>12);
	$AchsenFont = array("FontName"=>$Schrift,"FontSize"=>8);
	$LegendenFont = array("FontName"=>$Schrift,"FontSize"=>10);

	 // SolarData definition 
	$SolarData = new pData;
	 
	 $SolarData->addPoints($ZeitachseDaten,"Stunde");
	 $SolarData->addPoints($KollektortemperaturDaten,"Kollektortemperatur");
	 $SolarData->addPoints($BoilerobenDaten,"Boilertemperatur");
	 $SolarData->addPoints($PumpestatusDaten,"Pumpe");
	 $SolarData->addPoints($ElektrostatusDaten,"Elektro");
	 
	//  $SolarData->setSerieWeight("Kollektortemperatur",8);
	 
	$SolarData->setAxisName(0,"Temperatur");
	$SolarData->setSerieDescription("Pumpe","Pumpe");
	$SolarData->setAbscissa("Stunde");
	
	
	 // Initialise the graph
	 $SolarDiagramm = new pImage(850,250,$SolarData);
	 
	 
	 $SolarData->setSerieWeight("Pumpe",1);
	 $SolarData->setSerieWeight("Elektro",1);
	  
	
	 //$myPicture->setFontProperties(array("FontName"=>"../fonts/pf_arma_five.ttf","FontSize"=>6));
	 $SolarDiagramm->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>8));
 	
	/* Turn of Antialiasing */
 	$SolarDiagramm->Antialias = TRUE;
	$SolarDiagramm->drawRectangle(7,7,730,223,240,240,240);
	
	
	 //$SolarDiagramm->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>9));
	$SolarDiagramm->setGraphArea(60,30,680,180);
	 
	// $SolarDiagrammSkala=array("R"=>191,"G"=>215,"B"=>59);
//	$SolarDiagrammSkala["Pos"] = SCALE_POS_TOPBOTTOM;
	$SolarDiagrammSkala["DrawSubTicks"] = FALSE;
	// $SolarDiagrammSkala["GridAlpha"] = 10;
	$SolarDiagrammSkala["LabelSkip"] = 60;
	$SolarDiagrammSkala["CycleBackground"] = TRUE;
	$SolarDiagrammSkala["SkippedOuterTickWidth"] = 0;
	
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
	$SolarDiagramm->drawLegend(390,15,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

	$SolarDiagramm->autoOutput("SolarDiagramm.png");
	
	
	
	
?>
