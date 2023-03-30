<?php

// Model za uporabnika
/*
    Model z uporabniki.
    Čeprav nimamo users_controller-ja, ta model potrebujemo pri oglasih, 
    saj oglas vsebuje podatke o uporabniku, ki je oglas objavil.
    Razred implementira metodo find, ki jo uporablja Ads model zato, da 
    user_id zamenja z instanco objekta User z vsemi podatki o uporabniku.
*/

class User
{
    public $id;
    public $username;
    public $password;
    public $email;
    public $name;
    public $surname;
    public $address;
    public $post;
    public $phone;
    public $isAdmin;

    // Konstruktor
    public function __construct($id, $username, $password, $email, $name, $surname, $address, $post, $phone, $isAdmin)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->address = $address;
        $this->post = $post;
        $this->phone = $phone;
        $this->isAdmin = $isAdmin;
    }

    // Metoda, ki vrne uporabnika z določenim ID-jem iz baze
    public static function find($id)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT * FROM users WHERE id = '$id';";
        $res = $db->query($query);
        if ($user = $res->fetch_object()) {
            return new User($user->id, $user->username, $user->password, $user->email, $user->name, $user->surname, $user->address, $user->post, $user->phone, $user->is_admin);
        }
        return null;
    }
}
