<?php

namespace WDDSocial;
/*
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class UserVO {
	public $id, $language, $firstName, $lastName, $avatar, $bio, $hometown, $age, $type, $contact = array(), $email, $fullsailEmail, $vanityURL, $extra = array(), $completion = array();
	
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
		switch ($this->type) {
		    case 'Student':
		        $query = $db->prepare($sql->getStudentDetailByID);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$query->execute($data);
				$row = $query->fetch();
				$this->extra['startDate'] = $row->startDate;
				$this->extra['location'] = $row->location;
		        break;
		    case 'Teacher':
		        $query = $db->prepare($sql->getTeacherCoursesByID);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$query->execute($data);
				while($row = $query->fetch()){
					$this->extra[$row->courseID] = $row->title;
				}
		        break;
		    case 'Alum':
		        $query = $db->prepare($sql->getAlumDetailByID);
				$query->setFetchMode(\PDO::FETCH_OBJ);
				$query->execute($data);
				$row = $query->fetch();
				$this->extra['graduationDate'] = $row->graduationDate;
				$this->extra['employerTitle'] = $row->employerTitle;
				$this->extra['employerLink'] = $row->employerLink;
		        break;
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
		$this->completion['contact'] = $complete/$total;
	}
}