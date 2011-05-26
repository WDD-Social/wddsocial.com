<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SiteTemplate implements \Framework5\IView {
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		
		# input validation
		if (!set($options['title'])) # require page title
			throw new Exception("WDDSocial\SiteTemplate requires parameter options['title']");
		
		if (!set($options['content']))
			throw new Exception("WDDSocial\SiteTemplate requires parameter options['content']");
		
		# get required resources
		$active_nav = \Framework5\Request::segment(0); # active navigation link
		$request_id = \Framework5\Request::request_id(); # used for bug tracker link
		$this->lang = new \Framework5\Lang('wddsocial.lang.view.global.TemplateLang');
		
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
		<title>WDD Social | {$options['title']}</title>
		<meta name="description" content="">
		<meta name="author" content="Anthony Colangelo (http:#www.acolangelo.com) and Tyler Matthews (http:#www.tmatthewsdev.com)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="/images/site/social-favicon.ico">
		<link rel="apple-touch-icon" href="/images/site/social-apple-touch-icon.png">
		<link rel="stylesheet/less" href="/css/style.less">
		<link rel="stylesheet" href="/css/jquery.fancybox-1.3.4.css">
		<script src="/js/libs/modernizr-1.6.min.js"></script>
		<script src="/js/libs/less-1.0.41.min.js"></script>
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
			$html .= $this->_renderUserArea();
			$html .= $this->_renderNavigation($options['searchTerm']);
			$html .=<<<HTML

			</header>
HTML;
		
		$html .= $options['content'];
		
		# Template Footer
		$html .= <<<HTML
		
		</section><!-- END WRAP -->
		<footer>
			<nav>
				<ul>
					<!-- <li><a href="developer" title="WDD Social | {$this->lang->text('developer-title')}">{$this->lang->text('developer')}</a></li> -->
					<li><a href="/issues/{$request_id}" title="WDD Social | {$this->lang->text('issue-title')}">{$this->lang->text('issue')}</a></li>
					<li><a href="/about" title="WDD Social | {$this->lang->text('about-title')}">{$this->lang->text('about')}</a></li>
					<li><a href="/contact" title="WDD Social | {$this->lang->text('contact-title')}">{$this->lang->text('contact')}</a></li>
					<li><a href="/terms" title="WDD Social | {$this->lang->text('terms-title')}">{$this->lang->text('terms')}</a></li>
					<li><a href="/privacy" title="WDD Social | {$this->lang->text('privacy-title')}">{$this->lang->text('privacy')}</a></li>
				</ul>
			</nav>
			<small>&copy; 2011 WDD Social</small>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
		<script>!window.jQuery && document.write(unescape('%3Cscript src="/js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
		<script src="/js/plugins.js"></script>
		<script src="/js/libs/jquery.easing.1.3.js"></script>
		<script src="/js/libs/jquery.fancybox-1.3.4.pack.js"></script>
		<script src="/js/libs/jquery.mousewheel-3.0.4.pack.js"></script>
		<script src="/js/libs/date.js"></script>
		<script src="/js/script.js"></script>
		<!--[if lt IE 7 ]>
			<script src="/js/libs/dd_belatedpng.js"></script>
			<script> DD_belatedPNG.fix('img, .png_bg'); #fix any <img> or .png_bg background-images </script>
		<![endif]-->
	</body>
</html>
HTML;

		return $html;
	}
	
	
	
	/**
	* The site Template User Area
	*/
	
	private function _renderUserArea() {
		
		# if the user is logged in
		if (UserSession::is_authorized()) {
			
			$userAvatar = UserSession::user_avatar('small'); # get the users avatar
			$userName = UserSession::user_name();
			$userProfile = UserSession::user_profile();
			$messageCount = UserSession::unread_message_count();
			$messageBadge = ($messageCount > 0)?" <span class=\"badge\">{$messageCount}</span>":'';
			
			# output
			return <<<HTML
				
				<section id="user-area" class="signed-in">
					<p><strong><a href="{$userProfile}" title="{$this->lang->text('user-profile-title')}"><img src="{$userAvatar}" alt="{$userName}"/>{$userName}</a></strong></p>
					<p><a href="/create" title="{$this->lang->text('create-title')}">{$this->lang->text('create')}</a></p>
				 	<p><a href="/messages" title="{$this->lang->text('messages-title')}">{$this->lang->text('messages')}$messageBadge</a></p>
				 	<p><a href="/account" title="{$this->lang->text('account-title')}">{$this->lang->text('account')}</a></p>
				 	<p><a href="/signout" title="{$this->lang->text('signout-title')}">{$this->lang->text('signout')}</a></p>
				 </section><!-- END USER AREA -->
HTML;
		}
		
		# if the user is not logged in
		else {
			return <<<HTML

				<section id="user-area" class="signed-out">
					<p>{$this->lang->text('hiring')} <a href="/postajob" title="{$this->lang->text('post-a-job-title')}">{$this->lang->text('post-a-job')}</a></p>
					<p><a href="/signup" title="{$this->lang->text('signup-title')}">{$this->lang->text('signup')}</a></p>
					<p><a href="/signin" title="{$this->lang->text('signin-title')}">{$this->lang->text('signin')}</a></p>
				</section><!-- END USER AREA -->
HTML;
		}
	}
	
	
	
	/**
	* The site Navigation and Search area
	*/
	
	private function _renderNavigation($searchTerm) {
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
		
		$currentPage = \Framework5\Request::segment(0);
		
		if (isset($currentPage) and $currentPage == 'search') {
			$searchType = \Framework5\Request::segment(1);
		}
		else {
			$searchType = 'people';
		}
		
		$html .= <<<HTML

					</ul>
					<form action="/search" method="post">
						<input type="text" name="term" placeholder="{$this->lang->text('search_placeholder')}" value="{$searchTerm}" />
						<input type="hidden" name="searchType" value="{$searchType}" />
						<input type="submit" value="{$this->lang->text('search')}" />
					</form>
				</nav>
HTML;
		return $html;
	}
}