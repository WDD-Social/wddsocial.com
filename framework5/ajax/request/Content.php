<?php

namespace Ajax;

/**
* Gets basic content
* 
* @author Anthony Colangelo (me@acolangelo.com)
*/

class Content implements \Framework5\IExecutable {
	
	public function execute() {
		
		# check user auth
		if (!\WDDSocial\UserSession::is_authorized()) redirect('/');
		
		$this->db = instance(':db');
		$this->sql = instance(':sel-sql');
		
		switch ($_POST['section']) {
			case 'getUserDetail':
				echo $this->getUserDetail($_POST['usertype'], $_POST['userID']);
				break;
		}
	}
	
	
	
	private function getUserDetail($userType, $userID){
		$query = $this->db->prepare($this->sql->getUserDetailByID);
		$query->execute(array('id' => $userID));
		$query->setFetchMode(\PDO::FETCH_ASSOC);
		$result = $query->fetch();
		
		switch ($userType) {
			case 'student':
				return render('wddsocial.view.form.pieces.WDDSocial\StudentDetailInputs', $result);
				break;
			case 'teacher':
				import('wddsocial.model.WDDSocial\CourseVO');
				$query = $this->db->prepare($this->sql->getTeacherCoursesByID);
		        $query->setFetchMode(\PDO::FETCH_CLASS,'WDDSocial\CourseVO');
				$query->execute(array('id' => $userID));
				$result['courses'] = array();
				while($course = $query->fetch()){
					array_push($result['courses'],$course);
				}
				return render('wddsocial.view.form.pieces.WDDSocial\TeacherDetailInputs', $result);
				break;
			case 'alum':
				return render('wddsocial.view.form.pieces.WDDSocial\AlumDetailInputs', $result);
				break;
		}
	}
}