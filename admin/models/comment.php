<?php

require_once 'users.php'; // Vključimo model za uporabnike

class Comment
{
    public $id;
    public $user;
    public $ad;
    public $content;
    public $time;

    // Konstruktor
    public function __construct($id, $user, $ad, $content, $time)
    {
        $this->id = $id;
        $this->user = User::find($user); //naložimo podatke o uporabniku
        $this->ad = Ad::find($ad);
        $this->content = $content;
        $this->time = $time;
    }

    public static function all($id)
    {
        $comments = array();
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT * FROM comments WHERE id_ad = '$id';";
        $res = $db->query($query);
        while ($comment = $res->fetch_object()) {
            array_push($comments, new Comment($comment->id, $comment->id_user, $comment->id_ad, $comment->content, $comment->timestamp));
        }

        return $comments;
    }

    public static function lastFive(){
        $comments = array();
        $db = Db::getInstance();
        $query = "SELECT * FROM comments ORDER BY timestamp DESC;";
        $res = $db->query($query);
        while ($comment = $res->fetch_object()) {
            array_push($comments, new Comment($comment->id, $comment->id_user, $comment->id_ad, $comment->content, $comment->timestamp));
        }

        return $comments;
    }

    public static function find($id){
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT * FROM comments WHERE id = '$id';";
        $res = $db->query($query);
        if($comment = $res->fetch_object()){
            return new Comment($comment->id, $comment->id_user, $comment->id_ad, $comment->content, $comment->timestamp);
        }
        return null;
    }

    public static function add($ad, $content)
    {
        $user = $_SESSION["USER_ID"];
        $db = Db::getInstance();
        $query = "INSERT INTO comments (id_user, id_ad, content) VALUES ($user, $ad, '$content');";
        if($db->query($query)){
            $id = mysqli_insert_id($db);
            return Comment::find($id);
        }
        else{
            return null;
        }
    }

    public function delete()
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $this->id);
        $query = "DELETE FROM comments WHERE id = '$id'";
        if ($db->query($query)) {
            return true;
        } else {
            return false;
        }
    }
}
