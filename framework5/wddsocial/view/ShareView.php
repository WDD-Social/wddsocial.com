<?php

class ShareView implements \Framework5\IView {	
	
	# CREATES THE SHARE FORM
	public static function render($options = null) {
		return <<<HTML

				<section id="share" class="small no-margin side-sticky">
					<h1>Share</h1>
					<form action="form.html" method="post">
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
				</section><!-- END SHARE -->
HTML;
	}
}