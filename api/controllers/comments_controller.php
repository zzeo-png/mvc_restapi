<?php

/*
    Tudi API za delovanje uporablja arhitekturo MVC. 
    V primeru API-ja nimamo view-ov, saj API vrača strukturirane podatke v JSON formatu.
    Posledično akcije kontrolerja ne vključujejo datotek z view-i, ampak izpisujejo JSON nize.

    Akcije:
        index: izpiše vse oglase
        show: izpiše en oglas
        store: vstavi oglas v bazo
        update: posodobi obstoječi oglas v bazi
        delete: izbriše oglas iz baze
*/

//kontroler za delo z oglasi
class comments_controller
{
    public function show($id)
    {
        $comments = Comment::all($id);
        echo json_encode($comments);
    }

    public function lastFive(){
        $comments = Comment::lastFive();
        echo json_encode($comments);
    }

    public function post()
    {
        $comment = Comment::add($_POST["ad"], $_POST["content"]);
        echo json_encode($comment);
    }

    public function delete($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        echo json_encode($comment);
    }
}
