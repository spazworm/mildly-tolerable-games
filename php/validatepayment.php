<?php
include "user.php";

$cardType = $_POST['cardtype'];
$cardNumber = $_POST['cardnumber'];
$cardName = $_POST['cardname'];
$expMonth = $_POST['expmonth'];
$expYear = $_POST['expyear'];
$payType = $_POST['paytype'];

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['user'])) {
    $_SESSION['paymenterror'] = "You must log in to continue";
    header( "Location: {$_SERVER['HTTP_REFERER']}");
}
else {
    if($payType == "paypal") {
        $_SESSION['paytype'] = $payType;
        if(isset($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }
        header( "Location: ../html/confirmorder.html"); 
    }
    else {
        //check if payment type is selected
        if($cardType == "select") {
            $_SESSION['paymenterror'] = "You must select a payment type";
            header("Location: ../html/payment.html");
        }
        else {
            //check card number length
            if(strlen($cardNumber) != 16) {
                $_SESSION['paymenterror'] = "Your card number should be 16 Numbers Long";
                header("Location: ../html/payment.html");
            }
            else {
                //check card number for numeric
                if (!is_numeric($cardNumber)) {
                    $_SESSION['paymenterror'] = "Your card number should contain only numbers";
                    header("Location: ../html/payment.html");
                }
                else {
                    //if month is less than this month and equal to this year
                    if($expMonth < date(("m")) && $expYear == date(("y")) ||
                            //if year is less than this year
                            $expYear < date(("y"))) {
                        $_SESSION['paymenterror'] = "Your card is expired";
                        header("Location: ../html/payment.html");
                    }
                    else  {
                        if($expMonth == "month" || $expYear == "year") {
                            $_SESSION['paymenterror'] = "Please enter a expiration date";
                            header("Location: ../html/payment.html");
                        }
                            else {
                                $_SESSION['cardtype'] = $cardType;
                                $_SESSION['cardnumber'] = $cardNumber;
                                $_SESSION['cardname'] = $cardName;
                                $_SESSION['expmonth'] = $expMonth;
                                $_SESSION['expyear'] = $expYear;
                                $_SESSION['paytype'] = $payType;

                                if(isset($_SESSION['cart'])) {
                                    unset($_SESSION['cart']);
                                }
                                header( "Location: ../html/confirmorder.html");                   
                        }                  
                    }
                }
            }
        }
    }
}
?>
