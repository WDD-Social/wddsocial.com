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
		$_SESSION['authorized'] = false;
		
		
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'Connecting the Full Sail University Web Community'));
		
		import('wddsocial.sql.SelectorSQL');
		$sql = new SelectorSQL();
		$query = $db->query($sql->getLatest);
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