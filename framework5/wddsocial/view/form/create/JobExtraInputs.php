<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class JobExtraInputs implements \Framework5\IView {		
	
	public static function render($options = null) {
		$html .= render('wddsocial.view.form.pieces.WDDSocial\JobTypeSelector',1);
		$html .= <<<HTML

						<fieldset>
							<label for="company">Company</label>
							<input type="text" name="company" id="company" />
						</fieldset>
						<fieldset>
							<label for="location">Location</label>
							<input type="text" name="location" id="location" />
						</fieldset>
						<fieldset>
							<label for="compensation">Compensation</label>
							<input type="text" name="compensation" id="compensation" />
						</fieldset>
						<fieldset>
							<label for="company-website">Company&rsquo;s Website</label>
							<input type="text" name="company-website" id="company-website" placeholder="example.com" />
						</fieldset>
						<fieldset>
							<label for="email">Contact Email</label>
							<input type="email" name="email" id="email" placeholder="example@example.com" />
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