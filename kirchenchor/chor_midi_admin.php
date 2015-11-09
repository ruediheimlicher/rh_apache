<?php
/*

Chor MIDI Admin Datenbank 
*/

include("pwd.php");

/* verbinden mit db */
	#$db = include "../bank.php";
	$db = include "chor_db.php";

	#mysql_set_charset('utf8',$db);
	#mysql_select_db("midi", $db); 

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chor MIDI Admin</title>
 
<link href="chor.css" rel="stylesheet" type="text/css" />

   <script type="text/javascript">
    function makeSelection(frm, id) 
    {
      if(!frm || !id)
        return;
      var elem = frm.elements[id];
      if(!elem)
        return;
      var val = elem.options[elem.selectedIndex].value;
      opener.targetElement.value = val;
      this.close();
    }
    </script>



</head>

<body class="liste">
	

<div><h1 class="lernmedien">Chor MIDI Admin</h1></div>
	
<!--<h2 class="lernmedien">Admin</h2>-->
<!--https://www.daniweb.com/web-development/javascript-dhtml-ajax/threads/119146/using-a-popup-to-fill-an-input-field -->

<!--
 <form id="frm" name="frm" action="#">
    <span>Names: </span>
    <select name="nameSelection">
      <option value="holly">Holly</option>
      <option value="golly">Golly</option>
      <option value="molly">Molly</option>
    </select>
    <input type="button" value="Select Name" onclick="makeSelection(this.form, 'nameSelection');">
  </form>
--!>
<?php
#phpinfo();
#print '<p>benutzer;: '. $benutzer.'* pw: '. $passwort.'*</p>';
#print_r($_POST);

#print'<br>';
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
#print '<p>test: '.$test.'  pass: *'. $pass.'*</p>';
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
	print_r($_POST['edit']);
	$zeilenid = $_POST['edit'];
	print 'edit count: *'.count($zeilenid).'*<br>';
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
#print '<br>satzid aus edit: '.$satzid.'<br>';

if (isset($_POST['sent']))
	$sent = $_POST['sent'];	
#	print 'sent: '.$sent.'<br>';
		
	if ($sent == 'yes') 
	{
		#print '<hr style="; width:600px;margin-left:20px; margin-right:60px; height:1px color:red; background-color:yellow; ">';
		$anzclicked=0;
		if ( isset($_POST['edit']))
		{
			$anzclicked = count($_POST['edit']);	
#			print 'edit da. anzclicked: '.$anzclicked.'<br>';
			
			if (isset($_POST['changeradio']))
			{
				$changeradio = $_POST['changeradio'];
				
#				print 'X changeradio: '.$changeradio.'<br>';
				
				# mehrere DS
				if ($anzclicked > 1)
				{
					$clickedarray = $_POST['edit'];
#					print 'clickedarray<br>';
#					print_r($clickedarray);
#					print'<br>';
					mysql_data_seek($result_archiv,0);
					print '<form action="chor_midi_adminconfirm.php" method="post">';
					print '<input type="hidden" name="task" value ="multchange">';
					# http://stackoverflow.com/questions/14025774/php-int-array-in-where-clause-array-to-string-conversion
					$result_mult = mysql_query("SELECT * FROM audio WHERE id  IN (" . implode(',', $clickedarray) . ")", $db);
					
					print 'SELECT error: *'.mysql_error().'*<br>';
					
					$changearray = array();
					
					print '<h3>Ausgewählte Datensätze: </h3>';
					
					print '<p>';
					while ($zeile = mysql_fetch_assoc($result_mult))
					{
					 #'zeile: '.print_r($zeile).'<br>';
					 #$changearray[] = $zeile;
						$changearray[] = array('changeid'=>$zeile['id'],'changesatz'=>$zeile['satz']);
						print '<input type="hidden" name="changeid[]" value ='.$zeile['id'].'>';
						print '<input type="hidden" name="changesatz[]" value ='.$zeile['satz'].'>';
						print '<input type="hidden" name="changeregister[]" value ='.$zeile['register'].'>';
						
						print 'id: '.$zeile['id'].' Satz: '.$zeile['satz'].' Register: '.$zeile['register'].'<br>';
					}
					print '</p>';
					print 'changearray<br>';
					print_r($changearray);
					print'<br>';
					
					
					print'<br>';print'<br>';
					if ($changeradio == "delete")
					{
						print '<p class="nameneingabe" ><input type="submit" name="multdelete" value="Datensätze löschen"><input type="submit" name="cancel" value="Abbrechen"></p>';
					
					}
					elseif ($changeradio == 'change')
					{
						print '<input type="radio" id="aktivieren" name="multaktion" value="aktivieren" checked = "checked"><label for="aktivieren" >Aktivieren</label> ';
						print '<input type="radio" id="deaktivieren" name="multaktion" value="deaktivieren" "><label for="deaktivieren" >Deaktivieren</label> ';
						#print '<input type="radio" id="löschen" name="multaktion" value="delete" "><label for="delete" >Löschen</label> ';
		
						print '<p class="nameneingabe" ><input type="submit" name="multspeichern" value="Datensätze bearbeiten"><input type="submit" name="cancel" value="Abbrechen"></p>';

					}
					print '</form><br>';
				
				
				} # end mehrere DS
				else # nur ein DS
				{
					print 'editid: '.$editid.' satzid: '.$satzid.' anzclicked: '.$anzclicked.'<br>';
				
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
					#print_r($zeilenarray);
					print '<br>';
					if ($changeradio == 'change')
					{
						print 'CHANGE<br>';
					
						foreach ($zeilenarray as $data)
						{
							print 'data: '.$data.'<br>';
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
			
						$deletename=$zeilenarray['satz'];
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
					}# ein DS
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
		
		$eingabefehler = "";
		if (isset($_POST['eingabefehler']))
		{
			$eingabefehler = $_POST['eingabefehler'];
			print '<p>'.$eingabefehler.'<br>';
		}
		
		#mysql_data_seek($result_archiv,$index);
		#$zeile= mysql_fetch_assoc($result_archiv);
		print 'editid: '.$editid.'<br>';
		#$result_idzeile = mysql_query("SELECT * FROM testarchiv WHERE id = $editid", $db);
		$result_idzeile = mysql_query("SELECT * FROM audio WHERE id = $editid", $db);
	
		#$zeilenarray = mysql_fetch_row($result_idzeile);
		$zeilenarray = mysql_fetch_assoc($result_idzeile);
		print 'zeilenarray count: '.count($zeilenarray).'<br>';
		print_r($zeilenarray);
		print '*<br>';

		print 'ERR<br>';
	
		$aktiv = $_POST['aktiv'];
		print 'aktiv: '.$aktiv.'<br>';
		
		if ($aktiv)
		{
		$checked =  'checked = "checked"';
		}
		else
		{
		$checked = "";
		}
		
		$event = $_POST['event'];
		$werk = $_POST['werk'];
		$satznummer = $_POST['satznummer'];
		$satz = $_POST['satz'];
		$bezeichnung = $_POST['bezeichnung'];
		$register = $_POST['register'];
		print 'register: '.$register.'<br>';
		$stimme1 = chop($_POST['stimme1']);
		$stimme2 = chop($_POST['stimme2']);
		$stimme3 = chop($_POST['stimme3']);
		$anmerkung = chop($_POST['anmerkung']);
	
		# neu
		$sopranstimme1="";
		$sopranstimme2="";
		$sopranstimme3="";
		if (isset($_POST['sopranstimme1']))
		{
			$sopranstimme1 = chop($_POST['sopranstimme1']);
		}
		if (isset($_POST['sopranstimme2']))
		{
			$sopranstimme2 = chop($_POST['sopranstimme2']);
		}
		if (isset($_POST['sopranstimme3']))
		{
		$sopranstimme3 = chop($_POST['sopranstimme3']);
		}
		
		if (isset($_POST['altstimme1'])) {$altstimme1 = chop($_POST['altstimme1']);}else $altstimme1 = "";
		if (isset($_POST['altstimme2'])) {$altstimme2 = chop($_POST['altstimme2']);}else $altstimme2 = "";
		if (isset($_POST['altstimme2'])) {$altstimme3 = chop($_POST['altstimme3']);}else $altstimme3 = "";
	
		if (isset($_POST['tenorstimme1'])) {$tenorstimme1 = chop($_POST['tenorstimme1']);}else $tenorstimme1 = "";
		if (isset($_POST['tenorstimme2'])) {$tenorstimme2 = chop($_POST['tenorstimme2']);}else $tenorstimme2 = "";
		if (isset($_POST['tenorstimme3'])) {$tenorstimme3 = chop($_POST['tenorstimme3']);}else $tenorstimme3 = "";
	
		if (isset($_POST['bassstimme1'])) {$bassstimme1 = chop($_POST['bassstimme1']);}else $bassstimme1 = "";
		if (isset($_POST['bassstimme2'])) {$bassstimme2 = chop($_POST['bassstimme2']);}else $bassstimme2 = "";
		if (isset($_POST['bassstimme3'])) {$bassstimme3 = chop($_POST['bassstimme3']);}else $bassstimme3 = "";

		if (isset($_POST['alle'])) {$alle = chop($_POST['alle']);}else $alle = "";

	
				
		print '<form action="chor_midi_adminconfirm.php" method="post">';
		# new
		print '
		<table>;
			<tr>
				<td><p class="nameneingabe">aktiv:</td>
				<td>';
		if ($aktiv)
		{		
			print '
				    <input type="radio" id="yes" name="neuesaktiv" value="1" checked = "checked"><label for="neuesaktiv" > Ja</label> 
    				<input type="radio" id="no" name="neuesaktiv" value="0"><label for="neuesaktiv"> Nein</label> 
    				';
    	}
    	else
 		{		
			print '
				    <input type="radio" id="yes" name="neuesaktiv" value="1"><label for="neuesaktiv" > Ja</label> 
    				<input type="radio" id="no" name="neuesaktiv" value="0" checked = "checked"><label for="neuesaktiv"> Nein</label> 
    				';
    	}
    	
		print '	</td>
			</tr>
			<tr>
				<td><p class="nameneingabe">event:</td>
				<td><input size="28" maxlength="40" name="neuerevent" value = "'.$event.'"></td>
				
			</tr>
			<tr>
				<td><p class="nameneingabe">Werk:</td>
				<td><input size="28" maxlength="40" name="neueswerk" value = "'.$werk.'"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Satznummer:</td>
				<td><input size="10" maxlength="40" name="neuesatznummer" value = "'.$satznummer.'"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Satz:</td>
				<td><input size="28" maxlength="40" name="neuersatz" value = "'.$satz.'"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Bezeichnung:</td>
				<td><input size="28" maxlength="40" name="neuebezeichnung" value = "'.$bezeichnung.'"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Register:</td>
				<td class = "registereingabe">
			';	 
			$sopranchecked = "";
			$altchecked = "";
			$tenorchecked = "";
			$basschecked = "";
			$allechecked = "";
			if ($register == "sopran")
			{
				$sopranchecked =  ' checked = "checked"';
			}
			elseif ($register == "alt")
			{
				$altchecked =  ' checked = "checked"';
			}
			elseif ($register == "tenor")
			{
				$tenorchecked =  ' checked = "checked"';
			}
			elseif ($register == "bass")
			{
				$basschecked =  ' checked = "checked"';
			}
			elseif ($register == "alle")
			{
				$allechecked =  ' checked = "checked"';
			}

			
			print '
        			<input type="radio" id="sopran" name="neuesregister" value="sopran" '.$sopranchecked.'><label for="sopran" > Sopran</label> 
    				<input type="radio" id="alt" name="neuesregister" value="alt" '.$altchecked.'><label for="alt"> Alt</label> 
					<input type="radio" id="tenor" name="neuesregister" value="tenor" '.$tenorchecked.'><label for="tenor"> Tenor</label> 
					<input type="radio" id="bass" name="neuesregister" value="bass" '.$basschecked.'><label for="bass"> Bass</label> 
					<input type="radio" id="alle" name="neuesregister" value="alle" '.$allechecked.'><label for="alle"> Alle</label> 
  			';	
			print '
				<!--<input size="40" maxlength="40" name="neuesregister"></td> -->
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 1:</td>
				<td><textarea rows="2" cols="25" name="neuestimme1" value = "'.$stimme1.'"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 2:</td>
				<td><textarea rows="2" cols="25" name="neuestimme2" value = "'.$stimme2.'"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 3:</td>
				<td><textarea rows="2" cols="25" name="neuestimme3" value = "'.$stimme3.'"></textarea></td>
			</tr>
			<!--
			<tr>
				<td><p class="nameneingabe">Alle Stimmen:</td>
				<td><textarea rows="2" cols="25" name="neuealle" value = "'.$alle.'"></textarea></td>
			</tr>
			-->
			<tr>
				<td><p class="nameneingabe">Anmerkung:</td>
				<td><textarea rows="2" cols="25" name="neueanmerkung" value = "'.$anmerkung.'"></textarea></td>
			</tr>
		
		</table>';

		print '<table>
			<tr>
			<td><p class="nameneingabe">Stimme</td>
				<td><p class="nameneingabe">Sopran</td>
				<td><p class="nameneingabe">Alt</td>
				<td><p class="nameneingabe">Tenor</td>
				<td><p class="nameneingabe">Bass</td>
				<td><p class="nameneingabe">alle</td>
			</tr>

		
			<tr>
				<td><p class="nameneingabe">Stimme 1:</td>
				<td><textarea rows="2" cols="25" name="neuesopranstimme1" value = "'.$sopranstimme1.'"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuealtstimme1" value = "'.$altstimme1.'"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuetenorstimme1" value = "'.$tenorstimme1.'"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuebassstimme1" value = "'.$bassstimme1.'"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuealle"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 2:</td>
				<td><textarea rows="2" cols="25" name="neuesopranstimme2"value = "'.$sopranstimme2.'"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuealtstimme2" value = "'.$altstimme2.'"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuetenorstimme2" value = "'.$tenorstimme2.'"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuebassstimme2" value = "'.$bassstimme2.'"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 3:</td>
				<td><textarea rows="2" cols="25" name="neuesopranstimme3"value = "'.$sopranstimme3.'"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuealtstimme3 value = "'.$altstimme3.'""></textarea></td>
				<td><textarea rows="2" cols="25" name="neuetenorstimme3" value = "'.$tenorstimme3.'"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuebassstimme3" value = "'.$bassstimme3.'"></textarea></td>
			</tr>
			<!--
			<tr>
				<td><p class="nameneingabe">Alle Stimmen:</td>
				<td><textarea rows="2" cols="25" name="neuealle"></textarea></td>

			</tr>
			--!>
		</table>';
		
	
		# end new 
		print '<input type="hidden" name="index" value ="'.$editid.'">';
		print '	<input type="hidden" name="test" value ="'.$test.'">';
		print '<input type="hidden" name="task" value ="new">';
		print '<br><p class="nameneingabe" ><input type="submit" name="speichern" value="Datensatz neu speichern"></p>';
		print '</form><br>';

	}
	else
	{

		# neuer Datensatz
		print '<h2  style="margin-left:30px">neuer Datensatz</h2>';
		print '<form action="chor_midi_adminconfirm.php" method="post">';
		
		$neuerdatensatzstring = '<h2 style="margin-left:40px">Daten</h2>';
		
		$neuerevent="";
		if (isset($_POST['neuerevent']))
		{
			$neuerevent = $_POST['neuerevent'];
		}
		
		print '
		<table>;
			<tr>
				<td><p class="nameneingabe">aktiv:</td>
				<td>
				    <input type="radio" id="yes" name="neuesaktiv" value="1" checked = "checked"><label for="neuesaktiv" > Ja</label> 
    				<input type="radio" id="no" name="neuesaktiv" value="0"><label for="neuesaktiv"> Nein</label> 
				</td>
				<!--<td><input size="4" maxlength="40" name="neuesaktiv" value = "1"></td> -->
			</tr>
			<tr>
				<td><p class="nameneingabe">event:</td>
				<td><input size="28" maxlength="40" name="neuerevent" value = "'.$neuerevent.'"></td>
				
			</tr>
			<tr>
				<td><p class="nameneingabe">Werk:</td>
				<td><input size="28" maxlength="40" name="neueswerk"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Satznummer:</td>
				<td><input size="10" maxlength="40" name="neuesatznummer"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Satz:</td>
				<td><input size="28" maxlength="40" name="neuersatz"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Bezeichnung:</td>
				<td><input size="28" maxlength="40" name="neuebezeichnung"></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Register:</td>
				<td class = "registereingabe">
				 
        			<input type="radio" id="sopran" name="neuesregister" value="sopran" checked = "checked"><label for="sopran" > Sopran</label> 
    				<input type="radio" id="alt" name="neuesregister" value="alt"><label for="alt"> Alt</label> 
					<input type="radio" id="tenor" name="neuesregister" value="tenor"><label for="tenor"> Tenor</label> 
					<input type="radio" id="bass" name="neuesregister" value="bass"><label for="bass"> Bass</label> 
					<input type="radio" id="alle" name="neuesregister" value="alle"><label for="alle"> Alle</label> 
  				

				<!--<input size="40" maxlength="40" name="neuesregister"></td> -->
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 1:</td>
				<td><textarea rows="2" cols="25" name="neuestimme1"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 2:</td>
				<td><textarea rows="2" cols="25" name="neuestimme2"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 3:</td>
				<td><textarea rows="2" cols="25" name="neuestimme3"></textarea></td>
			</tr>
			<!--
			<tr>
				<td><p class="nameneingabe">Alle Stimmen:</td>
				<td><textarea rows="2" cols="25" name="neuealle"></textarea></td>
			</tr>
			--!>
			<tr>
				<td><p class="nameneingabe">Anmerkung:</td>
				<td><textarea rows="2" cols="25" name="neueanmerkung"></textarea></td>
			</tr>
		
		</table>';

		print '<table>
			<tr>
			<td><p class="nameneingabe">Stimme</td>
				<td><p class="nameneingabe">Sopran</td>
				<td><p class="nameneingabe">Alt</td>
				<td><p class="nameneingabe">Tenor</td>
				<td><p class="nameneingabe">Bass</td>
				<td><p class="nameneingabe">alle</td>
			</tr>

		
			<tr>
				<td><p class="nameneingabe">Stimme 1:</td>
				<td><textarea rows="2" cols="25" name="neuesopranstimme1"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuealtstimme1"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuetenorstimme1"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuebassstimme1"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuealle"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 2:</td>
				<td><textarea rows="2" cols="25" name="neuesopranstimme2"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuealtstimme2"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuetenorstimme2"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuebassstimme2"></textarea></td>
			</tr>
			<tr>
				<td><p class="nameneingabe">Stimme 3:</td>
				<td><textarea rows="2" cols="25" name="neuesopranstimme3"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuealtstimme3"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuetenorstimme3"></textarea></td>
				<td><textarea rows="2" cols="25" name="neuebassstimme3"></textarea></td>
			</tr>
		</table>';

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
	$tableheaderstring .= '<th class="text breite200 " >Stimme 1</th>';
	$tableheaderstring .= '<th class="text breite100 " >Stimme 2</th>';
	$tableheaderstring .= '<th class="text breite100 " >Stimme 3</th>';

	
	$tableheaderstring .= '	</tr>';

	print $tableheaderstring;

	print '<form action="" method="post">';
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

	print '<form action="" ><input type="submit" class="links40" value="zurück zur Startseite" name="textfile"></form>';
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

