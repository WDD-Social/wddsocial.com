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
		
		$randomLimit = (isset($options['categories']))?count($options['categories']) + 2:4;
		$query = $db->query($sql->getRandomCategories . " LIMIT $randomLimit");
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$categories = $query->fetchAll();
		
		$html = <<<HTML

						<h1 id="categories">Categories</h1>
						<fieldset>
HTML;
		$i = 1;
		if (isset($options['categories'])) {
			foreach ($options['categories'] as $category) {
				$html .= <<<HTML

							<input type="text" name="categories[]" class="autocompleter" data-autocomplete="categories" autocomplete="off" placeholder="{$categories[$i-1]->title}" value="{$category->title}" />
HTML;
				$i++;
			}
		}
		for ($i; $i < $randomLimit; $i++) {
			$html .= <<<HTML

							<input type="text" name="categories[]" class="autocompleter" data-autocomplete="categories" autocomplete="off" placeholder="{$categories[$i-1]->title}" />
HTML;
		}
		$html .= <<<HTML

							<a href="" title="Add Another Category" class="add-more">Add Another Category</a>
						</fieldset>
HTML;
		return $html;
	}
}