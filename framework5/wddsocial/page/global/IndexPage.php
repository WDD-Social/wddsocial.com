<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author Anthony Colangelo (me@acolangelo.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public static function execute() {
		import('wddsocial.sql.SelectorSQL');
		import('wddsocial.controller.UserValidator');
		
		# DISPLAY PAGE HEADER
		echo render('wddsocial.view.TemplateView', array('section' => 'top', 'title' => 'Connecting the Full Sail University Web Community'));
		
		$class = (\WDDSocial\UserValidator::is_authorized())?'dashboard':'start-page';
		echo <<<HTML

			<section id="content" class="$class">
HTML;
		
		# CHECK WHICH HOME PAGE TO CREATE, BASED ON AUTHORIZATION
		if(\WDDSocial\UserValidator::is_authorized()){	
			# CREATE DASHBOARD
			echo render('wddsocial.view.ShareView');
			static::get_latest();
			static::get_events();
			static::get_jobs();
		}else{
			# CREATE PUBLIC HOME PAGE
		}
		
		
		# display page footer
		# END CONTENT AREA
		echo <<<HTML

			</section><!-- END CONTENT -->
HTML;
		
		# CREATE FOOTER
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
	}
	
	# GETS AND DISPLAYS LATEST CONTENT SECTION
	private static function get_latest(){
		import('wddsocial.model.DisplayVO');
		# GET DB INSTANCE AND QUERY
		$db = instance(':db');
		$sql = new SelectorSQL();
		$query = $db->query($sql->getLatest);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		# CREATE SECTION HEADER
		echo <<<HTML

				<section id="latest" class="medium with-secondary filterable">
					<h1>Latest</h1>
					<div class="secondary filters">
						<a href="dashboard.html#all" title="All Latest Activity" class="current">All</a> 
						<a href="dashboard.html#people" title="Latest People">People</a> 
						<a href="dashboard.html#projects" title="Latest Projects">Projects</a> 
						<a href="dashboard.html#articles" title="Latest Articles">Articles</a>
					</div><!-- END SECONDARY -->
HTML;
		
		# CREATE SECTION ITEMS
		while($row = $query->fetch()){
			echo render('wddsocial.view.MediumDisplayView', array('type' => $row->type,'content' => $row));
		}
		
		# CREATE SECTION FOOTER
		echo <<<HTML

					<p class="load-more"><a href="#" title="Load more posts...">Load More</a></p>
				</section><!-- END LATEST -->
				
HTML;
	}
	
	# GETS AND DISPLAYS EVENTS
	private static function get_events(){
		import('wddsocial.model.EventVO');
		
		# GET DB INSTANCE AND QUERY
		$db = instance(':db');
		$sql = new SelectorSQL();
		$query = (\WDDSocial\UserValidator::is_authorized())?$db->query($sql->getUpcomingEvents):$db->query($sql->getUpcomingPublicEvents);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\EventVO');
		
		if(\WDDSocial\UserValidator::is_authorized()){
			echo <<<HTML

				<section id="events" class="small no-margin side-sticky">
					<h1>Events</h1>
HTML;
			# SET LIMIT OF POSTS
			$limit = 3;
		}else{
			echo <<<HTML

				<section id="events" class="small no-margin slider">
					<h1>Events</h1>
					<div class="slider-controls"><a href="#" title="Featured Events 1" class="current">1</a> <a href="#" title="Featured Events 2">2</a> <a href="#" title="Featured Events 3">3</a> <a href="#" title="Featured Events 4">4</a> <a href="#" title="Featured Events 5">5</a></div>
HTML;
			# SET LIMIT OF POSTS
			$limit = 2;
		}		
		
		# CREATE SECTION ITEMS
		$row = $query->fetchAll();
		for($i = 0; $i<$limit; $i++){
			echo render('wddsocial.view.SmallDisplayView', array('type' => $row[$i]->type,'content' => $row[$i]));
		}
		
		# CREATE SECTION FOOTER
		echo <<<HTML

				</section><!-- END EVENTS -->			
HTML;
	}
	
	# GETS AND DISPLAYS JOBS
	private static function get_jobs(){
		import('wddsocial.model.JobVO');
		
		# GET DB INSTANCE AND QUERY
		$db = instance(':db');
		$sql = new SelectorSQL();
		$query = $db->query($sql->getNewJobs);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\JobVO');
		
		echo <<<HTML

				<section id="jobs" class="small no-margin side-sticky">
					<h1>Jobs</h1>
HTML;
		
		# CREATE SECTION ITEMS
		while($row = $query->fetch()){
			echo render('wddsocial.view.SmallDisplayView', array('type' => $row->type,'content' => $row));
		}
		
		# CREATE SECTION FOOTER
		echo <<<HTML

				</section><!-- END JOBS -->			
HTML;
	}
}