<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ContentView implements \Framework5\IView {
	
	/**
	* Determines what type of content to render
	*/
	
	public static function render($options = null) {
		import('wddsocial.helper.WDDSocial\NaturalLanguage');
		
		# retrieve content based on the provided section
		switch ($options['section']) {
			case 'overview':
				return static::overview($options['content']);
			case 'team':
				return static::members($options['content']);
			default:
				throw new \Framework5\Exception("ContentView requires parameter section (overview), '{$options['section']}' provided");
		}
	}
	
	
	
	/**
	* Display content overview
	*/
	
	private static function overview($content){
		$root = \Framework5\Request::root_path();
		$html = "";
		if(\WDDSocial\UserValidator::is_current($content->userID)){
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$content->title}&rsquo;" class="edit">Edit</a>
						<a href="{$root}" title="Delete &ldquo;{$content->title}&rsquo;" class="delete">Delete</a>
					</div><!-- END SECONDARY -->
HTML;
		}else{
			switch ($content->type) {
				case 'project':
					if(\WDDSocial\UserValidator::is_project_owner($content->id)){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$content->title}&rsquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
					}else{
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Flag &ldquo;{$content->title}&rsquo;" class="flag">Flag</a>
					</div><!-- END SECONDARY -->
HTML;
					}
					break;
				case 'article':
					if(\WDDSocial\UserValidator::is_article_owner($content->id)){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$content->title}&rsquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
					}else{
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Flag &ldquo;{$content->title}&rsquo;" class="flag">Flag</a>
					</div><!-- END SECONDARY -->
HTML;
					}
					break;
			}
		}
		if(count($content->images) > 0){
			$html .= <<<HTML

					<a href="{$root}images/uploads/{$content->images[0]->file}_full.jpg" title="{$content->images[0]->title}"><img src="{$root}images/uploads/{$content->images[0]->file}_large.jpg" alt="{$content->images[0]->title}" /></a>
					<div class="large no-margin">
HTML;
		}else{
			$html .= <<<HTML

					<div class="large">
HTML;
		}
		$html .= <<<HTML

						<h2>Description</h2>
HTML;
		
		if($content->description != ''){
			$html .= <<<HTML

						<p>{$content->description}</p>
HTML;
		}else{
			$html .= <<<HTML

						<p>No description has been added. How lame is that?</p>
HTML;
		}
		switch ($content->type) {
			case 'project':
				$html .= <<<HTML

						<p>Completed in <strong>{$content->completeDate}</strong>.</p>
						<p>Posted {$content->date}</p>
HTML;
				break;
			case 'article':
				$html .= <<<HTML

						<p>Written {$content->date}</p>
HTML;
				break;
		}
		$html .= <<<HTML

					</div><!-- END DESCRIPTION -->
					
					<div class="small">
						<h2>Categories</h2>
HTML;
		if(count($content->categories) > 0){
			$html .= <<<HTML

						<ul>
HTML;
			foreach($content->categories as $category){
				$html .= <<<HTML

							<li><a href="{$root}/search/{$category->title}" title="Categories | {$category->title}">{$category->title}</a></li>
HTML;
			}
			$html .= <<<HTML

						</ul>
HTML;
		}else{
			$html .= <<<HTML

						<p>No categories have been added. Such a shame...</p>
HTML;
		}
		$html .= <<<HTML

					</div><!-- END CATGORIES -->
					
					<div class="small no-margin">
						<h2>Links</h2>
HTML;
		if(count($content->links) > 0){
			$html .= <<<HTML

						<ul>
HTML;
			foreach($content->links as $link){
				$html .= <<<HTML

							<li><a href="http://{$link->link}" title="{$link->title}">{$link->title}</a></li>
HTML;
			}
			$html .= <<<HTML

						</ul>
HTML;
		}else{
			$html .= <<<HTML

						<p>No links have been added. That&rsquo;s no fun.</p>
HTML;
		}
		$html .= <<<HTML

					</div><!-- END LINKS -->
HTML;
		
		if($content->content != ''){
			$html .= <<<HTML

					<section class="content">
						<p>{$content->content}</p>
					</section><!-- END CONTENT -->
HTML;
		}
		
		return $html;
	}
	
	
	
	/**
	* Display members listing
	*/
	
	private static function members($content){
		$root = \Framework5\Request::root_path();
		$html = "";
		switch ($content->type) {
			case 'project':
				if(\WDDSocial\UserValidator::is_project_owner($content->id)){
					$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$content->title}&rsquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
				}
				break;
			case 'article':
				if(\WDDSocial\UserValidator::is_article_owner($content->id)){
					$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$content->title}&rsquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
				}
				break;
			default :
				if(\WDDSocial\UserValidator::is_current($content->userID)){
					$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$content->title}&rsquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
				}
				break;
		}
		if(count($content->team) > 0){
			$html .= <<<HTML

					<ul>
HTML;
			
			$html .= <<<HTML

					</ul>
HTML;
		}else{
			$html .= <<<HTML

					<p>No one has been added. Well, that&rsquo;s pretty lonely.</p>
HTML;
		}
		return $html;
	}
}