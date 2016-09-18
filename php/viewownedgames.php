<?php
include_once"user.php";
include_once"product.php";
include_once"productcontainer.php";
include "con_mtgamesdb.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$ownedGames = new ProductContainer();

//check if logged in
if(!isset($_SESSION['user'])) {
    echo "<div style='text-align:center;'>You must log in to view owned games</div>";
}
else {
    //load user
    $user = new User();
    $user = unserialize ($_SESSION['user']);
        
    $query = 
        "SELECT * "
        ."FROM Product "
        ."JOIN OwnsACopyOf "
        ."ON Product.productID = OwnsACopyOf.productID "
        ."WHERE OwnsACopyOf.userID = ".$user->getUserID();

    $result = $mysqlioo->query($query) or die ("Error retreiving cart");

    //load owned games
    $ownedGames->resultToContainer($result);


    if($ownedGames->get_depth() != 0) {
        echo $ownedGames->getOwnedGameContainerProducts();
    }
    else {
        echo "<div style='text-align:center;'>You don't own any games :'(</div>";
    }
}
        
    

?>
