<?php

namespace Framework5;

class ExceptionView implements IView {
	
	public function render($options = null) {
		$e = $options;
		/*
		echo "<pre>";
		print_r($e);
		echo "<pre>";
		*/
		$html = <<<HTML
		
		<p>
			ApplicationBase::exception_error() killed execution with message: {$e->getMessage()}
			thrown in {$e->getFile()} on line {$e->getLine()}
			Exception->getTrace()
		</p>
HTML;
		
		$get_trace = $e->getTrace();
		foreach($get_trace as $trace) {
		$html .= <<<HTML
		<table>
			<tr>
				<td>file</td>
				<td>{$trace['file']}</td>
			</tr>
			<tr>	
				<td>line</td>
				<td>{$trace['line']}</td>
			</tr>
			<tr>	
				<td>method</td>
				<td>{$trace['function']}</td>
			</tr>
			<tr>	
				<td>Namespace</td>
				<td>{$trace['class']}</td>
			</tr>

HTML;
			if ($trace['args']) {
				foreach ($trace['args'] as $arg) {
					$html .= <<<HTML
			<tr>
				<td>arg</td>
				<td>$arg</td>
			</tr>

HTML;
					
				}
			}
		
		$html .= <<<HTML
		</table>

HTML;
			
		}
		return $html;
	}
}
