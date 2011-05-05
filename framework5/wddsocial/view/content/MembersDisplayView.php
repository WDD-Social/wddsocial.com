<?php

namespace WDDSocial;

/*
* Display members listing
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class MembersDisplayView implements \Framework5\IView {
	
	public static function render($content = null) {
	
		$root = \Framework5\Request::root_path();
		$html = "";
		$possessiveTitle = NaturalLanguage::possessive($content->title);
		
		switch ($content->type) {
			case 'project':
				if(UserValidator::is_project_owner($content->id)){
					$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;$possessiveTitle Team&rdquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
				}
				break;
			case 'article':
				if(UserValidator::is_article_owner($content->id)){
					$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="Edit &ldquo;{$possessiveTitle} Authors&rdquo;" class="edit">Edit</a>
					</div><!-- END SECONDARY -->
HTML;
				}
				break;
			default :
				if(UserSession::is_current($content->userID)){
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
				if(UserSession::is_current($member->id)){
					$key = array_search($member, $content->team);
					$currentUser = $content->team[$key];
					unset($content->team[$key]);
					array_unshift($content->team,$currentUser);
				}
			}
			
			foreach($content->team as $member){
				$userVerbage = NaturalLanguage::view_profile($member->id,"{$member->firstName} {$member->lastName}");
				$userDisplayName = NaturalLanguage::display_name($member->id,"{$member->firstName} {$member->lastName}");
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
}