<?php

namespace WDDSocial;
/*
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class UserVO {
	public $id, $language, $firstName, $lastName, $avatar, $bio, $hometown, $age, $type, $typeID, $contact = array(), $email, $fullsailEmail, $vanityURL, $extra = array();
	
	public function __construct(){
		$this->contact['website']=$this->website;
		$this->contact['twitter']=$this->twitter;
		$this->contact['facebook']=$this->facebook;
		$this->contact['github']=$this->github;
		$this->contact['dribbble']=$this->dribbble;
		$this->contact['forrst']=$this->forrst;
		
		$this->getContactCompletion($this->contact);
		$this->getUserDetail($this->id);
		$this->getUserLikes($this->id);
		$this->getUserDislikes($this->id);
	}
	
	private function getUserDetail($id){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('id' => $id);
		$query = $db->prepare($sql->getUserDetailByID);
		$query->setFetchMode(\PDO::FETCH_ASSOC);
		$query->execute($data);
		$row = $query->fetch();
		$this->extra = $row;
		
		if ($this->type == 'teacher') {
			$this->extra['courses'] = array();
	        $query = $db->prepare($sql->getTeacherCoursesByID);
	        $query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\CourseVO');
			$query->execute($data);
			while($course = $query->fetch()){
				array_push($this->extra['courses'],$course);
			}
		}
	}
	
	private function getUserLikes($id){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('id' => $id);
		$this->extra['likes'] = array();
        $query = $db->prepare($sql->getUserLikesByID);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute($data);
		while($row = $query->fetch()){
			array_push($this->extra['likes'],$row->title);
		}
	}
	
	private function getUserDislikes($id){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		$data = array('id' => $id);
		$this->extra['dislikes'] = array();
        $query = $db->prepare($sql->getUserDislikesByID);
		$query->setFetchMode(\PDO::FETCH_OBJ);
		$query->execute($data);
		while($row = $query->fetch()){
			array_push($this->extra['dislikes'],$row->title);
		}
	}
	
	private function getContactCompletion($contacts){
		$complete = 0;
		$total = 0;
		foreach($contacts as $contact){
			$total++;
			if(isset($contact)){
				$complete++;
			}
		}
		//$this->contact['complete'] = $complete/$total;
	}
}