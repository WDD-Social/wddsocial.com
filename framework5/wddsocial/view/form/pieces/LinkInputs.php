<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class LinkInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$html = <<<HTML

						<h1 id="links">Links</h1>
HTML;
		for ($i = 1; $i < 2; $i++) {
			if ($i == 1) {
				$linkNumber = '';
			}
			else {
				$linkNumber = " $i";
			}
			$html .= <<<HTML

						<fieldset>
							<label for="link-title$i">Link$linkNumber Title</label>
							<input type="text" name="link-titles[]" id="link-title$i" />
							
							<label for="link-url$i">Link$linkNumber URL</label>
							<input type="text" name="link-urls[]" id="link-url$i" placeholder="example.com" />
						</fieldset>
HTML;
		}
		$html .= <<<HTML

						<a href="#" title="Add Another Link" class="add-more">Add Another Link</a>
HTML;
		return $html;
	}
}