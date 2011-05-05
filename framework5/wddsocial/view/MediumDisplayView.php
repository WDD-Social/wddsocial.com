<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class MediumDisplayView implements \Framework5\IView {	
	
	/**
	* Determines what type of content to render
	*/
	
	public static function render($options = null) {
		
		import('wddsocial.helper.WDDSocial\NaturalLanguage');
		import('wddsocial.controller.WDDSocial\UserValidator');
		
		switch ($options['type']) {
			case 'project':
				return static::project_display($options['content']);
			
			case 'article':
				return static::article_display($options['content']);
			
			case 'person':
				return static::person_display($options['content']);
			
			default:
				throw new \Exception("MediumDisplayView requires parameter type (project, article, or person), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* Creates a project article
	*/
	
	private static function project_display($project){
	
		//$lang = new \Framework5\Lang('wddsocial.lang.view.MediumDisplayView');
		$root = \Framework5\Request::root_path();
		
		$userVerbage = NaturalLanguage::view_profile($project->userID,"{$project->userFirstName} {$project->userLastName}");
		$userDisplayName = NaturalLanguage::display_name($project->userID,"{$project->userFirstName} {$project->userLastName}");
		$teamIntro = static::format_team_string($project->userID,$project->team);
		
		$html = <<<HTML

					<article class="projects with-secondary">
						<div class="secondary">
HTML;
		
		# Determines what type of secondary controls to present (Flag or Edit/Delete)
		if(UserSession::is_current($project->userID)){
			$html .= <<<HTML

							<a href="{$root}" title="Edit &ldquo;{$project->title}&rdquo;" class="edit">Edit</a>
							<a href="{$root}" title="Delete &ldquo;{$project->title}&rdquo;" class="delete">Delete</a>
HTML;
		}else if(UserValidator::is_project_owner($project->id)){
			$html .= <<<HTML

							<a href="{$root}" title="Edit &ldquo;{$project->title}&rdquo;" class="edit">Edit</a>
HTML;
		}else if(UserSession::is_authorized()){
			$html .= <<<HTML

							<a href="{$root}" title="Flag &ldquo;{$project->title}&rdquo;" class="flag">Flag</a>
HTML;
		}	
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="{$root}user/{$project->userURL}" title="{$userVerbage}"><img src="{$root}images/avatars/{$project->userAvatar}_medium.jpg" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="{$root}user/{$project->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> posted a <strong><a href="{$root}project/{$project->vanityURL}" title="{$project->title}">project</a></strong>$teamIntro.</p>
						<h2><a href="{$root}project/{$project->vanityURL}" title="{$project->title}">{$project->title}</a></h2>
						<p>{$project->description}</p>
HTML;
		
		# Build images if needed
		if(count($project->images) > 0){
			$html .= <<<HTML

						<p class="images">			
HTML;
			foreach($project->images as $image){
				$html .= <<<HTML

							<a href="{$root}images/uploads/{$image->file}_full.jpg" title="{$image->title}"><img src="{$root}images/uploads/{$image->file}_large.jpg" alt="{$image->title}"/></a>
HTML;
			}
			$html .= <<<HTML

						</p>			
HTML;
		}
		$html .= <<<HTML

						<p class="comments"><a href="{$root}project/{$project->vanityURL}#comments" title="{$project->title} | Comments">{$project->comments} comments</a> <span class="hidden">|</span> <span class="time">{$project->date}</span></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach($project->categories as $category){
			array_push($categoryLinks,"<a href=\"{$root}search/$category\" title=\"Categories | $category\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$project->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates an article article
	*/
	
	private static function article_display($article){
		$root = \Framework5\Request::root_path();
		
		$userVerbage = NaturalLanguage::view_profile($article->userID,"{$article->userFirstName} {$article->userLastName}");
		$userDisplayName = NaturalLanguage::display_name($article->userID,"{$article->userFirstName} {$article->userLastName}");
		
		$html = <<<HTML

					<article class="articles with-secondary">
						<div class="secondary">
HTML;
		
		# Determines what type of secondary controls to present (Flag or Edit/Delete)
		if(UserSession::is_current($article->userID)){
			$html .= <<<HTML

							<a href="{$root}" title="Edit &ldquo;{$article->title}&rdquo;" class="edit">Edit</a>
							<a href="{$root}" title="Delete &ldquo;{$article->title}&rdquo;" class="delete">Delete</a>
HTML;
		}else if(UserValidator::is_article_owner($article->id)){
			$html .= <<<HTML

							<a href="{$root}" title="Edit &ldquo;{$article->title}&rdquo;" class="edit">Edit</a>
HTML;
		}else if(UserSession::is_authorized()){
			$html .= <<<HTML

							<a href="{$root}" title="Flag &ldquo;{$article->title}&rdquo;" class="flag">Flag</a>
HTML;
		}	
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="{$root}user/{$article->userURL}" title="{$userVerbage}"><img src="{$root}images/avatars/{$article->userAvatar}_medium.jpg" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="{$root}user/{$article->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> wrote an <strong><a href="{$root}article/{$article->vanityURL}" title="{$article->title}">article</a></strong>.</p>
						<h2><a href="{$root}article/{$article->vanityURL}" title="{$article->title}">{$article->title}</a></h2>
						<p>{$article->description}</p>
HTML;
		
		# Build images if needed
		if(count($article->images) > 0){
			$html .= <<<HTML

						<p class="images">			
HTML;
			foreach($article->images as $image){
				$html .= <<<HTML

							<a href="{$root}images/uploads/{$image->file}_full.jpg" title="{$image->title}"><img src="{$root}images/uploads/{$image->file}_large.jpg" alt="{$image->title}"/></a>
HTML;
			}
			$html .= <<<HTML

						</p>			
HTML;
		}
		$html .= <<<HTML

						<p class="comments"><a href="{$root}article/{$article->vanityURL}#comments" title="{$article->title} | Comments">{$article->comments} comments</a> <span class="hidden">|</span> <span class="time">{$article->date}</span></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach($article->categories as $category){
			array_push($categoryLinks,"<a href=\"{$root}search/$category\" title=\"Categories | $category\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$article->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates a person article
	*/
	
	private static function person_display($person){
		$root = \Framework5\Request::root_path();
		
		$userVerbage = NaturalLanguage::view_profile($person->userID,"{$person->userFirstName} {$person->userLastName}");
		$userDisplayName = NaturalLanguage::display_name($person->userID,"{$person->userFirstName} {$person->userLastName}");
		
		$html = <<<HTML

					<article class="people">
						<p class="item-image"><a href="{$root}user/{$person->userURL}" title="{$userVerbage}"><img src="{$root}images/avatars/{$person->userAvatar}_medium.jpg" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="{$root}user/{$person->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> joined the community.</p>
						<p>{$person->description}</p>
						<p>{$person->date}</p>
					</article><!-- END {$person->title} -->
HTML;
		return $html;
	}
	
	
	/**
	* Creates and formats the team string for display
	*/
	
	private static function format_team_string($ownerID, $team){
		# Remove user who posted content from team (for intro sentence), and put current user at front of array
		$cleanTeam = $team;
		foreach($cleanTeam as $member){
			if($member->id == $ownerID){
				$key = array_search($member, $cleanTeam);
				unset($cleanTeam[$key]);
			}else if(UserSession::is_current($member->id)){
				$key = array_search($member, $cleanTeam);
				$currentUser = $cleanTeam[$key];
				unset($cleanTeam[$key]);
				array_unshift($cleanTeam,$currentUser);
			}
		}
		$cleanTeam = array_values($cleanTeam);
		
		# Create team string
		if(count($cleanTeam) > 0){
			$teamIntro = " with ";
			$teamString = array();
			
			# Creates string according to how many team members there are for this piece of content
			if(count($cleanTeam) == 1){
				$userVerbage = NaturalLanguage::view_profile($cleanTeam[0]->id,"{$cleanTeam[0]->firstName} {$cleanTeam[0]->lastName}");
				$userDisplayName = NaturalLanguage::display_name($cleanTeam[0]->id,"{$cleanTeam[0]->firstName} {$cleanTeam[0]->lastName}");
				$teamIntro .= "<strong><a href=\"{$root}user/{$cleanTeam[0]->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>";
			}else if(count($cleanTeam) == 2){
				foreach($cleanTeam as $member){
					$userVerbage = NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
					$userDisplayName = NaturalLanguage::display_name($member->id,"{$member->firstName} {$member->lastName}");
					array_push($teamString, "<strong><a href=\"{$root}user/{$member->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>");
				}
				$teamString = implode(' and ',$teamString);
				$teamIntro .= $teamString;
			}else{
				$strings = array();
				foreach($cleanTeam as $member){
					$userVerbage = NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
					$userDisplayName = NaturalLanguage::display_name($member->id,"{$member->firstName} {$member->lastName}");
					array_push($strings,"<strong><a href=\"{$root}user/{$member->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>");
				}
				$teamIntro .= NaturalLanguage::comma_list($strings);
			}
		}else{
			$teamIntro = "";
		}
		
		return $teamIntro;
	}
}