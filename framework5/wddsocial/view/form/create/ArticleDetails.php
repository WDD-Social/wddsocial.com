<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ArticleDetails implements \Framework5\IView {		
	
	public static function render($options = null) {
		$html .= render('wddsocial.view.form.create.WDDSocial\PrivacyLevels');
		return $html;
	}
}