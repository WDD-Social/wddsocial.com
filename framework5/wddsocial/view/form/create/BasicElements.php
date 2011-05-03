<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class BasicElements implements \Framework5\IView {		
	
	public static function render($options = null) {
		switch ($options['section']) {
			case 'header':
				return static::header($options);
				break;
			case 'footer':
				return static::footer($options);
				break;
		}
	}
	
	/**
	* Displays form header and basic inputs
	*/
	
	private static function header($options){
		$root = \Framework5\Request::root_path();
		$capitalizedTitle = ucfirst($options['data']['type']);
		$vanity = strtolower(trim($options['data']['title']));
		return <<<HTML

					<h1 class="mega">Create a New {$capitalizedTitle}</h1>
					<form action="{$root}create" method="post">
						<h1>Basics</h1>
						<p class="error"><strong>{$options['error']}</strong></p>
						<input type="hidden" name="type" value="{$options['data']['type']}" />
						<fieldset>
							<label for="title">$capitalizedTitle Title</label>
							<input type="text" name="title" id="title" value="{$options['data']['title']}" />
						</fieldset>
						<fieldset>
							<label for="description">Short Description</label>
							<textarea id="description" class="short"></textarea>
							<small><span class="count">128</span> characters left</small>
						</fieldset>
						<fieldset>
							<label for="content">Long Description</label>
							<textarea id="content"></textarea>
							<small>You&rsquo;ve got 65,536 characters to use, so make it count.</small>
						</fieldset>
						<fieldset>
							<label for="vanityURL">Custom Vanity URL</label>
							<input type="text" name="vanityURL" id="vanityURL" placeholder="Optional" />
							<small>Example: wddsocial.com/{$options['data']['type']}/{$vanity}</small>
						</fieldset>
HTML;
	}
	
	
	
	/**
	* Displays form footer
	*/
	
	private static function footer(){
		return <<<HTML

						<input type="submit" value="Create" />
					</form>
HTML;
	}
}