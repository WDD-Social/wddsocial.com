<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class CategoryInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$query = $db->query($sql->getThreeRandomCategories);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$categories = $query->fetchAll();
		$html = <<<HTML

						<h1 id="categories">Categories</h1>
						<fieldset>
HTML;
		for ($i = 1; $i < 4; $i++) {
			$html .= <<<HTML

							<input type="text" name="categories[]" id="category$i" placeholder="{$categories[$i-1]->title}" />
HTML;
		}
		$html .= <<<HTML

							<a href="#" title="Add Another Category" class="add-more">Add Another Category</a>
						</fieldset>
HTML;
		return $html;
	}
}