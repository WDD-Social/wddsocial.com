<?php

namespace WDDSocial;

/*
* 
* 
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class JobPage implements \Framework5\IExecutable {
	
	public static function execute() {	
		
		$job = static::getJob(\Framework5\Request::segment(1));
			
		if($job == false){
			echo render(':template', 
				array('section' => 'top', 'title' => "Job Not Found"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			echo "<h1>Job Not Found</h1>";
			echo render('wddsocial.view.WDDSocial\SectionView',
					array('section' => 'end_content'));
		}else{
			# display site header
			echo render(':template', 
				array('section' => 'top', 'title' => "{$job->title}"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			
			# display Job overview
			static::displayJobOverview($job);
			static::displayJobDetails($job);
			static::displayJobMedia($job);
			
			echo render('wddsocial.view.WDDSocial\SectionView',
					array('section' => 'end_content'));
			
		}
		
		echo render(':template', 
				array('section' => 'bottom'));
	}
	
	
	
	/**
	* Gets the requested Job and data
	*/
	
	private static function getJob($vanityURL){
		import('wddsocial.model.WDDSocial\ContentVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('vanityURL' => $vanityURL);
		$query = $db->prepare($sql->getJobByVanityURL);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
		$query->execute($data);
		return $query->fetch();
	}
	
	
	
	/**
	* Gets the requested Job and data
	*/
	
	private static function displayJobOverview($job){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'Job', 'classes' => array('large', 'with-secondary'), 'header' => $job->title));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'overview', 'content' => $job));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'Job'));
	}
	
	
	
	/**
	* Gets the requested Job and data
	*/
	
	private static function displayJobDetails($job){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'details', 'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 'header' => 'Details'));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'job_details', 'content' => $job));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'details'));
	}
	
	
	
	/**
	* Gets the requested Job and data
	*/
	
	private static function displayJobMedia($job){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'media', 'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 'header' => 'Media', 'extra' => 'media_filters'));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'media', 'content' => $job, 'active' => 'images'));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'media'));
	}
}