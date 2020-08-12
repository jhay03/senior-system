<?php
	
	include('../../connection.php');

	// For New Member
	if($_GET['action'] == "add"){
		$usercode = $_POST['usercode'];
		$username = $_POST['username'];
		$password = md5($_POST['password']);
		$fullname = $_POST['fullname'];
		$userrole = $_POST['userrole'];

		// Save New Member
		$mysqli -> query("INSERT INTO auth_users_tbl VALUES('','$usercode','$username','$password','$fullname','$userrole')");
		header('Location:user_maintenance.php');
	}

	// For Updating Details
	if($_GET['action'] == "update"){
		$return = $_GET['return'];
		$userid   = $_POST['modified_userid'];
		$usercode = $_POST['modified_usercode'];
		$username = $_POST['modified_username'];
		$fullname = $_POST['modified_fullname'];
		$userrole = $_POST['modified_userrole'];

		// Update Information
		$mysqli -> query("UPDATE auth_users_tbl SET auth_usercode='$usercode',
													auth_username='$username',
													auth_fullname='$fullname',
													auth_role='$userrole' 
												WHERE auth_id='$userid'");
		if($return == "role"){
			header('Location:team_roles.php');
		}else{
			header('Location:user_maintenance.php');
		}
	}

	// For Delete Details
	if($_GET['action'] == "remove"){
		$userid   = $_POST['delete_userid'];

		// Delete Information
		$mysqli -> query("DELETE FROM auth_users_tbl WHERE auth_id='$userid'");
		header('Location:user_maintenance.php');
	}

	// For Assign District
	if($_GET['action'] == "assign"){
		$userid = $_POST['userid'];

		// Remove pre-assigned district first
		$mysqli -> query("DELETE FROM district_assignment WHERE user_code='$userid'");

		// Insert newly assigned district
		$district = $_POST['district'];
		foreach ($district as $value) {
			//convert value to array
			$arrValue = explode(',', $value);
			$finalDistrict = $arrValue[0];
			$finalCount = $arrValue[1];
			$mysqli -> query("INSERT INTO district_assignment VALUES('','$finalDistrict','$finalCount','$userid')");
		}
		header('Location:district_assignment.php');
	}

	// Reset District Assignment
	if($_GET['action'] == "reset"){

		// Delete Information
		$mysqli -> query("TRUNCATE district_assignment");
		header('Location:district_assignment.php');
	}

	// Reset District Assignment
	if($_GET['action'] == "reset_user"){
		$userid = $_GET['id'];
		// Delete Information
		$mysqli -> query("DELETE FROM district_assignment WHERE user_code='$userid'");
		header('Location:district_assignment.php');
	}

?>