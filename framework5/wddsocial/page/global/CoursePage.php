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
			
			$content .= render('wddsocial.view.content.WDDSocial\CourseOverviewView', $course);
			
			$content.= render(':section', array('section' => 'end_content_section', 'id' => 'course'));	
			
			# translate and natural language
			if (count($course->team) > 1 || count($course->team) < 1)
				$teacher_header = 'teachers';
			else $teacher_header = 'teacher';
			
			# display course teachers
			$content.= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'teachers', 'classes' => array('small', 'no-margin', 'side-sticky'), 'header' => $teacher_header));
					
			$content.= render('wddsocial.view.content.WDDSocial\MembersDisplayView', $course);
			
			$content.= render(':section', array('section' => 'end_content_section', 'id' => 'teachers'));
			
			# display section header
			$content .= render(':section',
				array('section' => 'begin_content_section', 'id' => 'latest',
					'classes' => array('medium', 'with-secondary', 'filterable'),
					'header' => 'Latest', 'extra' => 'course_latest_filters'));
			
			$paginator = new Paginator(2,20);
			
			# display section items
			$activity = $this->_get_course_latest($course->id, 0, $paginator->limit);
			if (count($activity) > 0) {
				foreach ($activity as $item) {
					$content .= render('wddsocial.view.content.WDDSocial\MediumDisplayView', 
						array('type' => $item->type,'content' => $item));
				}
				$next = $this->_get_course_latest($course->id, $paginator->limit, 20);
			}
			else {
				$content .= render('wddsocial.view.content.WDDSocial\NoPosts');
				$next = array();
			}
			
			
			if (count($next) > 0) {
				# display section footer
				$content .= render(':section',
					array('section' => 'end_content_section', 'id' => 'latest', 'load_more' => 'posts', 'load_more_link' => "/course/{$course->id}/{$paginator->next}"));	
			}
			
			else {
				# display section footer
				$content .= render(':section',
					array('section' => 'end_content_section', 'id' => 'latest'));	
			}
			
			$content .= render(':section',
				array('section' => 'begin_content_section',
				'id' => 'events',
				'classes' => array('small', 'no-margin', 'side-sticky'),
				'header' => $this->lang->text('events-header'))
			);	
			
			import('wddsocial.model.WDDSocial\EventVO');
			# query
			$limit = 3;
			if (UserSession::is_authorized()) $query = $this->db->prepare($this->sql->getCourseEvents . " LIMIT 0, $limit");
			else $query = $this->db->prepare($this->sql->getCoursePublicEvents . " LIMIT 0, $limit");
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\EventVO');
			$query->execute(array('id' => $course->id));	
			
			if ($query->rowCount() > 0) {
				while ($event = $query->fetch()) {
					$content .= render('wddsocial.view.content.WDDSocial\SmallDisplayView', array('type' => $event->type,'content' => $event));
				}
			}
			else {
				$content .= render('wddsocial.view.content.WDDSocial\SmallDisplayEmptyView',array('type' => 'events'));
			}
			
			# create section footer
			$content .= render(':section', 
				array('section' => 'end_content_section', 'id' => 'events'));
		}
		else {
			redirect('/404');
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
	
	
	
	/**
	* Gets latest activity relating to user
	*/
	
	private function _get_course_latest($id, $start = 0, $limit = 20){
		
		import('wddsocial.model.WDDSocial\DisplayVO');
		
		# query
		$data = array('id' => $id);
		
		if (UserSession::is_authorized()) {
			$query = $this->db->prepare($this->sql->getCourseLatest . " LIMIT $start, $limit");
		}
		else {
			$query = $this->db->prepare($this->sql->getCoursePublicLatest . " LIMIT $start, $limit");
		}
		
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		$query->execute($data);
		return $query->fetchAll();
	}
}