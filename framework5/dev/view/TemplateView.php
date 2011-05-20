<?php

namespace Framework5\Dev;

/*
* 
* @author Tyler Matthews (tmatthewsdev@gmail.com)
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TemplateView implements \Framework5\IView {
	
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
		<title>Framework5 Developer | {$options['title']}</title>
		<meta name="description" content="">
		<meta name="author" content="Anthony Colangelo (http:#www.acolangelo.com) and Tyler Matthews (http:#www.tmatthewsdev.com)">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="/images/site/framework5-favicon.ico">
		<link rel="apple-touch-icon" href="/images/site/social-apple-touch-icon.png">
		<link rel="stylesheet/less" href="/css/style.less">
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
				<h1>Framework5 Developer</h1>
HTML;
			$html .= $this->_renderUserArea();
			$html .= $this->_renderNavigation();
			$html .=<<<HTML

			</header>
			<section id="content">

HTML;
		
		$html .= $options['content'];
		
		# Template Footer
		$html .= <<<HTML

			</section>
		</section><!-- END WRAP -->
		<footer>
			<nav>
				<ul>
					<li><a href="/dev/about" title="About Framework 5 Developer">About</a></li>
					<li><a href="/dev/phpinfo" title="PHP Info">PHP Info</a></li>
				</ul>
			</nav>
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
		<script>!window.jQuery && document.write(unescape('%3Cscript src="/js/libs/jquery-1.4.2.js"%3E%3C/script%3E'))</script>
		<script src="/js/plugins.js"></script>
		<script src="/js/libs/jquery.easing.1.3.js"></script>
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
		/*
		# if the user is logged in
		if (UserSession::is_authorized()) {
			
			$userAvatar = UserSession::user_avatar('small'); # get the users avatar
			$userName = UserSession::user_name();
			$userProfile = UserSession::user_profile();
			
			# output
			return <<<HTML
				
				<section id="user-area" class="signed-in">
					<p><strong><a href="{$userProfile}" title="{$this->lang->text('user_profile_title')}"><img src="{$userAvatar}" alt="{$userName}"/>{$userName}</a></strong></p>
					<p><a href="/create" title="{$this->lang->text('create_title')}">{$this->lang->text('create')}</a></p>
				 	<p><a href="/messages" title="{$this->lang->text('messages_title')}">{$this->lang->text('messages')} <!--<span class="badge">0</span>--></a></p>
				 	<p><a href="/account" title="{$this->lang->text('account_title')}">{$this->lang->text('account')}</a></p>
				 	<p><a href="/signout" title="{$this->lang->text('signout_title')}">{$this->lang->text('signout')}</a></p>
				 </section><!-- END USER AREA -->
HTML;
		}
		
		# if the user is not logged in
		else {
		*/
			return <<<HTML

				<section id="user-area" class="signed-out">
					<p><a href="/signup" title=""></a></p>
					<p><a href="/signin" title=""></a></p>
				</section><!-- END USER AREA -->
HTML;
		//}
	}
	
	
	
	/**
	* The site Navigation and Search area
	*/
	
	private function _renderNavigation() {
		$current = \Framework5\Request::segment(0);
		
		$html = <<<HTML

				<nav>
					<ul>
HTML;
		
		$navItems = array(
			'dev/requests' => 'Requests',
			'dev/issues' => 'Issues');
		
		foreach ($navItems as $key => $value) {
			$key = strtolower($key);
			if ($key == $current) $class = ' class="current"';
			else $class = null;
			
			$html .= <<<HTML

						<li><a href="/{$key}" title="{$navItem}"{$class}>{$value}</a></li>
HTML;
		}
		
		$html .= <<<HTML

					</ul>
					<form action="/dev/exec" method="post">
						<input type="text" id="cmd" name="cmd" placeholder="execute a command" />
						<input type="submit" name="submit" value="Execute" />
					</form>
				</nav>
HTML;
		return $html;
	}
}