<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ShareView implements \Framework5\IView {		
	
	public static function render($options = null) {
		$root = \Framework5\Request::root_path();
		return <<<HTML

					<form action="{$root}" method="post">
						<p class="error"><strong></strong></p>
						<fieldset>
							<label for="title">Title</label>
							<input type="text" name="title" id="title" />
						</fieldset>
						<fieldset class="radio">
							<label>Type</label>
							<div>
								<input type="radio" id="project" name="type" checked />
								<label for="project">Project</label>
								
								<input type="radio" id="article" name="type" />
								<label for="article">Article</label>
								
								<input type="radio" id="event" name="type" />
								<label for="event">Event</label>
								
								<input type="radio" id="job" name="type" />
								<label for="job">Job</label>
							</div>
						</fieldset>
						<input type="submit" value="Create" />
					</form>
HTML;
	}
}