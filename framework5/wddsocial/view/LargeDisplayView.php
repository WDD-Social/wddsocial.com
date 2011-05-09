<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class LargeDisplayView implements \Framework5\IView {	
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		import('wddsocial.helper.WDDSocial\NaturalLanguage');
		
		switch ($options['type']) {
			case 'project':
				return $this->project_display($options['content']);
			default:
				throw new Exception("LargeDisplayView requires parameter type (project), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* Creates a project article
	*/
	
	private function project_display($project){
		$root = \Framework5\Request::root_path();
		
		$teamLinks = static::format_team($project->team);
		
		$html = <<<HTML

					<article class="slider-item">
						<p class="item-image"><a href="{$root}/project/{$project->vanityURL}" title="{$project->title}"><img src="{$root}/images/uploads/{$project->images[0]->file}_large.jpg" alt="{$project->images[0]->title}" /></a></p>
						<h2><a href="{$root}/project/{$project->vanityURL}" title="{$project->title}">{$project->title}</a></h2>
						<p>{$teamLinks}</p>
						<p>{$project->description}</p>
						<p class="comments"><a href="{$root}/project/{$project->vanityURL}#comments" title="{$project->title} | Comments">{$project->comments} comments</a></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach($project->categories as $category){
			array_push($categoryLinks,"<a href=\"{$root}/search/$category\" title=\"Categories | $category\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$project->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Formats the team array into the correct string
	*/
	
	private static function format_team($team){
		if(count($team) > 0){
			
			# Creates string according to how many team members there are for this piece of content
			if(count($team) == 1){
				$userVerbage = \WDDSocial\NaturalLanguage::view_profile($team[0]->id,"{$team[0]->firstName} {$team[0]->lastName}");
				$teamString .= "<a href=\"{$root}/user/{$team[0]->vanityURL}\" title=\"$userVerbage\">{$team[0]->firstName} {$team[0]->lastName}</a>";
			}else if(count($team) == 2){
				$teamString = array();
				foreach($team as $member){
					$userVerbage = \WDDSocial\NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
					array_push($teamString, "<a href=\"{$root}/user/{$member->vanityURL}\" title=\"$userVerbage\">{$member->firstName} {$member->lastName}</a>");
				}
				$teamString = implode(' and ',$teamString);
			}else{
				for($i = 0; $i < count($team); $i++){
					$userVerbage = \WDDSocial\NaturalLanguage::view_profile($team[$i]->id,"{$team[$i]->firstName} {$team[$i]->lastName}");
					if($i == count($team)-1){
						$teamString .= "and <a href=\"{$root}/user/{$team[$i]->vanityURL}\" title=\"$userVerbage\">{$team[$i]->firstName} {$team[$i]->lastName}</a>";
					}else{
						$teamString .= "<a href=\"{$root}/user/{$team[$i]->vanityURL}\" title=\"$userVerbage\">{$team[$i]->firstName} {$team[$i]->lastName}</a>, ";
					}
				}
			}
		}else{
			$teamString = "";
		}
		
		return $teamString;
	}
}