<?php
session_start();

/*

Login Datenbank Chor
*/


include "pwd.php";
	#print'benutzer : *'.$benutzer.'*<br>';
	#print'passwort : *'.$passwort.'*<br>';

	$planfile = "plans/fotoplan.txt";
	if (file_exists($planfile)) 
	{
		$sommer = file_get_contents($planfile);
		#print'fotoplan : '.$sommer.'<br>';
	}
	else
	{
		#
	#print'kein planfile<br>';# an: '.$planfile.'<br>';
	}
	

	$hostname = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);
	#print'hostname : *'.$hostname.'*<br>';
	#print'path : *'.$path.'*<br>';

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fotoalbum Login</title>
 
<link href="chor.css" rel="stylesheet" type="text/css" />
</head>

<body class="basic">

<body >
<?php
	#print'p not OK<br>';
	
	#print'remote addr: '.$_SERVER['REMOTE_ADDR'].'<br>';
	#print'server addr: '.$_SERVER['SERVER_ADDR'].'<br>';
	#print'script filename: '.$_SERVER['SCRIPT_FILENAME'].'<br>';
	#print'hostname: '.$_SERVER['HTTP_HOST'].'<br>';
	#print'self: '.$_SERVER['PHP_SELF'].'<br>';
	$hostname = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);
	#print'path: '.$path.'<br>';
	#print'Pfad zur geschützten Admin-Seite: http://'.$hostname.$path.'/chor_admin.php<br>';
	#print 'pass: *'.$sommer.'*<br>';
	
	$versuche=0;
	if (isset($_SESSION['versuche']))
	{
	$versuche = $_SESSION['versuche'];
	}
	#print 'versuche: *'.$versuche.'*<br>';
	
	print'<form name = "login" action="Fotoalbum/index.php" method="post">';
	print'<input type="hidden" name="pass" value = 0>';
	print'<input type="hidden" name="anzahlversuche" value = '.$versuche.'>';
	print'<input type="hidden" name="user" value = "falken">';
	print'<table border=0>';
	print '<tr style = "height:30px; ">';
	print '<th style = "border:none; width: 120px;padding-top: 8px;" >';
	print '<p style = "font-family: Arial, Helvetica, sans-serif;font-size: 24px;">Login: </p>';
	print '</th>';
	print '<th style = "border:none; width: 120px;padding-top: 8px;" ></th>';
	print '<th style = "border:none; width: 180px;padding-top: 8px;" ></th>';
	print'<tr>';
	print'<td> </td>';
	print'</tr>';
	
	print'<tr>';
	print'<td></td>';
	print'<td><p style = "font-family: Arial, Helvetica, sans-serif;font-size: 18px;">Passwort: </p></td>';
	print'<td>';
	print'<input type="password" name="pass"></td>';

	#print'</tr>';
	#print'<tr>';
	print'<td></td>';
	print'<td><input type="submit" name="senden" value="Login" ><td></td></td>';
	print'</tr>';
	print'</table>';
	print'</form>';

?>
<!-- Focus auf Textfeld: http://www.mediacollege.com/internet/javascript/form/focus.html -->

</body>
</html>

