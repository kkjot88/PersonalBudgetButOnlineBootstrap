<?php
    session_start();

    if (!isset($_SESSION['isLoggedIn'])) {
        header('Location: Logowanie.php');
        exit();
    }

    $currentUserId = $_SESSION['user']['userid'];    
    $username = $_SESSION['user']['name'];

    $today = new dateTime();
    $dateFrom = date('Y-m-01', strtotime($today->format('Y-m-d')));
    $dateTo = date('Y-m-t', strtotime($today->format('Y-m-d')));    

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) {
            throw new Exception($connection->error,$connection->errno);
        }
        else {

            //get sum of each income catogory for given user in given period
            $sumOfEachIncomeCategoryQuery = $connection->query(
                "SELECT 
                    ic.category AS category,
                    SUM(i.amount) AS amount
                FROM incomes i
                INNER JOIN incomecategories ic
                USING (categoryid)
                WHERE i.userid = {$currentUserId} AND i.date >= '{$dateFrom}' AND i.date <= '{$dateTo}'
                GROUP BY i.categoryid 
                ORDER BY SUM(i.amount) DESC"
            );

            //get sum of each expense catogory for given user in given period
            $sumOfEachExpenseCategoryQuery = $connection->query(
                "SELECT 
                    ec.category AS category,
                    SUM(e.amount) AS amount
                FROM expenses e
                INNER JOIN expensecategories ec
                USING (categoryid)
                WHERE e.userid = {$currentUserId} AND e.date >= '{$dateFrom}' AND e.date <= '{$dateTo}'
                GROUP BY e.categoryid 
                ORDER BY SUM(e.amount) DESC"
            );            

            if (gettype($sumOfEachIncomeCategoryQuery) != 'object' ||
                gettype($sumOfEachExpenseCategoryQuery) != 'object') {
                    throw new Exception($connection->error,$connection->errno);
            }            
            
            $sumOfEachIncomeCategory = $sumOfEachIncomeCategoryQuery->fetch_all(MYSQLI_ASSOC);
            $sumOfEachExpenseCategory = $sumOfEachExpenseCategoryQuery->fetch_all(MYSQLI_ASSOC);

            $connection->close();
        }
	} 
    catch (Exception $e) {
        $_SESSION['notfound'] = "Operacja nie powiodła się";
        header('Location: notfound.php');        
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
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Budżet osobisty</title>

    <meta name="description" content="Strona służąca do zapanowania nad swoim budżetem osobistym" />
    <meta name="keywords"
        content="finanse, budzet, budżet, rachunki, rachunkowość, pieniądze, bilans, wydatki, wydatek, dochodzy, przychody" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- jquery -->
    <script src="js/jquery/jquery.min.js"></script>

    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <script src="js/bootstrap/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap/bootstrap-datepicker3.min.css">
    <script src="js/bootstrap/bootstrap-datepicker.min.js"></script>
    <script src="js/bootstrap/bootstrap-datepicker-pl.min.js"></script>

    <script src="js/cleave/cleave.min.js"></script>

    <link rel="stylesheet" href="css/general.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/balance.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/datepicker.css">

    <script>

        var incomesObj;
        var expensesObj;
        var incomesTotal;
        var expensesTotal;
        var balanceFinal;   

        var incomesObj = JSON.parse('<?php echo json_encode($sumOfEachIncomeCategory); ?>');
        var expensesObj = JSON.parse('<?php echo json_encode($sumOfEachExpenseCategory); ?>');

        var todayDate = new Date();
        var firstDayOfCurrentMonth = new Date(todayDate.getFullYear(), todayDate.getMonth(), 1).toLocaleDateString('pl-PL', {year: "numeric", month: "2-digit", day: "2-digit"});
        var lastDayOfCurrentMonth = new Date(todayDate.getFullYear(), todayDate.getMonth() + 1, 0).toLocaleDateString('pl-PL', {year: "numeric", month: "2-digit", day: "2-digit"});
        var firstDayOfPreviousMonth = new Date(todayDate.getFullYear(), todayDate.getMonth() - 1, 1).toLocaleDateString('pl-PL', {year: "numeric", month: "2-digit", day: "2-digit"});
        var lastDayOfPreviousMonth = new Date(todayDate.getFullYear(), todayDate.getMonth(), 0).toLocaleDateString('pl-PL', {year: "numeric", month: "2-digit", day: "2-digit"});
        var firstDayOfCurrentYear = new Date(todayDate.getFullYear(), 0, 1).
        toLocaleDateString('pl-PL', {year: "numeric", month: "2-digit", day: "2-digit"});
        var lastDayOfCurrentYear = new Date(todayDate.getFullYear() + 1, 0, 0).
        toLocaleDateString('pl-PL', {year: "numeric", month: "2-digit", day: "2-digit"});

        $(function () {
            
            updateFinancesInAccordion(incomesObj,"#incomes-table");
            updateFinancesInAccordion(expensesObj,"#expenses-table");
            calculateBalance();

            /* this ONLY works because of change in bootstrap-datepicker.min.js file:

            hasClass("input-daterange")||p.inputs?(a.extend(p,{inputs:p.inputs||b.find("div").toArray()})
                        b.find("input") => b.find("div")

            */
            $('.input-daterange').datepicker({
                language: 'pl',
                todayHighlight: 'true',
            });

            var modal = new bootstrap.Modal($('#pick-date-period'));

            $('#from').val(firstDayOfCurrentMonth);
            $('#to').val(lastDayOfCurrentMonth);            

            $('#period').on('change', () => {
                let selectedOption = $("option:selected", this).val();
                switch (selectedOption) {
                    case '0':
                        disableDateinput("from");
                        disableDateinput("to");
                        $('#from').val(firstDayOfCurrentMonth);
                        $('#to').val(lastDayOfCurrentMonth);
                        $('#datepicker-from').datepicker('clearDates');
                        $('#datepicker-to').datepicker('clearDates');
                        $(".datepicker-button").addClass("d-none");
                        $.ajax({
                            type: "POST",
                            url: "bilansBackend.php",
                            data: $("#dates").serialize(),
                                success: function(data) {
                                    if (data == "connection-error") {
                                        window.location.href = "notfound.php";
                                    } else {
                                        incomesObj = JSON.parse(data.split("]")[0]+']');
                                        expensesObj = JSON.parse(data.split("]")[1]+']');
                                        updateFinancesInAccordion(incomesObj,"#incomes-table");
                                        updateFinancesInAccordion(expensesObj,"#expenses-table");
                                        calculateBalance();
                                        initChart();    
                                    }
                                }
                        });                   
                        return false;
                        break;
                    case '1':
                        disableDateinput("from");
                        disableDateinput("to");
                        $('#from').val(firstDayOfPreviousMonth);
                        $('#to').val(lastDayOfPreviousMonth);
                        $('#datepicker-from').datepicker('clearDates');
                        $('#datepicker-to').datepicker('clearDates');
                        $(".datepicker-button").addClass("d-none");
                        $.ajax({
                            type: "POST",
                            url: "bilansBackend.php",
                            data: $("#dates").serialize(),
                                success: function(data) {
                                    if (data == "connection-error") {
                                        window.location.href = "notfound.php";
                                    } else {
                                        incomesObj = JSON.parse(data.split("]")[0]+']');
                                        expensesObj = JSON.parse(data.split("]")[1]+']');
                                        updateFinancesInAccordion(incomesObj,"#incomes-table");
                                        updateFinancesInAccordion(expensesObj,"#expenses-table");
                                        calculateBalance();
                                        initChart();    
                                        
                                    }
                                }
                        });
                        return false;
                        break;
                    case '2':
                        disableDateinput("from");
                        disableDateinput("to");
                        $('#from').val(firstDayOfCurrentYear);
                        $('#to').val(lastDayOfCurrentYear);
                        $('#datepicker-from').datepicker('clearDates');
                        $('#datepicker-to').datepicker('clearDates');
                        $(".datepicker-button").addClass("d-none");
                        $.ajax({
                            type: "POST",
                            url: "bilansBackend.php",
                            data: $("#dates").serialize(),
                                success: function(data) {
                                    if (data == "connection-error") {
                                        window.location.href = "notfound.php";
                                    } else {
                                        incomesObj = JSON.parse(data.split("]")[0]+']');
                                        expensesObj = JSON.parse(data.split("]")[1]+']');
                                        updateFinancesInAccordion(incomesObj,"#incomes-table");
                                        updateFinancesInAccordion(expensesObj,"#expenses-table");
                                        calculateBalance();
                                        initChart();    
                                    }    
                                }
                        });
                        return false;
                        break;
                    case '3':
                        enableDateInput("from");
                        enableDateInput("to");
                        $('#from').val("");
                        $('#to').val("");
                        $('.datepicker-button').removeClass('d-none');
                        modal.show();
                        break;
                }
            });            

            var finalStartDate;
            var finalEndDate;

            $('#datepicker-from *').on('click', () => {

                //$("#from").val($("#datepicker-from").datepicker('getFormattedDate'));
                finalStartDate = $("#datepicker-from").datepicker('getFormattedDate');
                let startDate = finalStartDate;
                let endDate = finalEndDate;

                $("#label-from").text($("#datepicker-from").datepicker('getFormattedDate'));

                if (startDate > endDate) {
                    //$("#to").val($("#datepicker-from").datepicker('getFormattedDate'));
                    finalEndDate = $("#datepicker-from").datepicker('getFormattedDate');
                    $("#label-to").text($("#datepicker-from").datepicker('getFormattedDate'));
                }
            });

            $('#datepicker-to *').on('click', () => {

                //$("#to").val($("#datepicker-to").datepicker('getFormattedDate'));
                finalEndDate = $("#datepicker-to").datepicker('getFormattedDate');
                let startDate = finalStartDate;
                let endDate = finalEndDate;

                $("#label-to").text($("#datepicker-to").datepicker('getFormattedDate'));

                if (startDate > endDate) {
                    //$("#from").val($("#datepicker-to").datepicker('getFormattedDate'));
                    finalStartDate = $("#datepicker-to").datepicker('getFormattedDate');
                    $("#label-from").text($("#datepicker-to").datepicker('getFormattedDate'));
                }
            });

            $("#datepicker-save").on("click", () => {
                $("#from").val(finalStartDate);
                $("#to").val(finalEndDate);
                $.ajax({
                    type: "POST",
                    url: "bilansBackend.php",
                    data: $("#dates").serialize(),
                        success: function(data) {
                            if (data == "connection-error") {
                                window.location.href = "notfound.php";
                            } else {
                                incomesObj = JSON.parse(data.split("]")[0]+']');
                                expensesObj = JSON.parse(data.split("]")[1]+']');
                                updateFinancesInAccordion(incomesObj,"#incomes-table");
                                updateFinancesInAccordion(expensesObj,"#expenses-table");
                                calculateBalance();
                                initChart();    
                            }     
                        }
                });
                modal.hide();                
                return false;
            });

            $(".datepicker-button").on('click', () => {
                finalStartDate = $("#from").val();
                $("#datepicker-from").datepicker('setDate', finalStartDate);
                finalEndDate = $("#to").val();
                $("#datepicker-to").datepicker('setDate', finalEndDate);              
            });
            
        });

        var enableDateInput = (inputId) => $(`#${inputId}`).removeClass('disabled-input');
        var disableDateinput = (inputId) => $(`#${inputId}`).addClass('disabled-input');   

        function calculateBalance() {
            incomesTotal = incomesObj.reduce((total, current) => total + parseFloat(current['amount']),0);
            expensesTotal = expensesObj.reduce((total, current) => total + parseFloat(current['amount']),0);
            balanceFinal = incomesTotal - expensesTotal;    
            balanceFinal = Math.round((balanceFinal + Number.EPSILON) * 100) / 100        
            $('.incomes-total').text((incomesTotal + "zł").replace('.',','));
            $('.expenses-total').text((expensesTotal + "zł").replace('.',','));
            $('.balance-final').text((balanceFinal + "zł").replace('.',','));
            if (balanceFinal >= 0) {
                $('.balance-message').text("Gratulacje. Świetnie zarządzasz finansami!");
                $('.balance-message').removeClass('text-invalid');
            } else {
                $('.balance-message').text("Uważaj, wpadasz w długi!");
                $('.balance-message').addClass('text-invalid');
            }
        }

        function updateFinancesInAccordion (financesObj, tableId) {            
            $(tableId).empty();
            $(tableId).removeClass();
            financesObj.forEach((row) => {
                $(tableId).append(
                    `<tr><td>${row['category']}</td><td>${row['amount']}</td></tr>`
                );
            });
            if (jQuery.isEmptyObject(financesObj)) {
                $(tableId).append(
                    ($(tableId).parent().parent().parent().prev().attr('id') == 'incomes') ?
                    `<tr><td colspan="2">Nie posiadasz przychodów w wybranym okresie</td></tr>` :
                    `<tr><td colspan="2">Nie posiadasz wydatków w wybranym okresie</td></tr>` 
                );
                $(tableId).addClass('text-invalid').addClass('text-center');
            }
        }

    </script>

</head>

<body>

    <!-- Modal -->
    <div class="modal fade" id="pick-date-period" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content w-max-content mx-auto">
                <div class="modal-header py-2">
                    <h5 class="modal-title" id="modalLabel">Wybierz datę</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-1">
                    <!-- bootstrap datepicker -->
                    <div class="d-flex text-center pb-1">
                        <div class="col-1 flex-grow-1">od:
                            <span class="ms-1 text-white" id="label-from"></span>
                        </div>
                        <div class="me-3"></div>
                        <div class="col-1 flex-grow-1">do:
                            <span class="ms-1 text-white" id="label-to"></span>
                        </div>
                    </div>

                    <div class="input-group input-daterange">
                        <div class="me-3" id="datepicker-from"></div>
                        <div id="datepicker-to"></div>
                    </div>
                </div>
                <div class="modal-footer py-2 px-3" id="datepicker-save-container">
                    <button type="button" class="btn btn-primary m-0" id="datepicker-save">zapisz zmiany</button>
                </div>
            </div>
        </div>
    </div>

    <nav>
        <!-- up to md (must be first) -->
        <div class="d-flex d-lg-none fixed-top">

            <!-- sm with burger -->
            <div class="d-md-none navbar fixed-top pb-0">
                <div class="container-fluid">

                    <span class="text-white mb-2" href="#">Budżet osobisty</span>

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
                            <a href="Przychody.php" type="button"
                                class="btn btn-nav shadow-none rounded-0 text-start">Dodaj
                                przychód</a>
                            <a href="Wydatki.php" type="button"
                                class="btn btn-nav shadow-none rounded-0 text-start">Dodaj
                                wydatek</a>
                            <a href="Bilans.php" type="button"
                                class="btn btn-nav shadow-none rounded-0 text-start active"
                                aria-pressed="true">Przeglądaj
                                bilans</a>
                            <hr class="mt-2 mb-2">
                            <div class="d-flex mb-2 align-items-center">
                                <img src="img/default-user.png" alt="" width="32" height="32" class="rounded-circle">
                                <span class="ms-2 me-auto"><?php
                                    echo $username;
                                ?></span>
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

                    <div class="btn-group justify-self-left me-auto" role="group" aria-label="Navigation buttons">
                        <a href="Przychody.php" type="button" class="btn btn-nav rounded-0 m-0 pb-3 pt-3">Dodaj
                            przychód</a>
                        <a href="Wydatki.php" type="button" class="btn btn-nav rounded-0 m-0 pb-3 pt-3">Dodaj
                            wydatek</a>
                        <a href="Bilans.php" type="button" class="btn btn-nav rounded-0 m-0 pb-3 pt-3 active"
                            aria-pressed="true">Przeglądaj bilans</a>
                    </div>

                    <div class="d-flex pe-2">
                        <div class="vr vr-40px "></div>
                    </div>

                    <div class="dropdown d-flex">
                        <a href="#" class="d-flex align-items-center dropdown-toggle flex-row-reverse" id="user-md"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="img/default-user.png" alt="" width="32" height="32" class="rounded-circle ms-2">
                            <span class="me-auto"><?php
                                echo $username;
                            ?></span>
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
                    <a href="Wydatki.php" class="nav-link mb-2 me-2">
                        Dodaj wydatek
                    </a>
                </li>
                <li>
                    <a href="Bilans.php" class="nav-link active" aria-current="page">
                        Przeglądaj bilans
                    </a>
                </li>
            </ul>
            <hr class="me-3">
            <div class="dropdown dropup">
                <a href="#" class="d-flex align-items-center dropdown-toggle pe-2 me-3" id="user-lg"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="img/default-user.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <span class="me-auto"><?php
                        echo $username;
                    ?></span>
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
        <form class="pt-2 pt-sm-3 pt-md-3 ps-lg-5 pt-lg-5 mx-auto mx-lg-0" id="dates"
        action="bilansBackend.php" method="post">
            <div class="d-flex align-items-md-center flex-column flex-md-row fs-5 p-0">
                <div class="d-flex flex-column flex-sm-row align-items-center mx-auto mx-lg-0">
                    <label for="period" class="text-nowrap m-0 me-sm-2">Okres bilansu:</label>
                    <div class="d-flex">
                        <select class="form-select classic w-max-content" aria-label="Okres bilansu" id="period">
                            <option value="0">Bieżący miesiąc</option>
                            <option value="1">Poprzedni miesiąc</option>
                            <option value="2">Bieżący rok</option>
                            <option value="3">Niestandardowy</option>
                        </select>
                        <div class="d-flex d-sm-none">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#pick-date-period"
                                class="datepicker-button d-none btn btn-primary ms-2 py-auto px-2 d-flex">
                                <i class="material-icons p-calendar">
                                    edit_calendar
                                </i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center pt-2 pb-1 pt-md-0 pb-sm-0 mx-auto mx-md-0">
                    <label for="from" class="ms-md-2 me-2">od:</label>
                    <input type="text" class="form-control disabled-input w-108px me-2" id="from" name="from" value="">
                    <label for="to" class="me-2">do:</label>
                    <input type="text" class="form-control disabled-input w-108px" id="to" name="to">
                    <div class="d-none d-sm-block">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#pick-date-period"
                            class="datepicker-button d-none btn btn-primary ms-2 py-auto px-2 d-flex">
                            <i class="material-icons p-calendar">
                                edit_calendar
                            </i>
                        </button>
                    </div>
                </div>
                <!-- hidden sumbit button for AJAX function -->
                <button type="submit" class="d-none" id="form-submit"></button>
            </div>
        </form>
        <div class="d-flex">
            <div class="accordion col-12 col-md-5 col-xl-4 p-1 pt-2 p-sm-2 pt-sm-3 p-md-3 p-lg-5 pt-lg-3 pe-lg-3"
                id="balance-accordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="incomes">
                        <button class="accordion-button shadow-none collapsed p-2" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false"
                            aria-controls="collapseOne" id="accordion-button-1">
                            Przychody:
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="incomes"
                        data-bs-parent="#balance-accordion">
                        <div class="accordion-body p-0 overflow-auto">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="sticky-top">
                                        <th scope="col">Kategoria:</th>
                                        <th scope="col">Suma:</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="incomes-table">                                    
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="expenses">
                        <button class="accordion-button shadow-none collapsed p-2" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                            aria-controls="collapseTwo" id="accordion-button-2">
                            Wydatki:
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="expenses"
                        data-bs-parent="#balance-accordion">
                        <div class="accordion-body p-0 overflow-auto">
                            <table class="table table-hover" data-sticky-header="true">
                                <thead>
                                    <tr class="sticky-top">
                                        <th scope="col">Kategoria:</th>
                                        <th scope="col">Suma:</th>
                                    </tr>
                                </thead>
                                <tbody id="expenses-table">                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-item d-block d-md-none">
                    <h2 class="accordion-header" id="summary-accordion">
                        <button class="accordion-button shadow-none collapsed p-2" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                            aria-controls="collapseThree" id="accordion-button-3">
                            Podsumowanie:
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="summary-accordion"
                        data-bs-parent="#balance-accordion">
                        <div class="accordion-body p-0 overflow-auto d-flex flex-column">
                            <div class="h-75" id="accordion-container">
                                <div id="chart-wrapper">
                                    <div id="piechart-accordion"></div>
                                </div>
                            </div>
                            <div class="d-flex my-auto">
                                <div class="col-9 d-flex flex-column my-auto py-2 mx-auto">
                                    <div class="d-flex p-0 justify-content-center">
                                        <div class="col-6 summary-border-bottom"></div>
                                        <div class="col-6 summary-border-start summary-border-bottom text-center">
                                            <span>suma</span>
                                        </div>
                                    </div>
                                    <div class="d-flex p-0 justify-content-center">
                                        <div class="col-6 summary-border-bottom">
                                            <span class="ps-3">Przychody:</span>
                                        </div>
                                        <div class="col-6 summary-border-start summary-border-bottom text-center">
                                            <span class="incomes-total"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex p-0 justify-content-center">
                                        <div class="col-6 summary-border-bottom">
                                            <span class="ps-3">Wydatki:</span>
                                        </div>
                                        <div class="col-6 summary-border-start summary-border-bottom text-center ">
                                            <span class="expenses-total"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="col-6">
                                            <span class="ps-3">Bilans:</span>
                                        </div>
                                        <div class="col-6 summary-border-start text-center">
                                            <span class="balance-final"></span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-center pt-2">
                                        <span class="text-center fst-italic">
                                            <strong class="balance-message"></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-1 d-none d-md-flex 
            p-md-3 ps-md-0 p-lg-5 pt-lg-3 ps-lg-0
            flex-grow-1 flex-column overflow-auto" id="summary">
                <div class="d-flex flex-column h-100">
                    <span class="d-block text-center fs-3">Podsumowanie:</span>
                    <div id="piechart" class="h-75"></div>
                    <div class="d-flex flex-grow-1" style="border: 1px solid #2b2d34;">
                        <div class="col-6 d-flex flex-column">
                            <div class="d-flex p-0 justify-content-center mt-auto">
                                <div class="col-md-6 col-xl-4 
                                p-0 pb-1
                                summary-border-bottom"></div>
                                <div class="col-md-5 col-xl-3 
                                p-0 pb-1
                                summary-border-start summary-border-bottom text-center">
                                    <span>suma</span>
                                </div>
                            </div>
                            <div class="d-flex p-0 justify-content-center">
                                <div class="col-md-6 col-xl-4 
                                p-0 pe-2 py-1
                                summary-border-bottom">
                                    <span class="ps-3">Przychody:</span>
                                </div>
                                <div class="col-md-5 col-xl-3
                                p-0 py-1
                                summary-border-start summary-border-bottom text-center">
                                    <span class="incomes-total"></span>
                                </div>
                            </div>
                            <div class="d-flex p-0 justify-content-center mb-auto">
                                <div class="col-md-6 col-xl-4
                                 p-0 pe-2 pt-1">
                                    <span class="ps-3">Wydatki:</span>
                                </div>
                                <div class="col-md-5 col-xl-3
                                 p-0 pt-1
                                 summary-border-start text-center ">
                                    <span class="expenses-total"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-flex flex-column
                        px-md-2 ps-md-0">
                            <div class="d-flex p-0 mt-auto justify-content-center">
                                <div class="col-6 text-end pb-1"><span>Bilans:</span></div>
                                <div class="col-6 ms-2 pb-1"><span class="balance-final"></span></div>
                            </div>
                            <div class="d-flex mb-auto justify-content-center">
                                <span class="summary-border-top text-center
                                px-2 pt-1">
                                    <strong class="balance-message"></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    $(document).ready(() => {
        $("main").css("visibility", "visible"); // to prevent flicker caused by resizing of divs on load

        $('svg').attr('preserveAspectRatio', 'xMinYMin ');

        if ($(window).width() < 768) {
            $("#accordion-button-3").removeClass("collapsed");
            $("#accordion-button-3").attr("aria-expanded", "true");
            $("#collapseThree").addClass("show");
        }
        else {
            $("#accordion-button-2").removeClass("collapsed");
            $("#accordion-button-2").attr("aria-expanded", "true");
            $("#collapseTwo").addClass("show");
        }

        resizeContentDivs();
    });

    $(window).resize(() => {
        resizeContentDivs();
        waitForFinalEvent(function () {
            resizeContentDivs();
        }, 1, "uniqueId-1");
        initChart();
        waitForFinalEvent(function () {
            initChart();
        }, 1, "uniqueId-2");
    });

    $("#accordion-button-3").on("click", () => {
        resizeContentDivs();
        initChart();
    });

    $(function () {
        var dateInputFormatingFrom = new Cleave('#from', {
            date: true,
            delimiter: '.',
            numeralDecimalMark: ',',
            datePattern: ['d', 'm', 'Y']
        });

        var dateInputFormatingTo = new Cleave('#to', {
            date: true,
            delimiter: '.',
            numeralDecimalMark: ',',
            datePattern: ['d', 'm', 'Y']
        });
    });

    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(initChart);

    function initChart() {

        var data = google.visualization.arrayToDataTable([
            [
                { label: 'Kategoria', type: 'string' },
                { label: 'Suma', type: 'number' },
                { type: 'string', role: 'tooltip' }
            ],
        ]);
        
        if (expensesObj != null) {
            expensesObj.forEach((row) => {
            data.addRows([[row['category'],parseFloat(row['amount']),'zł']]);
            });  
        }              

        data.addColumn({ type: 'string', role: 'tooltip' }); 

        customizeTooltips(data);

        var options = {
            backgroundColor: {
                fill: 'transparent',
                //strokeWidth: '5',
                //stroke: 'red'
            },
            width: '100%',
            height: '100%',
            chartArea: {
                width: '90%',
                height: '90%'
            },
            legend: 'none',
            //colors: ['green', 'yellow', 'black', 'orange', 'pink'],
            fontSize: '20',
            fontName: 'Open Sans',
            forcelFrame: 'false',
            pieSliceBorderColor: '', //no border?       
            pieSliceText: 'label',
            tooltip: {
                isHtml: 'false', //
                ignoreBounds: 'false', //If this is enabled with SVG tooltips, any overflow outside of the chart bounds will be cropped
                showColorCode: 'true',
                textStyle: {
                    color: 'black',
                    //fontName: 'string',
                    fontSize: '16',
                    bold: 'true',
                    italic: 'false'
                },
                trigger: 'focus',
            }
        };
        drawChart(data, options);
        drawChartAccordion(data, options)
    }

    function drawChart(data, options) {
        if (data.getNumberOfRows() == 0) {
            document.getElementById('piechart').classList.add('text-invalid','fs-3','d-flex','mx-auto','align-items-center','text-center');
            document.getElementById('piechart').innerHTML = "Nie posiadasz wydatków w wybranym okresie";
            
        } else {
            document.getElementById('piechart').classList.remove('text-invalid','fs-3','d-flex','mx-auto','align-items-center','text-center');
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }        
    }

    function drawChartAccordion(data, options) {
        if (data.getNumberOfRows() == 0) {
            document.getElementById('piechart-accordion').classList.add('text-invalid','text-center','fs-4','align-items-center','d-flex','justify-content-center');
            document.getElementById('piechart-accordion').innerHTML = "Nie posiadasz wydatków w wybranym okresie";
            
        } else {
            document.getElementById('piechart-accordion').classList.remove('text-invalid','fs-3','d-flex','mx-auto','align-items-center','text-center');
            var chartAccordion = new google.visualization.PieChart(document.getElementById('piechart-accordion'));
            chartAccordion.draw(data, options);
        }
    }

    var resizeContentDivs = () => {
        let windowHeight = $(window).height();
        let accordionContainerHeight = $(".accordion").outerHeight();
        let accordionButtonHeight = $(".accordion-button").outerHeight();
        let datesDivHeight = $("#dates").outerHeight();
        let accordionPaddingsHeight = $(".accordion").outerHeight() - $(".accordion").height();
        let navHeight = $("nav").height();

        if ($(window).width() < 768) {
            $(".accordion-body").css("height", windowHeight - navHeight - datesDivHeight - accordionPaddingsHeight - 3 * accordionButtonHeight - 5);

            $("#chart-wrapper").css("padding-bottom", $("#chart-wrapper").parent().height());
        }
        else if ($(window).width() >= 768 && $(window).width() < 992) {
            $(".accordion-body").css("height", windowHeight - navHeight - datesDivHeight - accordionPaddingsHeight - 2 * accordionButtonHeight - 4);
            $("#summary").css("height", windowHeight - navHeight - datesDivHeight);
        }
        else {
            $(".accordion-body").css("height", windowHeight - datesDivHeight - accordionPaddingsHeight - 2 * accordionButtonHeight - 4);
            $("#summary").css("height", windowHeight - datesDivHeight);
        }
    }

    function customizeTooltips(data) {
        let total = 0;
        data.Wf.forEach((amount) => {
            total += amount.c[1].v;
        })

        data.Wf.forEach((chartEntry) => {
            let category = chartEntry.c[0].v;
            let amount = chartEntry.c[1].v;
            let currency = chartEntry.c[2].v
            let percentage = " (" + ((amount / total) * 100).toFixed(1) + "%)";

            chartEntry.c[2].v = category + ":\n" + amount + currency + percentage;
        });
    };

    var waitForFinalEvent = (function () {
        var timers = {};
        return function (callback, ms, uniqueId) {
            if (!uniqueId) {
                uniqueId = "Don't call this twice without a uniqueId";
            }
            if (timers[uniqueId]) {
                clearTimeout(timers[uniqueId]);
            }
            timers[uniqueId] = setTimeout(callback, ms);
        };
    })();

</script>

</html>