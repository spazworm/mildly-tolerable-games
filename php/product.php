<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Product {
    private $productID;
    private $developerUserID;
    private $name;
    private $description;
    private $iconImage;
    private $bannerImage;
    private $productFile;
    private $price;
    private $currentDiscount;
    private $dateListed;
    private $genre;
    private $developer;
    private $publisher;
    private $originalRelease;
    
    function __construct($productID = 0,
            $developerUserID = 0,
            $name = "Not Provided",
            $description = "Not Provided",
            $iconImage = "Not Provided",
            $bannerImage = "Not Provided",
            $productFile = "Not Provided",
            $price = "Not Provided",
            $currentDiscount = 0,
            $dateListed  = "Not Provided",
            $genre = "Not Provided",
            $developer = "Not Provided",
            $publisher = "Not Provided",
            $originalRelease = "Not Provided") {
        $this->productID = $productID;
        $this->developerUserID = $developerUserID;
        $this->name = $name;
        $this->description = $description;
        $this->iconImage = $iconImage;
        $this->bannerImage = $bannerImage;
        $this->productFile = $productFile;
        $this->price = $price;
        $this->currentDiscount = $currentDiscount;
        $this->dateListed = $dateListed;
        $this->genre = $genre;
        $this->developer = $developer;
        $this->publisher = $publisher;
        $this->originalRelease = $originalRelease;
    }
    
    public function getProductID() {
        return $this->productID;
    }

    public function getDeveloperUserID() {
        return $this->developerUserID;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getIconImage() {
        return $this->iconImage;
    }
    
    public function getBannerImage() {
        return $this->bannerImage;
    }

    public function getProductFile() {
        return $this->productFile;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getCurrentDiscount() {
        return $this->currentDiscount;
    }

    public function getDateListed() {
        return $this->dateListed;
    }

    public function getGenre() {
        return $this->genre;
    }

    public function getDeveloper() {
        return $this->developer;
    }

    public function getPublisher() {
        return $this->publisher;
    }

    public function getOriginalRelease() {
        return $this->originalRelease;
    }
    
    public function currentPrice () {
        $price = $this->price;
        $currentDiscount = $this->currentDiscount; 
        $currentPrice = $price - ($price * $currentDiscount);
        return number_format($currentPrice,2);
    }
    
    public function getDiscountAsPercent() {
        $discountAsPercent = $this->currentDiscount * 100;
        return $discountAsPercent;
    }
    
    public function rowToProduct($row) {
        $this->productID = $row['productID'];
        $this->developerUserID = $row['developerUserID'];
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->iconImage = $row['iconImage'];
        $this->bannerImage = $row['bannerImage'];
        $this->productFile = $row['productFile'];
        $this->price = $row['price'];
        $this->currentDiscount = $row['currentDiscount'];
        $this->dateListed = $row['dateListed'];
        $this->genre = $row['genre'];
        $this->developer = $row['developer'];
        $this->publisher = $row['publisher'];
        $this->originalRelease = $row['originalRelease'];
    }
    
    public function getSaleDiv() {
        return "<a href='../html/product.html?product=".$this->productID."'><div id='salediv'>
            <img alt='".$this->name."cover' height='250px' src='".$this->iconImage."' width='250px'>
            <div id='infoinsalediv'>
                <table style='width:250px; padding:1px;'>
                    <tr>
                        <td colspan='2' style='text-align:center;'><h4>".$this->name."</h4></td>
                        </tr>
                        <tr>
                        <td style='width:99%;'>$".$this->currentPrice()."</td>
                        <td style='text-align:right;'><div id='discount'>".$this->getDiscountAsPercent()."% OFF</div></td>
                    </tr>
                </table>
            </div>
        </div></a>";
    }
    
    public function getLargeListItem() {        
        $text =  "<a href='../html/product.html?product=".$this->productID."'><div id='largelistitem'>					
            <div id='largelistitemimage'>
            <img alt='".$this->name."cover' src='".$this->iconImage."' width='150px' height='150px'>
            </div>
            
            <div id='largelistitemrightcontent'>
                
                <div id='largelistitemtitle'>
                    <h3>".$this->name."</h3>
                </div>
                
                <div id='largelistitemtext'>
                    ".$this->description."
                </div>
                
                <div id='largelistitemprice'><table style='border-spacing: 0px; width:inherit;'><tr>";
        if ($this->currentDiscount != 0) {
            $text= $text.
                    "<td><div id='discount'>
                    ".$this->getDiscountAsPercent()."% OFF
                    </div></td>";
        }
        $text= $text.
                "<td class='rightaligntd'>&nbsp;&nbsp;&nbsp;$".$this->currentPrice()."</td></table>                    
                </div>
            </div>					
        </div></a>";
        return $text;
    }
    
    public function getFullPageItem() {
        include "con_mtgamesdb.php";
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $addToCartSection = "";
        if(!isset($_SESSION['user'])) {
            $addToCartSection = "You must login to add to cart";
        }
        else {
            include_once "user.php";
            $user = new User();
            $user = unserialize($_SESSION['user']);
            $userID = $user->getUserID();
            $query = "SELECT * "
                    . "FROM OwnsACopyOf "
                    . "WHERE userID = $userID "
                    . "AND productID = ".$this->productID;
            $result = $mysqlioo->query($query) or die('Cannot complete query');
            if($result->num_rows == 0) {
                $addToCartSection = "<form action='../php/addtocart.php' method='post'>
                    <input type='hidden' name=productID value='".$this->productID."'>
                    <input type='submit' value='Add to Cart'>
                    </form>";
            }
            else {
                $addToCartSection = "You already own this game";
            }
        }
        
        $text=
        "<table>
            <tr>
                <td rowspan='9' style='width:250px; height:250px;'><img alt='".$this->name."cover' height='250px' src='".$this->iconImage."' width='250px'></td>
                <td class='rightaligntd'>Name:</td>
                <td>".$this->name."</td>
            </tr>
            <tr>
                <td class='rightaligntd' style='width: 110px;'>Genre:</td>
                <td>".$this->genre."</td>
            </tr>
            <tr>
                <td class='rightaligntd' style='width: 110px;'>Price:</td>
                <td>$".$this->price."</td>
            </tr>
            <tr>
                <td class='rightaligntd' style='width: 110px;'>Discount:</td>
                <td>";
                
            if ($this->currentDiscount != 0) {
            $text= $text.
                    "<div id='discount'>
                    ".$this->getDiscountAsPercent()."% OFF
                    </div>";
            }
            else {
                $text = $text."None";
            }
            $text = $text."</td>
            </tr>
            <tr>
                <td class='rightaligntd' style='width: 110px;'>Developer:</td>
                <td>".$this->developer."</td>
            </tr>
            <tr>
                <td class='rightaligntd' style='width: 110px;'>Publisher:</td>
                <td>".$this->publisher."</td>
            </tr>
            <tr>
                <td class='rightaligntd' style='width: 110px;'>Release Date:</td>
                <td>".$this->originalRelease."</td>
            </tr>
            <tr>
                <td class='rightaligntd' style='width: 110px;'>Date Listed:</td>
                <td>".$this->dateListed."</td>
            </tr>
            <tr>
                <td colspan='2' class='centeraligntd'><div  style='width: 220px;'>
                $addToCartSection
                </div></td>
            </tr>
            <tr>
                <td colspan='3' class='centeraligntd'>Screenshots:</td>
            </tr>
            <tr>
                <td colspan='3'><div id='fullpagedescription'>".$this->description."</div></td>
            </tr>
        </table>";
        
        return $text;
    }
    public function getProductByID($id) {
        include "con_mtgamesdb.php";
        $query = "SELECT * FROM product WHERE productID = $id";
        $result = $mysqlioo->query($query) or die ("<div style='text-align:center;'>No Product Found</div>");
        if($result->num_rows == 0) {
        echo "<div style='text-align:center;'>No Product Found</div>";
        }
        else {
            $row = $result->fetch_array(MYSQL_ASSOC);
                $this->rowToProduct($row);
        }
    } 
    
    public function getShoppingCartItem() {
        $text = 
           "<div id='shoppingcartitem'>
               <table class='shoppingcarttable'>
                    <trstyle='padding:0; margin:0;'>
                        <td style='width:75px; height:75px; padding:0;'>
                            <a href='../html/product.html?product=".$this->productID."'>
                                <img alt='".$this->name."cover' src='".$this->iconImage."' width='75px' height='75px'>
                            </a>
                        </td>
                        <td>
                            ".$this->name."
                        </td>
                        <td style='width:65px;'>
                            <form action='../php/removefromcart.php' method='post'>
                                <input type='hidden' name='productID' value='".$this->productID."'>
                                <input type='submit' value='Remove'>
                            </form>
                        </td>
                        <td style='width:65px; text-decoration:line-through'>
                            $".$this->price."
                        </td>
                        <td style='width:65px;'>";
        if($this->currentDiscount != 0) {
            $text= $text."<div id='discount'>".$this->getDiscountAsPercent()."% OFF</div>";
        }
        else {
            $text= $text."None";
        }
        $text= $text."  </td>
                        <td style='width:65px;'>
                            $".$this->currentPrice()."
                        </td>
                    </tr>
                </table>
            </div>";
        return $text;
    }
    public function getOwnedCopyItem() {
        $text = 
           "<div id='shoppingcartitem'>
               <table class='shoppingcarttable'>
                    <trstyle='padding:0; margin:0;'>
                        <td style='width:75px; height:75px; padding:0;'>
                            <a href='../html/product.html?product=".$this->productID."'>
                                <img alt='".$this->name."cover' src='".$this->iconImage."' width='75px' height='75px'>
                            </a>
                        </td>
                        <td>
                            ".$this->name."
                        </td>
                        <td style='width:150px'>
                            ".$this->genre."
                        </td>
                        <td style='width:90px;'>
                            <form action='../php/downloadproduct.php' method='post'>
                                <input type='hidden' name='productID' value='".$this->productID."'>
                                <input type='submit' value='Download'>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>";
        return $text;
    }
}

?>
