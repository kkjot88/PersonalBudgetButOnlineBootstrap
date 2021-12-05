<?php
    session_start();

    $currentUserId = $_SESSION['user']['userid'];    

    $dateFrom = date("Y-m-d", strtotime($_POST['from']));
    $dateTo = date("Y-m-d", strtotime($_POST['to']));

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
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
                    throw new Exception(mysqli_connect_errno());
            }            
            
            $sumOfEachIncomeCategory = $sumOfEachIncomeCategoryQuery->fetch_all(MYSQLI_ASSOC);
            $sumOfEachExpenseCategory = $sumOfEachExpenseCategoryQuery->fetch_all(MYSQLI_ASSOC);

            echo json_encode($sumOfEachIncomeCategory);
            echo json_encode($sumOfEachExpenseCategory);

            $connection->close();
        }
	} 
    catch (Exception $e) {
        echo "shieeeeeet";
        exit();
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