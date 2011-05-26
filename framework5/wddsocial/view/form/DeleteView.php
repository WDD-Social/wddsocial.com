<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class DeleteView implements \Framework5\IView {
	public function render($options = null) {
		$content = $options['content'];
		$capitalizedType = ucfirst($options['type']);
		$contentTitle = ($options['type'] == 'user')?'My Profile':"View $capitalizedType";
		$viewLink = "/{$options['type']}/{$content->vanityURL}";
		
		$sourceArray = explode('/',$options['source']);
		if ( ($options['source'] == $_SERVER['REQUEST_URI']) or ($sourceArray[0] == $options['type'] and $sourceArray[1] == $content->vanityURL) ) {
			$redirect = '';
		}
		else {
			$redirect = $options['source'];
		}
		
		if ($options['type'] == 'comment') {
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
				$viewLink = "/{$final->type}/{$final->vanityURL}#comments";
			}
			else {
				$viewLink = "/{$options['source']}#comments";
			}
		}
		
		switch ($options['type']) {
			case 'user':
				$deleteTitle = 'your account';
				$disclaimerText = ' All of your projects, articles, events, job postings, comments, and related images, videos, and comments will be erased.';
				break;
			case 'comment':
				$deleteTitle = 'your comment';
				$disclaimerText = '';
				break;
			default:
				$deleteTitle = "the {$content->type} &ldquo;{$content->title}&rdquo;";
				$disclaimerText = ' All related images, videos, and comments will be erased.';
				break;
		}
		
		$html .= <<<HTML

					<h1 class="mega form">Are you sure you want to delete $deleteTitle?</h1>
					<form action="{$_SERVER['REQUEST_URI']}" method="post">
						<h1>Attention</h1>
						<p class="error"><strong>{$options['error']}</strong></p>
						<p><strong>This action is final and cannot be undone</strong>.</p>
						<p><strong>Related content can not be retrieved</strong>.</p>
						<p>$disclaimerText</p>
						<input type="hidden" name="type" value="{$options['type']}" />
						<input type="hidden" name="id" value="{$content->id}" />
						<input type="hidden" name="redirect" value="/{$redirect}" />
						<div class="buttongroup">
							<input type="submit" name="submit" value="Delete" class="alert inline" />
							<a href="$viewLink" title="{$content->title}" class="button inline">{$contentTitle}</a>
						</div><!-- END BUTTON GROUP -->
					</form>
HTML;
		return $html;
	}
}