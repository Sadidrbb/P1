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
	<title>Login Form</title>
</head>
<body>
	<?php  
		if (isset($_POST["login"])) {
			$email = $_POST["email"];
			$password = $_POST["password"];
			require_once "database.php";
			$sql = "SELECT * FROM users WHERE email = '$email'";
			$result = mysqli_query($conn, $sql);
			$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
			if ($user) {
				if (password_verify($password, $user["password"])) {
					session_start();
					$_SESSION["user"] = "yes";
					header("Location: index.php");
					die();
				}else{
					echo "<div class='alert alert-danger'>Password does not match</div>";
				}
			}else{
				echo "<div class='alert alert-danger'>Email does not match</div>";
			}
		}
	?>

	<div class="container">
		<div class="logo" style="margin-left: 115px; margin-bottom: 30px;">
			<img src="unpam.png">
		</div>

		<form action="login.php" method="post">
			<div class="form-group">
				<input type="email" placeholder="Email : " name="email" class="form-control">
			</div>
			<div class="form-group">
				<input type="password" placeholder="Password : " name="password" class="form-control">
			</div>
			<div class="form-btn">
				<input type="submit" value="Login" name="login" class="btn btn-primary">
			</div>
		</form>
		<div><p>Not registered yet? <a href="registration.php" style="text-decoration: none;">Register here</a></p></div>
	</div>

</body>
</html>