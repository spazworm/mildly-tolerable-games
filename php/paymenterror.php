<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['paymenterror'])) {
    echo "<div id='redcenteredmessage'>"
    . $_SESSION['paymenterror']
    . "</div>";
    unset($_SESSION['paymenterror']);
}
?>