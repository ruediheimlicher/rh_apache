#!/usr/bin/perl -w
use CGI::Carp qw( fatalsToBrowser );
use Net::SMTP;
#
#
use strict;
my $NULL=0.0;
my @prefdok=();
my (@dok)=();
my ($size)=0;
my ($prefsize)=0;
my @DatenzeilenArray=();
my $Datenwert = 0;
my $Datumstring=0;
my $lastDatumstring=0;

my $Tagstring=0;
my $Zeitstring = 0;


my @zeit=0;
my $jahr=0;
my $monat=0;
my $tag =0; 
my $stunde=0;
my $min =0;
my $sec =0;
my $tagsekunde=0;
my $laufzeit=0;

my $Stromlaufzeit=0;
my $stromstatus=0;
my $anzStatusZeilen=0;



my $Pumpelaufzeit=0;
my $Pumpestatus=0;



my %cgivars;
my @Statistik=0;

my @prefzeit=0;
my $anzPrefsZeilen;


# Strom
my $lastLeistung=0;
my $prevlastLeistung=0;

my $prevSavezeit=0;
my $Savezeit=0;

my $prevSekunde=0;

my $timeprefs=time();

# First, get the CGI variables into a list of strings
%cgivars= &getcgivars ;

# Print the CGI response header, required for all HTML output ****WICHTIG****
# Note the extra \n, to send the blank line
print "Content-type: text/html\n\n" ;
# Fuer reinen Text: Conten-type text/plain
# So werden newlines als Zeilenschaltung interpretiert
# print "Content-type: text/plain\n\n" ;

# cgivars in Textfile sichern
@zeit=localtime();

$jahr=$zeit[5]-100;
if (length($jahr) == 1)
{
	$jahr="0$jahr";
}

$monat = $zeit[4]+1;
if (length($monat) == 1)
{
	$monat="0$monat";
}
$tag= $zeit[3];
if(length($tag) == 1)
{
   $tag="0$tag";
}
$stunde = $zeit[2];
if(length($stunde) == 1)
{
   $stunde="0$stunde";
}
$min = $zeit[1];
if(length($min) == 1)
{
   $min="0$min";
}
$sec = $zeit[0];
if(length($sec) == 1)
{
   $sec="0$sec";
}

# sekunde des laufenden Tages
$tagsekunde = ((($zeit[2] * 60) + $zeit[1]) * 60) + $zeit[0];

# Datum des aktuellen Tages
$Datumstring = "$tag:$monat:$jahr $stunde:$min";

$Tagstring = "$tag.$monat.$jahr";
$Zeitstring = "$stunde:$min:$sec";
printf	"Datumstring heute: $Datumstring<br>";
printf	"tagsekunde: $tagsekunde<br>";

#print `pwd`;
#print `ls \`pwd/../public_html\``;
#my $PWD = `pwd`; chomp $PWD;
#print "Datenverzeichnis: \"", $PWD, "/../public_html/Data\"\n";
#print `ls $PWD/../public_html/Data`;

#printf	 "zeit[3]: $zeit[3] tag: $tag<br>";
#printf	 "zeit[2]: $zeit[2] stunde: $stunde<br>";
#printf	 "zeit[1]: $zeit[1] min: $min<br>";
open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "Z 130 Logfile nicht gefunden\n";
print LOGFILE "Messung: \t$Datumstring*\n";
close(LOGFILE);

#
# Prefs lesen, neues Datum einsetzen
#

# Last Time laden
open PREFS, "<../public_html/Data/StromDaten/StromPrefs.txt" || die "StromPrefs.txt nicht gefunden\n";
my @prefstat = stat(PREFS);
$prefsize=$prefstat[7];
my @feld=<PREFS>;
my $anzZeilen=@feld;
close(PREFS);
#print PREFS "size $prefsize\n";
# Wenn leer: neue Datei
#if ($prefsize == 0)
if($anzZeilen == 0) # leeres File
{
	open PREFS, ">../public_html/Data/StromDaten/StromPrefs.txt" || die "Prefs 2 nicht gefunden\n";
	#print PREFS "N $Datumstring";
	$lastDatumstring=$Datumstring;
	print PREFS "$Datumstring";
	close PREFS;
	
	open TIMEPREFS, ">../public_html/Data/StromDaten/stromtimeprefs.txt" || die "TimePrefs nicht gefunden\n";
	print TIMEPREFS time();
	close TIMEPREFS;


	open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die " Auch dieses Logfile nicht gefunden\n";
	print LOGFILE "neues File: $Datumstring\n";

	close LOGFILE;
	
}
else # StromPrefs schon vorhanden, aktualisieren
{
	# StromPrefs oeffnen
	open PREFS, ">../public_html/Data/StromDaten/StromPrefs.txt" || die "Prefs 3 nicht gefunden\n";
	my $linien=0;
	my $eintrag=0;
	$lastDatumstring=$feld[0];
	printf "TimePrefs lastDatumstring: $lastDatumstring<br>";
	#neues Datum in StromPrefs einsetzen
	print PREFS "$Datumstring\n";
	close (PREFS);
	
	# stromtimeprefs oeffnen: Datum als Zahl
	open TIMEPREFS, "<../public_html/Data/StromDaten/stromtimeprefs.txt" || die "stromtimeprefs 4 nicht gefunden\n";
	@prefzeit = <TIMEPREFS>;
	printf "TimePrefs: $prefzeit[0]<br>";
	$anzPrefsZeilen=@prefzeit;
	if ($anzPrefsZeilen == 0)
	{
	# neu 140801
		$lastDatumstring=$Datumstring;
		#print PREFS "$Datumstring";
		open TIMEPREFS, ">../public_html/Data/StromDaten/stromtimeprefs.txt" || die "stromtimeprefs 4 nicht gefunden\n";

		print TIMEPREFS time();
		
	# end neu 140801
		
		open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "StromLogfile nicht gefunden\n";
		print LOGFILE "strom.pl: Keine TimePrefs gefunden \t";
		print LOGFILE "stromtimeprefs anzPrefszeilen == 0  $timeprefs\t$Datumstring\n";
		close LOGFILE;

	}
	else
	{
		
		$laufzeit = $tagsekunde;
		my $laufzeitausprefs=time() - $prefzeit[0];
		
		# Kontrolle der Differnez, sollte konstant sein
		my $diff= $laufzeit - $laufzeitausprefs;
		
		open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "Z 130 Logfile nicht gefunden\n";
		print LOGFILE "laufzeitausprefs\tprefzeit: $timeprefs  laufzeitausprefs: $laufzeitausprefs laufzeit: $laufzeit Diff: $diff\n";
		#print LOGFILE "anzPrefszeilen: $anzPrefsZeilen	 prefzeit [0]: $prefzeit[0]	 laufzeit: $laufzeit  Diff(konstant): $diff\n";
		
		

	}
	close(TIMEPREFS);
	close LOGFILE;
	
} # prefs aktualisieren

# Stromstatus sichern
#lesen
open STROMSTATUSDATEI, "<../public_html/Data/StromDaten/stromstatus.txt" || die "STROMSTATUSDATEI nicht gefunden\n";
my @laststatus=<STROMSTATUSDATEI>;
my @lastStromstatusarray = split("\t",$laststatus[0]);
$lastLeistung = $lastStromstatusarray[6];

if ($lastLeistung == 0) # Fehler
{
	$lastLeistung=5;
}

$prevSekunde = $lastStromstatusarray[5];
if (@lastStromstatusarray >7 ) # last Status ist Data
{
	$prevlastLeistung = $lastStromstatusarray[7];
}
if (@lastStromstatusarray >8 ) # last savezeit
{
	$prevSavezeit = $lastStromstatusarray[8];
}


printf "lastLeistung: $lastLeistung\tprevlastLeistung: $prevlastLeistung<br>";

#
# Wenn neues Datum nicht gleich wie altes Datum > StromData sichern in Ordner StromDaten
# mit Datum aus lastDatumstring
#
printf "LastDatumString: $lastDatumstring<br>";
my @lastDatumstringarray = split(" ",$lastDatumstring);

printf "lastDatumstringarray *0*: $lastDatumstringarray[0] *1*: $lastDatumstringarray[1] <br>";


my @lastZeitarray= split(":",$lastDatumstringarray[1]); # Zeitangabe, drittes Objekt 

my $oldStunde = $lastZeitarray[0]; # Stunde
my $oldMinute = $lastZeitarray[1]; # Minute
printf "oldStunde: $lastZeitarray[0] oldMinute: $lastZeitarray[1] <br>";
my $newMinute = $min;
my $newStunde = $stunde;
printf "newStunde: $newStunde newMinute: $newMinute <br>";

my $newMonat = $ monat;
my $newJahr = $jahr;


my $oldFilename = $lastZeitarray[0]; # Stunde
my $newFilename = "$stunde";

printf  "oldFilename: $oldFilename  newFilename: $newFilename<br>";

# Daten des alten Tages sichern
my @lastDatumarray= split("", $lastDatumstringarray[0]); # Datumangabe
#
my $oldTag =$lastDatumarray[0].$lastDatumarray[1]; # Elemente 0,1: Tag des Monats
my $oldMonat=$lastDatumarray[3].$lastDatumarray[4]; # Elemente 3,4: Monat des Jahres
my $oldJahr=$lastDatumarray[6].$lastDatumarray[7]; # Elemente 6,7: Jahr
printf "oldJahr: $oldJahr * oldMonat: $oldMonat *oldTag: $oldTag *<br>";
#
printf ("$Datumstring<br>");
my $jahrlong= $jahr + 2000;
#printf "$jahrlong<br>";

my $oldJahrlong = $oldJahr+2000;
printf "oldJahrlong: $oldJahrlong<br>";

my $testnewPfad="../public_html/Data/StromDaten/$oldJahrlong/stromdaten$oldJahr$oldMonat$oldTag.txt";
my $newTagPfad="../public_html/Data/StromDaten/$oldJahrlong/stromdaten$oldJahr$oldMonat$oldTag.txt";
printf "newTagPfad: $newTagPfad<br>";

#	neuer Tag
my $newTag = "$tag";

#open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "newTag: Logfile nicht gefunden\n";
#print LOGFILE "oldTag: $oldTag	 newTag: $newTag\n";
#print LOGFILE "oldFilename: $oldFilename  newFilename: $newFilename\n";
#close LOGFILE;
		
			
		
		
		
#if ($oldFilename ne $newFilename)
#if ($oldMinute ne $newMinute)
#if ($oldStunde ne $newStunde)
#if ($newMinute%5==0)
#$newTag += 1; # Test

if ($oldTag ne $newTag) # Tagzahl hat sich geaendert
{
	#printf ("neue Minute: $newMinute\n");
	open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "oldTag ne newTag: Logfile nicht gefunden\n";
	print LOGFILE "neuer Tag: Laufzeit: $laufzeit  $Datumstring\n";
	close(LOGFILE);
	
	# Pfad zum File mit den Tagesdaten
	my $oldPfad="../public_html/Data/StromDaten/StromDaten.txt";
	
	# Pfad zum File im Speicherordner
	my $newPfad="../public_html/Data/StromDaten/$oldJahrlong/stromdaten$oldJahr$oldMonat$oldTag.txt";
		
	
	# File mit den Tagesdaten zum Lesen öffnen und Daten in Data lesen
	open OLD,"<$oldPfad";
	my @Data=<OLD>;
	
	# neuen Pfad öffnen : File wird erstellt
	open NEW,">$newPfad" || die "NEW nicht gefunden\n";
	
	# Daten in Speicherfile schreiben
	print NEW "@Data";
	close NEW;
	
	# File für die Daten des neuen Tages leeren
	open OLD,">$oldPfad" || die "OLD nicht gefunden\n";
	print OLD "";
	close OLD;


	# Neu: JahrOrdner		
	#my $newJahrOrdnerPfad="../public_html/Data/StromDaten/$jahrlong/stromdaten$oldJahr$oldMonat$oldTag.txt";
	#printf "$newJahrOrdnerPfad<br>";
	#open NEWJAHR, ">$newJahrOrdnerPfad" || die "NEWJAHR nicht gefunden\n";
	#print NEWJAHR "@Data";
	#close NEWJAHR; 
	# JahrOrdner
		
	$laufzeit=0;
		
	
} # End neuer Tag
	

#test neuer Orddner
	#my $testjahr = 2022;
	# Neu: JahrOrdner		
	#my $newJahrOrdnerPfad="../public_html/Data/StromDaten/$oldJahrlong";
	#my $newJahrOrdnerPfad="../public_html/Data/StromDaten/$testjahr";
	#printf "$newJahrOrdnerPfad<br>";
	#mkdir($newJahrOrdnerPfad);
	#open NEWJAHR, ">$newJahrOrdnerPfad" || die "NEWJAHR nicht gefunden\n";
	#print NEWJAHR "@Data";
	#close NEWJAHR; 
	# JahrOrdner




# end test

my $leistung = 0;	


open STROMDATEI, ">>../public_html/Data/StromDaten/StromDaten.txt" || die "STROMDATEI nicht gefunden\n";
open STROMTAGDATEI, ">>$newTagPfad" || die "STROMDATEI nicht gefunden\n";

@Statistik=stat(STROMDATEI);
$size=$Statistik[7];

#open LOGFILE, ">>../public_html/Data/StromLog.txt" || die "Logfile nicht gefunden\n";
#print LOGFILE "Die Datei stromdaten.txt ist $size Bytes gross.\n";
#close(LOGFILE);
my @stromdatei=0;
my $anzStromdateiZeilen=0;

# Strommonitordaten erstellen:
#	Aus StromDaten die Daten der letzten 300 s extrahieren und in StromMonitor ablegen
#
# array array_slice ( array $array , int $offset [, int $length [, bool $preserve_keys = false ]] )
#
#		Stromdatei fuer Lesen oeffnen
open STROMDATEI, "<../public_html/Data/StromDaten/StromDaten.txt" || die "STROMDATEI nicht gefunden\n";
@stromdatei = <STROMDATEI>;
$anzStromdateiZeilen=@stromdatei;



#		Stromdatei fuer Lesen oeffnen und Zeilen auf 300s reduzieren
open STROMMONITORDATEI, "<../public_html/Data/StromDaten/StromMonitor.txt" || die "STROMMONITORDATEI nicht gefunden\n";
my @strommonitordatei = <STROMMONITORDATEI>;
my $anzStrommonitordateiZeilen=@strommonitordatei;
printf	 "anzStrommonitordateiZeilen: $anzStrommonitordateiZeilen<br>";

if ($anzStrommonitordateiZeilen > 62)
{

	
	while ($anzStrommonitordateiZeilen > 60)
	{
		shift(@strommonitordatei);
		$anzStrommonitordateiZeilen=@strommonitordatei;
		printf	 "anzStrommonitordateiZeilen: $anzStrommonitordateiZeilen<br>";
		# Array ersetzen, Schreiben von Anfang an
		open STROMMONITORDATEI, ">../public_html/Data/StromDaten/StromMonitor.txt" || die "STROMMONITORDATEI nicht gefunden\n";
		print STROMMONITORDATEI @strommonitordatei;
		
	}
	
}

open STROMMONITORDATEI, ">>../public_html/Data/StromDaten/StromMonitor.txt" || die "STROMMONITORDATEI nicht gefunden\n";

#open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "Logfile nicht gefunden\n";
#print LOGFILE "Die Datei ElektroDaten.txt hat $anzElektrodateiZeilen Zeilen.\n";
#close(LOGFILE);

#open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "Logfile nicht gefunden\n";
#print LOGFILE "Die Datei stromdaten.txt hat $anzStromdateiZeilen Zeilen.\n";
#close(LOGFILE);

open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "Logfile nicht gefunden\n";
print LOGFILE "Die Datei StromMonitor.txt hat $anzStrommonitordateiZeilen Zeilen.\n";
close(LOGFILE);

#	stromdaten fuer Schreiben am Ende oeffnen
#open STROMDATEI, ">>../public_html/Data/StromDaten/StromDaten.txt" || die "STROMDATEI nicht gefunden\n";

#int($value + 0.5)


my $beispielfloat=3.64159;
my $beispielfix = sprintf("%.1f",$beispielfloat);

#my $beispielint = sprintf("%d",$beispielfix);
my $beispielint = roundup($beispielfix);


open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "Logfile StromLog nicht gefunden\n";
#print LOGFILE "\n** $Datumstring\toldElektrolaufzeit: $oldElektrolaufzeit\told Elektrostatus: $oldElektrostatus\n";
#print LOGFILE "*** beispielfloat: $beispielfloat\tbeispielfix: $beispielfix\tbeispielint: $beispielint\n";
close(LOGFILE);

#
#	 stromdaten von Homecentral lesen
#
open STROMDATEI, ">>../public_html/Data/StromDaten/StromDaten.txt" || die "STROMDATEI nicht gefunden\n";

if ($anzStromdateiZeilen==0)							# File ist noch leer, Titel schreiben
{
	print STROMDATEI "HomeCentral\nFalkenstrasse 20\n8630 Rueti\n";
	print STROMDATEI "$Datumstring\n\n";
	$jahr +=2000;
	print STROMDATEI "Startzeit: $jahr-$monat-$tag $stunde:$min:$sec +0100\n";
	
#	open TIMEPREFS, ">../public_html/Data/stromtimeprefs.txt" || die "stromtimeprefs nicht gefunden\n";
#	print TIMEPREFS time();
#	close(TIMEPREFS);
	
	$laufzeit=0;
}
else
{
	#
	# Daten von stromdaten.txt lesen
	#
	open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "StromLog: Logfile nicht gefunden\n";
	#print LOGFILE "cgivars pw: $cgivars{pw}\t";
	#print LOGFILE "cgivars strom: $cgivars{strom}\n";
	#print LOGFILE "cgivars ws: $cgivars{ws}\n";
	
	
	my $pw = ($cgivars{pw});	
	$leistung = ($cgivars{strom});						#	Leistung
	
#	my $wattstunden = ($cgivars{ws});					# 	Wattstunden

#	my $datacontrol=0;									# 	Kontrolle, ob Daten OK: Vorlauf, Ruecklauf sind nie null
#	
#	my $h0 = hex($cgivars{d0});							# 	
#		$datacontrol += $h0;
#	my $h1 = hex($cgivars{d1});							# 	
#		$datacontrol += $h1;
#	
	
	print LOGFILE "$Datumstring\t Leistung: $leistung Watt\n";
#	print LOGFILE "\toldMinute: $oldMinute\t newMinute: $newMinute";

#if ($h0) # callbackerrcounter >0, Eintrag ins Log
#	{
#		print LOGFILE "$Datumstring\tstrom.pl Strom L:\t $leistung\tWs:\t$wattstunden\tcb err:\t$h0\te1:\t$h1*\n";	
#		#\td2: $h2\td3: $h3\td4: $h4\td5: $h5\n";
#	}

	
	
	close LOGFILE;


#$prevSavezeit=50;
my $prevRoundedSavezeit =20* int($prevSavezeit/20 +0.5);
printf "prevSavezeit: $prevSavezeit\tprevRoundedSavezeit: $prevRoundedSavezeit<br>";

	
	#printf "lastLeistung: $lastLeistung\tleistung: $leistung<br>";
open STROMSTATUSDATEI, ">../public_html/Data/StromDaten/stromstatus.txt" || die "STROMSTATUSDATEI nicht gefunden\n";
	
	if (abs($leistung - $lastLeistung) > 20)
	{
		$lastLeistung = $leistung;
	}
	
	my $newSavezeit = int ($sec /20);
	

my $RoundedSekunde = 20* int($sec/20 +0.5);


printf "min: $min\tRoundedSekunde: $RoundedSekunde<br>";


my $diffroundzeit = 	$RoundedSekunde - $prevRoundedSavezeit;

my $diffzeit = $sec - $prevRoundedSavezeit;


#if (($RoundedSekunde > $prevRoundedSavezeit) || ($RoundedSekunde eq 0))
{
	$Savezeit = $RoundedSekunde;

	print STROMSTATUSDATEI "$jahr\t$monat\t$tag\t$stunde\t$min\t$sec\t$leistung\t$lastLeistung\t$RoundedSekunde";


#print STROMMONITORDATEI "\n$Tagstring\t$stunde\t$min\t$RoundedSekunde\t$leistung";

#if ($RoundedSekunde eq 0)
#{
#	$prevRoundedSavezeit = 0;
#}


}

# Daten fuer Strommonitor ablegen: letzte 300s

print STROMMONITORDATEI "\n$Tagstring\t$stunde\t$min\t$sec\t$leistung";

close STROMSTATUSDATEI;


}	# mehrere Zeilen


		
if (($oldMinute ne $newMinute) && ($newMinute%2 == 0)) # neue Minute, gerade Minute, Temperaturen speichern
{
	
	#print STROMDATEI "\n$Datumstring\t";
	#print STROMDATEI "Daten  Leistung: $leistung Watt";
	print STROMDATEI "\n$jahr\t$monat\t$tag\t$stunde\t$min\t$sec\t$leistung";
	#print STROMTAGDATEI "\n$jahr\t$monat\t$tag\t$stunde\t$min\t$sec\t$leistung";



	#
	#	
	#

	#	if ($oldMinute % 5 == 0) # Testphase
	
	if ($oldStunde ne $newStunde) # Stunde zu Ende
	{
		open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "Elektrodatei: Logfile nicht gefunden\n";
		print LOGFILE "\n**\tNeue Stunde\n";
		#print LOGFILE "KollektorTemperaturMittel schreiben\n";
		close (LOGFILE);
		
	}	#	if ($oldStunde ne $newStunde)
			
	$oldMinute = $newMinute;
	}		#	if ($oldMinute ne $newMinute)
	#
	#	Ende Temperaturdaten speichern
	#
	
	
	
	#	aktuelle Zeit einsetzen
		
	open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "Elektrodatei: Logfile nicht gefunden\n";

	close(LOGFILE);
	
	
	#open LOGFILE, ">>../public_html/Data/StromLog.txt" || die "Logfile nicht gefunden\n";
	#print LOGFILE "**	Elektrodatei[6] nach print ist $Elektrodatei[6]\n";
	#close(LOGFILE);
	
	my $datacontrol=0;
	
	



close STROMMONITORDATEI;

close STROMTAGDATEI;
close STROMDATEI;

close(LOGFILE);


#print LOGFILE "$laufzeit\t$cgivars{d2}\t$cgivars{d3}\t$cgivars{d4}\t$cgivars{d1}\t$cgivars{d0}\t$cgivars{d6}\t$cgivars{d7}\t0\n";
#close(LOGFILE);

# Finally, print out the complete HTML response page
# print <<EOF druckt alles bis EOF


# Print the CGI variables sent by the user.
# Note that the order of variables is unpredictable.
# Also note this simple example assumes all input fields had unique names,
#	though the &getcgivars() routine correctly handles similarly named
#	fields-- it delimits the multiple values with the \0 character, within 
#	$cgivars{$_}.

#foreach (keys %cgivars) 
#{
#	 print "<li>[$_] = [$cgivars{$_}]\n" ;
#}




exit ;

sub roundup 
{
    my $n = shift;
    return(($n == int($n)) ? $n : int($n + 1))
}

sub round_to_halves 
{
    return 0.5 * (int(2*$_[0]));
}

# Read all CGI vars into an associative array.
# If multiple input fields have the same name, they are concatenated into
#	one array element and delimited with the \0 character (which fails if
#	the input has any \0 characters, very unlikely but conceivably possible).
# Currently only supports Content-Type of application/x-www-form-urlencoded.
sub getcgivars 
{
	my ($in, %in) ;
	my ($name, $value) ;
	open LOGFILE, ">>../public_html/Data/StromDaten/StromLog.txt" || die "Logfile nicht gefunden G\n";

	# First, read entire string of CGI vars into $in
	if ( ($ENV{'REQUEST_METHOD'} eq 'GET') ||
		 ($ENV{'REQUEST_METHOD'} eq 'HEAD') ) {
		$in= $ENV{'QUERY_STRING'} ;
		print LOGFILE "getcgivars in: $in\n";
	} 
	elsif ($ENV{'REQUEST_METHOD'} eq 'POST') 
	{
		if ($ENV{'CONTENT_TYPE'}=~ m#^application/x-www-form-urlencoded$#i) 
		{
			length($ENV{'CONTENT_LENGTH'})
				|| &HTMLdie("No Content-Length sent with the POST request.") ;
			read(STDIN, $in, $ENV{'CONTENT_LENGTH'}) ;

		} 
		else 
		{ 
			&HTMLdie("Unsupported Content-Type: $ENV{'CONTENT_TYPE'}") ;
		}

	} else {
		&HTMLdie("Script was called with unsupported REQUEST_METHOD.") ;
	}
	
	# Resolve and unencode name/value pairs into %in
	foreach (split(/[&;]/, $in)) 
	{
		s/\+/ /g ;
		($name, $value)= split('=', $_, 2) ;
		$name=~ s/%([0-9A-Fa-f]{2})/chr(hex($1))/ge ;
		$value=~ s/%([0-9A-Fa-f]{2})/chr(hex($1))/ge ;
		$in{$name}.= "\0" if defined($in{$name}) ;	# concatenate multiple vars
		$in{$name}.= $value ;
	}
	close(LOGFILE);
	return %in ;

}


# Die, outputting HTML error page
# If no $title, use a default title
sub HTMLdie 
{
	my ($msg,$title)= @_ ;
	$title= "CGI Error" if $title eq '' ;
	print <<EOF ;
Content-type: text/html

<html>
<head>
<title>$title</title>
</head>
<body>
<h1>$title</h1>
<h3>$msg</h3>
</body>
</html>
EOF

	exit ;
}
