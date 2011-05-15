<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class LikesDislikesInputs implements \Framework5\IView {		
	
	public function render($options = null) {
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$randomLimit = count($options['likes']) + 3;
		$query = $db->query($sql->getRandomCategories . " LIMIT $randomLimit");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$categories = $query->fetchAll();
		
		$html .= <<<HTML

						<h1 id="likes">Likes</h1>
						<p>What technologies and topics do you love so much that you could marry?</p>
						<fieldset>
HTML;
		$i = 1;
		foreach ($options['likes'] as $like) {
			$html .= <<<HTML

							<input type="text" name="likes[]" id="like$i" placeholder="{$categories[$i-1]->title}" value="{$like}" />
HTML;
			$i++;
		}
		for ($i; $i < $randomLimit; $i++) {
			$html .= <<<HTML

							<input type="text" name="likes[]" id="like$i" placeholder="{$categories[$i-1]->title}" />
HTML;
		}
		$html .= <<<HTML

						</fieldset>
						<a href="#" title="Add Another Like" class="add-more">Add Another Like</a>
						<input type="submit" name="submit" value="Save" />
HTML;
		
		$randomLimit = count($options['dislikes']) + 3;
		$query = $db->query($sql->getRandomCategories . " LIMIT $randomLimit");
		$query->execute();
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$categories = $query->fetchAll();
		
		$html .= <<<HTML

						<h1 id="dislikes">Dislikes</h1>
						<p>What technologies and topics do you hate so much that you wish you could cast them into the fiery depths of Mordor?</p>
						<fieldset>
HTML;
		$i = 1;
		foreach ($options['dislikes'] as $dislike) {
			$html .= <<<HTML

							<input type="text" name="dislikes[]" id="dislike$i" placeholder="{$categories[$i-1]->title}" value="{$dislike}" />
HTML;
			$i++;
		}
		for ($i; $i < $randomLimit; $i++) {
			$html .= <<<HTML

							<input type="text" name="dislikes[]" id="dislike$i" placeholder="{$categories[$i-1]->title}" />
HTML;
		}
		$html .= <<<HTML

						</fieldset>
						<a href="#" title="Add Another Dislike" class="add-more">Add Another Dislike</a>
						<input type="submit" name="submit" value="Save" />
HTML;
		return $html;
	}
}