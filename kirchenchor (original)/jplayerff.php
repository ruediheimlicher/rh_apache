<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8 />

<!-- Website Design By: www.happyworm.com -->
<title>Demo : jPlayer as an audio player</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="The CSS styleable jQuery media player plugin with HTML5 support!" />
<meta name="keywords" content="jPlayer, jQuery, jQuery plugin, media, video, audio, media player, video player, audio player, mp3, mp4, m4a, m4v, aac, h264, ogg, oga, ogv, wav, webm" />
<meta name="company" content="Happyworm" />
<link href="/css/jPlayer.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="/graphics/jplayer.ico" type="image/x-icon" />
<!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!--[if IE 6]>
<link href="/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
<link href="../Audio/Player/jplayer//blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<!--<link href="jplayer.pink.flag.css" rel="stylesheet" type="text/css" />-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
  <script type="text/javascript" src="../Audio/Player/jplayer/jquery.jplayer.min.js"></script>
  <!--
<script type="text/javascript" src="../js/jquery.jplayer.inspector.js"></script>
<script type="text/javascript" src="/js/themeswitcher.js"></script>
--<
<script type="text/javascript">
<![CDATA[
$(document).ready(function(){

	$("#jquery_jplayer_1").jPlayer({
		ready: function () {
			$(this).jPlayer("setMedia", {
				mp3:"http://www.jplayer.org/audio/mp3/TSP-01-Cro_magnon_man.mp3"
			});
		},
		swfPath: "/js",
		supplied: "mp3",
		wmode: "window",
		smoothPlayBar: true,
		keyEnabled: true
	});

	$("#jplayer_inspector").jPlayerInspector({jPlayer:$("#jquery_jplayer_1")});
});
]]>
</script>
<script type="text/javascript">
(function() {
	var s = document.createElement('script'), t = document.getElementsByTagName('script')[0];
	s.type = 'text/javascript';
	s.async = true;
	s.src = 'http://api.flattr.com/js/0.6/load.js?mode=auto';
	t.parentNode.insertBefore(s, t);
})();
</script>
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-3557377-9']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
</head>
<body class="demo" onload="prettyPrint();">
<div id="container">

	<header>
	<a href="/"><img src="/graphics/jplayer_logo.gif" alt="jPlayer" /><h1>HTML5 Audio & Video for jQuery</h1></a>

		<p><a href="http://www.happyworm.com" title="a project by happyworm">a project by happyworm</a></p>
		<nav class="main">
			<ul>
				<li id="home"><a href="/">home</a></li>
				<li id="demos"><a href="/latest/demos/">demos</a></li>
				<li id="download"><a href="/download/">download</a></li>
				<li id="dev_guide"><a href="/latest/developer-guide/">dev guide</a></li>
				<li id="support"><a href="/support/">support</a></li>
				<li id="sites"><a href="/sites/">sites</a></li>
				<li id="about"><a href="/about/">about</a></li>
				<!-- <li id="skins"><a href="/skins/">skins</a></li> -->
			</ul>
		</nav>

	</header>
	
	<div id="content_main">
		<section>

			<h2>jPlayer as an audio player</h2>




		<div id="jquery_jplayer_1" class="jp-jplayer"></div>

		<div id="jp_container_1" class="jp-audio">
			<div class="jp-type-single">
				<div class="jp-gui jp-interface">
					<ul class="jp-controls">
						<li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
						<li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
						<li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
						<li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
						<li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
						<li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
					</ul>
					<div class="jp-progress">
						<div class="jp-seek-bar">
							<div class="jp-play-bar"></div>

						</div>
					</div>
					<div class="jp-volume-bar">
						<div class="jp-volume-bar-value"></div>
					</div>
					<div class="jp-current-time"></div>
					<div class="jp-duration"></div>
				</div>
				<div class="jp-title">
					<ul>
						<li>Cro Magnon Man</li>
					</ul>
				</div>
				<div class="jp-no-solution">
					<span>Update Required</span>
					To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
				</div>
			</div>
		</div>

			<div id="jplayer_inspector"></div>
		</section>
	</div>
	<aside>

</body>
<script type="text/javascript" src="/js/prettify/prettify-jPlayer.js"></script>

</html>
