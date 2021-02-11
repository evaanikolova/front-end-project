<?php 

session_start();

if (isset($_SESSION['username'])) {
	
	$username = $_SESSION['username'];

	require_once "../../php/Profile.php";
	$profile = new Profile($username);
	echo $profile->fetchUser();

} else {
	echo json_encode([
		'status' => false
	]);
}

?>