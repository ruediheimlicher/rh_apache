#!/usr/bin/perl  
use CGI::Carp qw( fatalsToBrowser );
use LWP::UserAgent;
#use cPanelUserConfig;
print "Content-type: text/html\n\n"; 

use strict;

#http://stackoverflow.com/questions/753346/how-do-i-set-the-timezone-for-perls-localtime
use POSIX qw(tzset);
$ENV{TZ} = 'Europe/Zurich';
tzset;
my @zeit=localtime();

my $jahr=$zeit[5]-100;
if (length($jahr) == 1)
{
	$jahr="0$jahr";
}

my $monat = $zeit[4]+1;
if (length($monat) == 1)
{
	$monat="0$monat";
}
my $tag= $zeit[3];
if(length($tag) == 1)
{
   $tag="0$tag";
}
my $stunde = $zeit[2];
if(length($stunde) == 1)
{
   $stunde="0$stunde";
}
my $min = $zeit[1];
if(length($min) == 1)
{
   $min="0$min";
}
my $sec = $zeit[0];
if(length($sec) == 1)
{
   $sec="0$sec";
}
my $tagsekunde = ((($zeit[2] * 60) + $zeit[1]) * 60) + $zeit[0];

my $Datumstring = "Datum: $tag.$monat.$jahr $stunde:$min";
printf	"Datumstring heute: $Datumstring<br>";
printf	"tagsekunde: $tagsekunde<br>";

my $startDatumstring = "Datum: $tag.$monat.$jahr 00:00";
printf	"startDatumstring: $startDatumstring<br>";

# Last Time laden
open my $PREFS, "<../Data/SolarCentralPrefs.txt" || die "SolarCentralPrefs.txt nicht gefunden\n";
my @prefstat = stat($PREFS);
my $prefsize=$prefstat[7];
my @feld=<$PREFS>;
my $anzZeilen=@feld;
close($PREFS);
#print $PREFS "size $prefsize\n";
# Wenn leer: neue Datei
#if ($prefsize == 0)
if($anzZeilen == 0) # leeres File
{
	open $PREFS, ">../Data/SolarCentralPrefs.txt" || die "Prefs 2 nicht gefunden\n";
	#print $PREFS "N $Datumstring";
	my $lastDatumstring=$Datumstring;
	print $PREFS "$Datumstring";
	close $PREFS;
	
	open my $TIMEPREFS, ">../Data/SolarTimePrefs.txt" || die "SolarTimePrefs nicht gefunden\n";
	print $TIMEPREFS time();
	close $TIMEPREFS;


	open my $LOGFILE, ">>../Data/SolarLog.txt" || die " Auch dieses Logfile nicht gefunden\n";
	print $LOGFILE "neues File: $Datumstring\n";

	close $LOGFILE;
	
}
else # Prefs schon vorhanden, aktualisieren
{
	# SolarCentralPrefs oeffnen
	open $PREFS, ">../Data/SolarCentralPrefs.txt" || die "Prefs 3 nicht gefunden\n";
	my $linien=0;
	my $eintrag=0;
	my $lastDatumstring=$feld[0];

	#neues Datum in SolarCentralPrefs einsetzen
	print $PREFS "$Datumstring\n";
	close $PREFS;
	
	# SolarTimePrefs oeffnen: Datum als Zahl
	open my $TIMEPREFS, "<../Data/SolarTimePrefs.txt" || die "SolarTimePrefs 4 nicht gefunden\n";
	my @prefzeit = <$TIMEPREFS>;
	printf "TimePrefs: $prefzeit[0]<br>";
	my $anzPrefsZeilen=@prefzeit;
	if ($anzPrefsZeilen == 0)
	{
		open my $LOGFILE, ">>../Data/SolarLog.txt" || die "SolarLogfile nicht gefunden\n";
		#print LOGFILE "solar.pl: Kein SolarLog gefunden $lastDatumstring\n";
		print $LOGFILE "SolarTimePrefs anzPrefszeilen == 0  $Datumstring\n";
		close $LOGFILE;

	}
	else
	{
		
		my $laufzeit = $tagsekunde;
		my $laufzeitausprefs=time() - $prefzeit[0];
		
		# Kontrolle der Differnez, sollte konstant sein
		my $diff= $laufzeit - $laufzeitausprefs;
		
		
		#open $LOGFILE, ">>../Data/SolarLog.txt" || die "Z 130 Logfile nicht gefunden\n";
		#print $LOGFILE "laufzeitausprefs: $laufzeitausprefs laufzeit: $laufzeit Diff: $diff\n";
		#print $LOGFILE "anzPrefszeilen: $anzPrefsZeilen	 prefzeit [0]: $prefzeit[0]	 laufzeit: $laufzeit  Diff: $diff\n";
		#close $LOGFILE;

	}
	close($TIMEPREFS);

	
}


#open my $SOLARDIAGRAMMDATEI, ">>../Data/solardiagrammdaten.txt" || die "SOLARDIAGRAMMDATEI nicht gefunden\n";

my @Statistik=stat(my $SOLARDATEI);
my $size=$Statistik[7];

#open LOGFILE, ">>../Data/SolarLog.txt" || die "Logfile nicht gefunden\n";
#print LOGFILE "Die Datei SolarDaten.txt ist $size Bytes gross.\n";
#close(LOGFILE);
my @homedatei=0;
my $anzSolardateiZeilen=0;

#		Solardatei fuer Lesen oeffnen
open my $SOLARDATEI, "<../Data/SolarDaten.txt" || die "SOLARDATEI nicht gefunden\n";
@homedatei = <$SOLARDATEI>;
$anzSolardateiZeilen=@homedatei;

print '<p>anzSolardateiZeilen: '.$anzSolardateiZeilen.'</p> ';


my $lastSolarData = $homedatei[$anzSolardateiZeilen-1];
my @lastSolarDataArray = split("\t",$lastSolarData);

printf "%v02X\n", 255.255.255.240;
print '<p>lastSolarData *'.$lastSolarData.'*</p> ';

#open $SOLARDATEI, ">../Data/SolarDaten.txt" || die "SolarDaten nicht gefunden\n";

#foreach (@homedatei)
#{
	#print $SOLARDATEI "$_";
	#print  "$_<br>";
#}
#close ($SOLARDATEI);
$lastSolarDataArray[7] = sprintf("%X",$sec);
my $zeile=0;
foreach (@lastSolarDataArray)
{
	my $temphex = sprintf("%X",$_);
	print  "$zeile \t$_ \t$temphex<br>";
	$zeile++;
	
}


my $POSTSolarDatenstring = "http://localhost/public_html/cgi-bin/solar_exp.pl?";

my $scriptstring="http://localhost/public_html/cgi-bin/solar_exp.pl";
my $parameterstring = "";
$POSTSolarDatenstring .= "pw=Pong";

my $index=0;
for ($index=0;$index < 8; $index++)
{

	my $temphex = sprintf("%X",@lastSolarDataArray[$index+1]);
	$POSTSolarDatenstring .= "&d".$index."\=".$temphex;
	if ($index)
	{
		$parameterstring .= "&";
	}
	$parameterstring .= "d".$index."\=".$temphex;
	
}
print "*$POSTSolarDatenstring*<br>";
print "parameterstring: $parameterstring<br>";



#http://www.perl.com/pub/2002/08/20/perlandlwp.html
my $ua = LWP::UserAgent->new;

my $url = 'http://localhost/public_html/cgi-bin/solar_exp.pl';
$url = $POSTSolarDatenstring;
my $response = $ua->get($url);
die "Can't get $url -- ", $response->status_line
   unless $response->is_success;

#$response->content_type('application/x-www-form-urlencoded');
#$response->content($parameterstring);

my $content = $response->content; 
print "<br>content: *$content*<br>";
# Variablen von Elektrodatei
my @Elektrodatei=0;
my $anzElektrodateiZeilen=0;

my $oldLaufzeit=0;					# Laufzeit beim letzten Aufruf
my $oldElektrolaufzeit=0;			# bisher aufgelaufene Elektrozeit
my $oldElektrostatus=0;				# Status beim letzten Aufruf

#Variablen von Pumpedatei
my @Pumpedatei=0;
my $anzPumpedateiZeilen=0;
my $oldPumpeLaufzeit=0;				# Laufzeit beim letzten Aufruf
my $oldPumpelaufzeit=0;				# bisher aufgelaufene Pumpezeit
my $oldPumpestatus=0;				# Status beim letzten Aufruf
my $Pumpestartzeit=0;				# Zeit beim Beginn einer neuen Aktivität

open my $ELEKTRODATEI, "<../Data/ElektroDaten.txt" || die "ELEKTRODATEI nicht gefunden\n";
@Elektrodatei = <$ELEKTRODATEI>;
chomp(@Elektrodatei);
$anzElektrodateiZeilen=@Elektrodatei;


print <<EOF ; 

<html>
<head> 
<title>run_solar</title> 
</head> 
<body> 
<h2>run_solar</h2> 

</body> 
</html>
EOF

exit;