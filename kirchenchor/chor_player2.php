<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Player</title>
 <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>

<link type="text/css" href="../skin/pink.flag/scss/jplayer.pink.flag.css" rel="stylesheet" />
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script type="text/javascript" src="../js/jquery.jplayer.min.js"></script>

</head>

<body >
<h2 style="margin-left:10px">jPlayer</h2>


<?php
// vom KG-Netz
//$playerwerk = "http://www.refduernten.ch/www.zh.ref.ch/gemeinden/duernten/content/e14561/e12463/e15420/e15577/e1903/Requiem2_A.mp3";

// von home
$playerwerk = "mp3=http://www.ruediheimlicher.ch/Data/kirchenchor/Lieder/Test/Requiem2_A.mp3&amp;showstop=1";


//$playerwerk = "../Data/kirchenchor/Lieder/Test/Cum_sanctis_S.mp3";



?>

<!-- http://flash-mp3-player.net/players/maxi/generator/ -->
<config>
    <param name="mp3" value="http%3A//flash-mp3-player.net/medias/another_world.mp3"/>
    <param name="showstop" value="1" />
    <param name="showvolume" value="1"/>
    <param name="sliderwidth" value="5"/>
    <param name="sliderheight" value="20"/>
</config>


<object type="application/x-shockwave-flash" data="http://flash-mp3-player.net/medias/player_mp3_maxi.swf" width="900" height="60">
    <param name="movie" value="http://flash-mp3-player.net/medias/player_mp3_maxi.swf" />
    <param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="<?php echo $playerwerk ?>&amp;showstop=1&amp;sliderwidth=5&amp;sliderheight=20" />
</object>


</body>

</html>

