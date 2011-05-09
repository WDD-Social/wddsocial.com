<?php

namespace WDDSocial;

/*
* 
* @author 
*/

class MailTemplate implements \Framework5\IView {
	
	public function render($content = null) {
	
return <<<HTML
		<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>WDD Social | TITLE GOES HERE</title>
		<meta name="description" content="WDD Social | Connecting the Full Sail University web community.">
		<meta name="author" content="Anthony Colangelo (http://www.acolangelo.com)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="http://www.wddsocial.com/images/social.favicon.ico">
		<link rel="stylesheet" href="http://www.wddsocial.com/css/email.css">
	  	<script src="http://www.wddsocial.com/js/libs/modernizr-1.6.min.js"></script>
	</head>
	<body>
		<section id="content">
		
		{$content}
		
		</section><!-- END CONTENT -->
		<footer>
			<p>Connecting the Full Sail web community.</p>
			<p>06.02.2011</p>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
		<script>!window.jQuery && document.write(unescape('%3Cscript src="http://www.wddsocial.com/js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
		<script src="http://www.wddsocial.com/js/plugins.js"></script>
		<script src="http://www.wddsocial.com/js/script.js"></script>
		<!--[if lt IE 7 ]>
			<script src="http://www.wddsocial.com/js/libs/dd_belatedpng.js"></script>
			<script> DD_belatedPNG.fix('img, .png_bg'); //fix any <img> or .png_bg background-images </script>
		<![endif]-->
		<script>
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-17688306-5']);
		_gaq.push(['_trackPageview']);
		
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		</script>
	</body>
</html>

HTML;

	}
}