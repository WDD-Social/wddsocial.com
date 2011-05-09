<?php

namespace WDDSocial;

/*
* Display event location info
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class EventLocationDisplayView implements \Framework5\IView {
	
	public function render($content = null) {
		
		$possessiveTitle = NaturalLanguage::possessive($content->title);
		$html = "";
		
		# if current user is author, show edit controls
		if (UserSession::is_current($content->userID)) {
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="/" title="Edit {$possessiveTitle} Location and Time" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		
		# content
		$html .= <<<HTML

					<article class="location-and-time">
						<p class="item-image"><a href="/files/ics/wddsocial.{$content->icsUID}.ics" title="Download {$content->title} iCal File" class="calendar-icon">
							<span class="month">{$content->month}</span> 
							<span class="day">{$content->day}</span> 
							<span class="download"><img src="/images/site/icon-download.png" alt="Download iCal File"/>iCal</span>
						</a></p>
						<h2>{$content->location}</h2>
						<p>{$content->startTime} - {$content->endTime}</p>
						<p><a href="/files/ics/wddsocial.{$content->icsUID}.ics" title="Download {$content->title} iCal File">Download iCal File</a></p>
					</article><!-- END {$content->title} -->
HTML;
		return $html;
	}
}