<?

#include "../sources/libTeeChart.php";
#include "kalender.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--    <script language="javascript" type="text/javascript" src="../flot/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="../flot/jquery.flot.js"></script>-->
<!--    
<META HTTP-EQUIV="refresh" content="10; URL=http://www.ruediheimlicher.ch/Strom/strom.php/">

<script type="text/javascript" language="javascript" src="niftyplayer.js"></script>
-->
<!--
<script type="text/javascript" src="Player/audio-player.js"></script>  
<script type="text/javascript">  
            AudioPlayer.setup("Player/player.swf", 
            {  
            file: "/uploads/example.mp4",
        height: 360,
        image: "/uploads/example.jpg",
        width: 640
               
            });  
</script>  
-->
<!--
<script type="text/javascript" src="http://mediaplayer.yahoo.com/js"></script>
-->
<!--
<script language="javascript" type="text/javascript" src="datumpicker.js">
</script>
-->
	
 <link type="text/css" href="Player/jplayer//blue.monday/jplayer.blue.monday.css" rel="stylesheet" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js"></script>

  <script type="text/javascript" src="Player/jplayer/jquery.jplayer.min.js"></script>
  

	

<link href="chor.css" rel="stylesheet" type="text/css" />

<title>Kirchenchor</title>
<style type="text/css">

body 
{
    	font: 100% Verdana, Arial, Helvetica, sans-serif;
    	background: #666666;
    	/*eight: 850px;*/
		margin: 0; /* Es empfiehlt sich, margin (Rand) und padding (Auffüllung) des Body-Elements auf 0 einzustellen, um unterschiedlichen Browser-Standardeinstellungen Rechnung zu tragen. */
    	padding: 0;
    	text-align: left; /* Hierdurch wird der Container in IE 5*-Browsern zentriert. Dem Text wird dann im #container-Selektor die Standardausrichtung left (links) zugewiesen. */
    	color: #0000000;
		font-size:12px;
    }
	
	p {
		font-size:10px;
		margin:2px;
		padding:2px;
		border:0px solid;
		}
	p.liste
	{
		font-size:10px;
		margin:1px;
		padding:0px;
		border:0px solid;
	
	}
	h1 {
		font-size:20px;
		font-weight:bold;
		margin:4px;

		}
		
    .basic #container {
     	width: 100%;  /* Mit einer Breite, die 20 Pixel unter der vollen Breite von 800 Pixel liegt, können Sie dem Browser-Chrome Rechnung tragen und gleichzeitig eine horizontale Bildlaufleiste vermeiden. */
    	background: #0FFFFF;
    	margin: 20 auto; /* Durch automatische Ränder (in Verbindung mit einer Breite) wird die Seite zentriert. */
    	border: 0px solid #000000;
    	text-align: left; /* Hierdurch wird die Einstellung text-align: center im Body-Element überschrieben. */
  		height: 650px;
    }
    .basic #mainContent {
    	padding: 0 30px; /* padding (Auffüllung) bezeichnet den Innenabstand und margin (Rand) den Außenabstand der div -Box. */
    	
	}
		.Stil3 {font-size: 100%}
		.Stil4 {font-size: 10%}
		
	table {
		font-size:12px;
		color:#FF0000;
		margin:1px;

		}

	
.tagverlaufabschnitt
{
	position:relative; 
	left:10px;
	top:4px;
	bottom:	10px;
	width:1000px;
	height:280px;
	/*background-color:#d0e; */
}

.tagverlaufdiagrammabschnitt
{
	position:relative; 
	top:	80px;
	left:	10px;
	bottom:	4px;
	border:0px solid black;
	width:1000px;
	height:380px;
	/*background-color:#abe; */
	margin-bottom:10px;
	
	overflow:auto;
}

.tagverlaufdiagrammabschnitthist
{
	position:relative; 
	top:	80px;
	left:	10px;
	bottom:	4px;
	border:0px solid black;
	width:1000px;
	height:380px;
	/*background-color:#abe; */
	margin-bottom:10px;
	
	overflow:auto;
}


	
    </style>
</head>


<body class="basic">

<script type="text/javascript">
function submitform(registername)
{
document.write("Register");
	document.write(registername );
  
}
</script>

<?
#POST abfragen


print '<div id="container">';
#print_r($_POST);
print_r($_GET);

$pfadfehler=0;
$audiopfad = "../Data/kirchenchor";
$register = $_GET['register'];

$stueck = "";
$event = "";
$playpfad = "";
if ($_GET['playpfad'])
{
	$playpfad = $_GET['playpfad'];
	$pfadfehler = 1;
}
else
{
	if (count($_GET)>1)
	{
		if ($_GET['event'])
		{
			#print '<br>event da ';
			$event = $_GET['event'];
			$audiopfad = $audiopfad.'/'.$_GET['event'];
		}
		else
		{
			print '<br>event nicht da ';
			$pfadfehler=1;
		}
		 if (strlen($_GET['stueck']))
		{
			#print '<br>stueck da ';
			$stueck = $_GET['stueck'];
			$audiopfad = $audiopfad.'/'.$_GET['stueck'].'/';
		}
		else
		{
			print '<br>stueck nicht da ';
			$pfadfehler=1;
		}
	
	}
	else
	{
	$pfadfehler = 1;
	}
}
#print '<br>audiopfad: '.$audiopfad.' pfadfehler: '.$pfadfehler.'<br>';
$task = $_POST['task'];	
#print 'task: '.$task.'<br>';

$index = $_POST['index'];	
$name = $_POST['name'];
$task="";
if (isset($_POST['task']))
{
	$task = $_POST['task'];	
}

$registernamenarray = array("Sopran","Alt","Tenor","Bass");
?>
      <div id="mainContent">
      
        <h1> Kirchenchor Dürnten</h1>
   
		<p>
<!--http://stackoverflow.com/questions/6791238/send-post-request-on-click-of-href-in-jsp     
<form name="submitForm" method="POST" action="">
    <input type="hidden" name="param1" value="Hans">
    <A HREF="javascript:document.submitForm.submit()">Click Me</A>
</form>
<form name="submitForm2" method="POST" action="">
    <input type="hidden" name="param2" value="Fritz">
    <A HREF="javascript:document.submitForm2.submit()">Click Me Too</A>
</form>
-->

<!--
		<div class="navmenuabschnitt">
		<?
		print '<p>navmenuabschnitt</p>';
			print '<ul class="main-nav">';
			print '	<li><a href="../index.php">Home</a></li>';
				
			print '	<li><a href="http://www.refduernten.ch/content/e14561/e12463/e15420/e15549/index_ger.html">zurück</a></li>';
			
			print '</ul>';
	
		?>
		</div> -->
		<!-- navmenuabschnitt --!>
</p>
<?
	print '	<div class="registerwahlabschnitt"> ';
	#print '<p>registerwahlabschnitt</p>';
	print '<ul class="register-nav">';
	for ($i=0;$i< count($registernamenarray);$i++)
	{
		if ($register == $registernamenarray[$i])
		{
			print '	<li class = "register-nav"><a href="chor.php?register='.$registernamenarray[$i].'">'.$registernamenarray[$i].'</a></li>';
		}
		else
		{
			print '	<li><a href="chor.php?register='.$registernamenarray[$i].'">'.$registernamenarray[$i].'</a></li>';
		}
	} # for i

	#print '	<li><a href="../index.php">Home</a></li>';				
	print '	<li><a href="http://www.refduernten.ch/content/e14561/e12463/e15420/e15549/index_ger.html">zurück  </a></li>';
	print ' </ul>';
	
	#print '<br><p>register: '.$register.'</p>';


	print '</div> <!-- registerwahlabschnitt --!>';
?>
<!--http://forums.devarticles.com/javascript-development-22/using-javascript-for-form-submit-3451.html
<form name="change_record" action="record_update.php" method="post" autocomplete="off">

<a name="edit" href="javascript:void(1);" onClick="submitForm('Edit');">Edit</a>
<a name="delete" href="javascript:void(1);" onClick="submitForm('Delete');">Delete</a>

-->

<script language="Javascript">
  function procLink(action) 
  {
    document.foo.doThis.value = action
    document.foo.doThis.submit()
}
</script>
<!--
<form name="foo">
<input type="hidden" name="doThis" value="">
<a name="edit" href="chor.php" onClick="procLink('Edit');">Edit</a>

</form>
-->
<? 	
	
#print ' <div class = "verzeichnisabschnitt">';
#print '<p>verzeichnisabschnitt</p>';
# Audiodaten holen 
$audiofilearray = array();
$eventarray = array();
$stueckarray = array();



if ($handle = opendir('../Data/kirchenchor/')) 
{
	#echo "Directory handle: $handle\n";
	#echo "Files:<br>\n";

	/* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */

	#print '<ul class="main-nav">';


	while ($handle && (false !== ($file = readdir($handle))) ) 
	{
	#$regex ='/[^a-zA-ZäöüÄÖÜß0-9]/';
	$regex ='/^\.{1,2}/'; # Punkte am Anfang entfernen

	$filesauber = preg_replace($regex,'',$file);
	if (strlen($filesauber))
	{
		if (is_dir('../Data/kirchenchor/'.$filesauber.'/'))
		{
			#print  'event: *'. $filesauber.'*<br>';
			$position = strpos($filesauber,"_");
			#print  'pos: '.$position.'*<br>';

			$eventdic = array("event"=>  $filesauber);
	
			if ($stueckhandle = opendir('../Data/kirchenchor/'.$filesauber.'/'))
			{
			$stueckarray = array();
				while ($stueckhandle && (false !== ($stueckfile = readdir($stueckhandle))) )
				{
					$stuecksauber =  preg_replace($regex,'',$stueckfile);
					if (strlen($stuecksauber))
					{
			
						#print  '---stueck: *'. $stuecksauber.'*<br>';
						$position = strpos($stuecksauber,"_");
						#print  'pos: "'.$position.'"*<br>';
						$stueckarray[] = $stuecksauber;
						#array_push($eventdic,$stuecksauber);
					}
				}
				$eventdic["stuecke"] = $stueckarray;
				#array_push($eventdic,$stueckarray);
			}
			#print 'eventdic: ';
			#print_r($eventdic);
			print '<br>';
			$eventarray[] = $eventdic;
		}
		else
		{
			#print  'file: *'. $filesauber.'*<br>';
			#in array einsetzen
			$audiofilearray[] = $file;
			#print '<li><a href="'.$filesauber.'">'.$filesauber.'</a></li>';
		}
	}
	# macht dasselbe
	if ( ($file != "." && $file != ".."))
	{
		#print 'l: '.strlen($file).' '. $file.'  '. $filesauber.'<br>';
		#$audiofilearray[] = $file;
	}
	}
	#print '</div>';

print '<div class = "fileabschnitt">';	
	#print_r($eventarray[0]);
	#print '<p>fileabschnitt</p>';
	#print '<ul class="main-nav">';
	print '<h2 class = "menutitel ">Event</h2>';
	foreach($eventarray as $event )
	{
		#print '<li><a href="../Data/kirchenchor/'.$event['event'].'">'.$event['event'].'</a></li>';
		#print '	<li><a href="../index.php">go Home</a></li>';
		print '<div class = "eventabschnitt">';	
		print '<h2 class = "eventtitel ">'.$event['event'].'</h2>';
		print '<ul class="main-nav">';
		foreach($event["stuecke"] as $einzelstueck )
		{
			print '<li><a href="chor.php?register='.$register.'&event='.$event['event'].'&stueck='.$einzelstueck.'">'.$einzelstueck.'</a></li>';
		}

		print '</ul>';
		print '</div>';
	}

	$ignore = array('.','..','cgi-bin','.DS_Store');
	$audiofileda=0;
	$audiopfadarray = array(); # Pfade der Audiofiles
	if (($pfadfehler == 0)&&($audiohandle = opendir($audiopfad)))
	{
		#print '<p>audio da: '.$audiopfad.'</p>';
		
		while ($audiohandle && (false !== ($audiofile = readdir($audiohandle))) ) 
		{
			if (!($audiofile === '.DS_Store'))
			{
				#$regex ='/[^a-zA-ZäöüÄÖÜß0-9]/';
				$regex ='/^\.{1,2}/'; # Punkte am Anfang entfernen

				$audiofilesauber = preg_replace($regex,'',$audiofile);
		
				if (strlen($audiofilesauber))
				{
					$init = strtolower($register);
					$init = '_'.$init[0];
					if (stripos($audiofilesauber, $init))
					{
						$audiofileda =1;
					#print '<p>init: '.$init.' audiofilesauber da: '.$audiopfad.$audiofilesauber.'</p>';
					$audiofilearray[] = $audiofilesauber;
					}
				}
			}
		}#while
	closedir($audiohandle);
	}
	else
	{
		#print '<p>audio nicht da</p>';
	}

	

	#print '</ul>';
	#print_r($audiofilearray);
	closedir($handle);
}
# End Audiodaten holen

print '</div>'; # fileabschnitt

print '<div class = "audioabschnitt">	';
	#print ' <p>audioabschnitt</p>	';
	
	if ($audiofileda)
	{
		$index=0;
		print '	<h2 class = "audiotitel ">'.$stueck.'</h2>';
		
		#print '</div>';

		
		
		
		foreach($audiofilearray as $stimme )
		{
	
			#print '<br><p> stimme: '.$stimme.' stück: '.$stueck.' pfad: :'.$audiopfad.$stimme.'</p>';
			#print '<li><a href="../Data/kirchenchor/'.$stimme.'">'.$stimme.'</a></li>';
			#print '	<li><a href="../index.php">Home</a></li>';

			print '	<div class = "stimmeabschnitt">';
				if (stripos($stimme, "_mix"))
				{
					print '	<p class = "stimmetitel">'.$register.' mix</p>';
				}
				else
				{
					print '	<p class = "stimmetitel">'.$register.'</p>';
				}
				
				print '	<embed type="application/x-shockwave-flash" flashvars="audioUrl='.$audiopfad.$stimme.'" src="http://www.google.com/reader/ui/3523697345-audio-player.swf" width="500" height="27" allowscriptaccess="never"  quality="best" ></embed>';
				
			print '	</div>'; # stimmeabschnitt
		}

}# if audiofileda

#print '	</div>'; # audioabschnitt



print '</div>'; # <!-- audioabschnitt -->
?>


</div>


<?
?>



 	
 	</div><!-- end #mainContent -->
    <!-- end #container --></div>
    </body>
</html>
