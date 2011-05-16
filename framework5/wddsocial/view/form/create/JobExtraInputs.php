<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class JobExtraInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$jobType = (isset($options['data']))?$options['data']->jobTypeID:1;
		if (!isset($jobType))
			$jobType = 1;
		$html .= render('wddsocial.view.form.pieces.WDDSocial\JobTypeSelector',$jobType);
		$html .= <<<HTML

						<fieldset>
							<label for="company">Company *</label>
							<input type="text" name="company" id="company" value="{$options['data']->company}" />
						</fieldset>
						<fieldset>
							<label for="location">Location *</label>
							<input type="text" name="location" id="location" value="{$options['data']->location}" />
						</fieldset>
						<fieldset>
							<label for="compensation">Compensation</label>
							<input type="text" name="compensation" id="compensation" value="{$options['data']->compensation}" />
						</fieldset>
						<fieldset>
							<label for="website">Company&rsquo;s Website</label>
							<input type="text" name="website" id="website" placeholder="example.com" value="{$options['data']->website}" />
						</fieldset>
						<fieldset>
							<label for="email">Contact Email *</label>
							<input type="email" name="email" id="email" placeholder="example@example.com" value="{$options['data']->email}" />
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