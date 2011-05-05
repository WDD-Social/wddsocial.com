<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class LinkInputs implements \Framework5\IView {		
	
	public static function render($options = null) {
		$html = <<<HTML

						<h1 id="links">Links</h1>
						<fieldset>
HTML;
		for ($i = 1; $i < 4; $i++) {
			$html .= <<<HTML

							<input type="text" name="links[]" id="link$i" />
HTML;
		}
		$html .= <<<HTML

							<a href="#" title="Add Another Link" class="add-more">Add Another Link</a>
						</fieldset>
HTML;
		return $html;
	}
}