<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class DirectoryItemView implements \Framework5\IView {
	
	public function render($options = null) {
		switch ($options['type']) {
		
			case 'project':
				return $this->project_display($options['content']);
		
			case 'article':
				return $this->article_display($options['content']);
		
			case 'event':
				return $this->event_display($options['content']);
		
			case 'job':
				return $this->job_display($options['content']);
		
			case 'course':
				return $this->course_display($options['content']);
			
			default:
				throw new Exception("DirectoryItemView requires parameter type (project, article, event, job, or course), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* Creates a project listing
	*/
	
	private function project_display($project){
		$userDisplayName = NaturalLanguage::display_name($project->userID,"{$project->userFirstName} {$project->userLastName}");
		$leadImage = $project->images[0];
		$projectAvatar = (file_exists("images/uploads/{$leadImage->file}_medium.jpg"))?"/images/uploads/{$leadImage->file}_medium.jpg":"/images/site/job-default_medium.jpg";
		
		if (count($project->team) > 1) {
			$members = array();
			foreach ($project->team as $member) {
				$memberDisplayName = NaturalLanguage::display_name($member->id,"{$member->firstName} {$member->lastName}");
				$memberVerbage = NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
				array_push($members,"<strong><a href=\"/user/{$member->vanityURL}\" title=\"$memberVerbage\">$memberDisplayName</a></strong>");
			}
			$membersText = NaturalLanguage::comma_list($members);	
		}
		else {
			$member = $project->team[0];
			$memberDisplayName = NaturalLanguage::display_name($member->id,"{$member->firstName} {$member->lastName}");
			$memberVerbage = NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
			$membersText = "<strong><a href=\"/user/{$member->vanityURL}\" title=\"$memberVerbage\">$memberDisplayName</a></strong>";
		}
		
		
		$html = <<<HTML

					<article class="with-secondary">
						<div class="secondary">
HTML;
		# Determines what type of secondary controls to present (Flag or Edit/Delete)
		if(UserSession::is_current($project->userID)){
			$html .= <<<HTML

							<a href="/" title="Edit &ldquo;{$project->title}&rdquo;" class="edit">Edit</a>
							<a href="/" title="Delete &ldquo;{$project->title}&rdquo;" class="delete">Delete</a>
HTML;
		}else if(UserValidator::is_project_owner($project->id)){
			$html .= <<<HTML

							<a href="/" title="Edit &ldquo;{$project->title}&rdquo;" class="edit">Edit</a>
HTML;
		}else if(UserSession::is_authorized()){
			$html .= <<<HTML

							<a href="/" title="Flag &ldquo;{$project->title}&rdquo;" class="flag">Flag</a>
HTML;
		}	
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="/project/{$project->vanityURL}" title="{$project->title}"><img src="$projectAvatar" alt="{$project->title}"/></a></p>
						<h2><a href="/project/{$project->vanityURL}" title="{$project->title}">{$project->title}</a></h2>
						<p class="team">By $membersText</p>
						<p>{$project->description}</p>
HTML;
		$html .= <<<HTML

						<p class="comments"><a href="/project/{$project->vanityURL}#comments" title="{$project->title} | Comments">{$project->comments} comments</a> <span class="hidden">|</span> <span class="time">{$project->date}</span></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach($project->categories as $category){
			array_push($categoryLinks,"<a href=\"/search/$category\" title=\"Categories | $category\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$project->title} -->
HTML;
		return $html;
	}
}