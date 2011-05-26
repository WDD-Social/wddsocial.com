<?php

namespace WDDSocial;

/*
* Project Info Page
* 
* @author: Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ProjectPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.ProjectPageLang');
	}
	
	
	
	public function execute() {
		
		# get project details
		$project = $this->getProject(\Framework5\Request::segment(1));
		
		if (Validator::project_has_been_flagged($project->id)) redirect("/");
		
		# handle form submission
		if (isset($_POST['submit'])){
			$response = $this->_process_form($project->id,$project->type);
			
			if ($response->status) {
				$project = null;
				$project = $this->getProject(\Framework5\Request::segment(1));
			}
		}
		
		# if the project does not exist
		if ($project) {
			
			# display site header
			$page_title = $project->title;
			$content = render(':section', array('section' => 'begin_content'));
			
			# display project overview
			$content .= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'project', 
					'classes' => array('large', 'with-secondary'), 'header' => $project->title));
			$content .= render('wddsocial.view.content.WDDSocial\OverviewDisplayView', $project);
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'project'));
			
			
			# display prject team
			$content .= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'team', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => 'Team'));
			$content .= render('wddsocial.view.content.WDDSocial\MembersDisplayView', $project);
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'team'));
			
			$media = \Framework5\Request::segment(2);
			if (isset($media) and $media != '') {
				switch ($media) {
					case 'images':
						$activeMedia = 'images';
						break;
					case 'videos':
						$activeMedia = 'videos';
						break;
					default:
						$activeMedia = 'images';
						break;
				}
			}
			else {
				$activeMedia = 'images';
			}
			
			# display project media
			$content .= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'media', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => 'Media'));
			$content .= render('wddsocial.view.content.WDDSocial\MediaDisplayView', 
				array('content' => $project, 'active' => $activeMedia, 'base_link' => "/project/{$project->vanityURL}"));
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'media'));
			
			
			# display project comments
			$content .= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'comments', 
					'classes' => array('medium', 'with-secondary'), 'header' => 'Comments'));
			$content .= render('wddsocial.view.content.WDDSocial\CommentDisplayView', $project->comments);
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'comments'));
		}
		
		else {
			
			$page_title = $this->lang->text('not-found-page-title'); # set page title
			
			$content = render(':section', array('section' => 'begin_content'));
			$content .= "<h1>Project Not Found</h1>";
		}
		
		$content .= render(':section', array('section' => 'end_content'));
		
		# display page
		echo render(':template', 
			array('title' => $page_title, 'content' => $content));
	}
	
	
	
	/**
	* Gets the requested project and data
	*/
	
	private function getProject($vanityURL){
		import('wddsocial.model.WDDSocial\ContentVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('vanityURL' => $vanityURL);
		$query = $db->prepare($sql->getProjectByVanityURL);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
		$query->execute($data);
		return $query->fetch();
	}
	
	
	
	/**
	* Handle comment addition
	*/
	
	private function _process_form($projectID,$contentType) {
		import('wddsocial.model.WDDSocial\FormResponse');
		import('wddsocial.controller.processes.WDDSocial\CommentProcessor');
		CommentProcessor::add_comment($_POST['content'],$projectID,$contentType);
		return new FormResponse(true);
	}
}