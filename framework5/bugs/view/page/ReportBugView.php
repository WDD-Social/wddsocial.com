<?php

namespace Framework5\Bugs;

/*
* 
* @author 
*/

class ReportBugView implements \Framework5\IView {
	
	public function render($error = null) {
		return <<<HTML
			<h1>What seems to be the problem?</h1>
			<p>Providing us with a detailed description of any problem you encounter is a step towards a user friendly community. Thank you for your support.</p>
			<h2>{$error}</h2>
			<form method="post">
				<textarea name="message" style="width: 97%;"></textarea>
				<input type="submit" name="submit" value="Report Issue"/>
			</form>

HTML;
	
	}
}