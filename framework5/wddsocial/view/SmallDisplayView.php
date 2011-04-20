<?php

class SmallDisplayView implements \Framework5\IView {	
	
	public static function render($options = null) {
		
		switch ($options['type']) {
			case 'event':
				return static::event_display($options['content']);
			default:
				throw new Exception("SmallDisplayView requires parameter type (event), '{$options['type']}' provided");
		}
	}
	
	private static function event_display($event){
		$root = \Framework5\Request::root_path();
		import('wddsocial.controller.UserValidator');
		
		$class = ($_SESSION['authorized'] == true)?'with-secondary':'slider-item';
		
		$html = <<<HTML

					<article class="$class">
HTML;
		if($_SESSION['authorized'] == true){
			$html .=<<<HTML
						
						<div class="secondary">
HTML;
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

						<p class="item-image"><a href="files/ics/{$icsUID}.ics" title="Download {$event->title} iCal File" class="calendar-icon">
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
}