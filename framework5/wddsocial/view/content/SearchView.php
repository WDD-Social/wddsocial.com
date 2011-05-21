<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SearchView implements \Framework5\IView {
	
	public function render($options = null) {
		# retrieve content based on the provided section
		switch ($options['section']) {
			case 'mega_header':
				return $this->mega_header($options['term']);
			case 'headers':
				return $this->headers($options['types'], $options['active'], $options['term']);
			default:
				throw new Exception("SectionView requires parameter section (content or content_section), '{$options['section']}' provided");
		}
	}
	
	private function mega_header($term){
		return <<<HTML

				<h1 class="mega">Search results for &ldquo;{$term}&rdquo;</h1>
HTML;
	}
	
	private function headers($types, $active, $term){
		foreach ($types as $type) {
			$typeTitle = ucfirst($type);
			$typeClass = ($type == $active)?' class="current"':'';
			$html .= <<<HTML

						<a href="/search/$type/{$term}" title="Search $typeTitle for &ldquo;{$term}&rdquo;"$typeClass>$typeTitle</a>
HTML;
		}
		
		return $html;
	}
}