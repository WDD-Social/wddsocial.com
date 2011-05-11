<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public function execute() {
		echo "{developer page}<br/>";
		echo "<a href=\"requests/\">requests</a> | <a href=\"phpinfo/\">phpinfo</a>";
		
	}
}