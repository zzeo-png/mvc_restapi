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

class users_controller
{
    public function index()
    {
        //s pomočjo statične metode modela, dobimo seznam vseh oglasov
        //$ads bo na voljo v pogledu za vse oglase index.php
        $users = User::all();

        //pogled bo oblikoval seznam vseh oglasov v html kodo
        require_once('views/users/index.php');
    }

    public function create()
    {
        // Izpišemo pogled z obrazcem za vstavljanje oglasa
        require_once('views/users/create.php');
    }

    public function store()
    {
        // Obdelamo podatke iz obrazca (views/ads/create.php), akcija pričakuje da so podatki v $_POST
        // Tukaj bi morali podatke še validirati, preden jih dodamo v bazo

        // Pokličemo metodo za ustvarjanje novega oglasa
        $ad = User::create($_POST["username"], $_POST["email"], $_POST["name"], $_POST["surname"], isset($_POST["address"]) ? $_POST["address"] : null, isset($_POST["post"]) ? $_POST["post"] : null, isset($_POST["phone"]) ? $_POST["phone"] : null, $_POST["password"], isset($_POST["admin"]) ? $_POST["admin"] : null);

        //ko je oglas dodan, imamo v $ad podatke o tem novem oglasu
        //uporabniku lahko pokažemo pogled, ki ga bo obvestil o uspešnosti oddaje oglasa
        require_once('views/users/createSuccess.php');
    }

    public function edit()
    {
        // Ob klicu akcije se v URL poda GET parameter z ID-jem oglasa, ki ga urejamo
        // Od modela pridobimo podatke o oglasu, da lahko predizpolnimo vnosna polja v obrazcu
        $id;
        if (!isset($_GET['id'])) {
            if(isset($_SESSION["USER_ID"])){
                $id = $_SESSION["USER_ID"];
                $user = User::find($id);
                require_once('views/users/user_edit.php');
            }
            else{
                return call('pages', 'error');
            }
        }
        else{
            $id = $_GET["id"];
            $user = User::find($id);
            require_once('views/users/edit.php');
        }
    }

    public function update()
    {
        // Obdelamo podatke iz obrazca (views/ads/edit.php), ki pridejo v $_POST.
        // Pričakujemo, da je v $_POST podan tudi ID oglasa, ki ga posodabljamo.
        if (!isset($_POST['id'])) {
            return call('pages', 'error');
        }
        // Naložimo oglas
        $user = User::find($_POST['id']);
        // Pokličemo metodo, ki posodobi obstoječi oglas v bazi
        $user = $user->update($_POST["username"], $_POST["email"], $_POST["name"], $_POST["surname"], isset($_POST["address"]) ? $_POST["address"] : null, isset($_POST["post"]) ? $_POST["post"] : null, isset($_POST["phone"]) ? $_POST["phone"] : null, isset($_POST["password"]) ? $_POST["password"] : null, isset($_POST["admin"]) ? $_POST["admin"] : null);
        // Izpišemo pogled s sporočilom o uspehu
        require_once('views/users/editSuccess.php');
    }

    public function user_update()
    {
        if (!isset($_POST['id'])) {
            return call('pages', 'error');
        }
        // Naložimo oglas
        $user = User::find($_POST['id']);
        // Pokličemo metodo, ki posodobi obstoječi oglas v bazi
        $user = $user->user_update($_POST["username"], $_POST["email"], $_POST["name"], $_POST["surname"], isset($_POST["address"]) ? $_POST["address"] : null, isset($_POST["post"]) ? $_POST["post"] : null, isset($_POST["phone"]) ? $_POST["phone"] : null, isset($_POST["password"]) ? $_POST["password"] : null);
        // Izpišemo pogled s sporočilom o uspehu
        require_once('views/users/user_editSuccess.php');
    }

    public function delete()
    {
        // Obdelamo zahtevo za brisanje oglasa. Akcija pričakuje, da je v URL-ju podan ID oglasa.
        if (!isset($_GET['id'])) {
            return call('pages', 'error');
        }
        // Poiščemo oglas
        $user = User::find($_GET['id']);
        // Kličemo metodo za izbris oglasa iz baze.
        $user->delete();
        // Izpišemo sporočilo o uspehu
        require_once('views/users/deleteSuccess.php');
    }
}
