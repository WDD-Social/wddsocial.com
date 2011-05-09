<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class CommentDisplayView implements \Framework5\IView {
	
	public function render($comments = null) {
		
		$lang = new \Framework5\Lang('wddsocial.lang.view.CommentDisplayLang');
		
		$html = "";
		$commentCount = count($comments);
		$commentVerbage = 'comment';
		if ($commentCount > 1 or $commentCount < 1) $commentVerbage .= 's';
		
		# content
		$html .= <<<HTML

					<div class="secondary">
						<p>{$commentCount} {$commentVerbage}</p> 
					</div><!-- END SECONDARY -->
HTML;
		
		# if comments exist, display comments
		if ($commentCount > 0) {
			foreach ($comments as $comment) {
				$userVerbage = NaturalLanguage::view_profile(
					$comment->userID,"{$comment->firstName} {$comment->lastName}");
				
				$userDisplayName = NaturalLanguage::display_name(
					$comment->userID,"{$comment->firstName} {$comment->lastName}");
				
				$userAvatar = (file_exists("images/avatars/{$comment->avatar}_medium.jpg"))?"/images/avatars/{$comment->avatar}_medium.jpg":"/images/site/user-default_medium.jpg";
				
				$html .= <<<HTML

					<article class="with-secondary">
HTML;
				
				if (UserSession::is_current($comment->userID)) {
					$html .= <<<HTML

						<div class="secondary">
							<a href="/" title="{$lang->text('edit_title')}" class="edit">{$lang->text('edit')}</a> 
							<a href="/" title="{$lang->text('delete_title')}" class="delete">{$lang->text('delete')}</a>
						</div><!-- END SECONDARY -->
HTML;
				}
				
				else if (UserSession::is_authorized()) {
					$possessive = NaturalLanguage::possessive("{$comment->firstName} {$comment->lastName}");
					$html .= <<<HTML

						<div class="secondary">
							<a href="/" title="{$lang->text('flag_user_comment', $possessive)}" class="flag">{$lang->text('flag')}</a>
						</div><!-- END SECONDARY -->
HTML;
				}
				$html .= <<<HTML
						
						<p class="item-image"><a href="/user/{$comment->vanityURL}" title="{$userVerbage}"><img src="$userAvatar" alt="{$userDisplayName}"/></a></p>
						<h2><a href="/user/{$comment->vanityURL}" title="{$userVerbage}">{$userDisplayName}</a></h2>
						<p>{$comment->content}</p>
						<p class="comments">{$comment->date}</p>
					</article>
HTML;
			}
		}
		
		# no comments exist
		else {
			# if the user is authorized
			if (UserSession::is_authorized()){
				$html .= <<<HTML

					<p class="empty">{$lang->text('no_comments')}</p>
HTML;
			}
		}
		
		# add a comment, if authorized
		if (UserSession::is_authorized()) {
			$user = $_SESSION['user'];
			$userVerbage = NaturalLanguage::view_profile($user->id,"{$user->firstName} {$user->lastName}");
			$userDisplayName = NaturalLanguage::display_name($user->id,"{$user->firstName} {$user->lastName}");
			$userAvatar = (file_exists("images/avatars/{$user->avatar}_medium.jpg"))?"/images/avatars/{$user->avatar}_medium.jpg":"/images/site/user-default_medium.jpg";
			
			$html .= <<<HTML

					<article>
						<p class="item-image"><a href="/user/{$user->vanityURL}" title="{$userVerbage}"><img src="$userAvatar" alt="{$userDisplayName}"/></a></p>
						<h2><a href="/user/{$user->vanityURL}" title="{$userVerbage}">{$userDisplayName}</a></h2>
HTML;
			$html .= render('wddsocial.view.form.WDDSocial\CommentView');
			$html .= <<<HTML

					</article>
HTML;
		}
		
		# not authorized, signin?
		else {
			$html .= <<<HTML

					<p class="empty">{$lang->text('signin_required')}<a href="/signin" title="{$lang->text('signin_title')}">{$lang->text('signin_link')}</a></p>
HTML;
		}
		
		return $html;
	}
}