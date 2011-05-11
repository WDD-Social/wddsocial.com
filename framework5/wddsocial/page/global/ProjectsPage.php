<?php

namespace WDDSocial;

/*
*
* @author Anthony Colangelo (me@acolangelo.com) 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ProjectsPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
	}
	
	
	
	public function execute() {
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# display site header
		echo render(':template', array('section' => 'top', 'title' => 'Projects'));
		
		echo render(':section', array('section' => 'begin_content'));
		
		$sorter = \Framework5\Request::segment(2);
		
		if (isset($sorter) and ($sorter == 'alphabetically' or $sorter == 'newest' or $sorter == 'oldest'))
			$active = $sorter;
		else 
			$active = 'alphabetically';
		
		echo render(':section', 
			array('section' => 'begin_content_section', 'id' => 'directory', 
				'classes' => array('mega', 'with-secondary'), 
				'header' => 'Projects', 'extra' => 'directory_sorters', 'extra_options' => array('base_link' => '/projects/1/', 'active' => $active)));
		
		$page = \Framework5\Request::segment(1);
		if (!isset($page) or !is_numeric($page))
			$page = 1;
		
		# How many results per page
		$perPage = 18;
		
		# Limit of selection
		$limit = $page * $perPage;
		
		switch ($active) {
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
		$query = $this->db->prepare($this->sql->getProjects . " ORDER BY $orderBy" . " LIMIT 0, $limit");
		$query->execute(array('orderBy' => $orderBy));
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		# display section items
		while($item = $query->fetch()){
			echo render('wddsocial.view.content.WDDSocial\DirectoryItemView', 
				array('type' => $item->type,'content' => $item));
		}
		
		$query = $this->db->prepare($this->sql->getProjects . " ORDER BY $orderBy" . " LIMIT $limit, $perPage");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->fetch();
		
		if ($query->rowCount() > 0) {
			$nextPage = $page + 1;
			
			# display section footer
			echo render(':section',
				array('section' => 'end_content_section', 'id' => 'directory', 'load_more' => 'posts', 'load_more_link' => "/projects/$nextPage/$active"));	
		}		
		else {
			# display section footer
			echo render(':section',
				array('section' => 'end_content_section', 'id' => 'directory'));	
		}
		
		echo render(':section', array('section' => 'end_content'));
		
		# display site footer
		echo render(':template', array('section' => 'bottom'));
		
	}
}