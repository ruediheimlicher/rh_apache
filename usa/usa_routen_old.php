<?php

/* verbinden mit db */	

$db = include "usa_bank.php";



if (isset($_POST['uploadok']))

{

	$upda=1;

	#ßheader("location:chor_upload.php?");

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type"content="text/html;charset=UTF-8"/>



<link href="usa.css"rel="stylesheet"type="text/css"/>

</head>

<body class="basic">

<?php

print '<div id="admin">';

print '<div id="adminContent">';

print '<h2 class="eventtitel ">USA Routensammlung</h2>';



#POST abfragen

#print_r($_POST);

#print_r($_GET);

#print '<br>';



$suchrouten=0;

if (isset($_POST['routens']) && strlen($_POST['routens'][0]))

{

	$suchrouten = $_POST['routens'][0];

	#print'suchrouten: '. $_POST['routens'][0].'<br>';

}

elseif (isset($_POST['suchrouten']))

{

	$suchrouten = $_POST['suchrouten'];

}



$suchname=0;

if (isset($_POST['names']) && strlen($_POST['names'][0]))

{

	$suchname = $_POST['names'][0];

	#print'suchname: '. $_POST['names'][0].'<br>';

}

elseif (isset($_POST['suchname']))

{

	$suchname = $_POST['suchname'];

}

$editrow=0;

$editid=-1;

if (isset($_POST['editrow']) && isset($_POST['editid']))

{

	$suchname = $_POST['suchname'];

	$editid = $_POST['editid'];

	#print 'editid: '.$editid.'<br>';

}



$routenid=-1;

if (isset($_POST['routensuchen']) && isset($_POST['routenid']))

{

	$suchrouten = $_POST['suchrouten'];

	$routenid = $_POST['routenid'];

	#print 'routenid: '.$routenid.'<br>';

}



#print '<h2 class="audiotitel ">suchen</h2>';



$result_routen = mysql_query("SELECT * FROM routen ", $db)or die(print '<p  >Beim Suchen nach routen ist ein Fehler passiert: '.mysql_error().'</p>');

if (mysql_error())

{

	print 'select * error:';

	print mysql_error();

	print '<br>';

}



$task = "show";

if (isset($_POST['task']) )

{

	$task = $_POST['task'];

	#print_r($_POST);

	#print '<br>';



}

#print 'task: '.$task.'<br>';



print '<form method="POST" action="usa_sql_old.php">';

print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:24pt; margin-left:10px" value="> Campground" >';

print '</form>';



# routendaten suchen



$routenarray = array();

$alldatenarray = array();

# array der ip gleich wie $ip

while ($routen = mysql_fetch_array($result_routen) )

{

	$alldatenarray[] = $routen;

	#print_r($routen);

	#print '<br>';

	$cgzeile = $routen['ort_a'];

	

	#print '* ort_a:'.$cgzeile;

	#$cgzeile = $routen['ort_b'];

	#print ' ort_b:'.$cgzeile;

	#print ' url:'.$routen['url'].'<br>';

	#print '<br>';



	trim($cgzeile);

	

	#print ' zeile:'.$routen['routen'].'<br>';

	if (strlen($cgzeile)&&!(in_array(trim($cgzeile),$routenarray)) )

	{

		$routenarray[]=trim($cgzeile);

		#print '*** routen:*'.$routen['routen'].'* l: '.strlen($cgzeile).'<br>';

	}

	

}



asort($routenarray,SORT_STRING);

print '<form method="POST" action="">';

print ' <select size="1" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:18pt; margin-left:10px" name="routens[] ">';



foreach($routenarray as $routen)

{

	print 'routen:'.$routen.'<br>';

	if ($routen == $suchrouten)

	{

		print '<option value="'.$routen.'" selected>'.$routen.'</option>';

	}

	else

	{

		print '<option  value="'.$routen.'">'.$routen.'</option>';

	}

}



print ' </select>';

print ' <input type="submit" style="font-family:Tahoma,Verdana,Arial,Sans-serif; color:#0000C0; font-size:24pt; margin-left:10px" value="route suchen" >';

print '</form>';





$result_routendaten = mysql_query("SELECT * FROM routen WHERE ort_a = '$suchrouten'", $db)or die(print '<p  >Beim Suchen nach routendaten ist ein Fehler passiert: '.mysql_error().'</p>');



if (mysql_error())

{

	print 'SELECT routendaten error:';

	print mysql_error();

	print '<br>';

}







$routendatenarray = array();

while ($routendaten = mysql_fetch_array($result_routendaten) )

{

	$routenzeilendic["nummer"] = $routendaten['nummer'];

	$routenzeilendic["ort_a"] = $routendaten['ort_a'];

	$routenzeilendic["ort_b"] = $routendaten['ort_b'];



	$routenzeilendic["url"] = $routendaten['url'];

	$routenzeilendic["distanz"] = $routendaten['distanz'];

	$routenzeilendic["id"] = $routendaten['id'];



	



	#print_r($routendaten[0]);

	#print '<br>';

	#print 'ort_a: '.$routendaten['ort_a'].' ort_b: '.$routendaten['ort_b'].' url: '.$routendaten['url'].' Distanz: '.$routendaten['distanz'].'<br>';

	$routendatenarray[] = $routenzeilendic;

	print '<br>';

}



#if ($routendatenarray) # Daten vorhanden

{

	#	

		print '<td class = "drucktabellecenter" width = "50px"><form action ="https://maps.google.com/maps" method = "get" target="_blank" name = "saddr" value="Saratoga+Springs,+New+York,+USA" name = "daddr" value = "43.042841,-73.7143301+to:42.821858,-73.515541+to:Adams,+Massachusetts,+USA" >';



	



		print '<table class = "routetabelle">';

		print '<tr height = 24px>';

		print '<th class = "drucktabellecenter" width = "30px">nummer</td>';

		print '<th class = "text" width = "150px">ort_a</td>';

		print '<th class = "text" width = "150px">ort_b</td>';

		#print '<th class = "text" width = "100px">url</td>';

		print '<th class = "text" width = "60px">distanz</td>';

		print '<th class = "drucktabellecenter" width = "60px">';

		print '</td>';

	

		if ($task == "all")		

		{

		

			foreach($alldatenarray as $allroutendatenzeile)

			{

				print '<tr class = "drucktabelle">';

				print '<td class = "drucktabelle">'.$allroutendatenzeile['nummer'].'</td>';



				print '<td class = "drucktabelle">'.$allroutendatenzeile['ort_a'].'</td>';

				print '<td class = "drucktabelle">'.$allroutendatenzeile['ort_b'].'</td>';

				#print '<td class = "drucktabelle">'.$routendatenzeile['ort'].'</td>';

				print '<td class = "drucktabellecenter">'.$allroutendatenzeile['url'].'</td>';

				#print '<td class = "drucktabellecenter" width = "50px"><form action = "'.$allroutendatenzeile['url'].'"; name = "routenwahl" method = "GET"target="_blank">';

				#

				print '<td class = "drucktabellecenter" width = "50px"><form action="http://maps.google.com/maps" method="get" target="_blank"> <input type="hidden" name="daddr" value="'.$allroutendatenzeile['url'].'" />';



				#print '	<input type="hidden" name="routensuchen" value ="1">';

				#print '	<input type="hidden" name="suchrouten" value ='.$suchrouten.'>';

				#print '	<input type="hidden" name="routenid" value ='.$routendatenzeile['id'].'>';

				print '<input type="submit" class="links40" name="routenwahl" value=">>"></form></td>';



				print '</tr>';



			}# foreach

		



	}	

	elseif ($routendatenarray) # Daten vorhanden

	{		



		print '</tr>';

		foreach($routendatenarray as $routendatenzeile)

		{

			print '<tr class = "drucktabelle">';

			print '<td class = "drucktabelle">'.$routendatenzeile['nummer'].'</td>';



			print '<td class = "drucktabelle">'.$routendatenzeile['ort_a'].'</td>';

			print '<td class = "drucktabelle">'.$routendatenzeile['ort_b'].'</td>';

			#print '<td class = "drucktabelle">'.$routendatenzeile['ort'].'</td>';

			print '<td class = "drucktabellecenter">'.$routendatenzeile['distanz'].'</td>';

		

			$teile = explode("&",$routendatenzeile['url']);

			$kopf = explode("?",$teile[0]); # 0: https://maps.google.de/maps  1: saddr

			$kopfaddr = explode("=",$kopf[1]);

			#print '<br>';

			$saddr = $kopfaddr[1];

			#print 'saddr: '.$saddr.'<br>';

			$saddr = str_replace("+"," ",$saddr);

			#print 'saddr nach: '.$saddr.'<br>';

		

			$daddr =  explode("=",$teile[1]);

			$daddr = $daddr[1];

			#print 'daddr: '.$daddr.'<br>';

			$daddr = str_replace("+"," ",$daddr);

			#print 'daddr nach: '.$daddr.'<br>';

		

			print '<td class = "drucktabellecenter" width = "50px">';

			print'	<form action="http://maps.google.com/maps" method="get" target="_blank">';

					

		



			print '<input type="hidden" name="saddr" value = "'.$saddr.'" />';

			print '<input type="hidden" name="daddr" value="'.$daddr.'" />';

			print '<input type="submit" class="links40" name="routenwahl" value=">>"></form></td>';

		



		

			#print '<td class = "drucktabellecenter" width = "50px"><form action="https://maps.google.com/maps" method="get" target="_blank"> <input type="hidden" name="daddr" value="'.$routendatenzeile['url'].'" />';

			#print '<td class = "drucktabellecenter" width = "50px"><form action="'.$routendatenzeile['url'].'" method="get" target="_blank"> ';



				#print '<td class = "drucktabellecenter" width = "50px"><form action = "http://maps.google.de/maps" name = "saddr" value="'.$routendatenzeile['url'].' " method = "get" target="_blank">';

			#print '<td class = "drucktabellecenter" width = "50px"><form action = '.$routendatenzeile['url'].' " method = "get" target="_blank">';

		

		

			#print '<td class = "drucktabellecenter" width = "50px"><form action ="https://maps.google.com/maps" method = "get" target="_blank" name = "saddr" value="Saratoga+Springs,+New+York,+USA" name = "daddr" value = "43.042841,-73.7143301+to:42.821858,-73.515541+to:Adams,+Massachusetts,+USA" >';

		

		

		

			#print '	<input type="hidden" name="routensuchen" value ="1">';

			#print '	<input type="hidden" name="suchrouten" value ='.$suchrouten.'>';

			#print '	<input type="hidden" name="routenid" value ='.$routendatenzeile['id'].'>';

		



			print '</tr>';



		}# foreach

	

		print '</table><br>';





		print '</div>'; # routenabschnitt

	}

}# if routendatenarray







if ($task == "show")

	{}





if ($task == "new")

{

print '<div class = "editabschnitt">';

	# neuer Datensatz

	print '<h2 class="untertitel">neuer Datensatz</h2>';

	print '<form action="" method="post">';

	

	print '<table class = "eingabetabelle">';

	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">State:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="state"></td>';

	print '</tr>';

	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">routen:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="routen"></td>';

	print '</tr>';

	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">name:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="name"></td>';

	print '</tr>';

	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">mail:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="mail"></td>';

	print '</tr>';

	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">url:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="url"></td>';

	print '</tr>';



	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">tel:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="tel"></td>';

	print '</tr>';

	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">tel free:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="tel_free"></td>';

	print '</tr>';

	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">ort:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="ort"></td>';

	print '</tr>';

		print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">rate:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="rate"></td>';

	print '</tr>';



	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">adresse:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="adresse"></td>';	

	print '</tr>';



	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">Anmerkung:</td>';

	print '<td class = "drucktabelle" ><textarea rows="2" cols="45" name="anmerkung"></textarea></p></td>';

	print '</tr>';



	print '<tr height = 24px>';

	print '<td class = "drucktabelle" width = "100px">gmap:</td>';

	print '<td class = "drucktabelle" ><input size="110" maxlength="200" name="gmap"></td>';

	print '</tr>';



	print '</table>';



	print ' <input type="hidden"name="task"value="save"/>';

	print ' <input type="submit"name="savedata" value="Daten sichern"/>';

	print '</form>';





print '<form action=""method="POST">';

print ' <input type="hidden"name="task"value="show"/>';

print ' <input type="submit"name="back"value="zurück"/>';

print '</form>';







print '<br>';



print '</div>'; # editabschnitt

}



/*

print '<div class = "archivtababschnitt">';



print '<form action=""method="POST">';

print ' <input type="hidden"name="task"value="new"/>';

print ' <input type="submit"name="back"value="neue Daten*"/>';

print '</form>';





# Tabelle laden





print '</div>'; # archivtababschnitt

*/



# change end













print '</div>';	# adminContent

print '</div>';	# admin

?>

    </body>

</html>

