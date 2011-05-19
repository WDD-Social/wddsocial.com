<?php

namespace Framework5\Dev;

/**
* Renders execution trace data
* 
*/

class TraceView implements \Framework5\IView {	
	
	public function render($trace_array = null) {
		
		$html = <<<HTML
		<h2>Trace Info</h2>

HTML;
		
		foreach ($trace_array as $trace) {
			$html .= <<<HTML
		<p>timestamp: {$trace->timestamp} - message: {$trace->message} $lb</p>

HTML;
		}
		
		return $html;
	}
}