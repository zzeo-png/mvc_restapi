<?php
include_once('header.php');
?>
<head>
	<title>Moji oglasi - Klop.com</title>
	<link rel="stylesheet" href="styles/myads.css">
</head>
<?php

function get_ads(){
    $id = $_SESSION["USER_ID"];
    global $conn;
    $query = "SELECT * FROM ads WHERE user_id = '$id' ORDER BY timestamp DESC;";
    $res = $conn->query($query);
    $ads = array();
    while($ad = $res->fetch_object()){
        array_push($ads, $ad);
    }
    return $ads;
}

function get_categories($ad_id){
	$categories = array();
	global $conn;
	$query = "SELECT categories.value FROM category_in_ad
			  JOIN ads ON category_in_ad.id_ad = ads.id
			  JOIN categories ON category_in_ad.id_category = categories.id
			  WHERE ads.id = $ad_id;";
	$res = $conn->query($query);
	if($res->num_rows > 0){
		while($row = $res->fetch_array()){
			array_push($categories, $row['value']);
		}
	}
	
	return $categories;
}

function get_cover_img($id){
	global $conn;
	$id = mysqli_real_escape_string($conn, $id);
	$query = "SELECT images.name FROM image_in_ad
	JOIN ads ON image_in_ad.id_ad = ads.id
	JOIN images ON image_in_ad.id_image = images.id
	WHERE ads.id = $id AND image_in_ad.is_primary = 1;";
	$res = $conn->query($query);
	if($cover = $res->fetch_object()){
		return $cover;
	}
	$empty = new stdClass();
	$empty->src = "https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg";
	return $empty;
}

function get_views($id){
    global $conn;
	$query = "SELECT id FROM views WHERE id_ad = $id;";
	$res = $conn->query($query);
	
    return $res->num_rows;
}

if(isset($_GET["del"])){
    delete_ad($_GET["del"]);
}

function delete_ad($id){
    global $conn;
	$id = mysqli_real_escape_string($conn, $id);
    $user_id = $_SESSION["USER_ID"];
    $query = "DELETE FROM ads WHERE ads.id = '$id' AND ads.user_id = '$user_id';";
    $conn->query($query);
}

if(isset($_SESSION["USER_ID"])){
    $ads = get_ads();
    ?>
    <div class="container">
        <div class="heading">Moji oglasi</div>
    <?php

    for($i = 0; $i < count($ads); $i++){
        $ad = $ads[$i];
        if($i != 0){
            ?>
            <hr>
            <?php
        }   
        ?>
        <div class="ad">
            <div class="title"><?php echo $ad->title?></div>
            <div class="image"><img src="<?php echo get_cover_img($ad->id)->name ?>"/></div>
            <div class="cats"><?php
                $categories = get_categories($ad->id);
                foreach($categories as $cat){
                    ?>
                        <span><img src="icon/category.png"><?php echo $cat ?></span>
                    <?php
                }
                ?></div>
            <span class="views" title="Ogledi"><img src="icon/view.png"><?php
                echo get_views($ad->id);
            ?></span>
            <a href="ad.php?id=<?php echo $ad->id;?>&myads"><button>Preberi več</button></a>
            <a href="editad.php?id=<?php echo $ad->id;?>"><button>Uredi</button></a>
            <a href="myads.php?del=<?php echo $ad->id?>"><button>Odstrani</button></a>
        </div>
        <?php
    }
    ?></div><?php
}
else{
    echo "Za upravljanje oglasov se prijavite.";
}

include_once('footer.php');
?>