<head>
	<link rel="stylesheet" href="../styles/register.css">
</head>

<div class="container">
	<div class="heading">Uredi Uporabnika</div>
		<div class="form">
			<form action="?controller=users&action=update" method="POST">
				<div class="f_main">
					<div class="labels">
						<label>Uporabniško ime<span>*</span></label>
						<label>E-Naslov<span>*</span></label>
						<label>Ime<span>*</span></label>
						<label>Priimek<span>*</span></label>
						<label>Naslov</label>
						<label>Pošta</label>
						<label>Telefon</label>
						<label>Geslo</label>
						<label>Admin<span></span></label>
					</div>
					<div class="inputs">
                        <input type="hidden" name="id" value="<?php echo $user->id ?>"/>
						<input type="text" name="username" required value="<?php echo $user->username ?>"/>
						<input type="email" name="email" required value="<?php echo $user->email ?>"/>
						<input type="text" name="name" required value="<?php echo $user->name ?>"/>
						<input type="text" name="surname" required value="<?php echo $user->surname ?>"/>
						<input type="text" name="address" value="<?php echo $user->address ?>"/>
						<input type="text" name="post" value="<?php echo $user->post ?>"/>
						<input type="tel" name="phone" value="<?php echo $user->phone ?>"/>
						<input type="password" name="password"/>
						<input type="checkbox" name="admin" value="admin" <?php
                        if($user->isAdmin == 1){ echo "checked"; }
                        ?>/>
					</div>
				</div>
				<div class="warning">Polja označena z <span>*</span> so obvezna</div>
				<input type="submit" name="submit" value="Posodobi"/> <br/>
			</form>
		</div>
	</div>