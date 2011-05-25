<?php

namespace WDDSocial;

/*
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class BasicElements implements \Framework5\IView {		
	
	public function render($options = null) {
		switch ($options['section']) {
			case 'header':
				return $this->header($options);
				break;
			case 'footer':
				return $this->footer($options);
				break;
			case 'save':
				return $this->save($options);
				break;
		}
	}
	
	/**
	* Displays form header and basic inputs
	*/
	
	private function header($options){
		import('wddsocial.helper.WDDSocial\StringCleaner');
		
		if (isset($options['data']) and is_array($options['data'])) {
			$content->type = $options['data']['type'];
			$content->title = $options['data']['title'];
		}
		else if (is_object($options['data'])) {
			$content = $options['data'];
		}
		
		$capitalizedTitle = ucfirst($content->type);
			
		if ($content->type == 'article') {
			$contentTitle = 'Article Content';
			$textareaClass = ' class="long"';
			$required = ' *';
		}
		else {
			$contentTitle = 'Long Description';
			$textareaClass = '';
			$required = '';
		}
		
		$titleAutofocus = '';
		$descriptionAutofocus = '';
		if ($content->title == '') {
			$titleAutofocus = ' autofocus';
		}
		else {
			$descriptionAutofocus = ' autofocus';
		}
		
		switch (\Framework5\Request::segment(0)) {
			case 'create':
				$h1 = "Create a New {$capitalizedTitle}";
				$action = '/create';
				break;
			case 'edit':
				$h1 = "Edit {$capitalizedTitle}";
				$action = "{$_SERVER['REQUEST_URI']}";
				break;
		}
		
		return <<<HTML

					<h1 class="mega">$h1</h1>
					<form action="{$action}" method="post" enctype="multipart/form-data">
						<h1>Details</h1>
						<p class="error"><strong>{$options['error']}</strong></p>
						<input type="hidden" name="contentID" value="{$content->id}" />
						<input type="hidden" name="type" value="{$content->type}" />
						<input type="hidden" name="process" value="{$options['process']}" />
						<fieldset>
							<label for="title">$capitalizedTitle Title *</label>
							<input type="text" name="title" id="title" value="{$content->title}"$titleAutofocus />
						</fieldset>
						<fieldset>
							<label for="description">Short Description *</label>
							<textarea name="description" id="description" class="short"$descriptionAutofocus>{$content->description}</textarea>
							<small>Keep it short, <span class="count">128</span> characters left</small>
						<fieldset>
							<label for="content">$contentTitle$required</label>
							<textarea name="content" id="content"$textareaClass>{$content->content}</textarea>
							<small>You&rsquo;ve got <span class="count">65,536</span> characters left to use, so make it count.</small>
						</fieldset>
HTML;
	}
	
	
	
	/**
	* Displays form footer
	*/
	
	private function footer(){
		
		switch (\Framework5\Request::segment(0)) {
			case 'create':
				$buttonText = "Create";
				break;
			case 'edit':
				$buttonText = "Save";
				break;
			case 'postajob':
				$buttonText = "Post Job";
				break;
		}
		
		return <<<HTML

						<input type="submit" name="submit" value="$buttonText" />
					</form>
HTML;
	}
	
	
	
	/**
	* Displays save button
	*/
	
	private function save(){
		
		switch (\Framework5\Request::segment(0)) {
			case 'edit':
				$buttonText = "Save";
				break;
		}
		
		return <<<HTML

						<input type="submit" name="submit" value="$buttonText" />
HTML;
	}
}