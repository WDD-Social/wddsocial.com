<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class AboutView implements \Framework5\IView {
	
	public function render($options = null) {
		$users = $options['users'];
		
		$html .= <<<HTML

				<section id="about">
					<h1 class="mega">The Story of WDD Social</h1>
					<section class="long-content">
						<h1>The Beginnings</h1>
						<p>The story of WDD Social began in March, 2011. <a href="/user/{$users[0]->vanityURL}" title="WDD Social | {$users[0]->firstName} {$users[0]->lastName}">{$users[0]->firstName} {$users[0]->lastName}</a> and <a href="/user/{$users[1]->vanityURL}" title="WDD Social | {$users[1]->firstName} {$users[1]->lastName}">{$users[1]->firstName} {$users[1]->lastName}</a> began working on WDD Social as their <a href="/course/WFP2" title="WDD Social | Web Final Project 2">final project</a> for the Web Design &amp; Development degree at Full Sail University.</p>
						<p>WDD Social was launched officially on June 2, 2011, as <a href="/user/{$users[0]->vanityURL}" title="WDD Social | {$users[0]->firstName} {$users[0]->lastName}">{$users[0]->firstName}</a> and <a href="/user/{$users[1]->vanityURL}" title="WDD Social | {$users[1]->firstName} {$users[1]->lastName}">{$users[1]->firstName}</a> presented their final project to a room full of eager community members.</p>
						<p>The goal of WDD Social is to connect the Full Sail University web community, and make the community better as a whole.</p>
						<a href="/user/{$users[0]->vanityURL}" title="WDD Social | {$users[0]->firstName} {$users[0]->lastName}" class="image-link"><img src="/images/avatars/{$users[0]->avatar}_full.jpg" alt="{$users[0]->firstName} {$users[0]->lastName}"/></a>
						<a href="/user/{$users[1]->vanityURL}" title="WDD Social | {$users[1]->firstName} {$users[1]->lastName}" class="image-link"><img src="/images/avatars/{$users[1]->avatar}_full.jpg" alt="{$users[1]->firstName} {$users[1]->lastName}"/></a>
						<h1>An Open Source Project</h1>
						<p>WDD Social was intended to be a project for the community, by the community, and that&rsquo;s why it is an open source project.</p>
						<p>Got a great idea for an addition to the community? <a href="http://github.com/WDD-Social/wddsocial.com" title="WDD Social Github Repo">Head over to the GitHub repo</a>, and make it! <a href="/user/{$users[0]->vanityURL}" title="WDD Social | {$users[0]->firstName} {$users[0]->lastName}">{$users[0]->firstName}</a> and <a href="/user/{$users[1]->vanityURL}" title="WDD Social | {$users[1]->firstName} {$users[1]->lastName}">{$users[1]->firstName}</a> truly encourage open source contributions and would love if you expanded on what they have started.</p>
						<h1>Want to talk?</h1>
						<p>If you have questions, comments, insults, or if you just want to say hello, <a href="/contact" title="WDD Social | Contact">please contact us!</a></p>
						<p>We&rsquo;re from Jersey, so we&rsquo;d love to chat!</p>
						<p>Thanks for being awesome! See ya around the community!</p>
						<p class="signoff"><strong>The WDD Social Team</strong></p>
						<p class="signoff"><a href="/user/{$users[0]->vanityURL}" title="WDD Social | {$users[0]->firstName} {$users[0]->lastName}">{$users[0]->firstName} {$users[0]->lastName}</a></p>
						<p class="signoff"><a href="/user/{$users[1]->vanityURL}" title="WDD Social | {$users[1]->firstName} {$users[1]->lastName}">{$users[1]->firstName} {$users[1]->lastName}</a></p>
					</section>
				</section>
HTML;
		return $html;
	}
}