<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class TeamMemberInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$randomLimit = (isset($options['team']))?count($options['team']) + 2:2;
		$query = $db->query($sql->getRandomUsers . " LIMIT $randomLimit");
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$users = $query->fetchAll();
		
		$query = $db->query($sql->getRandomRoles . " LIMIT $randomLimit");
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$roles = $query->fetchAll();
		
		$singular = rtrim($options['header'],'s');
		
		$html = <<<HTML

						<h1 id="team">{$options['header']}</h1>
HTML;
		
		switch ($options['type']) {
			case 'project':
				$i = 1;
				if (isset($options['team'])) {
					foreach ($options['team'] as $team) {
						$html .= <<<HTML

						<fieldset>
							<label for="team">$singular</label>
							<input type="text" name="team[]" id="team" class="autocompleter" data-autocomplete="users" placeholder="{$users[$i-1]->name}" value="{$team->firstName} {$team->lastName}" />
							<label for="role">Project Role</label>
							<input type="text" name="roles[]" id="role" placeholder="{$roles[$i-1]->title}" value="{$team->role}" />
						</fieldset>
HTML;
						$i++;
					}
				}
				for ($i; $i < $randomLimit; $i++) {
					$nameValue = ($i == 1)?"{$_SESSION['user']->firstName} {$_SESSION['user']->lastName}":'';
					$html .= <<<HTML

						<fieldset>
							<label for="team">$singular</label>
							<input type="text" name="team[]" id="team" class="autocompleter" data-autocomplete="users" placeholder="{$users[$i-1]->name}" value="{$nameValue}" />
							<label for="role">Project Role</label>
							<input type="text" name="roles[]" id="role" placeholder="{$roles[$i-1]->title}" />
						</fieldset>
HTML;
				}
				break;
			case 'article':
				$html .= <<<HTML

						<fieldset>
HTML;
				$i = 1;
				if (isset($options['team'])) {
					foreach ($options['team'] as $team) {
						$html .= <<<HTML

							<input type="text" name="team[]" id="team" class="autocompleter" data-autocomplete="users" placeholder="{$users[$i-1]->name}" value="{$team->firstName} {$team->lastName}" />
HTML;
						$i++;
					}
				}
				for ($i; $i < $randomLimit; $i++) {
					$nameValue = ($i == 1)?"{$_SESSION['user']->firstName} {$_SESSION['user']->lastName}":'';
					$html .= <<<HTML

						<input type="text" name="team[]" id="team" class="autocompleter" data-autocomplete="users" placeholder="{$users[$i-1]->name}" value="{$nameValue}" />
HTML;
				}
				$html .= <<<HTML

						</fieldset>
HTML;
				break;
		}
		
		$html .= <<<HTML

						<a href="" title="Add Another $singular" class="add-more">Add Another $singular</a>
HTML;
		return $html;
	}
}