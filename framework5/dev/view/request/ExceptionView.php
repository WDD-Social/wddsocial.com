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
				<td>{$options->message}</td>
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
		<br/><br/>
HTML;
		
		$trace_data = unserialize($options->trace);
		if ($trace_data) {
			$html.= <<<HTML

			<h3>Backtrace</h3>
HTML;
			$html.= $this->render_backtrace($trace_data);
		}
		
		
		# render table end
		$html.= <<<HTML

			</table>
HTML;
		
		# return output
		return $html;
	}
	
	
	
	private function render_backtrace($trace_data) {
		foreach ($trace_data as $index => $trace) {
			
			# begin backtrace table
			$html.= <<<HTML

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
				<td>function</td>
				<td>{$trace['function']}</td>
			</tr>
			
			<tr>
				<td>class</td>
				<td>{$trace['class']}</td>
			</tr>
			
			<tr>
				<td>type</td>
				<td>{$trace['type']}</td>
			</tr>
HTML;
			
			# backtrace arguments
			foreach ($trace['args'] as $arg_index => $arg_value) {
				$html.= <<<HTML
			<tr>
				<td>[argument {$arg_index}]</td>
				<td>{$arg_value}</td>
			</tr>
HTML;
			}
			
			# end backtrace table
			$html.= <<<HTML

			</table>
			<br/><br/>
HTML;
		}
		
		return $html;
	}
}