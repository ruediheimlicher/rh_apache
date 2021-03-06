<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ErtragDiagramm</title>
</head>

<body>
<h1>ErtragDiagramm</h1>
<? // Tagertrag lesen
	$ErtragPfad="../Data/SolarTagErtrag.txt";
	$ErtragHandle = fopen($ErtragPfad,"r") or die("Can't open file");
	$ErtragText= fread($ErtragHandle,filesize($ErtragPfad));
	//echo nl2br($ErtragText);
	$ErtragZeilenarray=explode("\n",$ErtragText);
	//echo "<br>Zeilenarray: $Zeilenarray[2]";
	fclose($ErtragHandle);
	/*	
	echo "raw<br>";
	foreach($ErtragZeilenarray as $zeile)
	{
		$l = strlen($zeile);
		//echo "i: $i Wert: l: $l $zeile<br>";
	//	echo "i: $i Wert: laenge: $l <br>";
		$i++;
	}
	*/
	
	foreach ($ErtragZeilenarray as $key => $value) 
	{ 
 	 	if (strlen($value) == 0) 
		{ 
    		unset($ErtragZeilenarray[$key]); 
  		} 
	} 

	$ErtragZeilenarray = array_values($ErtragZeilenarray);
	
	
	//echo "nach filter<br>";		
	$i=0;
	/*
	foreach($ErtragZeilenarray as $zeile)
	{
		$l = strlen($zeile);
		//echo "i: $i Wert: l: $l $zeile<br>";
		$tagzeile = explode("\t",$zeile);
		echo "i: $i Wert: laenge: $l Datum: $tagzeile[0]<br>";
		$i++;
	}
	*/
	$anzErtragZeilen = count($ErtragZeilenarray);
	echo "ErtragZeilenarray zeilen: $anzErtragZeilen<br>";
	$lastTagertrag= $ErtragZeilenarray[$anzErtragZeilen-1];
	echo "lastTagertrag: $lastTagertrag<br>";
	
	 $ErtragDaten = explode("\t",$lastTagertrag);
	//echo "Statustext: $Statustext<br>";
	$tagertragindex=0;
	/*
	foreach ($ErtragDaten as $tagertragelement) 
	{
		echo "element: $tagertragindex Wert: $tagertragelement<br>";
		$tagertragindex++;
	}
	*/
	$Datum = $ErtragDaten[0];
	
	unset($ErtragDaten[0]); 
	//$ErtragDaten = array_values($ErtragDaten);
	echo "Datum: $Datum Datenarray: <br>";
	
	$ZeitachseDaten;
	
	$tagertragindex=0;
	foreach ($ErtragDaten as $tagertragelement) 
	{
		$ZeitachseDaten[] = tagertragindex;
		echo "element: $tagertragindex Wert: $tagertragelement<br>";
		$tagertragindex++;
	}	
	
	// Diagramm anlegen
		// Fontarrays definieren
	
	$Schrift = "pChart/fonts/MankSans.ttf";
	
	$DiagrammFont = array("FontName"=>$Schrift,"FontSize"=>12);
	$AchsenFont = array("FontName"=>$Schrift,"FontSize"=>8);
	$LegendenFont = array("FontName"=>$Schrift,"FontSize"=>10);

	 // ErtragData definition 
	$ErtragData = new pData;
	 
	 $ErtragData->addPoints($ZeitachseDaten,"Stunde");
	 $ErtragData->addPoints($ErtragDaten,"Ertrag");
	//  $ErtragData->setSerieWeight("Kollektortemperatur",8);
	 
	$ErtragData->setAxisName(0,"Ertrag");

	$ErtragData->setAbscissa("Stunde");
	
	
	 // Initialise the graph
	 $ErtragDiagramm = new pImage(750,250,$ErtragData);
	 
	
	 //$myPicture->setFontProperties(array("FontName"=>"../fonts/pf_arma_five.ttf","FontSize"=>6));
	 $ErtragDiagramm->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>8));
 	
	//Turn of Antialiasing *
 	$ErtragDiagramm->Antialias = TRUE;
	$ErtragDiagramm->drawRectangle(7,7,730,223,240,240,240);
	
	
	 //$ErtragDiagramm->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>9));
	$ErtragDiagramm->setGraphArea(60,30,680,180);
	$Diagrammfarbe = array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>15);
	$ErtragDiagramm->drawFilledRectangle(60,30,680,180,$Diagrammfarbe);
 
	// $ErtragDiagrammSkala=array("R"=>191,"G"=>215,"B"=>59);
//	$ErtragDiagrammSkala["Pos"] = SCALE_POS_TOPBOTTOM;
	$ErtragDiagrammSkala["DrawSubTicks"] = FALSE;
	// $ErtragDiagrammSkala["GridAlpha"] = 10;
	//$ErtragDiagrammSkala["LabelSkip"] = 59;
	$ErtragDiagrammSkala["CycleBackground"] = TRUE;
	$ErtragDiagrammSkala["SkippedOuterTickWidth"] = 0;
	$ErtragDiagrammSkala["InnerTickWidth"] = 0;
	// Skala zeichnen
	$ErtragDiagramm->drawScale($ErtragDiagrammSkala);
	
	// Nulllinie zeichnen
	$ErtragDiagramm->drawThreshold(0,array("Ticks"=>1));

	$ErtragDiagramm->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>12));
	$ErtragDiagramm->Antialias = TRUE;
	$Config = array("BreakVoid"=>TRUE);
	
	$ErtragDiagramm->drawLineChart($Config);
	
	// Legende zeichnen
	$ErtragDiagramm->setFontProperties(array("FontName"=>"pChart/fonts/MankSans.ttf","FontSize"=>10));
	$ErtragDiagramm->drawLegend(250,15,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

	$ErtragDiagramm->autoOutput("ErtragDiagramm.png");
	

	
?>
</body></html>