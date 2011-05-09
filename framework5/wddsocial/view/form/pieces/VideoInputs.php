<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class VideoInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$html = <<<HTML

						<h1 id="videos">Videos</h1>
						<p>Please provide the <strong><a href="http://youtube.com/" title="YouTube - Broadcast Yourself.">YouTube</a></strong> or <strong><a href="http://vimeo.com/" title="Vimeo, Video Sharing For You">Vimeo</a></strong> embed codes.</p>
HTML;
		# display image uploaders
		for ($i = 1; $i < 2; $i++) {
			if ($i == 1) {
				$videoNumber = '';
			}
			else {
				$videoNumber = " $i";
			}
			$html .= <<<HTML

						<fieldset>
							<label for="video$i">Video$videoNumber</label>
							<input type="text" name="videos[]" id="video$i" />
						</fieldset>
HTML;
		}
		$html .= <<<HTML

						<a href="#" title="Add Another Video" class="add-more">Add Another Video</a>
HTML;
		return $html;
	}
}