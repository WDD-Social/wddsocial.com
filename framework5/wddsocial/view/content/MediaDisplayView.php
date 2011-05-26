<?php

namespace WDDSocial;

/*
* Displays media information, such related to a project 
* 
* @author Anthony Colangelo (me@acolangelo.com)
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class MediaDisplayView implements \Framework5\IView {
	
	public function render($options = null) {
	
		$lang = new \Framework5\Lang('wddsocial.lang.CommonLang');
		
		$imagesClass = ($options['active'] == 'images')?' class="current"':'';
		$videosClass = ($options['active'] == 'videos')?' class="current"':'';
		
		$html = <<<HTML

					<div class="secondary cardstack">
						<a href="{$options['base_link']}" title="{$lang->text('related-images')}"$imagesClass>{$lang->text('images')}</a> 
						<a href="{$options['base_link']}/videos" title="{$lang->text('related-videos')}"$videosClass>{$lang->text('videos')}</a>
					</div><!-- END SECONDARY -->
					<div class="{$options['active']}">
HTML;
		
		# display an image or video
		switch ($options['active']) {
			case 'images':
				if ($options['type'] != 'job') {
					# Remove first image in array
					array_shift($options['content']->images);
				}
				if (count($options['content']->images) > 0) {
					foreach ($options['content']->images as $image) {
						$html .= <<<HTML

						<a href="/images/uploads/{$image->file}_full.jpg" title="{$image->title}" class="fancybox" rel="fancybox-media"><img src="/images/uploads/{$image->file}_large.jpg" alt="{$image->title}"/></a>
HTML;
					}
				}
				
				else {
					$html .= <<<HTML

						<p class="empty">{$lang->text('no_images')}</p>
HTML;
				}
				break;
			case 'videos':
				if (count($options['content']->videos) > 0) {
					foreach($options['content']->videos as $video){
						$html .= <<<HTML

						{$video->embedCode}
HTML;
					}
				}else{
					$html .= <<<HTML

						<p class="empty">{$lang->text('no_videos')}</p>
HTML;
				}
				
				break;
		}
		
		$html .= <<<HTML

					</div><!-- END {$options['active']} -->
HTML;
		
		return $html;
	}
}