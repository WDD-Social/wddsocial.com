<?php
$pdo = new PDO("mysql:host=localhost;dbname=wddsocial", "root", "root");

$query = $pdo->query("SELECT icsUID FROM events AS e WHERE TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) < 0");
$query->setFetchMode(PDO::FETCH_OBJ);
while ($event = $query->fetch()) {
	if (file_exists("files/ics/wddsocial.{$event->icsUID}.ics")) {
		unlink("files/ics/wddsocial.{$event->icsUID}.ics");
	}
}
echo "Cron happened.";