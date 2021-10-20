<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header('Location: Logowanie.php');
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
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/income-expense.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/datepicker.css">

</head>
<body>

    <!-- Modal -->
    <div class="modal fade" id="pick-date" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content w-max-content mx-auto">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="modalLabel">Wybierz datę</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- bootstrap datepicker -->
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
                        <div class="btn-group flex-column w-100" role="group" aria-label="First group">
                            <hr class="mt-0 mb-2">
                            <a href="Przychody.html" type="button"
                                class="btn btn-nav shadow-none rounded-0 text-start active" aria-pressed="true">Dodaj
                                przychód</a>
                            <a href="Wydatki.html" type="button"
                                class="btn btn-nav shadow-none rounded-0 text-start">Dodaj
                                wydatek</a>
                            <a href="Bilans.html" type="button"
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
                                <a href="Logowanie.html" class="d-flex">
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

                    <div class="btn-group justify-self-left me-auto" role="group" aria-label="Navigation buttons">
                        <a href="Przychody.html" type="button" class="btn btn-nav rounded-0 m-0 pb-3 pt-3 active"
                            aria-pressed="true">Dodaj wydatek</a>
                        <a href="Wydatki.html" type="button" class="btn btn-nav rounded-0 m-0 pb-3 pt-3">Dodaj
                            wydatek</a>
                        <a href="Bilans.html" type="button" class="btn btn-nav rounded-0 m-0 pb-3 pt-3">Przeglądaj
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
                    <a href="Przychody.html" class="nav-link mb-2 active" aria-current="page">
                        Dodaj przychód
                    </a>
                </li>
                <li>
                    <a href="Wydatki.html" class="nav-link mb-2 me-2">
                        Dodaj wydatek
                    </a>
                </li>
                <li>
                    <a href="Bilans.html" class="nav-link me-2">
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
        px-auto p-md-0 pe-md-5 pt-4 py-md-5">
            <div class="row justify-content-center justify-content-md-start pt-md-2 pb-3">
                <!-- md - xxl -->
                <div class="col-10 col-md-5 col-lg-4 col-xl-3 d-flex p-0 pe-2">
                    <label for="amount"
                        class="form-label align-self-center m-0 ms-md-auto me-auto me-md-0 fs-5 fs-md-4">
                        Kwota:
                    </label>
                </div>
                <div class="col-10 col-md-6 col-lg-4 col-xl-3 d-flex p-0">
                    <div class="input-group">
                        <input type="text" class="form-control align-self-center" id="amount" autocomplete="off">
                        <span class="input-group-text">zł</span>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center justify-content-md-start pb-3">
                <div class="col-10 col-md-5 col-lg-4 col-xl-3 d-flex p-0 pe-2">
                    <label for="date" class="form-label align-self-center m-0 ms-md-auto me-auto me-md-0 fs-5 fs-md-4">
                        Data:
                    </label>
                </div>
                <div class="col-10 col-md-6 col-lg-4 col-xl-3 d-flex p-0">
                    <div class="input-group">
                        <input type="text" class="form-control align-self-center" id="date" autocomplete="off">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#pick-date" class="input-group-text p-0" id="datepicker-button">
                            <i class="material-icons p-calendar">
                                edit_calendar
                            </i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center justify-content-md-start pb-3">
                <div class="col-10 col-md-5 col-lg-4 col-xl-3 d-flex p-0 pe-2">
                    <label for="income-category"
                        class="form-label align-self-center m-0 ms-md-auto me-auto me-md-0 fs-5 fs-md-4">Wybierz
                        kategorię:</label>
                </div>
                <div class="col-10 col-md-6 col-lg-4 col-xl-3 d-flex p-0">
                    <select class="form-select classic align-self-end" aria-label="Kategoria przychodu"
                        id="income-category">
                        <option selected>Wynagrodzenie</option>
                        <option value="1">Odsetki bankowe</option>
                        <option value="2">Sprzedaż na allegro</option>
                        <option value="3">Inne</option>
                    </select>
                </div>
            </div>
            <div class="row justify-content-center justify-content-md-start pb-3">
                <div class="col-10 col-md-5 d-flex col-lg-4 col-xl-3 p-0 pe-2">
                    <label for="comment"
                        class="align-self-start m-0 ms-md-auto me-auto me-md-0 fs-5 fs-md-4">Komentarz:</label>
                </div>
                <div class="col-10 col-md-6 col-lg-6 col-xl-5 d-flex p-0">
                    <textarea class="form-control align-self-center" rows="5" id="comment"></textarea>
                </div>
            </div>
            <div class="row justify-content-center justify-content-md-start pb-3">
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

        var modal = new bootstrap.Modal($('#pick-date'));
        var today = new Date().toLocaleDateString();

        $('#date').val(today);

        $('.modal-body').datepicker({
            language: 'pl',
            todayHighlight: 'true',
        });

        $('.modal-body').on('changeDate', () => {
            $('#date').val($('.modal-body').datepicker('getFormattedDate'));
            modal.hide();
        });

        $('#datepicker-button').on('click',
            () => $('.modal-body').datepicker('update', $('#date').val())
        );

        var amountInputFormating = new Cleave('#amount', {
            numeral: true,
            delimiter: ' ',
            numeralDecimalMark: ',',
            numeralThousandsGroupStyle: 'thousand'
        });

        var dateInputFormating = new Cleave('#date', {
            date: true,
            delimiter: '.',
            numeralDecimalMark: ',',
            datePattern: ['d', 'm', 'Y']
        });
    });

</script>

</html>