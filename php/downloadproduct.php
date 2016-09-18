<?php
include "con_mtgamesdb.php";
include "product.php";

$product = new Product();

$productID = $_POST['productID'];

$product->getProductByID($productID);

$productDir = $product->getProductFile();
$extension = pathinfo($productDir, PATHINFO_EXTENSION);
$productName = $product->getName().".".$extension;

send_File($productName, $productDir);

FUNCTION send_file($name, $path) {
  OB_END_CLEAN();
  IF (!IS_FILE($path) or CONNECTION_STATUS()!=0) RETURN(FALSE);
  HEADER("Cache-Control: no-store, no-cache, must-revalidate");
  HEADER("Cache-Control: post-check=0, pre-check=0", FALSE);
  HEADER("Pragma: no-cache");
  HEADER("Expires: ".GMDATE("D, d M Y H:i:s", MKTIME(DATE("H")+2, DATE("i"), DATE("s"), DATE("m"), DATE("d"), DATE("Y")))." GMT");
  HEADER("Last-Modified: ".GMDATE("D, d M Y H:i:s")." GMT");
  HEADER("Content-Type: application/octet-stream");
  HEADER("Content-Length: ".(string)(FILESIZE($path)));
  HEADER("Content-Disposition: inline; filename=$name");
  HEADER("Content-Transfer-Encoding: binary\n");
  IF ($file = FOPEN($path, 'rb')) {
   WHILE(!FEOF($file) and (CONNECTION_STATUS()==0)) {
     PRINT(FREAD($file, 1024*8));
     FLUSH();
   }
   FCLOSE($file);
  }
  RETURN((CONNECTION_STATUS()==0) and !CONNECTION_ABORTED());
}
?>
