<?php

namespace Framework5;

/*
* 
*/

class View extends Controller {
	
	public static function render($package_name, $options) {
		
		debug("Rendering package '$package_name'");
		
		# if the package has not been loaded, import it
		if (!loaded($package_name)) import($package_name);
		
		# get the class name
		$package = new Package($package_name);
		$controller = $package->fully_qualified;
		
		# check if class implents the Framework5\IScript interface
		if (!Factory::implement($controller, 'Framework5\IView'))
			throw new Exception("Package '$package_name' could not be rendered, class '$controller' does not implement interface '\Framework5\IView'");
		
		# return the result
		return $controller::render($options);
	}
	
}