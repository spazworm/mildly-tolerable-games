<?php
include "con_mtgamesdb.php";
$query = "SELECT * "
        . "FROM slogan "
        . "ORDER BY RAND() "
        . "LIMIT 1";

$result = $mysqlioo->query($query) or die ("could not load slogan...");

while($row = $result->fetch_array(MYSQL_ASSOC)) {
    $slogan = $row['slogan'];
    
    echo $slogan;
}
?>
