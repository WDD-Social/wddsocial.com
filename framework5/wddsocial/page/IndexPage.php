<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# display page header
		echo render('wddsocial.view.TemplateView', 
			array('section' => 'top', 'title' => 'Connecting the Full Sail University Web Community'));
		
		
		import('wddsocial.sql.SelectorSQL');
		$sql = new SelectorSQL();
		$db = instance(':db');
		
		if($_SESSION['authorized'] == true){
			import('wddsocial.model.DisplayVO');
			$query = $db->query($sql->getLatest);
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
			while($row = $query->fetch()){  
				echo "<p>{$row->type}</p>";
				echo "<p>{$row->title}</p>";
				echo "<p>{$row->description}</p>";
				echo "<p>{$row->vanityURL}</p>";
				echo "<p>{$row->userFirstName} {$row->userLastName}</p>";
				echo "<p>{$row->date}</p>";
				echo "<p>Comments: {$row->comments}</p>";
				echo "<pre>Tags:";
				print_r($row->tags);
				echo "</pre>";
				echo "<p>Team:</p>";
				echo "<ul>";
				foreach ($row->team as $member){
					echo "<li>{$member->firstName} {$member->lastName} ({$member->vanityURL})</li>";
				}
				echo "</ul>";
				echo "<p>------------------------------------</p>";
			}
		}else{
			
		}
		
		
		# display page footer
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
	}
}