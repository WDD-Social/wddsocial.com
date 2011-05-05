<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TeamMembers implements \Framework5\IView {		
	
	public static function render($options = null) {
		$singular = rtrim($options['header'],'s');
		$html = <<<HTML

						<h1>{$options['header']}</h1>
HTML;
		for ($i = 1; $i < 4; $i++) {
			$id = 'team' . $i;
			$html .= <<<HTML

						<fieldset>
							<label for="team$i">$singular $i</label>
							<input type="text" name="team[]" id="team$i" />
HTML;
			if ($options['type'] == 'project') {
				$html .= <<<HTML

							<label for="role$i">Project Role</label>
							<input type="text" name="roles[]" id="role$i" />
HTML;
				
			}
			$html .= <<<HTML

						</fieldset>
HTML;
		}
		$html .= <<<HTML

							<a href="#" title="Add Another $singular" class="add-more">Add Another $singular</a>
						</fieldset>
HTML;
		return $html;
	}
}