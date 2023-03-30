<!DOCTYPE html>
<head>
	<title>Administracija - Klop.com</title>
    <link rel="icon" href="../icon/klop.com_icon.png">
	<link rel="stylesheet" href="../styles/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&family=Seaweed+Script&display=swap" rel="stylesheet">
</head>
<body>
	<h1>Oglasnik - administracija</h1>
	<nav>
		<ul>
			<li><a href="/index.php">Domov</a></li>
			<?php
			if(isset($_SESSION["USER_ID"])){
				?>
				<li><a href="/publish.php">Objavi oglas</a></li>
                <li><a href="/admin/index.php">Administracija</a></li>
                <li><a href="/admin/index.php?controller=pages&action=api">Uporaba API</a></li>
				<li><a href="/logout.php">Odjava</a></li>
				<?php
			} else{
				?>
				<li><a href="/login.php">Prijava</a></li>
				<li><a href="/register.php">Registracija</a></li>
				<?php
			}
			?>
		</ul>
	</nav>
	<hr/>

    <!-- tukaj se bo vključevala koda pogledov, ki jih bodo nalagali kontrolerji -->
    <!-- klic akcije iz routes bo na tem mestu zgeneriral html kodo, ki bo zalepnjena v našo predlogo -->
    <?php require_once('routes.php'); ?> 

    </body>
</html>