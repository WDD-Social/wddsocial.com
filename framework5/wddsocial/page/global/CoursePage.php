<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class CoursePage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.CoursePageLang');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
	}
	
	
	
	public function execute() {
		
		$course = $this->_get_course(\Framework5\Request::segment(1));
		
		$content .= render(':section', array('section' => 'begin_content'));
		
		if ($course) {
			$content .= render(':section', array('section' => 'begin_content_section', 'id' => 'course', 'classes' => array('large'), 'header' => $course->title));
			
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'course'));	
		}
		else {
			$content .= render(':section', array('section' => 'begin_content_section', 'id' => 'course', 'classes' => array('large'), 'header' => 'Course not found'));
			
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'course'));
		}
		
		$content .= render(':section', array('section' => 'end_content'));
		
		echo render(':template', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
	
	
	
	/**
	* Gets the course and data
	*/
	
	private function _get_course($id){
		
		import('wddsocial.model.WDDSocial\CourseVO');
		
		# query
		$data = array('id' => $id);
		$query = $this->db->prepare($this->sql->getCourseByID);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\CourseVO');
		$query->execute($data);
		return $query->fetch();
	}
}