<?php
include_once('header.php');
?>
<head>
	<title>Prijava - Klop.com</title>
	<link rel="stylesheet" href="styles/login.css">
</head>
<?php

function validate_login($username, $password){
	global $conn;
	$username = mysqli_real_escape_string($conn, $username);
	$pass = sha1($password);
	$query = "SELECT * FROM users WHERE username='$username' AND password='$pass'";
	$res = $conn->query($query);
	if($user_obj = $res->fetch_object()){
		return $user_obj->id;
	}
	return -1;
}

$error="";
if(isset($_POST["submit"])){
	//Preveri prijavne podatke
	if(($user_id = validate_login($_POST["username"], $_POST["password"])) >= 0){
		//Zapomni si prijavljenega uporabnika v seji in preusmeri na index.php
		$_SESSION["USER_ID"] = $user_id;
		header("Location: index.php");
		die();
	} else{
		$error = "Prijava ni uspela.";
	}
}
?>
	<div class="container">
	<div class="heading">Prijava</div>
		<div class="form">
			<form action="login.php" method="POST">
				<div class="f_main">
					<div class="labels">
						<label>Uporabniško ime</label>
						<label>Geslo</label>
					</div>
					<div class="inputs">
						<input type="text" name="username"/>
						<input type="password" name="password"/>
					</div>
				</div>
				<input type="submit" name="submit" value="Prijava"/>
				<label class="error"><?php echo $error; ?></label>
			</form>
		</div>
	</div>
<?php
include_once('footer.php');
?>