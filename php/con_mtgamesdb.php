<?php
$serverx = "localhost";
$userx = "mtgames";
$passx = "bugrothmk3";
$databasex = "mtgamesdb";

if($mysqlioo = new mysqli($serverx, $userx, $passx, $databasex)) {
}
else {
    echo "Could not connect to site database";
}

?>
