<?php

namespace WDDSocial;

/*
* 
* 
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class EventPage implements \Framework5\IExecutable {
	
	public static function execute() {	
		
		$event = static::getEvent(\Framework5\Request::segment(1));
			
		if($event == false){
			echo render(':template', 
				array('section' => 'top', 'title' => "Event Not Found"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			echo "<h1>Event Not Found</h1>";
			echo render('wddsocial.view.WDDSocial\SectionView',
					array('section' => 'end_content'));
		}else{
			# display site header
			echo render(':template', 
				array('section' => 'top', 'title' => "{$event->title}"));
			echo render('wddsocial.view.WDDSocial\SectionView', array('section' => 'begin_content'));
			
			# display Event overview
			static::displayEventOverview($event);
			static::displayEventDetails($event);
			static::displayEventMedia($event);
			static::displayEventComments($event->comments);
			
			echo render('wddsocial.view.WDDSocial\SectionView',
					array('section' => 'end_content'));
			
		}
		
		echo render(':template', 
				array('section' => 'bottom'));
	}
	
	
	
	/**
	* Gets the requested Event and data
	*/
	
	private static function getEvent($vanityURL){
		import('wddsocial.model.WDDSocial\ContentVO');
		
		# Get db instance and query
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('vanityURL' => $vanityURL);
		$query = $db->prepare($sql->getEventByVanityURL);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\ContentVO');
		$query->execute($data);
		return $query->fetch();
	}
	
	
	
	/**
	* Gets the requested Event and data
	*/
	
	private static function displayEventOverview($event){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'event', 'classes' => array('large', 'with-secondary'), 'header' => $event->title));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'overview', 'content' => $event));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'event'));
	}
	
	
	
	/**
	* Gets the requested Event and data
	*/
	
	private static function displayEventDetails($event){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'location', 'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 'header' => 'Location and Time'));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'event_location', 'content' => $event));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'location'));
	}
	
	
	
	/**
	* Gets the requested Event and data
	*/
	
	private static function displayEventMedia($event){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'media', 'classes' => array('small', 'no-margin', 'side-sticky', 'with-secondary'), 'header' => 'Media', 'extra' => 'media_filters'));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'media', 'content' => $event, 'active' => 'images'));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'media'));
	}
	
	
	
	/**
	* Gets the requested Event and data
	*/
	
	private static function displayEventComments($comments){
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'begin_content_section', 'id' => 'comments', 'classes' => array('medium', 'with-secondary'), 'header' => 'Comments'));
		echo render('wddsocial.view.WDDSocial\ContentView', array('section' => 'comments', 'comments' => $comments));
		echo render('wddsocial.view.WDDSocial\SectionView', 
			array('section' => 'end_content_section', 'id' => 'comments'));
	}
}