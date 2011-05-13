<?php

namespace WDDSocial;

/*
* LargeDisplayView
* 
* Renders the large display view which shows a featured project
* on the home page.
* 
* This display view currently only creates one view, but 
* may be expanded in the future to allow more types of 
* content to be displayed in the same view format.
*  
* @author Anthony Colangelo (me@acolangelo.com)
* @package wddsocial.view.content.LargeDisplayView
* @param $options['type'] = 'project'; //type of view to display
* @param $options['content'] = $obj; //project to display
*/

class LargeDisplayView implements \Framework5\IView {	
	
	public function __construct() {
		# load localization package
		$this->lang = new \Framework5\Lang('wddsocial.lang.view.content.DisplayViewLang');
	}
	
	
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {

		# render selected view
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
		
		# get links to all team members profiles
		$teamLinks = $this->format_team($project->team);
		
		# output
		$html = <<<HTML

					<article class="slider-item">
						<p class="item-image"><a href="/project/{$project->vanityURL}" title="{$project->title}"><img src="/images/uploads/{$project->images[0]->file}_large.jpg" alt="{$project->images[0]->title}" /></a></p>
						<h2><a href="/project/{$project->vanityURL}" title="{$project->title}">{$project->title}</a></h2>
						<p>{$teamLinks}</p>
						<p>{$project->description}</p>
						<p class="comments"><a href="/project/{$project->vanityURL}#comments" title="{$this->lang->text('comments_title', $project->title)}">{$this->lang->text('comments', $project->comments)}</a></p>
HTML;
		
		# render categories
		$categoryLinks = array();
		foreach ($project->categories as $category) {
			array_push($categoryLinks, "<a href=\"/search/$category\" title=\"{$this->lang->text('category_title', $category)}\">$category</a>");
		}
		$categoryLinks = implode(' ', $categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$project->title} -->
HTML;
		
		return $html;
	}
	
	
	
	/**
	* Formats an array of team members into a string of profile links
	*/
	
	private function format_team($team){
		if (count($team) > 0) {
			# Creates string according to how many team members there are for this piece of content
			if (count($team) == 1) {
				$userVerbage = \WDDSocial\NaturalLanguage::view_profile($team[0]->id,"{$team[0]->firstName} {$team[0]->lastName}");
				$teamString .= "<a href=\"/user/{$team[0]->vanityURL}\" title=\"$userVerbage\">{$team[0]->firstName} {$team[0]->lastName}</a>";
			}
			
			# if 2 team members
			else if (count($team) == 2) {
				$teamString = array();
				foreach ($team as $member) {
					$userVerbage = \WDDSocial\NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
					array_push($teamString, "<a href=\"/user/{$member->vanityURL}\" title=\"$userVerbage\">{$member->firstName} {$member->lastName}</a>");
				}
				$teamString = implode(" {$this->lang->text('and')} ", $teamString);
			}
			
			# more than 2 team members
			else {
				for ($i = 0; $i < count($team); $i++) {
					$userVerbage = \WDDSocial\NaturalLanguage::view_profile($team[$i]->id,"{$team[$i]->firstName} {$team[$i]->lastName}");
					if ($i == count($team)-1) {
						$teamString .= "{$this->lang->text('and')} <a href=\"/user/{$team[$i]->vanityURL}\" title=\"$userVerbage\">{$team[$i]->firstName} {$team[$i]->lastName}</a>";
					}
					
					else {
						$teamString .= "<a href=\"/user/{$team[$i]->vanityURL}\" title=\"$userVerbage\">{$team[$i]->firstName} {$team[$i]->lastName}</a>, ";
					}
				}
			}
		}
		
		# no team members
		else {
			$teamString = "";
		}
		
		return $teamString;
	}
}