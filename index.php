<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">


<head>

  	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  	<title>Ruedi Heimlicher</title>
	<meta name="keywords" content="solaranlage,strommessung,homecentral,i2c,atmega" />

  <link href="indexstyle.css" rel="stylesheet" type="text/css" />

<!-- http://www.inspire-world.de/boarduploads/Textanzeige-rein-raus.html -->
<script language="JavaScript" type="text/javascript">
<!--
function show1() 
{
if(document.getElementById) document.getElementById("ve1").style.display = "inline";
if(document.getElementById) document.getElementById("da1").style.display = "none";
if(document.getElementById) document.getElementById("nd1").style.display = "inline";   
}

function weg1() 
{
if(document.getElementById) document.getElementById("ve1").style.display = "none";
if(document.getElementById) document.getElementById("da1").style.display = "inline";
if(document.getElementById) document.getElementById("nd1").style.display = "none";
}

//-->
</script>

</head>

<body>


<div class="titelabschnitt">

  <?php
  	$teaserhandle = fopen ("Texte/indexteaser.txt", "r");
	while (!feof($teaserhandle)) 
	{
    	$teaserbuffer = fgets($teaserhandle);
    	print '<p class="teaser" >'.$teaserbuffer.'</p>';
	}
	fclose ($teaserhandle);
	

 ?>
</div>

<div class="menuabschnitt">
	<ul id="main-nav">
    	<li><a href="homecentral/homecentral.php">HomeCentral</a></li>
    	<li><a href="Solar/Solar.php">Solar</a></li>
 <!--   	<li><a href="Solar/solarconvert.php">Solarconvert</a></li> -->
    	<li><a href="Strom/strom.php">Strom</a></li>
    	<li><a href="Informatik/Blatt_Informatik.htm">Informatik</a></li>
    	<li><a href="fotologin.php">Fotoalbum</a></li>
    	<li><a href="kirchenchor/chor.php">Chor</a></li>
    	<li><a href="kirchenchor/chor_login.php">admin</a></li>
    	<li><a href="usa/usa_sql_old.php">USA</a></li>

	</ul>



</div>

<div class="adminabschnitt">
<!--
	<ul class ="cal-nav">
    	<li class ="cal-nav"><a href="HomeCentral/HomeCentral.html">H</a></li>
    	<li class ="cal-nav"><a href="Solar/Solar.php">S</a></li>
    	<li class ="cal-nav"><a href="Strom/strom.php">O</a></li>
 	</ul>
-->
   	<?php
    //Gets the IP address
 	$ip = getenv("REMOTE_ADDR") ; 
 	print '<p class = "kalender">IP :<br>'. $ip.'</p>'; 
	?>

</div>

<div class="abschnitt1">

	<div style="position:absolute; top:10px; right:10px;">
		<img src="Bilder/Haus_web.jpg" width="250" height=auto alt="Falkenstr. 20 8630 Rüti">
	</div>

    <div style="position:absolute; top:10px; left:10px;">
      <p class="all14">Ruedi Heimlicher<br />Falkenstrasse 20<br />8630 R&uuml;ti</p>
      


<p class="all14"><a id="da1" style="display:inline" href="javascript:show1()" >Karte anzeigen</a><a id="nd1" style="display:none" href="javascript:weg1()">Karte ausblenden</a></p>
<div id="ve1" style="display:none">
	
	<script type="text/javascript" src="http://map.search.ch/api/map.js"></script>
	<script type="text/javascript">new SearchChMap({center:"rueti/falkenstr.20",x:"-10m",y:"-39m",zoom:0.5,type:"aerial"});</script>
	<div id="mapcontainer" style="width:560px;height:420px;border:1px solid #888">
		<noscript>
			<div>
				<a target="_top" href="http://map.search.ch/rueti/falkenstr.20?x=-10m&amp;y=-39m&amp;z=1024&amp;b=high">
				<img src="http://map.search.ch/chmap.de.jpg?layer=bg,fg,copy,ruler,circle&amp;zd=0.5.5&amp; x=-10m&amp;y=-39m&amp;w=460&amp;h=300&amp;poi=all&amp; base=8630+R%C3%BCti%2FFalkenstr.+20"
     			style="border:0" id="mapimg" alt="Falkenstr. 20, Rüti" /></a>
			</div>
		</noscript>

	</div>
</div>  



		<p class = "kalender">Daten holen:</p>
		<table >
			<tr>
				<td>
					<select onchange="this.form.monat.value = this.value"> 
					<?php
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
					<select onchange="this.form.jahr.value = this.value"> 
					<?php
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
					<?php
					#print '<input type="submit" name = "go" value="go" onClick="datumfehler('.$postjahr.','.$heutejahr.')"/>';
					print '<input type="submit" name = "go" value="go" />';
					?>
				</td>
		
			</tr>
		</table>
<a href="http://info.flagcounter.com/iFVP"><img src="http://s01.flagcounter.com/count/iFVP/bg_FFFFFF/txt_000000/border_CCCCCC/columns_3/maxflags_20/viewers_0/labels_0/pageviews_0/flags_0/" alt="Flagg Counter" ></img></a>
</div>
</div>

<?php
#print'<IMG SRC="http://cgi.mhs.ch/cgi-bin/counter/counter.exe?link=%countID%&style=led">';
#print "fehler counter: ".counter.err;

?>
</body>

</html>

