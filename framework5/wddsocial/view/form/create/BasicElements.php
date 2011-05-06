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
		import('wddsocial.helper.WDDSocial\StringCleaner');
		$root = \Framework5\Request::root_path();
		$capitalizedTitle = ucfirst($options['data']['type']);
			
		if ($options['data']['type'] == 'article') {
			$contentTitle = 'Article Content';
			$textareaClass = ' class="long"';
			$required = ' *';
		}
		else {
			$contentTitle = 'Long Description';
			$textareaClass = '';
			$required = '';
		}
		
		return <<<HTML

					<h1 class="mega">Create a New {$capitalizedTitle}</h1>
					<form action="{$root}create" method="post" enctype="multipart/form-data">
						<h1>Details</h1>
						<p class="error"><strong>{$options['error']}</strong></p>
						<input type="hidden" name="type" value="{$options['data']['type']}" />
						<input type="hidden" name="process" value="creation" />
						<fieldset>
							<label for="title">$capitalizedTitle Title *</label>
							<input type="text" name="title" id="title" value="{$options['data']['title']}" />
						</fieldset>
						<fieldset>
							<label for="description">Short Description *</label>
							<textarea id="description" class="short"></textarea>
							<small>Keep it short, <span class="count">128</span> characters left</small>
						<fieldset>
							<label for="content">$contentTitle$required</label>
							<textarea name="content" id="content"$textareaClass></textarea>
							<small>You&rsquo;ve got <span class="count">65,536</span> characters left to use, so make it count.</small>
						</fieldset>
HTML;
	}
	
	
	
	/**
	* Displays form footer
	*/
	
	private static function footer(){
		return <<<HTML

						<input type="submit" name="submit" value="Create" />
					</form>
HTML;
	}
}