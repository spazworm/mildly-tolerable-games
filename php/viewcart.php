<?php
include_once"user.php";
include_once"product.php";
include_once"productcontainer.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cart = new ProductContainer();

//check if logged in
if(!isset($_SESSION['user'])) {
    echo "<div style='text-align:center;'>You must log in to view cart</div>";
}
else {
    //load user
    $user = new User();
    $user = unserialize ($_SESSION['user']);
    //check for existing cart
    if(isset($_SESSION['cart'])) {
        $cart = unserialize($_SESSION['cart']);
    }
    else {
        //check database for cart
        include "con_mtgamesdb.php";
        $query = 
            "SELECT * "
            ."FROM product "
            ."JOIN shoppingcartproducts "
            ."ON product.productID = shoppingcartproducts.productID "
            ."WHERE shoppingcartproducts.userID = ".$user->getUserID();
        
        $result = $mysqlioo->query($query) or die ("Error retreiving cart");
        if($result->num_rows != 0) {
            //load cart
            $cart->resultToContainer($result);
            $_SESSION['cart'] = serialize($cart);
            
        }
    }
    if($cart->get_depth() != 0) {
        echo $cart->getShoppingCartContainerProducts()
            ."<div id='searchbar'>"
            ."<h6>"
            ."Savings: $".number_format($cart->getSavings(),2)
            ."&nbsp;&nbsp;&nbsp;&nbsp;"
            ."Total: $".number_format($cart->getTotalPrice(),2)
            ."</h6>"
            ."</div>"
            . "<form style='text-align:right;' action='../html/payment.html'>"
            . "<input type='submit' value='Checkout'></form>";
    }
    else {
        echo "<div style='text-align:center;'>Your cart is empty</div>";
    }
}
        
    

?>
