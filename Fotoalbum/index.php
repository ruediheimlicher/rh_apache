<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">


<head>
  	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  	<title>Fotoalbum</title>
	<meta name="keywords" content="solaranlage,strommessung,homecentral,i2c,atmega" />

	<link href="../indexstyle.css" rel="stylesheet" type="text/css" >

<style type="text/css">

 </style>
 
</head>

<body>


<?php	
include"../pwd.php";
#include "../plans/
?>

<?php

print '<div class="titelabschnitt">';
#print '<p>benutzer: *'. $benutzer.'* pw: *'. $passwort.'*</p>';
$pass=0;
if (isset($_POST['pass']))
{
$pass = $_POST['pass'];
}

$user = 0;
if (isset($_POST['user']))
{
$user = $_POST['user'];
}

$test = 0;
if (isset($_POST['test']))
{
$test = $_POST['test'];
}
#print '<p>user: '.$user.'  pass: *'. $pass.'*</p>';
#$user = "admin";


	
print '<h1 class = "u1">Fotoalbum</h1>';
		

print '</div>';
if (!(($benutzer == "benutzer") || ($passwort == "$pass")))
{
print '<div class = "menuabschnitt">';

#print '<p >user: '.$user.'  pass: *'. $pass.'*</p>';

print '<p class = "kalender">Eingabe falsch! <form action="../index.php" ><input type="submit" class="links40" value="zurück zur Startseite"></form></p> ';
print '</div>';
}
else
{




?>

<div class="menuabschnitt">

	<ul id="main-nav">
    	<li><a href="../">Home</a></li>
	</ul>


</div>

<div class = "abschnitt1">
	<div class = "albumabschnitt">

  		<ul id="foto-nav">
    		<li><a href="Boot Holland 12">Boot Holland 12</a></li>
		</ul>
	</div>
	<div class = "albumabschnitt">

  		<ul id="foto-nav">
    		<li><a href="Boot Lothringen 05">Boot  Lothringen 05</a></li>
		</ul>
	</div>
	
</div>

<?php
}
?>
</body>

</html>