<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SmallDisplayView implements \Framework5\IView {	
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		
		switch ($options['type']) {
			case 'article':
				return $this->article_display($options['content']);
			case 'event':
				return $this->event_display($options['content']);
			case 'job':
				return $this->job_display($options['content']);
			case 'person_imagegrid':
				return $this->person_imagegrid_display($options['content']);
			default:
				throw new Exception("SmallDisplayView requires parameter type (event or job), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* Creates an article article
	*/
	
	private function article_display($article){
		$userAvatar = (file_exists("images/avatars/{$article->userAvatar}_medium.jpg"))?"/images/avatars/{$article->userAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		
		$html = <<<HTML

					<article class="slider-item">
						<p class="item-image"><a href="/user/{$article->userURL}" title="{$userVerbage}"><img src="$userAvatar" alt="$userDisplayName"/></a></p>
						<h2><a href="/article/{$article->vanityURL}" title="{$article->title}">{$article->title}</a></h2>
						<p>{$article->description}</p>
						<p class="comments"><a href="/article/{$article->vanityURL}#comments" title="{$article->title} | Comments">{$article->comments} comments</a></p>
HTML;
		# Build categories
		$categoryLinks = array();
		foreach($article->categories as $category){
			array_push($categoryLinks,"<a href=\"/search/$category\" title=\"Categories | $category\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$article->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates an event article
	*/
	
	private function event_display($event){
		$class = (UserSession::is_authorized())?'with-secondary':'slider-item';
		
		$html = <<<HTML

					<article class="$class">
HTML;
		
		# Determines if user is signed in, to show secondary or not
		if(UserSession::is_authorized()){
			$html .=<<<HTML
						
						<div class="secondary">
HTML;
			# Determines what type of secondary controls to present (Flag or Edit/Delete)
			if(UserSession::is_current($event->userID)){
				$html .= <<<HTML

							<a href="/" title="Edit &ldquo;{$event->title}&rdquo;" class="edit">Edit</a>
							<a href="/" title="Delete &ldquo;{$event->title}&rdquo;" class="delete">Delete</a>
HTML;
			}else if(UserSession::is_authorized()){
				$html .= <<<HTML

							<a href="/" title="Flag &ldquo;{$event->title}&rdquo;" class="flag">Flag</a>
HTML;
			}
			$html .=<<<HTML
						
					</div><!-- END SECONDARY -->	
HTML;
		}
			
		$html .= <<<HTML

						<p class="item-image"><a href="/files/ics/wddsocial.{$event->icsUID}.ics" title="Download {$event->title} iCal File" class="calendar-icon">
							<span class="month">{$event->month}</span> 
							<span class="day">{$event->day}</span> 
							<span class="download"><img src="/images/site/icon-download.png" alt="Download iCal File"/>iCal</span>
						</a></p>
						<h2><a href="/event/{$event->vanityURL}" title="{$event->title}">{$event->title}</a></h2>
						<p class="location">{$event->location}</p>
						<p>{$event->startTime}</p>
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
	* Creates a job article
	*/
	
	private function job_display($job){
		$companyLink = ($job->website == '')?"http://google.com/?q={$job->company}":"http://{$job->website}";
		$jobAvatar = (file_exists("images/jobs/{$job->avatar}_medium.jpg"))?"/images/jobs/{$job->avatar}_medium.jpg":"/images/site/job-default_medium.jpg";
		
		$html = <<<HTML

					<article class="with-secondary">
HTML;
		
		# Determines if user is signed in, to show secondary or not
		if(UserSession::is_authorized()){
			$html .=<<<HTML
						
						<div class="secondary">
HTML;
			# Determines what type of secondary controls to present (Flag or Edit/Delete)
			if(UserSession::is_current($job->userID)){
				$html .= <<<HTML

							<a href="/" title="Edit &ldquo;{$job->title} | {$job->company}&rdquo;" class="edit">Edit</a>
							<a href="/" title="Delete &ldquo;{$job->title} | {$job->company}&rdquo;" class="delete">Delete</a>
HTML;
			}else if(UserSession::is_authorized()){
				$html .= <<<HTML

							<a href="/" title="Flag &ldquo;{$job->title} | {$job->company}&rdquo;" class="flag">Flag</a>
HTML;
			}
			$html .=<<<HTML
						
					</div><!-- END SECONDARY -->	
HTML;
		}
			
		$html .= <<<HTML

						<p class="item-image"><a href="$companyLink" title="{$job->company}"><img src="$jobAvatar" alt="{$job->company}"/></a></p>
						<h2><a href="/job/{$job->vanityURL}" title="{$job->title} | {$job->company}">{$job->title}</a></h2>
						<p class="company"><a href="$companyLink" title="{$job->company}">{$job->company}</a></p>
						<p><a href="http://maps.google.com/?q={$job->location}" title="Search Google Maps for {$job->location}">{$job->location}</a></p>
						<p>{$job->description}</p>
						<p class="job-type"><a href="/jobs#{$job->jobType}" title="See {$job->jobType} Job Postings">{$job->jobType}</a></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach($job->categories as $category){
			array_push($categoryLinks,"<a href=\"/search/$category\" title=\"Categories | $category\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$job->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates an image-grid element
	*/
	
	private function person_imagegrid_display($person){
		$userVerbage = NaturalLanguage::view_profile($person->userID,"{$person->userFirstName} {$person->userLastName}");
		$userAvatar = (file_exists("images/avatars/{$person->userAvatar}_medium.jpg"))?"/images/avatars/{$person->userAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		
		return <<<HTML

					<p><a href="/user/{$person->userVanityURL}" title="$userVerbage"><img src="$userAvatar" alt="{$person->userFirstName} {$person->userLastName}"/></a></p>
HTML;
	}
}