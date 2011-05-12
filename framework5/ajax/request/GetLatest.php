<?php

namespace Ajax;

/**
* Get news feed items
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class GetLatest implements \Framework5\IExecutable {
	
	public function execute() {
		
		# check user auth
		if (!\WDDSocial\UserSession::is_authorized()) redirect('/');
		
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		
		$limit = 10;
		
		# query
		import('wddsocial.model.WDDSocial\DisplayVO');
		$query = $this->db->prepare($this->sql->getLatest . " LIMIT 0, $limit");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		# display section items
		while ($item = $query->fetch()) {
			echo render('wddsocial.view.content.WDDSocial\MediumDisplayView', 
				array('type' => $item->type,'content' => $item));
		}
	}
}