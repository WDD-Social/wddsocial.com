<?php

namespace WDDSocial;

class ExtraView implements \Framework5\IView {		
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		
		switch ($options['type']) {
			case 'sign_in_intro':
				return $this->sign_in_intro();
			case 'sign_up_intro':
				return $this->sign_up_intro();
			case 'forgot_pass_intro':
				return $this->forgot_pass_intro();
			default:
				throw new \Framework5\Exception("ExtraView requires parameter type (sign_in_intro, or sign_up_intro), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* 
	*/
	
	private function sign_in_intro(){
		return <<<HTML

					<h1 class="mega">Welcome back, we&rsquo;ve missed you!</h1>
HTML;
	}
	
	
	
	/**
	* 
	*/
	
	private function sign_up_intro(){
		return <<<HTML

					<h1 class="mega form">Join the community. Socialize.</h1>
					<h2 class="form">* Required</h2>
HTML;
	}
	
	
	
	/**
	* 
	*/
	
	private function forgot_pass_intro(){
		return <<<HTML

					<h1 class="mega form">Forgot your password? Get a new one here.</h1>
HTML;
	}
}