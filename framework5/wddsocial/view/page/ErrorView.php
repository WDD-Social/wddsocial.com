<?php

namespace WDDSocial;

/*
* 
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class ErrorView implements \Framework5\IView {
	
	/**
	* 
	*/
	
	public function render($exception = null) {
		$html = <<<HTML

		<h1 class="mega">Ohh Noo! An error has occured.</h1>
		<p><iframe width="425" height="349" src="http://www.youtube.com/embed/zsdXmYGFBcU" frameborder="0" allowfullscreen></iframe></p>
		<p>Something went wrong the the internets, but we backtraced the problem and hope to fix everything shortly.</h1>
HTML;
		
		return $html;
	}
}