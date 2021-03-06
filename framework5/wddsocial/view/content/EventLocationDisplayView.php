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
		$this->lang = new \Framework5\Lang('wddsocial.lang.CommonLang');
	}
	
	public function render($content = null) {
		
		$possessiveTitle = NaturalLanguage::possessive($content->title);
		$html = "";
		
		# if current user is author, show edit controls
		if (UserValidator::is_event_owner($content->id)) {
			$html .= <<<HTML
					<div class="secondary icons">
						<a href="/edit/event/{$content->vanityURL}#location" title="{$this->lang->text('owner_edit_event', $possessiveTitle)}" class="edit">{$this->lang->text('edit')}</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		
		# content
		$html .= <<<HTML

					<article class="location-and-time">
						<p class="item-image"><a href="/files/ics/wddsocial.{$content->icsUID}.ics" title="{$this->lang->text('download_ical', $content->title)}" class="calendar-icon">
							<span class="month">{$content->month}</span> 
							<span class="day">{$content->day}</span> 
							<span class="download"><img src="/images/site/icon-download.png" alt="{$this->lang->text('download_ical_file')}"/>iCal</span>
						</a></p>
						<h2>{$content->location}</h2>
						<p>{$content->startTime} - {$content->endTime}</p>
						<p><a href="/files/ics/wddsocial.{$content->icsUID}.ics" title="{$this->lang->text('download_ical', $content->title)}">{$this->lang->text('download_ical_file')}</a></p>
					</article><!-- END {$content->title} -->
HTML;
		return $html;
	}
}