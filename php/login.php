<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "user.php";

$email = $_POST['email'];
$password = $_POST['pass'];

if ($email == "") {
    $_SESSION['loginerror'] = "Enter email address";
    header( "Location: {$_SERVER['HTTP_REFERER']}");
}
else {
    if ($password == "") {
        $_SESSION['loginerror'] = "Enter Password";
        header( "Location: {$_SERVER['HTTP_REFERER']}");
    }
    else {
        include "con_mtgamesdb.php";

        $query = "SELECT * "
                . "FROM user "
                . "WHERE email = '$email' "
                . "AND password = '$password'";


        if(!$result = $mysqlioo->query($query)) { 
                $_SESSION['loginerror'] = "Invalid email or password1";
                header( "Location: {$_SERVER['HTTP_REFERER']}");
        }
        else {
            if($result->num_rows != 1) {
                if($result->num_rows == 0) {
                    $_SESSION['loginerror'] = "Invalid email or password2";
                    header( "Location: {$_SERVER['HTTP_REFERER']}");
                }
                else {
                    $_SESSION['loginerror'] = "Found multiple users";
                    header( "Location: {$_SERVER['HTTP_REFERER']}");
                }

            }
            else {
                $row = $result->fetch_array(MYSQL_ASSOC);
                $user = new User($row['userID'], $row['firstName'], 
                        $row['lastName'], $row['unitNo'], $row['streetNo'], 
                        $row['streetName'], $row['cityTownName'], $row['state'], 
                        $row['country'], $row['developerStatus'], $row['administratorStatus'], 
                        $row['password'], $row['email'], $row['postcode'],
                        $row['username']);

            $_SESSION['user'] = serialize($user);

            header( "Location: {$_SERVER['HTTP_REFERER']}");
            }
        }
    }
}
?>
