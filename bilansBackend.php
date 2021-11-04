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
            
            $currentUserId = 1;
            $dateFrom = '2021-11-01';
            $dateTo = '2021-11-30';
            $givenCategory = 1;

            //get sum of each catogory for given user in given period
            $sumOfEachCategory = $connection->query(
                "SELECT 
                    ic.category AS category,
                    SUM(i.amount)/100 AS amount
                FROM incomes i
                INNER JOIN incomecategories ic
                USING (categoryid)
                WHERE i.userid = {$currentUserId} AND i.date > '{$dateFrom}' AND i.date < '{$dateTo}'
                GROUP BY i.categoryid 
                ORDER BY SUM(i.amount) DESC"
            );

            //get sum of specified category for given user in given period
            $sumOfGivenCategory = $connection->query(
                "SELECT 
                    ic.category AS category,
                    SUM(i.amount)/100 AS amount
                FROM incomes i 
                INNER JOIN incomecategories ic
                USING (categoryid)
                WHERE i.categoryid = {$givenCategory} AND i.userid = {$currentUserId} AND i.date > '{$dateFrom}' AND i.date < '{$dateTo}'"
            );     
            
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
            
            $insertUser = $connection->query("INSERT INTO users VALUES (NULL, 'ka', 'rol', 'ka@rol')");
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

            console($insertIncomeCategories);
            console($incomeCategoriesIdsQuery);
            console($insertUserIncomePair);
            console('-');
            console($insertExpenseCategories);
            console($expenseCategoriesIdsQuery);
            console($insertUserExpensePair);
            console('-');
            console($insertPaymentMethods);
            console($paymentMethodsIdsQuery);
            console($insertUserMethodPair);

            if (!$insertUser 
            || !$insertIncomeCategories || !$incomeCategoriesIdsQuery || !$insertUserIncomePair
            || !$insertExpenseCategories || !$expenseCategoriesIdsQuery || !$insertUserExpensePair 
            || !$insertPaymentMethods || !$paymentMethodsIdsQuery || !$insertUserMethodPair) {
                throw new Exception($connection->error);
            }

            $connection->commit();
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