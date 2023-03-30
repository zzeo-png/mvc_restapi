<?php
/*
Vstopna točka za našo spletno storitev. Podobno kot pri MVC, bodo tudi vse zahteve na API šle skozi index.php,
ki bo poskrbel za njihovo obravnavo.
Index.php ima tako vlogo routerja, ki na podlagi HTTP zahteve sproži ustrezne akcije.
Za razliko od MVC, bo poleg URL-ja pomembna tudi HTTP metoda v zahtevi, saj REST predpisuje akcije, ki jih prožijo določene metode.

ENDPOINTI:
    api/ads/:id/
        PUT -> posodobi
        GET -> vrni oglas
        DELETE -> zbriši oglas

    api/ads
        POST -> dodaj nov oglas
	    GET-> vrni vse oglase

S pomočjo .htaccess preslikamo URL-je iz /api.php/foo/bar => /api/foo/bar (več v datoteki .htaccess)
*/

require_once "../admin/connection.php"; //uporabimo povezavo na bazo iz MVC
require_once "../admin/models/ads.php"; //uporabimo model Ad iz MVC
require_once "controllers/ads_controller.php"; //vključimo API controller

session_start();

$ads_controller = new ads_controller;

//nastavimo glave odgovora tako, da brskalniku sporočimo, da mu vračamo json
header('Content-Type: application/json');
//omgočimo zahtevo iz različnih domen
header("Access-Control-Allow-Origin: *");
// Kot odgovor iz API-ja izpišemo JSON string s pomočjo funkcije json_encode

// preberemo HTTP metodo iz zahteve
$method = $_SERVER['REQUEST_METHOD'];

// Razberemo parametre iz URL - razbijemo URL po '/'
// tako dobimo iz zahteve api/first/second/third => $request = array("first", "second", "third")
if(isset($_SERVER['PATH_INFO']))
	$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
else
	$request="";

// Najprej potrebujemo 'router', ki bo razpoznal zahtevo in sprožil ustrezne akcije
// Preverimo, če je v url-ju prva pot 'ads'
if(!isset($request[0]) || $request[0] != "ads"){
    echo json_encode((object)["status"=>"404", "message"=>"Not found"]);
    die();
}
// Odvisno od metode pokličemo ustrezen controller action
switch($method){
    case "GET":
        // Če je v zahtevi nastavljen :id, kličemo akcijo show (en oglas), sicer pa index (vsi oglasi)
        if(isset($request[1])){
            $ads_controller->show($request[1]);
        } else {
            $ads_controller->index();
        }
        break;
    case "POST": 
        $ads_controller->store();
        break;
    case "PUT": 
        if(!isset($request[1])){
            // Če ni podan :id v zahtevi, izpišemo napako
            echo json_encode((object)["status"=>"500", "message"=>"Invalid parameters"]);
            die();
        }
        $ads_controller->update($request[1]);
        break;
    case "DELETE":
        if(!isset($request[1])){
            // Če ni podan :id v zahtevi, izpišemo napako
            echo json_encode((object)["status"=>"500", "message"=>"Invalid parameters"]);
            die();
        }
        $ads_controller->delete($request[1]);
        break;
    default: 
        break;
}


