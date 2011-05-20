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
		
<!doctype html>
<html>
	<head>
		<title>CodeMirror 2: PHP mode</title>
		<link rel="shortcut icon" href="/images/site/framework5-favicon.ico">
		
		<link rel="stylesheet" href="/js/libs/codemirror/lib/codemirror.css">
		<script src="/js/libs/codemirror/lib/codemirror.js"></script>
		
		<script src="/js/libs/codemirror/mode/xml/xml.js"></script>
		<link rel="stylesheet" href="/js/libs/codemirror/mode/xml/xml.css">
		
		<script src="/js/libs/codemirror/mode/javascript/javascript.js"></script>
		<link rel="stylesheet" href="/js/libs/codemirror/mode/javascript/javascript.css">
		
		<script src="/js/libs/codemirror/mode/css/css.js"></script>
		<link rel="stylesheet" href="/js/libs/codemirror/mode/css/css.css">
		
		<script src="/js/libs/codemirror/mode/clike/clike.js"></script>
		<link rel="stylesheet" href="/js/libs/codemirror/mode/clike/clike.css">
		<script src="/js/libs/codemirror/mode/php/php.js"></script>
		
		<style type="text/css">.CodeMirror {border-top: 1px solid black; border-bottom: 1px solid black;}</style>
		<link rel="stylesheet" href="/js/libs/codemirror/css/docs.css">
	</head>
	<body>
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
			$html.= <<<HTML

		<form>
			<textarea id="code" name="code">
{$contents}
			</textarea>
		</form>
		
		<script>
		var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
			lineNumbers: true,
			matchBrackets: true,
			mode: "application/x-httpd-php",
			indentUnit: 4,
			indentWithTabs: true,
			enterMode: "keep",
			tabMode: "shift"
		});
		</script>
	</body>
</html>
HTML;
		
		}
		
		else {
			$html.= 'invalid file';
		}
		
		# display page
		echo $html;
	}
}