<?php 

class Profile {

	private $username;

	public function __construct($username) {
		$this->username = $username;
	}

	public function fetchUser() {
		require_once "DatabaseConnection.php";

		$dbHandle = new DatabaseConnection();
		$connection = $dbHandle->getConnection();

		$selectStatement = $connection->prepare("SELECT username, name, email, role, facultyNumber, verificationCode FROM `users` WHERE username = :username");
		$result = $selectStatement->execute([
			'username' => $this->username
		]);
		$databaseUser = $selectStatement->fetch();

		return json_encode([
			'status' => true,
			'username' => $databaseUser['username'],
			'name' => $databaseUser['name'],
			'email' => $databaseUser['email'],
			'role' => $databaseUser['role'],
			'facultyNumber' => $databaseUser['facultyNumber'],
			'verificationCode' => $databaseUser['verificationCode']
		]);
	}
}

?>