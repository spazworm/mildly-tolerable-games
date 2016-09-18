<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['purchasemessage'])) {
    echo "<div id='greencenteredmessage'>"
    . $_SESSION['purchasemessage']
    . "</div>";
    unset($_SESSION['purchasemessage']);
}
?>