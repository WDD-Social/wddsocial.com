<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class PrivacyView implements \Framework5\IView {
	
	public function render($options = null) {
		$html .= <<<HTML
				
				<h1 class="mega">Privacy Policy</h1>
				<section class="long-content">
					<h2>1. Users should assume that all posts are publicly visible.</h2>
					<p>When posting anything on WDD Social, users should be aware that they are posting information that is visible publicly.</p>
					<p><a href="/events" title="WDD Social | Events">Events</a> and <a href="/articles" title="WDD Social | Articles">articles</a> can be viewed by everyone in the general public, or they can be restricted to the community only. However, even community-only posts can still be seen by anyone, and everyone, in the community.</p>
					<p>To avoid all conflicts and situations that may arise, users should assume that <strong>all</strong> posts can be viewed by the general public.</p>
					
					
					<h2>2. We will never sell or use your data in an unauthorized manner.</h2>
					<p>We promise that we will never sell your data to anyone. No, not even to become man of the year. No, not even if they made an extremely dramatized movie about us.</p>
					<p>We also promise to never use your personal data, or data about your projects, articles, events, or jobs, in an unauthorized manner.</p>
					
					
					<h2>3. We will delete all of your account information when you delete your account.</h2>
					<p>If you ever decide to delete your account, we will delete <strong>all</strong> of your account information.</p>
					<p>We promise not to hold onto any scraps of your data for any reason. Your account, and related information, will be completely and fully deleted.</p>
				</section>
HTML;
		return $html;
	}
}