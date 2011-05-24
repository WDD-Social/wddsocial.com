<?php

namespace WDDSocial;

/*
* Displays various 404 Pages
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TermsView implements \Framework5\IView {
	
	public function render($options = null) {
		return <<<HTML
				
				<h1 class="mega">Terms of Service</h1>
				<section class="long-content">
					<h1>Community-driven Disclaimer</h1>
					<p>WDD Social was intended to be &mdash; and will, forever, be &mdash; built for and by the community. That also means that the direction of WDD Social will be dictated by the community as a whole. That includes things such as features, improvements, community policies (Terms of Service, <a href="/privacy" title="WDD Social | Privacy Policy">Privacy Policy</a>, etc), and more.</p>
					
					
					<h1>Basic Rules and Guidelines</h1>
					<p>Anything you post on WDD Social can, and most likely will be, seen by <strong>peers</strong>, <strong>students</strong>, <strong>teachers</strong>, <strong>alumni</strong>, <strong>industry professionals</strong>, <strong>future employers</strong>, <strong>your mom</strong>, and <strong>that kid you &ldquo;liked&rdquo; in 4th grade</strong>. Please keep that in mind when posting <strong>anything</strong> on WDD Social.</p>
					<p>Here are a few basic rules, guidelines, and things to keep in mind:</p>
					
					
					<h2>1. This is a creative, collaborative community.</h2>
					<p>Everyone here loves web and application design and development, and probably also Star Wars, cat memes, and all that other hilarious stuff on the internet.</p>
					<p>However, this community is intended to be focused on our industry (web, applications, design, development, etc), so let&rsquo;s keep the posts relevant to and appropriate for our industry.</p>
					<p>In this community, we all want to see the projects you&rsquo;re working on, the things you find interesting, and the industry events you might be attending. We can all go to 4chan for the latest memes, so please, keep it off of WDD Social.<p>
					
					
					<h2>2. You must have permission to use and display all images you post.</h2>
					<p>When using an image as your avatar, or when adding an image to a project, article, event, or job, you must have the rights and permissions necessary to use that image.</p>
					<p>The person who uploads an image for use on WDD Social is solely responsible for any and all legal actions that are taken upon the usage of that image.</p>
					<p>WDD Social, its owners, or its development team is not responsible for any legal actions take on any user-uploaded images used on WDD Social.</p>
					
					
					<h2>3. WDD Social does not own any images that are uploaded, but does reserve the right to use then in screenshots, advertisements, or other public displays of the site.</h2>
					<p>You are free to upload your own pictures and designs to WDD Social, and we will never, and can never, claim ownership of those images.</p>
					<p>We do, however, reserve the right to use the images in screenshots, advertisements, or other public displays of WDD Social.</p>
					
					
					<h2>4. The community can, and will, use their flagging power</h2>
					<p>We built flagging into WDD Social to allow the community to decide what they want to see, and what they think should <abbr title="Go to Findlay, Ohio">GTFO</abbr>.</p>
					<p>Please do not promote irrelevant events, sales, promotions, or anything else that does not have to do with our industry. I promise you that it will be flagged very quickly.</p>
					
					
					<h2>5. The WDD Social team reserves the right to remove any content that is deemed inappropriate for the site.</h2>
					<p>The WDD Social team (ownership, development team, and other related parties) reserves the right to take down and remove content that is deemed inappropriate for the site.</p>
					<p>This can include offensive material, inflammatory comments, inappropriate images, content that does not belong in this community (irrelevant promotions, etc), and any other type of inappropriate content.</p>
					<p>We reserve the right to remove content regardless of whether it has or has not been flagged by the community.</p>
				</section>
HTML;
	}
}