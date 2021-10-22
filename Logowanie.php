<?php
	session_start();

	if (isset($_SESSION['isLoggedIn']) && ($_SESSION['isLoggedIn']==true)) {
		header('Location: Przychody.php');
		exit();
	}

	if (isset($_SESSION['loginErrorMsg'])) {
		$loginErrorMsg = $_SESSION['loginErrorMsg'];
	}
	else {
		$loginErrorMsg = NULL;
	}
?>
<!DOCTYPE html>
<html lang = "pl">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv = "X-UA-Compatible" content = "IE=edge"/>	
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
	<title>Budżet osobisty</title>
	
	<meta name="description" content="Strona służąca do zapanowania nad swoim budżetem osobistym" />
	<meta name="keywords" content="finanse, budzet, budżet, rachunki, rachunkowość, pieniądze, bilans, wydatki, wydatek, dochodzy, przychody"/>	

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bonheur+Royale">
	
	<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="css/general.css">
	<link rel="stylesheet" href="css/loginRegister.css">
	
	<script src="js/bootstrap/bootstrap.bundle.min.js"></script>	

	<script>
		var loginErrorMsg = "<?php echo $loginErrorMsg; ?>";
		if (loginErrorMsg) {
			//do stuff
			console.log(loginErrorMsg);
		}
	</script>
	
</head>
<body class="d-flex flex-column align-content-center">	

	<div class="adjusting-box-20"></div>

	<main class="flex-1-1-auto">

		<form action="zaloguj.php" method="post" class="container text-center mw-px-400">

			<div class="row">

				<h1 class="text-center mb-5">Budżet osobisty</h1>
				
				<div class="col-sm-12">

					<div class="input-group mb-4 d-flex justify-content-center">
						<input type="text" name="login" class="form-control text-center" autocomplete="off" placeholder="Nazwa użytkownika"> 
					</div>

					<div class="input-group mb-4 d-flex justify-content-center">
						<input type="password" name="password" class="form-control text-center" autocomplete="off" placeholder="Hasło" >
					</div>	
					
					<button class="btn btn-primary col-12 shadow-none" type="submit">Zaloguj się!</button>

					<div class="d-flex justify-content-center mt-4 mb-3">
						<!-- 
							for some reason can't change color to white for <hr> :(,
							shade is different than basic, making it not desired.
						-->
						<div class="horizontal-line-80"></div>
					</div>
					<div class="d-flex flex-column justify-content-center">
						<a class="mb-2" href="Rejestracja.php">Nie posiadasz jeszcze konta?</a>		
						<a class="mb-2" href="">Zapomniałeś hasło?</a>	
					</div>
					
				</div>
			</div>
		</form>
	</main>		
	
	<div class="adjusting-box-20"></div>
</body>
</html>