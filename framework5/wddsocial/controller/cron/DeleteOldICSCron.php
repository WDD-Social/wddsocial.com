<?php
$pdo = new PDO("mysql:host=localhost;dbname=wddsocial", "root", "root");

$query = $pdo->query("SELECT icsUID FROM events AS e WHERE TIMESTAMPDIFF(MINUTE,NOW(),endDateTime) < 0");
$query->setFetchMode(PDO::FETCH_OBJ);
while ($event = $query->fetch()) {
	$file = realpath("../../../../html/files/ics/wddsocial.{$event->icsUID}.ics");
	if (file_exists($file)) {
		unlink($file);
	}
	else {
		echo "<p>$file not found</p>";
	}
}