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
			throw new Exception("UserIntroView required option 'user' was not set");
		}
		
		# get dependencies
		$lang = new \Framework5\Lang('wddsocial.lang.page.global.UserPageLang');
		$userDisplayName = NaturalLanguage::display_name($user->id,"{$user->firstName} {$user->lastName}");
		$userAvatar = (file_exists("images/avatars/{$user->avatar}_full.jpg"))?"/images/avatars/{$user->avatar}_full.jpg":"/images/site/user-default_full.jpg";
		
		# content
		$html = <<<HTML

				<section id="user" class="mega with-secondary">
					<h1>$userDisplayName</h1>
HTML;
		
		if (UserSession::is_current($user->id)) {
			$html .= <<<HTML

					<div class="secondary icons">
						<a href="/account" title="{$lang->text('edit_your_profile')}" class="edit">{$lang->text('edit')}</a>
					</div><!-- END SECONDARY -->
HTML;
		}
		$userIntro = $this->getUserIntro($user);
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

							<li><a href="/search/$like" title="$like">$like</a></li>
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

							<li><a href="/search/$dislike" title="$dislike">$dislike</a></li>
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
	
	private function getUserIntro($user){
		$sentence = (UserSession::is_current($user->id))?"<strong>You</strong> are":"<strong>{$user->firstName}</strong> is";
		
		if($user->age != ''){
			$aan = ($user->age == 18)?'an':'a';
			$sentence .= " $aan <strong>{$user->age}-year-old</strong>";
		}
		if($user->type == 'Student'){
			if(isset($user->extra['location']) and $user->extra['location'] != '' and isset($user->age) and $user->age != ''){
				$sentence .= ",";
			}
			if(isset($user->extra['location']) and $user->extra['location'] != ''){
				if ($user->age == '')
					$sentence .= " an";
				$sentence .= " <strong>{$user->extra['location']}</strong>";
			}
		}
		$userType = strtolower($user->type);
		if ($user->age == '') {
			if ($userType == 'teacher')
				$sentence .= " a";
			else if ($userType == 'alum')
				$sentence .= " an";
			else if ($userType == 'student' and $user->extra['location'] == '')
				$sentence .= " a";
		}
		$sentence .= " <strong>{$userType}</strong>";
		if($user->hometown != ''){
			$sentence .= " from <strong><a href=\"http://maps.google.com/?q={$user->hometown}\" title=\"Search Google Maps for {$user->hometown}\">{$user->hometown}</a></strong>";
		}
		switch ($user->type) {
			case 'Student':
				if(isset($user->extra['startDate'])){
					$sentence .= " who began Full Sail in <strong>{$user->extra['startDate']}</strong>";
				}
				break;
			case 'Teacher':
				if(isset($user->extra['courses']) and count($user->extra['courses']) > 0){
					$sentence .= " who teaches ";
					if (count($user->extra['courses'] == 1)) {
						$course = $user->extra['courses'][0];
						$sentence .= "<strong><a href=\"/course/{$course->id}\" title=\"{$course->title}\">{$course->id}</a></strong>";
					}
					else {
						$courses = array();
						foreach ($user->extra['courses'] as $course) {
							array_push($courses,"<strong><a href=\"/course/{$course->id}\" title=\"{$course->title}\">{$course->id}</a></strong>");
						}
						$sentence .= NaturalLanguage::comma_list($courses);	
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