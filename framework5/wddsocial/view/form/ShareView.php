<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ShareView implements \Framework5\IView {		
	
	public function render($options = null) {
		
		$lang = new \Framework5\Lang('wddsocial.lang.view.form.ShareViewLang');
		
		return <<<HTML

					<form action="/create" method="post" class="{$options['class']}">
						<p class="error"><strong>{$options['error']}</strong></p>
						<fieldset>
							<label for="title">{$lang->text('title')}</label>
							<input type="text" name="title" id="title" />
						</fieldset>
						<fieldset class="radio">
							<label>{$lang->text('type')}</label>
							<div>
								<input type="radio" id="project" name="type" value="project" checked />
								<label for="project">{$lang->text('project')}</label>
								
								<input type="radio" id="article" name="type" value="article" />
								<label for="article">{$lang->text('article')}</label>
								
								<input type="radio" id="event" name="type" value="event" />
								<label for="event">{$lang->text('event')}</label>
								
								<input type="radio" id="job" name="type" value="job" />
								<label for="job">{$lang->text('job')}</label>
							</div>
						</fieldset>
						<input type="submit" name="submit" value="{$lang->text('create')}" />
					</form>
HTML;
	}
}