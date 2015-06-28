#!/usr/bin/perl -w
use CGI::Carp qw( fatalsToBrowser );
use Data::Dumper;
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

my $mittelwertadresse = "/solarkollektormittelwerte.txt";
my $tempadresse = "/tempeepromdaten.txt";

my $serverdataadresse =$mittelwertadresse;
# First, get the CGI variables into a list of strings


# cgivars in Textfile sichern
%cgivars= &getcgivars ;
#print Dumper(%cgivars);
#print "# %cgivars\n", Dump \%cgivars;
#print map {$_ . " "} %cgivars, "\n";
# Print the CGI response header, required for all HTML output ****WICHTIG****
# Note the extra \n, to send the blank line
print "Content-type: text/html\n\n" ;

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

my $mittelwertjahr = $cgivars{jahr};	#  	permanente oder nur temporaere Einstellung
my $mittelwertmonat = $cgivars{monat};		#  	hbyte der Adresse
my $mittelwerttag=1;	

# mittelwertdatei löschen
printf "mittelwertjahr: $mittelwertjahr  mittelwertmonat: $mittelwertmonat<br>";
open my $JAHRDATEI, ">../Data/kollektordaten/$mittelwertjahr/kollektormittelwerte.txt" || die "JAHRDATEI nicht gefunden\n";
print $JAHRDATEI "";
#close $JAHRDATEI;	
open $JAHRDATEI, ">>../Data/kollektordaten/$mittelwertjahr/kollektormittelwerte.txt" || die "JAHRDATEI nicht gefunden\n";
	
	
	#open LOGFILE, ">>../Data/eepromdaten/log.txt" || die "EEPROM Logfile nicht gefunden\n";
	#print LOGFILE " $mittelwertjahr\t$mittelwertmonat";
	#close(LOGFILE);
	
my $tagstring=0;
my $tagadresse=0;
my $anztagdateizeilen=0;

# Mittelwerte aufsummieren
my $temperatursumme = 0;
my $mittelwert = 0;
my $counter=0;
for ($mittelwertmonat=1;$mittelwertmonat <= 12;$mittelwertmonat++)
{
	for ($mittelwerttag=1;$mittelwerttag <= 31;$mittelwerttag++)
	{	

		$tagstring = sprintf("SolarDaten%.2d%.2d%.2d.txt\t",($mittelwertjahr-2000),$mittelwertmonat,$mittelwerttag);
		$tagadresse = "/$mittelwertjahr/$tagstring";
	
		$counter=0;
		$temperatursumme=0;
		$mittelwert=0;

		#printf "mittelwerttag: $mittelwerttag tagstring: $tagstring Data/SolarDaten/$mittelwertjahr/$tagstring<br>";
		#printf  "tagadresse: $tagadresse<br>";
		open my $TAGDATEI, "<../Data/SolarDaten/$mittelwertjahr/$tagstring" || die "TAGDATEI nicht gefunden\n";
		my @tagdatei = <$TAGDATEI>;
		if (@tagdatei)
		
			{
			#printf "datei am Tag $mittelwerttag ist da<br>";

			chomp(@tagdatei);
			$anztagdateizeilen=@tagdatei;

			# Status und Kollektortemp lesen
				#foreach $eintrag (@feld)
				#{
				#	push(@prefdok,$eintrag);
				#	$linien++;
				#} 
			my $tempzeile=0;
			$counter=0;
			foreach $tempzeile (@tagdatei)
			{
				#if ($counter<10)
				{
					my @datazeilearray= split("\t",$tempzeile); # 
					my $temperatur = $datazeilearray[6];
					my $status = $datazeilearray[7]& 0x08;
					if ($status)
					{
						if ($counter<10)
						{
							#printf "$tempzeile<br>";
						}

						#printf "$temperatur $datazeilearray[7] <br>";
						$temperatursumme += $temperatur;
						$counter ++;
					}
		
				}
			} # foreach
	
	
			if ($counter)
			{			
				$mittelwert = $temperatursumme/$counter;
				printf "jahr:\t$mittelwertjahr\tmonat:\t$mittelwertmonat\ttag:\t$mittelwerttag\tsumme:\t$temperatursumme\tanzahlwerte:\t$counter\tmittelwert:\t$mittelwert<br>";	

	
	

			}
					print $JAHRDATEI "$mittelwertjahr\t$mittelwertmonat\t$mittelwerttag\t$temperatursumme\t$counter\t$mittelwert\n";
	
			}
		else
		{
		printf "\t*** Datei  am Tag $mittelwerttag ist nicht da<br>";
		}
	
		
		close $TAGDATEI;
	
		#print JAHRDATEI "jahr:\t$mittelwertjahr\tmonat:\t$mittelwertmonat\ttag:\t$mittelwerttag\tsumme:\t$temperatursumme\tanzahlwerte:\t$counter\tmittelwert:\t$mittelwert\n";
		#print JAHRDATEI "$mittelwertjahr\t$mittelwertmonat\t$mittelwerttag\t$temperatursumme\t$counter\t$mittelwert\n";

	} # for mittelwerttag
}
close $JAHRDATEI;


print <<EOF ;
<html>
<head><title>Kollektormittelwerte berechnen</title></head>
<body>
<p>Mittelwerte berechnen.</p>
<p>Jahr: $mittelwertjahr  monat: $mittelwertmonat. tagadresse: $tagadresse</p>
<p>anztagdateizeilen: $anztagdateizeilen </p>

</body>
</html>
EOF


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
