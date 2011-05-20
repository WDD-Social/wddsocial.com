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
			<table style="border-spacing:5px">
			<tr>
				<td>Messsage</td>
				<td>File</td>
				<td>Line</td>
				<td>Time Difference</td>
				<td>Memory Difference</td>
				<td>Total Memory</td>
			</tr>
			
HTML;
		
		# render table data
		$debug_data = unserialize($options->data);
		if ($debug_data) {
			$row = 0;
			foreach ($debug_data as $data) {
				
				# format data
				$data->formattedMemory = BytesConverter::format($data->memory);
				if ($row > 0) {
					$data->memoryDiff = "+".BytesConverter::format($data->memory - $lastMemory);
					$data->timeDiff = "+".number_format($data->time - $lastTime, 9);
				}
				else {
					$data->memoryDiff = '-';
					$data->timeDiff = '-';
				}
				
				# render content
				$html.= <<<HTML
			<tr>
				<td>{$data->message}</td>
				<td><a href="#" title="{$data->path}">{$data->file}</a></td>
				<td>{$data->line}</td>
				<td>{$data->timeDiff}</td>
				<td>{$data->memoryDiff}</td>
				<td>{$data->formattedMemory}</td>
			</tr>
HTML;
				# save result to calculate difference
				$lastMemory = $data->memory;
				$lastTime = $data->time;
				$row++;
				
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