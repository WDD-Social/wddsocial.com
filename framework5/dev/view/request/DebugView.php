<?php

namespace Framework5\Dev;

/**
* Renders execution debug data
* 
*/

class DebugView implements \Framework5\IView {	
	
	public function render($options = null) {
		
		# determine if sent options is correct model
		if (!get_class($options) == 'DebugInfo')
			throw new \Framework5\Exception("DebugInfoView expects parameter of type DebugInfo");
		
		# render table head
		$html = <<<HTML

			<h2>Debug Data</h2>
			<table>
			<tr>
				<td>Messsage</td>
				<td>File</td>
				<td>Line</td>
				<td>Time</td>
				<td>Memory</td>
			</tr>
			
HTML;
		
		# render table data
		$debug_data = unserialize($options->data);
		if ($debug_data) {
			foreach ($debug_data as $data) {
				$data->memory = BytesConverter::format($data->memory);
				$html.= <<<HTML
			<tr>
				<td>{$data->message}</td>
				<td><a href="#" title="{$data->path}">{$data->file}</a></td>
				<td>{$data->line}</td>
				<td>{$data->time}</td>
				<td>{$data->memory}</td>
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