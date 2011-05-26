<?php

$timezone = new DateTimeZone('America/New_York');
$launch = new DateTime('2011-06-02 18:00:00',$timezone);
$today = new DateTime(NULL,$timezone);

$difference = $launch->diff($today);

if ($difference->days == 0) {
	$displayDate = 'today';
}
else if ($difference->days == 1) {
	$displayDate = 'tomorrow';
}
else if ($difference->days > 1) {
	$displayDate = "in just {$difference->days} days";
}

echo <<<HTML

<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>WDD Social | Connecting the Full Sail web community.</title>
		<meta name="description" content="Connecting the Full Sail University web community. 06.02.2011">
		<meta name="author" content="Anthony Colangelo (http://www.acolangelo.com)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="images/social.favicon.ico">
		<link rel="stylesheet" href="css/style.css?v=2">
	  	<script src="js/libs/modernizr-1.6.min.js"></script>
	</head>
	<body>
		<section id="content">
			<h1><a href="http://www.wddsocial.com/" title="WDD SOCIAL"><img alt="WDD Social" src="images/social.logo.png" /></a></h1>
			<h1 class="mega">Launching {$displayDate}!</h1>
			<section>
				<h2>Official Launch and Presentation</h2>
				<p>We&rsquo;ll be launching and presenting WDD Social at the Web Final presentations, and we&rsquo;d love for you to come join us!</p>
				<address>
					<p><strong>June 2nd, 2011, at 6:00 PM</strong></p>
					<p>Full Sail 3F-129</p>
				</address>
				<h2>What is WDD Social?</h1>
				<p>The mission of WDD Social is to connect the Full Sail University web community.</p>
				<p>It is a place that allows students, teachers, and alumni to share the projects they&rsquo;ve been working on, discuss their thoughts about the industry, and much, much more.</p>
				<p>Features include user profiles, project showcases, articles, events, private messaging, and even a job board.</p>
			</section>
		</section><!-- END CONTENT -->
		<footer>
			<p>Connecting the Full Sail web community.</p>
			<p>06.02.2011</p>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
		<script>!window.jQuery && document.write(unescape('%3Cscript src="js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
		<script src="js/plugins.js"></script>
		<script src="js/script.js"></script>
		<!--[if lt IE 7 ]>
			<script src="js/libs/dd_belatedpng.js"></script>
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
