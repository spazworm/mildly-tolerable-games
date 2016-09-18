<?php
include "user.php";
include "product.php";

$name = $_POST['name'];
$genre = $_POST['genre'];
$developer = $_POST['developer'];
$publisher = $_POST['publisher'];
$description = $_POST['description'];
$price = $_POST['price'];
$currentDiscount = $_POST['currentDiscount'];
$originalRelease = $_POST['originalRelease'];

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//check if logged in
if(!isset($_SESSION['user'])) {
    echo "please login to register a product";
}
else {    
    $user = new User();
    $user = unserialize($_SESSION['user']);
    
    if(!$user->getDeveloperStatus() == true) {
        echo "Error, only developers may upload products";
    }
    else {
        if($name == "" ||
                $genre == "" ||
                $developer == "" ||
                $publisher == "" ||
                $description == "" ||
                $price == "" ||
                $currentDiscount == "" ||
                $originalRelease == "") {
            echo "please ensure all fields are completed";
        }
        else {
            include "con_mtgamesdb.php";

            $query = "INSERT INTO product (developerUserID, name, description, iconImage, bannerImage,  
                productFile, price, currentDiscount, dateListed, genre, developer, 
                publisher, originalRelease)
                VALUES (".$user->getUserID().", '$name', '$description', '../images/noIconImage.jpg', 'Not Provided', 
                'Not Provided', $price, $currentDiscount, CURRENT_TIMESTAMP, 
                '$genre', '$developer', '$publisher', '$originalRelease')";
            
            echo $query;

            if($mysqlioo->query($query)) {
                $query = "SELECT * "
                        . "FROM product "
                        . "WHERE developerUserID = '".$user->getUserID()."' "
                        . "AND name = '$name' "
                        . "AND originalRelease = '$originalRelease' ";

                $result = $mysqlioo->query($query);
                $row = $result->fetch_array(MYSQL_ASSOC);

                $product = new Product($row['productID'], $row['developerUserID'], 
                    $row['name'], $row['description'], $row['iconImage'], 
                    $row['productFile'], $row['price'], $row['currentDiscount'], 
                    $row['dateListed'], $row['genre'], $row['developer'], 
                    $row['publisher'], $row['originalRelease']);

                $_SESSION['productEdit'] = serialize($product);

                header( 'Location: ../html/registerproductfiles.html');
            }
            else {
                echo "could not add new product<br>".$mysqlioo->error;
            } 
        }
    }
}








?>
