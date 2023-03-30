<?php
/*
    Controller za oglase. Vključuje naslednje standardne akcije:
        index: izpiše vse oglase
        show: izpiše posamezen oglas
        create: izpiše obrazec za vstavljanje oglasa
        store: vstavi obrazec v bazo
        edit: izpiše vmesnik za urejanje oglasa
        update: posodobi oglas v bazi
        delete: izbriše oglas iz baze
*/

class ads_controller
{
    public function index()
    {
        //s pomočjo statične metode modela, dobimo seznam vseh oglasov
        //$ads bo na voljo v pogledu za vse oglase index.php
        $ads = Ad::all();

        //pogled bo oblikoval seznam vseh oglasov v html kodo
        require_once('views/ads/index.php');
    }

    public function show()
    {
        //preverimo, če je uporabnik podal informacijo, o oglasu, ki ga želi pogledati
        if (!isset($_GET['id'])) {
            return call('pages', 'error'); //če ne, kličemo akcijo napaka na kontrolerju stran
            //retun smo nastavil za to, da se izvajanje kode v tej akciji ne nadaljuje
        }
        //drugače najdemo oglas in ga prikažemo
        $ad = Ad::find($_GET['id']);
        require_once('views/ads/show.php');
    }

    public function create()
    {
        // Izpišemo pogled z obrazcem za vstavljanje oglasa
        require_once('views/ads/create.php');
    }

    public function store()
    {
        // Obdelamo podatke iz obrazca (views/ads/create.php), akcija pričakuje da so podatki v $_POST
        // Tukaj bi morali podatke še validirati, preden jih dodamo v bazo

        // Pokličemo metodo za ustvarjanje novega oglasa
        $ad = Ad::insert($_POST["title"], $_POST["description"], $_FILES["image"]);

        //ko je oglas dodan, imamo v $ad podatke o tem novem oglasu
        //uporabniku lahko pokažemo pogled, ki ga bo obvestil o uspešnosti oddaje oglasa
        require_once('views/ads/createSuccess.php');
    }

    public function edit()
    {
        // Ob klicu akcije se v URL poda GET parameter z ID-jem oglasa, ki ga urejamo
        // Od modela pridobimo podatke o oglasu, da lahko predizpolnimo vnosna polja v obrazcu
        if (!isset($_GET['id'])) {
            return call('pages', 'error');
        }
        $ad = Ad::find($_GET['id']);
        require_once('views/ads/edit.php');
    }

    public function update()
    {
        // Obdelamo podatke iz obrazca (views/ads/edit.php), ki pridejo v $_POST.
        // Pričakujemo, da je v $_POST podan tudi ID oglasa, ki ga posodabljamo.
        if (!isset($_POST['id'])) {
            return call('pages', 'error');
        }
        // Naložimo oglas
        $ad = Ad::find($_POST['id']);
        // Pokličemo metodo, ki posodobi obstoječi oglas v bazi
        $ad = $ad->update($_POST["title"], $_POST["description"], $_FILES["image"]);
        // Izpišemo pogled s sporočilom o uspehu
        require_once('views/ads/editSuccess.php');
    }

    public function delete()
    {
        // Obdelamo zahtevo za brisanje oglasa. Akcija pričakuje, da je v URL-ju podan ID oglasa.
        if (!isset($_GET['id'])) {
            return call('pages', 'error');
        }
        // Poiščemo oglas
        $ad = Ad::find($_GET['id']);
        // Kličemo metodo za izbris oglasa iz baze.
        $ad->delete();
        // Izpišemo sporočilo o uspehu
        require_once('views/ads/deleteSuccess.php');
    }
}
