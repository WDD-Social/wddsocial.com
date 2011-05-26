<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SignInPromptView implements \Framework5\IView {
	
	public function render($options = null) {
		# retrieve content based on the provided section
		switch ($options['section']) {
			case 'jobs':
				return $this->jobs();
			case 'job_search':
				return $this->job_search();
			default:
				throw new Exception("SignInPromptView requires parameter section (jobs, job_search), '{$options['section']}' provided");
		}
	}
	
	private function jobs(){
		return <<<HTML

					<p><strong>You must be signed in to view our job postings.</strong> Would you like to <a href="/signin" title="Sign In to WDD Social">sign in now?</a></p>
					<p>Or, you could <a href="/postajob" title="WDD Social | Post a Job">post a job</a> of your own.</p>
HTML;
	}
	
	private function job_search(){
		return <<<HTML

					<p><strong>You must be signed in to search through our job postings.</strong> Would you like to <a href="/signin" title="Sign In to WDD Social">sign in now?</a></p>
					<p>Or, you could <a href="/postajob" title="WDD Social | Post a Job">post a job</a> of your own.</p>
HTML;
	}
}