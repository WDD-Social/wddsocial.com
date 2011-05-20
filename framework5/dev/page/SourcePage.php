<?php

namespace Framework5\Dev;

/*
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
*/

class SourcePage implements \Framework5\IExecutable {
	
	public function execute() {
		$file = $_GET['file'];
		
		$html = <<<HTML
		<p>
			<form id="source-location" action="/dev/source" method="get">
				<input type="text" id="file" name="file" value="{$file}"/>
				<input type="submit" value="go"/>
			</form>
		</p>
HTML;
		
		
		if (file_exists($file)) {
			$handle = fopen($file, "r");
			$contents = fread($handle, filesize($file));
			fclose($handle);
			
			$html.= '<textarea>';
			$html.= $contents;
			$html.= '</textarea>';
		}
		
		else {
			$html.= 'invalid file';
		}
		
		# display page
		echo $html;
	}
}