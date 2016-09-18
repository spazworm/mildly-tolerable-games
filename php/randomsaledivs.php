<?php
include_once "product.php";
echo "<div id='salebartitle'><h3>HOT SALES!</h3></div>";

include "con_mtgamesdb.php";
$query = "SELECT * "
        . "FROM product "
        . "WHERE currentDiscount > 0 "
        . "ORDER BY RAND() "
        . "LIMIT 3";

$result = $mysqlioo->query($query) or die ("Could Not Generate Sales");

while($row = $result->fetch_array(MYSQL_ASSOC)) {
    $product = new Product();
    $product->rowToProduct($row);
    echo $product->getSaleDiv();
}
?>
