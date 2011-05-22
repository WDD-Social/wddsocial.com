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
		
		$types = array('all','full-time','part-time','contract','freelance','internship');
		$type = \Framework5\Request::segment(1);
		if (!isset($type) or $type == '') $type = 'all';
		if (!in_array($type, $types)) redirect('/');
		
		$sorter = \Framework5\Request::segment(2);
		$sorters = array(
			'newest' => $this->lang->text('sort-newest'),
			'company' => $this->lang->text('sort-company'),
			'location' => $this->lang->text('sort-location'));
		
		if (isset($sorter) and in_array($sorter, array_keys($sorters))) $active_sorter = $sorter;
		else $active_sorter = $sorters['newest'];
		
		$headers = render('wddsocial.view.content.WDDSocial\JobsPageHeaderView', array('types' => $types, 'active' => $type, 'sorter' => $active_sorter));
		
		$content.= render(':section', 
			array('section' => 'begin_content_section', 'id' => 'directory', 
				'classes' => array('mega', 'with-secondary'), 
				'header' => $headers, 'sort' => true, 
				'sorters' => $sorters, 'base_link' => "/jobs/{$type}/", 'active' => $active_sorter));
		
		if (!UserSession::is_authorized()) {
			$content .= render('wddsocial.view.content.WDDSocial\SignInPromptView',array('section' => 'jobs'));
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'directory'));
		}
		else {
			$paginator = new Paginator(3,18);
			
			switch ($type) {
				case 'all':
					$where =  '';
					break;
				case 'full-time':
					$where = " AND jobType = 'full-time'";
					break;
				case 'part-time':
					$where = " AND jobType = 'part-time'";
					break;
				case 'contract':
					$where = " AND jobType = 'contract'";
					break;
				case 'freelance':
					$where = " AND jobType = 'freelance'";
					break;
				case 'internship':
					$where = " AND jobType = 'internship'";
					break;
				default:
					$where = '';
					break;
			}
			
			switch ($active_sorter) {
				case 'newest':
					$orderBy = '`datetime` DESC';
					break;
				
				case 'company':
					$orderBy = 'company ASC';
					break;
				
				case 'location':
					$orderBy = 'location ASC';
					break;
				
				default:
					$orderBy = '`datetime` DESC';
					break;
			}
			
			# query
			$query = $this->db->prepare($this->sql->getJobs . "$where ORDER BY $orderBy LIMIT 0, {$paginator->limit}");
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
			
			$query = $this->db->prepare($this->sql->getJobs . "$where ORDER BY $orderBy LIMIT {$paginator->limit}, {$paginator->per}");
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
}