<?php
include_once "product.php";
class ProductContainer {

    public $products;
    var $depth;

    function __construct() {
        $this->products = array();
        $this->depth = 0;
    }

    function add_product($product) {
        $this->products[$this->depth] = $product;
        $this->depth++;
    }

    function get_depth() {
        return $this->depth;
    }

    function get_product($product_no) {
        return $this->products[$product_no];
    }
    
    function resultToContainer($result) {
        while($row = $result->fetch_array()) {
            $product = new Product();
            $product->rowToProduct($row);
            $this->add_product($product);
        }
    }
    
    function getShoppingCartContainerProducts() {
        $printedProducts = "";
        for($i = 0; $i < $this->depth ; $i++) {
            $product = $this->get_product($i);
            $printedProducts = $printedProducts.$product->getShoppingCartItem();
        }
        return $printedProducts;
    }
    
    function getOwnedGameContainerProducts() {
        $printedProducts = "";
        for($i = 0; $i < $this->depth ; $i++) {
            $product = $this->get_product($i);
            $printedProducts = $printedProducts.$product->getOwnedCopyItem();
        }
        return $printedProducts;
    }
    
    function getTotalPrice() {
        $price = 0;
        for($i = 0; $i < $this->depth ; $i++) {
            $product = $this->get_product($i);
            $price = $price + $product->currentPrice();
        }
        return $price;
    }
    
    function getSavings() {
        $savings = 0;
        for($i = 0; $i < $this->depth ; $i++) {
            $product = $this->get_product($i);
            $savings = $savings + ($product->getPrice() - $product->currentPrice());
        }
        return $savings;
    }
    
    

}

?>
