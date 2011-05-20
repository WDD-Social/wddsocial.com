<?php
$db = instance(':db');
$sql = instance(':sel-sql');

$query = $db->query($sql->getExpiredEvents);
$query->setFetchMode(\PDO::FETCH_OBJ);
while ($event = $query->fetch()) {
	if (file_exists("files/ics/wddsocial.{$event->icsUID}.ics")) {
		unlink("files/ics/wddsocial.{$event->uid}.ics");
	}
}