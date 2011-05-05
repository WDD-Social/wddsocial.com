<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class ArticleExtraInputs implements \Framework5\IView {		
	
	public static function render($options = null) {
		$html .= render('wddsocial.view.form.pieces.WDDSocial\PrivacyLevelSelector',1);
		return $html;
	}
}