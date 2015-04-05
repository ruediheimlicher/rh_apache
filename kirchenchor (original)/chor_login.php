<?php
session_start();

/*

Login Datenbank Chor
*/


/* verbinden mit db */
#	$db = include "archivplan.php";
	#$db=mysql_connect("localhost","ruediheimlicher","RivChuv4");
	/* nun ist der zugang zum db-server in der variable $db gespeichert */
#	mysql_set_charset('utf8',$db);
	/* hier wird nun die eigentliche db ausgewählt */
#	mysql_select_db("ruediheimlicher_kicho",$db); 


	$planfile = "../plans/archivplan.txt";
	if (file_exists($planfile)) 
	{
		$sommer = file_get_contents($planfile);
		print'planfile: '.$sommer.'<br>';
	}
	else
	{
		print'kein planfile<br>';# an: '.$planfile.'<br>';
	}
	    $hostname = $_SERVER['HTTP_HOST'];
      $path = dirname($_SERVER['PHP_SELF']);

?>
<?php
print_r($_POST);
print'<br>';

	$planfile = "../Data/kirchenchor_data/kicholog.txt";
	if (file_exists($planfile)) 
	{
	print'kicholog da<br>';
	}
	else
	{
		print'kein kicholog da<br>';	
		}



$loghandle = fopen ("../Data/kirchenchor_data/kicholog.txt", "w");
$zeitstempel = date('d.m.Y H:i:s');
$user_ip = $_SERVER['REMOTE_ADDR'];
$self_ip = $_SERVER['PHP_SELF'];
$anz=0;
if (isset($_SESSION['versuche']))
{
$anz=$_SESSION['versuche'];
}
if (isset($_POST['passwort']) && $_POST['passwort'] == $sommer) # login OK
{
	# Login Verfahren:
	# http://aktuell.de.selfhtml.org/artikel/php/loginsystem/

	$_SESSION['pass']=1;
	$_SESSION['versuche']=0;
	
	if ($_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1') 
	{
        if (php_sapi_name() == 'cgi') 
        {
         	header('Status: 303 See Other');
         }
        else 
        {
         	header('HTTP/1.1 303 See Other');
         }
    }
    
    
    {
		if (!fwrite($loghandle, "$zeitstempel\t IP OK: \t$user_ip\n")) 
		{
			print "Kann in die Datei $logzeile nicht schreiben";
			fclose ($loghandle);
			exit;
		}

		#$_SERVER['HTTP_X_FORWARDED_FOR']
		
		fclose ($loghandle);
		
       header('Location: http://'.$hostname.$path.'/chor_admin.php');
       exit;
       }
}
else
{
	if (isset($_POST['passwort']) && strlen($_POST['passwort'])) # Eingabeversuch
	{
		
		if (!fwrite($loghandle, "$zeitstempel\t IP Versuch $anz:\t$user_ip\n")) 
		{
			print "Kann in die Datei $logzeile nicht schreiben";
			fclose ($loghandle);
			exit;
		}
		if ($anz > 2)
		{
		    header('Location: http://www.google.com');
       		exit;

		}

		$_SESSION['versuche']=$_SESSION['versuche']+1;
		
		
	}
	else # Eingabe leer
	{
		
		if (!fwrite($loghandle, "$zeitstempel\t IP leer:\t$user_ip\n")) 
		{
			print "Kann in die Datei $logzeile nicht schreiben";
			fclose ($loghandle);
			exit;
		}

		$_SESSION['versuche']=0;
	}
}
fclose ($loghandle);
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chor Login</title>
 
<link href="chor.css" rel="stylesheet" type="text/css" />
</head>

<body class="basic">

<body OnLoad="document.login.passwort.focus();">
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
	
	
	$versuche = $_SESSION['versuche'];
	#print 'versuche: *'.$versuche.'*<br>';
	
	print'<form name = "login" action="" method="POST">';
	print'<input type="hidden" name="pass" value = 0>';
	print'<input type="hidden" name="anzahlversuche" value = '.$versuche.'>';

	print'<table border=0>';
	print '<tr style = "height:30px; ">';
	print '<th style = "border:none; width: 120px;padding-top: 8px;" >';
	print '<h2 class="selectuntertitel ">Login</h2>';
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
	print'<input type="password" name="passwort"></td>';

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

