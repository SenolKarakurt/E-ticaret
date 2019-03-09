<?php
//error_reporting(0);
    try{
        $db = new PDO("mysql:host=localhost;dbname=newconcept", "root", "root");
        $db->exec("SET NAMES 'utf8'; SET CHARSET 'utf8'");
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }

?>