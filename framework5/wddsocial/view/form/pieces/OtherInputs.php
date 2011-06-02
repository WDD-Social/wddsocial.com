<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class OtherInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		if (is_array($options['data'])) {
			$content->title = $options['data']['title'];
			$content->type = $options['data']['type'];
			$content->vanityURL = $options['data']['vanityURL'];
		}
		else if (is_object($options['data'])) {
			$content = $options['data'];
		}
		
		if (isset($content->vanityURL)) {
			$vanity = $content->vanityURL;
			$vanityPlaceholder = 'example';
		}
		else {
			$vanity = strtolower(StringCleaner::clean_characters($content->title,array(' ','"',"'",'\\','/')));
			$vanity = ($vanity == '')?'example':$vanity;
			$vanityPlaceholder = ($vanity == 'example')?'Optional':$vanity;
		}
		$html = <<<HTML

						<h1 id="other-options">Other Options</h1>
						<fieldset>
							<label for="vanityURL">Custom Vanity URL</label>
							<input type="text" name="vanityURL" id="vanityURL" class="preview" autocomplete="off" placeholder="$vanityPlaceholder" value="{$content->vanityURL}" />
							<small>wddsocial.com/{$content->type}/<strong>{$vanity}</strong></small>
						</fieldset>
HTML;
		if ($content->type == 'article' or $content->type == 'event') {
			$html .= render('wddsocial.view.form.pieces.WDDSocial\PrivacyLevelSelector',1);
		}
		return $html;
	}
}