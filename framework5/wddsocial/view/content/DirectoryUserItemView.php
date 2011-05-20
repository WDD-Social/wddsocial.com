<?php

namespace WDDSocial;

/*
* Displays a user in the global People directory
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class DirectoryUserItemView implements \Framework5\IView {
	
	public function render($options = null) {
		$person = $options['content'];
		$userVerbage = NaturalLanguage::view_profile($person->id,"{$person->firstName} {$person->lastName}");
		$userDisplayName = NaturalLanguage::display_name($person->id,"{$person->firstName} {$person->lastName}");
		$userAvatar = (file_exists("images/avatars/{$person->avatar}_medium.jpg"))?"/images/avatars/{$person->avatar}_medium.jpg":"/images/site/user-default_medium.jpg";
		$userIntro = $this->getUserIntro($person);
		$html = <<<HTML

					<article class="people">
						<p class="item-image"><a href="/user/{$person->vanityURL}" title="{$userVerbage}"><img src="$userAvatar" alt="$userDisplayName"/></a></p>
						<h2><a href="/user/{$person->vanityURL}" title="{$userVerbage}">$userDisplayName</a></h2>
						<p>{$userIntro}</p>
						<p>Joined {$person->date}</p>
					</article><!-- END {$person->title} -->
HTML;
		return $html;
	}
	
	
	
	/**
	* Creates user intro sentence
	*/
	
	private function getUserIntro($user){
			$sentence = 'A';
		
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
					if (count($user->extra['courses']) == 1) {
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