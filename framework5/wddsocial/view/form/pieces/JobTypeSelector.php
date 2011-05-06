<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class JobTypeSelector implements \Framework5\IView {		
	
	public static function render($options = null) {
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getJobTypes);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$html = <<<HTML

						<fieldset class="radio">
							<label>Job Type</label>
							<div>
HTML;
		
		while ($jobType = $query->fetch()) {
			$lowercaseTitle = strtolower($jobType->title);
			if ($jobType->id == $options) {
				$selected = 'checked';
			}
			else {
				$selected = '';
			}
			$html .= <<<HTML

								<input type="radio" id="$lowercaseTitle" name="job-type" value="{$jobType->id}" $selected />
								<label for="$lowercaseTitle">{$jobType->title}</label>
HTML;
		}
		
		$html .= <<<HTML

							</div>
						</fieldset>
HTML;
		
		return $html;
	}
}