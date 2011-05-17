<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TemplateView implements \Framework5\IView {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.view.global.TemplateLang');
	}
	
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		
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
	
	private function _templateHeader($title) {
		if (!isset($title) or empty($title))
			throw new Exception("TemplateView top section requires parameter title");
		
		$active_nav = \Framework5\Request::segment(0);
		$root = \Framework5\Request::root_path();
		
		# output
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
		<meta name="author" content="Anthony Colangelo (http:#www.acolangelo.com) and Tyler Matthews (http:#www.tmatthewsdev.com)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="{$root}images/site/social-favicon.ico">
		<link rel="apple-touch-icon" href="{$root}images/site/social-apple-touch-icon.png">
		<link rel="stylesheet/less" href="{$root}css/style.less">
		<script src="{$root}js/libs/modernizr-1.6.min.js"></script>
		<script src="{$root}js/libs/less-1.0.41.min.js"></script>
		<script type="text/javascript">
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-17688306-7']);
			_gaq.push(['_trackPageview']);
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		</script>
	</head>
	<body>
		<section id="wrap">
			<header>
				<h1><a href="/" title="WDD Social Home">WDD Social</a></h1>
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
	
	private function _templateFooter() {
		$root = \Framework5\Request::root_path();
		$request_id = \Framework5\Request::request_id(); # used for bug tracker link
		
		# output
		return <<<HTML
		
		</section><!-- END WRAP -->
		<footer>
			<nav>
				<ul>
					<!-- <li><a href="developer" title="WDD Social | {$this->lang->text('developer_desc')}">{$this->lang->text('developer')}</a></li> -->
					<li><a href="/bugs/report/{$request_id}" title="WDD Social | Bug Tracker">Report a Problem</a></li>
					<li><a href="/about" title="WDD Social | {$this->lang->text('about_desc')}">{$this->lang->text('about')}</a></li>
					<li><a href="/contact" title="WDD Social | {$this->lang->text('contact_desc')}">{$this->lang->text('contact')}</a></li>
					<li><a href="/terms" title="WDD Social | {$this->lang->text('terms_desc')}">{$this->lang->text('terms')}</a></li>
					<li><a href="/privacy" title="WDD Social | {$this->lang->text('privacy_desc')}">{$this->lang->text('privacy')}</a></li>
				</ul>
			</nav>
			<small>&copy; 2011 WDD Social</small>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
		<script>!window.jQuery && document.write(unescape('%3Cscript src="{$root}js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
		<script src="{$root}js/plugins.js"></script>
		<script src="{$root}js/libs/jquery.easing.1.3.js"></script>
		<script src="{$root}js/libs/jquery.jslide.js"></script>
		<script src="{$root}js/script.js"></script>
		<!--[if lt IE 7 ]>
			<script src="{$root}js/libs/dd_belatedpng.js"></script>
			<script> DD_belatedPNG.fix('img, .png_bg'); #fix any <img> or .png_bg background-images </script>
		<![endif]-->
	</body>
</html>
HTML;
	}
	
	
	
	/**
	* The site Template User Area
	*/
	
	private function _userArea() {
		
		# if the user is logged in
		if ($_SESSION['authorized']) {
			
			# format content
			$userAvatar = (file_exists("images/avatars/{$_SESSION['user']->avatar}_small.jpg"))?"/images/avatars/{$_SESSION['user']->avatar}_small.jpg":"/images/site/user-default_small.jpg";
			
			# output
			return <<<HTML
				
				<section id="user-area" class="signed-in">
					<p><strong><a href="/user/{$_SESSION['user']->vanityURL}" title="{$this->lang->text('user_profile_title')}"><img src="$userAvatar" alt="{$_SESSION['user']->firstName} {$_SESSION['user']->lastName}"/>{$_SESSION['user']->firstName} {$_SESSION['user']->lastName}</a></strong></p>
				 	<p><a href="/messages" title="{$this->lang->text('messages_title')}">{$this->lang->text('messages')} <!--<span class="badge">0</span>--></a></p>
				 	<p><a href="/account" title="{$this->lang->text('account_title')}">{$this->lang->text('account')}</a></p>
				 	<p><a href="/signout" title="{$this->lang->text('signout_title')}">{$this->lang->text('signout')}</a></p>
				 </section><!-- END USER AREA -->
HTML;
		}
		
		# if the user is not logged in
		else{
			return <<<HTML

				<section id="user-area" class="signed-out">
					<p><a href="/signup" title="{$this->lang->text('signup_title')}">{$this->lang->text('signup')}</a></p>
					<p><a href="/signin" title="{$this->lang->text('signin_title')}">{$this->lang->text('signin')}</a></p>
				</section><!-- END USER AREA -->
HTML;
		}
	}
	
	
	
	/**
	* The site Navigation and Search area
	*/
	
	private function _navigation() {
		$current = \Framework5\Request::segment(0);
		
		$html = <<<HTML

				<nav>
					<ul>
HTML;
		
		$navItems = array(
			'people' => $this->lang->text('people'),
			'projects' => $this->lang->text('projects'),
			'articles' => $this->lang->text('articles'),
			'courses' => $this->lang->text('courses'),
			'events' => $this->lang->text('events'),
			'jobs' => $this->lang->text('jobs')
		);
		
		foreach ($navItems as $key => $value) {
			$lower = strtolower($key);
			if ($lower == $current) $class = ' class="current"';
			else $class = null;
			
			$html .= <<<HTML

						<li><a href="/{$lower}" title="{$navItem}"{$class}>$value</a></li>
HTML;
		}
		
		$html .= <<<HTML

					</ul>
					<form action="/search" method="get">
						<input type="text" name="term" placeholder="{$this->lang->text('search_placeholder')}" />
						<input type="submit" value="{$this->lang->text('search')}" />
					</form>
				</nav>
HTML;
		return $html;
	}
}