#!/usr/bin/perl -w
use CGI::Carp qw( fatalsToBrowser );
#use cPanelUserConfig;
use DBI;
#
#
use strict;
#
#   hello.pl-- standard "hello, world" program to demonstrate basic
#       CGI programming, and the use of the &getcgivars() routine.
#
# Print the CGI response header, required for all HTML output
# Note the extra \n, to send the blank line
print "Content-type: text/html\n\n" ;

# cgivars in Textfile sichern

#http://stackoverflow.com/questions/753346/how-do-i-set-the-timezone-for-perls-localtime
use POSIX qw(tzset);
$ENV{TZ} = 'Europe/Zurich';
tzset;

my $now = localtime;
#print "It is now   $now\n";

my $zeit=localtime();


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

my $Brennerlaufzeit=0;
my $brennerstatus=0;
my $anzStatusZeilen=0;

my %cgivars;
my @Statistik=0;

my @prefzeit=0;
my $anzPrefsZeilen;

my $Alarmstatus=0;
my $Resetstatus=1;
my @alarmdatei=0;
my $writeTask=0;
my $anzAlarmdateiZeilen=0;

# First, get the CGI variables into a list of strings
%cgivars= &getcgivars ;



my @debugstatusdatei;
my $anzstatus=0;
# First, get the CGI variables into a list of strings
%cgivars= &getcgivars ;

# Print the CGI response header, required for all HTML output ****WICHTIG****
# Note the extra \n, to send the blank line
#print "Content-type: text/html\n\n" ;

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


#open(HOMEDATEI, ">../public_html/Data/ip.txt") || die "HOMEDATEI  diesmal nicht gefunden\n";
#print "\n";
#$teststring = "Start $zeit ";
my $ipstring = $cgivars{'ip'};
#print HOMEDATEI $ipstring;
#foreach (keys %cgivars) 
#{
#    print HOMEDATEI "[$_] = [$cgivars{$_}]\n" ;
#}

#close(HOMEDATEI);
# http://www.perlmonks.org/?node_id=857618
open my $fhh, ">>", '../Data/Log.txt' 
  or die "Can't open the fscking file: $!";

my $need_work = 2;
my $i_tried   = 0;

while ( $need_work > $i_tried ) {

    $i_tried++;

    my $statement 
        = "I've tried $i_tried things as a test\n";

    print $fhh $statement;
}
close $fhh;

my $LOGFILE;

	#open LOGFILE, ">>../public_html/Data/Log.txt" || die "Logfile A nicht gefunden\n";
	#print LOGFILE "hello Start Datumstring: $Datumstring\n";
	#close(LOGFILE);

	open $LOGFILE, ">>../Data/Log.txt" || die "Logfile A nicht gefunden\n";
	print $LOGFILE "hello Start Datumstring: $Datumstring\n";
	close $LOGFILE;

printf "Hello $Datumstring.<br>";
printf "$stunde:$min:$sec";

# Finally, print out the complete HTML response page
# print <<EOF druckt alles bis EOF

print <<EOF ;
<html>
<head><title>CGI Results</title></head>
<body>
<h1>Hello, homecentral</h1>
</body>
</html>

EOF

# Print the CGI variables sent by the user.
# Note that the order of variables is unpredictable.
# Also note this simple example assumes all input fields had unique names,
#   though the &getcgivars() routine correctly handles similarly named
#   fields-- it delimits the multiple values with the \0 character, within 
#   $cgivars{$_}.
foreach (keys %cgivars) 
{
    print "<li>[$_] = [$cgivars{$_}]\n" ;
}

# Print close of HTML file
print <<EOF ;
</ul>
</body>
</html>
EOF



exit ;


#----------------- start of &getcgivars() module ----------------------

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
sub HTMLdie {
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
