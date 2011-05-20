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
				$html .= <<<HTML

						<fieldset>
							<label for="link-title">Link Title</label>
							<input type="text" name="link-titles[]" id="link-title" value="{$link->title}" />
							
							<label for="link-url">Link URL</label>
							<input type="text" name="link-urls[]" id="link-url" placeholder="example.com" value="{$link->link}" />
						</fieldset>
HTML;
				$i++;
			}
		}
		for ($i; $i < $limit; $i++) {
			$html .= <<<HTML

						<fieldset>
							<label for="link-title">Link Title</label>
							<input type="text" name="link-titles[]" id="link-title" />
							
							<label for="link-url">Link URL</label>
							<input type="text" name="link-urls[]" id="link-url" placeholder="example.com" />
						</fieldset>
HTML;
		}
		
		
		$html .= <<<HTML

						<a href="" title="Add Another Link" class="add-more">Add Another Link</a>
HTML;
		return $html;
	}
}