<?php

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SmallDisplayView implements \Framework5\IView {	
	
	# DETERMINES WHAT TYPE OF CONTENT TO RENDER
	public static function render($options = null) {
		import('wddsocial.controller.UserValidator');
		
		switch ($options['type']) {
			case 'event':
				return static::event_display($options['content']);
			case 'job':
				return static::job_display($options['content']);
			default:
				throw new Exception("SmallDisplayView requires parameter type (event or job), '{$options['type']}' provided");
		}
	}
	
	
	
	# CREATES AN EVENT ARTICLE
	private static function event_display($event){
		$root = \Framework5\Request::root_path();
		
		$class = (\WDDSocial\UserValidator::is_authorized())?'with-secondary':'slider-item';
		
		$html = <<<HTML

					<article class="$class">
HTML;
		
		# DETERMINES IF USER IS SIGNED IN, TO SHOW SECONDARY OR NOT
		if(\WDDSocial\UserValidator::is_authorized()){
			$html .=<<<HTML
						
						<div class="secondary">
HTML;
			# DETERMINES WHAT TYPE OF SECONDARY CONTROLS TO PRESENT (FLAG OR EDIT/DELETE)
			if(\WDDSocial\UserValidator::is_current($event->userID)){
				$html .= <<<HTML

							<a href="#" title="Edit &ldquo;{$event->title}&rsquo;" class="edit">Edit</a>
							<a href="#" title="Delete &ldquo;{$event->title}&rsquo;" class="delete">Delete</a>
HTML;
			}else{
				$html .= <<<HTML

							<a href="#" title="Flag &ldquo;{$event->title}&rsquo;" class="flag">Flag</a>
HTML;
			}
			$html .=<<<HTML
						
					</div><!-- END SECONDARY -->	
HTML;
		}
			
		$html .= <<<HTML

						<p class="item-image"><a href="files/ics/{$event->icsUID}.ics" title="Download {$event->title} iCal File" class="calendar-icon">
							<span class="month">{$event->month}</span> 
							<span class="day">{$event->day}</span> 
							<span class="download"><img src="images/site/icon-download.png" alt="Download iCal File"/>iCal</span>
						</a></p>
						<h2><a href="event/{$event->vanityURL}" title="{$event->title}">{$event->title}</a></h2>
						<p class="location">{$event->location}</p>
						<p>{$event->startTime}</p>
						<p>{$event->description}</p>
						<p class="comments"><a href="event/{$event->vanityURL}#comments" title="{$event->title} | Comments">{$event->comments} comments</a></p>
HTML;
		# BUILDS TAGS
		$tagLinks = array();
		foreach($event->tags as $tag){
			array_push($tagLinks,"<a href=\"{$root}/search/$tag\" title=\"Categories | $tag\">$tag</a>");
		}
		$tagLinks = implode(' ',$tagLinks);
		$html .= <<<HTML
						<p class="tags">$tagLinks</p>
					</article><!-- END {$event->title} -->
HTML;
		return $html;
	}
	
	
	
	# CREATES A JOB ARTICLE
	private static function job_display($job){
		$root = \Framework5\Request::root_path();
		$html = <<<HTML

					<article class="with-secondary">
HTML;
		
		# DETERMINES IF USER IS SIGNED IN, TO SHOW SECONDARY OR NOT
		if(\WDDSocial\UserValidator::is_authorized()){
			$html .=<<<HTML
						
						<div class="secondary">
HTML;
			# DETERMINES WHAT TYPE OF SECONDARY CONTROLS TO PRESENT (FLAG OR EDIT/DELETE)
			if(\WDDSocial\UserValidator::is_current($job->userID)){
				$html .= <<<HTML

							<a href="#" title="Edit &ldquo;{$job->title} | {$job->company}&rsquo;" class="edit">Edit</a>
							<a href="#" title="Delete &ldquo;{$job->title} | {$job->company}&rsquo;" class="delete">Delete</a>
HTML;
			}else{
				$html .= <<<HTML

							<a href="#" title="Flag &ldquo;{$job->title} | {$job->company}&rsquo;" class="flag">Flag</a>
HTML;
			}
			$html .=<<<HTML
						
					</div><!-- END SECONDARY -->	
HTML;
		}
			
		$html .= <<<HTML

						<p class="item-image"><a href="http://{$job->website}" title="{$job->company}"><img src="images/jobs/{$job->avatar}_medium.jpg" alt="{$job->company}"/></a></p>
						<h2><a href="{$root}/job/{$job->id}" title="{$job->title} | {$job->company}">{$job->title}</a></h2>
						<p class="company"><a href="http://{$job->website}" title="{$job->company}">{$job->company}</a></p>
						<p>{$job->location}</p>
						<p>{$job->description}</p>
						<p class="job-type"><a href="{$root}/jobs#{$job->jobType}" title="See {$job->jobType} Job Postings">{$job->jobType}</a></p>
HTML;
		# BUILDS TAGS
		$tagLinks = array();
		foreach($job->tags as $tag){
			array_push($tagLinks,"<a href=\"{$root}/search/$tag\" title=\"Categories | $tag\">$tag</a>");
		}
		$tagLinks = implode(' ',$tagLinks);
		$html .= <<<HTML
						<p class="tags">$tagLinks</p>
					</article><!-- END {$job->title} -->
HTML;
		return $html;
	}
}