<?php

namespace WDDSocial;

/*
* 
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ChangeFullsailEmailView implements \Framework5\IView {
	
	
	public function __construct() {
		//$this->lang = new \Framework5\Lang('wddsocial.lang.view.global.ViewLang');
	}
	
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		$html = <<<HTML

					<form action="/change-fullsail-email" method="post" class="small">
HTML;
		# if success
		if ($options['success']) {
			$html.= <<<HTML

						<p class="success"><strong>{$options['success']}</strong></p>
HTML;
		}
		
		# if error
		if ($options['error']) {
			$html.= <<<HTML

						<p class="error"><strong>{$options['error']}</strong></p>
HTML;
		}
		
		# form
		$html.= <<<HTML
						<fieldset>
							<label for="email">Primary Email</label>
							<input type="email" name="email" id="email" value="{$_POST['email']}" autofocus />
						</fieldset>
						
						<fieldset>
							<label for="fs-email">Full Sail Email</label>
							<input type="email" name="fs-email" id="fs-email" value="{$_POST['fs-email']}" autofocus />
						</fieldset>
						
						<fieldset>
							<label for="password" class="helper-link">Password</label>
							<input type="password" name="password" id="password" />
						</fieldset>
						
						<input type="submit" name="submit" value="Sign In" />
					</form>
HTML;
		
		# return output
		return $html;
	}
}