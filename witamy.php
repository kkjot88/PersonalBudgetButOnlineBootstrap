<?php
    session_start();

    if (!isset($_SESSION['isRegistrationSuccesful'])) {
        header('Location: Rejestracja.php');
        exit();
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
    
	<script src="js/bootstrap/bootstrap.bundle.min.js"></script>	
	
	<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="css/general.css">
	<link rel="stylesheet" href="css/loginRegister.css">	
	
</head>
<body class="d-flex h-75">
    
    <form action="zaloguj.php" class="d-flex flex-column mx-auto my-auto ">        

        <div class="mx-auto fs-1 mb-2">Witaj 
            <?php
            echo '<strong class="text-success">'.$_SESSION['username'].'</strong>!';
            ?>            
        </div>
        <div class="fs-2">Twoja rejestracja przebiegła pomyślnie</div>		          
		<button class="btn btn-primary btn-lg shadow-none w-50 mx-auto mt-4 fs-4" type="submit">
            Przejdź do aplikacji
        </button>
        <div class="mx-auto fs-4 mt-3">lub</div>        
        <a class="d-flex border-bottom-1 mx-auto mt-2 px-2 fs-4" href="witamyExit.php">
            wróć do strony głównej.
        </a>
    </form>

</body>
</html>