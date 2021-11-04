<?php
    session_start();

    if (isset($_SESSION['username']) || isset($_SESSION['password'])) {
        $_POST['login'] = $_SESSION['username'];
        $_POST['password'] = $_SESSION['password'];
        unset($_SESSION['username']);
        unset($_SESSION['password']);
    }

    if( (!isset($_POST['login'])) || (!isset($_POST['password'])) ) {
        header('Location: Logowanie.php');
        exit();
    }    

    require_once "connect.php";
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno != 0) {
        $_SESSION['notfound'] = "Logowanie nie powiodło się";
        header('Location: notfound.php');
    }
    else {     
        $login = $_POST['login'];
        $password = $_POST['password'];

        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        $password = htmlentities($password, ENT_QUOTES, "UTF-8");

        if ($credentialsQuery = @$connection->query(
            sprintf("SELECT * FROM users WHERE name='%s'",
            mysqli_real_escape_string($connection,$login)))) {
            $returnedUsersCount = $credentialsQuery->num_rows;
            if ($returnedUsersCount > 0) {                
                $_SESSION['user'] = $credentialsQuery->fetch_assoc();
                if(password_verify($password,$_SESSION['user']['password'])) {
                    $_SESSION['isLoggedIn'] = true;            
                    $credentialsQuery->close();
                    unset($_SESSION['loginErrorMsg']);             
                    header('Location: Przychody.php');
                }
                else {
                    $_SESSION['loginErrorMsg'] = "Nieprawidłowy login lub hasło";
                    header('Location: Logowanie.php');
                }          
            }
            else {
                $_SESSION['loginErrorMsg'] = "Nieprawidłowy login lub hasło";
                header('Location: Logowanie.php');
            }
        }
        $connection->close();
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
