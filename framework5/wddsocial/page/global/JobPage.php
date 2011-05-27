<?php

namespace WDDSocial;

/*
* Job Listing Info Page
* 
* @author: Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class JobPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.page.global.JobPageLang');
	}
	
	
	
	public function execute() {	
		
		$job = $this->getJob(\Framework5\Request::segment(1));
		
		if (!UserSession::is_authorized()) redirect("/");
		
		if (Validator::job_has_been_flagged($job->id)) redirect("/");
			
		if ($job) {
			$page_title = $job->title; # set page title
			$content = render(':section', array('section' => 'begin_content'));
			
			# display job overview
			$content .= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'job', 
					'classes' => array('large', 'with-secondary'), 'header' => $job->title));
			$content .= render('wddsocial.view.content.WDDSocial\OverviewDisplayView', $job);
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'job'));
			
			
			# display job details
			$content .= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'details', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => 'Details'));
			$content .= render('wddsocial.view.content.WDDSocial\JobDetailsDisplayView', $job);
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'details'));
			
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
			
			# display job media
			$content .= render(':section', 
				array('section' => 'begin_content_section', 'id' => 'media', 
					'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 
					'header' => 'Media'));
			$content .= render('wddsocial.view.content.WDDSocial\MediaDisplayView', array('content' => $job, 'active' => $activeMedia, 'base_link' => "/job/{$job->vanityURL}", 'type' => 'job'));
			$content .= render(':section', array('section' => 'end_content_section', 'id' => 'media'));
			$content .= render(':section', array('section' => 'end_content'));
			
		}
		
		# the job listing does not exist
		else {
			# display header
			$page_title = $this->lang->text('not-found-page-title');
			$content = render(':section', array('section' => 'begin_content'));
			
			$content .= "<h1>Job Not Found</h1>";
			$content .= render(':section', array('section' => 'end_content'));
		}
		
		# display page
		echo render(':template', 
			array('title' => $page_title, 'content' => $content));
	}
	
	
	
	/**
	* Gets the requested Job and data
	*/
	
	private function getJob($vanityURL){
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