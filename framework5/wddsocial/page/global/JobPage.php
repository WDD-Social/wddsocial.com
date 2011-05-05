<?php

namespace WDDSocial;

/*
* Job Listing Info Page
* 
* @author: Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class JobPage implements \Framework5\IExecutable {
	
	public static function execute() {	
		
		$job = static::getJob(\Framework5\Request::segment(1));
			
		if ($job) {
			# display site header
			echo render(':template', array('section' => 'top', 'title' => "{$job->title}"));
			echo render(':section', array('section' => 'begin_content'));
			
			# display job overview
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'job', 
					'classes' => array('large', 'with-secondary'), 'header' => $job->title));
			echo render('wddsocial.view.content.WDDSocial\OverviewDisplayView', $job);
			echo render(':section', array('section' => 'end_content_section', 'id' => 'job'));
			
			
			# display job details
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'details', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => 'Details'));
			echo render('wddsocial.view.content.WDDSocial\JobDetailsDisplayView', $job);
			echo render(':section', array('section' => 'end_content_section', 'id' => 'details'));
			
			
			# display job media
			echo render(':section', 
				array('section' => 'begin_content_section', 'id' => 'media', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => 'Media', 'extra' => 'media_filters'));
			echo render('wddsocial.view.WDDSocial\ContentView', 
				array('section' => 'media', 'content' => $job, 'active' => 'images'));
			echo render(':section', array('section' => 'end_content_section', 'id' => 'media'));
			
		}
		
		# the job listing does not exiost
		else {
			# display header
			echo render(':template', array('section' => 'top', 'title' => "Job Not Found"));
			echo render(':section', array('section' => 'begin_content'));
			
			echo "<h1>Job Not Found</h1>";
		}
		
		echo render(':section', array('section' => 'end_content'));
		echo render(':template', array('section' => 'bottom'));
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
}