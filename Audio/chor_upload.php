<?
/* verbinden mit db */	
	$db=mysql_connect("myni3576.sql.mhs.ch","myni3576","ruelczhedcu");
	/* nun ist der zugang zum db-server in der variable $db gespeichert */
	mysql_set_charset('utf8',$db);
	/* hier wird nun die eigentliche db ausgewählt */
	mysql_select_db("myni3576_kicho",$db); 
if (isset($_POST['uploadok']))
{
	$upda=1;
	#ßheader("location:chor_sql.php?");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type"content="text/html;charset=UTF-8"/>
<link href="chor.css"rel="stylesheet"type="text/css"/>
</head>
<body class="basic">
<?
print '<div id="admin">';
print '<div id="adminContent">';
#POST abfragen
print_r($_POST);
#print_r($_GET);
print '<br>';
print '<br> sql_upload: <br>';
print 'upda: '.$upda.'<br>';

print '<form action="chor_db.php"method="POST">';
print ' <input type="hidden"name="task"value="upload"/>';
print '		<input type="hidden" name="archivpfad" value="'.$archivpfad.'" type="file"/>'; 	# POST archivpfad

print ' <input type="submit"name="back"value="Daten laden"/>';
print '</form>';


print '<form action="choradmin.php"method="POST">';
print ' <input type="hidden"name="uploadok"value="0"/>';
print ' <input type="submit"name="back"value="zurück"/>';
print '</form>';

print '</div>';	# adminContent
print '</div>';	# admin
?>

    </body>
</html>
