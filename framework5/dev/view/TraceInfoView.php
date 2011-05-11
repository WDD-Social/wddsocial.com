<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class TraceInfoView implements \Framework5\IView {	
	
	public function render($trace_array = null) {
		
		$lb = '<br/>';
		
		$html = <<<HTML
		<h2>Trace Info</h2>

HTML;
		
		foreach ($trace_array as $trace) {
			$html .= <<<HTML
		timestamp: {$trace->timestamp} message: {$trace->message} $lb

HTML;
		}
		
		return $html;
	}
}