<?php

namespace WDDSocial;

/*
*
* @author Anthony Colangelo (me@acolangelo.com) 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class EventsPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
	}
	
	
	
	public function execute() {
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# display site header
		$page_title = 'Events';
		
		$content.= render(':section', array('section' => 'begin_content'));
		
		$sorter = \Framework5\Request::segment(2);
		$sorters = array('upcoming' => 'upcoming', 'alphabetically' => 'alphabetically', 'newest' => 'newest', 'oldest' => 'oldest');
		
		if (isset($sorter) and in_array($sorter, $sorters)) $active = $sorter;
		else $active = $sorters[0];
		
		$content.= render(':section', 
			array('section' => 'begin_content_section', 'id' => 'directory', 
				'classes' => array('mega', 'with-secondary'), 
				'header' => 'Events', 'sort' => true, 'sorters' => $sorters, 
				'base_link' => '/events/1/', 'active' => $active));
		
		$paginator = new Paginator(1,18);
		
		switch ($active) {
			case 'upcoming':
				$orderBy = 'startDateTime ASC';
				break;
			
			case 'alphabetically':
				$orderBy = 'title ASC';
				break;
			
			case 'newest':
				$orderBy = '`datetime` DESC';
				break;
			
			case 'oldest':
				$orderBy = '`datetime` ASC';
				break;
			
			default:
				$orderBy = 'title ASC';
				break;
		}
		
		
		# query
		$query = (UserSession::is_authorized())?$this->db->prepare($this->sql->getEvents . " ORDER BY $orderBy LIMIT 0, {$paginator->limit}"):$this->db->prepare($this->sql->getPublicEvents . " ORDER BY $orderBy LIMIT 0, {$paginator->limit}");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		# display section items
		while($item = $query->fetch()){
			$content.= render('wddsocial.view.content.WDDSocial\DirectoryItemView', 
				array('type' => $item->type,'content' => $item));
		}
		
		
		$query = (UserSession::is_authorized())?$this->db->prepare($this->sql->getEvents . " ORDER BY $orderBy LIMIT {$paginator->limit}, {$paginator->per}"):$this->db->prepare($this->sql->getPublicEvents . " ORDER BY $orderBy LIMIT {$paginator->limit}, {$paginator->per}");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->fetch();
		
		
		if ($query->rowCount() > 0) {
			# display section footer
			$content.= render(':section',
				array('section' => 'end_content_section', 'id' => 'directory', 'load_more' => 'posts', 'load_more_link' => "/events/{$paginator->next}/$active"));	
		}
			
		else {
			# display section footer
			$content.= render(':section',
				array('section' => 'end_content_section', 'id' => 'directory'));	
		}
		
		
		$content.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $page_title, 'content' => $content));
	}
}