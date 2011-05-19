<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ResendVerificationView implements \Framework5\IView {		
	
	public function render($options = null) {
		# if success
		if ($options['success']) {
			$html = <<<HTML

						<p class="success"><strong>{$options['success']}</strong></p>
HTML;
		}
		
		# render form
		else {
			# form open tag
			$html = <<<HTML

					<form action="/resend-verification" method="post" class="small">
HTML;
		
			# error message
			if ($options['error']) {
				$html.= <<<HTML

						<p class="error"><strong>{$options['error']}</strong></p>
HTML;
			}
		
			# form
			$html.= <<<HTML
						<fieldset>
							<label for="email">Full Sail Email</label>
							<input type="email" name="email" id="email" value="{$_POST['email']}" autofocus />
						</fieldset>
						<input type="submit" name="submit" value="Resend" />
					</form>
HTML;
		}
		
		# return output
		return $html;
	}
}