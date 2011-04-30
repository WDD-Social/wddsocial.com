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
	
	public static function render($options = null) {
		
		switch ($options['type']) {
			case 'article':
				return static::article_display($options['content']);
			case 'event':
				return static::event_display($options['content']);
			case 'job':
				return static::job_display($options['content']);
			case 'person_imagegrid':
				return static::person_imagegrid_display($options['content']);
			default:
				throw new \Framework5\Exception("SmallDisplayView requires parameter type (event or job), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* Creates an article article
	*/
	
	private static function article_display($article){
		$root = \Framework5\Request::root_path();
		
		$html = <<<HTML

					<article class="slider-item">
						<p class="item-image"><a href="{$root}/user/{$article->userURL}" title="{$userVerbage}"><img src="{$root}/images/avatars/{$article->userAvatar}_medium.jpg" alt="$userDisplayName"/></a></p>
						<h2><a href="{$root}/article/{$article->vanityURL}" title="{$article->title}">{$article->title}</a></h2>
						<p>{$article->description}</p>
						<p class="comments"><a href="{$root}/article/{$article->vanityURL}#comments" title="{$article->title} | Comments">{$article->comments} comments</a></p>
HTML;
		# Build categories
		$categoryLinks = array();
		foreach($article->categories as $category){
			array_push($categoryLinks,"<a href=\"{$root}/search/$category\" title=\"Categories | $category\">$category</a>");
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
	
	private static function event_display($event){
		$root = \Framework5\Request::root_path();
		
		$class = (\WDDSocial\UserValidator::is_authorized())?'with-secondary':'slider-item';
		
		$html = <<<HTML

					<article class="$class">
HTML;
		
		# Determines if user is signed in, to show secondary or not
		if(\WDDSocial\UserValidator::is_authorized()){
			$html .=<<<HTML
						
						<div class="secondary">
HTML;
			# Determines what type of secondary controls to present (Flag or Edit/Delete)
			if(\WDDSocial\UserValidator::is_current($event->userID)){
				$html .= <<<HTML

							<a href="{$root}" title="Edit &ldquo;{$event->title}&rdquo;" class="edit">Edit</a>
							<a href="{$root}" title="Delete &ldquo;{$event->title}&rdquo;" class="delete">Delete</a>
HTML;
			}else if(\WDDSocial\UserValidator::is_authorized()){
				$html .= <<<HTML

							<a href="{$root}" title="Flag &ldquo;{$event->title}&rdquo;" class="flag">Flag</a>
HTML;
			}
			$html .=<<<HTML
						
					</div><!-- END SECONDARY -->	
HTML;
		}
			
		$html .= <<<HTML

						<p class="item-image"><a href="{$root}/files/ics/{$event->icsUID}.ics" title="Download {$event->title} iCal File" class="calendar-icon">
							<span class="month">{$event->month}</span> 
							<span class="day">{$event->day}</span> 
							<span class="download"><img src="{$root}/images/site/icon-download.png" alt="Download iCal File"/>iCal</span>
						</a></p>
						<h2><a href="{$root}/event/{$event->vanityURL}" title="{$event->title}">{$event->title}</a></h2>
						<p class="location">{$event->location}</p>
						<p>{$event->startTime}</p>
						<p>{$event->description}</p>
						<p class="comments"><a href="{$root}/event/{$event->vanityURL}#comments" title="{$event->title} | Comments">{$event->comments} comments</a></p>
HTML;
		# Build categories
		$categoryLinks = array();
		foreach($event->categories as $category){
			array_push($categoryLinks,"<a href=\"{$root}/search/$category\" title=\"Categories | $category\">$category</a>");
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
	
	private static function job_display($job){
		$root = \Framework5\Request::root_path();
		$html = <<<HTML

					<article class="with-secondary">
HTML;
		
		# Determines if user is signed in, to show secondary or not
		if(\WDDSocial\UserValidator::is_authorized()){
			$html .=<<<HTML
						
						<div class="secondary">
HTML;
			# Determines what type of secondary controls to present (Flag or Edit/Delete)
			if(\WDDSocial\UserValidator::is_current($job->userID)){
				$html .= <<<HTML

							<a href="{$root}" title="Edit &ldquo;{$job->title} | {$job->company}&rdquo;" class="edit">Edit</a>
							<a href="{$root}" title="Delete &ldquo;{$job->title} | {$job->company}&rdquo;" class="delete">Delete</a>
HTML;
			}else if(\WDDSocial\UserValidator::is_authorized()){
				$html .= <<<HTML

							<a href="{$root}" title="Flag &ldquo;{$job->title} | {$job->company}&rdquo;" class="flag">Flag</a>
HTML;
			}
			$html .=<<<HTML
						
					</div><!-- END SECONDARY -->	
HTML;
		}
			
		$html .= <<<HTML

						<p class="item-image"><a href="http://{$job->website}" title="{$job->company}"><img src="{$root}/images/jobs/{$job->avatar}_medium.jpg" alt="{$job->company}"/></a></p>
						<h2><a href="{$root}/job/{$job->vanityURL}" title="{$job->title} | {$job->company}">{$job->title}</a></h2>
						<p class="company"><a href="http://{$job->website}" title="{$job->company}">{$job->company}</a></p>
						<p>{$job->location}</p>
						<p>{$job->description}</p>
						<p class="job-type"><a href="{$root}/jobs#{$job->jobType}" title="See {$job->jobType} Job Postings">{$job->jobType}</a></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach($job->categories as $category){
			array_push($categoryLinks,"<a href=\"{$root}/search/$category\" title=\"Categories | $category\">$category</a>");
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
	
	private static function person_imagegrid_display($person){
		$root = \Framework5\Request::root_path();
		$userVerbage = \WDDSocial\NaturalLanguage::view_profile($person->userID,"{$person->userFirstName} {$person->userLastName}");
		return <<<HTML

					<p><a href="{$root}/user/{$person->userVanityURL}" title="$userVerbage"><img src="{$root}/images/avatars/{$person->userAvatar}_medium.jpg" alt="{$person->userFirstName} {$person->userLastName}"/></a></p>
HTML;
	}
}