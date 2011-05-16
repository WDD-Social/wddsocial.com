<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class LinkInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$limit = (isset($options['links']))?count($options['links']) + 2:3;
		
		$html = <<<HTML

						<h1 id="links">Links</h1>
HTML;
		$i = 1;
		if (isset($options['links'])) {
			foreach ($options['links'] as $link) {
				$linkNumber = ($i == 1)?'':" $i";
				$html .= <<<HTML

						<fieldset>
							<label for="link-title$i">Link$linkNumber Title</label>
							<input type="text" name="link-titles[]" id="link-title$i" value="{$link->title}" />
							
							<label for="link-url$i">Link$linkNumber URL</label>
							<input type="text" name="link-urls[]" id="link-url$i" placeholder="example.com" value="{$link->link}" />
						</fieldset>
HTML;
				$i++;
			}
		}
		for ($i; $i < $limit; $i++) {
			$linkNumber = ($i == 1)?'':" $i";
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