<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SearchPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.SearchPageLang');
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
	}
	
	
	
	public function execute() {
		# set acceptable search types
		$types = array('people','projects','articles','courses','events','jobs');
		
		# redirect form submission
		if (isset($_POST['term']) and $_POST['term'] != '') {
			$term = urlencode($_POST['term']);
			if (isset($_POST['searchType']) and in_array($_POST['searchType'],$types)) {
				# if the last request came from any page except search results, and the search term is a category, redirect to project search results
				$last_request = explode('/',$_SESSION['last_request']);
				if ($last_request[0] != 'search') {
					switch ($last_request[0]) {
						case 'people':
							$searchType = 'people';
							break;
						case 'projects':
							$searchType = 'projects';
							break;
						case 'articles':
							$searchType = 'articles';
							break;
						case 'courses':
							$searchType = 'courses';
							break;
						case 'events':
							$searchType = 'events';
							break;
						case 'jobs':
							$searchType = 'jobs';
							break;
						default:
							$query = $this->db->prepare($this->sql->getCategoryIDByTitle);
							$query->execute(array('title' => urldecode($term)));
							if ($query->rowCount() > 0) {
								$searchType = 'projects';
							}
							else {
								$query = $this->db->prepare($this->sql->getCourseByID);
								$query->execute(array('id' => urldecode($term)));
								if ($query->rowCount() > 0) {
									$searchType = 'courses';
								}
								else {
									$searchType = 'people';
								}
							}
							break;
					}
				}
				else {
					$searchType = $_POST['searchType'];
				}
			}
			else {
				$searchType = 'people';
			}
			$term = urlencode($_POST['term']);
			redirect("/search/{$searchType}/{$term}");
		}
		
		# display search results
		else {
			$type = \Framework5\Request::segment(1);
			if (!isset($type) or $type == '' or !in_array($type, $types)) redirect('/');
			
			# get search term
			$term = urldecode(\Framework5\Request::segment(2));
			if (!isset($term) or $term == '') redirect('/');
			
			# get sorters
			$sorter = \Framework5\Request::segment(3);
			switch ($type) {
				case 'courses':
					$sorters = array(
						'month' => $this->lang->text('sort-month'),
						'alphabetically' => $this->lang->text('sort-alphabetically')
					);
					$default = 'month';
					break;
				case 'events':
					$sorters = array(
						'upcoming' => $this->lang->text('sort-upcoming'),
						'alphabetically' => $this->lang->text('sort-alphabetically'),
						'newest' => $this->lang->text('sort-newest'),
						'oldest' => $this->lang->text('sort-oldest')
					);
					$default = 'upcoming';
					break;
				case 'jobs':
					$sorters = array(
						'newest' => $this->lang->text('sort-newest'),
						'company' => $this->lang->text('sort-company'),
						'location' => $this->lang->text('sort-location')
					);
					$default = 'newest';
					break;
				default:
					$sorters = array(
						'alphabetically' => $this->lang->text('sort-alphabetically'),
						'newest' => $this->lang->text('sort-newest'),
						'oldest' => $this->lang->text('sort-oldest')
					);
					$default = 'newest';
					break;
			}
			
			# set active sorter
			if (isset($sorter) and in_array($sorter, array_keys($sorters))) $active_sorter = $sorter;
			else $active_sorter = $sorters[$default];
			
			# build content
			$content .= render(':section', array('section' => 'begin_content'));
			
			$content .= render('wddsocial.view.content.WDDSocial\SearchView', array('section' => 'mega_header', 'term' => $term));
			
			$headers = render('wddsocial.view.content.WDDSocial\SearchView', array('section' => 'headers', 'types' => $types, 'active' => $type, 'term' => $term));
			
			$content .= render(':section', array(
				'section' => 'begin_content_section',
				'id' => 'directory',
				'classes' => array('mega', 'with-secondary'),
				'header' => $headers,
				'sort' => true,
				'sorters' => $sorters,
				'active' => $active_sorter,
				'base_link' => "/search/{$type}/{$term}/"
			));
			
			
			$paginator = new Paginator(4,18);
			
			switch ($active_sorter) {
				case 'alphabetically':
					if ($type == 'people') {
						$orderBy = 'lastName ASC';
					}
					else {
						$orderBy = 'title ASC';
					}
					break;
				case 'newest':
					$orderBy = '`datetime` DESC';
					break;
				case 'oldest':
					$orderBy = '`datetime` ASC';
					break;
				case 'upcoming':
					$orderBy = 'startDateTime ASC';
					break;
				case 'month':
					$orderBy = '`month` ASC';
					break;
				case 'company':
					$orderBy = 'company ASC';
					break;
				case 'location':
					$orderBy = 'location ASC';
					break;
				default:
					if ($type == 'events') {
						$orderBy = 'title ASC';
					}
					else if ($type == 'courses') {
						$orderBy = '`month` ASC';
					}
					else {
						$orderBy = '`datetime` DESC';
					}
					break;
			}
			
			if (!UserSession::is_authorized() and $type == 'jobs') {
				$content .= render('wddsocial.view.content.WDDSocial\SignInPromptView',array('section' => 'job_search'));
				$content .= render(':section', array('section' => 'end_content_section', 'id' => 'directory'));
			}
			else {
				$results = $this->get_results($term, $type, 0, $paginator->limit, $orderBy);
				
				if (count($results) > 0) {
					switch ($type) {
						case 'people':
							foreach ($results as $person) {
								$content.= render('wddsocial.view.content.WDDSocial\DirectoryUserItemView', array('content' => $person));
							}
							break;
						case 'projects':
							foreach ($results as $project) {
								$content.= render('wddsocial.view.content.WDDSocial\DirectoryItemView', array('type' => 'project','content' => $project));
							}
							break;
						case 'articles':
							foreach ($results as $article) {
								$content.= render('wddsocial.view.content.WDDSocial\DirectoryItemView', array('type' => 'article','content' => $article));
							}
							break;
						case 'courses':
							foreach ($results as $course) {
								$content.= render('wddsocial.view.content.WDDSocial\DirectoryCourseItemView', $course);
							}
							break;
						case 'events':
							foreach ($results as $event) {
								$content.= render('wddsocial.view.content.WDDSocial\DirectoryItemView', array('type' => 'event','content' => $event));
							}
							break;
						case 'jobs':
							foreach ($results as $event) {
								$content.= render('wddsocial.view.content.WDDSocial\DirectoryItemView', array('type' => 'job','content' => $event));
							}
							break;
					}
				}
				else {
					$content .= render('wddsocial.view.content.WDDSocial\NoResults',array('type' => $type));
				}
				
				$next_results = $this->get_results($term, $type, $paginator->limit, $paginator->per, $orderBy);
				
				if (count($next_results) > 0) {
					# display section footer
					$content.= render(':section', array('section' => 'end_content_section', 'id' => 'directory', 'load_more' => "$type", 'load_more_link' => "/search/$type/$active_sorter/{$paginator->next}"));	
				}		
				else {
					# display section footer
					$content .= render(':section', array('section' => 'end_content_section', 'id' => 'directory'));
				}
			}
						
			$content .= render(':section', array('section' => 'end_content'));
			
			# display page
			echo render(':template', 
				array('title' => $this->lang->text('page-title'), 'searchTerm' => $term, 'content' => $content));
		}
	}
	
	
	
	private function get_results($term, $type, $start, $limit, $orderBy){
		switch ($type) {
			case 'people':
				import('wddsocial.model.WDDSocial\UserVO');
				$query = $this->db->prepare($this->sql->searchPeople . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\UserVO');
				return $query->fetchAll();
				break;
			case 'projects':
				import('wddsocial.model.WDDSocial\DisplayVO');
				$query = $this->db->prepare($this->sql->searchProjects . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
				return $query->fetchAll();
				break;
			case 'articles':
				import('wddsocial.model.WDDSocial\DisplayVO');
				$query = (UserSession::is_authorized())?$this->db->prepare($this->sql->searchArticles . " ORDER BY $orderBy" . " LIMIT $start, $limit"):$this->db->prepare($this->sql->searchPublicArticles . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
				return $query->fetchAll();
				break;
			case 'courses':
				import('wddsocial.model.WDDSocial\CourseVO');
				$query = $this->db->prepare($this->sql->searchCourses . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\CourseVO');
				return $query->fetchAll();
				break;
			case 'events':
				import('wddsocial.model.WDDSocial\DisplayVO');
				$query = (UserSession::is_authorized())?$this->db->prepare($this->sql->searchEvents . " ORDER BY $orderBy" . " LIMIT $start, $limit"):$this->db->prepare($this->sql->searchPublicEvents . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
				return $query->fetchAll();
				break;
			case 'jobs':
				import('wddsocial.model.WDDSocial\JobVO');
				$query = $this->db->prepare($this->sql->searchJobs . " ORDER BY $orderBy" . " LIMIT $start, $limit");
				$query->execute(array('term' => "%$term%"));
				$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
				return $query->fetchAll();
				break;
			default:
				return array();
				break;
		}
	}
}