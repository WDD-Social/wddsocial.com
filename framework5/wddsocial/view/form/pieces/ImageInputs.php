<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ImageInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		
		if (isset($options['images']) and count($options['images']) > 0) {
			$html .= <<<HTML

						<h1 id="existing-images">Edit Existing Images</h1>
HTML;
			$i = 1;
			foreach ($options['images'] as $image) {
				$html .= <<<HTML

						<fieldset>
							<label for="existing-image-titles$i">Image $i</label>
							<img src="/images/uploads/{$image->file}_large.jpg" alt="{$image->title}" />
							<input type="hidden" name="existing-image-files[]" value="{$image->file}" />
							<input type="text" name="existing-image-titles[]" id="existing-image-titles$i" placeholder="Enter Image $i Title" value="{$image->title}" />
							<p>
								<input type="checkbox" name="existing-image-status[]" id="existing-image-status$i" value="{$image->file}" />
								<label for="existing-image-status$i" class="plain">Delete this image</label>
							</p>
						</fieldset>
HTML;
				$i++;
			}
		}
		
		$imagesHeader = (isset($options['images']))?'Add New Images':'Images';
		
		$html .= <<<HTML

						<h1 id="images">$imagesHeader</h1>
HTML;
		# display image uploaders
		for ($i = 1; $i < 4; $i++) {
			$html .= <<<HTML

						<fieldset>
							<label for="image-titles">Image</label>
							<input type="text" name="image-titles[]" placeholder="Enter Image Title" />
							<input type="file" name="image-files[]" />
						</fieldset>
HTML;
		}
		$html .= <<<HTML

						<a href="" title="Add Another Image" class="add-more">Add Another Image</a>
HTML;
		return $html;
	}
}