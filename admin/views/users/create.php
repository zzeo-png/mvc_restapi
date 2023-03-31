<head>
	<link rel="stylesheet" href="../styles/register.css">
</head>

<div class="container">
	<div class="heading">Registracija</div>
		<div class="form">
			<form action="?controller=users&action=store" method="POST">
				<div class="f_main">
					<div class="labels">
						<label>Uporabniško ime<span>*</span></label>
						<label>E-Naslov<span>*</span></label>
						<label>Ime<span>*</span></label>
						<label>Priimek<span>*</span></label>
						<label>Naslov</label>
						<label>Pošta</label>
						<label>Telefon</label>
						<label>Geslo<span>*</span></label>
						<label>Ponovi geslo<span>*</span></label>
						<label>Admin<span></span></label>
					</div>
					<div class="inputs">
						<input type="text" name="username" required/>
						<input type="email" name="email" required/>
						<input type="text" name="name" required/>
						<input type="text" name="surname" required/>
						<input type="text" name="address"/>
						<input type="text" name="post"/>
						<input type="tel" name="phone"/>
						<input type="password" name="password" required/>
						<input type="password" name="repeat_password" required/>
						<input type="checkbox" name="admin" value="admin"/>
					</div>
				</div>
				<div class="warning">Polja označena z <span>*</span> so obvezna</div>
				<input type="submit" name="submit" value="Pošlji"/> <br/>
			</form>
		</div>
	</div>