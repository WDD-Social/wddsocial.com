<?php

namespace WDDSocial;

/*
* Display event location info
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class EventLocationDisplayView implements \Framework5\IView {
	
	public static function render($content = null) {
		
		$root = \Framework5\Request::root_path();
		$html = "";
		$possessiveTitle = NaturalLanguage::possessive($content->title);
		
		if (UserSession::is_current($content->userID)) {
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit {$possessiveTitle} Location and Time" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		$html .= <<<HTML

					<article class="location-and-time">
HTML;
			
		$html .= <<<HTML

						<p class="item-image"><a href="{$root}/files/ics/{$content->icsUID}.ics" title="Download {$content->title} iCal File" class="calendar-icon">
							<span class="month">{$content->month}</span> 
							<span class="day">{$content->day}</span> 
							<span class="download"><img src="{$root}/images/site/icon-download.png" alt="Download iCal File"/>iCal</span>
						</a></p>
						<h2>{$content->location}</h2>
						<p>{$content->startTime} - {$content->endTime}</p>
						<p><a href="{$root}/files/ics/{$content->icsUID}.ics" title="Download {$content->title} iCal File">Download iCal File</a></p>
					</article><!-- END {$content->title} -->
HTML;
		return $html;
	}
}