<?php

namespace Framework5\Dev;

/**
* 
* 
*/

class ExceptionView implements \Framework5\IView {	
	
	public function render($options = null) {
		
		# determine if sent options is correct model
		if (!get_class($options) == 'ExceptionInfo')
			throw new Framework5\Exception("ExceptionInfoView expects parameter of type ExceptionInfo");
		
		
		# format data
		$options->timestamp = date("F j, Y, g:i a", $options->timestamp);
		
		# render output
		
		$html = <<<HTML

		<h2>an exception was caught</h2>
		<table>
			<tr>
				<td>Request ID</td>
				<td>{$options->request_id}</td>
			</tr>
			<tr>
				<td>Exception type</td>
				<td>{$options->type}</td>
			</tr>
			<tr>
				<td>Time</td>
				<td>{$options->timestamp}</td>
			</tr>
			<tr>
				<td>Message</td>
				<td>$options->message}</td>
			</tr>
			<tr>
				<td>Code</td>
				<td>{$options->code}</td>
			</tr>
			<tr>
				<td>File</td>
				<td>{$options->file}</td>
			</tr>
			<tr>
				<td>Line</td>
				<td>{$options->line}</td>
			</tr>
		</table>
		
		<h3>Backtrace</h3>
		<table>
			<tr>
				<td>Messsage</td>
				<td>File</td>
				<td>Line</td>
				<td>Time</td>
				<td>Memory</td>
			</tr>
		
HTML;
		
		$trace_data = unserialize($options->trace);
		if ($trace_data) {
		
		echo '<pre>';
		htmlspecialchars(print_r($trace_data));
		echo '</pre>';
		
			foreach ($trace_data as $trace) {
				$html.= <<<HTML
			<tr>
				<td>{$trace->message}</td>
				<td><a href="#" title="{$trace->path}">{$trace->file}</a></td>
				<td>{$trace->line}</td>
				<td>{$trace->time}</td>
			</tr>
HTML;
			}
		}
		
		# render table end
		$html.= <<<HTML

			</table>
HTML;
		
		
		# return output
		return $html;
	}
}