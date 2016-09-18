<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['user'])) {
    include_once "user.php";
    $user = new User();
    $user = unserialize($_SESSION['user']);
    echo 
        "Welcome ".$user->getUsername()
        . " | <a href='../php/logout.php'>Logout</a>"
        . " | <a href='../html/viewownedgames.html'>View Account</a>";
}
else {
    echo '<form action="../php/login.php" method="post">'
    . '<input name="email" id="email" type="text" placeholder="email">'
    . '<input name="pass" id="pass" type="password" placeholder="password"> '
    . '<input type="submit" value="Login">'
    . ' <a href="../html/register.html">Register</a></form>';
} 
?>
