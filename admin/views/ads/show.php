<h4><?php echo $ad->title; ?></h4>
<p><?php echo $ad->description; ?></p>
<img src="data:image/jpg;base64, <?php echo $ad->image; ?>" width="400" />
<p>Objavil: <?php echo $ad->user->username; ?></p>
<a href="index.php"><button>Nazaj</button></a>
