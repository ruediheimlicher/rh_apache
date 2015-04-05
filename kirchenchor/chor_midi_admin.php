<?php
/*

Chor MIDI Admin Datenbank 
*/

include("pwd.php");

/* verbinden mit db */
	$db = include "../bank.php";

	mysql_set_charset('utf8',$db);
	mysql_select_db("midi", $db); 

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chor MIDI Admin</title>
 
<link href="chor.css" rel="stylesheet" type="text/css" />
</head>

<body class="liste">
	

<div><h1 class="lernmedien">Chor MIDI Admin</h1></div>
	
<!--<h2 class="lernmedien">Admin</h2>-->

<?php
#phpinfo();
print '<p>benutzer;: '. $benutzer.'* pw: '. $passwort.'*</p>';
print_r($_POST);

print'<br>';
$pass = 0;
if (isset($_POST['passwort']))
{
	$pass = $_POST['passwort'];
}

$user=0;
if (isset($_POST['user']))
{
	$user = $_POST['user'];
}

$test = 0;
if (isset($_POST['test']))
{
	$test = $_POST['test'];
}
print '<p>test: '.$test.'  pass: *'. $pass.'*</p>';
$user = "admin";

if (isset($_POST['kopieren']) && $_POST['kopieren'])
{
	print '<p>kopieren</p>';
	#$result_insert = mysql_query("CREATE TABLE testarchiv AS SELECT * FROM archiv");
	$result_insert = mysql_query("CREATE TABLE archiv AS SELECT * FROM archiv");
	if (mysql_error())
	{
		print 'Fehler beim Kopieren der DB: *'.mysql_error().'*<br>';
		print 'CREATE error: *'.mysql_error().'*<br>';
	}
	else
	{
		print 'kolonne: '.$num.' INSERT ok<br>';
	}

}
#CREATE TABLE neu_test AS SELECT * FROM test;
$test=1;
$pass = $passwort;
if (($test == 1) || ($passwort == "$pass"))
	{
	# print '<p class = "nameneingabe">Login OK</p>';
	
	print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form   action="chor.php" method = "post">';
	print '<input type="hidden" name="task" value ="">';
	print '<h3 class = "admin" >';
	print '<input type="submit" class="links40" name="textadmin" value="Chor Audio"style="width: 150px; margin-right:10px;"> Chor Audio';
	print '</h3>';
	print '</form>';

	print '<hr style=" width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form  action="chor_archiv.php" method = "post">';
	print '<input type="hidden" name="task" value ="">';
	print '	<input type="hidden" name="test" value =1>';
	print '<h3 class = "admin" >';
	print '<input type="submit" class="links40" name="archivfilter" value="Archiv" style="width: 150px; margin-right:10px;"> Archiv';
	print '</h3>';
	print '</form>';

	print '<hr style=" width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';
	print '<form   action="chor_besucher.php" method = "post">';
	print '<input type="hidden" name="task" value ="besucher">';
	print '<h3 class = "admin"  >';
	print '<input type="submit" class="links40" name="statistik" value="Besucher"style="width: 150px; margin-right:10px;"> Besucher';
	print '</h3>';
	print '</form>';
	


# CREATE TABLE neu_test AS SELECT * FROM test;


	?>

	<?php

	$sent='no';
	$zeilenname=0;
	$mediumordner="";
	#$db->set_charset("utf8"); 
	/* sql-abfrage schicken */
	$result_archiv = mysql_query("SELECT * FROM audio ", $db)or die(print '<p  >Beim Suchen nach mididaten ist ein Fehler passiert: '.mysql_error().'</p>');
if (mysql_error())
{
	print 'SELECT suchname error:';
	print mysql_error();
	print '<br>';
};

/*
audio:
id
aktiv
event
werk
satznummer
satz
bezeichnung
register
stimme1
stimme2
stimme3
*/


# Datensatz ändern
$sent='no';
$zeilenid="";
$editid=0;
#print_r($_POST);
if (isset($_POST['edit']))
{
	$zeilenid = $_POST['edit'];
	print 'edit count: '.count($zeilenid).'<br>';
	if (count($zeilenid))
	{
		$editid=$zeilenid[0];
		print 'editid aus edit: '.$editid.'<br>';
		
		if (count($zeilenid)>1)
		{
		$editname=$zeilenid[1];
		}
	}

	
}


$satzid=0;
if (isset($_POST['index']))
{
	#$satzid =  $_POST['index'][$editid];
}
print 'satzid aus edit: '.$satzid.'<br>';

if (isset($_POST['sent']))
	$sent = $_POST['sent'];	
	#print 'sent: '.$sent.'<br>';
		
	if ($sent == 'yes') 
	{
		#print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

		if ( isset($_POST['edit']))
		{
			#print 'edit da<br>';
			if (isset($_POST['changeradio']))
			{
				$changeradio = $_POST['changeradio'];	
				print 'changeradio: '.$changeradio.'<br>';
				print 'editid: '.$editid.' satzid: '.$satzid.'<br>';
				
				$zeilenidstring = implode(', ',$zeilenid);
				#print 'zeilenid[0]: *'.$zeilenid[0].'*  zeilenidstring: '.$zeilenidstring.' <br>';
				$index=0;
				mysql_data_seek($result_archiv,$index);
				#$zeile= mysql_fetch_assoc($result_archiv);
			
				#$result_idzeile = mysql_query("SELECT * FROM testarchiv WHERE id = $editid", $db);
				$result_idzeile = mysql_query("SELECT * FROM audio WHERE id = $editid", $db);
			
				#$zeilenarray = mysql_fetch_row($result_idzeile);
				$zeilenarray = mysql_fetch_assoc($result_idzeile);
				print 'zeilenarray count: '.count($zeilenarray).'<br>';
				print_r($zeilenarray);
				print '<br>';
				if ($changeradio == 'change')
				{
					print 'CHANGE<br>';
					
					foreach ($zeilenarray as $data)
					{
						#print 'data: '.$data.'<br>';
					}
	
					print 'aktiv: '.$zeilenarray['aktiv'].'<br>';
					print 'event: '.$zeilenarray['event'].'<br>';
					print 'werk: '.$zeilenarray['werk'].'<br>';
					print 'satznummer: '.$zeilenarray['satznummer'].'<br>';
					print 'satz: '.$zeilenarray['satz'].'<br>';
					print 'register: '.$zeilenarray['register'].'<br>';
					print 'stimme1: '.$zeilenarray['stimme1'].'<br>';
					print 'stimme2: '.$zeilenarray['stimme2'].'<br>';
					print 'stimme3: '.$zeilenarray['stimme3'].'<br>';
					
					
					$changeaktiv=0;
					$changeevent="";
					$changewerk="";
					$changsatznummer=0;
					$changesatz=0;
					$changebezeichnung="";
					$changeregister="";
					$changestimme1="";
					$changestimme2="";
					$changestimme3="";
					$changeanmerkung = "";
					$changeaktiv=trim($zeilenarray['aktiv']);
					
					$changeevent=trim($zeilenarray['event']);
					$changewerk=trim($zeilenarray['werk']);
					$changesatznummer=trim($zeilenarray['satznummer']);
					$changesatz=htmlentities($zeilenarray['satz']);
					$changebezeichnung=htmlentities($zeilenarray['bezeichnung']);
					$changeregister=htmlentities($zeilenarray['register']);
					$changestimme1=htmlentities($zeilenarray['stimme1']);
					$changestimme2=trim($zeilenarray['stimme2']);
					$changestimme3=$zeilenarray['stimme3'];
					
					#$changeanmerkung=$zeilenarray['anmerkung'];
					#$changeteaser=$zeilenarray['teaser'];
					
					$changedatensatzstring = '<h2 style="margin-left:30px">vorhandener Datensatz</h2>';
					print $changedatensatzstring;
					print '<form action="chor_midi_adminconfirm.php" method="post">';
					print '
					
					<table>midi_
						<tr>
							<td><p class="nameneingabe">aktiv:</td>
							<td><input size="4" maxlength="40" name="changeaktiv" value ="'.$changeaktiv.'"></td>
						</tr>
						<tr>
							<td><p class="nameneingabe">Event:</td>
							<td><input size="40" maxlength="40" name="changeevent" value ="'.$changeevent.'"></td>
						</tr>
						<tr>
							<td><p class="nameneingabe">Werk:</td>
							<td><input size="40" maxlength="40" name="changewerk" value ="'.$changewerk.'"></td>
						</tr>
						<tr>
							<td><p class="nameneingabe">Satznummer:</td>
							<td><input size="4" maxlength="40" name="changesatznummer" value ="'.$changsatznummer.'"></td>
						</tr>
						<tr>
							<td><p class="nameneingabe">Satz:</td>
							<td><input size="40" maxlength="40" name="changesatz" value ="'.$changesatz.'"></td>
						</tr>
						<tr>
							<td><p class="nameneingabe">Bezeichnung:</td>
							<td><input size="40" maxlength="40" name="changebezeichnung" value ="'.$changebezeichnung.'"></td>
						</tr>
						<tr>
							<td><p class="nameneingabe">Register:</td>
							<td><input size="40" maxlength="40" name="changeregister" value ="'.$changeregister.'"></td>
						</tr>
						<tr>
							<td><p class="nameneingabe">Stimme 1:</td>
							<td><textarea rows="2" cols="140"  name="changestimme1"> '.$changestimme1.'</textarea></td>
						</tr>
						<tr>
							<td><p class="nameneingabe">Stimme 2:</td>
							<td><textarea rows="2" cols="140"  name="changestimme2"> '.$changestimme2.'</textarea></td>
						</tr>
									<tr>
							<td><p class="nameneingabe">Stimme 3:</td>
							<td><textarea rows="2" cols="140"  name="changestimme3"> '.$changestimme3.'</textarea></td>
						</tr>
						<tr>
							<td><p class="nameneingabe">Anmerkung:</td>
							<td><textarea rows="2" cols="45" name="changeanmerkung">'.$changeanmerkung.'</textarea></td>
						</tr>
			
					</table>';
					
					
					
					#$changedatensatzstring .= '<p class="nameneingabe">Datum:<input size="40" maxlength="40" name="changedatum" value ="'.$changedatum.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Jahr:<input size="40" maxlength="40" name="changejahr" value ="'.$changejahr.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Pfr:<input size="40" maxlength="40" name="changepfr" value ="'.$changepfr.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Auftritt:<br style = "font-size:4px"><input size="40" maxlength="40" name="changeart" value ="'.$changeart.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Begleitung:<br style = "font-size:4px"><input size="40" maxlength="40" name="changebegleitung" value ="'.$changebegleitung.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Quelle:<br style = "font-size:4px"><input size="40" maxlength="40" name="changequelle" value ="'.$changequelle.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Nr:<br style = "font-size:4px"><input size="40" maxlength="40" name="changenr" value ="'.$changenr.'"></p>';
					
					#$changedatensatzstring .= '<p class="nameneingabe">Mitwirkung:<br style = "font-size:4px"><input size="40" maxlength="40" name="changemitwirkung" value ="'.$changemitwirkung.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Komponist:<br style = "font-size:4px"><input size="40" maxlength="40" name="changekomponist" value ="'.$changekomponist.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Vorname:<br style = "font-size:4px"><input size="40" maxlength="40" name="changekomponist_vn" value ="'.$changekomponist_vn.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Werk:<br style = "font-size:4px"><input size="40" maxlength="40" name="changewerk" value ="'.$changewerk.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Teil:<br style = "font-size:4px"><input size="40" maxlength="40" name="changeteil" value ="'.$changeteil.'"></p>';
					#$changedatensatzstring .= '<p class="nameneingabe">Anmerkung:<br><textarea rows="2" cols="45" name="changeanmerkung">'.$changeanmerkung.'</textarea></p>';
					
					
					print '<input type="hidden" name="index" value ="'.$editid.'">';
					print '<input type="hidden" name="test" value =1>';
					print '<input type="hidden" name="task" value ="change">';
					
			
					print '<p class="nameneingabe" ><input type="submit" name="speichern" value="Datensatz neu speichern"></p>';
					print '</form><br>';
				
				} # end if change
		
			
			
				if ($changeradio == 'delete')
				{ 	# delete
					#print '>DELETE<br>';
					print '<form action="chor_midi_adminconfirm.php"  method="post">';
					$deleteid = $zeilenid[0];
			
					$deletename=$zeilenarray['name'];
					print '	<input type="hidden" name="test" value ="'.$test.'">';
					print '<input type="hidden" name="name" value ="'.$deletename.'">';
					print '<input type="hidden" name="index" value ="'.$editid.'">';
					print '<input type="hidden" name="task" value ="delete">';
					# <h2 class="lernmedien">Fehler im neuen Datensatz</h2>
					print '<p ><h2 class="lernmedien"> Datensatz <strong>*'.$deletename.'*</strong> wirklich löschen?</h2>';
					print '<input type="submit"  class="links40" name="speichern"  value="Datensatz löschen"></p>';
					print '</form>';
					print '<form action=""  method="post">';
					print '<input type="hidden" name="task" value ="new">';
					print '<input type="hidden" name="sent" value ="no">';
				
					print '<input type="submit" class="links40"  name="speichern"  value="Abbrechen"></form>';
				
					
					
				}# end if delete
		
			}
			else
			{
				print 'kein changeradio: <br>';
			}

	
		} 
		else
		{
			print '<p class = "liste">kein Auftrag</p><br>';
		
		}
	}
	else if ($sent == 'err') # falsche Eingabe, Felder wieder laden
	{
		print '<h2  style="margin-left:30px">Fehler im neuen Datensatz</h2>';
	
		print '<hr style=" width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';
	
					print 'ERR<br>';
					/*
					$errname=$zeilenarray[1];
					#$changename=htmlentities($zeilenarray[1]);
					$errbeschreibung=$zeilenarray[2];
					$errart=$zeilenarray[3];
					$errgruppe=$zeilenarray[5];
					$errpreis=$zeilenarray[6];
					$errstufe=htmlentities($zeilenarray[7]);
					*/
			
					$errdatum=trim($zeilenarray['datum']);
					$errjahr=trim($zeilenarray['jahr']);
					$errpfr=trim($zeilenarray['pfr']);
					$errmitwirkung=trim($zeilenarray['mitwirkung']);
					$errart=htmlentities($zeilenarray['art']);
					$errbegleitung=htmlentities($zeilenarray['begleitung']);
					$errquelle=htmlentities($zeilenarray['quelle']);
					$errnr=htmlentities($zeilenarray['nr']);
					$errkomponist=trim($zeilenarray['komponist']);
					$errkomponist_vn=$zeilenarray['komponist_vn'];
					$errwerk=$zeilenarray['werk'];
					$errteil=$zeilenarray['teil'];
					
					$erranmerkung=$zeilenarray['anmerkung'];
	
				
					print '<form action="chor_midi_adminconfirm.php" method="post">';
					$errdatensatzstring = '<h1 style="margin-left:40px">Daten</h1>';
					$changedatensatzstring .= '<p class="nameneingabe">Datum:<br><input size="40" maxlength="40" name="neuesdatum" value ='.$errdatum.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Jahr:<br><input size="40" maxlength="40" name="neuesjahr" value ="'.$errjahr.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Pfr:<br><input size="40" maxlength="40" name="neuerpfr" value ="'.$errpfr.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Mitwirkung:<br><input size="40" maxlength="40" name="neuemitwirkung" value ="'.$errmitwirkung.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Auftritt:<br><input size="40" maxlength="160" name="neueart" value ="'.$errart.'"></p>';					

					$errdatensatzstring .= '<p class="nameneingabe">Begleitung:<br><input size="40" maxlength="160" name="neuebegleitung" value ="'.$errbegleitung.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Quelle:<br><input size="40" maxlength="160" name="neuequelle" value ="'.$errquelle.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Nr:<br><input size="40" maxlength="160" name="neuenr" value ="'.$errnr.'"></p>';
					
					$errdatensatzstring .= '<p class="nameneingabe">Komponist:<br><input size="40" maxlength="40" name="neuerkomponist" value ="'.$errkomponist.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Vorname:<br><input size="40" maxlength="40" name="neuerkomponist_vn" value ="'.$errkomponist_vn.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Werk:<br><input size="40" maxlength="40" name="neuerswerk" value ="'.$errwerk.'"></p>';
					$errdatensatzstring .= '<p class="nameneingabe">Teil:<br><input size="40" maxlength="40" name="neuerteil" value ="'.$errteil.'"></p>';
					
					#$errdatensatzstring .= '<p class="nameneingabe">Anmerkung:<br><textarea rows="2" cols="45" name="neueanmerkung" <textarea>'.$erranmerkung.'</textarea></textarea></p>';

					$errdatensatzstring .= '<p class="nameneingabe">Beschreibung:<br><textarea rows="2" cols="45" name="neuebeschreibung" <textarea>'.$erranmerkung.'</textarea></textarea></p>';
				
				
				
					print '<input type="hidden" name="index" value ="'.$editid.'">';
					print '	<input type="hidden" name="test" value ="'.$test.'">';
					print '<input type="hidden" name="task" value ="new">';
					print $errdatensatzstring;
					print '<br><p class="nameneingabe" ><input type="submit" name="speichern" value="Datensatz neu speichern"></p>';
					print '</form><br>';

	}




	else
	{

		# neuer Datensatz
		print '<h2  style="margin-left:30px">neuer Datensatz</h2>';
		print '<form action="chor_midi_adminconfirm.php" method="post">';
		
		$neuerdatensatzstring = '<h2 style="margin-left:40px">Daten</h2>';
		
		print '
		<table>
			<tr>
				<td><p class="nameneingabe">aktiv:</td>
				<td><input size="4" maxlength="40" name="neuesaktiv"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">event:</td>
				<td><input size="40" maxlength="40" name="neuerevent"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">werk:</td>
				<td><input size="40" maxlength="40" name="neueswerk"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Satznummer:</td>
				<td><input size="4" maxlength="40" name="neuesatznummer"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Satz:</td>
				<td><input size="40" maxlength="40" name="neuersatz"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Bezeichnung:</td>
				<td><input size="40" maxlength="40" name="neuebezeichnung"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Register:</td>
				<td><input size="40" maxlength="40" name="neuesregister"></td>
			</tr>
						<tr>
				<td><p class="nameneingabe">Stimme 1:</td>
				<td><textarea rows="2" cols="45" name="neuestimme1"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 2:</td>
				<td><textarea rows="2" cols="45" name="neuestimme2"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 3:</td>
				<td><textarea rows="2" cols="45" name="neuestimme3"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Anmerkung:</td>
				<td><textarea rows="2" cols="45" name="neueanmerkung"></textarea></td>
			</tr>
			
		</table>';
		
		#$neuerdatensatzstring .= '<p class="nameneingabe">Datum:<br><input size="40" maxlength="40" name="neuesdatum"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Jahr:<br><input size="40" maxlength="40" name="neuesjahr"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Pfr:<br><input size="40" maxlength="40" name="neuerpfr"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Mitwirkung:<br><input size="40" maxlength="40" name="neuemitwirkung"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Auftritt:<br><input size="40" maxlength="40" name="neueart"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Begleitung:<br><input size="40" maxlength="40" name="neuebegleitung"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Quelle:<br><input size="40" maxlength="40" name="neuequelle"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Nr:<br><input size="40" maxlength="20" name="neuenr"></p>';
		
		#$neuerdatensatzstring .= '<p class="nameneingabe">Komponist:<br><input size="40" maxlength="40" name="neuerkomponist"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Vorname:<br><input size="40" maxlength="40" name="neuerkomponist_vn"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Werk:<br><input size="40" maxlength="40" name="neueswerk"></p>';
		#$neuerdatensatzstring .= '<p class="nameneingabe">Teil:<br><input size="40" maxlength="40" name="neuerteil"></p>';
		
		#$neuerdatensatzstring .= '<p class="nameneingabe">Anmerkung:<br><textarea rows="2" cols="45" name="neueanmerkung"></textarea></p>';
		
		
		#print $neuerdatensatzstring;
		print '<input type="hidden" name="task" value ="new">';
		print '	<input type="hidden" name="test" value ="'.$test.'">';
		print '<p class="nameneingabe" ><input type="submit" name="speichern" value="neuen Datensatz speichern"></p>';
		print '</form><br>';

		#print 'kein sent<br>';
	}



	#print_r($zeilenname);
	print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';
	print '<h2 style="margin-left:30px">Daten redigieren oder löschen</h2>';
	print '<div class="admintabelleabschnitt">';

	#print '<div class = "admingruppenabschnitt>';

	print '<table style="margin-left :10px; width:95%;border-collapse:collapse;cell-padding:0px;font-size:10pt;">';
	print '<tr >';


	// Tableheader
	#$tableheaderstring  = '<th class="text" width="60">Name</th>';
	#$tableheaderstring  = '<th class="text breite30">id</th>';
	$tableheaderstring = '<th class="text breite30 zentriert" >edit</th>';
	$tableheaderstring .= '<th class="text breite80">aktiv</th>';
	$tableheaderstring .= '<th class="text breite100">event</th>';
	$tableheaderstring .= '<th class="text breite100">werk</th>';
	$tableheaderstring .= '<th class="text breite200" >SatzNr</th>';
	$tableheaderstring .= '<th class="text breite200" >Satz</th>';
	$tableheaderstring .= '<th class="text breite300 " >Bez</th>';
	$tableheaderstring .= '<th class="text breite80 " >Register</th>';
	$tableheaderstring .= '<th class="text breite100 " >Stimme 1</th>';
	$tableheaderstring .= '<th class="text breite100 " >Stimme 2</th>';
	$tableheaderstring .= '<th class="text breite100 " >Stimme 3</th>';

	
	$tableheaderstring .= '	</tr>';

	print $tableheaderstring;

	print '<form action="" method="post">';
	#print '<form action="/cgi-bin/lerntest.pl" method="post" accept-charset="UTF8">';
	$zeile=0;
	while ($archivdaten = mysql_fetch_assoc($result_archiv) )
	{
		#print ' <p>zeile: '.$zeile.' name: '.$archivdaten['name'].' nummer: '.$archivdaten['nummer'].' teaser: '.$archivdaten['teaser'].'</p>';;

		print '<tr class = "admintabelletablerow">';
		//print '<td align="center">'.'<input type = "hidden" name= ' . $archivdaten['id'] . '>';
		$ordnernummer=sprintf("%03d",$archivdaten['id']);
		$bildnummer=$ordnernummer."0010";
		$bildlink = "Bilder/".$ordnernummer."/".$ordnernummer."0010".".jpg";
		print '<td class="listetabellenzahl ">'.'<input type="checkbox" name="edit[]" method="post" value='. $archivdaten['id'].' >'. '</td>';

		print '<td class="listetabellentext">'.$archivdaten['aktiv'] . '</td>';
		print '<td class="listetabellentext">'. $archivdaten['event'] . '</td>';
		print '<td class="listetabellentext">'.$archivdaten['werk'] . '</td>';
		print '<td class="listetabellentext">'.$archivdaten['satznummer'] . '</td>';
		print '<td class="listetabellentext">'.$archivdaten['satz'] . '</td>';
		print '<td class="listetabellentext">'.$archivdaten['bezeichnung'] . '</td>';
		print '<td class="listetabellentext">'.$archivdaten['register'] . '</td>';
		print '<td class="listetabelleurl">'.$archivdaten['stimme1'] . '</td>';
		print '<td class="listetabelleurl">'.$archivdaten['stimme2'] . '</td>';
		print '<td class="listetabelleurl">'.$archivdaten['stimme3'] . '</td>';
	
		print '	</tr>';
	
		print '	<input type="hidden" name="test" value ="'.$test.'">';
		print '<input type="hidden" name=index[] value ='.$archivdaten['id'].'>';
		#print '<input type="hidden" name=name[] value ="'.$archivdaten['name'].'">';
		#print '<input type="hidden" name=preis[] value ='.$archivdaten['preis'].'>';

	$zeile++;
	}
	print '<input type="hidden" name="sent" value="yes">';

	print '</table>';
	print '<br>';
	print '</div>'; # adminabschnitt1
	print '<br>';
	# einführung Radiobutton

	# change

	# Datensatz ändern. Bringt die Daten in die Eingabefelder
	
	print '<p class="nameneingabe" ><input type="radio" name="changeradio" method="post" value="change" checked >Datensatz ändern ';

	# Datensatz löschen
	print '<input type="radio" class="links40" name="changeradio" method="post" value="delete" >Datensatz löschen</p>';
	print '	<input type="hidden" name="test" value =1>';
	print '<input type="submit" class="links40" name="change" value="Weiterfahren"></form>';

	#print '</div>'; # admingruppenabschnitt
	#print '</div>'; # adminabschnitt1

	print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<h2 style="margin-left:30px; margin-bottom:0px;">Neue Bilddateien von Konzerten laden</h2>';
	
	#print '<hr style="; width:600px;margin-left:20px; margin-right:60px; margin-top:0px; height:1px color:red; background-color:yellow; ">';
	
	# Tabelle laden
		$gruppenordnerpfad = "kirchenchor_data";
		$bildordnerpfad = "konzert";
		print '<p class = "nameneingabe" ><strong>Benötigte Dateien:</strong>(nacheinander zu laden) </p>';
		
		print '<dir style="margin-left:30px; margin-bottom:0px;margin-top:0px;" >
		<ul>
  		<li> Dateiname: <strong>Plakat</strong> Dateiformat: <strong>pdf</strong></li>
  		<li> Dateiname: <strong>Programm</strong> Dateiformat: <strong>pdf</strong></li>
  		<li> Dateiname: <strong>ProgrammTitel</strong> Dateiformat: <strong>jpg</strong></li>
		</ul>
		</dir>';
		
		print '<p class = "nameneingabe" >Bezeichnung für alle Dateien: <strong>Jahr_Monat_Dateiname</strong> Bsp: <strong>2013_04_Plakat.pdf</strong> </p>';

		
		print '<form action="chor_midi_adminconfirm.php" method="post"
					enctype="multipart/form-data">
						<p class="nameneingabe" ><label for="file">Pfad für Datei suchen:</label>
							<input type="file" accept=".pdf,.jpg"name="tabelle" id="tabellenfile" />
						</p>';
	
					# Pfade weitergeben
		print '		<input type="hidden" name="task" value ="upload">';
		print '		<input type="hidden" name="gruppenordnerpfad" value="'.$gruppenordnerpfad.'" type="file"/>'; 	# POST gruppenordnerpfad
		print '		<input type="hidden" name="bildordnerpfad" value="'.$bildordnerpfad.'" type="file"/>'; 			# POST bildordnerpfad relevant
		print '		<input type="hidden" name="medium" value="'.$mediumordner.'" type="file"/>'; 					# POST medium
		print '		<input type="hidden" name="test" value ="'.$test.'">';
						# zu adminconfirm schicken
		print '		<input type="submit" class="links40" name="submit" value="Datei laden" />';
	
		print '</form>';

	# change end

	print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';

	print '<form action="index.php" ><input type="submit" class="links40" value="zurück zur Startseite" name="textfile"></form>';
	mysql_close($db);

}
else
{
#print 'Nichts OK, Eingabe falsch.';
print '<p class = "links40">Eingabe falsch! <form action="index.php" ><input type="submit" class="links40" value="zurück zur Startseite"></form></p> ';
}	

?>

</body>

</html>

