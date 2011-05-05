<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class CategoryInputs implements \Framework5\IView {		
	
	public static function render($options = null) {
		$html = <<<HTML

						<h1 id="categories">Categories</h1>
						<fieldset>
HTML;
		for ($i = 1; $i < 4; $i++) {
			$html .= <<<HTML

							<input type="text" name="categories[]" id="category$i" />
HTML;
		}
		$html .= <<<HTML

							<a href="#" title="Add Another Category" class="add-more">Add Another Category</a>
						</fieldset>
HTML;
		return $html;
	}
}