<?php 

class User {

	private $username;
	private $name;
	private $email;
	private $role;
	private $facultyNumber;
	private $password;

	public function __construct($username, $name, $email, 
								$role, $facultyNumber, $password) {
		$this->username = $username;
		$this->name = $name;
		$this->email = $email;
		$this->role = $role;
		if ($this->role == 'student') {
			$this->facultyNumber = $facultyNumber;
		} else {
			$this->facultyNumber = NULL;
		}
		$this->password = $password;
	}

	public function persistUser() {
		require_once "DatabaseConnection.php";

		$dbHandle = new DatabaseConnection();
		$connection = $dbHandle->getConnection();

		$sqlInsertStatement = "INSERT INTO `users` (`id`, `username`, `name`, `email`, `role`, `facultyNumber`, `password`) VALUES (NULL, :username, :name, :email, :role, :facultyNumber, :password)";
		$prepareStatement = $connection->prepare($sqlInsertStatement);
		$result = $prepareStatement->execute([
			'username' => $this->username,
			'name' => $this->name,
			'email' => $this->email,
			'role' => $this->role,
			'facultyNumber' => $this->facultyNumber,
			'password' => $this->password
		]);

		$response = null;

		if ($result) {
			$response = array(
		        "status" => true
		    );
		} else {
			$error = $prepareStatement->errorInfo();
			$response = array(
		        "status" => false,
		        "message" => "There was an error saving your data to the database: " . $error[2],
		        "errorCode" => $error[0]
		    );
		}

		return $response;
	}
}

?>