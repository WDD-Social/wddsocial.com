<?php

namespace WDDSocial;

/*
* Displays a user in the global People directory
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class DirectoryCourseItemView implements \Framework5\IView {
	
	public function render($course = null) {
		if (count($course->team) > 0) {
			$teacher = $course->team[array_rand($course->team)];
			$avatar = (file_exists("images/avatars/{$teacher->avatar}_medium.jpg"))?"/images/avatars/{$teacher->avatar}_medium.jpg":'/images/site/job-default_medium.jpg';
		}
		else {
			$avatar = '/images/site/job-default_medium.jpg';
		}
		$html = <<<HTML

					<article>
						<p class="item-image"><a href="/course/{$course->id}" title="{$course->title}"><img src="$avatar" alt="{$course->title}"/></a></p>
						<h2><a href="/course/{$course->id}" title="{$course->title}">{$course->title}</a></h2>
						<p>{$course->description}</p>
					</article><!-- END {$course->title} -->
HTML;
		return $html;
	}
}