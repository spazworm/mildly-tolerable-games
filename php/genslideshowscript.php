<?php
include_once "con_mtgamesdb.php";
include_once "product.php";
include_once "productcontainer.php";

$query = "SELECT * "
        . "FROM Product "
        . "JOIN SlideshowProducts "
        . "ON Product.productID = SlideshowProducts.productID "
        . "ORDER BY SlideshowProducts.slideshowID "
        . "LIMIT 5";

$result = $mysqlioo->query($query) or die ("Cannot complete query");

$slideshowProducts = new ProductContainer();
$slideshowProducts->resultToContainer($result);

$slideshowTexts = array(5);
$i = 0;
foreach($result as $row) {
    $slideshowTexts[$i] = $row['slideshowText'];
    $i++;
}

echo "
    <script>
        window.onload=function(){gotoslide(0)};
        
        slides = new Array(
        '".$slideshowProducts->get_product(0)->getBannerImage()."',
        '".$slideshowProducts->get_product(1)->getBannerImage()."', 
        '".$slideshowProducts->get_product(2)->getBannerImage()."', 
        '".$slideshowProducts->get_product(3)->getBannerImage()."', 
        '".$slideshowProducts->get_product(4)->getBannerImage()."');
            
        slidetitles= new Array(
        '".$slideshowProducts->get_product(0)->getName()."',
        '".$slideshowProducts->get_product(1)->getName()."',
        '".$slideshowProducts->get_product(2)->getName()."',
        '".$slideshowProducts->get_product(3)->getName()."',
        '".$slideshowProducts->get_product(4)->getName()."');
            
        slidetexts= new Array(
        '".$slideshowTexts[0]."',
        '".$slideshowTexts[1]."',
        '".$slideshowTexts[2]."',
        '".$slideshowTexts[3]."',
        '".$slideshowTexts[4]."');
            
        slidehyperlinks= new Array(
        '../html/product.html?product=".$slideshowProducts->get_product(0)->getProductID()."',
        '../html/product.html?product=".$slideshowProducts->get_product(1)->getProductID()."',
        '../html/product.html?product=".$slideshowProducts->get_product(2)->getProductID()."',
        '../html/product.html?product=".$slideshowProducts->get_product(3)->getProductID()."',
        '../html/product.html?product=".$slideshowProducts->get_product(4)->getProductID()."');
            
        slide = 0;
        

        autoSlide = setInterval(function(){showslides(1)},10000);

        function resetAutoSlide() {
            autoSlide = setInterval(function(){showslides(1)},10000);
        }
        
        function clearSlideInterval() {
            clearInterval(autoSlide);
            autoSlide = setInterval(function(){resetAutoSlide()},50000);
        }

        function gotoslidebynavsquare (slideNumber){
            clearSlideInterval;
            gotoslide(slideNumber);
        }
        
        function gotoslidebyarrow(slideNav){
            clearSlideInterval;
            showslides(slideNav);
        }

        function gotoslide(slideNumber) {
            document.getElementById('navsquare' + (slide + 1)).src='../images/navsquare.png';
            slide = slideNumber;
            document.getElementById('navsquare' + (slide + 1)).src='../images/selectednavsquare.png';
            document.getElementById('slideshow').src=slides[slide];
            document.getElementById('slideshowinfotitle').innerHTML=slidetitles[slide];
            document.getElementById('slideshowinfomore').innerHTML=slidetexts[slide];
            document.getElementById('slidehyperlink').href=slidehyperlinks[slide];
        }

        function showslides(slidenav) {
            newslidelocation = slide + slidenav;
            if(newslidelocation == 5) {
                newslidelocation = 0;
            }
            if(newslidelocation == -1) {
                newslidelocation = 4;
            }
            gotoslide(newslidelocation);
        }
</script>
";
?>
