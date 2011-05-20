<?php
$pdo = new PDO("mysql:host=internal-db.s112587.gridserver.com;dbname=db112587_wddsocial", "db112587_social", "G*Uoj9F|S0i4f+tD");

$query = $pdo->query("SELECT icsUID FROM events AS e WHERE TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) < 0");
$query->setFetchMode(\PDO::FETCH_OBJ);
while ($event = $query->fetch()) {
	if (file_exists("files/ics/wddsocial.{$event->icsUID}.ics")) {
		unlink("files/ics/wddsocial.{$event->uid}.ics");
	}
}