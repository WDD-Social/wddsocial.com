<?php

namespace WDDSocial;
/*
* 
* 
* @author tmatthews (tmatthewsdev@gmail.com)
* @author: Anthony Colangelo (me@acolangelo.com)
*/

class UserVO {
	public $id, $language, $firstName, $lastName, $avatar, $bio, $hometown, $age, $type, $contact = array(), $email, $fullsailEmail, $vanityURL, $extra = array();
	
	public function __construct(){
		$db = instance(':db');
		$sql = instance(':sel-sql');
		
		$this->contact['website']=$this->website;
		$this->contact['twitter']=$this->twitter;
		$this->contact['facebook']=$this->facebook;
		$this->contact['github']=$this->github;
		$this->contact['dribbble']=$this->dribbble;
		$this->contact['forrst']=$this->forrst;
		
		$data = array('id' => $this->id);
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
}