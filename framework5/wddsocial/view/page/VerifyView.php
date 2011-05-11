<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class VerifyView implements \Framework5\IView {

	public function render($options = null) {
		
		switch ($options['type']) {
			case 'success':
				return $this->success();
				break;
			case 'error':
				return $this->error();
				break;
		}
	}
	
	private function success(){
		return <<<HTML

					<h1 class="mega">Awesome, you&rsquo;ve been verified! Come on in!</h1>
HTML;
	}
	
	private function error(){
		return <<<HTML

					<h1 class="mega">Uh oh, something went wrong.</h1>
					<p>The verification code was invalid. <a href="" title="Resend Verification Code">Resend verification code</a> or <a href="/contact" title="Contact WDD Social">contact us</a> if there is an issue.</p>
HTML;
	}
}