<?php
    session_start();

    if (!isset($_POST['type'])) {
        header('Location: Przychody.php');
        exit();
    }    

    $isValid = true;

    $userid = $_SESSION['user']['userid'];      
    $type = $_POST['type'];

    if (isset($_POST['cancel-button'])) {
        ($type == "income") ? header('Location: Przychody.php') : header('Location: Wydatki.php');     
        exit();
    }
    
    if ($_POST['amount'] <= 0 || $_POST['amount'] == "") {
        $_SESSION['amount'] = $_POST['amount'];
        $_SESSION['e_amount'] = "Proszę podać wartość dodatnią";
        $isValid = false;   
    }
    $amount = str_replace(',','.',$_POST['amount']);
    $amount = str_replace(' ','',$amount);
    if ($_POST['date'] == "") {
        $_SESSION['date'] = $_POST['date'];
        $_SESSION['e_date'] = "Proszę podać datę";
        $isValid = false;  
    }    
    $date = date("Y-m-d", strtotime($_POST['date']));
    $method = (isset($_POST['method'])) ? $_POST['method'] : '';
    if (isset($_POST['income-category'])) {
        $categoryName = $_POST['income-category'];
        $categoryTable = 'incomecategories';
    }
    if (isset($_POST['expense-category'])) {
        $categoryName = $_POST['expense-category'];        
        $categoryTable = 'expensecategories';
    }
    $comment = $_POST['comment'];   

    switch ($type) {
        case "income":
            $table = "incomes";
            break;
        case "expense":
            $table = "expenses";
            break;
    }

    if (!$isValid) {        
        ($type == "income") ? header('Location: Przychody.php') : header('Location: Wydatki.php');     
        exit();
    }

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) {
            throw new Exception($connection->error);
        }
        else {            
            if ($method == '') {
                $methodId = '';
            } else {
                if ($methodQuery = $connection->query(
                    "SELECT methodid FROM paymentmethods WHERE method='$method'"
                )) {
                    $methodIdInt = $methodQuery->fetch_assoc()['methodid'];
                    $methodId = "'{$methodIdInt}',";
                } else {
                    throw new Exception($connection->error);
                }
            }          

            if ($categoryQuery = $connection->query(
                "SELECT categoryid FROM $categoryTable WHERE category='$categoryName'"
            )) {
                $categoryId = $categoryQuery->fetch_assoc()['categoryid'];
            } else {
                throw new Exception($connection->error);
            }                     

            if ($connection->query("INSERT INTO $table VALUES (NULL, '$userid', '$amount', '$date', $methodId '$categoryId','$comment')")) {
                $_SESSION['success'] = true;
                ($type == "income") ? header('Location: Przychody.php') : header('Location: Wydatki.php');
                exit();
            } else {
                throw new Exception($connection->error);
            }
            $connection->close();
        }
	} 
    catch (Exception $e) {
        $_SESSION['notfound'] = "Operacja nie powiodła się";
        header('Location: notfound.php');
	}	

    //-----------------debug-function-----------------------
    function console($data, $context = 'Debug in Console') {

        // Buffering to solve problems frameworks, like header() in this and not a solid return.
        ob_start();

        $output  = 'console.info(\'' . $context . ':\');';
        $output .= 'console.log(' . json_encode($data) . ');';
        $output  = sprintf('<script>%s</script>', $output);

        echo $output;
    }
    //------------------------------------------------------
?>