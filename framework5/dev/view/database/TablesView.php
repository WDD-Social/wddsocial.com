<?php

namespace Framework5\Dev;

/*
* 
* @author Tyler Matthews (tmatthewsdev@gmail.com)
*/

class TablesView implements \Framework5\IView {
	
	/**
	* 
	*/
	
	public function render($tables = null) {
		
		# table head
		$html = <<<HTML

		<table id="database-tables" class="dev">
		<tr>
			<td>Table</td>
		</td>
HTML;
		
		# rows
		foreach ($tables as $table) {
		$html.= <<<HTML

		<tr>
			<td><a href="/dev/database/table/{$table}">{$table}</a></td>
		</td>
HTML;
		}
		
		# end table
		$html.= <<<HTML

		</table>
HTML;
		
		return $html;
	}
}