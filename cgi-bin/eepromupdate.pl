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
	$typ = $cgivars{typ};			#	typ 0: halbe Stunden 1: nur ganze Stunde
	
	chomp($data);
	my @tempDataArray=split(" ",$data);
	my $anzdata=@tempDataArray;
	$data = join("\t",@tempDataArray);

	
	$startadresse = 0x100 * $hbyte + $lbyte;
	$wochentag = ($startadresse & 0x38) / 0x08;
	$objekt = ($startadresse & 0x1C0) / 0x40;
	$raum = ($startadresse & 0xE00)/ 0x200;
	
	$dataadresse = 56*$raum + 7*$objekt + $wochentag;
	
	open LOGFILE, ">>../public_html/Data/eepromdaten/log.txt" || die "EEPROM Logfile nicht gefunden\n";
	print LOGFILE "* EEPROM $Datumstring\t";
	if ($permanent == 13) # eepromupdate überschreiben
	{
		print LOGFILE "eepromupdate.txt loeschen.\n";

		 # updatefile loeschen
 		open UPDATEFILE, ">../public_html/Data/eepromdaten/eepromupdatedaten.txt" || die "EEPROM Updatefile nicht gefunden\n";
		print UPDATEFILE "";
		close UPDATEFILE;
	}
	elsif ($permanent == 12)
	{
		print LOGFILE "eepromupdate.txt nicht loeschen.\n";
	}

	close(LOGFILE);


print <<EOF ;
<html>
<head><title>eepromupdate</title></head>
<body>
<p>eepromclearok=1</p>
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
