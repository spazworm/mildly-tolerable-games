<?php
include_once "product.php";
include_once "user.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//check if logged in
if(!isset($_SESSION['user'])) {
    echo "please login to register a product";
}
else {
    //check for errors with files
    if($_FILES["iconImage"]["error"] > 0 ||
        $_FILES["productFile"]["error"] > 0 ||
        $_FILES["bannerImage"]["error"] > 0) {
    "iconImage error: ".$_FILES["iconImage"]["error"]
    ."<br>productFile error: ".$_FILES["productFile"]["error"]
    ."<br>bannerImage error: ".$_FILES["bannerImage"]["error"];
    }
    else {
        if(!isset($_SESSION['productEdit'])) {
            echo "No product edit found";
        }
        else {
            $user = new User();
            $user = unserialize($_SESSION['user']);
            if(!$user->getDeveloperStatus() == true) {
                echo "Error, only developers may upload products";
            }
            else {
                $product = new Product();
                $product = unserialize($_SESSION['productEdit']);
                
                $extension1 = pathinfo($_FILES["iconImage"]["name"], PATHINFO_EXTENSION);
                $extension2 = pathinfo($_FILES["productFile"]["name"], PATHINFO_EXTENSION);
                $extension3 = pathinfo($_FILES["bannerImage"]["name"], PATHINFO_EXTENSION);

                if(!file_exists("../images/products/".$product->getProductID())) {
                    mkdir("../images/products/".$product->getProductID(), 0777, true);
                }
                move_uploaded_file($_FILES["iconImage"]["tmp_name"], 
                        "../images/products/".$product->getProductID()."/iconImage.".$extension1);
                move_uploaded_file($_FILES["productFile"]["tmp_name"], 
                        "../images/products/".$product->getProductID()."/productFile.".$extension2);
                move_uploaded_file($_FILES["bannerImage"]["tmp_name"], 
                        "../images/products/".$product->getProductID()."/bannerImage.".$extension3);

                include "con_mtgamesdb.php";

                $query = 'UPDATE product '
                    .'SET iconImage = "../images/products/'.$product->getProductID().'/iconImage.'.$extension1.'", '
                    .'productFile = "../images/products/'.$product->getProductID().'/productFile.'.$extension2.'", '
                    .'bannerImage = "../images/products/'.$product->getProductID().'/bannerImage.'.$extension3.'" '    
                    .'WHERE productID = "'.$product->getProductID().'" '
                    .'AND developerUserID = "'.$product->getDeveloperUserID().'" '
                    .'AND name = "'.$product->getName().'" ';
                
                echo $query;
                if($mysqlioo->query($query)) {
                    echo "files added successfully";
                }
                else {
                    echo "could not add new images";
                }
            } 
        }
    }
}




?>
