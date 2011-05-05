<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class MediaInputs implements \Framework5\IView {		
	
	public static function render($options = null) {
		$html = <<<HTML

						<h1 id="media">Media</h1>
						<fieldset>
							<label for="image1">Images</label>
HTML;
		# display image uploaders
		for ($i = 1; $i < 4; $i++) {
			$html .= <<<HTML

							<input type="file" name="images[]" id="image$i" />
HTML;
		}
		$html .= <<<HTML

							<a href="#" title="Add Another Image" class="add-more">Add Another Image</a>
						</fieldset>
						<fieldset>
							<label for="video1">Videos</label>
HTML;
		for ($j = 1; $j < 2; $j++) {
			$html .= <<<HTML

							<input type="text" name="videos[]" id="video$i" />
HTML;
		}
		$html .= <<<HTML

							<small><strong>YouTube</strong> or <strong>Vimeo</strong> Embed Code</small>
							<a href="#" title="Add Another Video" class="add-more">Add Another Video</a>
						</fieldset>
HTML;
		return $html;
	}
}