<?php

namespace WDDSocial;

/*
* Displays the details of a job listing
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class JobDetailsDisplayView implements \Framework5\IView {
	
	public static function render($content = null) {
		
		$root = \Framework5\Request::root_path();
		$html = "";
		if ($content->jobType == 'Internship') {
			$jobType = "an <strong><a href=\"{$root}jobs\" title=\"{$content->jobType} Jobs\">{$content->jobType}</a></strong>";
		}
		else {
			$jobType = "a <strong><a href=\"{$root}jobs\" title=\"{$content->jobType} Jobs\">{$content->jobType}</a></strong> gig";
		}
		
		if (UserSession::is_current($content->userID)) {
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit {$content->title} at {$content->company} Details" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		$html .= <<<HTML

					<article class="with-secondary">
HTML;
			
		$html .= <<<HTML

						<p class="item-image"><a href="http://{$content->website}" title="{$content->company}"><img src="{$root}images/jobs/{$content->avatar}_medium.jpg" alt="{$content->company}"/></a></p>
						<h2><a href="http://{$content->website}" title="{$content->company}">{$content->company}</a></h2>
						<p><a href="http://maps.google.com/?q={$content->location}" title="Search Google Maps for {$content->location}">{$content->location}</a></p>
						<p>This job is {$jobType}.</p>
HTML;
		if ($content->compensation != '') {
			$html .= <<<HTML

						<p>Compensation is <strong>{$content->compensation}</strong></p>
HTML;
		}
		$html .= <<<HTML
					</article>
HTML;
		return $html;
	}
}