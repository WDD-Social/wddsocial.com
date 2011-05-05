<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class PrivacyLevels implements \Framework5\IView {		
	
	public static function render($options = null) {
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getPrivacyLevels);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$html = <<<HTML

						<fieldset class="radio">
							<label>Viewable by</label>
							<div>
HTML;
		
		while ($privacyLevel = $query->fetch()) {
			$lowercaseTitle = strtolower($privacyLevel->title);
			switch ($lowercaseTitle) {
				case 'public':
					$displayText = 'Everyone';
					$selected = 'checked';
					break;
				case 'private':
					$displayText = 'Community Only';
					$selected = '';
					break;
			}
			$html .= <<<HTML

								<input type="radio" id="$lowercaseTitle" name="privacy-level" value="$lowercaseTitle" $selected />
								<label for="$lowercaseTitle">$displayText</label>
HTML;
		}
		
		$html .= <<<HTML
							</div>
						</fieldset>
HTML;
		
		return $html;
	}
}