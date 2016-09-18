<?php
include_once "user.php";
include_once"product.php";
include_once"productcontainer.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user'])) {
    echo "You must register and log in to add products to cart";
}
else {
    $user = new User();
    $user = unserialize($_SESSION['user']);
    $productID = $_POST['productID'];
    include "con_mtgamesdb.php";
    $query = "DELETE FROM shoppingcartproducts "
            . "WHERE userID = ".$user->getUserID()." "
            . "AND productID = ".$productID;
    
    $mysqlioo->query($query) or die ("Cannot remove item from cart");
    unset($_SESSION['cart']);
    header( "Location: ../html/cart.html");
}
?>
