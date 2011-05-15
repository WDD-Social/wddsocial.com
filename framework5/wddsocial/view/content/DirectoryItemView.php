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
				return $this->content_display($options['content']);
		
			case 'article':
				return $this->content_display($options['content']);
		
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
	* Creates a project/article listing
	*/
	
	private function content_display($content){
		$userDisplayName = NaturalLanguage::display_name($content->userID,"{$content->userFirstName} {$content->userLastName}");
		
		switch ($content->type) {
			case 'project':
				$leadImage = $content->images[0];
				$contentAvatar = (file_exists("images/uploads/{$leadImage->file}_medium.jpg"))?"/images/uploads/{$leadImage->file}_medium.jpg":"/images/site/job-default_medium.jpg";
				break;
			case 'article':
				$leadImage = $content->images[0];
				if (file_exists("images/uploads/{$leadImage->file}_medium.jpg")) {
					$contentAvatar = "/images/uploads/{$leadImage->file}_medium.jpg";
				}
				else if (file_exists("images/avatars/{$content->userAvatar}_medium.jpg")) {
					$contentAvatar = "/images/avatars/{$content->userAvatar}_medium.jpg";
				}
				else {
					$contentAvatar = "/images/site/job-default_medium.jpg";
				}
				break;
		}
		
		$membersText = $this->format_team_string($content->userID, $content->team, 'By');
		
		
		$html = <<<HTML

					<article class="with-secondary">
						<div class="secondary">
HTML;
		# Determines what type of secondary controls to present (Flag or Edit/Delete)
		if(UserSession::is_current($content->userID)){
			$html .= <<<HTML

							<a href="/edit/{$content->type}/{$content->vanityURL}" title="Edit &ldquo;{$content->title}&rdquo;" class="edit">Edit</a>
							<a href="/delete/{$content->type}/{$content->vanityURL}" title="Delete &ldquo;{$content->title}&rdquo;" class="delete">Delete</a>
HTML;
		}else if( ($content->type == 'project' and UserValidator::is_project_owner($content->id)) or ($content->type == 'article' and UserValidator::is_article_owner($content->id)) ){
			$html .= <<<HTML

							<a href="/edit/{$content->type}/{$content->vanityURL}" title="Edit &ldquo;{$content->title}&rdquo;" class="edit">Edit</a>
HTML;
		}else if(UserSession::is_authorized()){
			$html .= <<<HTML

							<a href="/flag/{$content->type}/{$content->vanityURL}" title="Flag &ldquo;{$content->title}&rdquo;" class="flag">Flag</a>
HTML;
		}	
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="/{$content->type}/{$content->vanityURL}" title="{$content->title}"><img src="$contentAvatar" alt="{$content->title}"/></a></p>
						<h2><a href="/{$content->type}/{$content->vanityURL}" title="{$content->title}">{$content->title}</a></h2>
						<p class="team">$membersText</p>
						<p>{$content->description}</p>
						<p class="comments"><a href="/{$content->type}/{$content->vanityURL}#comments" title="{$content->title} | Comments">{$content->comments} comments</a> <span class="hidden">|</span> <span class="time">{$content->date}</span></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach($content->categories as $category){
			array_push($categoryLinks,"<a href=\"/search/$category\" title=\"Categories | $category\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$content->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates an event listing
	*/
	
	private function event_display($event){
		$html = <<<HTML

					<article class="with-secondary">
						<div class="secondary">
HTML;
		# Determines what type of secondary controls to present (Flag or Edit/Delete)
		if (UserSession::is_current($event->userID)) {
			$html .= <<<HTML

							<a href="/edit/event/{$event->vanityURL}" title="Edit &ldquo;{$event->title}&rdquo;" class="edit">Edit</a>
							<a href="/delete/event/{$event->vanityURL}" title="Delete &ldquo;{$event->title}&rdquo;" class="delete">Delete</a>
HTML;
		}
		else if (UserSession::is_authorized()) {
			$html .= <<<HTML

							<a href="/flag/event/{$event->vanityURL}" title="Flag &ldquo;{$event->title}&rdquo;" class="flag">Flag</a>
HTML;
		}
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="/files/ics/wddsocial.{$event->icsUID}.ics" title="Download {$event->title} iCal File" class="calendar-icon">
							<span class="month">{$event->month}</span> 
							<span class="day">{$event->day}</span> 
							<span class="download"><img src="/images/site/icon-download.png" alt="Download iCal File"/>iCal</span>
						</a></p>
						<h2><a href="/event/{$event->vanityURL}" title="{$event->title}">{$event->title}</a></h2>
						<p class="location">{$event->location}</p>
						<p>{$event->startTime} - {$event->endTime}</p>
						<p>{$event->description}</p>
						<p class="comments"><a href="/event/{$event->vanityURL}#comments" title="{$event->title} | Comments">{$event->comments} comments</a></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach($event->categories as $category){
			array_push($categoryLinks,"<a href=\"/search/$category\" title=\"Categories | $category\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$event->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates and formats the team string for display
	*/
	
	private function format_team_string($ownerID, $team, $introWord, $removeOwner = false){
		# Remove user who posted content from team (for intro sentence), and put current user at front of array
		$cleanTeam = $team;
		foreach($cleanTeam as $member){
			if($member->id == $ownerID and $removeOwner){
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
			$teamIntro = "$introWord ";
			$teamString = array();
			
			# Creates string according to how many team members there are for this piece of content
			if(count($cleanTeam) == 1){
				$userVerbage = NaturalLanguage::view_profile($cleanTeam[0]->id,"{$cleanTeam[0]->firstName} {$cleanTeam[0]->lastName}");
				$userDisplayName = NaturalLanguage::display_name($cleanTeam[0]->id,"{$cleanTeam[0]->firstName} {$cleanTeam[0]->lastName}");
				$teamIntro .= "<strong><a href=\"/user/{$cleanTeam[0]->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>";
			}else if(count($cleanTeam) == 2){
				foreach($cleanTeam as $member){
					$userVerbage = NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
					$userDisplayName = NaturalLanguage::display_name($member->id,"{$member->firstName} {$member->lastName}");
					array_push($teamString, "<strong><a href=\"/user/{$member->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>");
				}
				$teamString = implode(' and ',$teamString);
				$teamIntro .= $teamString;
			}else{
				$strings = array();
				foreach($cleanTeam as $member){
					$userVerbage = NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
					$userDisplayName = NaturalLanguage::display_name($member->id,"{$member->firstName} {$member->lastName}");
					array_push($strings,"<strong><a href=\"/user/{$member->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>");
				}
				$teamIntro .= NaturalLanguage::comma_list($strings);
			}
		}else{
			$teamIntro = "";
		}
		
		return $teamIntro;
	}
}