<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['loginerror'])) {
    echo "<div id='loginerror'>Login Error: "
    . $_SESSION['loginerror']
    . "</div>";
    unset($_SESSION['loginerror']);
}
?>
