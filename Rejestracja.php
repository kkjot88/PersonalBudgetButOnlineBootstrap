<?php

	session_start();

	if (isset($_POST['username'])) {
		
		$isValid = true;

		$username = $_POST['username'];
		$emailPreSanitization = $_POST['email'];
		$email = filter_var($emailPreSanitization, FILTER_SANITIZE_EMAIL);
		$password = $_POST['password'];
		$passwordRepeat = $_POST['passwordRepeat'];
		$reCaptchaSecretKey = "6Le9augcAAAAAHarPZyouEy1VHHTxXyvZoex6ihe";
		$checkboxValue = isset($_POST['checkbox']);

		if ((strlen($username)<3) || (strlen($username) >20)) {
			$isValid = false;
			$_SESSION['e_username'] = "Nazwa użytkownika musi miec od 3 do 20 znaków"; //poprawic			
		}

		if (!ctype_alnum($username)) {
			$isValid = false;
			$_SESSION['e_username'] = "Nazwa użytkownika musi składać się tylko z liter i cyfr (bez polskich znaków)";
		}

		if ((filter_var($email, FILTER_VALIDATE_EMAIL)==false) || ($email != $emailPreSanitization)) {		
			$isValid = false;
			$_SESSION['e_email'] = "Nieprawidłowy adres email";
		}	

		if ((strlen($password) < 3) || (strlen($password) > 20)) {
			$isValid = false;
			$_SESSION['e_password'] = "Hasło musi posiadać od 8 do 20 znaków";
		}

		if ($password != $passwordRepeat) {
			$isValid = false;
			$_SESSION['e_password'] = "Hasła nie są takie same";
		}
		$passwordHashed = password_hash($password, PASSWORD_DEFAULT);

		if (!$checkboxValue) {
			$isValid = false;
			$_SESSION['e_checkbox'] = "Potwierdź akceptację regulaminu";
		}

		$checkCaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$reCaptchaSecretKey.'&response='.$_POST['g-recaptcha-response']);
		$captchaResponse = json_decode($checkCaptcha);
		if (!($captchaResponse->success)) {
			$isValid = false;
			$_SESSION['e_captcha'] = "Potwierdź że jesteś człowiekiem";
		}

		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try {
			$connection = new mysqli($host, $db_user, $db_password, $db_name);

			if ($connection->connect_errno != 0) {
				throw new Exception(mysqli_connect_errno());
			}
			else {
				$result = $connection->query("SELECT userid FROM users WHERE email='$email'");

				if (!$result) {
					throw new Exception($connection->error);
				}
				$emailsNumber = $result->num_rows;

				if($emailsNumber > 0) {
					$isValid = false;
					$_SESSION['e_email'] = "Istnieje już konto o takim adresie email";
				}

				$result = $connection->query("SELECT userid FROM users WHERE name='$username'");

				if (!$result) {
					throw new Exception($connection->error);
				}
				$usernamesNumber = $result->num_rows;

				if($usernamesNumber > 0) {
					$isValid = false;
					$_SESSION['e_username'] = "Istnieje już konto o takiej nazwie użytkownika";
				}

				if ($isValid == true) {

					if ($incomeDefaultCategoriesQuery = $connection->query(
						"SELECT 
							icd.category icdcat,
							icd.categoryid icdcatid
						FROM incomecategories_default icd
						ORDER BY icd.categoryid"
					)) {
						$incomeDefaultCategories = [];
						$i = 0;
						while ($category = $incomeDefaultCategoriesQuery->fetch_assoc()) {
							$incomeDefaultCategories[$i] = '"'.$connection->real_escape_string($category['icdcat']).'"';
							$i++;
						}
					} else {
						throw new Exception($connection->error);
					}
		
					if ($expenseDefaultCategoriesQuery = $connection->query(
						"SELECT 
							ecd.category ecdcat,
							ecd.categoryid ecdcatid
						FROM expensecategories_default ecd
						ORDER BY ecd.categoryid"
					)) {
						$expenseDefaultCategories = [];            
						$i = 0;
						while ($category = $expenseDefaultCategoriesQuery->fetch_assoc()) {
							$expenseDefaultCategories[$i] = '("'.$connection->real_escape_string($category['ecdcat']).'")';
							$i++;
						}            
					} else {
						throw new Exception($connection->error);
					}
		
					if ($defaultPaymentMethodsQuery = $connection->query(
						"SELECT 
							pmd.method pmdmet,
							pmd.methodid pmdmetid
						FROM paymentmethods_default pmd
						ORDER BY pmd.methodid"
					)) {
						$defaultPaymentMethods = [];            
						$i = 0;
						while ($method = $defaultPaymentMethodsQuery->fetch_assoc()) {
							$defaultPaymentMethods[$i] = '("'.$connection->real_escape_string($method['pmdmet']).'")';
							$i++;
						}            
					} else {
						throw new Exception($connection->error);
					}
					
					$connection->begin_transaction();
					
					$insertUser = $connection->query(
						"INSERT INTO
							users 
						VALUES (NULL, '$username', '$passwordHashed', '$email')");

					$insertedUserId = $connection->insert_id;                

					$incomeCategoriesIds = [];
					$expenseCategoriesIds = [];
					$paymentMethodsIds = [];

					foreach($incomeDefaultCategories as $category) {
						$insertIncomeCategories = $connection->query(
							'INSERT INTO incomecategories (category) 
							VALUES ('.$category.')'
						);
						if ($connection->errno == 1062) {                    
							$insertIncomeCategories = true;
						}
						$incomeCategoriesIdsQuery = $connection->query(
							"SELECT categoryid
							FROM incomecategories
							WHERE category=$category"
						);
						array_push($incomeCategoriesIds, $incomeCategoriesIdsQuery->fetch_assoc()['categoryid']);
					}          
					
					foreach ($incomeCategoriesIds as $id) {
						$insertUserIncomePair = $connection->query(
							"INSERT INTO users_incomecategories
							VALUES ($insertedUserId, $id)"
						); 
						if ($connection->errno == 1062) {                    
							$insertUserIncomePair = true;
						}
					}

					foreach($expenseDefaultCategories as $category) {
						$insertExpenseCategories = $connection->query(
							'INSERT INTO expensecategories (category) 
							VALUES ('.$category.')'
						);
						if ($connection->errno == 1062) {
							$insertExpenseCategories = true;
						}
						$expenseCategoriesIdsQuery = $connection->query(
							"SELECT categoryid
							FROM expensecategories
							WHERE category=$category"
						);
						array_push($expenseCategoriesIds, $expenseCategoriesIdsQuery->fetch_assoc()['categoryid']);
					}          
					
					foreach ($expenseCategoriesIds as $id) {
						$insertUserExpensePair = $connection->query(
							"INSERT INTO users_expensecategories
							VALUES ($insertedUserId, $id)"
						); 
						if ($connection->errno == 1062) {                    
							$insertUserIncomePair = true;
						}
					}            

					foreach($defaultPaymentMethods as $method) {
						$insertPaymentMethods = $connection->query(
							'INSERT INTO paymentmethods (method) 
							VALUES ('.$method.')'
						);
						if ($connection->errno == 1062) {                    
							$insertPaymentMethods = true;
						}
						$paymentMethodsIdsQuery = $connection->query(
							"SELECT methodid
							FROM paymentmethods
							WHERE method=$method"
						);
						array_push($paymentMethodsIds, $paymentMethodsIdsQuery->fetch_assoc()['methodid']);
					}          
					
					foreach ($paymentMethodsIds as $id) {
						$insertUserMethodPair = $connection->query(
							"INSERT INTO users_paymentmethods
							VALUES ($insertedUserId, $id)"
						); 
						if ($connection->errno == 1062) {                    
							$insertUserMethodPair = true;
						}
					}
			
					if (gettype($incomeCategoriesIdsQuery) != 'boolean') $incomeCategoriesIdsQuery = true;
					if (gettype($expenseCategoriesIdsQuery) != 'boolean') $expenseCategoriesIdsQuery = true;
					if (gettype($paymentMethodsIdsQuery) != 'boolean') $paymentMethodsIdsQuery = true;		

					if (!$insertUser 
					|| !$insertIncomeCategories || !$incomeCategoriesIdsQuery || !$insertUserIncomePair
					|| !$insertExpenseCategories || !$expenseCategoriesIdsQuery || !$insertUserExpensePair 
					|| !$insertPaymentMethods || !$paymentMethodsIdsQuery || !$insertUserMethodPair) {
						throw new Exception($connection->error);
					}
					else {
						$_SESSION['isRegistrationSuccesful'] = true;
						$_SESSION['username'] = $username;
						$_SESSION['password'] = $password;
						$connection->commit();
						header('Location: witamy.php');
					}					
				}
				$connection->close();
			}
		}
		catch (Exception $e) {
			$_SESSION['notfound'] = "Rejestracja nie powiodła się";
        	header('Location: notfound.php');
		}		
	}

	//-----------------debug-function-----------------------
    function console($data, $context = '') {

        // Buffering to solve problems frameworks, like header() in this and not a solid return.
        ob_start();
        
        $output  = 'console.log(' . json_encode($data) . ');';
        $output  = sprintf('<script>%s</script>', $output);

        echo $output;
    }
    //------------------------------------------------------
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
	
	<script src="js/bootstrap/bootstrap.bundle.min.js"></script>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bonheur+Royale">
	
	<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="css/general.css">
	<link rel="stylesheet" href="css/loginRegister.css">	

	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
</head>
<body class="d-flex flex-column align-content-center">	

	<div class="adjusting-box-20"></div>

	<main class="flex-1-1-auto">

		<form method="post" class="container mw-px-400">

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

					<div class="input-group mb-2 d-flex justify-content-center">
						<input type="text" class="form-control text-center" placeholder="Nazwa użytkownika"
						name="username" autocomplete="off"
						value = "<?php									
									echo (isset($username)) ? $username : '';
								?>"> 
					</div>					

					<?php
						if (isset($_SESSION['e_username'])) {
							echo '<div class="text-invalid mx-auto">'.$_SESSION['e_username'].'</div>';
							unset($_SESSION['e_username']);
						}
					?>

                    <div class="input-group mt-2 mb-2 d-flex justify-content-center">
						<input type="text" class="form-control text-center" placeholder="Adres email"
						name="email"
						value = "<?php
									echo (isset($email)) ? $email : '';
								?>"> 
					</div>

					<?php
						if (isset($_SESSION['e_email'])) {
							echo '<div class="text-invalid mx-auto">'.$_SESSION['e_email'].'</div>';
							unset($_SESSION['e_email']);
						}
					?>

					<div class="input-group mt-2 mb-2 d-flex justify-content-center">
						<input type="password" class="form-control text-center" placeholder="Hasło"
						name="password">
					</div>

					<?php
						if (isset($_SESSION['e_password'])) {
							echo '<div class="text-invalid mx-auto">'.$_SESSION['e_password'].'</div>';
						}
					?>

                    <div class="input-group mt-2 mb-2 d-flex justify-content-center">
						<input type="password" class="form-control text-center" placeholder="Powtórz hasło"
						name="passwordRepeat">
					</div>	

					<?php
						if (isset($_SESSION['e_password'])) {
							echo '<div class="text-invalid mx-auto">'.$_SESSION['e_password'].'</div>';
							unset($_SESSION['e_password']);
						}
					?>

					<div class="ms-sm-3 mt-2">
						<div class="form-check d-flex w-90 align-self-center ms-sm-4 mb-2">
							<input class="form-check-input align-self-center checkbox-big" type="checkbox" id="flexCheckDefault" name="checkbox" 
							<?php
								if (!isset($_SESSION['e_checkbox'])) echo 'checked';								
							?>> 
							<label class="form-check-label d-block ps-3" for="flexCheckDefault">
									Wyrażam zgodę na przetwarzanie danych, akceptuję 
									<a href=""><u>Regulamin</u></a> oraz
									<a href=""><u>Politykę Prywatności</u></a>.                               
							</label>
						</div>
					</div>

					<?php
						if (isset($_SESSION['e_checkbox'])) {
							echo '<div class="text-invalid mx-auto">'.$_SESSION['e_checkbox'].'</div>';
							unset($_SESSION['e_checkbox']);
						}
					?>				  
					
					<div class="g-recaptcha mx-sm-auto mt-2 mb-2" data-sitekey="6Le9augcAAAAAKQPrwbiTCKLM0EWIwOvDk-E6h0_" data-theme="dark"></div>

					<?php
						if (isset($_SESSION['e_captcha'])) {
							echo '<div class="text-invalid mx-auto mb-1">'.$_SESSION['e_captcha'].'</div>';
							unset($_SESSION['e_captcha']);
						}
					?>
					
					<button class="btn btn-primary col-12 shadow-none mt-2" type="submit">Zarejestruj się!</button>	

					<div class="d-flex justify-content-center mt-4 mb-3">
						<div class="horizontal-line-80"></div>
					</div>
					<div class="d-flex flex-column justify-content-center">
						<a class="text-center mb-2" href="Logowanie.php">Wróć do strony głównej</a>
					</div>
					
				</div>
			</div>
		</form>
	</main>		
	
	<div class="adjusting-box-20"></div>
</body>
</html>