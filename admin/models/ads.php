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
    public $user;
    public $time;
    public $categories;
    public $cover;
    public $images;
    public $views;

    // Konstruktor
    public function __construct($id, $title, $description, $user_id, $time, $categories, $images)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->user = User::find($user_id); //naložimo podatke o uporabniku
        $this->time = $time;
        $this->categories = $categories;
        $this->images = $images;
    }

    // Metoda, ki iz baze vrne vse oglase
    public static function all()
    {
        $db = Db::getInstance(); // pridobimo instanco baze
        $query = "SELECT * FROM ads;"; // pripravimo query
        $res = $db->query($query); // poženemo query
        $ads = array();
        while ($ad = $res->fetch_object()) {
            // dobi kategorije
            $cats = Ad::get_categories($ad->id);
            // dobi slike
            $imgs = Ad::get_images($ad->id);
            // Za vsak rezultat iz baze ustvarimo objekt (kličemo konstuktor) in ga dodamo v array $ads
            array_push($ads, new Ad($ad->id, $ad->title, $ad->description, $ad->user_id, $ad->timestamp, $cats, $imgs));
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
            // dobi kategorije
            $cats = Ad::get_categories($ad->id);
            // dobi slike
            $imgs = Ad::get_images($ad->id);
            // vrni oglas
            return new Ad($ad->id, $ad->title, $ad->description, $ad->user_id, $ad->timestamp, $cats, $imgs);
        }
        return null;
    }

    // Metoda, ki vrne vse kategorije oglasa
    public static function get_categories($id){
	    $categories = array();
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT categories.value FROM category_in_ad
                JOIN ads ON category_in_ad.id_ad = ads.id
                JOIN categories ON category_in_ad.id_category = categories.id
                WHERE ads.id = $id;";
        $res = $db->query($query);
        if($res->num_rows > 0){
            while($row = $res->fetch_array()){
                array_push($categories, $row['value']);
            }
        }
        
        return $categories;
    }

    // Metoda, ki vrne naslovno sliko oglasa
    public static function get_cover($id){
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT images.name FROM image_in_ad
                JOIN ads ON image_in_ad.id_ad = ads.id
                JOIN images ON image_in_ad.id_image = images.id
                WHERE ads.id = $id AND is_primary = 1;";
        $res = $db->query($query);
            
        return $res->fetch_object();
    }
    
    // Metoda, ki vrne slike oglasa
    public static function get_images($id){
        $imgs = array();
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT images.name FROM image_in_ad
                JOIN ads ON image_in_ad.id_ad = ads.id
                JOIN images ON image_in_ad.id_image = images.id
                WHERE ads.id = $id;";
        $res = $db->query($query);
        while($img = $res->fetch_object()){
            array_push($imgs, $img);
        }

        return $imgs;
    }

    // Metoda, ki doda nov oglas v bazo
    public static function insert($title, $desc, $img, $cover, $categories)
    {
        $db = Db::getInstance();
        $title = mysqli_real_escape_string($db, $title);
        $desc = mysqli_real_escape_string($db, $desc);
        $user_id = $_SESSION["USER_ID"]; // user_id vzamemo iz seje (prijavljen uporabnik)

        $query = "INSERT INTO ads (title, description, user_id, timestamp)
			  VALUES('$title', '$desc', '$user_id', now());";
	
        // kategorije
        if($db->query($query)){
            // dobi vstavljen ID
            $last_ad_id = mysqli_insert_id($db);
            
            foreach($categories as $category){
                $query_cat_id = "SELECT id FROM categories WHERE name = '$category';";
                $cat_id = $db->query($query_cat_id);
                $row = $cat_id->fetch_array();
                $cat_id = $row['id'];

                $query_cat = "INSERT INTO category_in_ad (id_ad, id_category)
                            VALUES($last_ad_id, $cat_id);";
                $db->query($query_cat);
            }

        }
        else{
            $error = mysqli_error($db);
            return false;
        }

        // slike
        foreach($_FILES["images"]["name"] as $key => $value){
            $image_name = $_FILES["images"]["name"][$key];
            $image_temp = $_FILES["images"]["tmp_name"][$key];
            $image_type = $_FILES["images"]["type"][$key];
            $Image_size = $_FILES["images"]["size"][$key];

            $allowed_types = array("jpg", "jpeg", "png");
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            if(in_array($image_ext, $allowed_types)){
                $image_new_name = pathinfo($image_name, PATHINFO_FILENAME) . "_" .  + date('dmYHis');
                $img_path = "../images/" . $image_new_name;

                if(!move_uploaded_file($image_temp, $img_path)){
                    $error = "Napaka pri nalaganju slike.";
                }

                $query = "INSERT INTO images (name) VALUES ('$img_path');";
                $db->query($query);

                $last_image_id = mysqli_insert_id($db);

                // prva slika je cover image
                if($image_name == $cover){
                    $query_image = "INSERT INTO image_in_ad (id_ad, id_image, is_primary)
                                    VALUES($last_ad_id, $last_image_id, 1);";
                }
                else{
                    $query_image = "INSERT INTO image_in_ad (id_ad, id_image)
                                    VALUES($last_ad_id, $last_image_id);";
                }
                
                $db->query($query_image);

            }
            else{
                return null;
            }
        }

	    $id = mysqli_insert_id($db); // preberemo id, ki ga je dobil vstavljen oglas
        return Ad::find($id);
    }

    // Metoda, ki posodobi obstoječ oglas v bazi
    public function update($title, $desc, $img, $del_img, $cover, $categories)
    {
        $db = Db::getInstance();
        $title = mysqli_real_escape_string($db, $title);
        $desc = mysqli_real_escape_string($db, $desc);
        $user_id = mysqli_real_escape_string($db, $this->user->id);
        $ad_id = mysqli_real_escape_string($db, $this->id);

        $query = "UPDATE ads SET title = '$title', description = '$desc'
        WHERE id = $ad_id AND user_id = $user_id;";
        
        if($db->query($query)){
            // zbriši vse kategorije
            $query = "DELETE category_in_ad FROM category_in_ad JOIN ads ON category_in_ad.id_ad = ads.id
            WHERE category_in_ad.id_ad = $ad_id AND ads.user_id = $user_id;";
            $db->query($query);

            // ponovno jih nastavi
            foreach($categories as $category){
                $query_cat_id = "SELECT id FROM categories WHERE name = '$category';";
                $cat_id = $db->query($query_cat_id);
                $row = $cat_id->fetch_array();
                $cat_id = $row['id'];

                $query_cat = "INSERT INTO category_in_ad (id_ad, id_category)
                            VALUES($ad_id, $cat_id);";
                $db->query($query_cat);
            }

        }
        else{
            return null;
        }
        
        // -- slike --
        // brisanje slik
        if($del_img != null){
            foreach($del_img as $del){
                $temp = $del['name'];
                //$query = "INSERT INTO images (name) VALUES ($temp);";
                $query = "DELETE FROM images WHERE `name` = '$temp';";
                $db->query($query);
            }
        }
        
        // flag za nador covera
        $is_cover_updated = false;

        // dodajanje
        if($img != null){
            foreach($_FILES["images"]["name"] as $key => $value){
                $image_name = $_FILES["images"]["name"][$key];
                $image_temp = $_FILES["images"]["tmp_name"][$key];
                $image_type = $_FILES["images"]["type"][$key];
                $image_size = $_FILES["images"]["size"][$key];
    
                $allowed_types = array("jpg", "jpeg", "png");
                $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    
                if(in_array($image_ext, $allowed_types)){
                    $image_new_name = pathinfo($image_name, PATHINFO_FILENAME) . "_" .  + date('dmYHis');
                    $img_path = "../images/" . $image_new_name;
    
                    if(!move_uploaded_file($image_temp, $img_path)){
                        $error = "Napaka pri nalaganju slike.";
                    }
    
                    $query = "INSERT INTO images (`name`, `size`, `type`) VALUES ('$img_path', $image_size, '$image_type');";
                    $db->query($query);
    
                    $last_image_id = mysqli_insert_id($db);
    
                    // preveri če cover photo obstaja
                    $query = "SELECT images.name FROM image_in_ad JOIN images ON image_in_ad.id_image = images.id WHERE id_ad = $ad_id AND is_primary = 1;";
                    $res = $db->query($query);
    
                    // če cover photo ne obstaja ali ni novo podan cover
                    if(($res->num_rows == 0 || $res->fetch_object()->name != $cover) && $cover == $image_name){
                        $query_image = "INSERT INTO image_in_ad (id_ad, id_image, is_primary)
                                        VALUES($ad_id, $last_image_id, 1);";
    
                        // popravi prejsnji cover na 0
                        $query_fix = "UPDATE image_in_ad SET is_primary = 0 WHERE id_ad = $ad_id AND is_primary = 1;";
                        $db->query($query_fix);
    
                        $is_cover_updated = true;
                    }
                    else{
                        $query_image = "INSERT INTO image_in_ad (id_ad, id_image)
                                        VALUES($ad_id, $last_image_id);";
                    }
                    
                    $db->query($query_image);
    
                }
                else{
                    return Ad::find($id); //iz baze pridobimo posodobljen oglas in ga vrnemo controllerju
                }
            }
        }

        // preveri če cover photo obstaja
        $query = "SELECT images.name FROM image_in_ad JOIN images ON image_in_ad.id_image = images.id WHERE id_ad = $ad_id AND is_primary = 1;";
        $res = $db->query($query);

        // če cover še vedno ni posodobljen
        if(!$is_cover_updated && $res->fetch_object()->name != $cover){
            // dobi trenutni cover
            $query = "SELECT images.name FROM image_in_ad JOIN images ON image_in_ad.id_image = images.id WHERE id_ad = $ad_id AND is_primary = 1;";
            $res = $db->query($query);
            // novi cover ni trenutni cover
            if($cover != $res->fetch_object()->name){
                // dobi vse slike ki niso cover
                $query = "SELECT images.* FROM image_in_ad JOIN images ON image_in_ad.id_image = images.id WHERE id_ad = $ad_id AND is_primary = 0;";
                $res = $db->query($query);
                
                // najdi novi cover
                while($ad_img = $res->fetch_object()){
                    if($ad_img->name == $cover){
                        // popravi prejšnjega
                        $query_fix = "UPDATE image_in_ad SET is_primary = 0 WHERE id_ad = $ad_id AND is_primary = 1;";
                        $db->query($query_fix);

                        // nastavi novega
                        $query = "UPDATE image_in_ad SET is_primary = 1 WHERE id_ad = $ad_id AND id_image = $ad_img->id;";
                        $db->query($query);
                    }
                }
            }
        }

        return true;
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
