<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class CommentEditView implements \Framework5\IView {		
	
	public function render($options = null) {
		$content = $options['data'];
		
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$data = array('id' => $content->id);
		$query = $db->prepare($sql->getCommentProject);
		$query->execute($data);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$result = $query->fetch();
		if ($result->contentID != NULL) {
			$final = $result;
		}
		else {
			$query = $db->prepare($sql->getCommentArticle);
			$query->execute($data);
			$query->setFetchMode(\PDO::FETCH_OBJ);
			$result = $query->fetch();
			if ($result->contentID != NULL) {
				$final = $result;
			}
			else {
				$query = $db->prepare($sql->getCommentEvent);
				$query->execute($data);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$result = $query->fetch();
				if ($result->contentID != NULL) {
					$final = $result;
				}
			}
		}
		
		if (isset($final)) {
			$redirect = "{$final->type}/{$final->vanityURL}#comments";
		}
		else {
			$redirect = "";
		}
		
		return <<<HTML

					<h1 class="mega">Edit Comment</h1>
					<form action="{$_SERVER['REQUEST_URI']}" method="post">
						<p class="error"><strong>{$options['error']}</strong></p>
						<input type="hidden" name="contentID" value="{$content->id}" />
						<input type="hidden" name="type" value="comment" />
						<input type="hidden" name="redirect" value="/{$redirect}" />
						<textarea name="content" placeholder="Write your comment...">{$content->content}</textarea>
						<input type="submit" name="submit" value="Save" />
					</form>
HTML;
	}
}