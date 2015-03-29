<?php
ob_start(); //Startet Ausgabepuffer http://www.flexib.de/php-fehler-cannot-modify-header-information-headers-already-sent/
$AnzeigetagPfad="../Data/homediagrammtag.txt";
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
#$downloadfile = "../Data/diagramme/HomeDatendiagramm_".$anzeigetagdesmonats."_".$anzeigemonat."_".$anzeigejahr.".jpg";
#$downloadfile = "../Data/HomeDatendiagramm_".$anzeigetagdesmonats."_".$anzeigemonat."_".$anzeigejahr.".jpg";
$downloadfile = "../Data/diagramme/HomeDatendiagramm.jpg";

$filename = "HomeDatendiagramm_".$anzeigetagdesmonats."_".$anzeigemonat."_".$anzeigejahr.".jpg";
$filesize = filesize($downloadfile);
header("Content-Type: image/jpeg"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Content-Length: $filesize");
ob_end_flush(); //Beendet Ausgabepuffer und Ausgabe des Inhaltes
if (file_exists($downloadfile))
{
	readfile($downloadfile);
}

exit;
?>