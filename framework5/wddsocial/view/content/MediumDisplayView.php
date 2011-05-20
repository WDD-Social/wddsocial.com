<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class MediumDisplayView implements \Framework5\IView {	
	
	public function __construct() {
		$this->lang = new \Framework5\Lang('wddsocial.lang.view.content.DisplayViewLang');
	}
	
	/**
	* Determines what type of content to render
	*/
	
	public function render($options = null) {
		switch ($options['type']) {
			case 'project':
				return $this->project_display($options['content']);
				
			case 'projectComment':
				return $this->project_comment_display($options['content']);
			
			case 'article':
				return $this->article_display($options['content']);
				
			case 'articleComment':
				return $this->article_comment_display($options['content']);
				
			case 'eventComment':
				return $this->event_comment_display($options['content']);
			
			case 'person':
				return $this->person_display($options['content']);
			
			default:
				throw new Exception("MediumDisplayView requires parameter type (project, projectComment, article, articleComment, eventComment, or person), '{$options['type']}' provided");
		}
	}
	
	
	
	/**
	* Creates a project article
	*/
	
	private function project_display($project){		
		
		# format natural language
		$userVerbage = NaturalLanguage::view_profile($project->userID,"{$project->userFirstName} {$project->userLastName}");
		$userDisplayName = NaturalLanguage::display_name($project->userID,"{$project->userFirstName} {$project->userLastName}");
		$userAvatar = (file_exists("images/avatars/{$project->userAvatar}_medium.jpg"))?"/images/avatars/{$project->userAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		$teamIntro = $this->format_team_string($project->userID,$project->team, ' with');
		
		# output
		$html = <<<HTML

					<article class="projects with-secondary">
						<div class="secondary">
HTML;
		
		
		# determines what type of secondary controls to present (Flag or Edit/Delete)
		if (UserSession::is_current($project->userID)) {
			$html .= <<<HTML

							<a href="/edit/project/{$project->vanityURL}" title="{$this->lang->text('edit_title', $project->title)}" class="edit">{$this->lang->text('edit')}</a>
							<a href="/delete/project/{$project->vanityURL}" title="{$this->lang->text('delete_title', $project->title)}" class="delete">{$this->lang->text('delete')}</a>
HTML;
		}
		
		else if(UserValidator::is_project_owner($project->id)){
			$html .= <<<HTML

							<a href="/edit/project/{$project->vanityURL}" title="{$this->lang->text('edit_title', $project->title)}" class="edit">{$this->lang->text('edit')}</a>
HTML;
		}
		
		else if(UserSession::is_authorized()){
			$flagClass = (UserSession::has_flagged($project->id,$project->type))?' current':'';
			$html .= <<<HTML

							<a href="/flag/project/{$project->vanityURL}" title="{$this->lang->text('flag_title', $project->title)}" class="flag$flagClass">{$this->lang->text('flag')}</a>
HTML;
		}
		
		# output
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="/user/{$project->userURL}" title="$userVerbage"><img src="$userAvatar" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="/user/{$project->userURL}" title="$userVerbage">$userDisplayName</a></strong> {$this->lang->text('posted_a')} <strong><a href="/project/{$project->vanityURL}" title="{$project->title}">{$this->lang->text('project')}</a></strong>$teamIntro.</p>
						<h2><a href="/project/{$project->vanityURL}" title="{$project->title}">{$project->title}</a></h2>
						<p>{$project->description}</p>
HTML;
		
		# build images if needed
		if (count($project->images) > 0) {
			$html .= <<<HTML

						<p class="images">
HTML;
			foreach ($project->images as $image) {
				if (file_exists("images/uploads/{$image->file}_full.jpg") and file_exists("images/uploads/{$image->file}_large.jpg")) {
					$html .= <<<HTML

							<a href="/images/uploads/{$image->file}_full.jpg" title="{$image->title}" class="fancybox" rel="fancybox-project{$project->vanityURL}"><img src="/images/uploads/{$image->file}_large.jpg" alt="{$image->title}"/></a>
HTML;
				}
			}
			$html .= <<<HTML

						</p>			
HTML;
		}
		$html .= <<<HTML

						<p class="comments"><a href="/project/{$project->vanityURL}#comments" title="{$this->lang->text('comments_title', $project->title)}">{$this->lang->text('comments', $project->comments)}</a> <span class="hidden">|</span> <span class="time">{$project->date}</span></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach ($project->categories as $category) {
			array_push($categoryLinks,"<a href=\"/search/$category\" title=\"{$this->lang->text('category_title', $category)}\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$project->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates a project comment article
	*/
	
	private function project_comment_display($projectComment){
	
		# format natural language
		$userVerbage = NaturalLanguage::view_profile($projectComment->userID,"{$projectComment->userFirstName} {$projectComment->userLastName}");
		$userDisplayName = NaturalLanguage::display_name($projectComment->userID,"{$projectComment->userFirstName} {$projectComment->userLastName}");
		$userAvatar = (file_exists("images/avatars/{$projectComment->userAvatar}_medium.jpg"))?"/images/avatars/{$projectComment->userAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		$teamIntro = $this->format_team_string($projectComment->userID,$projectComment->team, 'By', false);
		
		# output
		$html = <<<HTML

					<article class="comments with-secondary">
						<div class="secondary">
HTML;
		
		# determines what type of secondary controls to present (Flag or Edit/Delete)
		if (UserSession::is_current($projectComment->userID)) {
			$html .= <<<HTML

							<a href="/edit/comment/{$projectComment->id}" title="{$this->lang->text('edit_comment', $projectComment->title)}" class="edit">{$this->lang->text('edit')}</a>
							<a href="/delete/comment/{$projectComment->id}" title="{$this->lang->text('delete_comment', $projectComment->title)}" class="delete">{$this->lang->text('delete')}</a>
HTML;
		}
		
		# authorized users can flag content
		else if (UserSession::is_authorized()) {
			$html .= <<<HTML

							<a href="/flag/comment/{$projectComment->id}" title="{$this->lang->text('flag_comment', $projectComment->title)}" class="flag">{$this->lang->text('flag')}</a>
HTML;
		}
		
		# output
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="/user/{$projectComment->userURL}" title="{$userVerbage}"><img src="$userAvatar" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="/user/{$projectComment->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> commented on a <strong><a href="/project/{$projectComment->vanityURL}#comments" title="{$projectComment->title}">project</a></strong>.</p>
						<h2><a href="/project/{$projectComment->vanityURL}#comments" title="{$projectComment->title}">{$projectComment->title}</a></h2>
						<p>$teamIntro</p>
						<p>"{$projectComment->description}"</p>
HTML;
		$html .= <<<HTML

						<p class="comments"><a href="/project/{$projectComment->vanityURL}#comments" title="{$this->lang->text('comments_title', $projectComment->title)}">{$this->lang->text('comments', $projectComment->comments)}</a> <span class="hidden">|</span> <span class="time">{$projectComment->date}</span></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach ($projectComment->categories as $category) {
			array_push($categoryLinks,"<a href=\"/search/$category\" title=\"Categories | $category\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$projectComment->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates an article article
	*/
	
	private function article_display($article){
		
		# format natural language
		$userVerbage = NaturalLanguage::view_profile($article->userID,"{$article->userFirstName} {$article->userLastName}");
		$userDisplayName = NaturalLanguage::display_name($article->userID,"{$article->userFirstName} {$article->userLastName}");
		$userAvatar = (file_exists("images/avatars/{$article->userAvatar}_medium.jpg"))?"/images/avatars/{$article->userAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		$teamIntro = $this->format_team_string($article->userID,$article->team, ' with');
		
		# output
		$html = <<<HTML

					<article class="articles with-secondary">
						<div class="secondary">
HTML;
		
		# Determines what type of secondary controls to present (Flag or Edit/Delete)
		if (UserSession::is_current($article->userID)) {
			$html .= <<<HTML

							<a href="/edit/article/{$article->vanityURL}" title="Edit &ldquo;{$article->title}&rdquo;" class="edit">Edit</a>
							<a href="/delete/article/{$article->vanityURL}" title="Delete &ldquo;{$article->title}&rdquo;" class="delete">Delete</a>
HTML;
		}
		
		else if (UserValidator::is_article_owner($article->id)) {
			$html .= <<<HTML

							<a href="/edit/article/{$article->vanityURL}" title="{$this->lang->text('edit_title', $article->title)}" class="edit">{$this->lang->text('edit')}</a>
HTML;
		}
		
		# authorized users can flag
		else if (UserSession::is_authorized()) {
			$flagClass = (UserSession::has_flagged($article->id,$article->type))?' current':'';
			$html .= <<<HTML

							<a href="/flag/article/{$article->vanityURL}" title="{$this->lang->text('flag_title', $article->title)}" class="flag$flagClass">{$this->lang->text('flag')}</a>
HTML;
		}
		
		# output
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="/user/{$article->userURL}" title="{$userVerbage}"><img src="$userAvatar" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="/user/{$article->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> {$this->lang->text('wrote_an')} <strong><a href="/article/{$article->vanityURL}" title="{$article->title}">{$this->lang->text('article')}</a></strong>$teamIntro.</p>
						<h2><a href="/article/{$article->vanityURL}" title="{$article->title}">{$article->title}</a></h2>
						<p>{$article->description}</p>
HTML;
		
		# Build images if needed
		if(count($article->images) > 0){
			$html .= <<<HTML

						<p class="images">			
HTML;

			foreach ($article->images as $image) {
				if (file_exists("images/uploads/{$image->file}_full.jpg") and file_exists("images/uploads/{$image->file}_large.jpg")) {
					$html .= <<<HTML

							<a href="/images/uploads/{$image->file}_full.jpg" title="{$image->title}" class="fancybox" rel="fancybox-article{$article->vanityURL}"><img src="/images/uploads/{$image->file}_large.jpg" alt="{$image->title}"/></a>
HTML;
				}
			}
			
			$html .= <<<HTML

						</p>			
HTML;
		}
		
		$html .= <<<HTML

						<p class="comments"><a href="/article/{$article->vanityURL}#comments" title="{$this->lang->text('comments_title', $article->title)}">{$this->lang->text('comments', $article->comments)}</a> <span class="hidden">|</span> <span class="time">{$article->date}</span></p>
HTML;
		
		# build categories
		$categoryLinks = array();
		foreach ($article->categories as $category) {
			array_push($categoryLinks,"<a href=\"/search/$category\" title=\"{$this->lang->text('category_title', $category)}\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$article->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates an article comment article
	*/
	
	private function article_comment_display($articleComment){
	
		# format natural language
		$userVerbage = NaturalLanguage::view_profile($articleComment->userID,"{$articleComment->userFirstName} {$articleComment->userLastName}");
		$userDisplayName = NaturalLanguage::display_name($articleComment->userID,"{$articleComment->userFirstName} {$articleComment->userLastName}");
		$userAvatar = (file_exists("images/avatars/{$articleComment->userAvatar}_medium.jpg"))?"/images/avatars/{$articleComment->userAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		$teamIntro = $this->format_team_string($articleComment->userID,$articleComment->team, 'By', false);
		
		# output
		$html = <<<HTML

					<article class="comments with-secondary">
						<div class="secondary">
HTML;
		
		# determines what type of secondary controls to present (Flag or Edit/Delete)
		if(UserSession::is_current($articleComment->userID)){
			$html .= <<<HTML

							<a href="/edit/comment/{$articleComment->id}" title="Edit Comment on &ldquo;{$articleComment->title}&rdquo;" class="edit">Edit</a>
							<a href="/delete/comment/{$articleComment->id}" title="Delete {$articleComment->title} on &ldquo;{$articleComment->title}&rdquo;" class="delete">Delete</a>
HTML;
		}
		
		else if (UserSession::is_authorized()) {
			$html .= <<<HTML

							<a href="/flag/comment/{$articleComment->id}" title="Flag Comment on &ldquo;{$articleComment->title}&rdquo;" class="flag">Flag</a>
HTML;
		}	
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="/user/{$articleComment->userURL}" title="{$userVerbage}"><img src="$userAvatar" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="/user/{$articleComment->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> commented on an <strong><a href="/article/{$articleComment->vanityURL}#comments" title="{$articleComment->title}">article</a></strong>.</p>
						<h2><a href="/article/{$articleComment->vanityURL}#comments" title="{$articleComment->title}">{$articleComment->title}</a></h2>
						<p>$teamIntro</p>
						<p>"{$articleComment->description}"</p>
HTML;
		$html .= <<<HTML

						<p class="comments"><a href="/article/{$articleComment->vanityURL}#comments" title="{$this->lang->text('comments', $articleComment->title)}">{$this->lang->text('comments', $articleComment->comments)}</a> <span class="hidden">|</span> <span class="time">{$articleComment->date}</span></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach ($articleComment->categories as $category) {
			array_push($categoryLinks,"<a href=\"/search/$category\" title=\"{$this->lang->text('category_title', $category)}\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$articleComment->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates an event comment article
	*/
	
	private function event_comment_display($eventComment) {
		
		# format naturla language
		$userVerbage = NaturalLanguage::view_profile($eventComment->userID,"{$eventComment->userFirstName} {$eventComment->userLastName}");
		$userDisplayName = NaturalLanguage::display_name($eventComment->userID,"{$eventComment->userFirstName} {$eventComment->userLastName}");
		$userAvatar = (file_exists("images/avatars/{$eventComment->userAvatar}_medium.jpg"))?"/images/avatars/{$eventComment->userAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		
		# output
		$html = <<<HTML

					<article class="comments with-secondary">
						<div class="secondary">
HTML;
		
		# Determines what type of secondary controls to present (Flag or Edit/Delete)
		if (UserSession::is_current($eventComment->userID)) {
			$html .= <<<HTML

							<a href="/edit/comment/{$eventComment->id}" title="{$this->lang->text('edit_comment', $eventComment->title)}" class="edit">{$this->lang->text('edit')}</a>
							<a href="/delete/comment/{$eventComment->id}" title="{$this->lang->text('delete_comment', $eventComment->title)}" class="delete">{$this->lang->text('delete')}</a>
HTML;
		}else if(UserSession::is_authorized()){
			$html .= <<<HTML

							<a href="/flag/comment/{$eventComment->id}" title="{$this->lang->text('flag_comment', $eventComment->title)}" class="flag">{$this->lang->text('flag')}</a>
HTML;
		}
		
		# comment
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="/user/{$eventComment->userURL}" title="{$userVerbage}"><img src="$userAvatar" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="/user/{$eventComment->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> commented on an <strong><a href="/event/{$eventComment->vanityURL}#comments" title="{$eventComment->title}">event</a></strong>.</p>
						<h2><a href="/event/{$eventComment->vanityURL}#comments" title="{$eventComment->title}">{$eventComment->title}</a></h2>
						<p>{$eventComment->eventData->date}</p>
						<p>"{$eventComment->description}"</p>
HTML;
		$html .= <<<HTML

						<p class="comments"><a href="/event/{$eventComment->vanityURL}#comments" title="{$this->lang->text('comments_title', $eventComment->title)}">{$this->lang->text('comments', $eventComment->comments)}</a> <span class="hidden">|</span> <span class="time">{$eventComment->date}</span></p>
HTML;
		
		# Build categories
		$categoryLinks = array();
		foreach($eventComment->categories as $category){
			array_push($categoryLinks,"<a href=\"/search/$category\" title=\"Categories | $category\">$category</a>");
		}
		$categoryLinks = implode(' ',$categoryLinks);
		$html .= <<<HTML
						<p class="tags">$categoryLinks</p>
					</article><!-- END {$eventComment->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates a person article
	*/
	
	private function person_display($person){
		
		# format natural language
		$userVerbage = NaturalLanguage::view_profile($person->userID,"{$person->userFirstName} {$person->userLastName}");
		$userDisplayName = NaturalLanguage::display_name($person->userID,"{$person->userFirstName} {$person->userLastName}");
		$userAvatar = (file_exists("images/avatars/{$person->userAvatar}_medium.jpg"))?"/images/avatars/{$person->userAvatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		
		# output
		$html = <<<HTML

					<article class="people">
						<p class="item-image"><a href="/user/{$person->userURL}" title="{$userVerbage}"><img src="$userAvatar" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="/user/{$person->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> {$this->lang->text('joined')}.</p>
HTML;
		if (isset($person->description) and $person->description != '') {
			$html .= <<<HTML

						<p>{$person->description}</p>
HTML;
		}
		$html .= <<<HTML

						<p>{$person->date}</p>
					</article><!-- END {$person->title} -->
HTML;
		return $html;
	}
	
	
	/**
	* Creates and formats the team string for display
	*/
	
	private function format_team_string($ownerID, $team, $introWord, $removeOwner = true){
		# Remove user who posted content from team (for intro sentence), and put current user at front of array
		$cleanTeam = $team;
		foreach($cleanTeam as $member){
			if($member->id == $ownerID and $removeOwner){
				$key = array_search($member, $cleanTeam);
				unset($cleanTeam[$key]);
			}else if(UserSession::is_current($member->id)){
				$key = array_search($member, $cleanTeam);
				$currentUser = $cleanTeam[$key];
				unset($cleanTeam[$key]);
				array_unshift($cleanTeam,$currentUser);
			}
		}
		$cleanTeam = array_values($cleanTeam);
		
		# Create team string
		if(count($cleanTeam) > 0){
			$teamIntro = "$introWord ";
			$teamString = array();
			
			# Creates string according to how many team members there are for this piece of content
			if(count($cleanTeam) == 1){
				$userVerbage = NaturalLanguage::view_profile($cleanTeam[0]->id,"{$cleanTeam[0]->firstName} {$cleanTeam[0]->lastName}");
				$userDisplayName = NaturalLanguage::display_name($cleanTeam[0]->id,"{$cleanTeam[0]->firstName} {$cleanTeam[0]->lastName}");
				$teamIntro .= "<strong><a href=\"/user/{$cleanTeam[0]->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>";
			}else if(count($cleanTeam) == 2){
				foreach($cleanTeam as $member){
					$userVerbage = NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
					$userDisplayName = NaturalLanguage::display_name($member->id,"{$member->firstName} {$member->lastName}");
					array_push($teamString, "<strong><a href=\"/user/{$member->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>");
				}
				$teamString = implode(' and ',$teamString);
				$teamIntro .= $teamString;
			}else{
				$strings = array();
				foreach($cleanTeam as $member){
					$userVerbage = NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
					$userDisplayName = NaturalLanguage::display_name($member->id,"{$member->firstName} {$member->lastName}");
					array_push($strings,"<strong><a href=\"/user/{$member->vanityURL}\" title=\"$userVerbage\">$userDisplayName</a></strong>");
				}
				$teamIntro .= NaturalLanguage::comma_list($strings);
			}
		}else{
			$teamIntro = "";
		}
		
		return $teamIntro;
	}
}