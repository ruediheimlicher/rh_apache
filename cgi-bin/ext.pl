#!/usr/bin/perl  
use CGI::Carp qw( fatalsToBrowser );
#use cPanelUserConfig;
print "Content-type: text/html\n\n"; 

print <<EOF ; 

<html>
<head> 
<title>A Simple Perl CGI</title> 
</head> 
<body> 
<h1>A Simple Perl CGI</h1> 
<p>Hello World</p> 
</body> 
</html>
EOF

exit;