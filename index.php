<?php
include_once('header.php');
?>
<head>
	<title>Oglasi - Klop.com</title>
	<link rel="stylesheet" href="styles/index.css">
</head>
<?php

// Funkcija prebere oglase iz baze in vrne polje objektov
function get_ads(){
	global $conn;
	$query = "SELECT * FROM ads ORDER BY timestamp DESC;";
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

//Preberi oglase iz baze
$ads = get_ads();
?>
<div class="container">
	<div id="comments">
		<p>Najnovejši oglasi</p>
	</div>
	<script>
		$(document).ready(async () => {
			await loadComments()
		})

		async function loadComments(){
			await $.get("/api/index.php/comments", renderComments)
		}

		function renderComments(comments){
			comments.forEach(comment => {
				const wrapper = document.createElement("div")
				wrapper.id = comment.id
				wrapper.classList.add("comment")
				
				const user = document.createElement("h4")
				user.innerHTML = comment.user.username
				wrapper.append(user)

				const content = document.createElement("p")
				content.innerHTML = comment.content
				wrapper.append(content)

				const ad = document.createElement("a")
				ad.innerHTML = comment.ad.title
				ad.href = "/ad.php?id=" + comment.ad.id
				wrapper.append(ad)

				$("#comments").append(wrapper)
			})
		}
	</script>
	<div class="heading">Najnovejši oglasi</div>
<?php
//Izpiši oglase
//Doda link z GET parametrom id na oglasi.php za gumb 'Preberi več'
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
		<a href="ad.php?id=<?php echo $ad->id;?>"><button>Preberi več</button></a>
	</div>
	<?php
}
?>
</div>
<?php

include_once('footer.php');
?>