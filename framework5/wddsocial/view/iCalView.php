<?php

namespace WDDSocial;

class iCalView implements \Framework5\IView {		
	
	/**
	* Determines what type of content to render
	*/
	
	public static function render($options = null) {
		
		switch ($options['type']) {
			case 'header':
				return static::header();
			case 'footer':
				return static::footer();
			case 'event':
				return static::event($options['event']);
			default:
				throw new \Framework5\Exception("iCalView requires parameter type (header, footer, or event), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* Creates iCal file header
	*/
	
	private static function header(){
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
	
	private static function footer(){
		return <<<ICS

END:VCALENDAR
ICS;
	
	
	
	/**
	* Creates iCal event in .ics file
	*/
	
	private static function event($event){
		return <<<ICS

BEGIN:VEVENT
CREATED:$event->created
UID:$event->uid
DTEND;TZID=America/New_York:$event->end
TRANSP:OPAQUE
SUMMARY:$event->title
DESCRIPTION:$event->description
DTSTART;TZID=America/New_York:$event->start
DTSTAMP:$event->created
SEQUENCE:1
URL;VALUE=URI:$event->link
END:VEVENT
ICS;
	}
}