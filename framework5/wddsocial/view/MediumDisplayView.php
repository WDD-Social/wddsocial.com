<?php

class MediumDisplayView implements \Framework5\IView {	
	
	public static function render($options = null) {
		switch ($options['type']) {
			case 'project':
				return static::projectDisplay($options['content']);
			
			case 'article':
				return static::articleDisplay($options['content']);
			
			case 'person':
				return static::personDisplay($options['content']);
			
			default:
				throw new Exception("MediumDisplayView requires parameter type (project, article, or person), '{$options['type']}' provided");
		}
	}
	
	private static function projectDisplay($project){
		$root = \Framework5\Request::root_path();
		
		// NEED TO ADJUST FOR APOSTROPHE!!!!!!!!
		// 		Example: Colangelo's vs. Matthews'
		
		$userVerbage = ($project->userID === $_SESSION['user']->id)?"View Your Profile":"View {$project->userFirstName} {$project->userLastName}'s Profile";
		$userDisplayName = ($project->userID === $_SESSION['user']->id)?"You":"{$project->userFirstName} {$project->userLastName}";
		
		
		/* CREATE TEAM INTRO
if(count($project->team) > 1){
			$teamIntro = " with ";
			if(count($project->team) === 3){
				
			}else{
				for($i = 0; $i < count($project->team); $i++){
					if($i === count($project->team)-1){
						
					}else{
						
					}
				}
			}
		}else{
			$teamIntro = "";
		}
*/
		
		$html = <<<HTML

					<article class="projects with-secondary">
						<div class="secondary">
HTML;
		if($project->userID === $_SESSION['user']->id){
			$html .= <<<HTML

							<a href="#" title="Edit &ldquo;{$project->title}&rsquo;" class="edit">Edit</a>
							<a href="#" title="Delete &ldquo;{$project->title}&rsquo;" class="delete">Delete</a>
HTML;
		}else{
			$html .= <<<HTML

							<a href="#" title="Flag &ldquo;{$project->title}&rsquo;" class="flag">Flag</a>
HTML;
		}	
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="{$root}/user/{$project->userURL}" title="{$userVerbage}"><img src="images/avatars/{$project->userAvatar}_medium.jpg" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="{$root}/user/{$project->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> posted a <strong><a href="{$root}/project/{$project->vanityURL}" title="{$project->title}">project</a></strong> WITH TEAM.</p>
						<h2><a href="{$root}/project/{$project->vanityURL}" title="{$project->title}">{$project->title}</a></h2>
						<p>{$project->description}</p>
						<!--<p class="images">
							<a href="project.html" title="The Daily Syndication | Image 1"><img src="images/uploads/dailysyndication01_smedium.jpg" alt="The Daily Syndication | Image 1"/></a>
							<a href="project.html" title="The Daily Syndication | Image 2"><img src="images/uploads/dailysyndication02_smedium.jpg" alt="The Daily Syndication | Image 2"/></a>
							<a href="project.html" title="The Daily Syndication | Image 3"><img src="images/uploads/dailysyndication03_smedium.jpg" alt="The Daily Syndication | Image 3"/></a>
						</p>-->
						<p class="comments"><a href="{$root}/project/{$project->vanityURL}#comments" title="{$project->title} | Comments">{$project->comments} comments</a> <span class="hidden">|</span> <span class="time">{$project->date}</span></p>
HTML;
		$tagLinks = array();
		foreach($project->tags as $tag){
			array_push($tagLinks,"<a href=\"{$root}/search/$tag\" title=\"Categories | $tag\">$tag</a>");
		}
		$tagLinks = implode(' ',$tagLinks);
		$html .= <<<HTML
						<p class="tags">$tagLinks</p>
					</article><!-- END {$project->title} -->
HTML;
		return $html;
	}
	
	private static function articleDisplay($article){
		$root = \Framework5\Request::root_path();
		
		// NEED TO ADJUST FOR APOSTROPHE!!!!!!!!
		// 		Example: Colangelo's vs. Matthews'
		
		$userVerbage = ($article->userID === $_SESSION['user']->id)?"View Your Profile":"View {$article->userFirstName} {$article->userLastName}'s Profile";
		$userDisplayName = ($article->userID === $_SESSION['user']->id)?"You":"{$article->userFirstName} {$article->userLastName}";
		
		$html = <<<HTML

					<article class="articles with-secondary">
						<div class="secondary">
HTML;
		if($article->userID === $_SESSION['user']->id){
			$html .= <<<HTML

							<a href="#" title="Edit &ldquo;{$article->title}&rsquo;" class="edit">Edit</a>
							<a href="#" title="Delete &ldquo;{$article->title}&rsquo;" class="delete">Delete</a>
HTML;
		}else{
			$html .= <<<HTML

							<a href="#" title="Flag &ldquo;{$article->title}&rsquo;" class="flag">Flag</a>
HTML;
		}	
		$html .= <<<HTML

						</div><!-- END SECONDARY -->
						
						<p class="item-image"><a href="{$root}/user/{$article->userURL}" title="{$userVerbage}"><img src="images/avatars/{$article->userAvatar}_medium.jpg" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="{$root}/user/{$article->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> wrote an <strong><a href="{$root}/article/{$article->vanityURL}" title="{$article->title}">article</a></strong>.</p>
						<h2><a href="{$root}/article/{$article->vanityURL}" title="{$article->title}">{$article->title}</a></h2>
						<p>{$article->description}</p>
						<p class="comments"><a href="{$root}/article/{$article->vanityURL}#comments" title="{$article->title} | Comments">{$article->comments} comments</a> <span class="hidden">|</span> <span class="time">{$article->date}</span></p>
HTML;
		$tagLinks = array();
		foreach($article->tags as $tag){
			array_push($tagLinks,"<a href=\"{$root}/search/$tag\" title=\"Categories | $tag\">$tag</a>");
		}
		$tagLinks = implode(' ',$tagLinks);
		$html .= <<<HTML
						<p class="tags">$tagLinks</p>
					</article><!-- END {$article->title} -->
HTML;
		return $html;
	}
	
	private static function personDisplay($person){
		$root = \Framework5\Request::root_path();
		
		// NEED TO ADJUST FOR APOSTROPHE!!!!!!!!
		// 		Example: Colangelo's vs. Matthews'
		
		$userVerbage = ($person->userID === $_SESSION['user']->id)?"View Your Profile":"View {$person->userFirstName} {$person->userLastName}'s Profile";
		$userDisplayName = ($person->userID === $_SESSION['user']->id)?"You":"{$person->userFirstName} {$person->userLastName}";
		
		$html = <<<HTML

					<article class="people">
						<p class="item-image"><a href="{$root}/user/{$person->userURL}" title="{$userVerbage}"><img src="images/avatars/{$person->userAvatar}_medium.jpg" alt="$userDisplayName"/></a></p>
						<p class="intro"><strong><a href="{$root}/user/{$person->userURL}" title="{$userVerbage}">$userDisplayName</a></strong> joined the community.</p>
						<p>{$person->description}</p>
						<p>{$article->date}</p>
					</article><!-- END {$person->title} -->
HTML;
		return $html;
	}
}