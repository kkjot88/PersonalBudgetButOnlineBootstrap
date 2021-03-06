<?php
    session_start();

    if (!isset($_SESSION['notfound'])) {
        header('Location: zaloguj.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Budżet osobisty</title>

    <meta name="description" content="Strona służąca do zapanowania nad swoim budżetem osobistym" />
    <meta name="keywords"
        content="finanse, budzet, budżet, rachunki, rachunkowość, pieniądze, bilans, wydatki, wydatek, dochodzy, przychody" />

    <!-- jquery -->
    <script src="js/jquery/jquery.min.js"></script>

    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap/bootstrap-datepicker3.min.css">
    <script src="js/bootstrap/bootstrap-datepicker.min.js"></script>
    <script src="js/bootstrap/bootstrap-datepicker-pl.min.js"></script>

    <script src="js/cleave/cleave.min.js"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <link rel="stylesheet" href="css/general.css">

</head>
<body class="d-flex flex-column vh-100 align-items-center justify-content-center">    

    <div class="mb-2"><strong class="fs-1">404 not found</strong></div>

    <div class="mb-2 fs-2 text-invalid">
        <?php
            echo $_SESSION['notfound'];            
            unset($_SESSION['notfound']);
        ?>
    </div>

    <div class="mt-2 pb-5 mb-5"><a href="Logowanie.php" class="fs-3 text-decoration-underline">wróc do stronny logowania</a></div>

</body>
</html>

<?php
    session_unset();
?>