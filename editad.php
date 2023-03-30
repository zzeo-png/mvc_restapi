<?php
include_once('header.php');
?>
<head>
	<title>Uredi oglas - Klop.com</title>
	<link rel="stylesheet" href="styles/publish.css">
</head>
<?php

function edit($id, $title, $desc, $img, $del_img, $cover, $categories){
	global $conn;
	$title = mysqli_real_escape_string($conn, $title);
	$desc = mysqli_real_escape_string($conn, $desc);
	$user_id = $_SESSION["USER_ID"];
	$ad_id = $id;

	$query = "UPDATE ads SET title = '$title', description = '$desc'
	WHERE id = $ad_id AND user_id = $user_id;";
	
	if($conn->query($query)){
		// zbriši vse kategorije
		$query = "DELETE category_in_ad FROM category_in_ad JOIN ads ON category_in_ad.id_ad = ads.id
		WHERE category_in_ad.id_ad = $ad_id AND ads.user_id = $user_id;";
		$conn->query($query);

		// ponovno jih nastavi
		foreach($categories as $category){
			$query_cat_id = "SELECT id FROM categories WHERE name = '$category';";
			$cat_id = $conn->query($query_cat_id);
			$row = $cat_id->fetch_array();
			$cat_id = $row['id'];

			$query_cat = "INSERT INTO category_in_ad (id_ad, id_category)
						  VALUES($ad_id, $cat_id);";
			$conn->query($query_cat);
		}

	}
	else{
		//Izpis MYSQL napake z: echo mysqli_error($conn); 
		return false;
	}
	
	// -- slike --
	// brisanje slik
	foreach($del_img as $del){
		$temp = $del['name'];
		//$query = "INSERT INTO images (name) VALUES ($temp);";
		$query = "DELETE FROM images WHERE `name` = '$temp';";
		$conn->query($query);
	}

	// flag za nador covera
	$is_cover_updated = false;

	// dodajanje
	foreach($_FILES["images"]["name"] as $key => $value){
		$image_name = $_FILES["images"]["name"][$key];
		$image_temp = $_FILES["images"]["tmp_name"][$key];
		$image_type = $_FILES["images"]["type"][$key];
		$image_size = $_FILES["images"]["size"][$key];

		$allowed_types = array("jpg", "jpeg", "png");
		$image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

		if(in_array($image_ext, $allowed_types)){
			$image_new_name = pathinfo($image_name, PATHINFO_FILENAME) . "_" .  + date('dmYHis');
			$img_path = "images/" . $image_new_name;

			if(!move_uploaded_file($image_temp, $img_path)){
				$error = "Napaka pri nalaganju slike.";
			}

			$query = "INSERT INTO images (`name`, `size`, `type`) VALUES ('$img_path', $image_size, '$image_type');";
			$conn->query($query);

			$last_image_id = mysqli_insert_id($conn);

			// preveri če cover photo obstaja
			$query = "SELECT images.name FROM image_in_ad JOIN images ON image_in_ad.id_image = images.id WHERE id_ad = $ad_id AND is_primary = 1;";
			$res = $conn->query($query);

			// če cover photo ne obstaja ali ni novo podan cover
			if(($res->num_rows == 0 || $res->fetch_object()->name != $cover) && $cover == $image_name){
				$query_image = "INSERT INTO image_in_ad (id_ad, id_image, is_primary)
						        VALUES($ad_id, $last_image_id, 1);";

				// popravi prejsnji cover na 0
				$query_fix = "UPDATE image_in_ad SET is_primary = 0 WHERE id_ad = $ad_id AND is_primary = 1;";
				$conn->query($query_fix);

				$is_cover_updated = true;
			}
			else{
				$query_image = "INSERT INTO image_in_ad (id_ad, id_image)
						        VALUES($ad_id, $last_image_id);";
			}
			
			$conn->query($query_image);

		}
		else{
			$error = "Neveljavna datoteka.";
		}
	}

	// če cover še vedno ni posodobljen
	if(!$is_cover_updated){
		// dobi trenutni cover
		$query = "SELECT images.name FROM image_in_ad JOIN images ON image_in_ad.id_image = images.id WHERE id_ad = $ad_id AND is_primary = 1;";
		$res = $conn->query($query);
		// novi cover ni trenutni cover
		if($cover != $res->fetch_object()->name){
			// dobi vse slike ki niso cover
			$query = "SELECT images.* FROM image_in_ad JOIN images ON image_in_ad.id_image = images.id WHERE id_ad = $ad_id AND is_primary = 0;";
			$res = $conn->query($query);
			
			// najdi novi cover
			while($ad_img = $res->fetch_object()){
				if($ad_img->name == $cover){
					// popravi prejšnjega
					$query_fix = "UPDATE image_in_ad SET is_primary = 0 WHERE id_ad = $ad_id AND is_primary = 1;";
					$conn->query($query_fix);

					// nastavi novega
					$query = "UPDATE image_in_ad SET is_primary = 1 WHERE id_ad = $ad_id AND id_image = $ad_img->id;";
					$conn->query($query);
				}
			}
		}
	}

	return true;
}

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

function get_cover($id){
	global $conn;
	$id = mysqli_real_escape_string($conn, $id);
	$query = "SELECT images.name FROM image_in_ad
	JOIN ads ON image_in_ad.id_ad = ads.id
	JOIN images ON image_in_ad.id_image = images.id
	WHERE ads.id = $id AND is_primary = 1;";
	$res = $conn->query($query);

	return $res->fetch_object();
}

function get_categories(){
	global $conn;
	$query = "SELECT name, value FROM categories;";
	$res = $conn->query($query);
	$categories = array();
	while($category = $res->fetch_object()){
		array_push($categories, $category);
	}

	return $categories;
}

function get_ad_categories($ad_id){
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

if(isset($_POST["submit"])){
	if(isset($_POST["categories"])){
		if(edit($_POST["id"], $_POST["title"], $_POST["description"], $_FILES["images"], $_POST["delete_imgs"], $_POST["cover"], $_POST["categories"])){
			header("Location: myads.php");
			die();
		}
		else{
			$error = "Prišlo je do napake pri posodobitvi oglasa.";
		}
	}
	else{
		$error = "Izberi vsaj eno kategorijo.";
	}
}
else if(!isset($_GET["id"])){
	echo "Manjkajoči parametri.";
	die();
}

if(isset($_SESSION["USER_ID"])){
	
	$id = $_GET["id"];
	$ad = get_ad($id);
	$imgs = get_imgs($id);
	$cover = get_cover($id);
	$error = "";
	
	if($ad == null){
		echo "Napaka pri nalaganju oglasa.";
		die();
	}

	if($ad->user_id != $_SESSION["USER_ID"]){
		echo "Napaka pri nalaganju oglasa.";
		die();
	}

    ?>
    <div class="ad">
		<div class="title">Uredi oglas</div>
		<script>
			var new_images = []
			var cover
			var images = []
			var id = <?php echo $id ?>
		</script>
	<form action="editad.php" method="POST" enctype="multipart/form-data" id="myform">
		<div class="divider">
			<div class="section1">
				<div class="f_main">
					<div class="labels">
						<label>Naslov</label>
						<label>Vsebina</label>
					</div>
					<div class="inputs">
						<input type="text" name="title" required value="<?php echo $ad->title ?>"/>
						<textarea name="description" rows="10" cols="50" required><?php echo $ad->description ?></textarea>
					</div>
				</div>
				<label>Slika</label>
				<button id="upload_img" type="button">Izberi slike</button>
				<input type="file" name="images[]" multiple accept="image/*"/>
			</div>
			<div class="categories section2">
				<div class="heading">Kategorije:</div>
				<?php
					$categories = get_categories();
					$ad_categories = get_ad_categories($ad->id);
					foreach($categories as $category){
						?>
						<div class="item">
							<input class="styled" type="checkbox" name="categories[]" value="<?php echo $category->name ?>" id="<?php echo $category->name ?>"
							<?php
								foreach($ad_categories as $ad_cat){
									if($ad_cat == $category->value){
										echo "checked";
									}
								}
							?>
							>
							<label for="<?php echo $category->name ?>"> <?php echo $category->value ?> </label>
						</div>
						<?php
					}
				?>
			</div>
		</div>
		<div class="images">
			<?php
				$json = json_encode($imgs);
					?>
						<script>
						images = (<?php echo $json ?>)
						cover = (<?php echo json_encode($cover) ?>)
						</script>
					<?php
			?>
		</div>
		<a href="myads.php"><button type="button">Nazaj</button></a>
		<input type="submit" name="submit" value="Posodobi" />		
	</form>
	</div>	
	<label id="error"><?php echo $error; ?></label>
	
	<script>
		//upload imgs
		$("#upload_img").click(() => {
			$("input[type='file']").trigger("click")
		})

		const fileInput = document.querySelector('input[type="file"]')
		const preview = document.querySelector(".images")

		var imgs_to_delete = []

		fileInput.addEventListener('change', () => {

			for(const file of fileInput.files){
				const reader = new FileReader()

				reader.addEventListener('load', () => {
					images.push(file)
					new_images.push(file)
					add_image(reader.result)
					img_tools()
				})

				if(file){
					reader.readAsDataURL(file)
				}
			}
		})

		// posodobi
		const form = document.getElementById("myform")

		form.addEventListener("submit", (e) => {
			e.preventDefault()

			const formData = new FormData()

			const formElements = form.elements

			var catFlag = false;
			var covFlag = true;

			for(let i = 0; i < formElements.length; i++){
				var element = formElements[i]

				// dobi vse vrednosti razen image
				if(element.name !== "images[]"){
					if(element.type !== "checkbox"){
						formData.append(element.name, element.value)
					}
					else if(element.checked){
						formData.append(element.name, element.value)
						catFlag = true;
					}
				}
			}

			formData.append("id", id)

			if(images.length > 0){
				// dobi slike
				for(let i = 0; i < new_images.length; i++){
					formData.append("images[]", new_images[i])
				}

				// slike za brisanje
				for(let i = 0; i < imgs_to_delete.length; i++){
					formData.append("delete_imgs[" + i + "][name]", imgs_to_delete[i].name)
				}

				// cover image
				if(cover){
					formData.append("cover", cover.name)
				}
				else{
					covFlag = false;
				}
			}

			if(catFlag && covFlag && images.length > 0){
				fetch(form.action, {
					method: form.method,
					body: formData,
					redirect: 'follow'
				})
				.then(response => {
					window.location.href = response.url
				})
			}
			else if(images.length <= 0){
				$("#error").css("display", "block")
				$("#error").text("Oglas mora vsebovati vsaj eno sliko")
			}
			else if(!catFlag){
				$("#error").css("display", "block")
				$("#error").text("Oglas mora imeti vsaj eno kategorijo")
			}
			else if(!covFlag){
				$("#error").css("display", "block")
				$("#error").text("Oglas mora imeti naslovno sliko")
			}
		})

		function action(i){
			if('lastModified' in images[i]){
				let og_imgs = 0
				images.forEach(img => {
					if(!('lastModified' in img)){
						og_imgs++
					}
				})
				console.log("i:" + i)
				console.log("og_imgs:" + og_imgs)
				console.log("i - og_imgs:" + (i-og_imgs))
				new_images.splice(i - og_imgs, 1)
			}
			else{
				imgs_to_delete.push(images[i])
			}
			if(images[i].name == cover.name){
				cover = undefined
			}
			images.splice(i, 1)
			refresh_images()
		}

		function action_c(i){
			$('.image').removeClass('cover_img')
			$('.image').eq(i).addClass('cover_img')
			cover = images[i]
		}

		// brisanje slik
		function img_tools(){
			var del_buttons = document.getElementsByClassName("btn_delete")
			var cover_buttons = document.getElementsByClassName("btn_cover")

			for(let i = 0; i < del_buttons.length; i++){
				const delAction = action.bind(null, i)
				const coverAction = action_c.bind(null, i)
				$(del_buttons[i]).unbind()
				$(cover_buttons[i]).unbind()
				$(del_buttons[i]).bind("click", delAction)
				$(cover_buttons[i]).bind("click", coverAction)
			}
		}

		function add_image(img){
			var temp = img
			if(typeof img === 'object'){
				if('lastModified' in img){
					const reader = new FileReader()

						reader.addEventListener('load', () => {
							temp = reader.result
							add_image_2(temp)
						})

					if(img){
						reader.readAsDataURL(img)
					}

				}
				else{
					var temp = img.name
					add_image_2(temp)
				}
			}
			else{
				add_image_2(temp)
			}
		}

		function add_image_2(img){
			const wrapper = document.createElement("div")
			wrapper.classList.add("image")
			if(cover){
				if(cover.name == img){
					wrapper.classList.add("cover_img")
				}
			}

			const image = document.createElement("img")
			image.src = img

			// close icon
			const btn = document.createElement("div")
			btn.classList.add("btn_delete")
			btn.title = "Odstrani sliko"
			const c_icon = document.createElement("img")
			c_icon.classList.add("c_icon")
			c_icon.src = "icon/close.png"

			btn.appendChild(c_icon)


			// cover icon
			const btn_cover = document.createElement("div")
			btn_cover.classList.add("btn_cover")
			btn_cover.title = "Nastavi kot ikono"
			const p_icon = document.createElement("img")
			p_icon.classList.add("p_icon")
			p_icon.src = "icon/plus.png"

			btn_cover.appendChild(p_icon)

			const group = document.createElement("div")
			group.classList.add("group")

			wrapper.appendChild(image)
			group.appendChild(btn)
			group.appendChild(btn_cover)
			wrapper.appendChild(group)

			const parent = document.getElementsByClassName("images")
			parent[0].appendChild(wrapper)
		}

		function refresh_images(){
			var image_el = document.getElementsByClassName("image")
			while(image_el.length > 0){
				image_el[0].remove()
			}

			images.forEach(img => {
				add_image(img)
			})

			setTimeout(() => {
				img_tools()	
			}, 500);
		}

		refresh_images()
	</script>
	<?php
    
}
else{
    echo "Napaka pri nalaganju oglasa.";
}

include_once('footer.php');
?>