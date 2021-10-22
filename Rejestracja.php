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
	
</head>
<body class="d-flex flex-column align-content-center">	

	<div class="adjusting-box-20"></div>

	<main class="flex-1-1-auto">

		<form action="Przychody.html" class="container mw-px-400">

			<div class="row">

				<h1 class="text-center mb-3">Budżet osobisty</h1>

                <div class="d-flex mb-3">
                    <div class="w-100 h-90 d-flex justify-content-center">
                        <div class="horizontal-line-80 align-self-center"></div>
                    </div>
                    
                    <h2 class="text-center">Rejestracja</h2>
                    
                    <div class="w-100 h-90 d-flex justify-content-center">
                        <div class="horizontal-line-80 align-self-center"></div>
                    </div>
                </div>
				
				<div class="col-sm-12 d-flex flex-column">

					<div class="input-group mb-4 d-flex justify-content-center">
						<input type="text" class="form-control text-center" placeholder="Nazwa użytkownika"> 
					</div>

                    <div class="input-group mb-4 d-flex justify-content-center">
						<input type="text" class="form-control text-center" placeholder="Adres email"> 
					</div>

					<div class="input-group mb-4 d-flex justify-content-center">
						<input type="password" class="form-control text-center" placeholder="Hasło">
					</div>

                    <div class="input-group mb-3 d-flex justify-content-center">
						<input type="password" class="form-control text-center" placeholder="Powtórz hasło">
					</div>	

                    <div class="form-check d-flex w-90 align-self-center mb-3">
                        <input class="form-check-input align-self-center checkbox-big" type="checkbox" value="" id="flexCheckDefault" checked>
                        <label class="form-check-label d-block ps-3" for="flexCheckDefault">
                                Wyrażam zgodę na przetwarzanie danych, akceptuję 
                                <a href=""><u>Regulamin</u></a> oraz
                                <a href=""><u>Politykę Prywatności</u></a>.                               
                        </label>
                      </div>
					
					<button class="btn btn-primary col-12 shadow-none" type="submit">Zarejestruj się!</button>

					<div class="d-flex justify-content-center mt-4 mb-3">
						<div class="horizontal-line-80"></div>
					</div>
					<div class="d-flex flex-column justify-content-center">
						<a class="text-center mb-2" href="Logowanie.html">Wróć do strony głównej</a>
					</div>
					
				</div>
			</div>
		</form>
	</main>		
	
	<div class="adjusting-box-20"></div>
</body>
</html>