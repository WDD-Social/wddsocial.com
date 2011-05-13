<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class UserTypeSelector implements \Framework5\IView {		
	
	public function render($options = null) {
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getUserTypes);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$required = ($options['required'] === true)?' *':'';
		$html = <<<HTML

						<fieldset class="radio">
							<label>I am a{$required}</label>
							<div>
HTML;
		
		while ($userType = $query->fetch()) {
			$lowercaseTitle = strtolower($userType->title);
			if ($userType->id == $options['typeID']) {
				$selected = 'checked';
			}
			else {
				$selected = '';
			}
			$html .= <<<HTML

								<input type="radio" id="$lowercaseTitle" name="user-type" value="{$userType->id}" $selected />
								<label for="$lowercaseTitle">{$userType->title}</label>
HTML;
		}
		
		$html .= <<<HTML

							</div>
						</fieldset>
HTML;
		
		return $html;
	}
}