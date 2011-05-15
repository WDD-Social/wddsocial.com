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
		
		$lang = new \Framework5\Lang('wddsocial.lang.view.content.DisplayViewLang');
		
		$companyLink = ($content->website == '')?"http://google.com/?q={$content->company}":"http://{$content->website}";
		$jobAvatar = (file_exists("images/jobs/{$content->avatar}_medium.jpg"))?"/images/jobs/{$content->avatar}_medium.jpg":"/images/site/job-default_medium.jpg";
		
		$localJobType = $lang->text('jobtype', $content->jobType);
		
		if ($content->jobType == 'Internship') {
			$jobType = $lang->text('job_type_intern', $localJobType);
		}
		else {
			$jobType = $lang->text('job_type_gig', $localJobType);
		}
		
		# output
		$html = "";
		if (UserSession::is_current($content->userID)) {
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="/edit/job/{$content->vanityURL}" title="{$lang->text('edit_job_title', array('title' => $content->title, 'company' => $content->company))}" class="edit">{$lang->text('edit')}</a>
					</div><!-- END SECONDARY -->
HTML;
		}
			
		$html .= <<<HTML

						<h2><a href="$companyLink" title="{$content->company}">{$content->company}</a></h2>
						<p><a href="http://maps.google.com/?q={$content->location}" title="{$lang->text('search_maps', $content->location)}">{$content->location}</a></p>
						<p>{$lang->text('jobtype_display', $jobType)}</p>
HTML;
		if ($content->compensation != '') {
			$html .= <<<HTML

						<p>{$lang->text('compensation_display', $content->compensation)}</p>
HTML;
		}
		return $html;
	}
}