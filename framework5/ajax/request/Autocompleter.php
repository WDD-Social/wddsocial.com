<?php

namespace Ajax;

/**
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Autocompleter implements \Framework5\IExecutable {
	
	public function execute() {
		
		# disable logging
		\Framework5\Settings::$log_debug = false;
		\Framework5\Settings::$log_execution = false;
		\Framework5\Settings::$log_exception = false;
		
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		
		switch ($_POST['type']) {
			case 'users':
				$response->results = $this->get_users($_POST['term']);
				$response->status = (count($response->results) > 0)?true:false;
				echo json_encode($response);
				break;
			case 'categories':
				$response->results = $this->get_categories($_POST['term']);
				$response->status = (count($response->results) > 0)?true:false;
				echo json_encode($response);
				break;
			case 'courses':
				$response->results = $this->get_courses($_POST['term']);
				$response->status = (count($response->results) > 0)?true:false;
				echo json_encode($response);
				break;
		}
	}
	
	private function get_users($term){
		$query = $this->db->prepare($this->sql->autocompleteUsers . ' LIMIT 0, 10');
		$query->execute(array('term' => "$term%"));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		return $query->fetchAll();
	}
	
	private function get_categories($term){
		$query = $this->db->prepare($this->sql->autocompleteCategories . ' LIMIT 0, 10');
		$query->execute(array('term' => "$term%"));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		return $query->fetchAll();
	}
	
	private function get_courses($term){
		$query = $this->db->prepare($this->sql->autocompleteCourses . ' LIMIT 0, 10');
		$query->execute(array('term' => "$term%"));
		$query->setFetchMode(\PDO::FETCH_OBJ);
		return $query->fetchAll();
	}
}