#!/usr/bin/perl -w
use CGI::Carp qw( fatalsToBrowser );
#
#
use strict;
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

my $hbyte=0;
my $lbyte=0;
my $data=0;
my $pw=0;
my $permanent=0;

my $raum=0;
my $wochentag=0;
my $objekt=0;
my $titel="*";
my $typ = 0;

my $permanentadresse = "/eepromdaten.txt";
my $tempadresse = "/tempeepromdaten.txt";
my $historyadresse = "/eepromhistdaten.txt";


my $serverdataadresse =$permanentadresse;
# First, get the CGI variables into a list of strings
%cgivars= &getcgivars ;

# Print the CGI response header, required for all HTML output ****WICHTIG****
# Note the extra \n, to send the blank line
print "Content-type: text/html\n\n" ;

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

$Datumstring = "Datum: $tag.$monat.$jahr $stunde:$min";
my $datum = 10000*$jahr+100*$monat+$tag;
my $startadresse=0;
my $dataadresse=-1;

	$permanent = $cgivars{perm};	#  	permanente oder nur temporaere Einstellung
	$hbyte = $cgivars{hbyte};		#  	hbyte der Adresse
	$lbyte = $cgivars{lbyte};		# 	lbyte
	$data = $cgivars{data};			#	data
	$titel = $cgivars{titel};		#	titel
	$typ = $cgivars{tagbalkentyp};			#	typ 0: halbe Stunden 1: nur ganze Stunde 9: Servo
	
	chomp($data);
	my @tempDataArray=split(" ",$data);
	my $anzdata=@tempDataArray;
	$data = join("\t",@tempDataArray);

	
	$startadresse = 0x100 * $hbyte + $lbyte;
	$wochentag = ($startadresse & 0x38) / 0x08;
	$objekt = ($startadresse & 0x1C0) / 0x40;
	$raum = ($startadresse & 0xE00)/ 0x200;
	
	$dataadresse = 56*$raum + 7*$objekt + $wochentag;
	
open LOGFILE, ">>../Data/eepromdaten/log.txt" || die "EEPROM Logfile nicht gefunden\n";
print LOGFILE "* EEPROM $Datumstring\t";
print LOGFILE "hb:\t$hbyte\tlb: \t$lbyte\tdata:\t$data\t";
print LOGFILE "wt:\t$wochentag\tobj:\t$objekt\traum:\t$raum\tperm:\t$permanent\ttit:\t$titel\ttyp: \t$typ\n";

close(LOGFILE);
if (($permanent==1) || ($permanent==3))
 {
 	$serverdataadresse = $permanentadresse;
 }
 else
 {
 	$serverdataadresse = $tempadresse;
 }
 
 if ($permanent==3) # von iPhone
 {
 	# in updatefile sichern
 	open UPDATEFILE, ">>../Data/eepromdaten/eepromupdatedaten.txt" || die "EEPROM Updatefile nicht gefunden\n";
 	my $zeitstempel = "$jahr$monat$tag";
	print UPDATEFILE "$dataadresse\t$raum\t$objekt\t$wochentag\t$hbyte\t$lbyte\t$data\t$typ\t$permanent\t$zeitstempel\n";

	close UPDATEFILE;
 }
 
 
#open DATAFILE, "<../Data/eepromdaten/eepromdaten.txt" || die "EEPROM Datafile nicht gefunden\n";
open DATAFILE, "<../Data/eepromdaten$serverdataadresse" || die "EEPROM Datafile nicht gefunden\n";
my @DataDatei = <DATAFILE>;
chomp(@DataDatei);

my $anzdatazeilen=@DataDatei;

my $index=0;
if ($anzdatazeilen==0) # file ist noch leer
{
	printf "neu nummerieren permanent: $permanent anzdatazeilen: $anzdatazeilen<br>";
	open DATAFILE, ">../Data/eepromdaten$serverdataadresse" || die "EEPROM Datafile nicht gefunden\n";
	for (my $raum =0;$raum<8;$raum++)
	{
		for (my $obj=0;$obj < 8;$obj++)
		{
			for (my $wt=0;$wt<7;$wt++)
			{
				print DATAFILE "$index\t$raum\t$obj\t$wt\n";
				$index++;		
			 }
		}
	}
	close(DATAFILE);
	exit;
}
#

if ($dataadresse >=0)
{
	#printf "Daten schreiben anzdatenzeilen: $anzdatazeilen<br>";
	
	# Daten lesen
	open DATAFILE, "<../Data/eepromdaten$serverdataadresse" || die "EEPROM Datafile nicht gefunden\n";
	
	#my $DataString =  <DATAFILE>;
	#chomp($DataString);
	#my @tempArray=split("\n",$DataString);
	#my $anz=@tempArray;
	#printf "anz: $anz Datenstring: $DataString<br>";
	
	my @DataDatei = <DATAFILE>;
	chomp(@DataDatei);
	
	my $anzdatazeilen=@DataDatei;
	#printf "anz: $anzdatenzeilen <br>";
	
	# bisherige Datazeile
	my $datazeile = $DataDatei[$dataadresse];
	#printf "eepromdaten datenadresse: $dataadresse datenzeile raw: *$datazeile*<br>";
	
	
	open LOGFILE, ">>../Data/eepromdaten/log.txt" || die "EEPROM Logfile nicht gefunden\n";

	chomp($datazeile);
	
	my @tempArray=split("\t",$datazeile);
	my $anz=@tempArray;

	#printf "Bisherige datenzeile: $datazeile<br>";
	print LOGFILE "Alte datenzeile: $datazeile\n";
	open (HISTFILE, ">>../Data/eepromdaten$historyadresse") || die "EEPROM Histfile nicht gefunden\n";
	print HISTFILE "$datazeile\n";
	close(HISTFILE);
	

	if ($anz>4) # schon Daten vorhanden
	{
		#printf "index: $dataadresse anz: $anz datenzeile: $datazeile<br>";
		splice @tempArray,4;
		$datazeile = join("\t",@tempArray);
		
	}
	
	#$datazeile =~ s/\R//g; ## entfernt auch \r
	
	printf "homeserver+";
	
	#printf "<br>neue datenzeile: eepromdaten datenadresse: $dataadresse raum: $raum objekt: $objekt wochentag: $wochentag tagbalkentyp: $typ daten: $data datenzeile: *$datazeile*<br>";
	
	
	
	my $newdatazeile = "${datazeile}\t$data\t$datum\t$titel\t$typ";
	
	print LOGFILE "Neue datenzeile: $newdatazeile\n";
	
	$DataDatei[$dataadresse]= $newdatazeile;
	#printf "eepromdaten newdatenzeile: *$newdatazeile*<br>";
	
	open (DATAFILE, ">../Data/eepromdaten$serverdataadresse") || die "EEPROM Datafile nicht gefunden\n";
	for (my $i=0;$i<$anzdatazeilen;$i++)
	{
	
		#printf "eepromdaten DataDatei[$i]: *$DataDatei[$i]*<br>";
		print DATAFILE $DataDatei[$i];
		print DATAFILE "\n";
	}
	close(DATAFILE);
	close(LOGFILE);
}




#
#printf ("EEPROM Datumstring: $Datumstring<br>");
#printf "hbyte:\t$hbyte\tlbyte: \t$lbyte\tdaten:\t$data<br>";
#printf "wochentag:\t$wochentag\tobjekt:\t$objekt\traum:\t$raum\tpermanent:\t$permanent\ttitel:\t$titel\ttagbalkentyp:\t$typ <br>";






exit ;


# Read all CGI vars into an associative array.
# If multiple input fields have the same name, they are concatenated into
#   one array element and delimited with the \0 character (which fails if
#   the input has any \0 characters, very unlikely but conceivably possible).
# Currently only supports Content-Type of application/x-www-form-urlencoded.
sub getcgivars 
{
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
