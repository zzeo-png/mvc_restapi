<head>
	<title><?php echo $ad->title ?> - Klop.com</title>
	<link rel="stylesheet" href="../styles/ad.css">
</head>

<div class="ad">
    <div class="title"><?php echo $ad->title;?></div>
    <div class="desc"><?php echo $ad->description;?></div>
    <div class="images">
    <?php
        foreach($ad->images as $img){
            ?>
                <img src="<?php echo "../" . $img->name;?>" width="400"/>
            <?php
        }
    ?>
    </div>
    <div class="info">
        <div class="cats"><?php
            foreach($ad->categories as $cat){?>
                <span><img src="../icon/category.png"><?php echo $cat ?></span>
            <?php
            }
        ?></div>
        <div class="time">Objavljeno: <?php
        $timestamp = strtotime($ad->time);
        echo date("j. n. o G:i", $timestamp);
        ?></div>
    </div>
    <div class="user-icon" title="Podatki o uporabniku"><img src="../icon/user.png"></div>
    <div class="user-info">
        <ul>
            <li><span>Uporabniško ime:</span> <?php echo $ad->user->username ?></li>
            <li><span>E-Pošta:</span> <?php echo $ad->user->email ?></li>
            <li><span>Ime in priimek:</span> <?php echo $ad->user->name . " " . $ad->user->surname ?></li>
            <li><span>Pošta:</span> <?php echo $ad->user->post ?></li>
            <li><span>Telefon:</span> <?php echo $ad->user->phone ?></li>
        </ul>
    </div>
        <a href="index.php"><button>Nazaj</button></a>
</div>