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
		
		</section>
	</body>
</html>

HTML;

	}
}