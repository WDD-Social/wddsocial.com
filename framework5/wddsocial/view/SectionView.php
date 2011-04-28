<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class SectionView implements \Framework5\IView {
	
	/**
	* Determines what type of content to render
	*/
	
	public static function render($options = null) {
		
		# retrieve content based on the provided section
		switch ($options['section']) {
			case 'begin_content':
				return static::begin_content($options);
			case 'end_content':
				return static::end_content();
			case 'begin_content_section':
				return static::begin_content_section($options);
			case 'end_content_section':
				return static::end_content_section($options);
			default:
				throw new \Framework5\Exception("SectionView requires parameter section (content or content_section), '{$options['section']}' provided");
		}
	}
	
	
	
	/**
	* Opens main content section, with optional classes
	*/
	
	private static function begin_content($options){
		if(count($options['classes']) > 0){
			$classString = implode(' ', $options['classes']);
			return <<<HTML

			<section id="content" class="$classString">
HTML;
		}else{
			return <<<HTML

			<section id="content">
HTML;
		}
	}
	
	
	
	/**
	* Ends main content section
	*/
	
	private static function end_content(){	
		return <<<HTML

			</section><!-- END CONTENT -->
HTML;
	}
	
	
	
	# Opens subcontent section, with optional classes, extras
	private static function begin_content_section($options){
		if(!isset($options['id']) || !isset($options['header'])){
			throw new Exception("SectionView begin_content_setion requires parameter id (section ID) and header (h1 text)");
		}
		
		if(count($options['classes']) > 0){
			$classString = implode(' ', $options['classes']);
		}
		if(isset($options['extra'])){
			$extras = static::get_extra($options['extra']);
		}
		return <<<HTML

				<section id="{$options['id']}" class="$classString">
					<h1>{$options['header']}</h1>
					$extras
HTML;
	}
	
	
	
	# Ends subcontent section, with optional id, and load_more options
	private static function end_content_section($options){
		if(isset($options['load_more'])){
			$html .= <<<HTML

					<p class="load-more"><a href="{$root}" title="Load more {$options['load_more']}...">Load More</a></p>
HTML;
		}
		$html .= <<<HTML

				</section><!-- END {$options['id']} -->
HTML;
		return $html;
	}
	
	
	
	# Extra content pieces (filters, slider controls, etc)
	private static function get_extra($id){
		$extras = array(
			'latest_filters' => <<<HTML
<div class="secondary filters">
						<a href="{$_SERVER['REQUEST_URI']}#all" title="All Latest Activity" class="current">All</a> 
						<a href="{$_SERVER['REQUEST_URI']}#people" title="Latest People">People</a> 
						<a href="{$_SERVER['REQUEST_URI']}#projects" title="Latest Projects">Projects</a> 
						<a href="{$_SERVER['REQUEST_URI']}#articles" title="Latest Articles">Articles</a>
					</div><!-- END SECONDARY -->
HTML
			,'user_latest_filters' => <<<HTML
<div class="secondary filters">
						<a href="{$_SERVER['REQUEST_URI']}#all" title="All Latest Activity" class="current">All</a> 
						<a href="{$_SERVER['REQUEST_URI']}#projects" title="Latest Projects">Projects</a> 
						<a href="{$_SERVER['REQUEST_URI']}#articles" title="Latest Articles">Articles</a>
					</div><!-- END SECONDARY -->
HTML
			,'slider_controls' => <<<HTML
<div class="slider-controls"><a href="{$_SERVER['REQUEST_URI']}" title="Featured 1" class="current">1</a> <a href="{$_SERVER['REQUEST_URI']}" title="Featured 2">2</a> <a href="{$_SERVER['REQUEST_URI']}" title="Featured 3">3</a> <a href="{$_SERVER['REQUEST_URI']}" title="Featured 4">4</a> <a href="{$_SERVER['REQUEST_URI']}" title="Featured 5">5</a></div>
HTML
			,'media_filters' => <<<HTML
<div class="secondary filters">
						<a href="{$_SERVER['REQUEST_URI']}#images" title="Related Images" class="current">Images</a> 
						<a href="{$_SERVER['REQUEST_URI']}#videos" title="Related Videos">Videos</a>
					</div><!-- END SECONDARY -->
HTML
		);
		
		return $extras[$id];
	}
}