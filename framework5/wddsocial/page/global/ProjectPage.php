<?php

namespace WDDSocial;

/*
* Project Info Page
* 
* @author: Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ProjectPage implements \Framework5\IExecutable {
	
	public function execute() {	
		
		# get project details
		$project = $this->getProject(\Framework5\Request::segment(1));
		
		# if the project does not exist
		if ($project) {
			# display site header
			echo render(':template', array('section' => 'top', 'title' => "{$project->title}"));
			echo render(':section', array('section' => 'begin_content'));
			
			# display project overview
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'project', 
					'classes' => array('large', 'with-secondary'), 'header' => $project->title));
			echo render('wddsocial.view.content.WDDSocial\OverviewDisplayView', $project);
			echo render(':section', array('section' => 'end_content_section', 'id' => 'project'));
			
			
			# display prject team
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'team', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => 'Team'));
			echo render('wddsocial.view.content.WDDSocial\MembersDisplayView', $project);
			echo render(':section', array('section' => 'end_content_section', 'id' => 'team'));
			
			
			# display project media
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'media', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => 'Media', 'extra' => 'media_filters'));
			echo render('wddsocial.view.content.WDDSocial\MediaDisplayView', 
				array('content' => $project, 'active' => 'images'));
			echo render(':section', array('section' => 'end_content_section', 'id' => 'media'));
			
			
			# display project comments
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'comments', 
					'classes' => array('medium', 'with-secondary'), 'header' => 'Comments'));
			echo render('wddsocial.view.content.WDDSocial\CommentDisplayView', $project->comments);
			echo render(':section', array('section' => 'end_content_section', 'id' => 'comments'));
			
		}
		
		
		else {
			# display site header
			echo render(':template', array('section' => 'top', 'title' => "Project Not Found"));
			echo render(':section', array('section' => 'begin_content'));
			
			# display project not found view
			echo "<h1>Project Not Found</h1>";
		}
		
		# display page footer
		echo render(':section', array('section' => 'end_content'));
		echo render(':template', array('section' => 'bottom'));
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
}