<?php
/*
	Vstopna točka naše aplikacije. Vse zahteve gredo skozi index.php, ki poskrbi za ustrezno obravnavo.
	V URL-ju se bosta podala dva parametra: controller in action, ki bosta določala, katera akcija se izvede.
	S pomočjo .htaccess lahko skrajšamo URL naslove (več v .htaccess datoteki).
*/

require_once('connection.php');

session_start();
	
// Seja poteče po 30 minutah - avtomatsko odjavi neaktivnega uporabnika
if(isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] < 1800){
	session_regenerate_id(true);
}
$_SESSION['LAST_ACTIVITY'] = time();

// Razberemo namero uporabnika preko query string parametrov controller in action
if (isset($_GET['controller']) && isset($_GET['action'])) {
	$controller = $_GET['controller'];
	$action     = $_GET['action'];
} else {
  	// Če uporabnik ni podal svoje zahteve v pravilni obliki, ga preusmerimo na privzeto akcijo
	$controller = 'ads';
	$action     = 'index';
}

// Vključimo layout, torej splošni izgled strani, layout pa vključuje router (routes.php)
require_once('views/layout.php');