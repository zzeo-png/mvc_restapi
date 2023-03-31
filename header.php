<?php
	session_start();
	
	//Seja poteče po 30 minutah - avtomatsko odjavi neaktivnega uporabnika
	if(isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] < 1800){
		session_regenerate_id(true);
	}
	$_SESSION['LAST_ACTIVITY'] = time();
	
	//Poveži se z bazo
	$conn = new mysqli('localhost', 'root', '', 'klop.com');
	//Nastavi kodiranje znakov, ki se uporablja pri komunikaciji z bazo
	$conn->set_charset("UTF8");

	function is_admin($id){
		global $conn;
		$query = "SELECT is_admin FROM users WHERE id = $id;";
		$res = $conn->query($query);
		if($admin = $res->fetch_object()){
			return $admin->is_admin;
		}
		return 0;
	}

?>
<html>
<head>
	<link rel="icon" href="icon/klop.com_icon.png">
	<link rel="stylesheet" href="styles/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Seaweed+Script&display=swap" rel="stylesheet">
</head>
<body>
	<div class="header">
		<div class="logo"><img src="icon/klop.com_icon.png">Klop.com</div>
		<nav>
			<ul>
				<li><a href="index.php">Domov</a></li>
				<?php
				if(isset($_SESSION["USER_ID"]) && (is_admin($_SESSION["USER_ID"]) == 1)){
					?>
					<li><a href="publish.php">Objavi oglas</a></li>
					<li><a href="myads.php">Moji oglasi</a></li>
					<li><a href="admin/index.php">Administracija</a></li>
					<li><a href="logout.php">Odjava</a></li>
					<?php
				}
				else if(isset($_SESSION["USER_ID"])){
					?>
					<li><a href="publish.php">Objavi oglas</a></li>
					<li><a href="myads.php">Moji oglasi</a></li>
					<li><a href="admin/index.php?controller=users&action=edit">Moj Račun</a></li>
					<li><a href="logout.php">Odjava</a></li>
					<?php
				}
				else{
					?>
					<li><a href="login.php">Prijava</a></li>
					<li><a href="register.php">Registracija</a></li>
					<?php
				}
				?>
			</ul>
		</nav>
	</div>

	<script>
		$(".logo").click(() => {
			window.location.href = "/index.php"
		})
	</script>