<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class PageHeader implements \Framework5\IView {	
	
	public function render($options = null) {
		
	return <<<HTML
	<h1>Framework5 Developer</h1>
	<ul>
		<li><a href="requests/">requests</a></li>
		<li><a href="phpinfo/">phpinfo</a></li>
	</ul>

HTML;
	}
}