<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ConfirmEditJob implements \Framework5\IView {
	
	public function render($content = null) {
		return <<<HTML

				<h1 class="mega">Your job posting has been updated! Thanks!</h1>
				<section class="long-content">
					<p>Now that that&rsquo;s out of the way, would you like to <a href="/" title="WDD Social | Home">check out what&rsquo;s going on in the community?</a></p>
					<p>Or, why don&rsquo;t you <a href="/postajob" title="WDD Social | Post a Job">post another job</a> so that you can get <a href="/people" title="WDD Social | People">some amazing new employees</a> for your company.</p>
				</section>
HTML;
	}
}