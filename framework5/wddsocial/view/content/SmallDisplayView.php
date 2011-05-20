<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SmallDisplayView implements \Framework5\IView {	
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.view.content.DisplayViewLang');
	}
	
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
		
		# user avatar
		$userAvatar = (file_exists("images/avatars/{$article->userAvatar}_medium.jpg"))?"/images/avatars/{$article->userAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		
		# output
		$html = <<<HTML

					<article class="slider-item">
						<p class="item-image"><a href="/user/{$article->userURL}" title="{$userVerbage}"><img src="$userAvatar" alt="$userDisplayName"/></a></p>
						<h2><a href="/article/{$article->vanityURL}" title="{$article->title}">{$article->title}</a></h2>
						<p>{$article->description}</p>
						<p class="comments"><a href="/article/{$article->vanityURL}#comments" title="{$this->lang->text('comments_title', $article->title)}">{$this->lang->text('comments', $article->comments)}</a></p>
HTML;

		# Build categories
		$categoryLinks = array();
		foreach ($article->categories as $category) {
			$searchTerm = urlencode($category);
			array_push($categoryLinks,"<a href=\"/search/$searchTerm\" title=\"{$this->lang->text('category_title', $category)}\">$category</a>");
		}
		
		$categoryLinks = implode(' ', $categoryLinks);
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
		if (UserSession::is_authorized()) {
			$html .=<<<HTML
						
						<div class="secondary">
HTML;
			# Determines what type of secondary controls to present (Flag or Edit/Delete)
			if (UserSession::is_current($event->userID)) {
				$html .= <<<HTML

							<a href="/edit/event/{$event->vanityURL}" title="{$this->lang->text('edit_title', $event->title)}" class="edit">{$this->lang->text('edit')}</a>
							<a href="/delete/event/{$event->vanityURL}" title="{$this->lang->text('delete_title', $event->title)}" class="delete">{$this->lang->text('delete')}</a>
HTML;
			}
			
			else if (UserSession::is_authorized()) {
				$flagClass = (UserSession::has_flagged($event->id,$event->type))?' current':'';
				$html .= <<<HTML

							<a href="/flag/event/{$event->vanityURL}" title="{$this->lang->text('flag_title', $event->title)}" class="flag$flagClass">{$this->lang->text('flag')}</a>
HTML;
			}
			
			$html .=<<<HTML
						
					</div><!-- END SECONDARY -->	
HTML;
		}
			
		$html .= <<<HTML

						<p class="item-image"><a href="/files/ics/wddsocial.{$event->icsUID}.ics" title="{$this->lang->text('download_ical', $event->title)}" class="calendar-icon">
							<span class="month">{$event->month}</span> 
							<span class="day">{$event->day}</span> 
							<span class="download"><img src="/images/site/icon-download.png" alt="Download iCal File"/>{$this->lang->text('ical')}</span>
						</a></p>
						<h2><a href="/event/{$event->vanityURL}" title="{$event->title}">{$event->title}</a></h2>
						<p class="location">{$event->location}</p>
						<p>{$event->startTime}</p>
						<p>{$event->description}</p>
						<p class="comments"><a href="/event/{$event->vanityURL}#comments" title="{$this->lang->text('comments_title', $event->title)}">{$this->lang->text('comments', $event->comments)}</a></p>
HTML;

		# Build categories
		$categoryLinks = array();
		foreach ($event->categories as $category) {
			$searchTerm = urlencode($category);
			array_push($categoryLinks,"<a href=\"/search/$searchTerm\" title=\"{$this->lang->text('category_title', $category)}\">$category</a>");
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
	
	private function job_display($job) {
		
		# company site
		$companyLink = ($job->website == '')?"http://google.com/?q={$job->company}":"http://{$job->website}";
		
		# job posting avatar
		$jobAvatar = (file_exists("images/jobs/{$job->avatar}_medium.jpg"))?"/images/jobs/{$job->avatar}_medium.jpg":"/images/site/job-default_medium.jpg";
		
		# output
		$html = <<<HTML

					<article class="with-secondary">
HTML;
		
		# Determines if user is signed in, to show secondary or not
		if (UserSession::is_authorized()) {
			$html .=<<<HTML
						
						<div class="secondary">
HTML;
			# Determines what type of secondary controls to present (Flag or Edit/Delete)
			if (UserSession::is_current($job->userID)) {
				$html .= <<<HTML

							<a href="/edit/job/{$job->vanityURL}" title="{$this->lang->text('edit_title', "{$job->title} | {$job->company}")}" class="edit">{$this->lang->text('edit')}</a>
							<a href="/delete/job/{$job->vanityURL}" title="{$this->lang->text('delete_title', "{$job->title} | {$job->company}")}" class="delete">{$this->lang->text('delete')}</a>
HTML;
			}
			
			else if (UserSession::is_authorized()) {
				$flagClass = (UserSession::has_flagged($job->id,$job->type))?' current':'';
				$html .= <<<HTML

							<a href="/flag/job/{$job->vanityURL}" title="{$this->lang->text('flag_title', "{$job->title} | {$job->company}")}" class="flag$flagClass">{$this->lang->text('flag')}</a>
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
						<p><a href="http://maps.google.com/?q={$job->location}" title="{$this->lang->text('search_maps', $job->location)}">{$job->location}</a></p>
						<p>{$job->description}</p>
						<p class="job-type"><a href="/jobs#{$job->jobType}" title="{$this->lang->text('see_all_jobs', $job->jobType)}">{$job->jobType}</a></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach($job->categories as $category){
			$searchTerm = urlencode($category);
			array_push($categoryLinks,"<a href=\"/search/$searchTerm\" title=\"{$this->lang->text('category_title', $category)}\">$category</a>");
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