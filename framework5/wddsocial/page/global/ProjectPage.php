<?php

namespace WDDSocial;

/*
* 
* 
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class ProjectPage implements \Framework5\IExecutable {
	
	public static function execute() {	
		
		$project = static::getProject(\Framework5\Request::segment(1));
			
		if($project == false){
			echo render(':template', 
				array('section' => 'top', 'title' => "User Not Found"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			echo "<h1>Project Not Found</h1>";
			echo render('wddsocial.view.WDDSocial\SectionView',
					array('section' => 'end_content'));
		}else{
			# display site header
			echo render(':template', 
				array('section' => 'top', 'title' => "{$project->title}"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			
			# display project overview
			static::displayProjectOverview($project);
			static::displayProjectTeam($project);
			static::displayProjectMedia($project);
			static::displayProjectComments($project->comments);
			
			echo render('wddsocial.view.WDDSocial\SectionView',
					array('section' => 'end_content'));
			
		}
		
		echo render(':template', 
				array('section' => 'bottom'));
	}
	
	
	
	/**
	* Gets the requested project and data
	*/
	
	private static function getProject($vanityURL){
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
	* Gets the requested project and data
	*/
	
	private static function displayProjectOverview($project){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'project', 'classes' => array('large', 'with-secondary'), 'header' => $project->title));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'overview', 'content' => $project));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'project'));
	}
	
	
	
	/**
	* Gets the requested project and data
	*/
	
	private static function displayProjectTeam($project){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'team', 'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 'header' => 'Team'));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'members', 'content' => $project));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'team'));
	}
	
	
	
	/**
	* Gets the requested project and data
	*/
	
	private static function displayProjectMedia($project){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'media', 'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 'header' => 'Media', 'extra' => 'media_filters'));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'media', 'content' => $project, 'active' => 'images'));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'media'));
	}
	
	
	
	/**
	* Gets the requested project and data
	*/
	
	private static function displayProjectComments($comments){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'comments', 'classes' => array('medium', 'with-secondary'), 'header' => 'Comments'));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'comments', 'comments' => $comments));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'comments'));
	}
}