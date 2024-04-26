<?php 
session_start();
if (isset($_SESSION["user"])) {
	header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="regis.css">
	<title>Regis form</title>
</head>
<body>
	<div class="container">
		<div class="logo" style="margin-left: 115px; margin-bottom: 30px;">
			<img src="unpam.png">
		</div>
		<form action="registration.php" method="post">
			<?php
			if (isset($_POST["submit"])) {
				$fullName = $_POST["fullname"];
				$email = $_POST["email"];
				$password = $_POST["password"];
				$passwordRepeat = $_POST["repeat_password"];

				$passwordHash = password_hash($password, PASSWORD_DEFAULT);

				$errors = array();

				if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
					array_push($errors, "All fields arr required");
				}
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					array_push($errors, "Email is not valid");
				}
				if (strlen($password)<4) {
					array_push($errors, "Password atleast must 4 characters long");
				}
				if ($password!==$passwordRepeat) {
					array_push($errors, "Password does not match");
				}
				require_once "database.php";
				$sql = "SELECT * FROM users WHERE email = '$email'";
				$result = mysqli_query($conn, $sql);
				$rowCount = mysqli_num_rows($result);
				if ($rowCount>0) {
					array_push($errors, "Email already exist!");
				}

				if (count($errors)>0) {
					foreach ($errors as $error) {
						echo "<div class='alert alert-danger'>$error</div>";
					}
				}else {
					
					$sql = "INSERT INTO users(full_name, email, password) VALUES(?, ?, ?)";
					$stmt = mysqli_stmt_init($conn);
					$prepareStmt = mysqli_stmt_prepare($stmt, $sql);
					if ($prepareStmt) {
						mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
						mysqli_stmt_execute($stmt);
						echo "<div class='alert alert-success'>You are registered</div>";
					}else{
						die("Something went wrong");
					}
				}
			}
			?>
			<div class="form-group">
				<input type="text" class="form-control" name="fullname" placeholder="Full Name : ">
			</div>
			<div class="form-group">
				<input type="email" class="form-control" name="email" placeholder="Email : ">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" name="password" placeholder="Password : ">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password : ">
			</div>
			<div class="form-btn">
				<input type="submit" class="btn btn-primary" value="Register" name="submit">
			</div>
		</form>
		<div>
			<div><p>Already Registered <a href="login.php" style="text-decoration: none;">Login here</a></p></div>
		</div>
	</div>
</body>
</html>