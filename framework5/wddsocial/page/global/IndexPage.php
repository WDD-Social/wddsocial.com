<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public static function execute() {
		
		# display page header
		echo render('wddsocial.view.TemplateView', 
			array('section' => 'top', 'title' => 'Connecting the Full Sail University Web Community'));
		
		
		$db = instance(':db');
		
		import('wddsocial.sql.SelectorSQL');
		import('wddsocial.model.DisplayVO');
		
		
		
		
		// CREATE HOME PAGE (BASED ON IF USER IS SIGNED IN OR NOT)
		if($_SESSION['authorized'] == true){
			echo <<<HTML

			<section id="content" class="start-page">
HTML;
			echo render('wddsocial.view.ShareView');
			static::get_latest();
		}else{
			echo <<<HTML

			<section id="content" class="dashboard">
HTML;
		}
		
		
		# display page footer
		// END CONTENT AREA
		echo <<<HTML

			</section><!-- END CONTENT -->
HTML;
		
		// CREATE FOOTER
		echo render('wddsocial.view.TemplateView', array('section' => 'bottom'));
	}
	
	
	
	
	// GETS AND DISPLAYS LATEST CONTENT SECTION
	private static function get_latest(){
		// GET DB INSTANCE AND QUERY
		$db = instance(':db');
		$sql = new SelectorSQL();
		$query = $db->query($sql->getLatest);
		$query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\DisplayVO');
		
		// CREATE SECTION HEADER
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
		
		// CREATE SECTION ITEMS
		while($row = $query->fetch()){
			echo render('wddsocial.view.MediumDisplayView', array('type' => $row->type,'content' => $row));
		}
		
		// CREATE SECTION FOOTER
		echo <<<HTML

					<p class="load-more"><a href="#" title="Load more posts...">Load More</a></p>
				</section><!-- END LATEST -->
HTML;
	}
}