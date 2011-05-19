<?php

namespace Framework5\Dev;

/*
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class ExecPage implements \Framework5\IExecutable {
	
	public function __construct() {
		$this->db = instance('core.controller.Framework5\Database');
	}
	
	
	public function execute() {
		if (!set($_POST['submit'])) {
			$content = 'command required';
		}
		
		else {
			$command = $_POST['cmd'];
			$cmd = explode(' ', $command);
			
			switch ($cmds[0]) {
				case 'requests':
					redirect('/dev/requests');
					break;
				
				case 'request':
					if (!set($cmd[1])) $error = 'request requires param id';
					else redirect("/dev/request/{$cmd[1]}");
					break;
				
				case 'bugs':
					redirect('/dev/bugs');
					break;
				
				case 'phpinfo':
					redirect('/dev/phpinfo');
				
				default:
					$error = "invalid command '{$cmd[0]}'";
			}
			
			
			$content = "exec {$cmd}<br/>";
			
			if ($error) $content.= "error: {$error}";
		}
		
		
		# display output
		echo render('dev.view.Framework5\Dev\TemplateView',
			array('title' => 'Execute Command', 'content' => $content));
	}
}