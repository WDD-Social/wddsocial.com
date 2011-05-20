<?php

namespace WDDSocial;

/*
* Displays the overview of a project, article, or job listing.
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class CourseOverviewView implements \Framework5\IView {
	
	public function render($content = null) {

		$lang = new \Framework5\Lang('wddsocial.lang.view.content.DisplayViewLang');
		
		
		$html .= <<<HTML

					<div class="large">
						<h2>{$lang->text('description')}</h2>
						<p>{$content->description}</p>
					</div>
					<div class="small">
						<h2>Categories</h2>
						<ul>
HTML;
		if (count($content->categories) > 0) {
			foreach ($content->categories as $category) {
				$html .= <<<HTML

							<li><a href="/search/{$category->title}" title="{$lang->text('categories')} | {$category->title}">{$category->title}</a></li>
HTML;
			}
		}
		else {
			$html .= <<<HTML

							<p class="empty">Must be a boring course...</p>
HTML;
		}
		$html .= <<<HTML

						</ul>
					</div>
HTML;
		return $html;
	}
}