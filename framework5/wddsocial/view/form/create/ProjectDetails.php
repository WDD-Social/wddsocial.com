<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ProjectDetails implements \Framework5\IView {		
	
	public static function render($options = null) {
		$root = \Framework5\Request::root_path();
		return <<<HTML

						<h1>Project Details</h1>
						<fieldset>
							<label for="title">Title</label>
							<input type="text" name="title" id="title" value="{$options['data']['title']}" />
						</fieldset>
						<fieldset>
							<label for="description">Description</label>
							<textarea id="description"></textarea>
							<small><span class="count">128</span> characters left</small>
						</fieldset>
						<fieldset>
							<label for="vanityURL">Custom Vanity URL</label>
							<input type="text" name="vanityURL" id="vanityURL" placeholder="Optional" />
							<small>Example: wddsocial.com/{$options['data']['type']}/{$vanity}</small>
						</fieldset>
HTML;
	}
}