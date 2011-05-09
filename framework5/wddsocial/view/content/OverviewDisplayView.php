<?php

namespace WDDSocial;

/*
* Displays the overview of a project, article, or job listing.
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class OverviewDisplayView implements \Framework5\IView {
	
	public function render($content = null) {

		$html = "";
		
		# display edit controls, if user is author
		if (UserSession::is_current($content->userID)) {
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="/" title="Edit &ldquo;{$content->title}&rdquo;" class="edit">Edit</a>
						<a href="/" title="Delete &ldquo;{$content->title}&rdquo;" class="delete">Delete</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		
		# display edit controls, based on project type and current user
		else {
			switch ($content->type) {
				case 'project':
					if(UserValidator::is_project_owner($content->id)){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="/" title="Edit &ldquo;{$content->title}&rdquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
					}else if(UserSession::is_authorized()){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="/" title="Flag &ldquo;{$content->title}&rdquo;" class="flag">Flag</a>
					</div><!-- END SECONDARY -->
HTML;
					}
					break;
				case 'article':
					if(UserValidator::is_article_owner($content->id)){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="/" title="Edit &ldquo;{$content->title}&rdquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
					}else if(UserSession::is_authorized()){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="/" title="Flag &ldquo;{$content->title}&rdquo;" class="flag">Flag</a>
					</div><!-- END SECONDARY -->
HTML;
					}
				default :
					if(\WDDSocial\UserSession::is_authorized()){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="/" title="Flag &ldquo;{$content->title}&rdquo;" class="flag">Flag</a>
					</div><!-- END SECONDARY -->
HTML;
					}
					break;
			}
		}
		
		
		 
		if (count($content->images) > 0 and $content->type != 'job' and file_exists("images/uploads/{$content->images[0]->file}_full.jpg") and file_exists("images/uploads/{$content->images[0]->file}_large.jpg")) {
			$html .= <<<HTML

					<a href="/images/uploads/{$content->images[0]->file}_full.jpg" title="{$content->images[0]->title}"><img src="/images/uploads/{$content->images[0]->file}_large.jpg" alt="{$content->images[0]->title}" /></a>
					<div class="large no-margin">
HTML;
		}
		
		else if ($content->type == 'job' and file_exists("images/jobs/{$content->avatar}_full.jpg")) {
			$html .= <<<HTML

					<a href="http://{$content->website}" title="{$content->company}"><img src="/images/jobs/{$content->avatar}_full.jpg" alt="{$content->images[0]->title}" /></a>
					<div class="large no-margin">
HTML;
		}
		
		else {
			$html .= <<<HTML

					<div class="large">
HTML;
		}
		$html .= <<<HTML

						<h2>Description</h2>
HTML;
		
		
		
		if ($content->description != '') {
			$html .= <<<HTML

						<p>{$content->description}</p>
HTML;
		}
		
		else {
			$html .= <<<HTML

						<p class="empty">No description has been added. Lame.</p>
HTML;
		}
		
		# 
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
		
		
		if (count($content->categories) > 0) {
			$html .= <<<HTML

						<ul>
HTML;
			foreach ($content->categories as $category) {
				$html .= <<<HTML

							<li><a href="/search/{$category->title}" title="Categories | {$category->title}">{$category->title}</a></li>
HTML;
			}
			$html .= <<<HTML

						</ul>
HTML;
		}
		
		else {
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
		if ($content->type == 'job') {
			if ($content->website != '') {
				$linkCount++;
				$html .= <<<HTML

							<li><a href="http://{$content->website}" title="{$content->company}">{$content->company}</a></li>
HTML;
			}
			if ($content->email != '') {
				$linkCount++;
				$html .= <<<HTML

							<li><a href="mailto:{$content->email}" title="Email {$content->company}">Email</a></li>
HTML;
			}
		}

		if (count($content->links) > 0) {
			foreach ($content->links as $link) {
				$linkCount++;
				$html .= <<<HTML

							<li><a href="http://{$link->link}" title="{$link->title}">{$link->title}</a></li>
HTML;
			}
			$html .= <<<HTML

						</ul>
HTML;
		}
		
		if ($linkCount < 1) {
			$html .= <<<HTML

						<p>No links have been added. That&rsquo;s no fun.</p>
HTML;
		}
		$html .= <<<HTML

					</div><!-- END LINKS -->
HTML;
		
		if ($content->content != '') {
			$html .= <<<HTML

					<section class="content">
						<p>{$content->content}</p>
HTML;
			if ($content->type == 'job') {
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
}