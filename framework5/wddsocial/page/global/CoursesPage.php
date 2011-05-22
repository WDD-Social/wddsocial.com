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
	}
	
	
	
	public function execute() {
		import('wddsocial.model.WDDSocial\CourseVO');
		
		# display site header
		$page_title = 'Courses';
		
		$content.= render(':section', array('section' => 'begin_content'));
		
		$sorter = \Framework5\Request::segment(1);
		$sorters = array('month' => 'month', 'alphabetically' => 'alphabetically');
		
		if (isset($sorter) and in_array($sorter, $sorters)) $active = $sorter;
		else $active = $sorters['month'];
		
		$content.= render(':section', 
			array('section' => 'begin_content_section', 'id' => 'directory', 
				'classes' => array('mega', 'with-secondary'), 
				'header' => 'Courses', 'sort' => true, 'sorters' => $sorters, 
				'base_link' => '/courses/', 'active' => $active));
		
		$paginator = new Paginator(2,18);
		
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
			array('title' => $page_title, 'content' => $content));
	}
}