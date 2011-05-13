<?php

/*
* WDD Social: Language Pack for view.content.EventLocationDisplayView
*/

class MediaDisplayLang implements \Framework5\ILanguagePack {
	
	
	public static function content($id, $var) {
		switch ($id) {
			
			case 'no_images':
				return 'Welp! No images have been added, so this page will look a little plain...';
			case 'no_videos':
				return 'Uh oh, no videos have been added.';
		}
	}
}