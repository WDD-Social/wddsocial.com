<?php

namespace WDDSocial;

/*
* Displays various 404 Pages
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Http404View implements \Framework5\IView {
	
	public function render($options = null) {
		
		/* You done goofed
		return <<<HTML
				
				<h1 class="mega">You done goofed!</h1>
				<div id="error">
					<p>Either the page you were looking for doesn&rsquo;t exist, or someone is climbing in our website, and snatching our pages up.</p>
					<p>We back-traced him. He doesn&rsquo;t have to come and confess, we&rsquo;re looking for him. We&rsquo;re gonna find him.</p>
					<p>Anyway, you can try again by checking out our flippin&rsquo; sweet <a href="/" title="WDD Social | Home">homepage</a>.</p>
					<p>Or if this is something we should probably know about, please <a href="/contact" title="WDD Social | Contact">let us know</a>.
				</div><!--END ERROR -->
HTML;
*/

		# OMG Somethings Broken
		return <<<HTML
				
				<h1 class="mega">OMG! Something is broken!</h1>
				<div id="error">
					<img src="/images/site/404-cat.jpg" alt="Sad Cat" />
					<p>Either the page you were looking for doesn&rsquo;t exist, or someone is climbing in our website, and snatching our pages up.</p>
					<p>We back-traced him. He doesn&rsquo;t have to come and confess, we&rsquo;re looking for him. We&rsquo;re gonna find him.</p>
					<p>Anyway, you can try again by checking out our flippin&rsquo; sweet <a href="/" title="WDD Social | Home">homepage</a>.</p>
					<p>Or if this is something we should probably know about, please <a href="/contact" title="WDD Social | Contact">let us know</a>.
				</div><!--END ERROR -->
HTML;
	}
}