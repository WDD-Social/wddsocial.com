<?php

namespace WDDSocial;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class RemovePage implements \Framework5\IExecutable {
	public function execute() {
		UserSession::protect();
	}
}