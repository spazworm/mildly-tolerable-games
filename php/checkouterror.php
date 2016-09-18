<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['checkouterror'])) {
    echo "<div id='redcenteredmessage'>"
    . $_SESSION['checkouterror']
    . "</div>";
    unset($_SESSION['checkouterror']);
}
?>