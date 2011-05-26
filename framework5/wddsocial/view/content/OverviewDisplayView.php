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

		$lang = new \Framework5\Lang('wddsocial.lang.CommonLang');
		$html = "";
		
		# display edit controls, if user is author
		if (UserSession::is_current($content->userID)) {
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="/edit/{$content->type}/{$content->vanityURL}" title="{$lang->text('edit_title', $content->title)}" class="edit">{$lang->text('edit')}</a>
						<a href="/delete/{$content->type}/{$content->vanityURL}" title="{$lang->text('delete_title', $content->title)}" class="delete">{$lang->text('delete')}</a>
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
						<a href="/edit/{$content->type}/{$content->vanityURL}" title="{$lang->text('edit_title', $content->title)}" class="edit">{$lang->text('edit')}</a>
					</div><!-- END SECONDARY -->
HTML;
					}else if(UserSession::is_authorized()){
						$flagClass = (UserSession::has_flagged($content->id,$content->type))?' current':'';
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="/flag/{$content->type}/{$content->vanityURL}" title="{$lang->text('flag_title', $content->title)}" class="flag$flagClass">{$lang->text('flag')}</a>
					</div><!-- END SECONDARY -->
HTML;
					}
					break;
				case 'article':
					if(UserValidator::is_article_owner($content->id)){
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="/edit/{$content->type}/{$content->vanityURL}" title="{$lang->text('edit_title', $content->title)}" class="edit">{$lang->text('edit')}</a>
					</div><!-- END SECONDARY -->
HTML;
					}else if(UserSession::is_authorized()){
						$flagClass = (UserSession::has_flagged($content->id,$content->type))?' current':'';
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="/flag/{$content->type}/{$content->vanityURL}" title="{$lang->text('flag_title', $content->title)}" class="flag$flagClass">{$lang->text('flag')}</a>
					</div><!-- END SECONDARY -->
HTML;
					}
				default :
					if(\WDDSocial\UserSession::is_authorized()){
						$flagClass = (UserSession::has_flagged($content->id,$content->type))?' current':'';
						$html .= <<<HTML

					<div class="secondary icons">
						<a href="/flag/{$content->type}/{$content->vanityURL}" title="{$lang->text('flag_title', $content->title)}" class="flag$flagClass">{$lang->text('flag')}</a>
					</div><!-- END SECONDARY -->
HTML;
					}
					break;
			}
		}
		
		
		 
		if (count($content->images) > 0 and $content->type != 'job' and file_exists("images/uploads/{$content->images[0]->file}_full.jpg") and file_exists("images/uploads/{$content->images[0]->file}_large.jpg")) {
			$html .= <<<HTML

					<a href="/images/uploads/{$content->images[0]->file}_full.jpg" title="{$content->images[0]->title}" class="fancybox" rel="fancybox-media"><img src="/images/uploads/{$content->images[0]->file}_large.jpg" alt="{$content->images[0]->title}" /></a>
					<div class="large no-margin">
HTML;
		}
		
		else if ($content->type == 'job') {
			$companyLink = ($content->website == '')?"http://google.com/?q={$content->company}":"http://{$content->website}";
			$jobAvatar = (file_exists("images/jobs/{$content->avatar}_full.jpg"))?"/images/jobs/{$content->avatar}_full.jpg":"/images/site/job-default_full.jpg";
			
			$html .= <<<HTML

					<a href="$companyLink" title="{$content->company}"><img src="$jobAvatar" alt="{$content->images[0]->title}" /></a>
					<div class="large no-margin">
HTML;
		}
		
		else {
			$html .= <<<HTML

					<div class="large">
HTML;
		}
		$html .= <<<HTML

						<h2>{$lang->text('description')}</h2>
HTML;
		
		
		
		if ($content->description != '') {
			$html .= <<<HTML

						<p>{$content->description}</p>
HTML;
		}
		
		else {
			$html .= <<<HTML

						<p class="empty">{$lang->text('no_description')}</p>
HTML;
		}
		
		# 
		switch ($content->type) {
			case 'project':
				if ($content->completeDate != null) {
					$html .= <<<HTML

						<p>{$lang->text('completion_date', $content->completeDate)}</p>
HTML;
				}
				$html .= <<<HTML

						<p>{$lang->text('posted_date', $content->date)}</p>
HTML;
				break;
			case 'article':
				$html .= <<<HTML

						<p>{$lang->text('written_date', $content->date)}</p>
HTML;
				break;
		}
		$html .= <<<HTML

					</div><!-- END DESCRIPTION -->
					
					<div class="large no-margin">
						<h2>{$lang->text('categories')}</h2>
HTML;
		
		
		if (count($content->categories) > 0) {
			$html .= <<<HTML

						<ul>
HTML;
			$searchType = $content->type . 's';
			foreach ($content->categories as $category) {
				$searchTerm = urlencode($category->title);
				$html .= <<<HTML

							<li><a href="/search/{$searchType}/{$searchTerm}" title="{$lang->text('category_title',$category->title)}">{$category->title}</a></li>
HTML;
			}
			$html .= <<<HTML

						</ul>
HTML;
		}
		
		else {
			$html .= <<<HTML

						<p>{$lang->text('no_categories')}</p>
HTML;
		}
		$html .= <<<HTML

					</div><!-- END CATGORIES -->
					
					<div class="large no-margin">
						<h2>{$lang->text('links')}</h2>
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

						<p>{$lang->text('no_links')}</p>
HTML;
		}
		$html .= <<<HTML

					</div><!-- END LINKS -->
HTML;
		
		if ($content->content != '') {
			$article = nl2br($content->content);
			$html .= <<<HTML

					<section class="content">
						<p>{$article}</p>
HTML;
			if ($content->type == 'job') {
				$html .= <<<HTML

						<p><a href="mailto:{$content->email}" title="{$lang->text('apply_title')}" class="button">{$lang->text('apply_now')}</a></p>
HTML;
			}
			$html .= <<<HTML

					</section><!-- END CONTENT -->
HTML;
		}
		
		return $html;
	}
}