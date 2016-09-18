<?php
//include files
include_once"user.php";
include_once"product.php";
include_once"productcontainer.php";

//start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//check if logged in
if(!isset($_SESSION['user'])) {
    echo "You must register and log in to add products to cart";
}
else {
    //load user
    $user = unserialize($_SESSION['user']);
    
    
    $productID = $_POST['productID'];
    $cart = new ProductContainer();
    
    //check for cart
    if(isset($_SESSION['cart'])) {
        //load cart
        $cart = unserialize($_SESSION['cart']);
    }
    //load 
    $product = new Product();
    $product->getProductByID($productID);
    $cart->add_product($product);
    include "con_mtgamesdb.php";
    $query = "INSERT INTO shoppingcartproducts (userID, productID) "
            . "VALUES (".$user->getUserID().", ".$product->getProductID().")";
    
    $mysqlioo->query($query) or die ("Cannot add product to shopping cart");
    $_SESSION['cart'] = serialize($cart);
    header( "Location: ../html/cart.html");
}



?>
