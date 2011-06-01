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
				if (is_object($video)) {
					$embedCode = htmlspecialchars($video->embedCode);
				}
				else {
					$embedCode = htmlspecialchars($video);
				}
				$html .= <<<HTML

						<fieldset>
							<input type="text" name="videos[]" id="video" value="{$embedCode}" />
						</fieldset>
HTML;
				$i++;
			}
		}
		for ($i; $i < $limit; $i++) {
			$html .= <<<HTML

						<fieldset>
							<input type="text" name="videos[]" id="video" />
						</fieldset>
HTML;
		}
		$html .= <<<HTML

						<a href="" title="Add Another Video" class="add-more">Add Another Video</a>
HTML;
		return $html;
	}
}