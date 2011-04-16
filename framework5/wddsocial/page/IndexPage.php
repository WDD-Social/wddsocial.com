<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		$db = instance(':db');
		
		# load language pack
		//lang_load('wddsocial.lang.TemplateLang');
		
		$user->id = 1;
		$user->typeID = 1;
		$user->firstName = 'Anthony';
		$user->lastName = 'Colangelo';
		$user->vanityURL = 'anthony';
		$user->avatar = 'c4ca4238a0b923820dcc509a6f75849b';
		$user->languageID = 'en';
		
		session_start();
		$_SESSION['user'] = $user;
		$_SESSION['authorized'] = true;
		
		
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'Connecting the Full Sail University Web Community'));
		
		
		$query = $db->query("
			SELECT p.id, title, description, p.vanityURL, p.datetime, 'project' AS `type`, u.id AS userID, CONCAT_WS(' ', firstName, lastName) AS userName, u.vanityURL AS userURL,
			getDateDiffEN(p.datetime) AS `date`
			FROM projects AS p
			LEFT JOIN users AS u ON (p.userID = u.id)
			UNION
			SELECT a.id, a.title, a.description, a.vanityURL, a.datetime, 'article' AS `type`, u.id AS userID, CONCAT_WS(' ', firstName, lastName) AS userName, u.vanityURL AS userURL, getDateDiffEN(a.datetime) AS `DATE`
			FROM articles AS a
			LEFT JOIN users AS u ON (a.userID = u.id)
			UNION
			SELECT id, CONCAT_WS(' ', firstName, lastName) AS title, bio AS description, vanityURL, `DATETIME`, 'person' AS `TYPE`, id AS userID, CONCAT_WS(' ', firstName, lastName) AS userName, vanityURL AS userURL, getDateDiffEN(`DATETIME`) AS `DATE`
			FROM users AS u
			ORDER BY DATETIME DESC
		");
		$query->setFetchMode(\PDO::FETCH_OBJ);
		while($row = $query->fetch()){  
			echo "<p>{$row->type}</p>";
			echo "<p>{$row->title}</p>";
			echo "<p>{$row->description}</p>";
			echo "<p>{$row->vanityURL}</p>";
			echo "<p>{$row->userName}</p>";
			echo "<p>{$row->date}</p>";
			echo "<p>------------------------------------</p>";
		}
		
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
		
	}
}