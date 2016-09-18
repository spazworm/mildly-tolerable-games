<?php
    include_once "product.php";
    include "con_mtgamesdb.php";

    $query = "SELECT * FROM product ORDER BY originalRelease DESC LIMIT 10";

    $result = $mysqlioo->query($query) or die ("Cannot display items");
    if($result->num_rows == 0) {
        echo "<div style='text-align:center;'>No results found</div>";
    }
    else {
        while($row = $result->fetch_array(MYSQL_ASSOC)) {
            $product = new Product();
            $product->rowToProduct($row);
            echo $product->getLargeListItem();

        }
    }

?>