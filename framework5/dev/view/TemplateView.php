<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class TemplateView implements \Framework5\IView {	
	
	public function render($options = null) {
		return <<<HTML
<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
	<head>
		<title>Framework5 Developer</title>
		<link rel="shortcut icon" href="/images/site/framework5-favicon.ico">
	</head>

HTML;
	}
}