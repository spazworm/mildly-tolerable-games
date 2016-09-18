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
    echo "";
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
    echo '<a href="../html/cart.html"><img alt="cart" style="position:relative; top: 3px;" width="21px" '
            . 'height="17px" src="../images/shoppingcarticon.png"></a>'
            .$cart->get_depth().' items in cart';
}
?>
