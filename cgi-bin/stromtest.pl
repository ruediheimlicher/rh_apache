#!/usr/bin/perl -w
use CGI::Carp qw( fatalsToBrowser );
#
#
use strict;

print "Content-type: text/html\n\n" ;

my @prefdok=();
my (@dok)=();
my ($size)=0;
my ($prefsize)=0;
my @DatenzeilenArray=();
my $Datenwert = 0;
my $Datumstring=0;
my $lastDatumstring=0;
my @zeit=0;
my $jahr=0;
my $monat=0;
my $tag =0; 
my $stunde=0;
my $min =0;
my $sec =0;
my $tagsekunde=0;
my $laufzeit=0;

my $anzStatusZeilen=0;

my %cgivars=();
my @Statistik=0;

my @prefzeit=0;
my $anzPrefsZeilen;

my $h0=0;
my $h1=0;
my $h2=0;
my $h3=0;
my $h4=0;
my $h5=0;
my $h6=0;
my $h7=0;
my $h8=0;
my $h9=0;
my $h10=0;
my $Alarmstatus=0;
my $Resetstatus=1;

# First, get the CGI variables into a list of strings
%cgivars= &getcgivars ;

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

$tagsekunde = ((($zeit[2] * 60) + $zeit[1]) * 60) + $zeit[0];

$Datumstring = "$tag.$monat.$jahr $stunde:$min";
#open LOGFILE, ">>../public_html/Data/AlarmLog.txt" || die "AlarmLogfile A nicht gefunden\n";
#print LOGFILE "* Alarm $Datumstring\t";
#close(LOGFILE);

#
#printf "Die Datei AlarmDaten.txt hat $anzAlarmdateiZeilen Zeilen.<br>$alarmdatei[0]<br>";
printf ("<h2>Stromtest</h2> <p>  $Datumstring</p>");
my $jahrlong= $jahr + 2000;
#printf "$jahrlong<br>";

my $newTag = "$tag";


#open ERRLOGFILE, ">>../public_html/Data/ErrLog.txt" || die "ErrLogfile nicht gefunden J\n";
open TESTFILE, ">>../public_html/Data/TestDaten/testdaten.txt" 
    or die("Cannot open file for writing");

#print TESTFILE "Test $Datumstring\n";


# Zeilen auf 1000 reduzieren
# Testdatei fuer Lesen oeffnen
open TESTFILE, "<../public_html/Data/TestDaten/testdaten.txt" or die("Cannot open file for writing");

my @testdatei = <TESTFILE>;
my $anztestdateiZeilen=@testdatei;
printf	"anztestdateiZeilen: $anztestdateiZeilen<br>";
if ($anztestdateiZeilen > 1100)
{
	while ($anztestdateiZeilen > 1000)
	{
		shift(@testdatei);
		$anztestdateiZeilen=@testdatei;
		printf	 "anztestdateiZeilen: $anztestdateiZeilen<br>";
		# Array ersetzen, Schreiben von Anfang an
		open TESTFILE, ">../public_html/Data/TestDaten/testdaten.txt" || die "TESTFILE nicht gefunden\n";
		print TESTFILE @testdatei;
	}
	
}

#printf "Hello\n";
close TESTFILE;

	#
	# Daten von stromdaten.txt lesen
	#
	
	my $pw = ($cgivars{pw});	
	my $leistung = ($cgivars{strom});					#	Leistung
	my $wattstunden = ($cgivars{ws});
	my $paketcounter = ($cgivars{pk});
	
	
	my $datacontrol=0;									# 	Kontrolle, ob Daten OK: Vorlauf, Ruecklauf sind nie null
	$h0 = ($cgivars{e0});								# 	error 0
		$datacontrol += $h0;
	$h1 = ($cgivars{e1});								# 	error 1
		$datacontrol += $h1;
		
		
	$h2 = hex($cgivars{e2});							#	
		$datacontrol += $h2;
	$h3 = hex($cgivars{e3});							#	
		$datacontrol += $h3;
	$h4 = hex($cgivars{e4});							#	
		$datacontrol += $h4;
	$h5 = hex($cgivars{e5});							#	
	
	$h6 = hex($cgivars{d6});							#	
	
	$h7 = hex($cgivars{d7});							# 	
	
	open TESTFILE, ">>../public_html/Data/TestDaten/testdaten.txt" || die "StromLog: Logfile nicht gefunden\n";
	
#	print TESTFILE "\n$Datumstring\t Leistung: $leistung Watt";
#	print TESTFILE "\toldMinute: $oldMinute\t newMinute: $newMinute";
	
	
	print TESTFILE "$Datumstring\tstromtest Strom L:\t $leistung\tWs:\t$wattstunden\tpk:\t$paketcounter\tcb err:\t$h0\te1:\t$h1*\n";	#\td2: $h2\td3: $h3\td4: $h4\td5: $h5\n";
	close TESTFILE;
	
	if ($h0) # callbackerrcounter >0, Eintrag ins Log
	{
		open LOGFILE, ">>../public_html/Data/TestDaten/testlog.txt" || die "LOGFILE nicht gefunden\n";
		print LOGFILE "$Datumstring\tstromtest.pl Strom L:\t $leistung\tWs:\t$wattstunden\tpk:\t$paketcounter\tcb err:\t$h0\te1:\t$h1*\n";	
		#\td2: $h2\td3: $h3\td4: $h4\td5: $h5\n";

		close LOGFILE;
	}









# Read all CGI vars into an associative array.
# If multiple input fields have the same name, they are concatenated into
#   one array element and delimited with the \0 character (which fails if
#   the input has any \0 characters, very unlikely but conceivably possible).
# Currently only supports Content-Type of application/x-www-form-urlencoded.
sub getcgivars {
    my ($in, %in) ;
    my ($name, $value) ;

    # First, read entire string of CGI vars into $in
    if ( ($ENV{'REQUEST_METHOD'} eq 'GET') ||
         ($ENV{'REQUEST_METHOD'} eq 'HEAD') ) {
        $in= $ENV{'QUERY_STRING'} ;

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
    foreach (split(/[&;]/, $in)) {
        s/\+/ /g ;
        ($name, $value)= split('=', $_, 2) ;
        $name=~ s/%([0-9A-Fa-f]{2})/chr(hex($1))/ge ;
        $value=~ s/%([0-9A-Fa-f]{2})/chr(hex($1))/ge ;
        $in{$name}.= "\0" if defined($in{$name}) ;  # concatenate multiple vars
        $in{$name}.= $value ;
        
    }

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
