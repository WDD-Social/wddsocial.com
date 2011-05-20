<?php

namespace Framework5\Dev;

/*
* 
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class TableDescriptionView implements \Framework5\IView {
	
	public function render($table_desc = null) {
		
		$html = <<<HTML

		<table id="db-table-info" class="dev">
		<tr>
HTML;
		
		foreach (array_keys($table_desc[0]) as $key) {
			$html.= <<<HTML

			<td>{$key}</td>
HTML;
		}
		
		$html.= <<<HTML

		</tr>
HTML;
		
		foreach ($table_desc as $desc) {
			$html.= <<<HTML

		<tr>
HTML;
			foreach (array_values($desc) as $value) {
				$html.= <<<HTML

			<td>{$value}</td>
HTML;
			}
			
			$html.= <<<HTML

		</tr>
HTML;

		}
		
		$html.= <<<HTML

		</table>
HTML;
		
		return $html;
	}
}