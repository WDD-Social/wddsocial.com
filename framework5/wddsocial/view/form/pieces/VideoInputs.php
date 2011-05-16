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
		# display video uploaders
		$limit = (isset($options['videos']))?count($options['videos']) + 2:2;
		$i = 1;
		if (isset($options['videos'])) {
			foreach ($options['videos'] as $video) {
				$videoNumber = ($i == 1)?'':" $i";
				$embedCode = htmlspecialchars($video->embedCode);
				$html .= <<<HTML

						<fieldset>
							<label for="video$i">Video$videoNumber</label>
							<input type="text" name="videos[]" id="video$i" value="{$embedCode}" />
						</fieldset>
HTML;
				$i++;
			}
		}
		for ($i; $i < $limit; $i++) {
			$videoNumber = ($i == 1)?'':" $i";
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