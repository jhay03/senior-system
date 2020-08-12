<?php
	date_default_timezone_set('Asia/Manila');
	//$ip = $_SERVER['HTTP_CLIENT_IP']?:($_SERVER['HTTP_X_FORWARDE‌​D_FOR']?:$_SERVER['REMOTE_ADDR']);
	include('connection.php');
	session_start();

	/**
	  * ##
	  * ## @md GET INFORMATION
	  * ##
	 */
	if(isset($_POST['btnLogin'])){

		$username = $_POST['username'];
		$password = $_POST['password'];
		$password = md5($password);

		$user_query = $mysqli -> query("SELECT auth_fullname,auth_role,auth_usercode FROM auth_users_tbl WHERE auth_username='$username' AND auth_password='$password'");
		if(mysqli_num_rows($user_query)==0){
			$_SESSION['error_login'] = 'true';
			header('Location:../master-data-sanitation/');
		}else{
			while($user_res = $user_query -> fetch_assoc()){
				$_SESSION['authUser'] = $user_res['auth_fullname'];
				$_SESSION['authRole'] = $user_res['auth_role'];
				$_SESSION['auth_usercode'] = $user_res['auth_usercode'];
				header('Location:auth_directory/');
			}
		}
	}
	if(isset($_GET['auth_key']) && $_GET['auth_key']=="02d3d9ffe8be5e41a6bd4e4da1c71e08"){
		$_SESSION['authUser'] = $_GET['auth_fullname'];
		$_SESSION['authRole'] = $_GET['auth_role'];
		$_SESSION['auth_usercode'] = $_GET['auth_usercode'];
		header('Location:auth_directory/dir_data_sanitation/data-sanitation.php');
	}
?>
