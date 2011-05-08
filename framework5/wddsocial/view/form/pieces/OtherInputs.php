<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class OtherInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$vanity = strtolower(StringCleaner::clean_characters($options['title'],array(' ','"',"'",'\\','/')));
		$vanity = ($vanity == '')?'example':$vanity;
		$vanityPlaceholder = ($vanity == 'example')?'Optional':$vanity;
		$html = <<<HTML

						<h1 id="other-options">Other Options</h1>
						<fieldset>
							<label for="vanityURL">Custom Vanity URL</label>
							<input type="text" name="vanityURL" id="vanityURL" placeholder="$vanityPlaceholder" />
							<small>wddsocial.com/{$options['type']}/<strong>{$vanity}</strong></small>
						</fieldset>
HTML;
		if ($options['type'] == 'article' or $options['type'] == 'event') {
			$html .= render('wddsocial.view.form.pieces.WDDSocial\PrivacyLevelSelector',1);
		}
		
		return $html;
	}
}