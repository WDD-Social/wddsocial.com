<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class IndexPage implements \Framework5\IExecutable {
	
	public function execute() {
		echo render('dev.view.Framework5\Dev\PageHeader');
	}
}