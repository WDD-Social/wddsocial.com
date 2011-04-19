<?php

class TemplateView implements \Framework5\IView {	
	
	public static function render($options = null) {
		
		# retrieve content based on the provided section
		switch ($options['section']) {
			case 'top':
				return static::_templateHeader($options['title']);
			
			case 'bottom':
				return static::_templateFooter();
			
			default:
				throw new Exception(
					"TemplateView requires parameter section (top or bottom), '{$options['section']}' provided");
		}
	}
	
	
	
	/**
	* The site Template Header
	*/
	
	private static function _templateHeader($title) {
		if (!isset($title) or empty($title))
			throw new Exception("TemplateView top section requires parameter title");
			
		# output
		$active_nav = \Framework5\Request::segment(0);
		$root = \Framework5\Request::root_path();
		
		$html = <<<HTML
<!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>WDD Social | {$title}</title>
		<meta name="description" content="">
		<meta name="author" content="Anthony Colangelo (http://www.acolangelo.com) and Tyler Matthews (http://www.tmatthewsdev.com)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="{$root}images/site/social-favicon.ico">
		<link rel="apple-touch-icon" href="{$root}images/site/social-apple-touch-icon.png">
		<link rel="stylesheet/less" href="{$root}css/style.less">
		<script src="{$root}js/libs/modernizr-1.6.min.js"></script>
		<script src="{$root}js/less-1.0.41.min.js"></script>
	</head>
	<body>
		<section id="wrap">
			<header>
				<h1><a href="{$root}" title="WDD Social Home">WDD Social</a></h1>
HTML;
			$html .= static::_userArea();
			$html .= static::_navigation();
			$html .=<<<HTML

			</header>
HTML;
		return $html;
	}
	
	
	
	/**
	* The site Template Footer
	*/
	
	private static function _templateFooter() {
		$root = \Framework5\Request::root_path();
		return <<<HTML
		
		</section><!-- END WRAP -->
		<footer>
			<nav>
				<ul>
					<li><a href="developer" title="WDD Social | Developer Resources">Developer</a></li>
					<li><a href="about" title="WDD Social | About Us">About</a></li>
					<li><a href="contact" title="WDD Social | Contact Us">Contact</a></li>
					<li><a href="terms" title="WDD Social | Terms of Service">Terms</a></li>
					<li><a href="privacy" title="WDD Social | Privacy Policy">Privacy</a></li>
				</ul>
			</nav>
			<small>&copy; 2011 WDD Social</small>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
		<script>!window.jQuery && document.write(unescape('%3Cscript src="{$root}js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
		<script src="{$root}js/plugins.js"></script>
		<script src="{$root}js/libs/jquery.easing.1.3.js"></script>
		<script src="{$root}js/script.js"></script>
		<!--[if lt IE 7 ]>
			<script src="{$root}js/libs/dd_belatedpng.js"></script>
			<script> DD_belatedPNG.fix('img, .png_bg'); //fix any <img> or .png_bg background-images </script>
		<![endif]-->
	</body>
</html>
HTML;
	}
	
	
	
	/**
	* The site Template User Area
	*/
	
	private static function _userArea() {
		
		# if the user is logged in
		if ($_SESSION['authorized']) {
			$root = \Framework5\Request::root_path();
			return <<<HTML
				
				<section id="user-area" class="signed-in">
					<p><strong><a href="user/{$_SESSION['user']->vanityURL}" title="View My Profile"><img src="{$root}images/avatars/{$_SESSION['user']->avatar}_small.jpg" alt="{$_SESSION['user']->firstName} {$_SESSION['user']->lastName}"/>{$_SESSION['user']->firstName} {$_SESSION['user']->lastName}</a></strong></p>
				 	<p><a href="{$root}messages" title="View My Messages">Messages <span class="badge">3</span></a></p>
				 	<p><a href="account" title="View and Edit my Account Information">Account</a></p>
				 	<p><a href="signout" title="Sign Out of WDD Social">Sign Out</a></p>
				 </section><!-- END USER AREA -->
HTML;
		}
		
		# if the user is not logged in
		else{
			return <<<HTML

				<section id="user-area" class="signed-out">
					<p><a href="signup" title="Sign Up for WDD Social">Sign Up</a></p>
					<p><a href="signin" title="Sign In to WDD Social">Sign In</a></p>
				</section><!-- END USER AREA -->
HTML;
		}
	}
	
	
	
	/**
	* The site Navigation and Search area
	*/
	
	private static function _navigation() {
		$current = \Framework5\Request::segment(0);
		$root = \Framework5\Request::root_path();
		
		$html = <<<HTML

				<nav>
					<ul>
HTML;
		
		$navItems = array(
			'people' => text('TemplateLang:people'),
			'projects' => text('TemplateLang:projects'),
			'articles' => text('TemplateLang:articles'),
			'courses' => text('TemplateLang:courses'),
			'events' => text('TemplateLang:events'),
			'jobs' => text('TemplateLang:jobs')
		);
		
		foreach ($navItems as $key => $value) {
			$lower = strtolower($key);
			if ($lower == $current)
				$class = ' class="current"';
			else
				$class = null;
			
			$html .= <<<HTML

						<li><a href="{$root}{$lower}" title="{$navItem}"{$class}>$value</a></li>
HTML;
		}
		
		$html .= <<<HTML

					</ul>
					<form action="{$root}search" method="get">
						<input type="text" name="term" placeholder="Search..." />
						<input type="submit" value="Search" />
					</form>
				</nav>
HTML;
		return $html;
	}
}