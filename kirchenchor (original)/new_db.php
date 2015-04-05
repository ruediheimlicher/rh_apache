<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin</title>
 
</head>

<body >
	
<h2 style="margin-left:10px">Neue DB</h2>
<?

function table_exist($table)
{ 
    $pTableExist = mysql_query("show tables like '".$table."'");
    if ($rTableExist = mysql_fetch_array($pTableExist)) {
        return "Yes";
    }else{
        return "No";
    }
} 
print_r($_POST);
print '<br>';

$task = "new";
if ($_POST['task'])
{
	$task = $_POST['task'];

}


if ($task == 'new_db')
{


/* verbinden mit db */
#	$db=mysql_connect("localhost","ruediheimlicher","RivChuv4");
	/* nun ist der zugang zum db-server in der variable $db gespeichert */
#	mysql_set_charset('utf8',$db);
	/* hier wird nun die eigentliche db ausgewählt */
#	mysql_select_db("ruediheimlicher_kicho",$db); 




	$benutzer = $_POST['neuerbenutzer'];
	$kennwort = $_POST['neueskennwort'];
	$datenbank = $_POST['neuedatenbank'];
	$tabelle = $_POST['neuetabelle'];
	$localhost = "localhost";
	
	#$loginstring = '"'.$localhost.'","'.$benutzer.'","'.$kennwort.'"';
	
	#print 'loginstring: '.$loginstring.'<br>';
	
	/* verbinden mit db */
	#$db=mysql_connect('"'.$loginstring.'"');
	$db=mysql_connect("$localhost","$benutzer","$kennwort");
	/* nun ist der zugang zum db-server in der variable $db gespeichert */
	mysql_set_charset('utf8',$db);
	/* hier wird nun die eigentliche db aftp://ruediheimlicher:@ruediheimlicher.ch//public_html/kirchenchor/new_db.phpusgewählt */
	$result_count = mysql_query("SELECT COUNT(*)
					FROM information_schema.tables 
					WHERE table_schema = '$datenbank'  
					AND table_name = '$tabelle'");
	#mysql_query("CREATE TABLE  '$datenbank'");
	if (mysql_error())
	{
		print 'SELECT COUNT  error:';
		print mysql_error();
		print '<br>';
	}
	else
	{
		$anz = mysql_result($result_count,0);
		print 'SELECT COUNT alles OK. count: '.$anz.'<br>';
		if ($anz == 0)
		{
		
		
		$sql = "CREATE DATABASE $datenbank";
		if (mysql_query($sql, $db)) 
		{
    	echo "Schema my_db erfolgreich erzeugt\n";
		} 
		else 
		{
    		echo 'Schemaerzeugung fehlgeschlagen:: ' . mysql_error() . "\n";
		}
		
		
		
		print 'neue tabelle: '.$tabelle.'<br>';
		/*
		mysql_query("CREATE TABLE example(
		id INT NOT NULL AUTO_INCREMENT, 
		PRIMARY KEY(id),
 		name VARCHAR(30), 
 		age INT)")
 		or die(mysql_error());  

		echo "Table Created!";
		*/
		/*
		Aus phpMyAdmin:
		$create_table  = CREATE TABLE  `ruediheimlicher_kicho`.`abc` (
		`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
		`name` VARCHAR( 6 ) NULL ,
		PRIMARY KEY (  `id` )
		) ENGINE = MYISAM);
		*/

   	$create_table = mysql_query("CREATE TABLE  $datenbank.$tabelle (
                  id int(11) NOT NULL auto_increment,
                  titel varchar(200)  NULL,
                  name text NOT NULL,
                  PRIMARY KEY (id) )ENGINE=MyISAM  DEFAULT CHARSET=utf8")
      		or die ("Fehler beim Erstellen der Tabelle" );
      		
	if (mysql_error())
	{
		print 'CREATE suchname error:';
		print mysql_error();
		print '<br>';
	}
	else
	{
		print_r($create_table);
		print 'alles OK.<br>';
	}

		}
	
	}
	
	
	mysql_select_db("$datenbank",$db); 

	if (mysql_error())
	{
		print 'SELECT suchname error:';
		print mysql_error();
		print '<br>';
	}
	else
	{
		print 'SELECT alles OK.<br>';
	}
	


}	# if new_db


print '<form action="" method="post">';
$neuerdatensatzstring = '<h2 style="margin-left:10px">Daten</h2>';
$neuerdatensatzstring .= '<p  style="margin-left:10px">Benutzername:<br><input size="40" maxlength="40" name="neuerbenutzer" value = "'.$benutzer.'"></p>';
$neuerdatensatzstring .= '<p style="margin-left:10px">Kennwort:<br><input size="40" maxlength="40" name="neueskennwort" value = "'.$kennwort.'"></p>';
$neuerdatensatzstring .= '<p style="margin-left:10px">Datenbank<br><input size="40" maxlength="40" name="neuedatenbank" value = "'.$datenbank.'"></p>';
$neuerdatensatzstring .= '<p style="margin-left:10px">Tabelle<br><input size="40" maxlength="40" name="neuetabelle" value = "'.$tabelle.'"></p>';
print $neuerdatensatzstring;
print '<input type="hidden" name="task" value ="new_db">';
print '<br><p style="margin-left:10px" ><input type="submit" name="neuedb" value="neue Datenbank einrichten"></p>';
print '</form><br>';




?>


</body>

</html>

