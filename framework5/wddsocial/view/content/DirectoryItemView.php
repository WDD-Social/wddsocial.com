<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class DirectoryItemView implements \Framework5\IView {
	
	public function render($options = null) {
		switch ($options['type']) {
		
			case 'project':
				return $this->person_display($options['content']);
		
			case 'article':
				return $this->person_display($options['content']);
		
			case 'event':
				return $this->person_display($options['content']);
		
			case 'job':
				return $this->person_display($options['content']);
		
			case 'course':
				return $this->person_display($options['content']);
			
			default:
				throw new Exception("DirectoryItemView requires parameter type (project, article, event, job, or course), '{$options['type']}' provided");
		}
	}
	
}