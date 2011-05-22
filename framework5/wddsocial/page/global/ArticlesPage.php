<?php

namespace WDDSocial;

/*
*
* @author Anthony Colangelo (me@acolangelo.com) 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ArticlesPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.ArticlesPageLang');
	}
	
	
	
	public function execute() {
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		$content = render(':section', array('section' => 'begin_content'));
		
		$sorter = \Framework5\Request::segment(2);
		$sorters = array(
			'alphabetically' => $this->lang->text('sort-alphabetically'), 
			'newest' => $this->lang->text('sort-newest'), 
			'oldest' => $this->lang->text('sort-oldest'));
		
		if (isset($sorter) and in_array($sorter, $sorters)) $active = $sorter;
		else $active = $sorters['newest'];
		
		$content.= render(':section', 
			array('section' => 'begin_content_section', 'id' => 'directory', 
				'classes' => array('mega', 'with-secondary'), 
				'header' => $this->lang->text('page-header'), 'sort' => true, 'sorters' => $sorters, 
				'base_link' => '/articles/1/', 'active' => $active));
		
		$paginator = new Paginator(1,18);
		
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
				$orderBy = '`datetime` DESC';
				break;
		}
		
		# query
		$query = (UserSession::is_authorized())?$this->db->prepare($this->sql->getArticles . " ORDER BY $orderBy LIMIT 0, {$paginator->limit}"):$this->db->prepare($this->sql->getPublicArticles . " ORDER BY $orderBy LIMIT 0, {$paginator->limit}");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		# display section items
		while($item = $query->fetch()){
			$content.= render('wddsocial.view.content.WDDSocial\DirectoryItemView', 
				array('type' => $item->type,'content' => $item));
		}
		
		$query = (UserSession::is_authorized())?$this->db->prepare($this->sql->getArticles . " ORDER BY $orderBy LIMIT {$paginator->limit}, {$paginator->per}"):$this->db->prepare($this->sql->getPublicArticles . " ORDER BY $orderBy LIMIT {$paginator->limit}, {$paginator->per}");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->fetch();
		
		
		if ($query->rowCount() > 0) {
			# display section footer
			$content.= render(':section',
				array('section' => 'end_content_section', 'id' => 'directory', 
				'load_more' => 'posts', 'load_more_link' => "/articles/{$paginator->next}/$active"));	
		}
		
			
		else {
			# display section footer
			$content.= render(':section',
				array('section' => 'end_content_section', 'id' => 'directory'));	
		}
		
		$content.= render(':section', array('section' => 'end_content'));
		
		
		# display page
		echo render(':template', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
		
	}
}