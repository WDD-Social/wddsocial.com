<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class CoursesPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.CoursesPageLang');
	}
	
	
	
	public function execute() {
		import('wddsocial.model.WDDSocial\CourseVO');
		
		$content.= render(':section', array('section' => 'begin_content'));
		
		$sorter = \Framework5\Request::segment(1);
		$sorters = array(
			'month'          => $this->lang->text('sort-month'), 
			'alphabetically' => $this->lang->text('sort-alphabetically'));
		
		if (isset($sorter) and in_array($sorter, array_keys($sorters))) $active = $sorter;
		else $active = 'month';
		
		$content.= render(':section', 
			array('section' => 'begin_content_section', 'id' => 'directory', 
				'classes' => array('mega', 'with-secondary'), 
				'header' => $this->lang->text('page-header'), 'sort' => true, 'sorters' => $sorters, 
				'base_link' => '/courses/', 'active' => $active));
		
		$paginator = new Paginator(2,30);
		
		switch ($active) {
			case 'month':
				$orderBy = '`month` ASC';
				break;
			
			case 'alphabetically':
				$orderBy = 'title ASC';
				break;
			
			default:
				$orderBy = '`month` ASC';
				break;
		}
		
		
		# query
		$query = $this->db->prepare($this->sql->getCourses . " ORDER BY $orderBy");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\CourseVO');
		
		# display section items
		while($item = $query->fetch()){
			$content.= render('wddsocial.view.content.WDDSocial\DirectoryCourseItemView', $item);
		}
		
		$content.= render(':section', array('section' => 'end_content_section'));
		
		$content.= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
}