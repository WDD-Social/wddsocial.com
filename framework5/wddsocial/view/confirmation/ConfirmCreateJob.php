<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ConfirmCreateJob implements \Framework5\IView {
	
	public function render($content = null) {
		return <<<HTML

				<h1 class="mega">Your job has been posted! Congratulations!</h1>
				<section class="long-content">
					<p>You&rsquo;ve made a very smart decision! Your job posting will be seen by all of the code wizards and graphic magicians that make up our community here at WDD Social.</p>
					
					<h1>Important Information</h1>
					<p>We&rsquo;ve just sent you an email (to the address you provided us with) with links to edit and delete your job posting.</p>
					<p><strong>Please don&rsquo;t lose these links, because they contain a special security code that allows you and only you to edit or delete your job post.</strong></p>
					<p>If the dog eats the links, or you just forget where you put them, please <strong><a href="/contact" title="WDD Social | Contact Us">contact us</a></strong> to retrieve your information.</p>
					<h2>Where to now?</h2>
					<p>Now that that&rsquo;s out of the way, would you like to <a href="/" title="WDD Social | Home">check out what&rsquo;s going on in the community?</a></p>
					<p>Or, why don&rsquo;t you <a href="/postajob" title="WDD Social | Post a Job">post another job</a> so that you can get <a href="/people" title="WDD Social | People">some amazing new employees</a> for your company.</p>
				</section>
HTML;
	}
}