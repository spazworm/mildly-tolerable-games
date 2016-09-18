<?php
$contain = $_GET['contain'];

include_once "productcontainer.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['searchResults'])) {
    echo "<div style='text-align:center'>Enter search terms to display results</div>";
}
else {
    $searchResults = new ProductContainer();
    $searchResults = unserialize($_SESSION['searchResults']);
    
    if($searchResults->get_depth() == 0) {
        echo "<div style='text-align:center'>No products found</div>";
    }
    else {
        for($i = 0 ; $i < $searchResults->get_depth() ; $i++) {
            $product = $searchResults->get_product($i);
            echo $product->getLargeListItem();
        }
        unset($_SESSION['searchResults']);
    }
}
?>