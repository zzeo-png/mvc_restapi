<?php
/*
  Usmerjevalnik (router) skrbi za obravnavo HTTP zahtev. Glede na zahtevo, 
  pokliče ustrezno akcijo v zahtevanem controllerju.
*/

// Funkcija, ki kliče kontrolerje in hkrati vključuje njihovo kodo in kodo modela
function call($controller, $action)
{
  // Vključimo kodo controllerja in modela (pazimo na poimenovanje datotek)
  require_once('controllers/' . $controller . '_controller.php');
  require_once('models/' . $controller . '.php');

  // Ustvarimo kontroler
  $o = $controller . "_controller"; //generiramo ime razreda controllerja
  $controller = new $o; //ustvarimo instanco razreda (ime razreda je string spremenljivka)

  //pokličemo akcijo na kontrolerju (ime funkcije je string spremenljivka)
  $controller->{$action}();
}

// Seznam vseh dovoljenih controllerjev in njihovih akcij. Z njegovo pomočjo bi 
// lahko definirali tudi pravice (ustrezno zmanjšali nabor akcij pod določenimi pogoji)
$controllers = array(
  'pages' => ['error', 'api'],
  'ads' => ['index', 'show', 'create', 'store', 'edit', 'update', 'delete'],
  'users' => ['index', 'create', 'store', 'edit', 'update', 'user_update', 'delete']
);

// Preverimo, če zahteva kliče controller in akcijo iz zgornjega seznama
if (
  array_key_exists($controller, $controllers)
  && in_array($action, $controllers[$controller])
) {
  // Pokličemo akcijo
  call($controller, $action);
}
else {
  // Izpišemo stran z napako
  call('pages', 'error');
}