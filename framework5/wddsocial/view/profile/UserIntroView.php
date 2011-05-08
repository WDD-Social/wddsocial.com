<?php

namespace WDDSocial;

/*
* Displays the user profile intro section
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class UserIntroView implements \Framework5\IView {
	/**
	* Creates user intro, with name and background information
	*/
	
	public function render($user = null){
		
		if (!isset($user)) {
			throw new \Exception("UserIntroView required option 'user' was not set");
		}
		
		# get dependencies
		$root = \Framework5\Request::root_path();
		$lang = new \Framework5\Lang('wddsocial.lang.view.UserLang');
		$userDisplayName = NaturalLanguage::display_name($user->id,"{$user->firstName} {$user->lastName}");
		$userAvatar = (file_exists("{$root}/images/avatars/{$user->avatar}_full.jpg"))?"{$root}/images/avatars/{$user->avatar}_full.jpg":"{$root}images/site/user-default_full.jpg";
		
		# content
		$html = <<<HTML

				<section id="user" class="mega with-secondary">
					<h1>$userDisplayName</h1>
HTML;
		
		if (UserSession::is_current($user->id)) {
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="{$root}" title="{$lang->text('edit_your_profile')}" class="edit">{$lang->text('edit')}</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		$userIntro = static::getUserIntro($user);
		$html .= <<<HTML
					
					<img src="$userAvatar" alt="{$user->firstName} {$user->lastName}" />
					<p>$userIntro</p>
					<div class="large">
						<h2>{$lang->text('bio')}</h2>
						<p>{$user->bio}</p>
					</div><!-- END BIO -->
					<div class="small">
						<h2>{$lang->text('likes')}</h2>
						<ul>
HTML;
		foreach ($user->extra['likes'] as $like) {
			$html .= <<<HTML

							<li><a href="{$root}/search/$like" title="$like">$like</a></li>
HTML;
		}
		$html .= <<<HTML

						</ul>
					</div><!-- END LIKES -->
					<div class="small no-margin">
						<h2>{$lang->text('dislikes')}</h2>
						<ul>
HTML;
		foreach ($user->extra['dislikes'] as $dislike) {
			$html .= <<<HTML

							<li><a href="{$root}/search/$dislike" title="$dislike">$dislike</a></li>
HTML;
		}
		$html .= <<<HTML

						</ul>
					</div><!-- END DISLIKES -->
				</section><!-- END USER -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates user intro sentence
	*/
	
	private static function getUserIntro($user){
		$root = \Framework5\Request::root_path();
		$sentence = (UserSession::is_current($user->id))?"<strong>You</strong> are a":"<strong>{$user->firstName}</strong> is a";
		
		if(isset($user->age)){
			$sentence .= " <strong>{$user->age}-year-old</strong>";
		}
		if($user->type == 'Student'){
			if(isset($user->age)){
				$sentence .= ",";
			}else{
				$sentence .= " an";
			}
			if(isset($user->extra['location'])){
				$sentence .= " <strong>{$user->extra['location']}</strong>";
			}
		}
		$userType = strtolower($user->type);
		$sentence .= " <strong>{$userType}</strong>";
		if(isset($user->hometown)){
			$sentence .= " from <strong>{$user->hometown}</strong>";
		}
		switch ($user->type) {
			case 'Student':
				if(isset($user->extra['startDate'])){
					$sentence .= " who began Full Sail in <strong>{$user->extra['startDate']}</strong>";
				}
				break;
			case 'Teacher':
				if(isset($user->extra['courses']) && count($user->extra['courses']) > 0){
					$sentence .= " who teaches";
					for($i =0; $i < count($user->extra['courses']); $i++){
						if($i == count($user->extra['courses'])-1){
							$sentence .= " and <strong><a href=\"{$root}course/{$user->extra['courses'][$i][id]}\" title=\"{$user->extra['courses'][$i][title]}\">{$user->extra['courses'][$i][id]}</a></strong>";
						}else{
							$sentence .= " <strong><a href=\"{$root}course/{$user->extra['courses'][$i][id]}\" title=\"{$user->extra['courses'][$i][title]}\">{$user->extra['courses'][$i][id]}</a></strong>,";
						}
					}
				}
				break;
			case 'Alum':
				if(isset($user->extra['graduationDate'])){
					$sentence .= " who graduated in <strong>{$user->extra['graduationDate']}</strong>";
				}
				if(isset($user->extra['employerTitle'])){
					$employerLink = (isset($user->extra['employerLink']))?'http://'.$user->extra['employerLink']:"http://www.google.com/search?q={$user->extra['employerTitle']}";
					if(isset($user->extra['graduationDate'])){
						$sentence .= ", and";
					}else{
						$sentence .= " who";
					}
					$sentence .= " works for <strong><a href=\"$employerLink\" title=\"{$user->extra['employerTitle']}\">{$user->extra['employerTitle']}</a></strong>";
				}
				break;
		}
		$sentence .= ".";
		return $sentence;
	}
}