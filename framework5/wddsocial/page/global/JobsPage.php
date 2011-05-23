<?php

namespace WDDSocial;

/*
*
* @author Anthony Colangelo (me@acolangelo.com) 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class JobsPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.JobsPageLang');
	}
	
	
	
	public function execute() {
		import('wddsocial.model.WDDSocial\JobVO');
		
		$content = render(':section', array('section' => 'begin_content'));
		
		# job types filter
		$type = \Framework5\Request::segment(1);
		$job_types = array(
			'all'        => $this->lang->text('filter-all'),
			'full-time'  => $this->lang->text('filter-full-time'),
			'part-time'  => $this->lang->text('filter-part-time'),
			'contract'   => $this->lang->text('filter-contract'),
			'freelance'  => $this->lang->text('filter-freelance'),
			'internship' => $this->lang->text('filter-internship'));
		
		# if type is not set
		if (!isset($type) or empty($type)) $type = 'all';
		
		# if invalid job type
		if (!in_array($type, array_keys($job_types))) redirect('/');
		
		# job sorters
		$sorter = \Framework5\Request::segment(2);
		$sorters = array(
			'newest'   => $this->lang->text('sort-newest'),
			'company'  => $this->lang->text('sort-company'),
			'location' => $this->lang->text('sort-location'));
		
		if (isset($sorter) and in_array($sorter, array_keys($sorters))) 
			$active_sorter = $sorter;
		else $active_sorter = array_shift(array_keys($sorters));
		
		
		$headers = render('wddsocial.view.content.WDDSocial\JobsPageHeaderView', 
			array('types' => $job_types, 'active' => $type, 'sorter' => $active_sorter));
		
		
		$content.= render(':section', 
			array('section' => 'begin_content_section', 'id' => 'directory', 
				'classes' => array('mega', 'with-secondary'), 
				'header' => $headers, 'sort' => true, 
				'sorters' => $sorters, 'base_link' => "/jobs/{$type}/", 'active' => $active_sorter));
		
		
		if (!UserSession::is_authorized()) {
			$content .= render('wddsocial.view.content.WDDSocial\SignInPromptView', array('section' => 'jobs'));
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'directory'));
		}
		
		
		else {
			$paginator = new Paginator(3,18);
			
			$where = $this->query_type($type);
			$orderBy = $this->query_sorter($active_sorter);
			
			# get jobs
			$query = $this->db->prepare(
				$this->sql->getJobs . "$where ORDER BY $orderBy LIMIT 0, {$paginator->limit}");
			$query->execute();
			$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
			
			if ($query->rowCount() > 0) {
				# display section items
				while($item = $query->fetch()){
					$content.= render('wddsocial.view.content.WDDSocial\DirectoryItemView', 
						array('type' => 'job','content' => $item));
				}
			}
			
			else {
				$content .= render('wddsocial.view.content.WDDSocial\NoJobs', array('type' => $type));
			}
			
			
			$query = $this->db->prepare(
				$this->sql->getJobs . "$where ORDER BY $orderBy LIMIT {$paginator->limit}, {$paginator->per}");
			$query->execute();
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$query->fetch();
			
			
			if ($query->rowCount() > 0) {
				# display section footer
				$content.= render(':section',
					array('section' => 'end_content_section', 'id' => 'directory', 'load_more' => 'jobs', 'load_more_link' => "/jobs/{$active}/{$paginator->next}"));	
			}
			
			else {
				# display section footer
				$content.= render(':section',
					array('section' => 'end_content_section', 'id' => 'directory'));
			}
		}
		
		
				
		$content .= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $this->lang->text('page-title'), 'content' => $content));
	}
	
	
	
	/**
	* 
	*/
	
	private function query_type($type) {
		switch ($type) {
			case 'all':
				return '';
			
			case 'full-time':
				return " AND jobType = 'full-time'";
			
			case 'part-time':
				return " AND jobType = 'part-time'";
			
			case 'contract':
				return " AND jobType = 'contract'";
			
			case 'freelance':
				return " AND jobType = 'freelance'";
			
			case 'internship':
				return " AND jobType = 'internship'";
			
			default:
				return '';
			
		}
	}
	
	
	
	/**
	* 
	*/
	
	private function query_sorter($active_sorter) {
		switch ($active_sorter) {
			case 'newest':
				return '`datetime` DESC';
			
			case 'company':
				return 'company ASC';
			
			case 'location':
				return 'location ASC';
			
			default:
				return '`datetime` DESC';
		}
	}
}