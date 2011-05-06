<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class CommentDisplayView implements \Framework5\IView {
	
	public static function render($comments = null) {
		
		$root = \Framework5\Request::root_path();
		$html = "";
		$commentCount = count($comments);
		$commentVerbage = 'comment';
		if ($commentCount > 1 || $commentCount < 1) $commentVerbage .= 's';
		
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
				
				$html .= <<<HTML

					<article class="with-secondary">
HTML;
				
				if (UserSession::is_current($comment->userID)) {
					$html .= <<<HTML

						<div class="secondary">
							<a href="{$root}" title="Edit Your Comment" class="edit">Edit</a> 
							<a href="{$root}" title="Delete Your Comment" class="delete">Delete</a>
						</div><!-- END SECONDARY -->
HTML;
				}
				
				else if (UserSession::is_authorized()) {
					$possessive = NaturalLanguage::possessive("{$comment->firstName} {$comment->lastName}");
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
		}
		
		# no comments exist
		else {
			# if the user is authorized
			if (UserSession::is_authorized()){
				$html .= <<<HTML

					<p class="empty">No one has commented yet, why don&rsquo;t you start the conversation?</p>
HTML;
			}
		}
		
		# add a comment, if authorized
		if (UserSession::is_authorized()) {
			$user = $_SESSION['user'];
			$userVerbage = NaturalLanguage::view_profile($user->id,"{$user->firstName} {$user->lastName}");
			$userDisplayName = NaturalLanguage::display_name($user->id,"{$user->firstName} {$user->lastName}");
			
			$html .= <<<HTML

					<article>
						<p class="item-image"><a href="{$root}user/{$user->vanityURL}" title="{$userVerbage}"><img src="{$root}images/avatars/{$user->avatar}_medium.jpg" alt="{$userDisplayName}"/></a></p>
						<h2><a href="{$root}user/{$user->vanityURL}" title="{$userVerbage}">{$userDisplayName}</a></h2>
HTML;
			$html .= render('wddsocial.view.form.WDDSocial\CommentView');
			$html .= <<<HTML

					</article>
HTML;
		}
		
		# not authorized, signin?
		else {
			$html .= <<<HTML

					<p class="empty">You must be signed in to add a comment. <a href="{$root}signin" title="Sign In to WDD Social">Would you like to sign in?</a></p>
HTML;
		}
		
		return $html;
	}
}