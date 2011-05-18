<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class DeleteView implements \Framework5\IView {
	public function render($options = null) {
		$content = $options['content'];
		$capitalizedType = ucfirst($options['type']);
		$contentTitle = ($options['type'] == 'user')?'My Profile':"View $capitalizedType";
		$deleteTitle = ($options['type'] == 'user')?'your account':"the {$content->type} &ldquo;{$content->title}&rdquo;";
		$disclaimerText = ($options['type'] == 'user')?
			' All of your projects, articles, events, job postings, comments, and related images, videos, and comments will be erased.':
			' All related images, videos, and comments will be erased.';
		$html .= <<<HTML

					<h1 class="mega form">Are you sure you want to delete $deleteTitle?</h1>
					<form action="{$_SERVER['REQUEST_URI']}" method="post">
						<h1>Attention</h1>
						<p class="error"><strong>{$options['error']}</strong></p>
						<p><strong>This action is final and cannot be undone</strong>.</p>
						<p><strong>Related content can not be retrieved</strong>.</p>
						<p>$disclaimerText</p>
						<div class="buttongroup">
							<input type="hidden" name="type" value="{$options['type']}" />
							<input type="hidden" name="id" value="{$content->id}" />
							<input type="submit" name="submit" value="Delete" class="alert inline" />
							<a href="/{$options['type']}/{$content->vanityURL}" title="{$content->title}" class="button inline">{$contentTitle}</a>
						</div><!-- END BUTTON GROUP -->
					</form>
HTML;
		return $html;
	}
}