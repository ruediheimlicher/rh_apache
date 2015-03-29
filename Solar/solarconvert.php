		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script language="javascript" type="text/javascript" src="../flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="../flot/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="datumpicker.js">
    var javindex = 3;
    </script>
    
<link href="solarstyle.css" rel="stylesheet" type="text/css" />




<title>Solardaten</title>
 </head>

<body>

		
		$AlarmPfad="../Data/AlarmDaten.txt";
		<p class = "kalender">Daten holen:</p>
		<form action = "../cgi-bin/solarconvert.pl">
		<table >
			<tr>
				<td>
					<select name = "monat" > 
					<?
					# Monat-Pop setzen, heutemonat selektieren
					$monatarray= array("Jan","Feb","Mrz","Apr","Mai","Juni","Juli","Aug","Sept","Okt","Nov","Dez");
					for ($mon=0;$mon<12;$mon++)
					{
							{
								print '<option value = '.($mon+1).'>'.$monatarray[$mon].'</option>';
							}
					}
					
					?>
					</select>
				</td>
				
				<td>
					<select  name = "jahr" > 
					<?
					# Jahr-Pop setzen, heutejahr selektieren
					$jahrarray= array("2009","2010","2011","2012","2013","2014","2015","2016","2017","2018");

					for ($jahr=0;$jahr<count($jahrarray);$jahr++)
					{
						
							
								print '<option value = '.$jahrarray[$jahr].'>'.$jahrarray[$jahr].'</option>';
							
					}
					
					?>
					
					</select>
				</td>

				<td>
					<?
					print '<input type = "hidden" value = "123" name = "datawert">';
					#print '<input type="submit" name = "go" value="go" onClick="datumfehler('.$postjahr.','.$heutejahr.')"/>';
					print '<input  type="submit" name = "go" value="go" />';
					#href='session_2.php?sid='".session_id()."'>Go to the next page.</a>\n";

					?>
				</td>
		
			</tr>
		</table>
</form>

<form method = "POST" action = ../cgi-bin/solarconvert.pl\n">
<input  type="hidden"  value="send" />
	<input  type="submit"  value="send" />
</form>
</body>
</html>

