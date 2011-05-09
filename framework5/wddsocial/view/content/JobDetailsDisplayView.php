<?php

namespace WDDSocial;

/*
* Displays the details of a job listing
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class JobDetailsDisplayView implements \Framework5\IView {
	
	public function render($content = null) {
		$companyLink = ($content->website == '')?"http://google.com/?q={$content->company}":"http://{$content->website}";
		$jobAvatar = (file_exists("images/jobs/{$content->avatar}_medium.jpg"))?"/images/jobs/{$content->avatar}_medium.jpg":"/images/site/job-default_medium.jpg";
		
		$html = "";
		if ($content->jobType == 'Internship') {
			$jobType = "an <strong><a href=\"/jobs\" title=\"{$content->jobType} Jobs\">{$content->jobType}</a></strong>";
		}
		else {
			$jobType = "a <strong><a href=\"/jobs\" title=\"{$content->jobType} Jobs\">{$content->jobType}</a></strong> gig";
		}
		
		if (UserSession::is_current($content->userID)) {
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="/" title="Edit {$content->title} at {$content->company} Details" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		$html .= <<<HTML

						<h2><a href="$companyLink" title="{$content->company}">{$content->company}</a></h2>
						<p><a href="http://maps.google.com/?q={$content->location}" title="Search Google Maps for {$content->location}">{$content->location}</a></p>
						<p>This job is {$jobType}.</p>
HTML;
		if ($content->compensation != '') {
			$html .= <<<HTML

						<p>Compensation is <strong>{$content->compensation}</strong></p>
HTML;
		}
		return $html;
	}
}