<?php
if(!isset($_GET['contain'])) {
    echo "<div style='text-align:center;'>Please enter search terms above</div>";
}
else {
    include_once "product.php";
    include "con_mtgamesdb.php";
    $contain = $_GET['contain'];
    $includes = 0;
    
    if(isset($_GET['name'])) {
        $includes++;
    }
    if(isset($_GET['description'])) {
        $includes++;
    }
    if(isset($_GET['genre'])) {
        $includes++;
    }
    if(isset($_GET['developer'])) {
        $includes++;
    }
    if(isset($_GET['publisher'])) {
        $includes++;
    }
        
    $includesCount = 0;

    $query = "SELECT *, (price - (price * currentDiscount)) AS 'currentPrice', (";
    if(isset($_GET['name'])) {
        $query .= "((name LIKE '%$contain%') * 3)";
        $includesCount++;
        if($includesCount < $includes) {
            $query.= " + ";
        }
    }
    if(isset($_GET['description'])) {
        $query .= "(description LIKE '%$contain%')";
        $includesCount++;
        if($includesCount < $includes) {
            $query.= " + ";
        }
    }
    if(isset($_GET['genre'])) {
        $query .= "((genre LIKE '%$contain%') * 3)";
        $includesCount++;
        if($includesCount < $includes) {
            $query.= " + ";
        }
    }
    if(isset($_GET['developer'])) {
        $query .= "((developer LIKE '%$contain%') * 3)";
        $includesCount++;
        if($includesCount < $includes) {
            $query.= " + ";
        }
    }
    if(isset($_GET['publisher'])) {
        $query .= "((publisher LIKE '%$contain%') * 3)";
        $includesCount++;
        if($includesCount < $includes) {
            $query.= " + ";
        }
    }
    
    $query.= ")AS 'relevance' "
            . "FROM product "
            . "HAVING relevance > 0 ";
    $sort = $_GET['sort'];
    if($sort == 'relevance') {
        $query.= "ORDER BY relevance DESC";
    }
    if($sort == 'priceasc') {
        $query.= "ORDER BY currentPrice Asc";
    }
    if($sort == 'pricedesc') {
        $query.= "ORDER BY currentPrice DESC";
    }
    if($sort == 'releaseasc') {
        $query.= "ORDER BY originalRelease ASC";
    }
    if($sort == 'releasedesc') {
        $query.= "ORDER BY originalRelease DESC";
    }
    if($sort == 'discountdesc') {
        $query.= "ORDER BY currentDiscount DESC";
    }
    if($sort == 'listedasc') {
        $query.= "ORDER BY dateListed ASC";
    }
    if($sort == 'listeddesc') {
        $query.= "ORDER BY dateListed DESC";
    }

    $result = $mysqlioo->query($query) or die ("Invalid Search");
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
}
?>