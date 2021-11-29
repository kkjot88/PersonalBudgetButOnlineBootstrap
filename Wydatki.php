<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header('Location: Logowanie.php');
        exit();
    }

    $userid = $_SESSION['user']['userid'];      

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        }
        else {             
            if ($categories = $connection->query(
                "SELECT 
                    ec.category,
                    ec.categoryid
                FROM expensecategories ec
                INNER JOIN users_expensecategories eic
                USING (categoryid)
                WHERE eic.userid = '$userid'
                ORDER BY ec.categoryid"
            )) {} else {
                throw new Exception($connection->error);
            }

            if ($methods = $connection->query(
                "SELECT 
                    pm.method,
                    pm.methodid
                FROM paymentmethods pm
                INNER JOIN users_paymentmethods upm
                USING (methodid)
                WHERE upm.userid = '$userid'
                ORDER BY pm.methodid"
            )) {} else {
                throw new Exception($connection->error);
            }
            $connection->close();
        }
	} 
    catch (Exception $e) {
        $_SESSION['notfound'] = "Operacja nie powiodła się";
        header('Location: notfound.php');
        console("Błąd serwera something something");
        console($e);
	}	

    //-----------------debug-function-----------------------
    function console($data, $context = '') {

        // Buffering to solve problems frameworks, like header() in this and not a solid return.
        ob_start();

        $output = 'console.log(' . json_encode($data) . ');';
        $output = sprintf('<script>%s</script>', $output);

        echo $output;
    }
    //------------------------------------------------------
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
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/income-expense.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/datepicker.css">

</head>

<body>

    <!-- Success Modal //to be styled -->
    <div class="modal fade" id="success" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content w-max-content mx-auto">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="modalLabel"></h5>
                </div>
                <div class="modal-body fs-5">
                    Przychód dodany pomyślnie
                </div>
                <div class="modal-footer pt-3">                   
                    <button type="button" class="btn-primary mx-auto" data-bs-dismiss="modal" aria-label="Kontynuuj">
                        Kontynuuj
                    </button>
                </div>
            </div>
        </div>
    </div>    

    <!-- Modal -->
    <div class="modal fade" id="pick-date" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content w-max-content mx-auto">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="modalLabel">Wybierz datę</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="datepicker-modal-body">                        
                        <!-- bootstrap datepicker -->
                    </div>
                </div>
                <div class="modal-footer pt-3"></div>
            </div>
        </div>
    </div>

    <nav>
        <!-- up to md (must be first) -->
        <div class="d-flex d-lg-none fixed-top">

            <!-- sm with burger -->
            <div class="d-md-none navbar fixed-top pb-0">
                <div class="container-fluid">

                    <span class="text-white mb-2">Budżet osobisty</span>

                    <div class="mb-2">
                        <button class="navbar-toggler custom-toggler pe-2 ps-2" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="btn-group flex-column w-100" role="group" aria-label="Navigation buttons">
                            <hr class="mt-0 mb-2">
                            <a href="Przychody.php" type="button"
                                class="btn btn-nav shadow-none rounded-0 text-start">Dodaj
                                przychód</a>
                            <a href="Wydatki.php" type="button"
                                class="btn btn-nav shadow-none rounded-0 text-start active" aria-pressed="true">Dodaj
                                wydatek</a>
                            <a href="Bilans.php" type="button"
                                class="btn btn-nav shadow-none rounded-0 text-start">Przeglądaj
                                bilans</a>
                            <hr class="mt-2 mb-2">
                            <div class="d-flex mb-2 align-items-center">
                                <img src="img/default-user.png" alt="" width="32" height="32" class="rounded-circle">
                                <span class="ms-2 me-auto">Default user</span>
                                <a href="#" class="d-flex pe-3">
                                    <i class="material-icons fs-1">
                                        settings
                                    </i>
                                </a>
                                <a href="#" class="d-flex pe-3">
                                    <i class="material-icons fs-1">
                                        manage_accounts
                                    </i>
                                </a>
                                <a href="Logowanie.php" class="d-flex">
                                    <i class="material-icons fs-1">
                                        logout
                                    </i>
                                </a>
                            </div>
                            <hr class="mt-0 mb-2">
                        </div>
                    </div>
                </div>
            </div>

            <!-- md expanded -->
            <div class="d-none d-md-flex d-lg-none navbar navbar-expand-md fixed-top p-0">
                <div class="container-fluid">

                    <span class="text-white my-auto pe-2 me-1">Budżet osobisty</span>

                    <div class="vr vr-40px"></div>

                    <div class="btn-group justify-self-left me-auto" role="group" aria-label="First group">
                        <a href="Przychody.php" type="button" class="btn btn-nav rounded-0 m-0 pb-3 pt-3">Dodaj
                            wydatek</a>
                        <a href="Wydatki.php" type="button" class="btn btn-nav rounded-0 m-0 pb-3 pt-3 active"
                            aria-pressed="true">Dodaj
                            wydatek</a>
                        <a href="Bilans.php" type="button" class="btn btn-nav rounded-0 m-0 pb-3 pt-3">Przeglądaj
                            bilans</a>
                    </div>

                    <div class="d-flex pe-2">
                        <div class="vr vr-40px "></div>
                    </div>

                    <div class="dropdown d-flex">
                        <a href="#" class="d-flex align-items-center dropdown-toggle flex-row-reverse " id="user-md"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="img/default-user.png" alt="" width="32" height="32" class="rounded-circle ms-2">
                            <span class="me-auto">Default user</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end p-2" aria-labelledby="user-md">
                            <li><a class="dropdown-item p-1" href="#">Ustawienia</a></li>
                            <li><a class="dropdown-item p-1" href="#">Profil</a></li>
                            <li>
                                <hr class="dropdown-divider m-0 mt-2 mb-2">
                            </li>
                            <li><a class="dropdown-item p-1" href="wyloguj.php">Wyloguj się</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <!-- lg and up -->
        <div class="d-none d-lg-flex flex-column p-3 pe-0 sticky-top vh-100">
            <div class="text-white fs-4">
                Budżet osobisty
            </div>
            <hr class="me-3">
            <ul class="nav nav-pills flex-column mb-auto">
                <li>
                    <a href="Przychody.php" class="nav-link mb-2 me-2">
                        Dodaj przychód
                    </a>
                </li>
                <li>
                    <a href="Wydatki.php" class="nav-link mb-2 active" aria-current="page">
                        Dodaj wydatek
                    </a>
                </li>
                <li>
                    <a href="Bilans.php" class="nav-link me-2">
                        Przeglądaj bilans
                    </a>
                </li>
            </ul>
            <hr class="me-3">
            <div class="dropdown dropup">
                <a href="#" class="d-flex align-items-center dropdown-toggle pe-2 me-3" id="user-lg"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="img/default-user.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <span class="me-auto">Default user</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end p-2" aria-labelledby="user-lg">
                    <li><a class="dropdown-item p-1" href="#">Ustawienia</a></li>
                    <li><a class="dropdown-item p-1" href="#">Profil</a></li>
                    <li>
                        <hr class="dropdown-divider m-0 mt-2 mb-2">
                    </li>
                    <li><a class="dropdown-item p-1" href="wyloguj.php">Wyloguj się</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="d-flex flex-column">

        <form class="container d-flex flex-column 
        m-0 mx-auto m-lg-0
        px-auto pt-4 p-md-0 pe-md-5 py-md-5" action="addFinances.php" method="post">
            
            <!-- hidden input for income/expense differentation with post-->
            <input type="hidden" name="type" value="expense">

            <div class="row justify-content-center justify-content-md-start pt-md-2 pb-1 pb-md-2">
                <!-- md - xxl -->
                <div class="col-10 col-md-5 col-lg-4 col-xl-3 d-flex p-0 pe-2">
                    <label for="amount"
                        class="form-label align-self-center m-0 ms-md-auto me-auto me-md-0 fs-5 fs-md-4">Kwota:</label>
                </div>
                <div class="col-10 col-md-6 col-lg-4 col-xl-3 d-flex p-0">
                    <div class="input-group">
                        <input type="text" class="form-control align-self-center" id="amount" name="amount" autocomplete="off">
                        <span class="input-group-text">zł</span>
                    </div>
                </div>
            </div>

            <?php 
            echo (isset($_SESSION['e_amount'])) ?
            '<div class="row justify-content-center justify-content-md-start">
                <div class="col-10 col-md-5 col-lg-4 col-xl-3 d-flex p-0 pe-2"></div>
                <div class="col-10 col-md-6 col-lg-4 col-xl-3 d-flex p-0">
                    <span class="form-label align-self-center m-0 fs-5 fs-md-4 text-invalid">'.
                        $_SESSION['e_amount'].
                    '</span>
                </div>
            </div>' : ''; 
            unset($_SESSION['e_amount']);
            ?>

            <div class="row justify-content-center justify-content-md-start pb-1 pt-md-2 pb-md-3">
                <div class="col-10 col-md-5 col-lg-4 col-xl-3 d-flex p-0 pe-2">
                    <label for="date" class="form-label align-self-center m-0 ms-md-auto me-auto me-md-0 fs-5 fs-md-4">
                        Data:
                    </label>
                </div>
                <div class="col-10 col-md-6 col-lg-4 col-xl-3 d-flex p-0">
                    <div class="input-group">
                        <input type="text" class="form-control align-self-center" id="date" name="date" autocomplete="off">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#pick-date"
                            class="input-group-text p-0" id="datepicker-button">
                            <i class="material-icons p-calendar">
                                edit_calendar
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center justify-content-md-start pb-1 pb-md-3">
                <div class="col-10 col-md-5 col-lg-4 col-xl-3 d-flex p-0 pe-2">
                    <label for="payment-method"
                        class="form-label align-self-center m-0 ms-md-auto me-auto me-md-0 fs-5 fs-md-4">Sposób
                        płatności:</label>
                </div>
                <div class="col-10 col-md-6 col-lg-4 col-xl-3 d-flex p-0">
                    <select class="form-select classic align-self-end" aria-label="Metoda płatności"
                        id="payment-method" name="method">

                        <?php
                            $selected = true;
                            while ($option = $methods->fetch_assoc()) {
                                echo ($selected) ?
                                    '<option selected>'.$option['method'].'</option>':
                                    '<option>'.$option['method'].'</option>';
                                $selected = false;
                            }
                        ?>

                    </select>
                </div>
            </div>
            <div class="row justify-content-center justify-content-md-start pb-1 pb-md-3">
                <div class="col-10 col-md-5 col-lg-4 col-xl-3 d-flex p-0 pe-2">
                    <label for="expense-category"
                        class="form-label align-self-center m-0 ms-md-auto me-auto me-md-0 fs-5 fs-md-4">Wybierz
                        kategorię:</label>
                </div>
                <div class="col-10 col-md-6 col-lg-4 col-xl-3 d-flex p-0">
                    <select class="form-select classic align-self-end" aria-label="Kategoria wydatku"
                        id="expense-category" name="expense-category">
                        
                        <?php
                            $selected = true;
                            while ($option = $categories->fetch_assoc()) {
                                echo ($selected) ?
                                    '<option selected>'.$option['category'].'</option>':
                                    '<option>'.$option['category'].'</option>';
                                $selected = false;
                            }
                        ?>

                    </select>
                </div>
            </div>
            <div class="row justify-content-center justify-content-md-start pb-1 pb-3">
                <div class="col-10 col-md-5 d-flex col-lg-4 col-xl-3 p-0 pe-2">
                    <label for="comment"
                        class="align-self-start m-0 ms-md-auto me-auto me-md-0 fs-5 fs-md-4">Komentarz:</label>
                </div>
                <div class="col-10 col-md-6 col-lg-6 col-xl-5 d-flex p-0">
                    <textarea class="form-control align-self-center" rows="5" id="comment" name="comment"></textarea>
                </div>
            </div>
            <div class="row justify-content-center justify-content-md-start pb-1 pb-3">
                <div class="col-10 col-md-5 col-lg-4 col-xl-3 d-flex p-0 pe-2"></div>
                <div class="col-10 col-md-6 col-lg-4 col-xl-3 d-flex p-0">
                    <button type="submit" class="btn btn-primary shadow-none w-50 me-2">
                        <strong>Dodaj</strong>
                    </button>
                    <button class="btn shadow-none btn-primary w-50">
                        <strong>Anuluj</strong>
                    </button>
                </div>
            </div>
        </form>
    </main>
</body>

<script>

    $(function () {

        var success = <?php
            echo isset($_SESSION['success']) ? 'true' : 'false';
            unset($_SESSION['success']);
        ?>

        var successModal = new bootstrap.Modal($('#success'));
        success ? successModal.show() : successModal.hide();

        var modal = new bootstrap.Modal($('#pick-date'));
        var today = new Date().toLocaleDateString('pl-PL');

        $('#date').val(today);

        $('#datepicker-modal-body').datepicker({
            language: 'pl',
            todayHighlight: 'true',
        });

        $('#datepicker-modal-body').on('changeDate', () => {
            $('#date').val($('#datepicker-modal-body').datepicker('getFormattedDate'));
            modal.hide();
        });

        $('#datepicker-button').on('click',
            () => $('#datepicker-modal-body').datepicker('update', $('#date').val())
        );

        var amountInputFormating = new Cleave('#amount', {
            numeral: true,
            delimiter: ' ',
            numeralDecimalMark: ',',
            numeralThousandsGroupStyle: 'thousand'
        });
        /*
        var dateInputFormating = new Cleave('#date', {
            date: true,
            delimiter: '.',
            numeralDecimalMark: ',',
            datePattern: ['d', 'm', 'Y']
        });*/
    });

</script>

</html>