<?php

namespace WDDSocial;

/*
* Display members listing
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class MembersDisplayView implements \Framework5\IView {
	
	public function render($content = null) {
	
		$lang = new \Framework5\Lang('wddsocial.lang.view.content.DisplayViewLang');
		$html = "";
		$possessiveTitle = NaturalLanguage::possessive($content->title);
		
		$html = "";
		
		if ($content->type != 'course') {
			# display edit controls, if user is author
			switch ($content->type) {
				case 'project':
					if(UserValidator::is_project_owner($content->id)){
						$html .= <<<HTML
	
						<div class="secondary icons">
							<a href="/edit/{$content->type}/{$content->vanityURL}#team" title="{$lang->text('edit_team', $possessiveTitle)}" class="edit">{$lang->text('edit')}</a>
						</div><!-- END SECONDARY -->
HTML;
					}
					break;
				case 'article':
					if (UserValidator::is_article_owner($content->id)) {
						$html .= <<<HTML
	
						<div class="secondary icons">
							<a href="/edit/{$content->type}/{$content->vanityURL}#authors" title="{$lang->text('edit_authors', $possessiveTitle)}" class="edit">{$lang->text('edit')}</a>
						</div><!-- END SECONDARY -->
HTML;
					}
					break;
				default :
					if (UserSession::is_current($content->userID)) {
						$html .= <<<HTML
	
						<div class="secondary icons">
							<a href="/edit/{$content->type}/{$content->vanityURL}" title="{$lang->text('edit_members', $possessiveTitle)}" class="edit">{$lang->text('edit')}</a>
						</div><!-- END SECONDARY -->
HTML;
					}
					break;
			}
		}		
		
		# if team members were provided, display them
		if (count($content->team) > 0) {
			if($content->type == 'project'){
				$html .= <<<HTML

					<ul>
HTML;
			}
			
			foreach ($content->team as $member) {
				if (UserSession::is_current($member->id)) {
					$key = array_search($member, $content->team);
					$currentUser = $content->team[$key];
					unset($content->team[$key]);
					array_unshift($content->team,$currentUser);
				}
			}
			
			foreach ($content->team as $member) {
				$userVerbage = NaturalLanguage::view_profile(
					$member->id,"{$member->firstName} {$member->lastName}");
				$userDisplayName = NaturalLanguage::display_name(
					$member->id,"{$member->firstName} {$member->lastName}");
				$userAvatar = (file_exists("images/avatars/{$member->avatar}_medium.jpg"))?"/images/avatars/{$member->avatar}_medium.jpg":"/images/site/user-default_medium.jpg";
				
				$userDetail = '';
				switch ($content->type) {
					case 'project':
						$userDetail = $member->role;
						break;
					default :
						$userDetail = $member->bio;
						break;
				}
				if ($content->type == 'project') {
					$html .= <<<HTML

						<li>
							<a href="/user/{$member->vanityURL}" title="{$userVerbage}">
							<img src="{$userAvatar}" alt="{$userDisplayName}" />
							<p><strong>{$userDisplayName}</strong> {$userDetail}</p>
							</a>
						</li>
HTML;
				} 
				
				else {
					$html .= <<<HTML

					<article>
						<p class="item-image"><a href="/user/{$member->vanityURL}" title="{$userVerbage}"><img src="{$userAvatar}" alt="{$userDisplayName}" /></a></p>
						<h2><a href="/user/{$member->vanityURL}" title="{$userVerbage}">{$userDisplayName}</a></h2>
						<p>$userDetail</p>
					</article>
HTML;
				}
				
			}
			if ($content->type == 'project') {
				$html .= <<<HTML

					</ul>
HTML;
			}
		}
		
		# no team members were provided
		else{
			$html .= <<<HTML

					<p class="empty">{$lang->text('no_members')}</p>
HTML;
		}
		
		return $html;
	}
}