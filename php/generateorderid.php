<?php
function generateOrderID() {
  $id = "";
  $chars = "012345678936792572378883256937";
  for($i = 0; $i < 10; $i++) {
    $x = rand(0, strlen($chars) -1);
    $id.= $chars{$x};
  }
  $id.= date("siHdmY");
  return $id;
}
?>
