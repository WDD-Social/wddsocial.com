<?php

namespace WDDSocial;
/*
*
* @author: Anthony Colangelo (me@acolangelo.com)
*
*/
class SearchBuilder{
	
	public function __construct(){
		$this->people = array('firstName','lastName','vanityURL','c.title');
	}
	
	public function build($term,$type){
		$terms = explode(' ', $term);
		$final = array();
		switch ($type) {
			case 'people':
				foreach ($this->people as $searchField) {
					foreach ($terms as $searchTerm) {
						array_push($final,"$searchField LIKE '%$searchTerm%'");
					}
				}
				break;
		}
		return implode(' OR ',$final);
	}
}

/* EXAMPLE

import('wddsocial.helper.WDDSocial\SearchBuilder');
$sql = instance(':sel-sql');

$search = new SearchBuilder();

$queryString = $search->build('anthony colangelo html5','people');

echo "<pre>";
echo ($sql->searchPeople . "$queryString ORDER BY lastName ASC" . " LIMIT 0, 20");
echo "</pre>";
*/