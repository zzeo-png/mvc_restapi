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

    public static function all()
    {
        $db = Db::getInstance(); // pridobimo instanco baze
        $query = "SELECT * FROM users;"; // pripravimo query
        $res = $db->query($query); // poženemo query
        $users = array();
        while ($user = $res->fetch_object()) {
            array_push($users, new User($user->id, $user->username, $user->password, $user->email, $user->name, $user->surname, $user->address, $user->post, $user->phone, $user->is_admin));
        }
        return $users;
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

    public static function create($username, $email, $name, $surname, $address, $post, $phone, $password, $admin)
    {
        $db = Db::getInstance();
        $query = "INSERT INTO users (`username`, `password`, `email`, `name`, `surname`, `address`, `post`, `phone`, `is_admin`)
                VALUES ('$username', '$password', '$email', '$name', '$surname', ";
                
                if($address !== null){
                    $query .= "'$address',";
                }
                else{
                    $query .= "NULL,";
                }

                if($post !== null){
                    $query .= "'$post',";
                }
                else{
                    $query .= "NULL,";
                }

                if($phone !== null){
                    $query .= "'$phone',";
                }
                else{
                    $query .= "NULL,";
                }

                if($admin !== null){
                    $query .= "1);";
                }
                else{
                    $query .= "0);";
                }
        if($db->query($query)){
            $id = mysqli_insert_id($db);
            return User::find($id);
        }
        else{
            return null;
        }
    }

    public function update($username, $email, $name, $surname, $address, $post, $phone, $password, $admin)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $this->id);
        $query = "UPDATE users SET `username` = '$username', `email` = '$email', `name` = '$name', `surname` = '$surname',";
                
                if($password !== null){
                    $query .= "`password` = '$password',";
                }

                if($address !== null){
                    $query .= "`address` = '$address',";
                }
                else{
                    $query .= "`address` = NULL,";
                }

                if($post !== null){
                    $query .= "`post` = '$post',";
                }
                else{
                    $query .= "`post` = NULL,";
                }

                if($phone !== null){
                    $query .= "`phone` = '$phone',";
                }
                else{
                    $query .= "`phone` = NULL,";
                }

                if($admin !== null){
                    $query .= "`is_admin` = 1 ";
                }
                else{
                    $query .= "`is_admin` = 0 ";
                }

                $query .= "WHERE `id` = $id;";
                
        if($db->query($query)){
            return User::find($id);
        }
        else{
            return null;
        }
    }

    public function user_update($username, $email, $name, $surname, $address, $post, $phone, $password)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $this->id);
        $query = "UPDATE users SET `username` = '$username', `email` = '$email', `name` = '$name', `surname` = '$surname',";
                
                if($password !== null){
                    $query .= "`password` = '$password',";
                }

                if($address !== null){
                    $query .= "`address` = '$address',";
                }
                else{
                    $query .= "`address` = NULL,";
                }

                if($post !== null){
                    $query .= "`post` = '$post',";
                }
                else{
                    $query .= "`post` = NULL,";
                }

                if($phone !== null){
                    $query .= "`phone` = '$phone' ";
                }
                else{
                    $query .= "`phone` = NULL ";
                }

                $query .= "WHERE `id` = $id;";
                
        if($db->query($query)){
            return User::find($id);
        }
        else{
            return null;
        }
    }

    public function delete()
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $this->id);
        $query = "DELETE FROM users WHERE id = '$id'";
        if ($db->query($query)) {
            return true;
        } else {
            return false;
        }
    }
}
