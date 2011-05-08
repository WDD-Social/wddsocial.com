<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ShareView implements \Framework5\IView {		
	
	public function render($options = null) {
		$root = \Framework5\Request::root_path();
		return <<<HTML

					<form action="{$root}create" method="post">
						<p class="error"><strong>{$options['error']}</strong></p>
						<fieldset>
							<label for="title">Title</label>
							<input type="text" name="title" id="title" />
						</fieldset>
						<fieldset class="radio">
							<label>Type</label>
							<div>
								<input type="radio" id="project" name="type" value="project" checked />
								<label for="project">Project</label>
								
								<input type="radio" id="article" name="type" value="article" />
								<label for="article">Article</label>
								
								<input type="radio" id="event" name="type" value="event" />
								<label for="event">Event</label>
								
								<input type="radio" id="job" name="type" value="job" />
								<label for="job">Job</label>
							</div>
						</fieldset>
						<input type="submit" name="submit" value="Create" />
					</form>
HTML;
	}
}