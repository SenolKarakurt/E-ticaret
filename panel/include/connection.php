<?php
error_reporting(1);
    try{
        $db = new PDO("mysql:host=localhost;dbname=newconcept", "root", "root");
        $db->exec("SET NAMES 'utf8'; SET CHARSET 'utf8'");
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }
//db944fcd80329cd11676746787e2c21f
?>