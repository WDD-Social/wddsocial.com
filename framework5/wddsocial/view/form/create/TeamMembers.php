<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TeamMembers implements \Framework5\IView {		
	
	public static function render($options = null) {
		$html = <<<HTML

						<h1>$options</h1>
						<fieldset>
HTML;
		for ($i = 0; $i < 3; $i++) {
			if ($i > 0) {
				$id = 'team' . $i;
			}
			else {
				$id = 'team';
			}
			$html .= <<<HTML

							<input type="text" name="team[]" id="$id" />
HTML;
		}
		$singular = rtrim($options,'s');
		$html .= <<<HTML

							<a href="#" title="Add Another $singular" class="add-more">Add Another $singular</a>
						</fieldset>
HTML;
		return $html;
	}
}