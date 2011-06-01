<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class JobExtraInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		if (isset($options['data'])) {
			if (is_array($options['data'])) {
				$jobType = (isset($options['data']['job-type']))?$options['data']['job-type']:1;
				$companyValue = ($options['data']['company'] == '')?'':$options['data']['company'];
				$locationValue = ($options['data']['location'] == '')?'':$options['data']['location'];
				$compensationValue = ($options['data']['compensation'] == '')?'':$options['data']['compensation'];
				$websiteValue = ($options['data']['website'] == '')?'':$options['data']['website'];
				$emailValue = ($options['data']['email'] == '')?'':$options['data']['email'];
			}
			else if (is_object($options['data'])) {
				$jobType = (isset($options['data']->jobTypeID))?$options['data']->jobTypeID:1;
				$companyValue = ($options['data']->company == '')?'':$options['data']->company;
				$locationValue = ($options['data']->location == '')?'':$options['data']->location;
				$compensationValue = ($options['data']->compensation == '')?'':$options['data']->compensation;
				$websiteValue = ($options['data']->website == '')?'':$options['data']->website;
				$emailValue = ($options['data']->email == '')?'':$options['data']->email;
			}
		}
		else {
			$jobType = 1;
			$companyValue = '';
			$locationValue = '';
			$compensationValue = '';
			$websiteValue = '';
			$emailValue = '';
		}
		
		if (!isset($jobType)) $jobType = 1;
		$html .= render('wddsocial.view.form.pieces.WDDSocial\JobTypeSelector',$jobType);
		$html .= <<<HTML

						<fieldset>
							<label for="company">Company *</label>
							<input type="text" name="company" id="company" value="{$companyValue}" />
						</fieldset>
						<fieldset>
							<label for="location">Location *</label>
							<input type="text" name="location" id="location" value="{$locationValue}" />
						</fieldset>
						<fieldset>
							<label for="compensation">Compensation</label>
							<input type="text" name="compensation" id="compensation" value="{$compensationValue}" />
						</fieldset>
						<fieldset>
							<label for="website">Company&rsquo;s Website</label>
							<input type="text" name="website" id="website" placeholder="example.com" value="{$websiteValue}" />
						</fieldset>
						<fieldset>
							<label for="email">Contact Email *</label>
							<input type="email" name="email" id="email" placeholder="example@example.com" value="{$emailValue}" />
							<small>Applicants will contact this email.</small>
						</fieldset>
						<fieldset>
							<label for="company-avatar">Company&rsquo;s Logo</label>
							<input type="file" name="company-avatar" id="company-avatar" />
						</fieldset>
HTML;
		
		return $html;
	}
}