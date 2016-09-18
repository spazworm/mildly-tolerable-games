<?php
if(!isset($_GET['product'])) {
    echo "<div style='text-align:center;'>No Product Found</div>";
}
else {
    include_once "product.php";
    $productID = $_GET['product'];
    $product = new Product();
    $product->getProductByID($productID);
    echo $product->getFullPageItem();
}
?>