<?php
    session_start();

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if ($connection->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        }
        else {            		
            $currentUserId = $_SESSION['user']['userid'];
            
            $dateFrom = '2021-12-01';
            $dateTo = '2021-12-31';
            $givenCategory = 1;

            //get sum of each catogory for given user in given period
            $sumOfEachCategory = $connection->query(
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

            //get sum of specified category for given user in given period
            $sumOfGivenCategory = $connection->query(
                "SELECT 
                    ic.category AS category,
                    SUM(i.amount) AS amount
                FROM incomes i 
                INNER JOIN incomecategories ic
                USING (categoryid)
                WHERE i.categoryid = {$givenCategory} AND i.userid = {$currentUserId} AND i.date >= '{$dateFrom}' AND i.date <= '{$dateTo}'"
            );

            while ($option = $sumOfEachCategory->fetch_assoc()) {
                echo $option.'</br>';
                console($option);
            }

            echo "sad";
            
            $connection->close();
        }
	} 
    catch (Exception $e) {
        echo "shieeeeeet";
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