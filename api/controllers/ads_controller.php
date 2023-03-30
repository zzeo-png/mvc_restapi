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
class ads_controller
{

    public function index()
    {
        // Iz modela pidobimo vse oglase
        $ads = Ad::all();

        //izpišemo $ads v JSON formatu
        echo json_encode($ads);
    }

    public function show($id)
    {
        $ad = Ad::find($id);
        echo json_encode($ad);
    }

    public function store()
    {
        // Store se pokliče z POST, zato so podatki iz obrazca na voljo v $_POST
        $ad = Ad::insert($_POST["title"], $_POST["description"], null);
        // Vrnemo vstavljen oglas
        echo json_encode($ad);
    }

    public function update($id)
    {
        // Update se pokliče z PUT, zato nima podatkov v formData ($_POST).
        // Namesto tega smo jih poslali v body-u HTTP zahtevka v JSON formatu.
        $data = file_get_contents('php://input'); //preberemo body iz zahtevka
        $data = json_decode($data, true); //dekodiramo JSON string v PHP array

        // Poiščemo in posodobimo oglas
        $ad = Ad::find($id);
        $ad = $ad->update($data["title"], $data["description"], null);

        // Vrnemo posodobljen oglas
        echo json_encode($ad);
    }

    public function delete($id)
    {
        // Poiščemo in izbrišemo oglas
        $ad = Ad::find($id);
        $ad->delete();
        // Vrnemo podatke iz izbrisanega oglasa
        echo json_encode($ad);
    }
}
