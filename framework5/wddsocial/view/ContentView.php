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
			case 'members':
				return static::members($options['content']);
			case 'event_location':
				return static::event_location($options['content']);
			case 'job_details':
				return static::job_details($options['content']);
			case 'media':
				return static::media($options['content'],$options['active']);
			case 'comments':
				return static::comments($options['comments']);
			default:
				throw new \Framework5\Exception("ContentView requires parameter section (overview, members, media, or comments), '{$options['section']}' provided");
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
						<a href="{$root}" title="Edit &ldquo;{$content->title}&rdquo;" class="edit">Edit</a>
						<a href="{$root}" title="Delete &ldquo;{$content->title}&rdquo;" class="delete">Delete</a>
					</div><!-- END SECONDARY -->
HTML;
		}else{
			switch ($content->type) {
				case 'project':
					if(\WDDSocial\UserValidator::is_project_owner($content->id)){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$content->title}&rdquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
					}else if(\WDDSocial\UserValidator::is_authorized()){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Flag &ldquo;{$content->title}&rdquo;" class="flag">Flag</a>
					</div><!-- END SECONDARY -->
HTML;
					}
					break;
				case 'article':
					if(\WDDSocial\UserValidator::is_article_owner($content->id)){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$content->title}&rdquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
					}else if(\WDDSocial\UserValidator::is_authorized()){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Flag &ldquo;{$content->title}&rdquo;" class="flag">Flag</a>
					</div><!-- END SECONDARY -->
HTML;
					}
				default :
					if(\WDDSocial\UserValidator::is_authorized()){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Flag &ldquo;{$content->title}&rdquo;" class="flag">Flag</a>
					</div><!-- END SECONDARY -->
HTML;
					}
					break;
			}
		}
		if(count($content->images) > 0 && $content->type != 'job'){
			$html .= <<<HTML

					<a href="{$root}images/uploads/{$content->images[0]->file}_full.jpg" title="{$content->images[0]->title}"><img src="{$root}images/uploads/{$content->images[0]->file}_large.jpg" alt="{$content->images[0]->title}" /></a>
					<div class="large no-margin">
HTML;
		}else if($content->type == 'job'){
			$html .= <<<HTML

					<a href="http://{$content->website}" title="{$content->company}"><img src="{$root}images/jobs/{$content->avatar}_full.jpg" alt="{$content->images[0]->title}" /></a>
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

						<p class="empty">No description has been added. Lame.</p>
HTML;
		}
		switch ($content->type) {
			case 'project':
				$html .= <<<HTML

						<p>Completed in {$content->completeDate}.</p>
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
		$linkCount = 0;
		$html .= <<<HTML

						<ul>
HTML;
		if($content->type == 'job'){
			if($content->website != ''){
				$linkCount++;
				$html .= <<<HTML

							<li><a href="http://{$content->website}" title="{$content->company}">{$content->company}</a></li>
HTML;
			}
			if($content->email != ''){
				$linkCount++;
				$html .= <<<HTML

							<li><a href="mailto:{$content->email}" title="Email {$content->company}">Email</a></li>
HTML;
			}
		}

		if(count($content->links) > 0){
			foreach($content->links as $link){
				$linkCount++;
				$html .= <<<HTML

							<li><a href="http://{$link->link}" title="{$link->title}">{$link->title}</a></li>
HTML;
			}
			$html .= <<<HTML

						</ul>
HTML;
		}
		
		if($linkCount < 1){
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
HTML;
			if($content->type == 'job'){
				$html .= <<<HTML

						<p><a href="mailto:{$content->email}" title="Apply for this job" class="button">Apply Now</a></p>
HTML;
			}
			$html .= <<<HTML

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
		$possessiveTitle = \WDDSocial\NaturalLanguage::possessive($content->title);
		
		switch ($content->type) {
			case 'project':
				if(\WDDSocial\UserValidator::is_project_owner($content->id)){
					$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;$possessiveTitle Team&rdquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
				}
				break;
			case 'article':
				if(\WDDSocial\UserValidator::is_article_owner($content->id)){
					$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$possessiveTitle} Authors&rdquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
				}
				break;
			default :
				if(\WDDSocial\UserValidator::is_current($content->userID)){
					$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$possessiveTitle} Members&rdquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
				}
				break;
		}
		
		if(count($content->team) > 0){
			if($content->type != 'article'){
				$html .= <<<HTML

					<ul>
HTML;
			}
			
			foreach($content->team as $member){
				if(\WDDSocial\UserValidator::is_current($member->id)){
					$key = array_search($member, $content->team);
					$currentUser = $content->team[$key];
					unset($content->team[$key]);
					array_unshift($content->team,$currentUser);
				}
			}
			
			foreach($content->team as $member){
				$userVerbage = \WDDSocial\NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
				$userDisplayName = \WDDSocial\NaturalLanguage::display_name($member->id,"{$member->firstName} {$member->lastName}");
				$userDetail = '';
				switch ($content->type) {
					case 'project':
						$userDetail = $member->role;
						break;
					default :
						$userDetail = $member->bio;
						break;
				}
				if($content->type != 'article'){
					$html .= <<<HTML

						<li>
							<a href="{$root}user/{$member->vanityURL}" title="{$userVerbage}">
							<img src="{$root}images/avatars/{$member->avatar}_medium.jpg" alt="{$userDisplayName}" />
							<p><strong>{$userDisplayName}</strong> {$userDetail}</p>
							</a>
						</li>
HTML;
				}else{
					$html .= <<<HTML

					<article>
						<p class="item-image"><a href="{$root}user/{$member->vanityURL}" title="{$userVerbage}"><img src="{$root}images/avatars/{$member->avatar}_medium.jpg" alt="{$userDisplayName}" /></a></p>
						<h2><a href="{$root}user/{$member->vanityURL}" title="{$userVerbage}">{$userDisplayName}</a></h2>
						<p>$userDetail</p>
					</article>
HTML;
				}
				
			}
			if($content->type != 'article'){
				$html .= <<<HTML

					</ul>
HTML;
			}
		}else{
			$html .= <<<HTML

					<p class="empty">No one has been added. Well, that&rsquo;s pretty lonely.</p>
HTML;
		}
		
		return $html;
	}
	
	
	
	/**
	* Display event location and time
	*/
	
	private static function event_location($content){
		$root = \Framework5\Request::root_path();
		$html = "";
		$possessiveTitle = \WDDSocial\NaturalLanguage::possessive($content->title);
		
		if(\WDDSocial\UserValidator::is_current($content->userID)){
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit {$possessiveTitle} Location and Time" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		$html .= <<<HTML

					<article class="location-and-time">
HTML;
			
		$html .= <<<HTML

						<p class="item-image"><a href="{$root}/files/ics/{$content->icsUID}.ics" title="Download {$content->title} iCal File" class="calendar-icon">
							<span class="month">{$content->month}</span> 
							<span class="day">{$content->day}</span> 
							<span class="download"><img src="{$root}/images/site/icon-download.png" alt="Download iCal File"/>iCal</span>
						</a></p>
						<h2>{$content->location}</h2>
						<p>{$content->startTime} - {$content->endTime}</p>
						<p><a href="{$root}/files/ics/{$content->icsUID}.ics" title="Download {$content->title} iCal File">Download iCal File</a></p>
					</article><!-- END {$content->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Display event location and time
	*/
	
	private static function job_details($content){
		$root = \Framework5\Request::root_path();
		$html = "";
		if($content->jobType == 'Internship'){
			$jobType = "an <strong><a href=\"{$root}jobs\" title=\"{$content->jobType} Jobs\">{$content->jobType}</a></strong>";
		}else{
			$jobType = "a <strong><a href=\"{$root}jobs\" title=\"{$content->jobType} Jobs\">{$content->jobType}</a></strong> gig";
		}
		
		if(\WDDSocial\UserValidator::is_current($content->userID)){
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit {$content->title} at {$content->company} Details" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		$html .= <<<HTML

					<article class="with-secondary">
HTML;
			
		$html .= <<<HTML

						<p class="item-image"><a href="http://{$content->website}" title="{$content->company}"><img src="{$root}images/jobs/{$content->avatar}_medium.jpg" alt="{$content->company}"/></a></p>
						<h2><a href="http://{$content->website}" title="{$content->company}">{$content->company}</a></h2>
						<p><a href="http://maps.google.com/?q={$content->location}" title="Search Google Maps for {$content->location}">{$content->location}</a></p>
						<p>This job is {$jobType}.</p>
HTML;
		if($content->compensation != ''){
			$html .= <<<HTML

						<p>Compensation is <strong>{$content->compensation}</strong></p>
HTML;
		}
		$html .= <<<HTML
					</article>
HTML;
		return $html;
	}
	
	
	
	/**
	* Display content media
	*/
	
	private static function media($content,$active){
		$root = \Framework5\Request::root_path();
		$html = <<<HTML

					<div class="$active">
HTML;
		
		switch ($active) {
			case 'images':
				if(count($content->images) > 0){
					foreach($content->images as $image){
						$html .= <<<HTML

						<a href="{$root}images/uploads/{$image->file}_full.jpg" title="{$image->title}"><img src="{$root}images/uploads/{$image->file}_large.jpg" alt="{$image->title}"/></a>
HTML;
					}
				}else{
					$html .= <<<HTML

						<p class="empty">Welp! No images have been added, so this page will look a little plain...</p>
HTML;
				}
				
				break;
			case 'videos':
				if(count($content->videos) > 0){
					foreach($content->videos as $video){
						$html .= <<<HTML

						{$video->embedCode}
HTML;
					}
				}else{
					$html .= <<<HTML

						<p class="empty">Uh oh, no videos have been added.</p>
HTML;
				}
				
				break;
		}
		
		$html .= <<<HTML

					</div><!-- END $active -->
HTML;
		
		return $html;
	}
	
	
	
	/**
	* Display content comments
	*/
	
	private static function comments($comments){
		$root = \Framework5\Request::root_path();
		$html = "";
		$commentCount = count($comments);
		$commentVerbage = 'comment';
		if($commentCount > 1 || $commentCount < 1){
			$commentVerbage .= 's';
		}
		$html .= <<<HTML

					<div class="secondary">
						<p>{$commentCount} {$commentVerbage}</p> 
					</div><!-- END SECONDARY -->
HTML;
		
		if($commentCount > 0){
			foreach($comments as $comment){
				$userVerbage = \WDDSocial\NaturalLanguage::view_profile($comment->userID,"{$comment->firstName} {$comment->lastName}");
				$userDisplayName = \WDDSocial\NaturalLanguage::display_name($comment->userID,"{$comment->firstName} {$comment->lastName}");
				
				$html .= <<<HTML

					<article class="with-secondary">
HTML;
				if(\WDDSocial\UserValidator::is_current($comment->userID)){
					$html .= <<<HTML

						<div class="secondary">
							<a href="{$root}" title="Edit Your Comment" class="edit">Edit</a> 
							<a href="{$root}" title="Delete Your Comment" class="delete">Delete</a>
						</div><!-- END SECONDARY -->
HTML;
				}else if(\WDDSocial\UserValidator::is_authorized()){
					$possessive = \WDDSocial\NaturalLanguage::possessive("{$comment->firstName} {$comment->lastName}");
					$html .= <<<HTML

						<div class="secondary">
							<a href="{$root}" title="Flag {$possessive} Comment" class="flag">Flag</a>
						</div><!-- END SECONDARY -->
HTML;
				}
				$html .= <<<HTML
						
						<p class="item-image"><a href="{$root}user/{$comment->vanityURL}" title="{$userVerbage}"><img src="{$root}images/avatars/{$comment->avatar}_medium.jpg" alt="{$userDisplayName}"/></a></p>
						<h2><a href="{$root}user/{$comment->vanityURL}" title="{$userVerbage}">{$userDisplayName}</a></h2>
						<p>{$comment->content}</p>
						<p class="comments">{$comment->date}</p>
					</article>
HTML;
			}
		}else{
			if(\WDDSocial\UserValidator::is_authorized()){
				$html .= <<<HTML

					<p class="empty">No one has commented yet, why don&rsquo;t you start the conversation?</p>
HTML;
			}
		}
		
		if(\WDDSocial\UserValidator::is_authorized()){
			$user = $_SESSION['user'];
			$userVerbage = \WDDSocial\NaturalLanguage::view_profile($user->id,"{$user->firstName} {$user->lastName}");
			$userDisplayName = \WDDSocial\NaturalLanguage::display_name($user->id,"{$user->firstName} {$user->lastName}");
			
			$html .= <<<HTML

					<article>
						<p class="item-image"><a href="{$root}user/{$user->vanityURL}" title="{$userVerbage}"><img src="{$root}images/avatars/{$user->avatar}_medium.jpg" alt="{$userDisplayName}"/></a></p>
						<h2><a href="{$root}user/{$user->vanityURL}" title="{$userVerbage}">{$userDisplayName}</a></h2>
HTML;
			$html .= render('wddsocial.view.WDDSocial\FormView', array('type' => 'comment'));
			$html .= <<<HTML

					</article>
HTML;
		}else{
			$html .= <<<HTML

					<p class="empty">You must be signed in to add a comment. <a href="{$root}signin" title="Sign In to WDD Social">Would you like to sign in?</a></p>
HTML;
		}
		
		return $html;
	}
}