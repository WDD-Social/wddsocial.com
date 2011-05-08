<?php

namespace WDDSocial;

class iCalView implements \Framework5\IView {		
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		
		switch ($options['section']) {
			case 'header':
				return $this->header();
			case 'footer':
				return $this->footer();
			case 'event':
				return $this->event($options['event']);
			default:
				throw new \Framework5\Exception("iCalView requires parameter type (header, footer, or event), '{$options['section']}' provided");
		}
	}
	
	
	
	/**
	* Creates iCal file header
	*/
	
	private function header(){
		return <<<ICS
BEGIN:VCALENDAR
METHOD:PUBLISH
VERSION:2.0
X-WR-CALNAME:WDD Social
PRODID:-//Apple Inc.//iCal 4.0.4//EN
X-APPLE-CALENDAR-COLOR:#74B336
X-WR-TIMEZONE:America/New_York
CALSCALE:GREGORIAN
BEGIN:VTIMEZONE
TZID:America/New_York
BEGIN:DAYLIGHT
TZOFFSETFROM:-0500
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU
DTSTART:20070311T020000
TZNAME:EDT
TZOFFSETTO:-0400
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:-0400
RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU
DTSTART:20071104T020000
TZNAME:EST
TZOFFSETTO:-0500
END:STANDARD
END:VTIMEZONE
ICS;
	}
	
	
	
	/**
	* Creates iCal file footer
	*/
	
	private function footer(){
		return <<<ICS

END:VCALENDAR
ICS;
	}
	
	
	
	/**
	* Creates iCal event in .ics file
	*/
	
	private function event($event){
		return <<<ICS

BEGIN:VEVENT
CREATED:{$event->created}
UID:{$event->uid}
DTEND;TZID=America/New_York:{$event->end}
TRANSP:OPAQUE
SUMMARY:{$event->title}
DESCRIPTION:{$event->description}
LOCATION:{$event->location}
DTSTART;TZID=America/New_York:{$event->start}
DTSTAMP:{$event->created}
SEQUENCE:1
URL;VALUE=URI:http://wddsocial.com/event/{$event->vanityURL}
END:VEVENT
ICS;
	}
}