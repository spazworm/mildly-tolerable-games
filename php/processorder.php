<?php
include "user.php";
include "productcontainer.php";
include "generateorderid.php";

$payType = $_SESSION['paytype'];
if($payType != "paypal") {
    $cardType = $_SESSION['cardtype'];
    $cardNumber = $_SESSION['cardnumber'];
    $cardName = $_SESSION['cardname'];
    $expMonth = $_SESSION['expmonth'];
    $expYear = $_SESSION['expyear'];
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = new User();
if(!isset($_SESSION['user'])) {
    echo "You must log in to make a purchase";
}
else {
    include "con_mtgamesdb.php";
    $user = unserialize ($_SESSION['user']);
    $cart = new ProductContainer();
    if(!isset($_SESSION['cart'])) {
        $_SESSION['checkouterror'] = "You have no products in your cart";
        header("Location: ../html/cart.html");
    }
    else {
        $cart = unserialize($_SESSION['cart']);
    }
    if($payType == "paypal") {
        processPayPalPayment($user, $cart);
    }
    else {
        processCardPayment($user, $cart);
    }
}


function enterIntoDatabase($user, $cart) {
    include "con_mtgamesdb.php";
        
        //insert into OwnsACopyOf
        $query = "INSERT INTO OwnsACopyOf "
                . "(userID, productID) VALUES ";
        for($i = 0 ; $i < $cart->get_depth() ; $i++) {
        $productID = $cart->get_product($i)->getProductID();
            $query.=" (".$user->getUserID().", $productID)";
            if($i < ($cart->get_depth() - 1)) {
                $query.=",";
            }
        }
        
        $mysqlioo->query($query) or die ("Could not add products to ownership");
        
        //insert into order
        $orderID = generateOrderID();
        $userID = $user->getUserID();
        $query= "INSERT INTO orders (orderID, userID, date) "
                . "VALUES ($orderID, $userID, CURRENT_TIMESTAMP)";
        $mysqlioo->query($query) or die("Could not add order".$mysqlioo->error);
        
        //insert into orderProducts
        $query = "INSERT INTO orderProducts "
                . "(orderID, productID, discountAtSale, noDiscountPriceAtSale)"
                . "VALUES";
        for($i = 0 ; $i < $cart->get_depth() ; $i++) {
            $productID = $cart->get_product($i)->getProductID();
            $discountAtSale = $cart->get_product($i)->getCurrentDiscount();
            $noDiscountPriceAtSale = $cart->get_product($i)->getPrice();
            $query.= " ($orderID, $productID, $discountAtSale, $noDiscountPriceAtSale)";
            if($i < ($cart->get_depth() - 1)) {
                $query.=",";
            }
        }
        $mysqlioo->query($query) or die ("Could not add order products");
        
        //clear shopping cart
        unset($_SESSION['cart']);
        $query = "DELETE FROM shoppingCartProducts "
                . "WHERE userID = $userID";
        $mysqlioo->query($query) or die("Could not clear shopping cart");
        
        header("Location: ../html/viewownedgames.html");
        $_SESSION['purchasemessage'] = "Purchase complete, please download your new products from the links below.";
}

function processPayPalPayment($user, $cart) {
    $amountPayable = $cart->getTotalPrice();
    
    // Setup class
    require_once('paypal.class.php');  // include the class file
    $p = new paypal_class;             // initiate an instance of the class
    $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
    //$p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url

    // setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
    $this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

    // if there is not action variable, set the default action of 'process'
    if (empty($_GET['action'])) $_GET['action'] = 'process';  

    switch ($_GET['action']) {

        case 'process':      // Process and order...

        // For example, after ensureing all the POST variables from your custom
        // order form are valid, you might have:
        //
        // $p->add_field('first_name', $_POST['first_name']);
        // $p->add_field('last_name', $_POST['last_name']);

        $p->add_field('business', 'spazworm_businesstestaccount@gmail.com');
        $p->add_field('return', $this_script.'?action=success');
        $p->add_field('cancel_return', $this_script.'?action=cancel');
        $p->add_field('notify_url', $this_script.'?action=ipn');
        $p->add_field('item_name', 'MTGames Purchase');
        $p->add_field('amount', $amountPayable);

        $p->submit_paypal_post(); // submit the fields to paypal
        //$p->dump_fields();      // for debugging, output a table of all the fields
        break;

        case 'success':      // Order was successful...
            enterIntoDatabase($user, $cart);

        // This is where you would have the code to
        // email an admin, update the database with payment status, activate a
        // membership, etc.  

        //echo "<html><head><title>Success</title></head><body><h3>Thank you for your order.</h3>";
        //foreach ($_POST as $key => $value) { echo "$key: $value<br>"; }
        //echo "</body></html>";

        // You could also simply re-direct them to another page, or your own 
        // order status page which presents the user with the status of their
        // order based on a database

        break;

        case 'cancel':       // Order was canceled...
            $_SESSION['checkouterror'] = "Order cancelled";
            header("Location: ../html/cart.html");

        // The order was canceled before being completed.

        echo "<html><head><title>Canceled</title></head><body><h3>The order was canceled.</h3>";
        echo "</body></html>";

        break;

        case 'ipn':          // Paypal is calling page for IPN validation...

        // It's important to remember that paypal calling this script.  There
        // is no output here.  This is where you validate the IPN data and if it's
        // valid, update your database to signify that the user has payed.  If
        // you try and use an echo or printf function here it's not going to do you
        // a bit of good.  This is on the "backend".  That is why, by default, the
        // class logs all IPN data to a text file.

        if ($p->validate_ipn()) {

            // Payment has been recieved and IPN is verified.  This is where you
            // update your database to activate or process the order, or setup
            // the database with the user's order details, email an administrator,
            // etc.  You can access a slew of information via the ipn_data() array.

            // For this example, we'll just email ourselves ALL the data.
            $subject = 'Instant Payment Notification - Recieved Payment';
            $to = 'spazworm@gmail.com';    //  your email
            $body =  "An instant payment notification was successfully recieved\n"
                    . "You got some more money, what a cool guy you are\n";
            $body .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
            $body .= " at ".date('g:i A')."\n\nDetails:\n";
            foreach ($p->ipn_data as $key => $value) { $body .= "\n$key: $value"; }
            mail($to, $subject, $body);
        }
        break;
    }     
}

function processCardPayment($user, $cart) {
    enterIntoDatabase($user, $cart);
}
?>