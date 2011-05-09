<?php

namespace WDDSocial;

/*
* Display event location info
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class EventLocationDisplayView implements \Framework5\IView {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.view.EventLocationLang');
	}
	
	public function render($content = null) {
		
		$possessiveTitle = NaturalLanguage::possessive($content->title);
		$html = "";
		
		# if current user is author, show edit controls
		if (UserSession::is_current($content->userID)) {
			$html .= <<<HTML
					<div class="secondary icons">
<<<<<<< HEAD
						<a href="{$root}" title="{$this->lang->text('owner_edit_title', $possessiveTitle)}" class="edit">{$this->lang->text('edit')}</a>
=======
						<a href="/" title="Edit {$possessiveTitle} Location and Time" class="edit">Edit</a>
>>>>>>> cbf38e17b883108573d9660dcd89dc19f83fc205
					</div><!-- END SECONDARY -->
HTML;
		}
		
		# content
		$html .= <<<HTML

					<article class="location-and-time">
<<<<<<< HEAD
						<p class="item-image"><a href="{$root}files/ics/{$content->icsUID}.ics" title="{$this->lang->text('download_ical_title', $content->title)}" class="calendar-icon">
							<span class="month">{$content->month}</span> 
							<span class="day">{$content->day}</span> 
							<span class="download"><img src="{$root}images/site/icon-download.png" alt="{$this->lang->text('download_ical_file')}"/>iCal</span>
						</a></p>
						<h2>{$content->location}</h2>
						<p>{$content->startTime} - {$content->endTime}</p>
						<p><a href="{$root}files/ics/{$content->icsUID}.ics" title="{$this->lang->text('download_ical_title', $content->title)}">{$this->lang->text('download_ical_file')}</a></p>
=======
						<p class="item-image"><a href="/files/ics/wddsocial.{$content->icsUID}.ics" title="Download {$content->title} iCal File" class="calendar-icon">
							<span class="month">{$content->month}</span> 
							<span class="day">{$content->day}</span> 
							<span class="download"><img src="/images/site/icon-download.png" alt="Download iCal File"/>iCal</span>
						</a></p>
						<h2>{$content->location}</h2>
						<p>{$content->startTime} - {$content->endTime}</p>
						<p><a href="/files/ics/wddsocial.{$content->icsUID}.ics" title="Download {$content->title} iCal File">Download iCal File</a></p>
>>>>>>> cbf38e17b883108573d9660dcd89dc19f83fc205
					</article><!-- END {$content->title} -->
HTML;
		return $html;
	}
}