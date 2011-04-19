<?php

class MediumDisplayView implements \Framework5\IView {	
	
	public static function render($options = null) {
		
		switch ($options['type']) {
			case 'project':
				return static::project_display($options['content']);
			
			case 'article':
				return static::article_display($options['content']);
			
			case 'person':
				return static::person_display($options['content']);
			
			default:
				throw new Exception("MediumDisplayView requires parameter type (project, article, or person), '{$options['type']}' provided");
		}
	}
	
	private static function project_display($project){
		$root = \Framework5\Request::root_path();
		import('wddsocial.helper.NaturalLanguage');
		import('wddsocial.controller.UserValidator');
		
		$userVerbage = \WDDSocial\NaturalLanguage::view_profile($project->userID,"{$project->userFirstName} {$project->userLastName}");
		$userDisplayName = \WDDSocial\NaturalLanguage::display_name($project->userID,"{$project->userFirstName} {$project->userLastName}");
		$teamIntro = static::format_team_string($project->userID,$project->team);
		
		$html = <<<HTML

					<article class="projects with-secondary">
						<div class="secondary">
HTML;
		if(\WDDSocial\UserValidator::is_current($project->userID)){
			$html .= <<<HTML

							<a href="#" title="Edit &ldquo;{$project->title}&rsquo;" class="edit">Edit</a>
							<a href="#" title="Delete &ldquo;{$project->title}&rsquo;" class="delete">Delete</a>
HTML;
		}else{
			$html .= <<<HTML

							<a href="#" title="Flag &ldquo;{$project->title}&rsquo;" class="flag">Flag</a>
HTML;
		}	
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="{$root}/user/{$project->userURL}" title="{$userVerbage}"><img src="images/avatars/{$project->userAvatar}_medium.jpg" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="{$root}/user/{$project->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> posted a <strong><a href="{$root}/project/{$project->vanityURL}" title="{$project->title}">project</a></strong>$teamIntro.</p>
						<h2><a href="{$root}/project/{$project->vanityURL}" title="{$project->title}">{$project->title}</a></h2>
						<p>{$project->description}</p>
						<!--<p class="images">
							<a href="project.html" title="The Daily Syndication | Image 1"><img src="images/uploads/dailysyndication01_smedium.jpg" alt="The Daily Syndication | Image 1"/></a>
							<a href="project.html" title="The Daily Syndication | Image 2"><img src="images/uploads/dailysyndication02_smedium.jpg" alt="The Daily Syndication | Image 2"/></a>
							<a href="project.html" title="The Daily Syndication | Image 3"><img src="images/uploads/dailysyndication03_smedium.jpg" alt="The Daily Syndication | Image 3"/></a>
						</p>-->
						<p class="comments"><a href="{$root}/project/{$project->vanityURL}#comments" title="{$project->title} | Comments">{$project->comments} comments</a> <span class="hidden">|</span> <span class="time">{$project->date}</span></p>
HTML;
		$tagLinks = array();
		foreach($project->tags as $tag){
			array_push($tagLinks,"<a href=\"{$root}/search/$tag\" title=\"Categories | $tag\">$tag</a>");
		}
		$tagLinks = implode(' ',$tagLinks);
		$html .= <<<HTML
						<p class="tags">$tagLinks</p>
					</article><!-- END {$project->title} -->
HTML;
		return $html;
	}
	
	private static function article_display($article){
		$root = \Framework5\Request::root_path();
		import('wddsocial.helper.NaturalLanguage');
		import('wddsocial.controller.UserValidator');
		
		$userVerbage = \WDDSocial\NaturalLanguage::view_profile($article->userID,"{$article->userFirstName} {$article->userLastName}");
		$userDisplayName = \WDDSocial\NaturalLanguage::display_name($article->userID,"{$article->userFirstName} {$article->userLastName}");
		
		$html = <<<HTML

					<article class="articles with-secondary">
						<div class="secondary">
HTML;
		if(\WDDSocial\UserValidator::is_current($article->userID)){
			$html .= <<<HTML

							<a href="#" title="Edit &ldquo;{$article->title}&rsquo;" class="edit">Edit</a>
							<a href="#" title="Delete &ldquo;{$article->title}&rsquo;" class="delete">Delete</a>
HTML;
		}else{
			$html .= <<<HTML

							<a href="#" title="Flag &ldquo;{$article->title}&rsquo;" class="flag">Flag</a>
HTML;
		}	
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="{$root}/user/{$article->userURL}" title="{$userVerbage}"><img src="images/avatars/{$article->userAvatar}_medium.jpg" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="{$root}/user/{$article->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> wrote an <strong><a href="{$root}/article/{$article->vanityURL}" title="{$article->title}">article</a></strong>.</p>
						<h2><a href="{$root}/article/{$article->vanityURL}" title="{$article->title}">{$article->title}</a></h2>
						<p>{$article->description}</p>
						<p class="comments"><a href="{$root}/article/{$article->vanityURL}#comments" title="{$article->title} | Comments">{$article->comments} comments</a> <span class="hidden">|</span> <span class="time">{$article->date}</span></p>
HTML;
		$tagLinks = array();
		foreach($article->tags as $tag){
			array_push($tagLinks,"<a href=\"{$root}/search/$tag\" title=\"Categories | $tag\">$tag</a>");
		}
		$tagLinks = implode(' ',$tagLinks);
		$html .= <<<HTML
						<p class="tags">$tagLinks</p>
					</article><!-- END {$article->title} -->
HTML;
		return $html;
	}
	
	private static function person_display($person){
		$root = \Framework5\Request::root_path();
		import('wddsocial.helper.NaturalLanguage');
		
		$userVerbage = \WDDSocial\NaturalLanguage::view_profile($person->userID,"{$person->userFirstName} {$person->userLastName}");
		$userDisplayName = \WDDSocial\NaturalLanguage::display_name($person->userID,"{$person->userFirstName} {$person->userLastName}");
		
		$html = <<<HTML

					<article class="people">
						<p class="item-image"><a href="{$root}/user/{$person->userURL}" title="{$userVerbage}"><img src="images/avatars/{$person->userAvatar}_medium.jpg" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="{$root}/user/{$person->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> joined the community.</p>
						<p>{$person->description}</p>
						<p>{$person->date}</p>
					</article><!-- END {$person->title} -->
HTML;
		return $html;
	}
	
	private static function format_team_string($ownerID, $team){
		import('wddsocial.controller.UserValidator');
		// REMOVE USER WHO POSTED PROJECT FROM TEAM (FOR INTRO SENTENCE), AND PUT CURRENT USER AT FRONT OF ARRAY
		$cleanTeam = $team;
		foreach($cleanTeam as $member){
			if($member->userID == $ownerID){
				$key = array_search($member, $cleanTeam);
				unset($cleanTeam[$key]);
			}else if(\WDDSocial\UserValidator::is_current($member->userID)){
				$key = array_search($member, $cleanTeam);
				$currentUser = $cleanTeam[$key];
				unset($cleanTeam[$key]);
				array_unshift($cleanTeam,$currentUser);
			}
		}
		$cleanTeam = array_values($cleanTeam);
		
		// CREATE TEAM STRING
		if(count($cleanTeam) > 0){
			$teamIntro = " with ";
			$teamString = array();
			if(count($cleanTeam) == 1){
				$userVerbage = \WDDSocial\NaturalLanguage::view_profile($cleanTeam[0]->userID,"{$cleanTeam[0]->firstName} {$cleanTeam[0]->lastName}");
				$userDisplayName = \WDDSocial\NaturalLanguage::display_name($cleanTeam[0]->userID,"{$cleanTeam[0]->firstName} {$cleanTeam[0]->lastName}");
				$teamIntro .= "<strong><a href=\"{$root}/user/{$member->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>";
			}else if(count($cleanTeam) == 2){
				foreach($cleanTeam as $member){
					$userVerbage = \WDDSocial\NaturalLanguage::view_profile($member->userID,"{$member->firstName} {$member->lastName}");
					$userDisplayName = \WDDSocial\NaturalLanguage::display_name($member->userID,"{$member->firstName} {$member->lastName}");
					array_push($teamString, "<strong><a href=\"{$root}/user/{$member->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>");
				}
				$teamString = implode(' and ',$teamString);
				$teamIntro .= $teamString;
			}else{
				for($i = 0; $i < count($cleanTeam); $i++){
					$userVerbage = \WDDSocial\NaturalLanguage::view_profile($cleanTeam[$i]->userID,"{$cleanTeam[$i]->firstName} {$cleanTeam[$i]->lastName}");
					$userDisplayName = \WDDSocial\NaturalLanguage::display_name($cleanTeam[$i]->userID,"{$cleanTeam[$i]->firstName} {$cleanTeam[$i]->lastName}");
					if($i == count($cleanTeam)-1){
						$teamIntro .= "and <strong><a href=\"{$root}/user/{$cleanTeam[$i]->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>";
					}else{
						$teamIntro .= "<strong><a href=\"{$root}/user/{$cleanTeam[$i]->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>, ";
					}
				}
			}
		}else{
			$teamIntro = "";
		}
		
		return $teamIntro;
	}
}