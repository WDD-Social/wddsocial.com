<?php

namespace WDDSocial;

/*
* Displays various 404 Pages
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Http404View implements \Framework5\IView {
	
	public function render($options = null) {
		# OMG Somethings Broken Cat
		return <<<HTML

				<section id="error">
					<h1 class="mega">OMG! Something is broken!</h1>
					<img src="/images/site/404-cat.jpg" alt="Sad Cat" />
					<p>Either the page you were looking for doesn&rsquo;t exist, or someone is climbing in our website, and snatching our pages up.</p>
					<p>Anyway, you can try again by checking out our flippin&rsquo; sweet <a href="/" title="WDD Social | Home">homepage</a>.</p>
					<p>Or if this is something we should probably know about, please <a href="/contact" title="WDD Social | Contact">let us know</a>, and we will have our developer gnomes get on it right away.</p>
				</section>
HTML;
	}
}