<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public static function execute() {
		echo "{developer page}<br/>";
		echo "{navigation} [<a href=\"requests/\">requests</a>]";
		
	}
}