<?php

namespace WDDSocial;

/*
* Displays the overview of a project, article, or job listing.
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class CourseOverviewView implements \Framework5\IView {
	
	public function render($content = null) {

		$lang = new \Framework5\Lang('wddsocial.lang.view.content.DisplayViewLang');
		
		$html .= <<<HTML

					<div class="large">
					</div>
HTML;
	}
}