<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SmallDisplayEmptyView implements \Framework5\IView {	
	
	public function __construct() {
		
	}
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		switch ($options['type']) {
			case 'articles':
				return $this->empty_articles();
			case 'events':
				return $this->empty_events();
			case 'jobs':
				return $this->empty_jobs();
			default:
				throw new Exception("SmallDisplayEmptyView requires parameter type (articles, events, or jobs), '{$options['type']}' provided");
		}
	}
	
	private function empty_articles(){
		return <<<HTML

					<p class="empty">Looks like no one wants to share anything.</p>
HTML;
	}
	
	private function empty_events(){
		return <<<HTML

					<p class="empty">Well, there isn&rsquo;t anything going on anytime soon. Boring. There currently aren&rsquo;t any upcoming events, <a href="/create/event" title="WDD Social | Share an Event">would you like to share one?</a></p>
HTML;
	}
	
	private function empty_jobs(){
		return <<<HTML

					<p class="empty">Uh oh, looks like the recession hit hard here, too. There currently aren&rsquo;t any job posts, <a href="/create/job" title="WDD Social | Share a Job">share one.</a></p>
HTML;
	}
}