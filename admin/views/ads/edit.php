<h2>Objavi oglas</h2>
<form action="?controller=ads&action=update" method="POST" enctype="multipart/form-data">
    <!-- ID od oglasa, ki ga želimo urediti, pošljemo v POST s pomočjo avtomatsko izpolnjenega skritega vnosnega polja <input type='hidden'>-->
    <input type="hidden" name="id" value="<?php echo $ad->id; ?>" />
    <label>Naslov</label><input type="text" name="title" value="<?php echo $ad->title; ?>" /> <br />
    <label>Vsebina</label><textarea name="description" rows="10" cols="50"><?php echo $ad->description; ?></textarea> <br />
    <label>Slika</label> <img src="data:image/jpg;base64, <?php echo $ad->image; ?>" width="400" /> <br />
    <label>Zamenjaj sliko</label><input type="file" name="image" /> <br />
    <input type="submit" name="submit" value="Shrani" /> <br />
</form>