<?php
include_once('header.php');
?>
<head>
	<title>Registracija - Klop.com</title>
	<link rel="stylesheet" href="styles/register.css">
</head>
<?php

// Funkcija preveri, ali v bazi obstaja uporabnik z določenim imenom in vrne true, če obstaja.
function username_exists($username){
	global $conn;
	$username = mysqli_real_escape_string($conn, $username);
	$query = "SELECT * FROM users WHERE username='$username'";
	$res = $conn->query($query);
	return mysqli_num_rows($res) > 0;
}

// Funkcija ustvari uporabnika v tabeli users. Poskrbi tudi za ustrezno šifriranje uporabniškega gesla.
function register_user($username, $password, $email, $name, $surname, $address, $post, $phone){
	global $conn;
	$username = mysqli_real_escape_string($conn, $username);
	$pass = sha1($password);
	$email = mysqli_real_escape_string($conn, $email);
	$name = mysqli_real_escape_string($conn, $name);
	$surname = mysqli_real_escape_string($conn, $surname);
	$address = mysqli_real_escape_string($conn, $address);
	$post = mysqli_real_escape_string($conn, $post);
	$phone = mysqli_real_escape_string($conn, $phone);
	/* 
		Tukaj za hashiranje gesla uporabljamo sha1 funkcijo. V praksi se priporočajo naprednejše metode, ki k geslu dodajo naključne znake (salt).
		Več informacij: 
		http://php.net/manual/en/faq.passwords.php#faq.passwords 
		https://crackstation.net/hashing-security.htm
	*/
	$query = "INSERT INTO users (username, password, email, name, surname, address, post, phone) VALUES ('$username', '$pass', '$email', '$name', '$surname', '$address', '$post', '$phone');";
	if($conn->query($query)){
		return true;
	}
	else{
		echo mysqli_error($conn);
		return false;
	}
}

$error = "";
if(isset($_POST["submit"])){
	/*
		VALIDACIJA: preveriti moramo, ali je uporabnik pravilno vnesel podatke (unikatno uporabniško ime, dolžina gesla,...)
		Validacijo vnesenih podatkov VEDNO izvajamo na strežniški strani. Validacija, ki se izvede na strani odjemalca (recimo Javascript), 
		služi za bolj prijazne uporabniške vmesnike, saj uporabnika sproti obvešča o napakah. Validacija na strani odjemalca ne zagotavlja
		nobene varnosti, saj jo lahko uporabnik enostavno zaobide (developer tools,...).
	*/
	//Preveri če se gesli ujemata
	if($_POST["password"] != $_POST["repeat_password"]){
		$error = "Gesli se ne ujemata.";
	}
	//Preveri ali uporabniško ime obstaja
	else if(username_exists($_POST["username"])){
		$error = "Uporabniško ime je že zasedeno.";
	}
	//Podatki so pravilno izpolnjeni, registriraj uporabnika
	else if(register_user($_POST["username"], $_POST["password"], $_POST["email"], $_POST["name"], $_POST["surname"], $_POST["address"], $_POST["post"], $_POST["phone"])){
		header("Location: login.php");
		die();
	}
	//Prišlo je do napake pri registraciji
	else{
		$error = "Prišlo je do napake med registracijo uporabnika.";
	}
}

?>
	<div class="container">
	<div class="heading">Registracija</div>
		<div class="form">
			<form action="register.php" method="POST">
				<div class="f_main">
					<div class="labels">
						<label>Uporabniško ime<span>*</span></label>
						<label>E-Naslov<span>*</span></label>
						<label>Ime<span>*</span></label>
						<label>Priimek<span>*</span></label>
						<label>Naslov</label>
						<label>Pošta</label>
						<label>Telefon</label>
						<label>Geslo<span>*</span></label>
						<label>Ponovi geslo<span>*</span></label>
					</div>
					<div class="inputs">
						<input type="text" name="username" required/>
						<input type="email" name="email" required/>
						<input type="text" name="name" required/>
						<input type="text" name="surname" required/>
						<input type="text" name="address"/>
						<input type="text" name="post"/>
						<input type="tel" name="phone"/>
						<input type="password" name="password" required/>
						<input type="password" name="repeat_password" required/>
					</div>
				</div>
				<div class="warning">Polja označena z <span>*</span> so obvezna</div>
				<input type="submit" name="submit" value="Pošlji"/> <br/>
				<label class="error"><?php echo $error; ?></label>
			</form>
		</div>
	</div>
<?php
include_once('footer.php');
?>