<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ImageInputs implements \Framework5\IView {		
	
	public static function render($options = null) {
		$html = <<<HTML

						<h1 id="images">Images</h1>
HTML;
		# display image uploaders
		for ($i = 1; $i < 4; $i++) {
			$html .= <<<HTML

						<fieldset>
							<label for="image-titles$i">Image $i</label>
							<input type="text" name="image-titles[]" id="image-titles$i" placeholder="Enter Image $i Title" />
							<input type="file" name="image-files[]" id="image-files$i" />
						</fieldset>
HTML;
		}
		$html .= <<<HTML

						<a href="#" title="Add Another Image" class="add-more">Add Another Image</a>
HTML;
		return $html;
	}
}