<?php
$firstName = $_POST['fname'];
$lastName = $_POST['lname'];
$unitNo = $_POST['unitno'];
$streetNo = $_POST['streetno'];
$streetName = $_POST['streetname'];
$cityTownName = $_POST['citytownname'];
$state = $_POST['state'];
$country = $_POST['country'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmpassword'];
$email = $_POST['email'];
$confirmEmail = $_POST['confirmemail'];
$postcode = $_POST['postcode'];
$username = $_POST['username'];

if($email != $confirmEmail) {
    echo "email address fields do not match";
}
elseif ($password != $confirmPassword){
    echo "password fields do not match";
}
elseif($firstName == "" ||
        $lastName == "" ||
        $streetNo == "" ||
        $streetName == "" ||
        $cityTownName == "" ||
        $state == "" ||
        $country == "" ||
        $password == "" ||
        $confirmPassword == "" ||
        $email == "" ||
        $confirmEmail == "" ||
        $postcode == "" ||
        $username == "") {
    echo "please ensure all fields are completed";
}
else {
    include "con_mtgamesdb.php";
    
    $query = "SELECT * ".
        "FROM user ". 
        "WHERE email = '$email'";
    
    $result = $mysqlioo->query($query) or die("could not query database");
    
    if($result->num_rows != 0) {
        echo "email address already used";
    }
    else{
        if($unitNo == "") {
            $unitNo = "null";
        }
        
        $query = "INSERT INTO user (email, username, firstName, lastName, 
            unitNo, streetNo, streetName, cityTownName, postcode, state, 
            country, developerStatus, administratorStatus, password)
            VALUES ('$email', '$username', '$firstName', '$lastName', 
            $unitNo, $streetNo, '$streetName', '$cityTownName', 
            '$postcode', '$state', '$country', false, false, '$password')";

        if($mysqlioo->query($query)) {
            echo "new user added succesfully";
        }
        else {
            echo "could not register new user"/*.$mysqlioo->error*/;
        }
    }   
}






?>
