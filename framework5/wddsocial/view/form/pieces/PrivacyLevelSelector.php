<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class PrivacyLevelSelector implements \Framework5\IView {		
	
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
			if ($privacyLevel->id == $options) {
				$selected = 'checked';
			}
			else {
				$selected = '';
			}
			switch ($lowercaseTitle) {
				case 'public':
					$displayText = 'Everyone';
					break;
				case 'private':
					$displayText = 'Community Only';
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