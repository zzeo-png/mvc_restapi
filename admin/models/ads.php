<?php
/*
    Model za oglas. Vsebuje lastnosti, ki definirajo strukturo oglasa in sovpadajo s stolpci v bazi.
    Nekatere metode so statične, ker niso vezane na posamezen oglas: poišči vse oglase, vstavi nov oglas, ... 
    Druge so statične, ker so vezane na posamezen oglas: posodobi oglas, izbriši oglas, ... 

    V modelu moramo definirati tudi relacije oz. povezane entitete/modele. V primeru oglasa je to $user, ki 
    povezuje oglas z uporabnikom, ki je oglas objavil. Relacija nam poskrbi za nalaganje podatkov o uporabniku, 
    da nimamo samo user_id, ampak tudi username, ...
*/

require_once 'users.php'; // Vključimo model za uporabnike

class Ad
{
    public $id;
    public $title;
    public $description;
    public $image;
    public $user;

    // Konstruktor
    public function __construct($id, $title, $description, $image, $user_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->image = base64_encode($image); //byte array (blob) zakodiramo v base64 string
        $this->user = User::find($user_id); //naložimo podatke o uporabniku
    }

    // Metoda, ki iz baze vrne vse oglase
    public static function all()
    {
        $db = Db::getInstance(); // pridobimo instanco baze
        $query = "SELECT * FROM ads;"; // pripravimo query
        $res = $db->query($query); // poženemo query
        $ads = array();
        while ($ad = $res->fetch_object()) {
            // Za vsak rezultat iz baze ustvarimo objekt (kličemo konstuktor) in ga dodamo v array $ads
            array_push($ads, new Ad($ad->id, $ad->title, $ad->description, $ad->image, $ad->user_id));
        }
        return $ads;
    }

    // Metoda, ki vrne en oglas z specifičnim id-jem iz baze
    public static function find($id)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT * FROM ads WHERE ads.id = '$id';";
        $res = $db->query($query);
        if ($ad = $res->fetch_object()) {
            return new Ad($ad->id, $ad->title, $ad->description, $ad->image, $ad->user_id);
        }
        return null;
    }


    // Metoda, ki doda nov oglas v bazo
    public static function insert($title, $desc, $img)
    {
        $db = Db::getInstance();
        $title = mysqli_real_escape_string($db, $title);
        $desc = mysqli_real_escape_string($db, $desc);
        $user_id = $_SESSION["USER_ID"]; // user_id vzamemo iz seje (prijavljen uporabnik)

        //Preberemo vsebino (byte array) slike in pripravimo byte array za pisanje v bazo (blob)
        $img_file = "";
        if ($img && $img["tmp_name"] != "") {
            $img_file = file_get_contents($img["tmp_name"]);
            $img_file = mysqli_real_escape_string($db, $img_file);
        }

        $query = "INSERT INTO ads (title, description, user_id, image) VALUES('$title', '$desc', '$user_id', '$img_file');";
        if ($db->query($query)) {
            $id = mysqli_insert_id($db); // preberemo id, ki ga je dobil vstavljen oglas
            return Ad::find($id); // preberemo nov oglas iz baze in ga vrnemo controllerju
        } else {
            return null; // v primeru napake vrnemo null
        }
    }

    // Metoda, ki posodobi obstoječ oglas v bazi
    public function update($title, $desc, $img)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $this->id);
        $title = mysqli_real_escape_string($db, $title);
        $desc = mysqli_real_escape_string($db, $desc);

        // Preverimo, če je uporabnik zamenjal sliko in sestavimo ustrezen query
        $query = "";
        if ($img && $img["tmp_name"] != "") {
            $img_file = file_get_contents($img["tmp_name"]);
            $img_file = mysqli_real_escape_string($db, $img_file);
            $query = "UPDATE ads SET title = '$title', description = '$desc', image = '$img_file' WHERE id = '$id'";
        } else {
            $query = "UPDATE ads SET title = '$title', description = '$desc' WHERE id = '$id'";
        }
        if ($db->query($query)) {
            return Ad::find($id); //iz baze pridobimo posodobljen oglas in ga vrnemo controllerju
        } else {
            return null;
        }
    }

    // Metoda, ki izbriše oglas iz baze
    public function delete()
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $this->id);
        $query = "DELETE FROM ads WHERE id = '$id'";
        if ($db->query($query)) {
            return true;
        } else {
            return false;
        }
    }
}
