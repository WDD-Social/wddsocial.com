<?php
mysql_connect('localhost', 'root', 'root');
mysql_select_db('localhost');
$ics = <<<EOF
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
EOF;
$result = mysql_query("
	SELECT id, title, description, location, link, DATE_FORMAT(startDatetime, '%Y%m%dT%H%i%S') AS startDatetime, DATE_FORMAT(endDatetime, '%Y%m%dT%H%i%S') AS endDatetime, DATE_FORMAT(createdDatetime, '%Y%m%dT%H%i%SZ') AS createdDatetime, icsUID
	FROM events
	WHERE id = 1
");
while($event = mysql_fetch_assoc($result)){
	$eventID = $event['id'];
	$eventTitle = $event['title'];
	$eventDescription = $event['description'];
	$eventLocation = $event['location'];
	$eventLink = $event['link'];
	$eventStart = $event['startDatetime'];
	$eventEnd = $event['endDatetime'];
	$eventCreated = $event['createdDatetime'];
	$eventUID = $event['icsUID'];
	
	echo("<p>$eventTitle</p>");
	$uidString = $eventID . $eventTitle . $eventCreated . '@wddsocial.com';
	echo("<p>$uidString</p>");
	echo("<p>$eventUID</p>");
	$ics.= <<<EOF

BEGIN:VEVENT
CREATED:$eventCreated
UID:$eventUID
DTEND;TZID=America/New_York:$eventEnd
TRANSP:OPAQUE
SUMMARY:$eventTitle
DESCRIPTION:$eventDescription
DTSTART;TZID=America/New_York:$eventStart
DTSTAMP:$eventCreated
SEQUENCE:1
URL;VALUE=URI:$eventLink
END:VEVENT
EOF;
}

$ics.= <<<EOF

END:VCALENDAR
EOF;
echo("<pre>$ics</pre>");
$handle = fopen("WDD Social - $eventTitle.ics",'x');
fwrite($handle,$ics);
fclose($handle);