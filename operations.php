<?php
ob_start();
session_start();
include "panel/include/connection.php";
$pg = $_GET["pg"];
$a = array("a" => 2, "b" => 3);
echo array_sum($a) * 2;
if ($pg == "updatecost"){
    $qty2 = strip_tags(trim($_POST["qty2"]));
    $bid = strip_tags(trim($_POST["id"]));
    $upcost = $db->query("update basket set quantity='$qty2' where id='$bid'");
    if($upcost->rowCount()){
        echo "true";
    }
    else{
        echo "false";
    }
}

?>