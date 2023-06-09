<?php 
include_once('header.php');

//Funkcija izbere oglas s podanim ID-jem. Doda tudi uporabnika, ki je objavil oglas.
function get_ad($id){
	global $conn;
	$id = mysqli_real_escape_string($conn, $id);
	$query = "SELECT ads.*, users.username, users.email, users.name, users.surname, users.post, users.phone FROM ads LEFT JOIN users ON users.id = ads.user_id WHERE ads.id = $id;";
	$res = $conn->query($query);
	if($obj = $res->fetch_object()){
		return $obj;
	}
	return null;
}

function get_imgs($id){
	global $conn;
	$id = mysqli_real_escape_string($conn, $id);
	$query = "SELECT images.name FROM image_in_ad
	JOIN ads ON image_in_ad.id_ad = ads.id
	JOIN images ON image_in_ad.id_image = images.id
	WHERE ads.id = $id;";
	$res = $conn->query($query);
	$imgs = array();
	while($img = $res->fetch_object()){
		array_push($imgs, $img);
	}

	return $imgs;
}

function get_ad_categories($id){
	$categories = array();
	global $conn;
	$query = "SELECT categories.value FROM category_in_ad
			  JOIN ads ON category_in_ad.id_ad = ads.id
			  JOIN categories ON category_in_ad.id_category = categories.id
			  WHERE ads.id = $id;";
	$res = $conn->query($query);
	if($res->num_rows > 0){
		while($row = $res->fetch_array()){
			array_push($categories, $row['value']);
		}
	}
	
	return $categories;
}

// ogledi
function count_view($ad_id, $user_id){
	global $conn;
	if(is_null($user_id)){
		$query = "INSERT INTO views (id_user, id_ad) VALUES (NULL, $ad_id);";
	}
	else{
		$query = "INSERT INTO views (id_user, id_ad) VALUES ($user_id, $ad_id);";
	}
	$conn->query($query);
}

function has_viewed($ad_id, $user_id){
	global $conn;
	$query = "SELECT id FROM views WHERE id_user = $user_id AND id_ad = $ad_id;";
	$res = $conn->query($query);
	if($res->num_rows > 0){
		return true;
	}
	else{
		return false;
	}
}

if(!isset($_GET["id"])){
	echo "Manjkajoči parametri.";
	die();
}

$id = $_GET["id"];
$ad = get_ad($id);
$imgs = get_imgs($id);
$cats = get_ad_categories($id);

if($ad == null){
	echo "Oglas ne obstaja.";
	die();
}

?>
<head>
	<title><?php echo $ad->title ?> - Klop.com</title>
	<link rel="stylesheet" href="styles/ad.css">
</head>
<?php

// preveri če je prvi ogled
if(isset($_SESSION["USER_ID"])){
	$user_id = $_SESSION["USER_ID"];
	if(!has_viewed($id, $user_id)){
		count_view($id, $user_id);
	}
}
else if(!(isset($_COOKIE[$id]))){
	count_view($id, NULL);
	// cookie velja 24ur
	setcookie($id, 'viewed', time() + 86400, '/');
}


?>
	<div class="ad">
		<div class="title"><?php echo $ad->title;?></div>
		<div class="desc"><?php echo $ad->description;?></div>
		<div class="images">
		<?php
			foreach($imgs as $img){
				?>
					<img src="<?php echo $img->name;?>" width="400"/>
				<?php
			}
		?>
		</div>
		<div class="info">
			<div class="cats"><?php
				foreach($cats as $cat){?>
					<span><img src="icon/category.png"><?php echo $cat ?></span>
				<?php
				}
			?></div>
			<div class="time">Objavljeno: <?php
			$timestamp = strtotime($ad->timestamp);
			echo date("j. n. o G:i", $timestamp);
			?></div>
		</div>
		<div class="user-icon" title="Podatki o uporabniku"><img src="icon/user.png"></div>
		<div class="user-info">
			<ul>
				<li><span>Uporabniško ime:</span> <?php echo $ad->username ?></li>
				<li><span>E-Pošta:</span> <?php echo $ad->email ?></li>
				<li><span>Ime in priimek:</span> <?php echo "$ad->name $ad->surname"?></li>
				<li><span>Pošta:</span> <?php echo $ad->post ?></li>
				<li><span>Telefon:</span> <?php echo $ad->phone ?></li>
			</ul>
		</div>
		<?php
			if(isset($_GET["myads"])){
				?>
					<a href="myads.php"><button>Nazaj</button></a>
				<?php
			}
			else{
				?>
					<a href="index.php"><button>Nazaj</button></a>
				<?php
			}
		?>
	</div>
	<?php
		if(isset($_SESSION["USER_ID"])){
			?>
				<div class="add_comment">
					<h4>Dodaj komentar</h4>
					<textarea id="content" cols="40" rows="5"></textarea>
					<button id="post_comment">Objavi</button>
				</div>

				<script>
					$(document).ready(() => {
						$("#post_comment").click(postComment)
					})

					function postComment(){
						var data = {
							ad: (<?php echo $_GET["id"] ?>),
							content: $("#content").val()
						}

						$("#content").val("")

						$.post("/api/index.php/comments/", data, (comment) => {
							const wrapper = document.createElement("div")
							wrapper.id = comment.id
							wrapper.classList.add("comment")
					
							const user = document.createElement("h4")
							getCountry(comment.ip, country => {
								user.innerHTML = comment.user.username + " - " + country
							})
							wrapper.append(user)

							const content = document.createElement("p")
							content.innerHTML = comment.content
							wrapper.append(content)

							const delete_btn = document.createElement("button")
							delete_btn.innerHTML = "Izbriši"
							delete_btn.classList.add("delete")
							wrapper.append(delete_btn)

							$("#comments").append(wrapper)
							$(".delete").click(deleteClick)
						})
					}
				</script>
			<?php
		}
	?>
	<div class="comment_section">
		<h2>Komentarji: </h2>
		<div class="comments" id="comments"></div>
	</div>
	<script>
		$(document).ready(async () => {
			await loadComments()
			$(".delete").click(deleteClick)
		})

		async function loadComments(){
			await $.get("/api/index.php/comments/" + <?php echo $_GET["id"] ?>, renderComments)
		}

		function renderComments(comments){
			comments.forEach(comment => {
				const wrapper = document.createElement("div")
				wrapper.id = comment.id
				wrapper.classList.add("comment")
				
				const user = document.createElement("h4")
				getCountry(comment.ip, country => {
					user.innerHTML = comment.user.username + " - " + country
				})
				wrapper.append(user)

				const content = document.createElement("p")
				content.innerHTML = comment.content
				wrapper.append(content)

				<?php if(isset($_SESSION["USER_ID"])){
					?>
					
					if((comment.user.id == (<?php echo $_SESSION["USER_ID"] ?>)) ||  ((<?php echo $_SESSION["USER_ID"] ?>) == (<?php echo $ad->user_id ?>))){
						const delete_btn = document.createElement("button")
						delete_btn.innerHTML = "Izbriši"
						delete_btn.classList.add("delete")
						wrapper.append(delete_btn)
					}
					<?php
					}
				?>

				$("#comments").append(wrapper)
			})
		}

		function deleteClick(){
			console.log("DELTE CALLED")
			var comment = $(this).closest(".comment");
			deleteComment(comment)
			comment.remove();
		}

		function deleteComment(comment){
			var id = comment.attr("id")
			$.ajax({
				url: "/api/index.php/comments/" + id,
				method: "DELETE"
			})
		}

		function getCountry(ip, callback){
			$.ajax({
				url: "http://ip-api.com/json/" + ip +"?field=country",
				success: (data) => {
					if(data.status === "success"){
						callback(data.country)
					}
					else{
						console.log("Error: ", data.message)
					}
				}
			})
		}
	</script>
	<?php

include_once('footer.php');
?>