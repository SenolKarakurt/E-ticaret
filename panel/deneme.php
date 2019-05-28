<?php
ob_start();
session_start();

include "connection.php";

$pg = $_GET["pg"];
$user_id = $_GET["userID"];
$web_id = $_GET["webID"];
$siteID = $_GET["siteID"];
$cat_id = $_GET["catID"];
date_default_timezone_set('Europe/Istanbul');
if (isset($_SESSION["admin"]["username"])){

    //Users
    if ($pg == "users"){
        $process = $_GET["process"];

        if ($process == "user_add") {
            if (isset($_POST["username"])) {
                $username = strip_tags(trim($_POST["username"]));
                $password = md5(sha1(strip_tags(trim($_POST["password"]))));
                $email = strip_tags(trim($_POST["email"]));
                $name = strip_tags(trim($_POST["name"]));
                $usertype = strip_tags(trim($_POST["usertype"]));

                if (empty($username) || empty($password) || empty($email) || empty($name) || empty($usertype)) {
                    header("Location: ../index.php");
                } else {
                    $query = $db->query("insert into panelusers (username,password,email,name,usertype) values('" . $username . "','" . $password . "','" . $email . "','" . $name . "','" . $usertype . "')");
                    if ($query->rowCount()) {
                        setcookie('testcookie', "true|User Saved.........", time() + 20, '/');
                        header("location: ../index.php?p=users");
                    } else {
                        setcookie('testcookie', "false|User Not Saved.........", time() + 20, '/');
                        header("location: ../index.php?p=users");
                    }
                }
            }
        }

        elseif ($process == "user_delete") {
            if (empty($user_id) && !is_numeric($user_id)) {
                header("Location: ../index.php");
            } else {

                $user_delete = $db->query("delete from panelusers where id='$user_id'");
                if ($user_delete->rowCount()) {
                    setcookie('testcookie', "true|User Deleted.........", time() + 20, '/');
                    header("location: ../index.php?p=users");
                } else {
                    setcookie('testcookie', "false|User Not Deleted.........", time() + 20, '/');
                    header("location: ../index.php?p=users");
                }
            }
        }

        elseif ($process == "user_update") {
            $q=$_GET["q"];
            if($q == "get"){
                if (isset($_GET["userID"])){
                    //$user_id = $_GET["userID"];
                    $edituser = $db->query("select * from panelusers where id='$user_id'",PDO::FETCH_ASSOC);
                    if ($edituser->rowCount()){
                        foreach ($edituser as $edit){
                            $user_data["user"] = array(
                                "username"     => $edit["username"],
                                "password"     => $edit["password"],
                                "email"        => $edit["email"],
                                "name"         => $edit["name"],
                                "usertype"     => $edit["usertype"]
                            );
                            echo json_encode($user_data);

                        }
                    }
                }
            }
            elseif ($q == "update"){
                $username2 = strip_tags(trim($_POST["username2"]));
                $password2 = md5(sha1(strip_tags(trim($_POST["password2"]))));
                $email2 = strip_tags(trim($_POST["email2"]));
                $name2 = strip_tags(trim($_POST["name2"]));
                $usertype2 = strip_tags(trim($_POST["usertype2"]));
                $IDUser = strip_tags(trim($_POST["ID"]));
                if (empty($username2) || empty($password2) || empty($email2) || empty($name2) || empty($usertype2)) {
                    echo "empty";
                } else {
                    $user_update = $db->query("update panelusers set username='$username2', 
            password='$password2',
            email='$email2', 
            name='$name2', 
            usertype='$usertype2' 
             where id='$IDUser'");
                    if ($user_update->rowCount()) {
                        setcookie('testcookie', "true|User Updated.........", time() + 20, '/');
                        header("location: ../index.php?p=users");
                    } else {
                        setcookie('testcookie', "true|User Not Updated.........", time() + 20, '/');
                        header("location: ../index.php?p=users");
                    }
                }

            }


        }
        else {
            header("Location: ../index.php");
        }
    }
    //Categories
    else if ($pg == "categories"){
        $process = $_GET["process"];
        $q=$_GET["q"];
        if ($process == "category_edit") {
            if ($q == "get") {
                if (isset($_GET["webID"])){
                    $webID = $_GET["webID"];
                    $categorySelect = $db->query("select * from categories where websiteid='$webID'");
                    foreach($categorySelect as $cat) {
                        ?>
                        <option value="<?php echo $cat["id"]; ?>"><?php echo $cat["category"]; ?></option>
                        <?php
                    }
                }
                elseif (isset($_POST["site_id"])){
                    $siteID = $_POST["site_id"];
                    echo '<option value="0">Top Category</option>';
                    category($db, 0, 0, $id, $siteID);
                }
                elseif (isset($_POST["site_id2"])){
                    $siteID = $_POST["site_id2"];
                    echo '<option value="0">Top Category</option>';
                    category($db, 0, 0, $id, $siteID);
                }
                else{
                    $catID = $_GET["cat_id"];
                    $categorySelect = $db->query("select * from categories where id='$catID'");
                    if ($categorySelect->rowCount()) {
                        foreach ($categorySelect as $categoryedit) {
                            $cat_data["cat_data"] = array(
                                "category"      => $categoryedit["category"],
                                "cat_top_id"    => $categoryedit["category_top_id"],
                                "sira"          => $categoryedit["sira"],
                                "ctitle"        => $categoryedit["ctitle"],
                                "categorytitle" => $categoryedit["categorytitle"],
                                "h2title"       => $categoryedit["h2title"],
                                "headercontent" => $categoryedit["headercontent"],
                                "pagecontent"   => $categoryedit["pagecontent"],
                                "link"          => $categoryedit["link"],
                                "description"   => $categoryedit["description"],
                                "keyword"       => $categoryedit["keyword"],
                                "cat_image"     => $categoryedit["cimage"],
                                "websiteid"     => $categoryedit["websiteid"],
                                "site_name"     => $site_name
                            );
                        }
                        echo json_encode($cat_data);
                    }
                }
            }
            else if ($q == "update"){
                if (isset($_POST["edit_subcategory"])) {
                    $cat_name2 = strip_tags(trim($_POST["cat_name2"]));
                    $cat_top_id2 = strip_tags(trim($_POST["cat_top_id2"]));
                    $sira2 = strip_tags(trim($_POST["sira2"]));
                    $IDCat = strip_tags(trim($_POST["subcategory_edit"]));
                    $cat_name_seflink2 = permalink($cat_name2);
                    $cat_title2 = strip_tags(trim($_POST["cat_title2"]));
                    $c_title2 = strip_tags(trim($_POST["c_title2"]));
                    $h2_title2 = strip_tags(trim($_POST["h2title2"]));
                    $cat_keyword2 = strip_tags(trim($_POST["cat_keyword2"]));
                    $cat_description2 = strip_tags(trim($_POST["cat_description2"]));
                    $cat_link2 = strip_tags(trim($_POST["cat_link2"]));
                    $header_content2 = strip_tags(trim($_POST["header_content2"]));

                    $cat_site2 = strip_tags(trim($_POST["cat_site2"]));
                    $siteID = $cat_site2;

                    $cat_old_name = strip_tags(trim($_POST["cat_image_hidden"]));

                    $isim_parcala = explode(".", $cat_old_name);
                    $r_yeniismi = $cat_name_seflink2.".".$isim_parcala["1"];
                    $cat_image2 = $_FILES["cat_image2"]["name"];

                    if ($cat_image2 == null){

                        $cat_update = $db->query("update categories set category='$cat_name2', categorytitle='$cat_title2', link='$cat_link2', sira='$sira2', description='$cat_description2', keyword='$cat_keyword2', ctitle='$c_title2', h2title='$h2_title2', headercontent='$header_content2', websiteid='$siteID', category_top_id='$cat_top_id2', category_seflink='$cat_name_seflink2' where id='$IDCat'");

                        //rename("../../../".$site_name."/img/".$cat_old_name, "../../../".$site_name."/img/".$r_yeniismi);

                        rename("../../img/".$cat_old_name, "../../img/".$r_yeniismi);

                        if ($cat_update){
                            setcookie('testcookie', "true|Category Updated...", time() + 20, '/');
                            header("location: ../index.php?p=categories&site=".$siteID);
                        }
                        else{
                            setcookie('testcookie', "false|Category Not Updated...", time() + 20, '/');
                            header("location: ../index.php?p=categories&site=".$siteID);
                        }
                    }
                    else{
                        $risimi = $_FILES["cat_image2"]["name"];
                        //$s_name = strip_tags(trim($_POST["slider_name"]));
                        $rturu = $_FILES["cat_image2"]["type"];
                        $rboyutu = $_FILES["cat_image2"]["size"];
                        $isim_parcala = explode(".", $risimi);
                        $rasgeleisim = permalink($cat_title2);
                        $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];

                        //$dhedef = "../../../".$site_name."/img";
                        //$temp = "../../../".$site_name."/img/temp";
                        //unlink("../../../".$site_name."img/".$cat_old_name);

                        $dhedef = "../../img";
                        $temp = "../../img/temp";
                        unlink("../../img/".$cat_old_name);

                        $dyukle = move_uploaded_file($_FILES["cat_image2"]["tmp_name"], $temp . '/' . $r_yeniismi);
                        if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg") {
                            $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "png") {
                            $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "gif") {
                            $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "bmp") {
                            $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                        }
                        $boyut = getimagesize($temp . '/' . $r_yeniismi);
                        $resimorani = 848 / $boyut[0];
                        $yeniyukseklik = $resimorani * $boyut[1];
                        $yeniresim = imagecreatetruecolor(848, $yeniyukseklik);
                        imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 848, $yeniyukseklik, $boyut[0], $boyut[1]);
                        $hedefdosya = $dhedef . "/" . $r_yeniismi;
                        imagejpeg($yeniresim, $hedefdosya, 100);

                        if ($dyukle) {
                            $cat_update = $db->query("update categories set category='$cat_name2', categorytitle='$cat_title2', link='$cat_link2', sira='$sira2', description='$cat_description2', keyword='$cat_keyword2', ctitle='$c_title2', h2title='$h2_title2', cimage='$r_yeniismi', headercontent='$header_content2', websiteid='$siteID', category_top_id='$cat_top_id2', category_seflink='$cat_name_seflink2' where id='$IDCat'");

                            if ($cat_update->rowCount()){
                                setcookie('testcookie', "true|Category Updated...", time() + 20, '/');
                                header("location: ../index.php?p=categories&site=".$siteID);
                            }
                            else{
                                setcookie('testcookie', "false|Category Not Updated...", time() + 20, '/');
                                header("location: ../index.php?p=categories&site=".$siteID);
                            }
                        }
                        else {
                            echo "Dosya Yüklenemedi";
                            header("refresh: 2; url=$_SERVER[HTTP_REFERER]");
                        }
                    }

                    /*
                                        $cat_update = $db->query("update categories set category='$cat_name2', categorytitle='$cat_title2', link='$cat_link2', sira='$sira2', description='$cat_description2', keyword='$cat_keyword2', ctitle='$c_title2', pagecontent='$page_content2', h2title='$h2_title2', headercontent='$header_content2', websiteid='$siteID', category_top_id='$cat_top_id2', category_seflink='$cat_name_seflink2' where id='$IDCat'");
                                        if ($cat_update->rowCount()) {
                                            echo "true";
                                        } else {
                                            echo $IDCat;
                                        }
                    */

                }
            }
            else if ($q == "orderUpdate"){
                $order = $_POST["order"];
                $id = $_POST["id"];
                $order_update = $db->query("update categories set sira='$order' where id='$id'");
            }
        }
        else if($process == "category_add") {
            /*
            if (isset($_POST["add_subcategory"])){
                $cat_name = strip_tags(trim($_POST["cat_name"]));
                $cat_top_id = strip_tags(trim($_POST["cat_top_id"]));
                $cat_order = strip_tags(trim($_POST["sira"]));
                $cat_name_seflink = permalink($cat_name);
                $siteID = $_SESSION["admin"]["websiteid"];
                $add_sub = $db->query("insert into categories(category,sira,websiteid,category_top_id,category_seflink) values ('$cat_name','$cat_order','$siteID','$cat_top_id','$cat_name_seflink')");

                if ($add_sub->rowCount()) {
                    echo "true";
                } else {
                    echo "false";
                }
            }
            */
            if (isset($_POST["add_subcategory"])) {
                $cat_name = strip_tags(trim($_POST["cat_name"]));
                $cat_top_id = strip_tags(trim($_POST["cat_top_id"]));
                $cat_order = strip_tags(trim($_POST["sira"]));
                $cat_name_seflink = permalink($cat_name);
                $cat_title = strip_tags(trim($_POST["cat_title"]));
                $c_title = strip_tags(trim($_POST["c_title"]));
                $h2_title = strip_tags(trim($_POST["h2title"]));
                $cat_keyword = strip_tags(trim($_POST["cat_keyword"]));
                $cat_description = strip_tags(trim($_POST["cat_description"]));
                $cat_link = strip_tags(trim($_POST["cat_link"]));
                $header_content = strip_tags(trim($_POST["header_content"]));

                $cat_site = strip_tags(trim($_POST["cat_site"]));
                $siteID = $cat_site;

                $risimi = $_FILES["cat_image"]["name"];
                //$s_name = strip_tags(trim($_POST["slider_name"]));
                $rturu = $_FILES["cat_image"]["type"];
                $rboyutu = $_FILES["cat_image"]["size"];
                $isim_parcala = explode(".", $risimi);
                $rasgeleisim = permalink($cat_title);
                $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];

                //$temp = "../../../".$site_name."/img/temp";
                //$dhedef = "../../../".$site_name."/img";
                $temp = "../../img/temp";
                $dhedef = "../../img";

                if (!empty($_FILES["cat_image"]["name"]) || $_FILES["cat_image"]["name"] != null) {
                    if (!file_exists($dhedef . "/" . $r_yeniismi)) {
                        $dyukle = move_uploaded_file($_FILES["cat_image"]["tmp_name"], $temp . '/' . $r_yeniismi);
                        if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg") {
                            $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "png") {
                            $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "gif") {
                            $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "bmp") {
                            $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                        }
                        $boyut = getimagesize($temp . '/' . $r_yeniismi);
                        $resimorani = 848 / $boyut[0];
                        $yeniyukseklik = $resimorani * $boyut[1];
                        $yeniresim = imagecreatetruecolor(848, $yeniyukseklik);
                        imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 848, $yeniyukseklik, $boyut[0], $boyut[1]);
                        $hedefdosya = $dhedef . "/" . $r_yeniismi;
                        imagejpeg($yeniresim, $hedefdosya, 100);

                        if ($dyukle) {
                            $add_sub = $db->query("insert into `categories`(`category`, `categorytitle`, `link`, `sira`, `description`, `keyword`, `ctitle`, `h2title`, `cimage`, `headercontent`, `websiteid`, `category_top_id`, `category_seflink`) values ('$cat_name','$cat_title','$cat_link','$cat_order','$cat_description','$cat_keyword','$c_title','$h2_title','$r_yeniismi','$header_content','$siteID','$cat_top_id','$cat_name_seflink')");

                            if ($add_sub) {
                                setcookie('testcookie', "true|Kategori Kaydedildi.........", time() + 20, '/');
                                header("location: ../index.php?p=categories");
                            } else {
                                setcookie('testcookie', "false|Kategori Kaydedilemedi...", time() + 20, '/');
                                header("location: ../index.php?p=categories");
                            }
                            unlink($temp . "/" . $r_yeniismi);
                        } else {
                            $add_sub2 = $db->query("insert into `categories`(`category`, `categorytitle`, `link`, `sira`, `description`, `keyword`, `ctitle`, `h2title`, `headercontent`, `websiteid`, `category_top_id`, `category_seflink`) values ('$cat_name','$cat_title','$cat_link','$cat_order','$cat_description','$cat_keyword','$c_title','$h2_title','$header_content','$siteID','$cat_top_id','$cat_name_seflink')");

                            if ($add_sub2) {
                                setcookie('testcookie', "true|Kategori Kaydedildi.........", time() + 20, '/');
                                header("location: ../index.php?p=categories");
                            } else {
                                setcookie('testcookie', "false|Kategori Kaydedilemedi...", time() + 20, '/');
                                header("location: ../index.php?p=categories");
                            }
                            //echo "Dosya Yüklenemedi";
                            // header("refresh: 2; url=$_SERVER[HTTP_REFERER]");
                        }
                    } else {
                        setcookie('testcookie', "false|Resim Mevcut...", time() + 20, '/');
                        header("location: ../index.php?p=categories");
                    }
                }
                else{
                    $add_sub3 = $db->query("insert into `categories`(`category`, `categorytitle`, `link`, `sira`, `description`, `keyword`, `ctitle`, `h2title`, `headercontent`, `websiteid`, `category_top_id`, `category_seflink`) values ('$cat_name','$cat_title','$cat_link','$cat_order','$cat_description','$cat_keyword','$c_title','$h2_title','$header_content','$siteID','$cat_top_id','$cat_name_seflink')");

                    if ($add_sub3) {
                        setcookie('testcookie', "true|Kategori Kaydedildi.........", time() + 20, '/');
                        header("location: ../index.php?p=categories");
                    } else {
                        setcookie('testcookie', "false|Kategori Kaydedilemedi...", time() + 20, '/');
                        header("location: ../index.php?p=categories");
                    }
                }

            }
        }
        else if ($process == "category_delete"){

            $cat_id = $_GET["catID"];
            $cat_site = $_GET["cat_site"];
            $_SESSION["admin"]["websiteid"] = $cat_site;

            $sub_select = $db->query("select * from categories where category_top_id='$cat_id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");

            function sub1($sub1_id){
                global $db;
                $sub1Sor = $db->query("select * from categories where category_top_id='$sub1_id' and websiteid='".$_SESSION["admin"]["websiteid"]."'",PDO::FETCH_ASSOC);
                foreach ($sub1Sor as $sub1Yaz){
                    $sub2id = $sub1Yaz["id"];
                    $sub2Sor = $db->query("select * from categories where category_top_id='$sub2id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                    $sub2Say = $sub2Sor->rowCount();
                    if($sub2Say > 0){
                        sub2($sub2id);
                        $db->query("delete from categories where category_top_id='$sub2id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                        $db->query("delete from categories where id='$sub2id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                    }
                    $db->query("delete from categories where id='$sub2id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                }
            }

            function sub2 ($sub2_id){
                global $db;
                $sub3Sor = $db->query("select * from categories where category_top_id='$sub2_id' and websiteid='".$_SESSION["admin"]["websiteid"]."'",PDO::FETCH_ASSOC);
                foreach ($sub3Sor as $sub3Yaz){
                    $sub3id = $sub3Yaz["id"];
                    $sub4Sor = $db->query("select * from categories where category_top_id='$sub3id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                    $sub4Say = $sub4Sor->rowCount();
                    if($sub4Say > 0){
                        sub2($sub3id);
                        $db->query("delete from categories where category_top_id='$sub3id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                        $db->query("delete from categories where id='$sub3id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                    }
                    $db->query("delete from categories where id='$sub3id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                }
            }
            sub1($cat_id);
            $db->query("delete from categories where id='$cat_id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
            echo "true";
        }
    }
    //Websites
    else if ($pg == "websites"){
        $process = $_GET["process"];
        if (isset($_POST["siteID"])) {
            $siteID = $_POST["siteID"];
            $Site = $db->query("select * from websites where websiteid='$siteID'");
            //echo $go;
            foreach ($Site as $goWebSite){
                $_SESSION["admin"]["websiteid"] = $goWebSite["websiteid"];
            }
        }
        elseif ($process == "website_edit"){
            $q=$_GET["q"];
            if ($q == "get"){
                $websiteID = $_GET["websiteID"];
                $websiteSelect = $db->query("select * from websites where websiteid='$websiteID'");
                if ($websiteSelect->rowCount()){
                    foreach ($websiteSelect as $webedit){
                        $web_data["web_data"] = array(
                            "websitename" => $webedit["websitename"],
                            "websitelink" => $webedit["websitelink"]
                        );
                        echo json_encode($web_data);
                    }
                }
            }
            elseif ($q == "update"){
                $websitename2 = strip_tags(trim($_POST["websitename2"]));
                $websitelink2 = strip_tags(trim($_POST["websitelink2"]));
                $IDweb = strip_tags(trim($_POST["webID"]));
                if (empty($websitename2) || empty($websitelink2)) {
                    echo "empty";
                } else {
                    $web_update = $db->query("update websites set websitename='$websitename2', websitelink='$websitelink2'  where websiteid='$IDweb'");
                    if ($web_update->rowCount()) {
                        //header("Location: ../index.php");   //Notification
                        echo "true";
                    } else {
                        // header("Location: ../index.php");
                        echo $web_id;
                    }
                }

            }
            elseif ($q == "getSites"){
                $web = $db->query("select * from websites");
                foreach($web as $wb){
                    echo $wb["websiteid"]."\n";
                }
            }
        }
        elseif ($process == "website_add"){
            if (isset($_POST["websitename"]) && isset($_POST["websitelink"])){
                $websitename = strip_tags(trim($_POST["websitename"]));
                $websitelink = strip_tags(trim($_POST["websitelink"]));
                if (empty($websitename) || empty($websitelink)) {
                    header("Location: ../index.php");
                } else {
                    $query = $db->query("insert into websites (websitename,websitelink) values('" . $websitename . "','" . $websitelink . "')");
                    if ($query->rowCount()) {
                        echo "true";
                    } else {
                        echo "false";
                    }
                }
            }
        }
        elseif ($process == "website_delete") {

            if (empty($siteID) && !is_numeric($siteID)) {
                header("Location: ../index.php");
            } else {

                $website_delete = $db->query("delete from websites where websiteid='$siteID'");
                if ($website_delete->rowCount()) {
                    // header("Location: ../index.php"); //Notification
                    echo "true";
                } else {
                    //header("Location: ../index.php");
                    echo "false";
                }
            }
        }
    }
    //Slider
    else if ($pg == "slider"){
        $process = $_GET["process"];
        if ($process == "slider_add") {
            if ($_POST["slider_add"]) {
                $sliderSite = $_POST["sliderSite"];
                $_SESSION["admin"]["websiteid"] = $sliderSite;

                $wb = $db->query("select * from websites where websiteid='$sliderSite'")->fetch();
                $site_name = $wb["websitename"];

                if ($_SESSION["admin"]["websiteid"] == 1){
                    $slider_title = strip_tags(trim($_POST["slider_title"]));
                    $slider_description = strip_tags(trim($_POST["slider_description"]));
                    $slider_order = strip_tags(trim($_POST["slider_order"]));
                    $slider_link = strip_tags(trim($_POST["sliderLink"]));
                    $s_status = $_POST["slider_status"] == "on" ? "1" : "0";
                    $risimi = $_FILES["slider_image"]["name"];
                    //$s_name = strip_tags(trim($_POST["slider_name"]));
                    $rturu = $_FILES["slider_image"]["type"];
                    $rboyutu = $_FILES["slider_image"]["size"];
                    $isim_parcala = explode(".", $risimi);
                    $rasgeleisim = permalink($slider_title);
                    $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];

                    $temp = "../../../".$site_name."/img/temp";
                    $dhedef = "../../../".$site_name."/img/slides";
                    $thumb = "../../../".$site_name."/img/slides/thumbs/";

                    //$temp = "../../img/temp";
                    //$dhedef = "../../img/slides";
                    //$thumb = "../../img/slides/thumbs/";
                    if (!file_exists($dhedef."/".$r_yeniismi) && !file_exists($thumb.$r_yeniismi)) {
                        $dyukle = move_uploaded_file($_FILES["slider_image"]["tmp_name"], $temp . '/' . $r_yeniismi);
                        if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg" || $isim_parcala["1"] == "JPG" || $isim_parcala["1"] == "JPEG") {
                            $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "png") {
                            $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "gif") {
                            $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "bmp") {
                            $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                        }
                        $boyut = getimagesize($temp . '/' . $r_yeniismi);
                        $resimorani = 800 / $boyut[0];
                        $yeniyukseklik = $resimorani * $boyut[1];
                        $yeniresim = imagecreatetruecolor(800, $yeniyukseklik);
                        imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 800, $yeniyukseklik, $boyut[0], $boyut[1]);
                        $hedefdosya = $dhedef . "/" . $r_yeniismi;
                        imagejpeg($yeniresim, $hedefdosya, 100);

                        $boyutt = getimagesize($temp . '/' . $r_yeniismi);
                        $resimoranit = 140 / $boyutt[0];
                        $yeniyukseklikt = $resimoranit * $boyutt[1];
                        $yeniresimt = imagecreatetruecolor(140, $yeniyukseklikt);
                        imagecopyresampled($yeniresimt, $ndo, 0, 0, 0, 0, 140, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                        $hedefdosyat = $thumb . "/" . $r_yeniismi;
                        imagejpeg($yeniresimt, $hedefdosyat, 100);

                        if ($dyukle) {
                            $addSlide = $db->query("insert into slider (slider_title, slider_name, slider_description, slider_link, slider_order, slider_status, websiteid) VALUES ('$slider_title','$r_yeniismi','$slider_description', '$slider_link','$slider_order','$s_status','" . $_SESSION["admin"]["websiteid"] . "')");

                            if ($addSlide->rowCount()) {
                                setcookie('testcookie', "true|Slayt kaydedildi...", time() + 20, '/');
                                header("location: ../index.php?p=slider");
                            } else {
                                setcookie('testcookie', "false|Slayt kaydedilemedi...", time() + 20, '/');
                                header("location: ../index.php?p=slider");
                            }
                            unlink($temp."/".$r_yeniismi);
                        } else {
                            setcookie('testcookie', "false|Resim yüklenemedi...", time() + 20, '/');
                            header("location: ../index.php?p=slider");
                        }

                    } else{
                        setcookie('testcookie', "false|Resim Mevcut...", time() + 20, '/');
                        header("location: ../index.php?p=slider");
                    }
                }
                elseif ($_SESSION["admin"]["websiteid"] == 2){
                    $slider_title = strip_tags(trim($_POST["slider_title"]));
                    $slider_description = strip_tags(trim($_POST["slider_description"]));
                    $slider_order = strip_tags(trim($_POST["slider_order"]));
                    $slider_link = strip_tags(trim($_POST["sliderLink"]));
                    $s_status = $_POST["slider_status"] == "on" ? "1" : "0";
                    $risimi = $_FILES["slider_image"]["name"];
                    //$s_name = strip_tags(trim($_POST["slider_name"]));
                    $rturu = $_FILES["slider_image"]["type"];
                    $rboyutu = $_FILES["slider_image"]["size"];
                    $isim_parcala = explode(".", $risimi);
                    $rasgeleisim = permalink($slider_title);
                    $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];

                    $temp = "../../../".$site_name."/img/temp";
                    $dhedef = "../../../".$site_name."/img/slides";
                    $thumb = "../../../".$site_name."/img/slides/thumbs/";

                    //$temp = "../../img/temp";
                    //$dhedef = "../../img/slides";
                    //$thumb = "../../img/slides/thumbs/";

                    if (!file_exists($dhedef."/".$r_yeniismi) && !file_exists($thumb.$r_yeniismi)) {
                        $dyukle = move_uploaded_file($_FILES["slider_image"]["tmp_name"], $temp . '/' . $r_yeniismi);
                        if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg" || $isim_parcala["1"] == "JPG" || $isim_parcala["1"] == "JPEG") {
                            $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "png") {
                            $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "gif") {
                            $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "bmp") {
                            $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                        }
                        $boyut = getimagesize($temp . '/' . $r_yeniismi);
                        $resimorani = 1140 / $boyut[0];
                        $yeniyukseklik = $resimorani * $boyut[1];
                        $yeniresim = imagecreatetruecolor(1140, $yeniyukseklik);
                        imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 1140, $yeniyukseklik, $boyut[0], $boyut[1]);
                        $hedefdosya = $dhedef . "/" . $r_yeniismi;
                        imagejpeg($yeniresim, $hedefdosya, 100);

                        $boyutt = getimagesize($temp . '/' . $r_yeniismi);
                        $resimoranit = 140 / $boyutt[0];
                        $yeniyukseklikt = $resimoranit * $boyutt[1];
                        $yeniresimt = imagecreatetruecolor(140, $yeniyukseklikt);
                        imagecopyresampled($yeniresimt, $ndo, 0, 0, 0, 0, 140, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                        $hedefdosyat = $thumb . "/" . $r_yeniismi;
                        imagejpeg($yeniresimt, $hedefdosyat, 100);

                        if ($dyukle) {
                            $addSlide = $db->query("insert into slider (slider_title, slider_name, slider_description, slider_link, slider_order, slider_status, websiteid) VALUES ('$slider_title','$r_yeniismi','$slider_description', '$slider_link','$slider_order','$s_status','" . $_SESSION["admin"]["websiteid"] . "')");

                            if ($addSlide->rowCount()) {
                                setcookie('testcookie', "true|Slayt kaydedildi...", time() + 20, '/');
                                header("location: ../index.php?p=slider");
                            } else {
                                setcookie('testcookie', "false|Slayt kaydedilemedi...", time() + 20, '/');
                                header("location: ../index.php?p=slider");
                            }
                            unlink($temp."/".$r_yeniismi);
                        } else {
                            setcookie('testcookie', "false|Resim yüklenemedi...", time() + 20, '/');
                            header("location: ../index.php?p=slider");
                        }

                    } else{
                        setcookie('testcookie', "false|Resim Mevcut...", time() + 20, '/');
                        header("location: ../index.php?p=slider");
                    }
                }

            }
        }
        else if ($process == "slider_edit"){
            $q = $_GET["q"];
            if ($q == "get"){
                $sliderID = $_GET["slider_id"];
                $sliderSelect = $db->query("select * from slider where slider_id='$sliderID'");
                if ($sliderSelect->rowCount()){
                    foreach ($sliderSelect as $slider_edit){
                        $slider_data = array();
                        $slider_data["slider_data"] = array(
                            "slider_name"        => $slider_edit["slider_name"],
                            "slider_description" => $slider_edit["slider_description"],
                            "slider_title"       => $slider_edit["slider_title"],
                            "slider_site"        => $slider_edit["websiteid"],
                            "slider_order"       => $slider_edit["slider_order"],
                            "slider_status"      => $slider_edit["slider_status"],
                            "slider_link"        => $slider_edit["slider_link"]
                        );
                    }
                    $getPages = $db->query("select * from pages");
                    $lenghtToPages = $getPages->rowCount();
                    $slider_data["lenght"]= array(
                        "lenght" => $lenghtToPages
                    );
                    $k=0;
                    foreach ($getPages as $ss) {
                        $slider_data["pages"][$k] = array(
                            "id"      =>  $ss["id"],
                            "title"    =>  $ss["title"]
                        );
                        $k++;
                    }
                    echo json_encode($slider_data);



                }
            }
            else if ($_POST["slider_edit"]){
                $slider_description2 = strip_tags(trim($_POST["slider_description2"]));
                $slider_title2 = strip_tags(trim($_POST["slider_title2"]));
                $slider_order2 = strip_tags(trim($_POST["slider_order2"]));
                $IDSlider = strip_tags(trim($_POST["slider_hidden"]));
                $slider_old_name = strip_tags(trim($_POST["slider_hidden2"]));

                $sliderSite2 = $_POST["slider_site"];
                $_SESSION["admin"]["websiteid"] = $sliderSite2;

                $s_status2 = $_POST["slider_status2"] == "on" ? "1" : "0";
                $slider_link2 = strip_tags(trim($_POST["pagesSelect"]));
                $isim_parcala = explode(".", $slider_old_name);
                $slider_name2 = permalink($slider_title2);
                $r_yeniismi = $slider_name2.".".$isim_parcala["1"];
                $slider_image = $_FILES["slider_image2"]["name"];
                if ($slider_image == null){
                    $slider_update = $db->query("update slider set slider_title='$slider_title2', slider_description='$slider_description2', slider_name='$r_yeniismi', slider_link='$slider_link2', slider_order='$slider_order2', slider_status='$s_status2', websiteid='" . $_SESSION["admin"]["websiteid"] . "'  where slider_id='$IDSlider'");
                    rename("../../img/slides/thumbs/".$slider_old_name, "../../img/slides/thumbs/".$r_yeniismi);
                    rename("../../img/slides/".$slider_old_name, "../../img/slides/".$r_yeniismi);
                    if ($slider_update->rowCount()){
                        setcookie('testcookie', "true|Image Updated...", time() + 20, '/');
                        header("location: ../index.php?p=slider");
                    }
                    else{
                        setcookie('testcookie', "false|Image Not Updated...", time() + 20, '/');
                        header("location: ../index.php?p=slider");
                    }
                }
                else{
                    $risimi = $_FILES["slider_image2"]["name"];
                    //$s_name = strip_tags(trim($_POST["slider_name"]));
                    $rturu = $_FILES["slider_image2"]["type"];
                    $rboyutu = $_FILES["slider_image2"]["size"];
                    $isim_parcala = explode(".", $risimi);
                    $rasgeleisim = permalink($slider_title2);
                    $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];

                    $temp = "../../img/temp";
                    $dhedef = "../../img/slides";
                    $thumb = "../../img/slides/thumbs/";
                    $dyukle = move_uploaded_file($_FILES["slider_image2"]["tmp_name"], $temp . '/' . $r_yeniismi);
                    if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg" || $isim_parcala["1"] == "JPG" || $isim_parcala["1"] == "JPEG") {
                        $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "png") {
                        $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "gif") {
                        $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "bmp") {
                        $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                    }
                    $boyut = getimagesize($temp . '/' . $r_yeniismi);
                    $resimorani = 800 / $boyut[0];
                    $yeniyukseklik = $resimorani * $boyut[1];
                    $yeniresim = imagecreatetruecolor(800, $yeniyukseklik);
                    imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 800, $yeniyukseklik, $boyut[0], $boyut[1]);
                    $hedefdosya = $dhedef . "/" . $r_yeniismi;
                    imagejpeg($yeniresim, $hedefdosya, 100);

                    $boyutt = getimagesize($temp . '/' . $r_yeniismi);
                    $resimoranit = 140 / $boyutt[0];
                    $yeniyukseklikt = $resimoranit * $boyutt[1];
                    $yeniresimt = imagecreatetruecolor(140, $yeniyukseklikt);
                    imagecopyresampled($yeniresimt, $ndo, 0, 0, 0, 0, 140, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                    $hedefdosyat = $thumb . "/" . $r_yeniismi;
                    imagejpeg($yeniresimt, $hedefdosyat, 100);
                    if ($dyukle) {
                        $slider_update = $db->query("update slider set slider_title='$slider_title2', slider_description='$slider_description2', slider_name='$r_yeniismi', slider_link='$slider_link2', slider_order='$slider_order2', slider_status='$s_status2', websiteid='" . $_SESSION["admin"]["websiteid"] . "'  where slider_id='$IDSlider'");

                        if ($slider_update){
                            setcookie('testcookie', "true|Image Updated...", time() + 20, '/');
                            header("location: ../index.php?p=slider");
                        }
                        else{
                            setcookie('testcookie', "false|Image Not Updated...", time() + 20, '/');
                            header("location: ../index.php?p=slider");
                        }
                        unlink($temp."/".$r_yeniismi);
                    } else {
                        setcookie('testcookie', "false|File Not Uploaded...", time() + 20, '/');
                        header("location: ../index.php?p=slider");
                    }
                }
            }
        }
        else if ($process == "slider_delete"){
            $sliderID = $_GET["sliderID"];
            if (empty($sliderID) && !is_numeric($sliderID)) {
                header("Location: ../index.php");
            }
            else {
                $del_slider = $db->query("select * from slider where slider_id='$sliderID'");
                if ($del_slider->rowCount()){
                    foreach ($del_slider as $item) {
                        $sld = $item["slider_name"];
                    }
                }
                $url = "../../../".$site_name."/img/slides/thumbs/".$sld;
                $url2 = "../../../".$site_name."/img/slides/".$sld;
                $slider_delete = $db->query("delete from slider where slider_id='$sliderID'");
                if ($slider_delete->rowCount()) {
                    // header("Location: ../index.php"); //Notification
                    echo "true";
                    unlink($url);
                    unlink($url2);
                } else {
                    //header("Location: ../index.php");
                    echo "false";
                }
            }
        }
        elseif($process == "orderUpdate"){
            $order = $_POST["order"];
            $id = $_POST["id"];
            $order_update = $db->query("update slider set slider_order='$order' where slider_id='$id'");
        }
    }
    //Tabs
    else if ($pg == "tabs"){
        $process = $_GET["process"];
        $q = $_GET["q"];
        if ($process == "tabs_edit") {
            if ($q == "get") {
                $tabID = $_GET["tab_id"];
                $tabSelect = $db->query("select * from hp_tabs where tab_id='$tabID'");
                if ($tabSelect->rowCount()) {
                    foreach ($tabSelect as $tab_edit) {
                        $tab_data["tab_data"] = array(
                            "tab_name" => $tab_edit["tab_name"],
                            "tab_order" => $tab_edit["tab_order"],
                            "tab_status" => $tab_edit["tab_status"],
                            "tab_site"   => $tab_edit["websiteid"]
                        );
                        echo json_encode($tab_data);
                    }
                }
            }
            elseif ($q == "update") {
                $tabs_name2 = strip_tags(trim($_POST["tab_name2"]));
                $tabs_order2 = strip_tags(trim($_POST["tab_order2"]));
                $IDTab = strip_tags(trim($_POST["tabID"]));
                $tabs_seflink2 = permalink($_POST["tab_name2"]);
                $tab_status2 = $_POST["tab_status2"];
                $tab_site2 = $_POST["tab_site2"];

                $tabs_update = $db->query("update hp_tabs set tab_name='$tabs_name2', tab_order='$tabs_order2', tab_seflink='$tabs_seflink2', tab_status='$tab_status2' where tab_id='$IDTab'");
                if ($tabs_update->rowCount()){
                    echo "true";
                }
                /*
                if (isset($_POST["tabs_active2"])) {
                    $tabs_active2 = $_POST["tab_active2"];
                    if (empty($tabs_name2) || empty($tabs_order2) || empty($tabs_active2)) {
                        echo "empty";
                    }
                    else {

                        $tabs_update = $db->query("update hp_tabs set tab_name='$tabs_name2', tab_order='$tabs_order2', tab_seflink='$tabs_seflink2', tab_status='$tabs_active2', websiteid='" . $_SESSION["admin"]["websiteid"] . "'  where tab_id='$IDTab'");
                        if ($tabs_update->rowCount()) {
                            //header("Location: ../index.php");   //Notification
                            echo "true";
                        } else {
                            // header("Location: ../index.php");
                            echo $IDTab;
                        }
                    }
                }
                elseif (isset($_POST["tabs_passive2"])) {
                    $tabs_passive2 = $_POST["tab_passive2"];
                    if (empty($tabs_name2) || empty($tabs_order2) || empty($tabs_passive2)) {
                        echo "empty";
                    }
                    else {
                        $tabs_update2 = $db->query("update hp_tabs set tab_name='$tabs_name2', tab_order='$tabs_order2', tab_seflink='$tabs_seflink2', tab_status='$tabs_passive2', websiteid='" . $_SESSION["admin"]["websiteid"] . "'  where tab_id='$IDTab'");
                        if ($tabs_update2->rowCount()) {
                            //header("Location: ../index.php");   //Notification
                            echo "true";
                        } else {
                            // header("Location: ../index.php");
                            echo $IDTab;
                        }
                    }
                }
                */
            }
        }
        else if($process == "tabs_delete"){
            $tabID = $_GET["tabID"];
            if (empty($tabID) && !is_numeric($tabID)) {
                header("Location: ../index.php");
            }
            else {
                $tabs_delete = $db->query("delete from hp_tabs where tab_id='$tabID'");
                if ($tabs_delete->rowCount()) {
                    // header("Location: ../index.php"); //Notification
                    echo "true";
                } else {
                    //header("Location: ../index.php");
                    echo "false";
                }
            }
        }
        elseif($process == "tabs_add"){
            $tabs_name = strip_tags(trim($_POST["tab_name"]));
            $tab_site = $_POST["tab_site"];
            $_SESSION["admin"]["websiteid"] = $tab_site;
            $tabs_order = strip_tags(trim($_POST["tab_order"]));
            $tabs_seflink = permalink($_POST["tab_name"]);
            if (isset($_POST["tabs_active"])){
                $tabsSave = $db->query("insert into hp_tabs (tab_name,tab_order, tab_seflink,tab_status, websiteid) values ('$tabs_name', '$tabs_order', '$tabs_seflink', '" . $_POST["tabs_active"] . "', '".$_SESSION["admin"]["websiteid"]."')");
                echo "true";
            }
            elseif(isset($_POST["tabs_passive"])){
                $tabsSave = $db->query("insert into hp_tabs (tab_name,tab_order, tab_seflink,tab_status, websiteid) values ('$tabs_name', '$tabs_order', '$tabs_seflink', '" . $_POST["tabs_passive"] . "', '".$_SESSION["admin"]["websiteid"]."')");
                echo "true";
            }
        }
    }
    //Products
    else if ($pg == "products"){
        $process = $_GET["process"];
        $q = $_GET["q"];
        $pr = $_GET["pr"];
        if ($process == "product_add"){
            if ($pr == "pradd"){
                $productname = strip_tags(trim($_POST["productname"]));
                $generalname = strip_tags(trim($_POST["generalname"]));
                //$productlink = strip_tags(trim($_POST["productlink"]));
                $fromprice = strip_tags(trim($_POST["fromprice"]));
                $notes = strip_tags(trim($_POST["notes"]));
                $productinfo = strip_tags(trim($_POST["productinfo"]));
                $pr_cat_top_id = strip_tags(trim($_POST["pr_cat_top_id"]));

                $site = $_POST["site"];
                $_SESSION["admin"]["websiteid"] = $site;

                $edge = strip_tags(trim($_POST["edge"]));
                $room = strip_tags(trim($_POST["room"]));
                $material = strip_tags(trim($_POST["material"]));
                $finish = strip_tags(trim($_POST["finish"]));
                $traffic = strip_tags(trim($_POST["traffic"]));
                $wall = strip_tags(trim($_POST["wall"]));
                $colour = strip_tags(trim($_POST["colour"]));
                $colour2 = strip_tags(trim($_POST["colour2"]));
                $pop = strip_tags(trim($_POST["pop"]));
                $gap = strip_tags(trim($_POST["gap"]));

                $new = $_POST["at1"];
                $soffer = $_POST["soffer"];
                $freesample = $_POST["freesample"];
                $projects = $_POST["projects"];
                $show = $_POST["show"];

                $optionstype = strip_tags(trim($_POST["optionstype"]));
                $pertype = strip_tags(trim($_POST["pertype"]));

                $pr = $_POST["pr"];
                $sl = $_POST["sl"];
                $ch = $_POST["ch"];
                $z = $_POST["z"];

                $hiddenpr = $_POST["hiddenpr"];
                $hiddensl = $_POST["hiddensl"];
                $hiddench = $_POST["hiddench"];

                $rltpr = $_POST["rltpr"];
                $rltch = $_POST["rltch"];

                $rltprcnt = $_POST["rltprcnt"];
                $rltchcnt = $_POST["rltchcnt"];
                $sum = $_POST["sum"];

                json_encode($pr);
                json_encode($sl);
                json_encode($ch);
                json_encode($rltpr);
                json_encode($rltch);

//echo $new."     ".$soffer."     ".$freesample."     ".$projects."       ".$show;
// echo "pr:       ".$edge."            sl:         ".$room."        ch:     ".$material."            ".$finish."      ".$traffic."        ".$wall."        ".$colour."        ".$colour2."        ".$pop."        ".$gap."        ".$at1."        ".$soffer."     ".$freesample."     ".$projects."       ".$show."       ".$optionstype."        ".$pertype;

                if ($_SESSION["admin"]["websiteid"] == 1){
                    $IDTop = $db->query("select max(pr_id) as maxid from products where websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                    $maxID = $IDTop["maxid"] + 1;
                    $IDSZ = $db->query("select max(szid) as maxszid from products where websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                    $maxSzId = $IDSZ["maxszid"] + 1;
                }
                if ($_SESSION["admin"]["websiteid"] == 2){
                    $IDTop = $db->query("select max(szid) as maxid from products where websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                    $maxID = $IDTop["maxid"] + 1;
                    $IDSZ = $db->query("select max(szid) as maxszid from products where websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                    $maxSzId = $IDSZ["maxszid"] + 1;
                }

                $add_pr = $db->query("insert into products (pr_id,szid,generalname,name,fromprice,categoryid,productinfo,showsite,note,new,gap,room,soffer,freesample,colour,colour2,material,finish,wall,edge,traffic,projects,pop,optionstype,pertype,websiteid) values('$maxID','$maxSzId','$generalname','$productname','$fromprice','$pr_cat_top_id','$productinfo','$show','$notes','$new','$gap','$room','$soffer','$freesample','$colour','$colour2','$material','$finish','$wall','$edge','$traffic','$projects','$pop','$optionstype','$pertype','$site')");

                /*
                $query = $db->prepare("insert into `products` (`pr_id`,`szid`,`generalname`,`name`,`link`,`fromprice`,`categoryid`,`productinfo`,`showsite`,`note`,`new`,`gap`,`room`,`soffer`,`freesample`,`colour`,`colour2`,`material`,`finish`,`wall`,`edge`,`traffic`,`projects`,`pop`,`optionstype`,`pertype`,`websiteid`) values(:maxID,:maxSzId,:generalname,:productname,:productlink,:fromprice,:pr_cat_top_id,:productinfo,:show,:notes,:new,:gap,:room,:soffer,:freesample,:colour,:colour2,:material,:finish,:wall,:edge,:traffic,:projects,:pop,:optionstype,:pertype,:site_id)");
                $add_pr = $query->execute(
                        array(
                            ':maxID' => $maxID,
                            ':maxSzId' => $maxSzId,
                            ':generalname' => $generalname,
                            ':productname' => $productname,
                            ':productlink' => $productlink,
                            ':fromprice' => $fromprice,
                            ':pr_cat_top_id' => $pr_cat_top_id,
                            ':productinfo' => $productinfo,
                            ':show' => $show,
                            ':notes' => $notes,
                            ':new' => $new,
                            ':gap' => $gap,
                            ':room' => $room,
                            ':soffer' => $soffer,
                            ':freesample' => $freesample,
                            ':colour' => $colour,
                            ':colour2' => $colour2,
                            ':material' => $material,
                            ':finish' => $finish,
                            ':wall' => $wall,
                            ':edge' => $edge,
                            ':traffic' => $traffic,
                            ':projects' => $projects,
                            ':pop' => $pop,
                            ':optionstype' => $optionstype,
                            ':pertype' => $pertype,
                            ':site_id' => $site_id
                        )
                );
                */
                //$last_id = $db->lastInsertId();
                $last_id = $maxID;
                $gnrlup = $db->query("update products set generalid='$last_id' where pr_id='$last_id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                //$gnrlup = $db->query("update products set generalid='$maxSzId' where pr_id='$last_id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");

                if($add_pr){

                    $i=0;
                    $a=0;
                    $b=0;
                    $IDSZ = $db->query("select max(szid) as maxszid from products where websiteid='$site'")->fetch();
                    $SzId = $IDSZ["maxszid"];

                    while ($i < $hiddenpr - 2 && $a < $sum - 2 && $b < $hiddench - 3){

                        $p1 = $pr[$i];
                        $p2 = $pr[$i + 1];
                        $p3 = $pr[$i + 2];
                        $s1 = $sl[$a];
                        $s2 = $sl[$a + 1];
                        $s3 = $sl[$a + 2];
                        $ch1 = $ch[$b];
                        $ch2 = $ch[$b + 1];
                        $ch3 = $ch[$b + 2];
                        $ch4 = $ch[$b + 3];

                        $add_prSize = $db->query("insert into `product_sizes` (`productid`, `sizeid`, `price`, `wasprice`, `stock`, `new`, `soffer`, `note`, `vatid`, `show`, `isdummy`) values('$SzId','$s1','$p1','$p2','$s2','$ch3','$ch4','$p3','$s3','$ch1','$ch2')");
                        //$add_prSize = $db->query("insert into product_sizes (productid, sizeid, price, wasprice, stock, new, soffer, note, vatid, show, isdummy) values('$SzId','$s1','$p1','$p2','$s2','$ch3','$ch4','$p3','$s3','$ch1','$ch2')");

                        $i = $i + 3;
                        $a = $a + 3;
                        $b = $b + 4;

                    }
                    if ($add_prSize){

                        $c=0;
                        $d=0;
                        while ($c < $z && $d < $rltchcnt){
                            $rp1 = $rltpr[$c];
                            $rch1 = $rltch[$d];
                            $add_prRelated = $db->query("insert into `productsrelated` (`productid`, `relatedproductid`, `ismaintenance`, `websiteid`) values('$SzId','$rp1','$rch1','$site')");
                            //$add_prRelated = $db->query("insert into productsrelated (productid, relatedproductid, ismaintenance, websiteid) values('$SzId','$rp1','$rch1','$site_id')");
                            $c=$c+1;
                            $d=$d+1;
                        }
                        if ($add_prRelated){
                            setcookie('testcookie', "true|Product Sizes and Related Insert...", time() + 20, '/');
                            header("Location: ../index.php?p=products");
                        }
                        else{
                            setcookie('testcookie', "false|Product Related Not Insert...", time() + 20, '/');
                            header("Location: ../index.php?p=products");
                        }

                    }
                    else{
                        setcookie('testcookie', "false|Product Sizes Not Insert...", time() + 20, '/');
                        header("Location: ../index.php?p=products");
                    }

                }
                else{
                    setcookie('testcookie', "false|Product Not Insert...", time() + 20, '/');
                    header("Location: ../index.php?p=products");
                }

                /*
                                    if ($add_pr->rowCount()) {
                                         echo "true";
                                       $add_prSize = $db->query("insert into product_sizes(productid,sizeid,price,wasprice,stock,new,soffer,note,show,isdummy,websiteid) values('$last_id','$size_id','$price','$price2','$stock_status','$checkbox3','$checkbox4','$note1','$checkbox1','$checkbox2','".$_SESSION["admin"]["websiteid"]."')");
                                 }
                                    else {
                                        echo "false";
                                   }

                                    $add_prSize = $db->query("insert into product_sizes (`productid`, `sizeid`, `price`, `wasprice`, `stock`, `new`, `soffer`, `note`, `show`, `isdummy`, `websiteid`) values ('$last_id','$size_id','$price','$price2','$stock_status','$ch3','$ch4','$note1','$ch1','$ch2','".$_SESSION["admin"]["websiteid"]."')");

                                    $add_prRelated = $db->query("insert into productsrelated (`productid`, `relatedproductid`, `ismaintenance`, `websiteid`) values ('$last_id','$product_related','$checkbox80','".$_SESSION["admin"]["websiteid"]."')");
                */

            }
            elseif ($q == "add"){
                $sizes = $db->query("select * from sizes");
                $pr = $db->query("select * from products where websiteid='".$_SESSION["admin"]["websiteid"]."'");
                $cmbs = $db->query("select * from combos where menu='vat'");
                $size_data = array();
                $countsz=$sizes->rowCount();
                if ($countsz){
                    $k=0;
                    foreach ($sizes as $ss) {
                        $size_data["sizes"][$k] = array(
                            "id"      =>  $ss["id"],
                            "size"    =>  $ss["size"],
                            "cntsz"   =>  $countsz
                        );
                        $k++;
                    }
                }
                $cntpr=$pr->rowCount();
                if ($cntpr){
                    $t=0;
                    foreach ($pr as $rlt) {
                        $size_data["products"][$t] = array(
                            "pr_id"          =>      $rlt["pr_id"],
                            "name"           =>      $rlt["name"],
                            "cntpr"          =>      $cntpr
                        );
                        $t++;
                    }
                }
                $cntcmbs = $cmbs->rowCount();
                if ($cntcmbs){
                    $x=0;
                    foreach ($cmbs as $cm) {
                        $size_data["combos"][$x] = array(
                            "id"         =>      $cm["id"],
                            "name"       =>      $cm["name"],
                            "menu"       =>      $cm["menu"],
                            "sira"       =>      $cm["sira"],
                            "amount"     =>      $cm["amount"],
                            "countcmbs"  =>      $cntcmbs
                        );
                        $x++;
                    }
                }
                echo json_encode($size_data);
            }
            elseif ($q == "addsz"){
                $size = strip_tags(trim($_POST["size"]));
                $pr_id = strip_tags(trim($_POST["pr_id"]));
                $sz_id = strip_tags(trim($_POST["sz_id"]));
                $price = strip_tags(trim($_POST["price"]));
                $price2 = strip_tags(trim($_POST["price2"]));
                $stock_status = strip_tags(trim($_POST["stock_status"]));
                $vat = strip_tags(trim($_POST["vat"]));
                $note1 = strip_tags(trim($_POST["note1"]));
                $att = strip_tags(trim($_POST["at"]));
                $at = ($att == "checked") ? "1" : "0";
                $att2 = strip_tags(trim($_POST["at2"]));
                $at2 = ($att2 == "checked") ? "1" : "0";
                $att3 = strip_tags(trim($_POST["at3"]));
                $at3 = ($att3 == "checked") ? "1" : "0";
                $att4 = strip_tags(trim($_POST["at4"]));
                $at4 = ($att4 == "checked") ? "1" : "0";

                $last_ct = strip_tags(trim($_POST["last_ct"]));

                //echo "size: ".$size."   pr_id: ".$pr_id."           sz_id: ".$sz_id."   price: ".$price."       price2: ".$price2."     stock_status: ".$stock_status."         vat: ".$vat."       note1: ".$note1."       at: ".$at."     at2: ".$at2."   at3: ".$at3."       at4: ".$at4;

                $sizeInsert = $db->query("insert into `product_sizes`(`productid`, `sizeid`, `price`, `wasprice`, `stock`, `new`, `soffer`, `note`, `vatid`, `show`, `isdummy`) values('$sz_id','$size','$price','$price2','$stock_status','$at3','$at4','$note1','$vat','$at','$at2')");
                if ($sizeInsert->rowCount()){
                    $last_id = $db->lastInsertId();
                    echo $last_id."-".$last_ct;
                }
                else{
                    setcookie('testcookie', "false|Size Not Insert...", time() + 20, '/');
                    header("Location: ../index.php?p=products");
                }
            }
            elseif ($q == "addrlt"){
                $pr_id = strip_tags(trim($_POST["pr_id"]));
                $sz_id = strip_tags(trim($_POST["sz_id"]));
                $pr_site = strip_tags(trim($_POST["pr_site"]));
                $att = strip_tags(trim($_POST["at"]));
                $at = ($att == "checked") ? "1" : "0";

                $last_kk = strip_tags(trim($_POST["last_kk"]));

                $_SESSION['admin']['websiteid'] = $pr_site;

                //echo "size: ".$size."   pr_id: ".$pr_id."           sz_id: ".$sz_id."   price: ".$price."       price2: ".$price2."     stock_status: ".$stock_status."         vat: ".$vat."       note1: ".$note1."       at: ".$at."     at2: ".$at2."   at3: ".$at3."       at4: ".$at4;

                $relatedInsert = $db->query("insert into `productsrelated`(`productid`, `relatedproductid`, `ismaintenance`, `websiteid`) values('$sz_id','$pr_id','$at','".$_SESSION['admin']['websiteid']."')");
                if ($relatedInsert->rowCount()){
                    $last_id = $db->lastInsertId();
                    echo $last_id."-".$last_kk;
                }
                else{
                    setcookie('testcookie', "false|Related Product Not Insert...", time() + 20, '/');
                    header("Location: ../index.php?p=products");
                }
            }
        }
        elseif ($process == "image"){
            if (isset($_POST["image_add"])){
                $website = $_POST["website"];
                $_SESSION["admin"]["websiteid"] = $website;
                $productid = strip_tags(trim($_POST["pr_name"]));

                if ($_SESSION["admin"]["websiteid"] == 1){
                    $prname = $db->query("select * from products where pr_id='$productid' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                }
                elseif ($_SESSION["admin"]["websiteid"] == 2){
                    $slc = $db->query("select * from products where pr_id='$productid' and websiteid='".$_SESSION['admin']['websiteid']."'")->fetch();
                    $productid = $slc["szid"];
                    $prname = $db->query("select * from products where szid='$productid' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                }

                $productname = strip_tags(trim($prname["name"]));
                $dosya_sayi = count($_FILES['primage']['name']);

                for($a = 0; $a < $dosya_sayi; $a++) {
                    $rkaynagi = $_FILES["primage"]["tmp_name"];
                    $risimi = $_FILES["primage"]["name"][$a];
                    $rturu = $_FILES["primage"]["type"][$a];
                    $rboyutu = $_FILES["primage"]["size"][$a];
                    $isim_parcala = explode(".", $risimi);
                    $rasgeleisim = $productid."-".permalink($productname).rand(1,99)."-".$a;
                    $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];

                    //$temp = "../../../".$site_name."/img/temp";
                    $temp = "../../img/temp";
                    //$dhedef = "../../../".$site_name."/img/products";
                    $dhedef = "../../img/products";
                    //$thumb = "../../../".$site_name."/img/products/thumbs/";
                    $thumb = "../../img/products/thumbs/";
                    if (!file_exists($dhedef . "/" . $r_yeniismi) && !file_exists($thumb . $r_yeniismi)) {
                        $dyukle = move_uploaded_file($_FILES["primage"]["tmp_name"][$a], $temp . '/' . $r_yeniismi);
                        if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg") {
                            $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "png") {
                            $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "gif") {
                            $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "bmp") {
                            $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                        }
                        $boyut = getimagesize($temp . '/' . $r_yeniismi);
                        $resimorani = 563 / $boyut[0];
                        $yeniyukseklik = $resimorani * $boyut[1];
                        $yeniresim = imagecreatetruecolor(563, $yeniyukseklik);
                        imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 563, $yeniyukseklik, $boyut[0], $boyut[1]);
                        $hedefdosya = $dhedef . "/" . $r_yeniismi;
                        imagejpeg($yeniresim, $hedefdosya, 90);

                        $boyutt = getimagesize($temp . '/' . $r_yeniismi);
                        $resimoranit = 563 / $boyutt[0];
                        $yeniyukseklikt = $resimoranit * $boyutt[1];
                        $yeniresimt = imagecreatetruecolor(563, $yeniyukseklikt);
                        imagecopyresampled($yeniresimt, $ndo, 0, 0, 0, 0, 563, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                        $hedefdosyat = $thumb . "/" . $r_yeniismi;
                        imagejpeg($yeniresimt, $hedefdosyat, 90);
                        if ($dyukle) {
                            $stid = $_SESSION["admin"]["websiteid"];
                            $rkaydet = $db->query("insert into images (pr_id, imagename, websiteid) VALUES ('$productid', '$r_yeniismi', '$stid')");
                            setcookie('testcookie', "true|Image Insert...", time() + 20, '/');
                            header("location: ../index.php?p=products");

                        } else {
                            //echo "Dosya Yüklenemedi";
                            header("location: ../index.php?p=products");
                        }
                        unlink($temp."/".$r_yeniismi);
                    } else {
                        setcookie('testcookie', "false|File is Exists...", time() + 20, '/');
                        header("location: ../index.php?p=products");
                    }
                }

                /*
                $productname = strip_tags(trim($_POST["productname"]));
                $generalname = strip_tags(trim($_POST["generalname"]));
                $productlink = strip_tags(trim($_POST["productlink"]));
                $fromprice = strip_tags(trim($_POST["fromprice"]));
                $notes = strip_tags(trim($_POST["notes"]));

                $dosya_sayi = count($_FILES['productimage']['name']);
                for($a = 0; $a < $dosya_sayi; $a++) {
                    $rkaynagi = $_FILES["productimage"]["tmp_name"];
                    $risimi = $_FILES["productimage"]["name"][$a];
                    $rturu = $_FILES["productimage"]["type"][$a];
                    $rboyutu = $_FILES["productimage"]["size"][$a];
                    $isim_parcala = explode(".", $risimi);
                    $rasgeleisim = permalink($productname)."-".$a;
                    $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];

                    $temp = "../../img/temp";
                    $dhedef = "../../img/products";
                    $thumb = "../../img/products/thumbs/";
                    if (!file_exists($dhedef . "/" . $r_yeniismi) && !file_exists($thumb . $r_yeniismi)) {
        $dyukle = move_uploaded_file($_FILES["productimage"]["tmp_name"][$a], $temp . '/' . $r_yeniismi);
                        if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg") {
                            $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "png") {
                            $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "gif") {
                            $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "bmp") {
                            $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                        }
                        $boyut = getimagesize($temp . '/' . $r_yeniismi);
                        $resimorani = 274 / $boyut[0];
                        $yeniyukseklik = $resimorani * $boyut[1];
                        $yeniresim = imagecreatetruecolor(274, $yeniyukseklik);
                        imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 274, $yeniyukseklik, $boyut[0], $boyut[1]);
                        $hedefdosya = $dhedef . "/" . $r_yeniismi;
                        imagejpeg($yeniresim, $hedefdosya, 90);

                        $boyutt = getimagesize($temp . '/' . $r_yeniismi);
                        $resimoranit = 274 / $boyutt[0];
                        $yeniyukseklikt = $resimoranit * $boyutt[1];
                        $yeniresimt = imagecreatetruecolor(274, $yeniyukseklikt);
                        imagecopyresampled($yeniresimt, $ndo, 0, 0, 0, 0, 274, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                        $hedefdosyat = $thumb . "/" . $r_yeniismi;
                        imagejpeg($yeniresimt, $hedefdosyat, 90);
                        if ($dyukle) {
                            $last = $db->query("select * from products order by pr_id desc limit 0, 1");
                            if ($last->rowCount()){
                                foreach ($last as $item) {
                                    $last_id = $item["pr_id"];

                $rkaydet = $db->query("insert into images (pr_id, imagename) VALUES ('$last_id', '$r_yeniismi')");
                            setcookie('testcookie', "true|Product Insert...", time() + 20, '/');
                            header("location: ../index.php?p=products");
                                }
                            }
                        } else {
                            //echo "Dosya Yüklenemedi";
                            header("location: ../index.php?p=products");
                        }
                        unlink($temp."/".$r_yeniismi);
                    } else {
                        setcookie('testcookie', "false|Dosya Mevcut...", time() + 20, '/');
                        header("location: ../index.php?p=products");
                    }
                }
                */
            }
            elseif ($pr == "primage"){
                if (isset($_POST["imageID"])){
                    $id = $_POST["imageID"];
                    $image_site = $_POST["image_site"];
                    $_SESSION["admin"]["websiteid"] = $image_site;
                    if ($_SESSION["admin"]["websiteid"] == 1){
                        $pr = $db->query("select * from products where pr_id='$id' and websiteid='$image_site'")->fetch();
                        echo $pr["pr_id"];
                    }
                    elseif ($_SESSION["admin"]["websiteid"] == 2){
                        $pr = $db->query("select * from products where szid='$id' and websiteid='$image_site'")->fetch();
                        echo $pr["szid"];
                    }
                }
                else{
                    $imgID = $_GET["imageID"];
                    $image_site = $_GET["image_site"];
                    $_SESSION["admin"]["websiteid"] = $image_site;
                    $image_data = array();

                    if ($_SESSION["admin"]["websiteid"] == 1){
                        $slpr = $db->query("select * from products where pr_id='$imgID' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                        $imgID = $slpr["pr_id"];
                        $szid = $slpr["szid"];
                    }
                    elseif ($_SESSION["admin"]["websiteid"] == 2){
                        $slpr = $db->query("select * from products where pr_id='$imgID' and websiteid='1'")->fetch();
                        $szid = $slpr["szid"];
                        $slpr2 = $db->query("select * from products where szid='$szid' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                        $imgID = $slpr2["szid"];
                    }

                    $img = $db->query("select * from images where pr_id='$imgID' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                    $tt = 0;

                    foreach ($img as $im) {
                        $image_data["images"][$tt] = array(
                            "id"        => utf8_decode(utf8_encode($im["id"])),
                            "pr_id"     => utf8_decode(utf8_encode($im["pr_id"])),
                            "imagename" => utf8_decode(utf8_encode($im["imagename"])),
                            "productname" => utf8_decode(utf8_encode($slpr["name"]))
                        );
                        $tt++;
                    }

                    echo json_encode($image_data);

                }
            }
            elseif ($pr == "image_edit"){
                if (isset($_POST["image_update"])){
                    if (!isset($_POST["imagename"])){
                        $prid2 = strip_tags(trim($_POST["pr_name2"]));
                        $name = $db->query("select * from products where pr_id='$prid2' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                        $productname2 = $name["name"];
                        $productImgId = strip_tags(trim($_POST["productImgId"]));
                        $selectImg = $db->query("select * from images where pr_id='$productImgId' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                        $img_count = $selectImg->rowCount();
                        $a = 0;

                        if (!empty($_FILES["primage2"]["name"])){

                            foreach ($_FILES["primage2"]["name"] as $file){
                                $productname2 = strip_tags(trim($_POST["imagename"]));
                                $productImgId = strip_tags(trim($_POST["productImgId"]));
                                $selectImg = $db->query("select * from images where pr_id='$productImgId' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                                $img_count = $selectImg->rowCount();

                                $rkaynagi = $_FILES["primage2"]["tmp_name"][$a];
                                $risimi = $_FILES["primage2"]["name"][$a];
                                $rturu = $_FILES["primage2"]["type"][$a];
                                $rboyutu = $_FILES["primage2"]["size"][$a];
                                $isim_parcala = explode(".", $risimi);
                                $rasgeleisim = $productImgId . "-" . permalink($productname2) . rand(1,99) . "_" . $a;
                                $r_yeniismi2 = $rasgeleisim . "." . $isim_parcala["1"];

                                //$temp = "../../../".$site_name."/img/temp";
                                $temp = "../../img/temp";
                                //$dhedef = "../../../".$site_name."/img/products";
                                $dhedef = "../../img/products";
                                //$thumb = "../../../".$site_name."/img/products/thumbs/";
                                $thumb = "../../img/products/thumbs/";
                                $isim_parcala = explode(".", $risimi);
                                $rasgeleisim = $productImgId."-".permalink($productname2) . rand(1,99) . "-" . $a;
                                $r_yeniismi3 = $rasgeleisim . "." . $isim_parcala["1"];

                                if ((!file_exists($dhedef . "/" . $r_yeniismi2) && !file_exists($dhedef . $r_yeniismi2)) || (!file_exists($dhedef . "/" . $r_yeniismi3) && !file_exists($thumb . $r_yeniismi3))) {
                                    $sayi = rand(1, 99);
                                    $isim_parcala = explode(".", $risimi);
                                    $rasgeleisim = $prid2."-".permalink($productname2) . rand(1,99) . "-" . $a . $sayi;
                                    $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];
                                    $dyukle = move_uploaded_file($_FILES["primage2"]["tmp_name"][$a], $temp . '/' . $r_yeniismi);
                                    if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg") {
                                        $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                                    } elseif ($isim_parcala["1"] == "png") {
                                        $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                                    } elseif ($isim_parcala["1"] == "gif") {
                                        $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                                    } elseif ($isim_parcala["1"] == "bmp") {
                                        $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                                    }
                                    $boyut = getimagesize($temp . '/' . $r_yeniismi);
                                    $resimorani = 563 / $boyut[0];
                                    $yeniyukseklik = $resimorani * $boyut[1];
                                    $yeniresim = imagecreatetruecolor(563, $yeniyukseklik);
                                    imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 563, $yeniyukseklik, $boyut[0], $boyut[1]);
                                    $hedefdosya = $dhedef . "/" . $r_yeniismi;
                                    imagejpeg($yeniresim, $hedefdosya, 90);

                                    $boyutt = getimagesize($temp . '/' . $r_yeniismi);
                                    $resimoranit = 563 / $boyutt[0];
                                    $yeniyukseklikt = $resimoranit * $boyutt[1];
                                    $yeniresimt = imagecreatetruecolor(563, $yeniyukseklikt);
                                    imagecopyresampled($yeniresimt, $ndo, 0, 0, 0, 0, 563, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                                    $hedefdosyat = $thumb . "/" . $r_yeniismi;
                                    imagejpeg($yeniresimt, $hedefdosyat, 90);
                                    $stid = $_SESSION["admin"]["websiteid"];
                                    $img_update = $db->query("insert into images(pr_id,imagename,websiteid) values('$productImgId','$r_yeniismi','$stid')");
                                    unlink($temp . "/" . $r_yeniismi);

                                    setcookie('testcookie', "true|Product Image Inserted and Image Name Updated...", time() + 20, '/');
                                    header("location: ../index.php?p=products");
                                } else {
                                    setcookie('testcookie', "false|File is Exists...", time() + 20, '/');
                                    header("location: ../index.php?p=products");
                                }
                                $a++;
                            }

                        }
                        else{
                            $productname2 = strip_tags(trim($_POST["imagename"]));
                            $productImgId = strip_tags(trim($_POST["productImgId"]));
                            $selectImg = $db->query("select * from images where pr_id='$productImgId' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                            $img_count = $selectImg->rowCount();
                            $i = 0;
                            foreach ($selectImg as $item){
                                $rand = rand(1, 99);
                                $img = $item["imagename"];
                                $ex = explode(".", $img);
                                $pr_name2 = $productImgId . "-" . permalink($productname2) . "-" . $rand . $i . "." . $ex[1];
                                $img_update = $db->query("update images set imagename='$pr_name2' where pr_id='".$item['pr_id']."' and id='".$item['id']."'");
                                if ($img_update){
                                    //rename("../../../".$site_name."/img/products/thumbs/" . $img, "../../../".$site_name."/img/products/thumbs/" . $pr_name2);
                                    rename("../../img/products/thumbs/" . $img, "../../img/products/thumbs/" . $pr_name2);
                                    //rename("../../../".$site_name."/img/products/" . $img, "../../../".$site_name."/img/products/" . $pr_name2);
                                    rename("../../img/products/" . $img, "../../img/products/" . $pr_name2);
                                    $up = "true";
                                }
                                else{
                                    $up = "false";
                                }
                                $i++;
                            }
                            if ($up == "true"){
                                setcookie('testcookie', "true|Image Name Updated...", time() + 20, '/');
                                header("location: ../index.php?p=products");
                            }
                            else{
                                setcookie('testcookie', "false|Image Name Not Updated...", time() + 20, '/');
                                header("location: ../index.php?p=products");
                            }

                        }









                        /*
                        $productname2 = strip_tags(trim($_POST["imagename"]));
                        $productImgId = strip_tags(trim($_POST["productImgId"]));
                        $selectImg = $db->query("select * from images where pr_id='$productImgId' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                        $img_count = $selectImg->rowCount();
                        $i = 0;
                        foreach ($selectImg as $item){
                            $rand = rand(0, 1000);
                            $img = $item["imagename"];
                            $ex = explode(".", $img);
                            $pr_name2 = permalink($productname2) . "-" . $rand . $i . "." . $ex[1];
                            $img_update = $db->query("update images set imagename='$pr_name2' where pr_id='".$item['pr_id']."' and id='".$item['id']."'");
                            if ($img_update){
                                //rename("../../../".$site_name."/img/products/thumbs/" . $img, "../../../".$site_name."/img/products/thumbs/" . $pr_name2);
                                rename("../../img/products/thumbs/" . $img, "../../img/products/thumbs/" . $pr_name2);
                                //rename("../../../".$site_name."/img/products/" . $img, "../../../".$site_name."/img/products/" . $pr_name2);
                                rename("../../img/products/" . $img, "../../img/products/" . $pr_name2);
                                $up = "true";
                            }
                            else{
                                $up = "false";
                            }
                            $i++;
                        }
                        if ($up == "true"){
                            setcookie('testcookie', "true|Image Name Updated...", time() + 20, '/');
                            header("location: ../index.php?p=products");
                        }
                        else{
                            setcookie('testcookie', "false|Image Name Not Updated...", time() + 20, '/');
                            header("location: ../index.php?p=products");
                        }
                        */
                    }
                    else{
                        $prid2 = strip_tags(trim($_POST["pr_name2"]));
                        $name = $db->query("select * from products where pr_id='$prid2' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                        $productname2 = $name["name"];
                        $productImgId = strip_tags(trim($_POST["productImgId"]));
                        $selectImg = $db->query("select * from images where pr_id='$productImgId' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                        $img_count = $selectImg->rowCount();
                        $a = 0;

                        if (!empty($_FILES["primage2"]["name"])){
                            foreach ($_FILES["primage2"]["name"] as $file){
                                $rkaynagi = $_FILES["primage2"]["tmp_name"][$a];
                                $risimi = $_FILES["primage2"]["name"][$a];
                                $rturu = $_FILES["primage2"]["type"][$a];
                                $rboyutu = $_FILES["primage2"]["size"][$a];
                                $isim_parcala = explode(".", $risimi);
                                $rasgeleisim = $productImgId."-".permalink($productname2) . rand(1,99) . "_" . $a;
                                $r_yeniismi2 = $rasgeleisim . "." . $isim_parcala["1"];

                                //$temp = "../../../".$site_name."/img/temp";
                                $temp = "../../img/temp";
                                //$dhedef = "../../../".$site_name."/img/products";
                                $dhedef = "../../img/products";
                                //$thumb = "../../../".$site_name."/img/products/thumbs/";
                                $thumb = "../../img/products/thumbs/";
                                $isim_parcala = explode(".", $risimi);
                                $rasgeleisim = $productImgId."-".permalink($productname2) . rand(1,99) . "-" . $a;
                                $r_yeniismi3 = $rasgeleisim . "." . $isim_parcala["1"];

                                if ((!file_exists($dhedef . "/" . $r_yeniismi2) && !file_exists($dhedef . $r_yeniismi2)) || (!file_exists($dhedef . "/" . $r_yeniismi3) && !file_exists($thumb . $r_yeniismi3))) {
                                    $sayi = rand(1, 99);
                                    $isim_parcala = explode(".", $risimi);
                                    $rasgeleisim = $productImgId."-".permalink($productname2) . rand(1,99) . "-" . $a . $sayi;
                                    $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];
                                    $dyukle = move_uploaded_file($_FILES["primage2"]["tmp_name"][$a], $temp . '/' . $r_yeniismi);
                                    if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg") {
                                        $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                                    } elseif ($isim_parcala["1"] == "png") {
                                        $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                                    } elseif ($isim_parcala["1"] == "gif") {
                                        $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                                    } elseif ($isim_parcala["1"] == "bmp") {
                                        $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                                    }
                                    $boyut = getimagesize($temp . '/' . $r_yeniismi);
                                    $resimorani = 563 / $boyut[0];
                                    $yeniyukseklik = $resimorani * $boyut[1];
                                    $yeniresim = imagecreatetruecolor(563, $yeniyukseklik);
                                    imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 563, $yeniyukseklik, $boyut[0], $boyut[1]);
                                    $hedefdosya = $dhedef . "/" . $r_yeniismi;
                                    imagejpeg($yeniresim, $hedefdosya, 90);

                                    $boyutt = getimagesize($temp . '/' . $r_yeniismi);
                                    $resimoranit = 563 / $boyutt[0];
                                    $yeniyukseklikt = $resimoranit * $boyutt[1];
                                    $yeniresimt = imagecreatetruecolor(563, $yeniyukseklikt);
                                    imagecopyresampled($yeniresimt, $ndo, 0, 0, 0, 0, 563, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                                    $hedefdosyat = $thumb . "/" . $r_yeniismi;
                                    imagejpeg($yeniresimt, $hedefdosyat, 90);
                                    $stid = $_SESSION["admin"]["websiteid"];
                                    $img_update = $db->query("insert into images(pr_id,imagename,websiteid) values('$productImgId','$r_yeniismi','$stid')");
                                    unlink($temp . "/" . $r_yeniismi);

                                    setcookie('testcookie', "true|Product Image Inserted and Image Name Updated...", time() + 20, '/');
                                    header("location: ../index.php?p=products");
                                } else {
                                    setcookie('testcookie', "false|File is Exists...", time() + 20, '/');
                                    header("location: ../index.php?p=products");
                                }
                                $a++;
                            }
                        }
                        else{
                            $i = 0;
                            foreach ($selectImg as $item){
                                $rand = rand(1, 99);
                                $img = $item["imagename"];
                                $ex = explode(".", $img);
                                $pr_name2 = $productImgId."-".permalink($productname2) . "-" . $rand . $i . "." . $ex[1];
                                $img_update = $db->query("update images set imagename='$pr_name2' where pr_id='".$item['pr_id']."' and id='".$item['id']."'");
                                if ($img_update){
                                    //rename("../../../".$site_name."/img/products/thumbs/" . $img, "../../../".$site_name."/img/products/thumbs/" . $pr_name2);
                                    rename("../../img/products/thumbs/" . $img, "../../img/products/thumbs/" . $pr_name2);
                                    //rename("../../../".$site_name."/img/products/" . $img, "../../../".$site_name."/img/products/" . $pr_name2);
                                    rename("../../img/products/" . $img, "../../img/products/" . $pr_name2);
                                    $up = "true";
                                }
                                else{
                                    $up = "false";
                                }
                                $i++;
                            }
                            if ($up == "true"){
                                setcookie('testcookie', "true|Image Name Updated...", time() + 20, '/');
                                header("location: ../index.php?p=products");
                            }
                            else{
                                setcookie('testcookie', "false|Image Name Not Updated...", time() + 20, '/');
                                header("location: ../index.php?p=products");
                            }
                        }
                    }
                }
            }
        }
        elseif ($process == "product_edit"){
            if ($pr == "prupdate"){
                $productname2 = strip_tags(trim($_POST["productname2"]));
                $generalname2 = strip_tags(trim($_POST["generalname2"]));
                //$productlink2 = strip_tags(trim($_POST["productlink2"]));
                $fromprice2 = strip_tags(trim($_POST["fromprice2"]));
                $notes2 = strip_tags(trim($_POST["notes2"]));
                $productinfo2 = strip_tags(trim($_POST["productinfo2"]));
                $pr_cat_top_id2 = strip_tags(trim($_POST["pr_cat_top_id2"]));
                $product_hidden = strip_tags(trim($_POST["product_hidden"]));
                $sz_hidden = strip_tags(trim($_POST["sz_hidden"]));
                $product_img = strip_tags(trim($_POST["product_img"]));

                $edge2 = strip_tags(trim($_POST["edge2"]));
                $room2 = strip_tags(trim($_POST["room2"]));
                $material2 = strip_tags(trim($_POST["material2"]));
                $finish2 = strip_tags(trim($_POST["finish2"]));
                $traffic2 = strip_tags(trim($_POST["traffic2"]));
                $wall2 = strip_tags(trim($_POST["wall2"]));
                $colour4 = strip_tags(trim($_POST["colour4"]));
                $colour25 = strip_tags(trim($_POST["colour25"]));
                $pop2 = strip_tags(trim($_POST["pop2"]));
                $gap2 = strip_tags(trim($_POST["gap2"]));

                $optionstype2 = strip_tags(trim($_POST["optionstype2"]));
                $pertype2 = strip_tags(trim($_POST["pertype2"]));

                $pr_site = $_POST["pr_site"];
                $_SESSION["admin"]["websiteid"] = $pr_site;

                $pr2 = $_POST["prd2"];
                $sl2 = $_POST["slc2"];
                $stk2 = $_POST["stock2"];
                $vat2 = $_POST["vt2"];
                $ch2 = $_POST["check"];
                $edgech2 = $_POST["chedge2"];
                $rltsl2 = $_POST["slrlt2"];
                $rltch2 = $_POST["chrlt2"];
                $rlthd2 = $_POST["hdrlt2"];

                $a = $_POST["a"];
                $b = $_POST["b"];
                $c = $_POST["c"];
                $d = $_POST["d"];
                $r = $_POST["r"];
                $p = $_POST["p"];


                $f = $_POST["f"];
                $g = $_POST["g"];
                $h = $_POST["h"];

                json_encode($pr2);
                json_encode($sl2);
                json_encode($stk2);
                json_encode($vat2);
                json_encode($ch2);
                json_encode($edgech2);
                json_encode($rltsl2);
                json_encode($rltch2);
                json_encode($rlthd2);

                $z = 0;
                $ed1 = $edgech2[$z];
                $ed2 = $edgech2[$z + 1];
                $ed3 = $edgech2[$z + 2];
                $ed4 = $edgech2[$z + 3];
                $ed5 = $edgech2[$z + 4];

                $slc = $db->query("select * from products where szid='$sz_hidden' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();

                $upgnrl = $db->query("update products set generalname='$generalname2' where szid='".$slc["szid"]."'");

                $products_update = $db->query("update `products` set `name`='$productname2', `fromprice`='$fromprice2', `categoryid`='$pr_cat_top_id2', `productinfo`='$productinfo2', `showsite`='$ed5', `note`='$notes2', `new`='$ed1', `gap`='$gap2', `room`='$room2', `soffer`='$ed2', `freesample`='$ed3', `colour`='$colour4', `colour2`='$colour25', `material`='$material2', `finish`='$finish2', `wall`='$wall2', `edge`='$edge2', `traffic`='$traffic2', `projects`='$ed4', `pop`='$pop2', `optionstype`='$optionstype2', `pertype`='$pertype2' where `pr_id`='".$slc["pr_id"]."' and `websiteid`='".$_SESSION["admin"]["websiteid"]."'");

                if ($products_update){

                    $k = 0; // input
                    $m = 0; // ss select
                    $n = 0; // checkbox
                    $pp = 0; // stk select
                    $w = 0; // vat select

                    //$sz = $db->query("select * from products where pr_id='$product_hidden' and `websiteid`='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                    $sz = $db->query("select * from products where szid='$sz_hidden' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();

                    $IDsz = $sz["szid"];

                    while ($k < $a - 3 && $m < $b && $pp < $r && $w < $d && $n < $c - 3) {
                        $p12 = $pr2[$k];
                        $p22 = $pr2[$k + 1];
                        $p32 = $pr2[$k + 2];
                        $p42 = $pr2[$k + 3];
                        $s12 = $sl2[$m];
                        $stk22 = $stk2[$pp];
                        $vt2 = $vat2[$w];
                        $ch12 = $ch2[$n];
                        $ch22 = $ch2[$n + 1];
                        $ch32 = $ch2[$n + 2];
                        $ch42 = $ch2[$n + 3];

                        $up_prSize = $db->query("update `product_sizes` set `sizeid`='$s12', `price`='$p12', `wasprice`='$p22', `stock`='$stk22', `new`='$ch32', `soffer`='$ch42', `note`='$p32', `vatid`='$vt2', `show`='$ch12', `isdummy`='$ch22' where `productid`='$IDsz' and `id`='$p42'");

                        if ($up_prSize){
                            $szUp = "true";
                        }
                        else{
                            $szUp = "false";
                        }
                        $k = $k + 4;
                        $n = $n + 4;
                        $m = $m + 1;
                        $pp = $pp + 1;
                        $w = $w + 1;
                    }
                    if ($szUp == "true") {
                        $ttt = 0;
                        $rrr = 0;
                        $ddd = 0;
                        while ($ttt < $f && $rrr < $g && $ddd < $h) {
                            $rsl22 = $rltsl2[$ttt];
                            $rch22 = $rltch2[$rrr];
                            $rlthd22 = $rlthd2[$ddd];
                            $up_prRelated = $db->query("update `productsrelated` set `relatedproductid`='$rsl22', `ismaintenance`='$rch22' where `productid`='$IDsz' and `id`='$rlthd22'");
                            if ($up_prRelated) {
                                $rltUp = "true";
                            } else {
                                $rltUp = "false";
                            }
                            $ttt = $ttt + 1;
                            $rrr = $rrr + 1;
                            $ddd = $ddd + 1;
                        }
                        if ($rltUp == "true") {
                            setcookie('testcookie', "true|Product Updated...", time() + 20, '/');
                            header("location: ../index.php?p=products");
                        }
                        else {
                            setcookie('testcookie', "false|Product Not Updated...", time() + 20, '/');
                            header("location: ../index.php?p=products");
                        }
                    }
                    else {
                        setcookie('testcookie', "false|Product Not Updated...", time() + 20, '/');
                        header("location: ../index.php?p=products");
                    }
                }
                else{
                    setcookie('testcookie', "false|Product Not Updated...", time() + 20, '/');
                    header("location: ../index.php?p=products");
                }
            }
            elseif ($q == "get"){

                if (isset($_GET["pr_id"])){
                    $productID = $_GET["pr_id"];
                }
                elseif (isset($_GET["productID"])){
                    $productID = $_GET["productID"];
                }

                if (isset($_GET["webID"])){
                    $_SESSION['admin']['websiteid'] = $_GET["webID"];
                }
                elseif (!isset($_GET["webID"])){
                    $_SESSION['admin']['websiteid'] = 1;
                }

                $site_ids = $db->query("select * from websites where websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                $site_name = $site_ids["websitename"];
                $site_id = $site_ids["websiteid"];

                if ($_SESSION["admin"]["websiteid"] == 1){
                    $prSelect = $db->query("select * from products where pr_id='$productID' and websiteid='".$_SESSION['admin']['websiteid']."'");
                }
                elseif ($_SESSION["admin"]["websiteid"] == 2){
                    $slc = $db->query("select * from products where szid='$productID' and websiteid='".$_SESSION['admin']['websiteid']."'")->fetch();
                    $productID = $slc["szid"];
                    $prSelect = $db->query("select * from products where szid='$productID' and websiteid='".$_SESSION['admin']['websiteid']."'");
                }

                //$relatedSelect = $db->query("select * from productsrelated where productid='$productID'");

                $pr_img = $db->query("select * from images where pr_id='$productID' and websiteid='".$_SESSION["admin"]["websiteid"]."'");

                $sizes = $db->query("select * from sizes");
                $relateds = $db->query("select * from productsrelated where websiteid='".$_SESSION['admin']['websiteid']."'");
                $prdct = $db->query("select * from products where websiteid='".$_SESSION['admin']['websiteid']."'");
                $cmbs = $db->query("select * from combos where menu='vat'");
                $allcmbs = $db->query("select * from combos");
                $product_data = array();
                $prcnt=$prSelect->rowCount();
                if ($prcnt){
                    $s=0;
                    foreach ($prSelect as $product_edit){
                        $product_data["product_data"] = array(
                            "pr_id"       => utf8_decode(utf8_encode($product_edit["pr_id"])),
                            "szID"        => utf8_decode(utf8_encode($product_edit["szid"])),
                            "name"        => utf8_decode(utf8_encode($product_edit["name"])),
                            "fromprice"   => utf8_decode(utf8_encode($product_edit["fromprice"])),
                            "productinfo" => utf8_decode(utf8_encode($product_edit["productinfo"])),
                            "note"        => utf8_decode(utf8_encode($product_edit["note"])),
                            "categoryid"  => utf8_decode(utf8_encode($product_edit["categoryid"])),
                            "generalname" => utf8_decode(utf8_encode($product_edit["generalname"])),
                            "show"        => utf8_decode(utf8_encode($product_edit["showsite"])),
                            "new"         => utf8_decode(utf8_encode($product_edit["new"])),
                            "gap"         => utf8_decode(utf8_encode($product_edit["gap"])),
                            "room"        => utf8_decode(utf8_encode($product_edit["room"])),
                            "soffer"      => utf8_decode(utf8_encode($product_edit["soffer"])),
                            "freesample"  => utf8_decode(utf8_encode($product_edit["freesample"])),
                            "colour"      => utf8_decode(utf8_encode($product_edit["colour"])),
                            "colour2"     => utf8_decode(utf8_encode($product_edit["colour2"])),
                            "material"    => utf8_decode(utf8_encode($product_edit["material"])),
                            "finish"      => utf8_decode(utf8_encode($product_edit["finish"])),
                            "wall"        => utf8_decode(utf8_encode($product_edit["wall"])),
                            "edge"        => utf8_decode(utf8_encode($product_edit["edge"])),
                            "traffic"     => utf8_decode(utf8_encode($product_edit["traffic"])),
                            "projects"    => utf8_decode(utf8_encode($product_edit["projects"])),
                            "pop"         => utf8_decode(utf8_encode($product_edit["pop"])),
                            "optionstype" => utf8_decode(utf8_encode($product_edit["optionstype"])),
                            "pertype"     => utf8_decode(utf8_encode($product_edit["pertype"])),
                            "websiteid"   => utf8_decode(utf8_encode($product_edit["websiteid"])),
                            "sessionid"   => utf8_decode(utf8_encode($_SESSION["admin"]["websiteid"])),
                            "websitename" => utf8_decode(utf8_encode($site_name)),
                            "prcnt"       => utf8_decode(utf8_encode($prcnt))
                        );

                        $szID = $product_data["product_data"]["szID"];
                        $sizeSelect = $db->query("select * from product_sizes where productid='$szID'");
                        $countsz=$sizeSelect->rowCount();
                        if ($countsz){
                            foreach ($sizeSelect as $size){
                                $product_data["size_data"][$s] = array(
                                    "pr_sizeid" => utf8_decode(utf8_encode($size["id"])),
                                    "productid" => utf8_decode(utf8_encode($size["productid"])),
                                    "sizeid"   => utf8_decode(utf8_encode($size["sizeid"])),
                                    "price"    => utf8_decode(utf8_encode($size["price"])),
                                    "wasprice" => utf8_decode(utf8_encode($size["wasprice"])),
                                    "stock"    => utf8_decode(utf8_encode($size["stock"])),
                                    "new"      => utf8_decode(utf8_encode($size["new"])),
                                    "soffer"   => utf8_decode(utf8_encode($size["soffer"])),
                                    "note"     => utf8_decode(utf8_encode($size["note"])),
                                    "vatid"    => utf8_decode(utf8_encode($size["vatid"])),
                                    "show"     => utf8_decode(utf8_encode($size["show"])),
                                    "isdummy"  => utf8_decode(utf8_encode($size["isdummy"])),
                                    "countsz"  => count($product_data["size_data"]) + 1
                                );
                                $sizeid=$product_data["size_data"][$s]["sizeid"];
                                $sizeSlt = $db->query("select * from sizes where id='$sizeid'");
                                $countsize=$sizeSlt->rowCount();
                                if ($countsize){
                                    foreach ($sizeSlt as $sz){
                                        $product_data["size_pr"][$s] = array(
                                            "id"    =>      utf8_decode(utf8_encode($sz["id"])),
                                            "size"  =>      utf8_decode(utf8_encode($sz["size"]))
                                        );
                                        $vatid=$product_data["size_data"][$s]["vatid"];
                                        $vatSlct=$db->query("select * from combos where id='$vatid'");
                                        $countvat=$vatSlct->rowCount();
                                        if ($countvat){
                                            foreach ($vatSlct as $vt) {
                                                $product_data["vat"][$s] = array(
                                                    "id"      =>      utf8_decode(utf8_encode($vt["id"])),
                                                    "name"    =>      utf8_decode(utf8_encode($vt["name"]))
                                                );
                                                $s++;
                                            }
                                        }
                                    }
                                }

                            }
                        }

                        //$rltID = $product_data["product_data"]["pr_id"];
                        $rltID = $product_data["product_data"]["szID"];
                        $relatedSelect = $db->query("select * from productsrelated where productid='$rltID' and websiteid='".$_SESSION['admin']['websiteid']."'");
                        $countrlt=$relatedSelect->rowCount();
                        if ($countrlt){
                            $r=0;
                            foreach ($relatedSelect as $related){
                                $product_data["related_data"][$r] = array(
                                    "related_id"         =>  utf8_decode(utf8_encode($related["id"])),
                                    "productid"          =>  utf8_decode(utf8_encode($related["productid"])),
                                    "relatedproductid"   =>  utf8_decode(utf8_encode($related["relatedproductid"])),
                                    "ismaintenance"      =>  utf8_decode(utf8_encode($related["ismaintenance"])),
                                    "countrltd"          =>  count($product_data["related_data"]) + 1
                                );
                                $prid=$product_data["related_data"][$r]["relatedproductid"];
                                if ($_SESSION["admin"]["websiteid"] == 1){
                                    $pr_related=$db->query("select * from products where pr_id='$prid' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                                }
                                elseif ($_SESSION["admin"]["websiteid"] == 2){
                                    $slc = $db->query("select * from products where szid='$prid' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                                    $prid = $slc["szid"];
                                    $pr_related=$db->query("select * from products where szid='$prid' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                                }
                                $countproduct=$pr_related->rowCount();
                                if ($countproduct){
                                    foreach ($pr_related as $item){
                                        if ($_SESSION["admin"]["websiteid"] == 1){
                                            $product_data["product_related"][$r] = array(
                                                "pr_id"     =>      utf8_decode(utf8_encode($item["pr_id"])),
                                                "name"      =>      utf8_decode(utf8_encode($item["name"])),
                                                "cntrltpr"  =>      utf8_decode(utf8_encode($countproduct))
                                            );
                                            $r++;
                                        }
                                        elseif ($_SESSION["admin"]["websiteid"] == 2){
                                            $product_data["product_related"][$r] = array(
                                                "pr_id"     =>      utf8_decode(utf8_encode($item["szid"])),
                                                "name"      =>      utf8_decode(utf8_encode($item["name"])),
                                                "cntrltpr"  =>      utf8_decode(utf8_encode($countproduct))
                                            );
                                            $r++;
                                        }
                                    }
                                }
                            }
                        }


                    }
                }

                $countsz=$sizes->rowCount();
                if ($countsz){
                    $k=0;
                    foreach ($sizes as $ss) {
                        $product_data["sizes"][$k] = array(
                            "id"      =>  utf8_decode(utf8_encode($ss["id"])),
                            "size"    =>  utf8_decode(utf8_encode($ss["size"])),
                            "cntsz"   =>  utf8_decode(utf8_encode($countsz))
                        );
                        $k++;
                    }
                }
                $countrelated=$relateds->rowCount();
                if ($countrelated){
                    $x=0;
                    foreach ($relateds as $related) {
                        $product_data["related"][$x] = array(
                            "id"                =>      utf8_decode(utf8_encode($related["id"])),
                            "productid"         =>      utf8_decode(utf8_encode($related["productid"])),
                            "relatedproductid"  =>      utf8_decode(utf8_encode($related["relatedproductid"])),
                            "ismaintenance"     =>      utf8_decode(utf8_encode($related["ismaintenance"])),
                            "cntrlt"            =>      utf8_decode(utf8_encode($countrelated))
                        );
                        $x++;
                    }
                }
                $cntpr=$prdct->rowCount();
                if ($cntpr){
                    $m=0;
                    foreach ($prdct as $product){
                        $product_data["products"][$m] = array(
                            "pr_id"       => utf8_decode(utf8_encode($product["pr_id"])),
                            "name"        => utf8_decode(utf8_encode($product["name"])),
                            "link"        => utf8_decode(utf8_encode($product["link"])),
                            "fromprice"   => utf8_decode(utf8_encode($product["fromprice"])),
                            "productinfo" => utf8_decode(utf8_encode($product["productinfo"])),
                            "note"        => utf8_decode(utf8_encode($product["note"])),
                            "categoryid"  => utf8_decode(utf8_encode($product["categoryid"])),
                            "generalname" => utf8_decode(utf8_encode($product["generalname"])),
                            "cntpr"       => utf8_decode(utf8_encode($cntpr))
                        );
                        $m++;
                    }
                }
                $cntimg=$pr_img->rowCount();
                if ($cntimg){
                    $v=0;
                    foreach ($pr_img as $img) {
                        $product_data["images"][$v] = array(
                            "id"         =>      utf8_decode(utf8_encode($img["id"])),
                            "pr_id"      =>      utf8_decode(utf8_encode($img["pr_id"])),
                            "imagename"  =>      utf8_decode(utf8_encode($img["imagename"])),
                            "websitename" =>     utf8_decode(utf8_encode($site_name)),
                            "countimg"   =>      utf8_decode(utf8_encode($cntimg))
                        );
                        $v++;
                    }
                }
                $cntcmbs=$cmbs->rowCount();
                if ($cntcmbs){
                    $x=0;
                    foreach ($cmbs as $cm) {
                        $product_data["combos"][$x] = array(
                            "id"         =>      utf8_decode(utf8_encode($cm["id"])),
                            "name"       =>      utf8_decode(utf8_encode($cm["name"])),
                            "menu"       =>      utf8_decode(utf8_encode($cm["menu"])),
                            "sira"       =>      utf8_decode(utf8_encode($cm["sira"])),
                            "amount"     =>      utf8_decode(utf8_encode($cm["amount"])),
                            "countcmbs"  =>      utf8_decode(utf8_encode($cntcmbs))
                        );
                        $x++;
                    }
                }

                echo json_encode($product_data);

            }
            elseif ($q == "getProduct"){
                $id = $_POST["id"];
                $_SESSION['admin']['websiteid'] = $id;
                $allpr = $db->query("select * from products where websiteid='".$_SESSION['admin']['websiteid']."'");
                if ($allpr->rowCount()){
                    ?>
                    <option disabled selected> Select Product </option>
                    <?php
                    foreach ($allpr as $item){
                        if ($id == 1) {
                            ?>
                            <option value="<?php echo $item["pr_id"] ?>"><?php echo $item["name"]; ?></option>
                            <?php
                        }
                        elseif ($id == 2){
                            ?>
                            <option value="<?php echo $item["szid"] ?>"><?php echo $item["name"]; ?></option>
                            <?php
                        }
                    }
                }
            }
            elseif ($q == "getRelated"){
                $id = $_POST["site_id"];
                $_SESSION["admin"]["websiteid"] = $id;
                $allprrlt = $db->query("select * from products where websiteid='".$_SESSION["admin"]["websiteid"]."'");
                echo '<option selected disabled> Select </option>';
                if ($allprrlt->rowCount()){
                    foreach ($allprrlt as $product) {
                        ?>
                        <option value="<?php echo $product["pr_id"]; ?>">
                            <?php echo $product["name"]; ?>
                        </option>
                        <?php
                    }
                }
            }
            elseif ($q == "need"){
                ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Products Sizes</h4>
                </div>

                <div class="table-responsive" id="size2">
                    <table class="table" id="size-table2">
                        <thead>
                        <tr>
                            <th>Size</th>
                            <th>Price(£)</th>
                            <th>Old Price (£)</th>
                            <th>Stock</th>
                            <th>VatID</th>
                            <th>Show</th>
                            <th>Dummy</th>
                            <th>New</th>
                            <th>Special</th>
                            <th>Note</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <select class="form-control select2me" id="sz_options22" name="sz_options22[]">
                                            <option selected disabled>Select</option>
                                            <?php
                                            $sizeSelect = $db->query("select * from sizes");
                                            if ($sizeSelect->rowCount()){
                                                foreach ($sizeSelect as $size) {
                                                    ?>
                                                    <option value="<?php echo $size["id"]; ?>">
                                                        <?php echo $size["size"]; ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </th>
                            <th>
                                <input class="form-control input-small" name="price12[]" type="text" id="price12" value="" size="2" aria-invalid="false" style="width: 75%;">
                            </th>
                            <th>
                                <input class="form-control input-small" name="price22[]" type="text" id="price22" value="" size="2" aria-invalid="false" style="width: 75%;">
                            </th>
                            <th>
                                <select name="stock_status2" id="stock_status2" class="col-md-9 select2me" style="height: 33px;">
                                    <option selected disabled>Select</option>
                                    <option value="1">In Stock</option>
                                    <option value="2">Low Stock</option>
                                    <option value="3">Out of Stock</option>
                                </select>
                            </th>
                            <th>
                                <select name="vatid2[]" id="vatid2" class="col-md-9 select2me" style="height: 33px;">
                                    <option selected disabled>Select</option>
                                    <?php
                                    $vt = $db->query("select * from combos where menu='vat'");
                                    if ($vt->rowCount()){
                                        foreach ($vt as $vat) {
                                            ?>
                                            <option value="<?php echo $vat["id"];  ?>"><?php  echo $vat["name"];  ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </th>
                            <th>
                                <div class="col-md-4">
                                    <div class="md-checkbox" style="margin-top: 5px;">
                                        <input type="checkbox" id="product_showa" name="product_showa[]" class="md-check">
                                        <label for="product_showa">
                                            <span class="inc"></span>
                                            <span class="check"></span>
                                            <span class="box"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="col-md-4">
                                    <div class="md-checkbox" style="margin-top: 5px;">
                                        <input type="checkbox" id="dummya" name="dummya[]" class="md-check">
                                        <label for="dummya">
                                            <span class="inc"></span>
                                            <span class="check"></span>
                                            <span class="box"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="col-md-4">
                                    <div class="md-checkbox" style="margin-top: 5px;">
                                        <input type="checkbox" id="new-sizea" name="new-sizea[]" class="md-check">
                                        <label for="new-sizea">
                                            <span class="inc"></span>
                                            <span class="check"></span>
                                            <span class="box"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div class="col-md-4">
                                    <div class="md-checkbox" style="margin-top: 5px;">
                                        <input type="checkbox" id="speciala" name="speciala[]" class="md-check">
                                        <label for="speciala">
                                            <span class="inc"></span>
                                            <span class="check"></span>
                                            <span class="box"></span>
                                        </label>
                                    </div>
                                </div>
                            </th>

                            <th>
                                <input class="form-control input-small" id="note12" name="note12[]" type="text" value="" size="5" maxlength="100">
                            </th>
                            <th>
                                <a href="#" id="plus2" style="margin-right: 20px;">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>
                            </th>
                            <th>
                                <input class="form-control input-small" name="pr2" type="hidden" id="hiddenpr2" value="" />
                                <input class="form-control input-small" name="sl2" type="hidden" id="hiddensl2" value="" />
                                <input class="form-control input-small" name="ch2" type="hidden" id="hiddench2" value="" />
                            </th>
                        </tr>



                        </tbody>
                    </table>
                </div>

                <hr>
                <?php
            }
        }
        elseif ($process == "product_delete"){
            $prID = $_GET["prID"];
            if (empty($prID) && !is_numeric($prID)){
                header("Location: ../index.php");
            }
            else {
                $sl = $db->query("select * from products where pr_id='$prID' and websiteid='1'")->fetch();
                $szid = $sl["szid"];
                $product_delete = $db->query("delete from products where pr_id='$prID'");
                $product_delete2 = $db->query("delete from products where szid='$szid'");
                if ($product_delete){
                    $prsize_del = $db->query("delete from product_sizes where productid='$szid'");
                    if ($prsize_del){
                        $rltpr_del = $db->query("delete from productsrelated where productid='$szid'");
                        if ($rltpr_del){
                            $imgSlct = $db->query("select * from images where pr_id='$prID'");
                            $delimgcnt = $imgSlct->rowCount();
                            if ($delimgcnt){
                                foreach ($imgSlct as $img){
                                    $imgid = $img["id"];
                                    $imgdel = $db->query("delete from images where pr_id='$prID' and id='$imgid'");
                                    $imgurl = $img["imagename"];
                                    $url = "../../img/products/".$imgurl;
                                    $thumbs = "../../img/products/thumbs/".$imgurl;
                                    unlink($url);
                                    unlink($thumbs);
                                }
                                if ($imgdel){
                                    setcookie('testcookie', "true|Product, Sizes, Related and Images Deleted...", time() + 20, '/');
                                    header("Location: ../index.php?p=products");
                                }
                                else{
                                    setcookie('testcookie', "false|Product, Sizes, Related and Images Not Deleted...", time() + 20, '/');
                                    header("Location: ../index.php?p=products");
                                }
                            }
                            else{
                                setcookie('testcookie', "false|Product, product sizes, product related deleted but product does not have images...", time() + 20, '/');
                                header("Location: ../index.php?p=products");
                            }
                        }
                        else{
                            setcookie('testcookie', "false|Product and product sizes deleted but product does not have related...", time() + 20, '/');
                            header("Location: ../index.php?p=products");
                        }
                    }
                    else{
                        setcookie('testcookie', "false|Product deleted but product does not have sizes...", time() + 20, '/');
                        header("Location: ../index.php?p=products");
                    }
                }
                else {
                    setcookie('testcookie', "false|Product Not Deleted...", time() + 20, '/');
                    header("Location: ../index.php?p=products");
                }
            }
        }
        elseif ($process == "product_dup"){
            if (isset($_POST["dup"])){
                $pr_id = $_POST["dup_id"];
                $wbst = $_POST["website"];
                $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                $szid = $pr["szid"];
                $sizeSelect = $db->query("select * from product_sizes where productid='$szid'");
                $relatedSelect = $db->query("select * from productsrelated where productid='$szid' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                $pr_img = $db->query("select * from images where pr_id='$pr_id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                $sizes = $db->query("select * from sizes");
                $prdct = $db->query("select * from products where websiteid='".$_SESSION["admin"]["websiteid"]."'");
                $cmbs = $db->query("select * from combos where menu='vat'");
                $allcmbs = $db->query("select * from combos");
                $product_data = array();
                if ($pr->rowCount()){
                    foreach ($pr as $item) {
                        $generalid = $item["generalid"];
                        $prnm = $item["name"];
                        $gnrl = $item["generalname"];
                        $link = $item["link"];
                        $frmprc = $item["fromprice"];
                        $catid = $item["categoryid"];
                        $prinfo = $item["productinfo"];
                        $nt = $item["note"];
                        $nw = $item["new"];
                        $gp = $item["gap"];
                        $rm = $item["room"];
                        $sf = $item["soffer"];
                        $frsmp = $item["freesample"];
                        $cl = $item["colour"];
                        $cl2 = $item["colour2"];
                        $mtr = $item["material"];
                        $fnsh = $item["finish"];
                        $wll = $item["wall"];
                        $ed = $item["edge"];
                        $trf = $item["traffic"];
                        $pro = $item["projects"];
                        $pp = $item["pop"];
                        $opt = $item["optionstype"];
                        $perty = $item["pertype"];

                        $db->query("insert into products (generalid,generalname,name,link,fromprice,categoryid,productinfo,note,new,gap,room,soffer,freesample,colour,colour2,material,finish,wall,edge,traffic,projects,pop,optionstype,pertype,websiteid) values ('$generalid','$gnrl','$prnm','$link','$frmprc','$catid','$prinfo','$nt','$nw','$gp','$rm','$sf','$frsmp','$cl','$cl2','$mtr','$fnsh','$wll','$ed','$trf','$pro','$pp','$opt','$perty','$wbst')");

                    }
                    $last_id = $db->lastInsertId();
                    $countsz=$sizeSelect->rowCount();
                    if ($countsz) {
                        $s=0;
                        foreach ($sizeSelect as $size) {
                            $product_data["size_data"][$s] = array(
                                "pr_sizeid" =>  $size["id"],
                                "productid" => $size["productid"],
                                "sizeid"   => $size["sizeid"],
                                "price"    => $size["price"],
                                "wasprice" => $size["wasprice"],
                                "stock"    => $size["stock"],
                                "new"      => $size["new"],
                                "soffer"   => $size["soffer"],
                                "note"     => $size["note"],
                                "vatid"    => $size["vatid"],
                                "show"     => $size["show"],
                                "isdummy"  => $size["isdummy"],
                                "countsz"  => $countsz
                            );
                            $sizeid = $product_data["size_data"][$s]["sizeid"];
                            $price = $product_data["size_data"][$s]["price"];
                            $wasprice = $product_data["size_data"][$s]["wasprice"];
                            $stock = $product_data["size_data"][$s]["stock"];
                            $new = $product_data["size_data"][$s]["new"];
                            $soffer = $product_data["size_data"][$s]["soffer"];
                            $note = $product_data["size_data"][$s]["note"];
                            $vatid = $product_data["size_data"][$s]["vatid"];
                            $show = $product_data["size_data"][$s]["show"];
                            $isdummy = $product_data["size_data"][$s]["isdummy"];

                            $add_prSize = $db->query("insert into product_sizes (`productid`, `sizeid`, `price`, `wasprice`, `stock`, `new`, `soffer`, `note`,`vatid`, `show`, `isdummy`, `websiteid`) values ('$last_id','$sizeid','$price','$wasprice','$stock','$new','$soffer','$note','$vatid','$show','$isdummy','$wbst')");

                            $s++;
                        }
                        $cntrelated = $relatedSelect->rowCount();
                        if ($cntrelated){
                            $r=0;
                            foreach ($relatedSelect as $related){
                                $product_data["related_data"][$r] = array(
                                    "related_id"         =>  $related["id"],
                                    "productid"          =>  $related["productid"],
                                    "relatedproductid"   =>  $related["relatedproductid"],
                                    "ismaintenance"      =>  $related["ismaintenance"],
                                    "countrlt"           =>  $cntrelated
                                );
                                $rltprdctid = $product_data["related_data"][$r]["relatedproductid"];
                                $ismaintenance = $product_data["related_data"][$r]["ismaintenance"];

                                $add_prRelated = $db->query("insert into productsrelated (`productid`, `relatedproductid`, `ismaintenance`, `websiteid`) values ('$last_id','$rltprdctid','$ismaintenance','$wbst')");
                                $r++;
                            }
                            /*
                            $cntimg = $pr_img->rowCount();
                            if ($cntimg){
                                $v=0;
                                foreach ($pr_img as $img) {
                                    $product_data["images"][$v] = array(
                                        "id"         =>      $img["id"],
                                        "pr_id"      =>      $img["pr_id"],
                                        "imagename"  =>      $img["imagename"],
                                        "countimg"   =>      $cntimg
                                    );

                                $imgname = $product_data["images"][$v]["imagename"];
                                $rkaydet = $db->query("insert into images (pr_id, imagename) values ('$last_id', '$imgname')");
                                $v++;
                                }
                            }
                            */
                            setcookie('testcookie', "true|Product Duplicate...", time() + 20, '/');
                            header("Location: ../index.php?p=products");
                        }
                    }
                }
            }
        }
        elseif ($process == "img_delete"){
            $imgID = $_GET["imgID"];
            if (empty($imgID) && !is_numeric($imgID)) {
                header("Location: ../index.php");
            }
            else {
                $del_img = $db->query("select * from images where id='$imgID' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                if ($del_img->rowCount()){
                    foreach ($del_img as $item) {
                        $img = $item["imagename"];
                    }
                }
                $img_delete = $db->query("delete from images where id='$imgID' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
                if ($img_delete) {
                    echo "true";
                    unlink("../../img/products/".$img);
                    unlink("../../img/products/thumbs/".$img);
                } else {
                    echo "false";
                }
            }
        }
        elseif ($process == "PrSizeDelete"){
            $prID = $_GET["prid"];
            $selectsz = $_GET["selectsz"];
            if (empty($prID) && !is_numeric($prID) && empty($selectsz) && !is_numeric($selectsz)) {
                header("Location: ../index.php");
            }
            else{
                $del = $db->query("select * from products where pr_id='$prID' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                $sz = $db->query("select * from product_sizes where productid='".$del['szid']."' and sizeid='$selectsz'");
                if ($sz->rowCount()){
                    foreach ($sz as $item){
                        $sz_delete = $db->query("delete from product_sizes where productid='".$del['szid']."' and sizeid='$selectsz'");
                        if ($sz_delete){
                            echo 1;
                        }
                        else{
                            echo 0;
                        }
                    }
                }
                else{
                    echo 0;
                }
            }
        }
        elseif ($process == "PrRelatedDelete"){
            $prID = $_GET["prid"];
            $selectsz = $_GET["selectsz"];
            if (empty($prID) && !is_numeric($prID) && empty($selectsz) && !is_numeric($selectsz)) {
                header("Location: ../index.php");
            }
            else{
                $del = $db->query("select * from products where pr_id='$prID' and websiteid='".$_SESSION["admin"]["websiteid"]."'")->fetch();
                $sz = $db->query("select * from productsrelated where productid='".$del['szid']."' and relatedproductid='$selectsz'");
                if ($sz->rowCount()){
                    foreach ($sz as $item){
                        $sz_delete = $db->query("delete from productsrelated where productid='".$del['szid']."' and relatedproductid='$selectsz'");
                        if ($sz_delete){
                            echo 1;
                        }
                        else{
                            echo 0;
                        }
                    }
                }
                else{
                    echo 0;
                }
            }
        }
    }
    //Combos
    else if ($pg == "combos"){

        $process = $_GET["process"];

        if ($process == "combos_add"){
            $type = strip_tags(trim($_POST["type"]));
            $menuname = strip_tags(trim($_POST["menuname"]));
            comboAdd($type,$menuname);
        }
        elseif ($process == "comboGet"){
            $type = strip_tags(trim($_POST["type"]));
            comboGet($type);
        }
        elseif ($process == "comboUpdate") {

            $id = strip_tags(trim($_POST["id"]));
            $value = strip_tags(trim($_POST["value"]));
            $column = strip_tags(trim($_POST["column"]));

            //$updateCombo = $db->query("update combos set '$column'='$value' where id='$id'");
            if($column=="name"){
                $updateCombo = $db->query("update combos set name='$value' where id='$id'");
                if($updateCombo){
                    echo "true";
                }else{
                    echo"false";
                }
            }elseif($column=="sira"){
                $updateCombo = $db->query("update combos set sira='$value' where id='$id'");
                if($updateCombo){
                    echo "true";
                }else{
                    echo"false";
                }
            }elseif($column=="amount"){
                $updateCombo = $db->query("update combos set amount='$value' where id='$id'");
                if($updateCombo){
                    echo "true";
                }else{
                    echo"false";
                }
            }
        }
        elseif($process == "comboSelect"){
            $type = strip_tags(trim($_POST["type"]));
            comboSelect($type);
        }
        elseif ($process == "comboDelete") {
            $comboID = strip_tags(trim($_POST["id"]));
            $comboPost = strip_tags(trim($_POST["postSecure"]));
            if ($comboPost == "idDelete") {
                $combos_delete = $db->query("delete from combos where id='$comboID'");
                if ($combos_delete) {
                    echo 1;
                }
                else {
                    echo 0;
                }
            }
        }
    }
    //Sizes
    else if ($pg == "sizes"){
        $process = $_GET["process"];
        $q = $_GET["q"];
        if ($process == "size_add"){
            $size = strip_tags(trim($_POST["size"]));
            $sizeot = strip_tags(trim($_POST["sizeot"]));
            $width = strip_tags(trim($_POST["width"]));
            $height = strip_tags(trim($_POST["height"]));
            $thickness = strip_tags(trim($_POST["thickness"]));
            $volume = strip_tags(trim($_POST["volume"]));
            $area = strip_tags(trim($_POST["area"]));
            $weight = strip_tags(trim($_POST["weight"]));
            $itemunit = strip_tags(trim($_POST["itemunit"]));
            $sizetype = strip_tags(trim($_POST["sizetype"]));
            $sizeunit = strip_tags(trim($_POST["sizeunit"]));
            $qtyunit = strip_tags(trim($_POST["qtyunit"]));

            $size_add = $db->query("insert into sizes(size,sizeot,width,height,thickness,area,volume,weight,sizetype,sizeunit,itemunit,qtyunit) values('$size','$sizeot','$width','$height','$thickness','$volume','$area','$weight','$itemunit','$sizetype','$sizeunit','$qtyunit')");
            if ($size_add->rowCount()){
                $lstsize_id = $db->lastInsertId();
                $sz = $db->query("select * from sizes where id='$lstsize_id'");
                if ($sz->rowCount()){
                    foreach ($sz as $item) {
                        echo $item["size"]."-".$lstsize_id;
                    }
                }
                setcookie('testcookie', "true|Size Saved.........", time() + 20, '/');
            }
            else{
                setcookie('testcookie', "false|Size Not Saved.........", time() + 20, '/');
            }
        }
        elseif ($process == "size_edit"){
            if ($q == "get"){
                $sizeID = $_GET["size_id"];
                $sizeSelect = $db->query("select * from sizes where id='$sizeID'");
                if ($sizeSelect->rowCount()) {
                    foreach ($sizeSelect as $size_edit) {
                        $sizes_data["sizes_data"] = array(
                            "id" => $size_edit["id"],
                            "size" => $size_edit["size"],
                            "sizeot" => $size_edit["sizeot"],
                            "width" => $size_edit["width"],
                            "height" => $size_edit["height"],
                            "thickness" => $size_edit["thickness"],
                            "area" => $size_edit["area"],
                            "volume" => $size_edit["volume"],
                            "weight" => $size_edit["weight"],
                            "sizetype" => $size_edit["sizetype"],
                            "sizeunit" => $size_edit["sizeunit"],
                            "itemunit" => $size_edit["itemunit"],
                            "qtyunit" => $size_edit["qtyunit"]
                        );
                        echo json_encode($sizes_data);
                    }
                }
            }
            elseif ($q == "update"){
                $size2 = strip_tags(trim($_POST["size2"]));
                $sizeot2 = strip_tags(trim($_POST["sizeot2"]));
                $width2 = strip_tags(trim($_POST["width2"]));
                $height2 = strip_tags(trim($_POST["height2"]));
                $thickness2 = strip_tags(trim($_POST["thickness2"]));
                $area2 = strip_tags(trim($_POST["area2"]));
                $volume2 = strip_tags(trim($_POST["volume2"]));
                $weight2 = strip_tags(trim($_POST["weight2"]));
                $itemunit2 = strip_tags(trim($_POST["itemunit2"]));
                $sizetype2 = strip_tags(trim($_POST["sizetype2"]));
                $sizeunit2 = strip_tags(trim($_POST["sizeunit2"]));
                $qtyunit2 = strip_tags(trim($_POST["qtyunit2"]));
                $size_id = $_POST["size_id"];
                $size_update = $db->query("update sizes set size='$size2',sizeot='$sizeot2',width='$width2',height='$height2',thickness='$thickness2',area='$area2',volume='$volume2',weight='$weight2',sizetype='$sizetype2',sizeunit='$sizeunit2',itemunit='$itemunit2',qtyunit='$qtyunit2' where id='$size_id'");
                if ($size_update->rowCount()){
                    echo 1;
                }
                else{
                    echo 0;
                }
            }
        }
        elseif ($process == "size_delete"){
            $sizeID = $_GET["sizeID"];
            if (empty($sizeID) && !is_numeric($sizeID)) {
                header("Location: ../index.php");
            }
            else {
                $size_delete = $db->query("delete from sizes where id='$sizeID'");
                if ($size_delete) {
                    // header("Location: ../index.php"); //Notification
                    echo 1;
                }
                else {
                    //header("Location: ../index.php");
                    echo 0;
                }
            }
        }
    }
    //Settings
    else if ($pg == "settings"){
        $process = $_GET["process"];
        if ($process == "settings_edit") {
            if ($_POST["edit-btn"]) {
                $phone = strip_tags(trim($_POST["phone"]));
                $title = strip_tags(trim($_POST["title"]));
                $description = strip_tags(trim($_POST["description"]));
                $keywords = strip_tags(trim($_POST["keywords"]));
                $facebook = strip_tags(trim($_POST["facebook"]));
                $twitter = strip_tags(trim($_POST["twitter"]));
                $pinterest = strip_tags(trim($_POST["pinterest"]));
                $instagram = strip_tags(trim($_POST["instagram"]));
                $footer = strip_tags(trim($_POST["footer"]));
                $mail = strip_tags(trim($_POST["mail"]));
                $section = trim($_POST["editor2"]);
                $section_title = strip_tags(trim($_POST["section-title"]));
                $settings_id = strip_tags(trim($_POST["settings_edit"]));
                $settings_hidden = strip_tags(trim($_POST["settings_edit2"]));
                $settings_site = strip_tags(trim($_POST["settings_site"]));
                $settings_name = permalink($title);

                $_SESSION["admin"]["websiteid"] = $settings_site;

                $isim_parcala = explode(".", $settings_hidden);
                $r_yeniismi = $settings_name.".".$isim_parcala["1"];
                $settings_image = $_FILES["web_logo"]["name"];
                if ($settings_image == null){
                    $settings_update = $db->query("update hp_settings set logo='$r_yeniismi', phone='$phone', title='$title', description='$description', keywords='$keywords', facebook='$facebook', twitter='$twitter', pinterest='$pinterest', instagram='$instagram', footer='$footer', mail='$mail', section='$section', section_title='$section_title' where websiteid='".$_SESSION["admin"]["websiteid"]."'");
                    rename("../../../".$site_name."/img/".$settings_hidden, "../../../".$site_name."/img/".$r_yeniismi);
                    if ($settings_update){
                        setcookie('testcookie', "true|Settings Updated...", time() + 20, '/');
                        header("location: ../index.php?p=settings");
                    }
                    else{
                        setcookie('testcookie', "false|Settings Not Updated...", time() + 20, '/');
                        header("location: ../index.php?p=settings");
                    }
                }
                else{
                    unlink("../../../".$site_name."/img/thumbs/".$settings_hidden);
                    unlink("../../..".$site_name."/img/".$settings_hidden);
                    $risimi = $_FILES["web_logo"]["name"];
                    //$s_name = strip_tags(trim($_POST["slider_name"]));
                    $rturu = $_FILES["web_logo"]["type"];
                    $rboyutu = $_FILES["web_logo"]["size"];
                    $isim_parcala = explode(".", $risimi);
                    $rasgeleisim = permalink($title);
                    $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];

                    $temp = "../../../".$site_name."/img/temp";
                    $dhedef = "../../../".$site_name."/img";
                    $thumb = "../../../".$site_name."/img/thumbs";
                    $dyukle = move_uploaded_file($_FILES["web_logo"]["tmp_name"], $temp . '/' . $r_yeniismi);
                    if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg" || $isim_parcala["1"] == "JPG" || $isim_parcala["1"] == "JPEG") {
                        $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                        $ndot = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "png") {
                        $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                        $ndot = imagecreatefrompng($temp . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "gif") {
                        $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                        $ndot = imagecreatefromgif($temp . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "bmp") {
                        $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                        $ndot = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                    }
                    $boyut = getimagesize($temp . '/' . $r_yeniismi);
                    $resimorani = 140 / $boyut[0];
                    $yeniyukseklik = $resimorani * $boyut[1];
                    $yeniresim = imagecreatetruecolor(140, $yeniyukseklik);
                    imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 140, $yeniyukseklik, $boyut[0], $boyut[1]);
                    $hedefdosya = $dhedef . "/" . $r_yeniismi;
                    imagejpeg($yeniresim, $hedefdosya, 100);

                    if ($dyukle) {

                        $settings_update = $db->query("update hp_settings set logo='$r_yeniismi', phone='$phone', title='$title', description='$description', keywords='$keywords', facebook='$facebook', twitter='$twitter', pinterest='$pinterest', instagram='$instagram', footer='$footer', mail='$mail', section='$section', section_title='$section_title' where websiteid='".$_SESSION["admin"]["websiteid"]."'");

                        if ($settings_update->rowCount()){
                            setcookie('testcookie', "true|Settings Updated...", time() + 20, '/');
                            header("location: ../index.php?p=settings");
                        }
                        else{
                            setcookie('testcookie', "false|Settings Not Updated...", time() + 20, '/');
                            header("location: ../index.php?p=settings");
                        }
                        unlink($temp."/".$r_yeniismi);
                    } else {
                        setcookie('testcookie', "false|Dosya Yüklenemedi...", time() + 20, '/');
                        header("location: ../index.php?p=settings");
                    }
                }

            }
        }
    }
    //Pages
    else if ($pg == "pages"){
        $process = $_GET["process"];
        if ($process == "addPage"){
            if ($_POST["pageAdd"]){
                $pageImg = $_FILES["pageImg"]["name"];
                $pageTitle = strip_tags(trim($_POST["pageTitle"]));
                $title = strip_tags(trim($_POST["title"]));
                $pageDescription = strip_tags(trim($_POST["pageDescription"]));
                $pageText = strip_tags(trim($_POST["pageText"]));
                $pageStatus = $_POST["pageStatus"] == "on" ? "1" : "0";
                $website = $_POST["website"];
                $_SESSION["admin"]["websiteid"] = $website;

                if (!empty($pageImg)){
                    $rturu = $_FILES["pageImg"]["type"];
                    $rboyutu = $_FILES["pageImg"]["size"];
                    $isim_parcala = explode(".", $pageImg);
                    $pageName = permalink($pageTitle);
                    $r_yeniismi = $pageName . "." . $isim_parcala[1];

                    $dhedef = "../../img/";
                    $thumb = "../../img/thumbs/";
                    if (!file_exists($dhedef.$r_yeniismi) && !file_exists($thumb.$r_yeniismi)) {
                        $dyukle = move_uploaded_file($_FILES["pageImg"]["tmp_name"], $dhedef . '/' . $r_yeniismi);
                        if ($isim_parcala[1] == "jpg" || $isim_parcala[1] == "jpeg") {
                            $ndo = imagecreatefromjpeg($dhedef . "/" . $r_yeniismi);
                            $ndot = imagecreatefromjpeg($dhedef . "/" . $r_yeniismi);
                        } elseif ($isim_parcala[1] == "png") {
                            $ndo = imagecreatefrompng($dhedef . "/" . $r_yeniismi);
                            $ndot = imagecreatefrompng($dhedef . "/" . $r_yeniismi);
                        } elseif ($isim_parcala[1] == "gif") {
                            $ndo = imagecreatefromgif($dhedef . "/" . $r_yeniismi);
                            $ndot = imagecreatefromgif($dhedef . "/" . $r_yeniismi);
                        } elseif ($isim_parcala[1] == "bmp") {
                            $ndo = imagecreatefromwbmp($dhedef . "/" . $r_yeniismi);
                            $ndot = imagecreatefromwbmp($dhedef . "/" . $r_yeniismi);
                        }
                        $boyut = getimagesize($dhedef . '/' . $r_yeniismi);
                        $resimorani = 700 / $boyut[0];
                        $yeniyukseklik = $resimorani * $boyut[1];
                        $yeniresim = imagecreatetruecolor(700, $yeniyukseklik);
                        imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 700, $yeniyukseklik, $boyut[0], $boyut[1]);
                        $hedefdosya = $dhedef . "/" . $r_yeniismi;
                        imagejpeg($yeniresim, $hedefdosya, 90);

                        $boyutt = getimagesize($dhedef . '/' . $r_yeniismi);
                        $resimoranit = 140 / $boyutt[0];
                        $yeniyukseklikt = $resimoranit * $boyutt[1];
                        $yeniresimt = imagecreatetruecolor(140, $yeniyukseklikt);
                        imagecopyresampled($yeniresimt, $ndot, 0, 0, 0, 0, 140, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                        $hedefdosyat = $thumb . "/" . $r_yeniismi;
                        imagejpeg($yeniresimt, $hedefdosyat, 90);

                        if ($dyukle) {
                            $addPage = $db->query("insert into pages (adres, title, title2, pagecontent, description, keyword, status, image, websiteid) VALUES ('$pageName','$pageTitle','$pageTitle','$title', '$pageDescription','$title','$pageStatus','$r_yeniismi','" . $_SESSION["admin"]["websiteid"] . "')");

                            if ($addPage) {
                                setcookie('testcookie', "true|Sayfa Kaydedildi.........", time() + 20, '/');
                                header("location: ../index.php?p=pages");
                            } else {
                                setcookie('testcookie', "false|Sayfa kaydedilemedi...", time() + 20, '/');
                                header("location: ../index.php?p=pages");
                                unlink($dhedef."/".$r_yeniismi);
                                unlink($thumb."/".$r_yeniismi);
                            }

                        } else {
                            setcookie('testcookie', "false|Resim yüklenemedi...", time() + 20, '/');
                            header("location: ../index.php?p=pages");
                        }

                    } else{
                        setcookie('testcookie', "false|Resim Mevcut...", time() + 20, '/');
                        header("location: ../index.php?p=pages");
                    }
                }else{
                    setcookie('testcookie', "true|resim yok iken işlemler...", time() + 20, '/');
                    header("location: ../index.php?p=pages");
                }
            }
        }
        elseif ($process == "editPages"){
            if (isset($_POST['pid']) && !empty($_POST['pid'])) {
                $id = intval($_POST['pid']);
                $query = "SELECT * FROM pages WHERE id=:id";
                $stmt = $db->prepare( $query );
                $stmt->execute(array(':id'=>$id));
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($row);
                exit;
            }
            if (isset($_POST["editPage"])){
                $pageImg = $_FILES["pageImg"]["name"];
                $pid = strip_tags(trim($_POST["id"]));
                $pageTitle = strip_tags(trim($_POST["pageTitle"]));
                $title2 = strip_tags(trim($_POST["pageTitle2"]));
                $adres = permalink($pageTitle);
                $pageDescription = strip_tags(trim($_POST["pageDescription"]));
                $pageText = strip_tags(trim($_POST["pageText"]));
                $pgOldImg = $_POST["pageimg"];
                $pageStatus = $_POST["pageStatus"] == "on" ? "1" : "0";
                $isim_parcala = explode(".", $pgOldImg);
                $page_title = permalink($pageTitle);
                $r_yeniismi = $page_title.".".$isim_parcala["1"];

                if ($pageImg == null){
                    $page_update = $db->query("update pages set title='$pageTitle', title2='$title2', pagecontent='$pageText', status='$pageStatus', description='$pageDescription', image='$r_yeniismi' where id='$pid'");
                    if ($page_update->rowCount()){
                        rename("../../img/thumbs/".$pgOldImg, "../../img/thumbs/".$r_yeniismi);
                        rename("../../img/".$pgOldImg, "../../img/".$r_yeniismi);
                        setcookie('testcookie', "true|Pages Updated...", time() + 20, '/');
                        header("location: ../index.php?p=pages");
                    }
                }
                else{
                    $risimi = $_FILES["pageImg"]["name"];
                    //$s_name = strip_tags(trim($_POST["slider_name"]));
                    $rturu = $_FILES["pageImg"]["type"];
                    $rboyutu = $_FILES["pageImg"]["size"];
                    $isim_parcala = explode(".", $risimi);
                    $rasgeleisim = permalink($title2);
                    $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];

                    $dhedef = "../../img/";
                    $thumb = "../../img/thumbs/";
                    $dyukle = move_uploaded_file($_FILES["pageImg"]["tmp_name"], $dhedef . '/' . $r_yeniismi);
                    if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg") {
                        $ndo = imagecreatefromjpeg($dhedef . "/" . $r_yeniismi);
                        $ndot = imagecreatefromjpeg($dhedef . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "png") {
                        $ndo = imagecreatefrompng($dhedef . "/" . $r_yeniismi);
                        $ndot = imagecreatefrompng($dhedef . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "gif") {
                        $ndo = imagecreatefromgif($dhedef . "/" . $r_yeniismi);
                        $ndot = imagecreatefromgif($dhedef . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "bmp") {
                        $ndo = imagecreatefromwbmp($dhedef . "/" . $r_yeniismi);
                        $ndot = imagecreatefromwbmp($dhedef . "/" . $r_yeniismi);
                    }
                    $boyut = getimagesize($dhedef . '/' . $r_yeniismi);
                    $resimorani = 700 / $boyut[0];
                    $yeniyukseklik = $resimorani * $boyut[1];
                    $yeniresim = imagecreatetruecolor(700, $yeniyukseklik);
                    imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 700, $yeniyukseklik, $boyut[0], $boyut[1]);
                    $hedefdosya = $dhedef . "/" . $r_yeniismi;
                    imagejpeg($yeniresim, $hedefdosya, 90);

                    $boyutt = getimagesize($dhedef . '/' . $r_yeniismi);
                    $resimoranit = 70 / $boyutt[0];
                    $yeniyukseklikt = $resimoranit * $boyutt[1];
                    $yeniresimt = imagecreatetruecolor(70, $yeniyukseklikt);
                    imagecopyresampled($yeniresimt, $ndot, 0, 0, 0, 0, 70, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                    $hedefdosyat = $thumb . "/" . $r_yeniismi;
                    imagejpeg($yeniresimt, $hedefdosyat, 90);
                    if ($dyukle) {
                        $page_update = $db->query("update pages set title='$pageTitle', title2='$title2', pagecontent='$pageText', status='$pageStatus', description='$pageDescription', image='$r_yeniismi' where id='$pid'");
                        unlink("../../img/thumbs/".$pgOldImg);
                        unlink("../../img/".$pgOldImg);
                        if ($page_update->rowCount()){
                            setcookie('testcookie', "true|Pages Updated...", time() + 20, '/');
                            header("location: ../index.php?p=pages");
                        }
                        else{
                            setcookie('testcookie', "false|Pages Not Updated...", time() + 20, '/');
                            header("location: ../index.php?p=pages");
                        }

                    } else {
                        echo "Dosya Yüklenemedi";
                        header("refresh: 2; url=$_SERVER[HTTP_REFERER]");
                    }
                    /*
                                        $stmt = $db->prepare('UPDATE pages
                                  SET adres=:adres,
                                   title=:title,
                                   title2=:title2,
                                   pagecontent=:pagecontent,
                                   description=:desription,
                                   status=:status
                                   WHERE id=:pid');
                                        $stmt->bindParam(':adres',$adres);
                                        $stmt->bindParam(':title',$title);
                                        $stmt->bindParam(':title2',$title2);
                                        $stmt->bindParam(':pagecontent',$pagecontent);
                                        $stmt->bindParam(':description',$description);
                                        $stmt->bindParam(':status',$pageStatus);
                                        $stmt->bindParam(':pid',$pid);

                                        if($stmt->execute()){
                                            echo "eeee";
                                        }
                                        else{
                                            $errMSG = "Sorry Data Could Not Updated !";
                                        }
                                        */
                }
            }
            else{echo "111";}
        }
        elseif ($process == "deletePages"){
            $pageID = $_GET["pageID"];
            if (empty($pageID) && !is_numeric($pageID)) {
                header("Location: ../index.php");
            }
            else {
                $pg_img = $db->query("select * from pages where id='$pageID'");
                if ($pg_img->rowCount()){
                    foreach ($pg_img as $item) {
                        $img_pg = $item["image"];
                    }
                }
                $pages_delete = $db->query("delete from pages where id='$pageID'");
                if ($pages_delete->rowCount()) {
                    // header("Location: ../index.php"); //Notification
                    echo "true";
                    unlink("../../img/thumbs/".$img_pg);
                    unlink("../../img/".$img_pg);
                } else {
                    //header("Location: ../index.php");
                    echo "false";
                }
            }
        }
        elseif ($process == "pg_img"){
            $imgID = $_GET["imgID"];
            $pgImgSelect = $db->query("select * from pages where id='$imgID'");
            if ($pgImgSelect->rowCount()){
                foreach ($pgImgSelect as $item) {
                    $img_name = $item["image"];
                }
            }
            $img_delete = $db->query("update pages set image='' where id='$imgID'");
            if ($img_delete->rowCount()) {
                echo "true";
                unlink("../../img/".$img_name);
                unlink("../../img/thumbs/".$img_name);
            } else {
                echo "false";
            }
        }
    }
    //Gallery
    else if ($pg == "gallery"){
        $process = $_GET["process"];
        if ($process == "addGalleryImage"){
            if (isset($_POST["imageAdd"])){
                $galleryImg = $_FILES["galleryImg"]["name"];
                $imageTitle = strip_tags(trim($_POST["imageTitle"]));
                $image_cat = strip_tags(trim($_POST["gallery_image_cat"]));
                if (!empty($galleryImg)){
                    $rturu = $_FILES["$galleryImg"]["type"];
                    $rboyutu = $_FILES["$galleryImg"]["size"];
                    $isim_parcala = explode(".", $galleryImg);
                    $imgName = permalink($imageTitle);
                    $r_yeniismi = $imgName . "." . $isim_parcala[1];

                    $temp = "../../img/temp";
                    $dhedef = "../../img/gallery";
                    $thumb = "../../img/gallery/thumbs/";
                    if (!file_exists($dhedef.$r_yeniismi) && !file_exists($thumb.$r_yeniismi)) {
                        $dyukle = move_uploaded_file($_FILES["galleryImg"]["tmp_name"], $temp . '/' . $r_yeniismi);
                        if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg" || $isim_parcala["1"] == "JPG" || $isim_parcala["1"] == "JPEG") {
                            $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "png") {
                            $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "gif") {
                            $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                        } elseif ($isim_parcala["1"] == "bmp") {
                            $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                        }
                        $boyut = getimagesize($temp . '/' . $r_yeniismi);
                        $resimorani = 800 / $boyut[0];
                        $yeniyukseklik = $resimorani * $boyut[1];
                        $yeniresim = imagecreatetruecolor(800, $yeniyukseklik);
                        imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 800, $yeniyukseklik, $boyut[0], $boyut[1]);
                        $hedefdosya = $dhedef . "/" . $r_yeniismi;
                        imagejpeg($yeniresim, $hedefdosya, 100);

                        $boyutt = getimagesize($temp . '/' . $r_yeniismi);
                        $resimoranit = 240 / $boyutt[0];
                        $yeniyukseklikt = $resimoranit * $boyutt[1];
                        $yeniresimt = imagecreatetruecolor(240, $yeniyukseklikt);
                        imagecopyresampled($yeniresimt, $ndo, 0, 0, 0, 0, 240, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                        $hedefdosyat = $thumb . "/" . $r_yeniismi;
                        imagejpeg($yeniresimt, $hedefdosyat, 100);
                        if ($dyukle){
                            $addImage = $db->query("insert into gallery (image, imagetitle, categoryid) VALUES ('$r_yeniismi','$imageTitle','$image_cat')");
                            if ($addImage){
                                setcookie('testcookie', "true|Image Kaydedildi.........", time() + 20, '/');
                                header("location: ../index.php?p=gallery");
                            } else{
                                setcookie('testcookie', "false|Image kaydedilemedi...", time() + 20, '/');
                                header("location: ../index.php?p=gallery");
                            }
                        } else{
                            setcookie('testcookie', "false|Image Not Uploaded...", time() + 20, '/');
                            header("location: ../index.php?p=gallery");
                        }
                        unlink($temp."/".$r_yeniismi);
                    } else{
                        setcookie('testcookie', "false|Image is here...", time() + 20, '/');
                        header("location: ../index.php?p=gallery");
                    }
                } else{
                    setcookie('testcookie', "true|Actions When There is No Picture...", time() + 20, '/');
                    header("location: ../index.php?p=gallery");
                }
            }
        }
        elseif ($process == "deleteGalleryImage"){
            $imgID = $_GET["id"];
            if (empty($imgID) && !is_numeric($imgID)) {
                header("Location: ../index.php?p=gallery");
            }
            else{
                $selectImg = $db->query("select * from gallery where id='$imgID'")->fetch();
                $imgurl = $selectImg["image"];
                $deleteImg = $db->query("delete from gallery where id='$imgID'");
                if ($deleteImg){
                    $url = "../../img/gallery/".$imgurl;
                    $thumbs = "../../img/gallery/thumbs/".$imgurl;
                    unlink($url);
                    unlink($thumbs);
                    header("Location: ../index.php?p=gallery");
                    setcookie('testcookie', "true|Image Deleted.........", time() + 20, '/');
                }
                else{
                    header("Location: ../index.php?p=gallery");
                    setcookie('testcookie', "false|Image Not Deleted.........", time() + 20, '/');
                }
            }
        }
        elseif ($process == "editGalleryImage"){
            $q = $_GET["q"];
            if ($q == "get"){
                $imgid = $_GET["imgid"];
                $selectGallery = $db->query("select * from gallery where id='$imgid'");
                $galleryData = array();
                if ($selectGallery->rowCount()){
                    foreach ($selectGallery as $gl) {
                        $ct = $db->query("select * from categories where id='".$gl["categoryid"]."'")->fetch();
                        $galleryData["gallery_data"] = array(
                            "image"       =>  $gl["image"],
                            "imagetitle"  =>  $gl["imagetitle"],
                            "categoryid"  =>  $gl["categoryid"],
                            "catname"     =>  $ct["category"]
                        );
                    }
                    echo json_encode($galleryData);
                }
                else{
                    echo "false";
                }
            }
            elseif (isset($_POST["imageUpdate"])){
                $imageTitle2 = strip_tags(trim($_POST["imageTitle2"]));
                $gallery_image_cat2 = strip_tags(trim($_POST["gallery_image_cat2"]));
                $IDGallery = strip_tags(trim($_POST["gallery_hidden"]));
                $gallery_old_name = strip_tags(trim($_POST["gallery_hidden2"]));
                $isim_parcala = explode(".", $gallery_old_name);
                $gallery_name2 = permalink($imageTitle2);
                $r_yeniismi = $gallery_name2.".".$isim_parcala["1"];
                $gallery_image = $_FILES["galleryImg2"]["name"];
                if ($gallery_image == null){
                    $gallery_update = $db->query("update gallery set image='$r_yeniismi', imagetitle='$imageTitle2', categoryid='$gallery_image_cat2' where id='$IDGallery'");
                    rename("../../img/gallery/thumbs/".$gallery_old_name, "../../img/gallery/thumbs/".$r_yeniismi);
                    rename("../../img/gallery/".$gallery_old_name, "../../img/gallery/".$r_yeniismi);
                    if ($gallery_update->rowCount()){
                        setcookie('testcookie', "true|Gallery Image Updated...", time() + 20, '/');
                        header("location: ../index.php?p=gallery");
                    }
                    else{
                        setcookie('testcookie', "false|Gallery Image Not Updated...", time() + 20, '/');
                        header("location: ../index.php?p=gallery");
                    }
                }
                else{
                    $risimi = $_FILES["galleryImg2"]["name"];
                    //$s_name = strip_tags(trim($_POST["slider_name"]));
                    $rturu = $_FILES["galleryImg2"]["type"];
                    $rboyutu = $_FILES["galleryImg2"]["size"];
                    $isim_parcala = explode(".", $risimi);
                    $rasgeleisim = permalink($imageTitle2);
                    $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];

                    $temp = "../../img/temp";
                    $dhedef = "../../img/gallery";
                    $thumb = "../../img/gallery/thumbs/";
                    $dyukle = move_uploaded_file($_FILES["galleryImg2"]["tmp_name"], $temp . '/' . $r_yeniismi);
                    if ($isim_parcala["1"] == "jpg" || $isim_parcala["1"] == "jpeg" || $isim_parcala["1"] == "JPG" || $isim_parcala["1"] == "JPEG") {
                        $ndo = imagecreatefromjpeg($temp . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "png") {
                        $ndo = imagecreatefrompng($temp . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "gif") {
                        $ndo = imagecreatefromgif($temp . "/" . $r_yeniismi);
                    } elseif ($isim_parcala["1"] == "bmp") {
                        $ndo = imagecreatefromwbmp($temp . "/" . $r_yeniismi);
                    }
                    $boyut = getimagesize($temp . '/' . $r_yeniismi);
                    $resimorani = 800 / $boyut[0];
                    $yeniyukseklik = $resimorani * $boyut[1];
                    $yeniresim = imagecreatetruecolor(800, $yeniyukseklik);
                    imagecopyresampled($yeniresim, $ndo, 0, 0, 0, 0, 800, $yeniyukseklik, $boyut[0], $boyut[1]);
                    $hedefdosya = $dhedef . "/" . $r_yeniismi;
                    imagejpeg($yeniresim, $hedefdosya, 100);

                    $boyutt = getimagesize($temp . '/' . $r_yeniismi);
                    $resimoranit = 240 / $boyutt[0];
                    $yeniyukseklikt = $resimoranit * $boyutt[1];
                    $yeniresimt = imagecreatetruecolor(240, $yeniyukseklikt);
                    imagecopyresampled($yeniresimt, $ndo, 0, 0, 0, 0, 240, $yeniyukseklikt, $boyutt[0], $boyutt[1]);
                    $hedefdosyat = $thumb . "/" . $r_yeniismi;
                    imagejpeg($yeniresimt, $hedefdosyat, 100);
                    if ($dyukle) {
                        $gallery_update = $db->query("update gallery set image='$r_yeniismi', imagetitle='$slider_title2', categoryid='$gallery_image_cat2' where id='$IDGallery'");
                        if ($gallery_update){
                            setcookie('testcookie', "true|Gallery Image Updated...", time() + 20, '/');
                            header("location: ../index.php?p=gallery");
                        }
                        else{
                            setcookie('testcookie', "false|Gallery Image Not Updated...", time() + 20, '/');
                            header("location: ../index.php?p=gallery");
                        }
                        unlink($temp."/".$r_yeniismi);
                    }
                    else {
                        setcookie('testcookie', "false|File Not Uploaded...", time() + 20, '/');
                        header("location: ../index.php?p=gallery");
                    }
                }
            }
        }
    }
    //Customers and Suppliers
    else if ($pg == "customer"){
        $process = $_GET["process"];
        $pr_id = $_GET["pr_id"];
        if($process == "getCustomer"){
            customerSelect();
        }
        elseif ($process == "editCustomer"){
            $getCustomer = $db->query("select * from companies WHERE pr_id='$pr_id'")->fetch();
            $getCustomer2 = array();
            $getCustomer2["getCustomer"] = array(
                "customername"        => $getCustomer["companyname"],
                "firstname"        => $getCustomer["firstname"],
                "lastname"        => $getCustomer["lastname"],
                "tel1"        => $getCustomer["tel1"],
                "tel2"        => $getCustomer["tel2"],
                "fax"        => $getCustomer["fax"],
                "email"        => $getCustomer["email"],
                "web"        => $getCustomer["web"],
                "note"        => $getCustomer["note"],
                "fpoc"        => $getCustomer["fpoc"],
                "companytype"        => $getCustomer["companytype"],
                "paymentterm"        => $getCustomer["paymentterm"],
                "issupplier"        => $getCustomer["issupplier"],
                "iscustomer"        => $getCustomer["iscustomer"],
                "isinactive"        => $getCustomer["isinactive"],
                "vatno"        => $getCustomer["vatno"]
            );
            $getAddress = $db->query("select * from company_address WHERE companyid='$pr_id'")->fetch();
            $getCustomer2["getAddress"] = array(
                "addressname"        => $getAddress["addressname"],
                "house"        => $getAddress["house"],
                "street"        => $getAddress["street"],
                "city"        => $getAddress["city"],
                "county"        => $getAddress["county"],
                "postcode"        => $getAddress["postcode"],
                "country"        => $getAddress["country"],
                "ismain"        => $getAddress["ismain"],
            );
            $companyAccount = $db->query("select SUM(amount) AS balance from accounts WHERE companyid='$pr_id'")->fetch();
            $getCustomer2["getBalance"] = array(
                "balance"        => number_format($companyAccount["balance"],2)
            );
            echo json_encode($getCustomer2);
        }
        elseif ($process == "update"){
            if ($_POST["details"]){
                $pr_id = strip_tags(trim($_POST["pr_id"]));
                $customerName = strtoupper(strip_tags(trim($_POST["customerName2"])));
                $contactName = strtoupper(strip_tags(trim($_POST["contactName21"])));
                $contactName2 = strtoupper(strip_tags(trim($_POST["contactName22"])));
                $tel = strip_tags(trim($_POST["tel21"]));
                $tel2 = strip_tags(trim($_POST["tel22"]));
                $fax = strip_tags(trim($_POST["fax2"]));
                $vat = strip_tags(trim($_POST["vat2"]));
                $mail = strip_tags(trim($_POST["mail2"]));
                $web = strip_tags(trim($_POST["web2"]));
                $companyType = strip_tags(trim($_POST["companyType2"]));
                //$fpoc = strip_tags(trim($_POST["fpoc2"]));
                $paymentMethod = strip_tags(trim($_POST["paymentMethod2"]));
                $isSupplier = strip_tags(trim($_POST["isSupplier2"]));
                $isCustomer = strip_tags(trim($_POST["isCustomer2"]));
                $isinactive = strip_tags(trim($_POST["isinactive"]));
                $note = strip_tags(trim($_POST["note2"]));
                if ($isSupplier=="on"){$isSupplier = "1";}else{$isSupplier="0";}
                if ($isCustomer=="on"){$isCustomer = "1";}else{$isCustomer="0";}
                if ($isinactive=="on"){$isinactive = "1";}else{$isinactive="0";}

                if (($isCustomer == "1" || $isCustomer == "0") && ($isSupplier != "1" || $isSupplier != "0")){
                    $customerUpdate = $db->query("update companies set companyname='$customerName', firstname='$contactName', lastname='$contactName2', tel1='$tel', tel2='$tel2', fax='$fax', email='$mail', web='$web', note='$note', companytype='$companyType', paymentterm='$paymentMethod', iscustomer='$isCustomer', isinactive='$isinactive', vatno='$vat' WHERE pr_id='$pr_id'");
                }
                elseif (($isCustomer != "1" || $isCustomer != "0") && ($isSupplier == "1" || $isSupplier == "0")){
                    $customerUpdate = $db->query("update companies set companyname='$customerName', firstname='$contactName', lastname='$contactName2', tel1='$tel', tel2='$tel2', fax='$fax', email='$mail', web='$web', note='$note', companytype='$companyType', paymentterm='$paymentMethod', issupplier='$isSupplier', isinactive='$isinactive', vatno='$vat' WHERE pr_id='$pr_id'");
                }

                $companyAccount = $db->query("select SUM(amount) AS balance from accounts WHERE companyid='$pr_id'")->fetch();
                if ($customerUpdate){
                    echo 1;
                    //setcookie('testcookie', "true|Customer Updated...", time() + 20, '/');
                }else{
                    echo 0;
                    //setcookie('testcookie', "false|Customer Not Updated...", time() + 20, '/');
                }
            }
            elseif ($_POST["address"]){
                $locationName = strtoupper(strip_tags(trim($_POST["locationName2ad"])));
                $house = strtoupper(strip_tags(trim($_POST["house2ad"])));
                $street = strtoupper(strip_tags(trim($_POST["street2ad"])));
                $city = strtoupper(strip_tags(trim($_POST["city2ad"])));
                $county = strtoupper(strip_tags(trim($_POST["county2ad"])));
                $postcode = strtoupper(strip_tags(trim($_POST["postcode2ad"])));
                $country = strtoupper(strip_tags(trim($_POST["country2ad"])));
                $isMain = strip_tags(trim($_POST["isMain2ad"]));
                $addressID = $_POST["addressID"];

                $updateAddress = $db->query("update company_address set addressname='$locationName', house='$house', street='$street', city='$city', county='$county', country='$country', postcode='$postcode', ismain='$isMain' WHERE id='$addressID'");
                if ($updateAddress){
                    echo 1;

                }else{
                    echo 0;
                }
            }elseif ($_POST["proformas"]){
                echo"proformas";
            }elseif ($_POST["orders"]){
                echo "orders";
            }elseif ($_POST["invoices"]){
                echo "invoices";
            }elseif ($_POST["transactions"]){
                echo "transactions";
            }
        }
        elseif ($process == "addcompany"){
            if(isset($_POST["customerAdd"])){
                $customerName = strtoupper(strip_tags(trim($_POST["customerName"])));
                $contactName = strtoupper(strip_tags(trim($_POST["contactName"])));
                $contactName2 = strtoupper(strip_tags(trim($_POST["contactName2"])));
                $tel = strip_tags(trim($_POST["tel"]));
                $tel2 = strip_tags(trim($_POST["tel2"]));
                $fax = strip_tags(trim($_POST["fax"]));
                $vat = strip_tags(trim($_POST["vat"]));
                $mail = strip_tags(trim($_POST["mail"]));
                $web = strip_tags(trim($_POST["web"]));
                $companyType = strip_tags(trim($_POST["comboSelectcompanyType"]));
                //$fpoc = strip_tags(trim($_POST["comboSelectfpoc"]));
                $paymentMethod = strip_tags(trim($_POST["comboSelectpaymentterm"]));
                $balance = strip_tags(trim($_POST["balance"]));
                $isSupplier = strip_tags(trim($_POST["isSupplier"]));
                $isCustomer = strip_tags(trim($_POST["isCustomer"]));
                $note = strip_tags(trim($_POST["note"]));
                $locationName = strtoupper(strip_tags(trim($_POST["locationName"])));
                $house = strtoupper(strip_tags(trim($_POST["house"])));
                $street = strtoupper(strip_tags(trim($_POST["street"])));
                $city = strtoupper(strip_tags(trim($_POST["city"])));
                $county = strtoupper(strip_tags(trim($_POST["county"])));
                $postcode = strtoupper(strip_tags(trim($_POST["postcode"])));
                $country = strtoupper(strip_tags(trim($_POST["country"])));
                $isMain = strip_tags(trim($_POST["isMain"]));
                if ($isSupplier=="on"){$isSupplier = "1"; $issuppinactive = "0";}else{ $isCustomer = "1"; $iscustinactive = "0"; }
                if ($isCustomer=="on"){$isCustomer = "1"; $iscustinactive = "0";}else{ $isSupplier = "1"; $issuppinactive = "0"; }
                if ($isMain=="on"){$isMain = "1";}else{$isMain="0";}

                if (($isCustomer == "1" && $iscustinactive == "0")){
                    $addCustomer = $db->query("insert into companies (companyname,firstname,lastname,tel1,tel2,fax,email,web,note,companytype,paymentterm,iscustomer,isinactive,vatno) VALUES ('$customerName','$contactName','$contactName2','$tel','$tel2','$fax','$mail','$web','$note','$companyType','$paymentMethod','$isCustomer','$iscustinactive','$vat')");
                }
                elseif (($isSupplier == "1" && $issuppinactive == "0")){
                    $addCustomer = $db->query("insert into companies (companyname,firstname,lastname,tel1,tel2,fax,email,web,note,companytype,paymentterm,issupplier,isinactive,vatno) VALUES ('$customerName','$contactName','$contactName2','$tel','$tel2','$fax','$mail','$web','$note','$companyType','$paymentMethod','$isSupplier','$issuppinactive','$vat')");
                }

                $companyID = $db->lastInsertId();
                $addAdress = $db->query("insert into company_address (addressname, companyid, house, street, city, county, postcode, country, ismain) VALUES ('$locationName', '$companyID', '$house', '$street', '$city', '$county', '$postcode', '$country', '$isMain')");
                if ($addCustomer->rowCount()){
                    $upcustomer = $db->query("update companies set id='$companyID' where pr_id='$companyID'");
                    //echo "true";
                    if (($isCustomer == "1" && $iscustinactive == "0")){
                        setcookie('testcookie', "true|Customer Saved...", time() + 20, '/');
                        header("location: ../index.php?p=customer&st=activeCustomers");
                    }
                    elseif (($isSupplier == "1" && $issuppinactive == "0")){
                        setcookie('testcookie', "true|Supplier Saved...", time() + 20, '/');
                        header("location: ../index.php?p=supplier&spp=activeSuppliers");
                    }
                }else{
                    //echo "false";
                    if (($isCustomer == "1" && $iscustinactive == "0")){
                        setcookie('testcookie', "false|Customer Not Saved...", time() + 20, '/');
                        header("location: ../index.php?p=customer&st=activeCustomers");
                    }
                    elseif (($isSupplier == "1" && $issuppinactive == "0")){
                        setcookie('testcookie', "false|Supplier Not Saved...", time() + 20, '/');
                        header("location: ../index.php?p=supplier&spp=activeSuppliers");
                    }

                }
            }
            elseif($_POST["addnewaddress"]){
                $locationName = strtoupper(strip_tags(trim($_POST["locationName"])));
                $house = strtoupper(strip_tags(trim($_POST["house"])));
                $street = strtoupper(strip_tags(trim($_POST["street"])));
                $city = strtoupper(strip_tags(trim($_POST["city"])));
                $county = strtoupper(strip_tags(trim($_POST["county"])));
                $postcode = strtoupper(strip_tags(trim($_POST["postcode"])));
                $country = strtoupper(strip_tags(trim($_POST["country"])));
                $isMain = strip_tags(trim($_POST["isMain"]));
                $companyID = strip_tags(trim($_POST["companyID"]));
                $isMain = strip_tags(trim($_POST["isMain"]));

                $addAdress = $db->query("insert into company_address (addressname, companyid, house, street, city, county, postcode, country, ismain) VALUES ('$locationName', '$companyID', '$house', '$street', '$city', '$county', '$postcode', '$country', '$isMain')");
                $addnewaddress = strip_tags(trim($_POST["addnewaddress"]));
                if ($addAdress){
                    echo 1;
                    //setcookie('testcookie', "true|Address Saved...", time() + 20, '/');
                }else{
                    echo 0;
                    //setcookie('testcookie', "false|Address Not Saved...", time() + 20, '/');
                }
            }

        }
        elseif ($process == "getAddress"){
            $pr_id = $_POST["pr_id"];
            $getAddress = $db->query("select * from company_address WHERE companyid=".$pr_id." order by ismain desc", PDO::FETCH_ASSOC);
            $getAddress3a = $db->query("select * from company_address WHERE companyid=".$pr_id." order by ismain desc", PDO::FETCH_ASSOC);
            ?>
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <ul class="nav nav-tabs tabs-left">
                        <?php
                        $aa = 0;
                        foreach ($getAddress as $getAddress2){
                            if($aa == "0"){
                                $activeIn = "active";
                            }
                            else{
                                $activeIn = "";
                            }

                            echo'
                             <li class="'.$activeIn.'">
                            <a href="#'.$getAddress2["id"].'" data-toggle="tab"> '.$getAddress2["addressname"].' </a>
                        </li>
                             ';
                            $aa++;
                        }
                        ?>
                    </ul>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-9">
                    <div class="tab-content">
                        <?php
                        $bb = 0;
                        foreach ($getAddress3a as $getAddress3){
                            if($bb == "0"){
                                $activeIn = "active in";
                            }
                            else{
                                $activeIn = 'fade';
                            }
//                            echo'
//                             <div class="tab-pane ';if($bb == "0"){echo"active in";}else{echo'fade';} echo'" id="'.$getAddress3["id"].'">
//                             ';
                            echo '<div class="tab-pane '.$activeIn.'" id="'.$getAddress3["id"].'">';
                            ?>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Location Name</label>
                                <div class="col-md-4">
                                    <input type="text" name="locationName2ad<? echo $getAddress3["id"]; ?>" id="locationName2ad<? echo $getAddress3["id"]; ?>" class="form-control input-circle" value="<? echo $getAddress3["addressname"]; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">House</label>
                                <div class="col-md-4">
                                    <input type="text" name="house2ad<? echo $getAddress3["id"]; ?>" id="house2ad<? echo $getAddress3["id"]; ?>" class="form-control input-circle" value="<? echo $getAddress3["house"]; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Street</label>
                                <div class="col-md-4">
                                    <input type="text" name="street2ad<? echo $getAddress3["id"]; ?>" id="street2ad<? echo $getAddress3["id"]; ?>" class="form-control input-circle" value="<? echo $getAddress3["street"]; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">City</label>
                                <div class="col-md-4">
                                    <input type="text" name="city2ad<? echo $getAddress3["id"]; ?>" id="city2ad<? echo $getAddress3["id"]; ?>" class="form-control input-circle" value="<? echo $getAddress3["city"]; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">County</label>
                                <div class="col-md-4">
                                    <input type="text" name="county2ad<? echo $getAddress3["id"]; ?>" id="county2ad<? echo $getAddress3["id"]; ?>" class="form-control input-circle" value="<? echo $getAddress3["county"]; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Postcode</label>
                                <div class="col-md-4">
                                    <input type="text" name="postcode2ad<? echo $getAddress3["id"]; ?>" id="postcode2ad<? echo $getAddress3["id"]; ?>" class="form-control input-circle" value="<? echo $getAddress3["postcode"]; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Country</label>
                                <div class="col-md-4">
                                    <input type="text" name="country2ad<? echo $getAddress3["id"]; ?>" id="country2ad<? echo $getAddress3["id"]; ?>" class="form-control input-circle" value="<? echo $getAddress3["country"]; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Is Main</label>
                                <div class="col-md-2">
                                    <div class="md-checkbox" style="margin-top: 5px;">
                                        <input type="checkbox" name="isMain2ad<? echo $getAddress3["id"]; ?>" id="isMain2ad<? echo $getAddress3["id"]; ?>" class="md-check" <?if($getAddress3["ismain"]=="1"){echo" checked";} ?> >
                                        <label for="isMain2ad<? echo $getAddress3["id"]; ?>">
                                            <span class="inc"></span>
                                            <span class="check"></span>
                                            <span class="box"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label"></label>
                                <div class="col-md-8">
                                    <input type="hidden" id="addressID" name="addressID" value="<? echo $getAddress3["id"]; ?>">
                                    <button type="button" class="btn red" onclick="deleteAddress(<? echo $getAddress3["id"]; ?>)"><i class="fa fa-trash"></i></button>
                                    <button type="button" class="btn btn-success" onclick="updateAddress(<? echo $getAddress3["id"]; ?>)">Update <b>"<? echo $getAddress3["addressname"]; ?>"</b></button>

                                </div>

                            </div>
                            <?php
                            echo'</div>';
                            $bb++ ;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?
        }
        elseif ($process == "deleteAddress"){
            $addressid = $_POST["addressid"];
            $addresDelete = $db->query("delete from company_address WHERE id='$addressid'");
            if($addresDelete){
                echo 1;
            }
            else{
                echo 0;
            }
        }
        elseif ($process == "getProformas") {
            $proforma_id = $_POST["proforma_id"];
            $ordertype = 2;
            $status = 1;
            $getProformas = $db->query("SELECT company_address.*, companies.*, orders.orderdate, orders.id, orders.orderno, orders.proformano, orders.status, orders.deliverydate, orders.fupfirstdate, orders.websiteid FROM company_address left join orders on company_address.id=orders.addressid left join companies on companies.id=orders.companyid where orders.ordertype='$ordertype' and orders.status>='$status' and orders.isdeleted<>1 and orders.companyid='$proforma_id'");
            if ($getProformas->rowCount()) {
                foreach ($getProformas as $prf) {
                    ?>

                    <tr>
                        <td><?php echo $prf["proformano"]; ?></td>
                        <td>
                            <a href="index.php?p=sales&pg=panel-order-edit&orderid=<?php echo $prf["id"]; ?>&type=proforma" id="proforma">
                                <?php if ($prf["companyname"] != "") {
                                    echo $prf["companyname"];
                                } else {
                                    echo $prf["firstname"] . "  " . $prf["lastname"];
                                } ?>
                            </a>
                        </td>
                        <td>
                            <?php
                            if ($prf["status"] == 1) {
                                echo "Open";
                            }
                            if ($prf["status"] == 2) {
                                echo "Follow";
                            }
                            if ($prf["status"] == 3) {
                                echo "Archived";
                            }
                            if ($prf["status"] == 4) {
                                echo "Lost";
                            }
                            ?>
                        </td>
                        <td><?php echo $prf["postcode"]; ?></td>
                        <td>
                            <?php
                            $orderdate = strtotime($prf["orderdate"]);
                            $orderdate = date("Y-m-d", $orderdate);
                            echo $orderdate;
                            ?>
                        </td>
                        <td><?php
                            if ($prf["websiteid"] == 1) {
                                echo "SD";
                            } elseif ($prf["websiteid"] == 2) {
                                echo "TS";
                            } ?></td>
                    </tr>

                    <?php
                }
            }
            else{
                echo '<h4 class="text-danger">No results found</h4>';
            }

        }
        elseif ($process == "getOrders"){
            $order_id = $_POST["order_id"];
            $ordertype = 3;
            $status = 1;
            $getOrders = $db->query("SELECT company_address.*, companies.*, orders.orderdate, orders.id, orders.orderno, orders.proformano, orders.status, orders.deliverydate, orders.fupfirstdate, orders.websiteid FROM company_address left join orders on company_address.id=orders.addressid left join companies on companies.id=orders.companyid where orders.ordertype='$ordertype' and orders.status>='$status' and orders.isdeleted<>1 and orders.companyid='$order_id'");
            if ($getOrders->rowCount()) {
                foreach ($getOrders as $order) {
                    ?>

                    <tr>
                        <td><?php echo $order["orderno"]; ?></td>
                        <td>
                            <a href="index.php?p=sales&pg=panel-order-edit&orderid=<?php echo $order["id"]; ?>&type=order" id="orders">
                                <?php if ($order["companyname"] != "") {
                                    echo $order["companyname"];
                                } else {
                                    echo $order["firstname"] . "  " . $order["lastname"];
                                } ?>
                            </a>
                        </td>
                        <td>
                            <?php
                            if ($order["status"] == 1) {
                                echo "Open";
                            }
                            if ($order["status"] == 2) {
                                echo "Approved";
                            }
                            if ($order["status"] == 3) {
                                echo "Archived";
                            }
                            if ($order["status"] == 4) {
                                echo "Lost";
                            }
                            ?>
                        </td>
                        <td><?php echo $order["postcode"]; ?></td>
                        <td>
                            <?php
                            $orderdate = strtotime($order["orderdate"]);
                            $orderdate = date("Y-m-d", $orderdate);
                            echo $orderdate;
                            ?>
                        </td>
                        <td><?php
                            if ($order["websiteid"] == 1) {
                                echo "SD";
                            } elseif ($order["websiteid"] == 2) {
                                echo "TS";
                            } ?>
                        </td>
                    </tr>

                    <?php
                }
            }
            else{
                echo '<h4 class="text-danger">No results found</h4>';
            }
        }
        elseif ($process == "getInvoices"){
            $invoice_id = $_POST["invoice_id"];
            $ordertype = 4;
            $status = 0;
            $getInvoices = $db->query("SELECT company_address.*, companies.*, orders.orderdate, orders.id, orders.invoiceno, orders.status, orders.deliverydate, orders.fupfirstdate, orders.websiteid FROM company_address left join orders on company_address.id=orders.addressid left join companies on companies.id=orders.companyid where orders.ordertype='$ordertype' and orders.status>='$status' and orders.isdeleted<>1 and orders.companyid='$invoice_id'");
            if ($getInvoices->rowCount()) {
                foreach ($getInvoices as $invoice) {
                    ?>

                    <tr>
                        <td><?php echo $invoice["invoiceno"]; ?></td>
                        <td>
                            <a href="index.php?p=invoice&pg=panel-invoice-edit&orderid=<?php echo $invoice["id"]; ?>" id="invoices">
                                <?php if ($invoice["companyname"] != "") {
                                    echo $invoice["companyname"];
                                } else {
                                    echo $invoice["firstname"] . "  " . $invoice["lastname"];
                                } ?>
                            </a>
                        </td>
                        <td>
                            <?php
                            if ($invoice["status"] == 0) {
                                echo "Open";
                            }
                            if ($invoice["status"] == 1) {
                                echo "Approved";
                            }
                            if ($invoice["status"] == 2) {
                                echo "Delivered";
                            }
                            if ($invoice["status"] == 3) {
                                echo "Archived";
                            }
                            ?>
                        </td>
                        <td><?php echo $invoice["postcode"]; ?></td>
                        <td>
                            <?php
                            $orderdate = strtotime($invoice["orderdate"]);
                            $orderdate = date("Y-m-d", $orderdate);
                            echo $orderdate;
                            ?>
                        </td>
                        <td><?php
                            if ($invoice["websiteid"] == 1) {
                                echo "SD";
                            } elseif ($invoice["websiteid"] == 2) {
                                echo "TS";
                            } ?>
                        </td>
                        <td>
                            <a href="index.php?p=invoice&pg=panel-invoice-edit&orderid=<?php echo $invoice["id"]; ?>">
                                Edit
                            </a>
                        </td>
                    </tr>

                    <?php
                }
            }
            else{
                echo '<h4 class="text-danger">No results found</h4>';
            }
        }
        elseif ($process == "getTransactions"){
            $transaction_id = $_POST["transaction_id"];
            $getTransactions = $db->query("SELECT accounts.*, companies.companyname, companies.firstname, companies.lastname, combos1.name as transactionname, combos2.name as bankname FROM accounts left join companies on accounts.companyid=companies.id left join combos as combos1 on accounts.transactiontype=combos1.id left join combos as combos2 on accounts.bankid=combos2.id where accounts.companyid='$transaction_id'");
            if($getTransactions->rowCount()){
                foreach ($getTransactions as $getTransaction){
                    ?>
                    <tr>
                        <td><?php echo $getTransaction["id"]; ?></td>
                        <td><a href="panel-transaction-edit.asp?id=<?php echo $getTransaction["id"]; ?>"><?php echo $getTransaction["transactiondate"]; ?></a></td>
                        <td class="text-uppercase">
                            <a href="panel-transaction-edit.asp?id=<?php echo $getTransaction["id"]; ?>">
                                <?php
                                if ($getTransaction["companyname"] != ""){
                                    echo $getTransaction["companyname"];
                                }
                                else{
                                    echo $getTransaction["firstname"]."  ".$getTransaction["lastname"];
                                }
                                ?>
                            </a>
                        </td>
                        <td><a href="panel-transaction-edit.asp?id=<?php echo $getTransaction["id"]; ?>"><?php echo $getTransaction["transactionname"]; ?></a></td>
                        <td>
                            <?php
                            if ($getTransaction["invoiceid"] != ""){
                                if ($getTransaction["transactiontype"] == 38) {
                                    ?>
                                    <a href="panel-credit-edit.asp?orderid=<?php echo $getTransaction["invoiceid"]; ?>">C:<?php echo $getTransaction["invoiceid"]; ?></a>
                                    <?php
                                }
                                else{
                                    ?>
                                    <a href="panel-invoice-edit.asp?orderid=<?php echo $getTransaction["invoiceid"]; ?>">I:<?php echo $getTransaction["invoiceid"]; ?></a>
                                    <?php
                                }
                            }
                            if ($getTransaction["billid"] != ""){
                                ?>
                                <a href="panel-bill-edit.asp?orderid=<?php echo $getTransaction["billid"]; ?>">B:<?php echo $getTransaction["billid"]; ?></a>
                                <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $getTransaction["reference"]; ?></td>
                        <td><?php echo $getTransaction["bankname"]; ?></td>
                        <td class="<?php if ($getTransaction["amount"] < 0){ echo 'text-danger'; }else{ echo 'text-primary'; } echo ' text-right'; ?>">
                            <?php
                            if($getTransaction["amount"] >= 0){
                                $totalpaid = $totalpaid + $getTransaction["amount"];
                            }
                            if($getTransaction["amount"] < 0){
                                $totalreceived = $totalreceived + $getTransaction["amount"];
                            }
                            echo number_format($getTransaction["amount"],2);
                            ?>
                        </td>
                        <td><?php $totalbalance = $totalpaid + $totalreceived; echo $totalbalance; ?></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="7">
                        <span class="<?php if ($totalpaid < 0){ echo 'text-danger'; }else{ echo 'text-primary'; } ?>">
                            Total Paid: <?php echo number_format($totalpaid,2); ?>
                        </span> /
                        <span class="<?php if ($totalreceived < 0){ echo 'text-danger'; }else{ echo 'text-primary'; } ?>">
                            Total Received: <?php echo number_format($totalreceived,2);?>
                        </span>
                    </td>
                    <td class="text-right"><b>Total Balance: </b></td>
                    <td class="text-right <?php if ($totalbalance < 0){ echo ' text-danger'; }else{ echo ' text-primary'; } ?>">
                        <b><?php echo number_format($totalbalance,2); ?></b>
                    </td>
                    <td></td>
                </tr>
                <?php
            }
            else{
                echo '<h4 class="text-danger">No results found</h4>';
            }
        }

    }
    //Suppliers
    elseif ($pg == "supplier"){
        $operation = $_GET["operation"];
        if ($operation == "newsupplierrefund"){
            $acts = $db->query("SELECT id FROM accounts order by id desc");
            if ($acts->rowCount()){
                $acc = $db->query("SELECT id FROM accounts order by id desc")->fetch();
                $sonid = $acc["id"] + 1;
            }
            else{
                $sonid = 1;
            }

            if ($_POST["transactiondate"] != ""){
                $transactiondate = strtotime($_POST["transactiondate"]);
                $transactiondate = date("Y-m-d",$transactiondate);
            }
            else{
                $transactiondate = null;
            }

            $companyid = strip_tags(trim($_POST["companyid"]));
            $newpaymentterm = strip_tags(trim($_POST["newpaymentterm"]));
            $reference = strip_tags(trim($_POST["reference"]));
            $note = strip_tags(trim($_POST["note"]));

            if ($_POST["invoiceid"] != ""){ $invoiceid = $_POST["invoiceid"]; }
            if ($_POST["employeeid"] != ""){ $employeeid = $_POST["employeeid"]; }
            $amount = str_replace(",",".",strip_tags(trim($_POST["amount"])));
            if ($amount == ""){ $amount = 0; }

            $transactiontype = strip_tags(trim($_POST["transactiontype"]));
            $cmb = $db->query("select * from combos where id='$transactiontype'")->fetch();
            $transactiontype = $cmb["name"];
            if (strstr($transactiontype,"()")){

            }
            elseif (strstr($transactiontype,"(+)")){
                if ($amount > 0){

                }
                else{
                    $amount = 0 - ($amount);
                }
            }
            elseif (strstr($transactiontype,"(-)")){
                if ($amount > 0){
                    $amount = 0 - ($amount);
                }
                else{

                }
            }

            if ($_POST["bankid"] != ""){ $bankid = $_POST["bankid"]; }else{ $bankid = null; }
            if ($_POST["billid"] != ""){ $billid = $_POST["billid"]; }else{ $billid = null; }
            if ($_POST["invoiceid"] != ""){ $invoiceid = $_POST["invoiceid"]; }else{ $invoiceid = null; }

            /* invoiceid ve billid olan transaction ekleme için */
            //$saveaccount = $db->query("insert into accounts(id, transactiondate, companyid, amount, transactiontype, paymentmethod, reference, bankid, note,invoiceid,billid) values('$sonid','$transactiondate','$companyid','$amount','".$_POST["transactiontype"]."','$newpaymentterm','$reference','$bankid','$note','$invoiceid','$billid')");
            $saveaccount = $db->query("insert into accounts(id, transactiondate, companyid, amount, transactiontype, paymentmethod, reference, bankid, note) values('$sonid','$transactiondate','$companyid','$amount','".$_POST["transactiontype"]."','$newpaymentterm','$reference','$bankid','$note')");

            /* Not Deposite Değilse Banka Hesabına at */
            if ($bankid != 24){
                $banktran = $db->query("SELECT max(id) as maxid FROM banktransactions")->fetch();
                $sonbankid = $banktran["maxid"] + 1;
                if (empty($sonbankid)){ $sonbankid = 1; }

                $transamount = $amount;
                if ($transamount > 0){
                    $transamount = 0 - ($amount);
                }

                $transactiondate = strtotime($_POST["transactiondate"]);
                $transactiondate = date("Y-m-d",$transactiondate);

                $savebanktran = $db->query("insert into banktransactions(id,bankid,transactiondate,amount) values('$sonbankid','$bankid','$transactiondate','$transamount')");

                $upacc = $db->query("update accounts set banktransactionid='$sonbankid' where id='$sonid'");
            }
            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d H:i:s");
            $tablename = "accounts";
            $tableid = $sonid;
            $parentid = 0;
            $logtypeid = 42;
            $iduser = $paneluser["id"];
            $notes = "transaction type :".$_POST["transactiontype"]." - amount : ".$_POST["amount"];
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($saveaccount){
                setcookie('testcookie', "true|Supplier Refund Saved...", time() + 20, '/');
                header("Location: ../index.php?p=supplier&spp=findsupplierrefund");
            }
            else{
                setcookie('testcookie', "false|Supplier Refund Not Saved...", time() + 20, '/');
                header("Location: ../index.php?p=supplier&spp=newsupplierrefund");
            }
        }
        elseif ($operation == "edittransaction"){
            $id = $_GET["id"];
            if (isset($_POST["updatetransaction"])) {
                if ($_POST["banktransactionid"] != "") {
                    $banktransactionid = strip_tags(trim($_POST["banktransactionid"]));
                } else {
                    $banktransactionid = NULL;
                }

                $companyid = strip_tags(trim($_POST["companyid"]));

                if ($_POST["invoiceid"] != "") {
                    $invoiceid = $_POST["invoiceid"];
                } else {
                    $invoiceid = NULL;
                }

                $transactiontype = strip_tags(trim($_POST["transactiontype"]));
                $transactionname = strip_tags(trim($_POST["transactiontype"]));
                $amount = str_replace(",", ".", strip_tags(trim($_POST["amount"])));
                if ($amount == "") {
                    $amount = 0;
                }
                $cmb = $db->query("SELECT * FROM combos where id='$transactiontype'")->fetch();
                $transactiontype = $cmb["name"];

                if (strstr($transactiontype, "()")) {

                } elseif (strstr($transactiontype, "(+)")) {
                    if ($amount > 0) {

                    } else {
                        $amount = 0 - ($amount);
                    }
                } elseif (strstr($transactiontype, "(-)")) {
                    if ($amount > 0) {
                        $amount = 0 - ($amount);
                    } else {

                    }
                }
                $acc = $db->query("SELECT * FROM accounts where id='$id'")->fetch();
                $eskiamount = $acc["amount"];
                if ($eskiamount != $amount) {
                    $paidamount = $amount;
                    if ($paidamount > 0) {

                    } else {
                        $paidamount = 0 - ($paidamount);
                    }
                    if ($_POST["transactiontype"] == 20) {
                        $upaccinv = $db->query("update account_invoice set paidamount='$paidamount' where accountid='$id'");
                    }
                    if ($_POST["transactiontype"] == 18) {
                        $upaccinv = $db->query("update account_invoice set paidamount='$paidamount' where accountid='$id'");
                    }
                }
                if ($_POST["bankid"] != "") {
                    $bankid = $_POST["bankid"];
                } else {
                    $bankid = NULL;
                }
                if ($_POST["billid"] != "") {
                    $billid = $_POST["billid"];
                } else {
                    $billid = NULL;
                }


                if (!is_null($_POST["transactiondate22"]) || !empty($_POST["transactiondate22"])) {
                    $rawdate = htmlentities($_POST['transactiondate22']);
                    $transactiondate = strtotime($rawdate);
                    $transactiondate = date("Y-m-d",$transactiondate);
                    $upacc = $db->prepare("update accounts set transactiondate=:transactiondate, companyid=:companyid, amount=:amount, transactiontype=:transactiontype, paymentmethod=:paymentmethod, reference=:reference, bankid=:bankid, note=:note, invoiceid=:invoiceid, billid=:billid, banktransactionid=:banktransactionid where id=:id");
                    $upacc->bindValue(":transactiondate",$transactiondate,PDO::PARAM_INT);
                    $upacc->bindValue(":companyid",$companyid,PDO::PARAM_INT);
                    $upacc->bindValue(":amount",$amount,PDO::PARAM_INT);
                    $upacc->bindValue(":transactiontype",$transactionname,PDO::PARAM_INT);
                    $upacc->bindValue(":paymentmethod",$_POST["paymentmethod"],PDO::PARAM_INT);
                    $upacc->bindValue(":reference",$_POST["reference"],PDO::PARAM_INT);
                    $upacc->bindValue(":bankid",$bankid,PDO::PARAM_INT);
                    $upacc->bindValue(":note",$_POST["note"],PDO::PARAM_STR);
                    $upacc->bindValue(":invoiceid",$invoiceid,PDO::PARAM_INT);
                    $upacc->bindValue(":billid",$billid,PDO::PARAM_INT);
                    $upacc->bindValue(":banktransactionid",$banktransactionid,PDO::PARAM_INT);
                    $upacc->bindValue(":id",$id,PDO::PARAM_INT);
                    $upacc->execute();
                }
                else {
                    $upacc = $db->prepare("update accounts set transactiondate=:transactiondate, companyid=:companyid, amount=:amount, transactiontype=:transactiontype, paymentmethod=:paymentmethod, reference=:reference, bankid=:bankid, note=:note, invoiceid=:invoiceid, billid=:billid, banktransactionid=:banktransactionid where id=:id");
                    $upacc->bindValue(":transactiondate",NULL,PDO::PARAM_NULL);
                    $upacc->bindValue(":companyid",$companyid,PDO::PARAM_INT);
                    $upacc->bindValue(":amount",$amount,PDO::PARAM_INT);
                    $upacc->bindValue(":transactiontype",$transactionname,PDO::PARAM_INT);
                    $upacc->bindValue(":paymentmethod",$_POST["paymentmethod"],PDO::PARAM_INT);
                    $upacc->bindValue(":reference",$_POST["reference"],PDO::PARAM_INT);
                    $upacc->bindValue(":bankid",$bankid,PDO::PARAM_INT);
                    $upacc->bindValue(":note",$_POST["note"],PDO::PARAM_INT);
                    $upacc->bindValue(":invoiceid",$invoiceid,PDO::PARAM_INT);
                    $upacc->bindValue(":billid",$billid,PDO::PARAM_INT);
                    $upacc->bindValue(":banktransactionid",$banktransactionid,PDO::PARAM_INT);
                    $upacc->bindValue(":id",$id,PDO::PARAM_INT);
                    $upacc->execute();
                }
                //$upacc = $db->query("update accounts set transactiondate='$transactiondate', companyid='$companyid', amount='$amount', transactiontype='$transactionname', paymentmethod='" . $_POST["paymentmethod"] . "', reference='" . $_POST["reference"] . "', bankid='$bankid', note='" . $_POST["note"] . "', invoiceid='$invoiceid', billid='$billid', banktransactionid='$banktransactionid' where id='$id'");

                if ($upacc) {
                    setcookie('testcookie', "true|Transaction Updated...", time() + 20, '/');
                    header("Location: ../index.php?p=supplier&spp=findsupplierrefund");
                } else {
                    setcookie('testcookie', "false|Transaction Not Updated...", time() + 20, '/');
                    header("Location: ../index.php?p=supplier&spp=findsupplierrefund&pg=panel-supplierrefund-edit&id=" . $id);
                }
            }
        }
    }
    // Sales Order
    else if ($pg == "salesOrder"){
        $process = $_GET["process"];
        if($process == "getAddressDetails"){
            $adr_id = $_POST["adr_id"];
            getAddressDetails($adr_id);
        }
        elseif($process == "getAddress"){
            $pr_id = $_POST["pr_id"];
            $addressSelect = $db->query("select * from company_address WHERE companyid='$pr_id'");
            foreach ($addressSelect as $addressSelecting){
                echo '<option value="'.$addressSelecting["id"].'">'.$addressSelecting["addressname"].' - '.$addressSelecting["postcode"].' - '.$addressSelecting["street"].' - '.$addressSelecting["city"].' - '.$addressSelecting["country"].'</option>';
            }
        }
        elseif($process == "panel-order-edit"){
            $orid = $_GET["orderid"];
            $type = $_GET["type"];
            $website = $_GET["websiteid"];
            $st = $_GET["st"];
            $crd = $_GET["crd"];
            $dlt = $_GET["dlt"];
            $is = $_GET["is"];

            if (isset($_POST["button5"])){
                $qty = strip_tags(trim($_POST["qty"]));
                $productid = $_POST["productid"];
                $product_sizeid = $_POST["product_sizeid"];
                $createdate = date("Y.m.d h:i:s");
                $orderselect = $db->query("select * from orders where id='$orid'")->fetch();
                $sessionid = $orderselect["sessionid"];
                if ($productid == 88){
                    $basket = $db->query("insert into basket(orderid,productid,sizeid,sessionid,sample,quantity,vatrate,createdate,isordered,websiteid) values('$orid','$productid','$product_sizeid','$sessionid','1','$qty','20','$createdate','1','$website')");
                    if ($basket->rowCount()){
                        if (isset($crd) && !empty($crd) && $crd != ""){
                            header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                        }
                        else{
                            header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                        }
                    }
                    else{
                        if (isset($crd) && !empty($crd) && $crd != ""){
                            header("Location: ../index.php?p=credit&crd=".$crd);
                        }
                        else{
                            header("Location: ../index.php?p=sales&st=".$st);
                        }
                    }
                }
                else{
                    $basket = $db->query("insert into basket(orderid,productid,sizeid,sessionid,quantity,vatrate,createdate,isordered,websiteid) values('$orid','$productid','$product_sizeid','$sessionid','$qty','20','$createdate','1','$website')");
                    if ($basket->rowCount()){
                        if (isset($crd) && !empty($crd) && $crd != ""){
                            header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                        }
                        else{
                            header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                        }
                    }
                    else{
                        if (isset($crd) && !empty($crd) && $crd != ""){
                            header("Location: ../index.php?p=credit&crd=".$crd);
                        }
                        else{
                            header("Location: ../index.php?p=sales&st=".$st);
                        }
                    }
                }
            }

            if (isset($is) && $is == "next") {
                if ($_POST["isnextday"] == "0" && $_POST["isnextday"] != "1") {
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='" . $orderpst['postcodeid'] . "'")->fetch();
                    $zn = $codepst["zone"];
                    $zoneselect = $db->query("select * from zones where id='$zn'")->fetch();
                    $isnextday = $_POST["isnextday"];
                    $zone = $zoneselect["economy"];
                    //$updelivery = $db->query("update orders set deliverydate=NULL, isnextday='$isnextday', deliverytime=NULL, deliveryprice='$zone' where id='$orid'");
                    $updelivery = $db->prepare("update orders set deliverydate=:deliverydate, isnextday=:isnextday, deliverytime=:deliverytime, deliveryprice=:deliveryprice where id=:id");

                    //$updelivery = $db->prepare("update orders set deliverydate=?, isnextday=?, deliverytime=?, deliveryprice=? where id=?");

                    $updelivery->bindValue(':deliverydate', NULL, PDO::PARAM_NULL);
                    $updelivery->bindValue(':isnextday', $isnextday, PDO::PARAM_INT);
                    $updelivery->bindValue(':deliverytime', NULL, PDO::PARAM_NULL);
                    $updelivery->bindValue(':deliveryprice', $zone, PDO::PARAM_INT);
                    $updelivery->bindValue(':id', $orid, PDO::PARAM_INT);

                    $updelivery->execute();

                    if ($type == "credit") {
                        header("Location: ../index.php?p=credit&crd=" . $crd . "&pg=panel-credit-edit&orderid=" . $orid);
                    } else {
                        header("Location: ../index.php?p=sales&st=" . $st . "&pg=panel-order-edit&orderid=" . $orid . "&type=" . $type);
                    }
                }
                if ($_POST["isnextday"] == "1" && $_POST["isnextday"] != "0") {
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='" . $orderpst['postcodeid'] . "'")->fetch();
                    $zn = $codepst["zone"];
                    $zoneselect = $db->query("select * from zones where id='$zn'")->fetch();
                    $isnextday = $_POST["isnextday"];
                    $zone = $zoneselect["nextday"];
                    //$updelivery = $db->query("update orders set deliverydate=NULL, isnextday='$isnextday', deliverytime=NULL, deliveryprice='$zone' where id='$orid'");
                    $updelivery2 = $db->prepare("update orders set deliverydate=:deliverydate, isnextday=:isnextday, deliverytime=:deliverytime, deliveryprice=:deliveryprice where id=:id");

                    //$updelivery = $db->prepare("update orders set deliverydate=?, isnextday=?, deliverytime=?, deliveryprice=? where id=?");

                    $updelivery2->bindValue(':deliverydate', NULL, PDO::PARAM_NULL);
                    $updelivery2->bindValue(':isnextday', $isnextday, PDO::PARAM_INT);
                    $updelivery2->bindValue(':deliverytime', NULL, PDO::PARAM_NULL);
                    $updelivery2->bindValue(':deliveryprice', $zone, PDO::PARAM_INT);
                    $updelivery2->bindValue(':id', $orid, PDO::PARAM_INT);
                    $updelivery2->execute();

                    if ($type == "credit") {
                        header("Location: ../index.php?p=credit&crd=" . $crd . "&pg=panel-credit-edit&orderid=" . $orid);
                    }
                    else {
                        header("Location: ../index.php?p=sales&st=" . $st . "&pg=panel-order-edit&orderid=" . $orid . "&type=" . $type);
                    }
                }
            }
            if (isset($dlt) && $dlt == "delivery") {
                if ($_POST["deliverytime"] == "standard") {
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='" . $orderpst['postcodeid'] . "'")->fetch();
                    $zn = $codepst["zone"];
                    $zone = $db->query("select * from zones where id='$zn'")->fetch();
                    $deliveryprice = $zone["nextday"];

                    $deliveryprice = number_format($deliveryprice,4);

                    $uptime = $db->query("update orders set deliverytime='" . $_POST["deliverytime"] . "', deliveryprice='$deliveryprice' where id='$orid'");

                    if ($type == "credit") {
                        header("Location: ../index.php?p=credit&crd=" . $crd . "&pg=panel-credit-edit&orderid=" . $orid);
                    } else {
                        header("Location: ../index.php?p=sales&st=" . $st . "&pg=panel-order-edit&orderid=" . $orid . "&type=" . $type);
                    }
                }
                elseif ($_POST["deliverytime"] == "am") {
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='" . $orderpst['postcodeid'] . "'")->fetch();
                    $zn = $codepst["zone"];
                    $zone = $db->query("select * from zones where id='$zn'")->fetch();
                    $deliveryprice = $zone["nextday"] + $zone["ampm"];

                    $deliveryprice = number_format($deliveryprice,4);

                    $uptime2 = $db->query("update orders set deliverytime='" . $_POST["deliverytime"] . "', deliveryprice='$deliveryprice' where id='$orid'");

                    if ($type == "credit") {
                        header("Location: ../index.php?p=credit&crd=" . $crd . "&pg=panel-credit-edit&orderid=" . $orid);
                    } else {
                        header("Location: ../index.php?p=sales&st=" . $st . "&pg=panel-order-edit&orderid=" . $orid . "&type=" . $type);
                    }
                }
                elseif ($_POST["deliverytime"] == "pm") {
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='" . $orderpst['postcodeid'] . "'")->fetch();
                    $zn = $codepst["zone"];
                    $zone = $db->query("select * from zones where id='$zn'")->fetch();
                    $deliveryprice = $zone["nextday"] + $zone["ampm"];

                    $deliveryprice = number_format($deliveryprice,4);

                    $uptime3 = $db->query("update orders set deliverytime='" . $_POST["deliverytime"] . "', deliveryprice='$deliveryprice' where id='$orid'");

                    if ($type == "credit") {
                        header("Location: ../index.php?p=credit&crd=" . $crd . "&pg=panel-credit-edit&orderid=" . $orid);
                    } else {
                        header("Location: ../index.php?p=sales&st=" . $st . "&pg=panel-order-edit&orderid=" . $orid . "&type=" . $type);
                    }
                }
                elseif ($_POST["deliverytime"] == "saturdayam") {
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='" . $orderpst['postcodeid'] . "'")->fetch();
                    $zn = $codepst["zone"];
                    $zone = $db->query("select * from zones where id='$zn'")->fetch();
                    $deliveryprice = $zone["nextday"] + $zone["saturdayam"];

                    $deliveryprice = number_format($deliveryprice,4);

                    $uptime4 = $db->query("update orders set deliverytime='" . $_POST["deliverytime"] . "', deliveryprice='$deliveryprice' where id='$orid'");

                    if ($type == "credit") {
                        header("Location: ../index.php?p=credit&crd=" . $crd . "&pg=panel-credit-edit&orderid=" . $orid);
                    } else {
                        header("Location: ../index.php?p=sales&st=" . $st . "&pg=panel-order-edit&orderid=" . $orid . "&type=" . $type);
                    }
                }
                elseif ($_POST["deliverytime"] == "saturdaypm") {
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='" . $orderpst['postcodeid'] . "'")->fetch();
                    $zn = $codepst["zone"];
                    $zone = $db->query("select * from zones where id='$zn'")->fetch();
                    $deliveryprice = $zone["nextday"] + $zone["saturdaypm"];

                    $deliveryprice = number_format($deliveryprice,4);

                    $uptime5 = $db->query("update orders set deliverytime='" . $_POST["deliverytime"] . "', deliveryprice='$deliveryprice' where id='$orid'");

                    if ($type == "credit") {
                        header("Location: ../index.php?p=credit&crd=" . $crd . "&pg=panel-credit-edit&orderid=" . $orid);
                    }
                    else {
                        header("Location: ../index.php?p=sales&st=" . $st . "&pg=panel-order-edit&orderid=" . $orid . "&type=" . $type);
                    }
                }
            }

            if (isset($_POST["datepicker"])){
                $pt = strtotime($_POST["datepicker"]);
                $tr = date('Y-m-d',$pt);
                $uptime = $db->query("update orders set deliverydate='$tr' where id='$orid'");
                if ($type == "credit"){
                    header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                }
            }
            if (isset($_POST["deliverydate"])){
                $ptdt = strtotime($_POST["deliverydate"]);
                $trdt = date('Y-m-d',$ptdt);
                $updeliverytime = $db->query("update orders set deliverydate='$trdt' where id='$orid'");
                if ($type == "credit"){
                    header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                }
            }
            if (isset($_POST["postcodeid"])){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$_POST["postcodeid"]."'")->fetch();
                $zn = $codepst["zone"];
                $zoneselect = $db->query("select * from zones where id='$zn'")->fetch();
                $pstup = $db->query("update orders set postcodeid='".$_POST["postcodeid"]."', deliveryprice='".$zoneselect["nextday"]."' where id='$orid'");
                if ($type == "credit"){
                    header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                }
            }

            if (isset($_POST["couponcode"]) && $_POST["couponcode"] != "" && $_POST["orderid"] != ""){
                $orderid = $orid;
                $couponcode = strip_tags(trim($_POST["couponcode"]));
                $cpn = $db->query("SELECT * FROM orders where id='$orderid'")->fetch();
                if ($couponcode == $cpn["couponcode"]){
                    $valid = 1;
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type."&beforevalid=1");
                }
                else{
                    $sizes = $db->query("select * from coupons where couponcode='$couponcode'");
                    if ($sizes->rowCount()){
                        foreach($sizes as $sz){
                            $couponcode = $sz["couponcode"];
                            $ste = strtotime($sz["validdate"]);
                            $validdate = date("Y-m-d",$ste);
                            $validdate = strtotime($validdate);
                            $now = strtotime(date("Y-m-d"));
                            if (($sz["isused"] == "0") && ($validdate >= $now)){
                                $cpnup = $db->query("update orders set couponcode='$couponcode' where id='$orderid'");
                                if ($cpnup){
                                    $or = $db->query("select * from orders where id='$orderid'")->fetch();
                                    $subdis = $or["subtotal"] * ($sz["discountrate"] / 100);
                                    $sub = $or["subtotal"] - $subdis;
                                    $vatdis = $or["vattotal"] * ($sz["discountrate"] / 100);
                                    $vat = $or["vattotal"] - $vatdis;
                                    $entdis = $or["entotal"] * ($sz["discountrate"] / 100);
                                    $ent = $or["entotal"] - $entdis;
                                    $upent = $db->query("update orders set subtotal='$sub', vattotal='$vat', entotal='$ent', discountrate='".$sz["discountrate"]."', discountprice='$entdis' where id='$orderid'");
                                    if ($upent){
                                        $voucher = "_voucher_";

                                        $discount = $db->query("SELECT sizes.size, sizes.id as sizeid, product_sizes.id as product_sizeid, products.name, products.szid, products.pr_id FROM products left join product_sizes on product_sizes.productid=products.szid left join sizes on sizes.id=product_sizes.sizeid where product_sizes.note='$voucher' and products.websiteid='$website'")->fetch();

                                        $productid = $discount["pr_id"];
                                        $productname = $discount["name"];
                                        $sizeid = $discount["sizeid"];
                                        $product_sizeid = $discount["product_sizeid"];
                                        $sizename = $discount["size"];

                                        $createdate = $or["orderdate"];
                                        $sessionid = $or["sessionid"];

                                        $discountsave = $db->query("insert into basket (orderid, productid, sizeid, sessionid, quantity, vatrate, vatid, isdiscount, isdeleted, isnondiscountable, createdate, isordered, websiteid) values('$orderid','$productid','$sizeid','$sessionid','1','20','29','1','0','1','$createdate','1','$website')");
                                        if ($discountsave){
                                            header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type."&valid=1");
                                        }
                                        else{
                                            header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type."");
                                        }

                                    }
                                    else{
                                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                                    }
                                }
                                else{
                                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                                }
                            }
                            else{
                                if ($validdate < $now){
                                    $notvalid = 3;
                                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type."&notvalid=".$notvalid);
                                }
                                elseif ($sz["isused"] == "1"){
                                    $notvalid = 2;
                                    $couponcode = "";
                                    $cpnup = $db->query("update orders set couponcode='$couponcode' where id='$orderid'");
                                    if ($cpnup){
                                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type."&notvalid=".$notvalid);
                                    }
                                    else{
                                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                                    }
                                }
                                else{
                                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                                }
                            }
                        }
                    }
                    else{
                        $notvalid = 1;
                        $couponcode = "";
                        $cpnup = $db->query("update orders set couponcode='$couponcode' where id='$orderid'");
                        if ($cpnup->rowCount()){
                            header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type."&notvalid=".$notvalid);
                        }
                        else{
                            header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                        }
                    }
                }
            }
            else{
                header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
            }

        }
        elseif ($process == "ordereditok"){
            $operation = $_GET["operation"];
            if ($operation == "orderdeleteall"){
                $idorder = $_GET["orderid"];
                $iduser = $_GET["userid"];
                $type = $_GET["type"];
                $orderselect = $db->query("select * from orders where id='$idorder'")->fetch();
                $sessionid = $orderselect["sessionid"];
                $delorder = $db->query("delete from `orders` where id='$idorder' and sessionid='$sessionid'");
                if ($delorder->rowCount()){
                    $deluser = $db->query("delete from `users` where id='$iduser'");
                    if ($deluser->rowCount()){
                        $delbskt = $db->query("delete from `basket` where orderid='$idorder' and sessionid='$sessionid'");
                        if ($delbskt->rowCount()){
                            header("Location: ../index.php?p=sales&type=".$type);
                            setcookie('testcookie', "true|Orders Deleted...", time() + 20, '/');
                        }
                        else{
                            header("Location: ../index.php?p=sales&type=".$type);
                            setcookie('testcookie', "false|Orders Not Deleted...", time() + 20, '/');
                        }
                    }
                    else{
                        header("Location: ../index.php?p=sales&type=".$type);
                        setcookie('testcookie', "false|Orders Not Deleted...", time() + 20, '/');
                    }
                }
                else{
                    header("Location: ../index.php?p=sales&type=".$type);
                    setcookie('testcookie', "false|Orders Not Deleted...", time() + 20, '/');
                }
            }
            elseif ($operation == "orderundeleteall"){
                $orderid = $_GET["orderid"];
                $userid = $_GET["userid"];
                $st = $_GET["st"];
                $inv = $_GET["inv"];
                $type = $_GET["type"];
                $unbasket = $db->query("update basket set isdeleted=0 where orderid='$orderid'");
                if ($unbasket->rowCount()){
                    $unorder = $db->query("update orders set isdeleted=0 where id='$orderid'");
                    if ($unorder->rowCount()){
                        $unuser = $db->query("update users set isdeleted=0 where id='$userid'");
                        if ($unuser->rowCount()){
                            if (isset($inv)){
                                header("Location: ../index.php?p=invoice&inv=".$inv);
                                setcookie('testcookie', "true|Orders Un Deleted All...", time() + 20, '/');
                            }
                            else{
                                header("Location: ../index.php?p=sales&st=".$st."&type=".$type);
                                setcookie('testcookie', "true|Orders Un Deleted All...", time() + 20, '/');
                            }

                        }
                        else{
                            if (isset($inv)){
                                header("Location: ../index.php?p=invoice&inv=".$inv);
                                setcookie('testcookie', "false|Operation Failed...", time() + 20, '/');
                            }
                            else{
                                header("Location: ../index.php?p=sales&st=".$st."&type=".$type);
                                setcookie('testcookie', "false|Operation Failed...", time() + 20, '/');
                            }

                        }
                    }
                    else{
                        if (isset($inv)){
                            header("Location: ../index.php?p=invoice&inv=".$inv);
                            setcookie('testcookie', "false|Operation Failed...", time() + 20, '/');
                        }
                        else{
                            header("Location: ../index.php?p=sales&st=".$st."&type=".$type);
                            setcookie('testcookie', "false|Operation Failed...", time() + 20, '/');
                        }
                    }
                }
                else{
                    if (isset($inv)){
                        header("Location: ../index.php?p=invoice&inv=".$inv);
                        setcookie('testcookie', "false|Operation Failed...", time() + 20, '/');
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=".$st."&type=".$type);
                        setcookie('testcookie', "false|Operation Failed...", time() + 20, '/');
                    }
                }
            }
            elseif ($operation == "orderstatus"){
                $status = $_GET["status"];
                $orderid = $_GET["orderid"];
                $type = $_GET["type"];
                $st = $_GET["st"];
                if ($status == 1){
                    $orderup = $db->query("update orders set status='$status' where id='$orderid'");
                    if ($orderup->rowCount()){
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                        setcookie('testcookie', "false|Order Not Open...", time() + 20, '/');
                    }
                }
                elseif ($status == 4){
                    $orderlostup = $db->query("update orders set status='$status' where id='$orderid'");
                    if ($orderlostup->rowCount()){
                        header("Location: ../index.php?p=sales&st=lost&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=lost&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                        setcookie('testcookie', "false|Order Not Lost...", time() + 20, '/');
                    }
                }
            }
            elseif ($operation == "newcouponok"){
                $orderid = $_GET["orderid"];
                $user = $_GET["user"];
                $st = $_GET["st"];
                $smp = $_GET["smp"];
                $type = $_GET["type"];
                if (isset($_POST["savecoupon"])){
                    if ($orderid != ""){
                        $discountrate = 5;
                        $discountmoney = null;
                        $coupontype = 0;
                        $isused = 0;
                        $createdate = date("Y-m-d");
                        $datevalid = date("Y/m/d");
                        $validdate = strtotime('15 day',strtotime($datevalid));
                        $validdate = date('Y-m-d' ,$validdate);
                        $forwhom = ltrim($user,50);

                        $maxid = $db->query("select max(id) as maxid from coupons")->fetch();
                        $lastID = $maxid["maxid"] + 1;
                        if (empty($lastID)){ $sonid = 1; }

                        $RandomLetter = $RandomLetter.chr(rand(65,90));
                        $RandomLetter = $RandomLetter.rand(0,9);
                        $RandomLetter = $RandomLetter.chr(rand(65,90));
                        $RandomLetter = $RandomLetter.chr(rand(65,90));
                        $RandomLetter = $RandomLetter.$lastID;
                        $savecoupon = $db->query("insert into coupons(id,couponcode,discountrate,discountmoney,coupontype,isused,createdate,validdate,forwhom) values('$lastID','$RandomLetter','$discountrate','$discountmoney','$coupontype','$isused','$createdate','$datevalid','$forwhom')");
                        if ($savecoupon->rowCount()){
                            header("Location: ../index.php?p=coupons");
                            setcookie('testcookie', "true|Coupon Created...", time() + 20, '/');
                        }
                        else{
                            setcookie('testcookie', "false|Coupon Not Created...", time() + 20, '/');
                        }
                    }
                    else{
                        if (isset($_POST["discountrate"]) && !empty($_POST["discountrate"]) && $_POST["discountrate"] != ""){
                            $discountrate = strip_tags(trim($_POST["discountrate"]));
                        }
                        else{
                            $discountrate = null;
                        }

                        if (isset($_POST["discountmoney"])&& !empty($_POST["discountmoney"]) && $_POST["discountmoney"] != ""){
                            $discountmoney = strip_tags(trim($_POST["discountmoney"]));
                        }
                        else{
                            $discountmoney = null;
                        }
                        $coupontype = $_POST["coupontype"];
                        $isused = 0;
                        $createdate = date("Y-m-d");
                        $datevalid = $_POST["datevalid"];
                        $forwhom = strip_tags(trim($_POST["forwhom"]));
                        $maxid = $db->query("select max(id) as maxid from coupons")->fetch();
                        $lastID = $maxid["maxid"] + 1;
                        if (empty($lastID)){ $sonid = 1; }

                        $RandomLetter = $RandomLetter.chr(rand(65,90));
                        $RandomLetter = $RandomLetter.rand(0,9);
                        $RandomLetter = $RandomLetter.chr(rand(65,90));
                        $RandomLetter = $RandomLetter.chr(rand(65,90));
                        $RandomLetter = $RandomLetter.$lastID;
                        $savecoupon = $db->query("insert into coupons(id,couponcode,discountrate,discountmoney,coupontype,isused,createdate,validdate,forwhom) values('$lastID','$RandomLetter','$discountrate','$discountmoney','$coupontype','$isused','$createdate','$datevalid','$forwhom')");
                        if ($savecoupon->rowCount()){
                            header("Location: ../index.php?p=coupons");
                            setcookie('testcookie', "true|Coupon Created...", time() + 20, '/');
                        }
                        else{
                            setcookie('testcookie', "false|Coupon Not Created...", time() + 20, '/');
                        }
                    }
                }
                else{
                    if ($orderid != ""){
                        $discountrate = 5;
                        $discountmoney = null;
                        $coupontype = 0;
                        $isused = 0;
                        $createdate = date("Y-m-d");
                        $datevalid = date("Y/m/d");
                        $validdate = strtotime('15 day',strtotime($datevalid));
                        $validdate = date('Y-m-d' ,$validdate);
                        $forwhom = ltrim($user,50);

                        $maxid = $db->query("select max(id) as maxid from coupons")->fetch();
                        $lastID = $maxid["maxid"] + 1;
                        if (empty($lastID)){ $sonid = 1; }

                        $RandomLetter = $RandomLetter.chr(rand(65,90));
                        $RandomLetter = $RandomLetter.rand(0,9);
                        $RandomLetter = $RandomLetter.chr(rand(65,90));
                        $RandomLetter = $RandomLetter.chr(rand(65,90));
                        $RandomLetter = $RandomLetter.$lastID;

                        $savecoupon = $db->query("insert into coupons(id,couponcode,discountrate,discountmoney,coupontype,isused,createdate,validdate,forwhom) values('$lastID','$RandomLetter','$discountrate','$discountmoney','$coupontype','$isused','$createdate','$datevalid','$forwhom')");

                        /*
                        if ($savecoupon->rowCount()){
                            header("Location: ../index.php?p=coupons");
                            setcookie('testcookie', "true|Coupon Created...", time() + 20, '/');
                        }
                        else {
                            header("Location: ../index.php?p=coupons");
                            setcookie('testcookie', "false|Coupon Not Created...", time() + 20, '/');
                        }
                        */

                        $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                        $processdate = date("Y-m-d H:i:s");
                        $tablename = "coupons";
                        $tableid = $sonid;
                        $parentid = 0;
                        $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
                        $userid = $paneluser["id"];
                        $log_notes = "";

                        $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");

                        if ($type != "") {

                            $order = $db->query("update orders set couponcode='$RandomLetter' where id='$orderid'");
                            $sz = $db->query("select * from coupons where couponcode='$RandomLetter'")->fetch();

                            $or = $db->query("select * from orders where id='$orderid'")->fetch();
                            $subdis = $or["subtotal"] * ($sz["discountrate"] / 100);
                            $sub = $or["subtotal"] - $subdis;
                            $vatdis = $or["vattotal"] * ($sz["discountrate"] / 100);
                            $vat = $or["vattotal"] - $vatdis;
                            $entdis = $or["entotal"] * ($sz["discountrate"] / 100);
                            $ent = $or["entotal"] - $entdis;
                            $upent = $db->query("update orders set subtotal='$sub', vattotal='$vat', entotal='$ent', discountrate='".$sz["discountrate"]."', discountprice='$entdis' where id='$orderid'");

                            if($order) {
                                if ($type == "proforma") {
                                    header("Location: ../index.php?p=sales&st=" . $st . "&pg=panel-order-edit&orderid=" . $orderid . "&type=" . $type);
                                }
                                elseif ($type == "order") {
                                    header("Location: ../index.php?p=sales&st=" . $st . "&pg=panel-order-edit&orderid=" . $orderid . "&type=" . $type);
                                }
                                elseif ($type == "invoice") {
                                    header("Location: ../index.php?p=invoice&inv=open&pg=panel-invoice-edit&orderid=" . $orderid . "&type=" . $type);
                                }
                                else {
                                    header("Location: ../index.php?p=sample_management&smp=" . $smp . "&pg=panel-sample-edit&orderid=" . $orderid . "&type=" . $type);
                                }
                            }
                        }

                    }

                }
            }
            elseif ($operation == "coupon_edit"){
                $cpnID = $_GET["cpnID"];
                $dt = $_GET["dt"];
                if ($dt == "coupon"){
                    $couponSelect = $db->query("select * from coupons where id='$cpnID'");
                    if ($couponSelect->rowCount()){
                        foreach ($couponSelect as $coupon_edit) {
                            $coupon_data["coupon_data"] = array(
                                "id" => $coupon_edit["id"],
                                "couponcode" => $coupon_edit["couponcode"],
                                "discountrate" => $coupon_edit["discountrate"],
                                "discountmoney" => $coupon_edit["discountmoney"],
                                "coupontype" => $coupon_edit["coupontype"],
                                "isused" => $coupon_edit["isused"],
                                "createdate" => $coupon_edit["createdate"],
                                "validdate" => $coupon_edit["validdate"],
                                "forwhom" => $coupon_edit["forwhom"]
                            );
                            echo json_encode($coupon_data);
                        }
                    }
                }
                elseif (isset($_POST["updatecoupon"])){
                    $countrate = strip_tags(trim($_POST["discountrate"]));
                    $countmoney = strip_tags(trim($_POST["discountmoney"]));
                    $coupontype = strip_tags(trim($_POST["coupontype"]));
                    $who = strip_tags(trim($_POST["forwhom"]));
                    $datevld = $_POST["datevalid"];
                    if ($_POST["isused"] == 0){
                        $isused = "Special";
                    }
                    elseif ($_POST["isused"] == 1){
                        $isused = "Open for all";
                    }
                    $IDcpn = $_POST["hiddencoupon"];
                    $upcoupon = $db->query("update coupons set discountrate='$countrate', discountmoney='$countmoney', coupontype='$coupontype', isused='$isused', validdate='$datevld', forwhom='$who' where id='$IDcpn'");
                    if ($upcoupon->rowCount()){
                        header("Location: ../index.php?p=coupons");
                        setcookie('testcookie', "true|Coupon Updated...", time() + 20, '/');
                    }
                    else{
                        setcookie('testcookie', "false|Coupon Not Updated...", time() + 20, '/');
                    }
                }
            }
            elseif ($operation == "deletecoupon"){

                $orderid = $_GET["orderid"];
                $st = $_GET["st"];
                $smp = $_GET["smp"];
                $type = $_GET["type"];
                $invoice = $_GET["invoice"];

                if ($invoice == 1){
                    $inv = $db->query("select * from invoices where id='$orderid'")->fetch();
                }
                else{
                    $inv = $db->query("select * from orders where id='$orderid'")->fetch();
                }

                $couponcode = $inv["couponcode"];
                $cpnup = $db->prepare("update orders set couponcode=:couponcode, discountrate=:discountrate, discountprice=:discountprice where id=:id");
                $cpnup->bindValue(":couponcode",NULL,PDO::PARAM_NULL);
                $cpnup->bindValue(":discountrate",NULL,PDO::PARAM_NULL);
                $cpnup->bindValue(":discountprice",NULL,PDO::PARAM_NULL);
                $cpnup->bindValue("id",$orderid,PDO::PARAM_INT);
                $cpnup->execute();

                $cpndelete = $db->query("delete from coupons where couponcode='$couponcode'");

                $basket = $db->query("select * from basket where orderid='$orderid' and isdiscount=1 and isordered=1");
                if ($basket->rowCount()){
                    $deletebasket = $db->query("delete from basket where orderid='$orderid' and isdiscount=1 and isordered=1");
                    if ($deletebasket->rowCount()){
                        if (isset($smp)){
                            header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
                        }
                        elseif ($type == "invoice"){
                            header("Location: ../index.php?p=invoice&inv=open&pg=panel-invoice-edit&orderid=".$orderid);
                        }
                        else{
                            header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                    else{
                        if (isset($smp)){
                            header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
                        }
                        elseif ($type == "invoice"){
                            header("Location: ../index.php?p=invoice&inv=open&pg=panel-invoice-edit&orderid=".$orderid);
                        }
                        else{
                            header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                }
                else{
                    if (isset($smp)){
                        header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
                    }
                    elseif ($type == "invoice"){
                        header("Location: ../index.php?p=invoice&inv=open&pg=panel-invoice-edit&orderid=".$orderid);
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                    }
                }
            }
            elseif ($operation == "extendvoucher"){
                $orderid = $_GET["orderid"];
                $st = $_GET["st"];
                $smp = $_GET["smp"];
                $inv = $_GET["inv"];
                $type = $_GET["type"];
                $sample = $_GET["sample"];
                $couponcode = $_GET["couponcode"];
                $cpn = $db->query("select * from coupons where couponcode='$couponcode'")->fetch();
                $cdt = strtotime($cpn["validdate"]);
                $cdt = date("Y-m-d",$cdt);
                $sevendays = strtotime('7 day',strtotime($cdt));
                $sevendays = date('Y-m-d',$sevendays);
                $upvalid = $db->query("update coupons set validdate='$sevendays' where couponcode='$couponcode'");
                if ($upvalid->rowCount()){
                    if (isset($st)){
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                        setcookie('testcookie', "true|Coupon Extended For 7 days...", time() + 20, '/');
                    }
                    elseif ($type == "invoice"){
                        header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
                        setcookie('testcookie', "true|Coupon Extended For 7 days...", time() + 20, '/');
                    }
                    elseif (isset($smp)){
                        header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
                        setcookie('testcookie', "true|Coupon Extended For 7 days...", time() + 20, '/');
                    }

                }
                else{
                    if (isset($st)){
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                        setcookie('testcookie', "true|Coupon Extended For 7 days...", time() + 20, '/');
                    }
                    elseif ($type == "invoice"){
                        header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
                        setcookie('testcookie', "true|Coupon Extended For 7 days...", time() + 20, '/');
                    }
                    elseif (isset($smp)){
                        header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
                        setcookie('testcookie', "true|Coupon Extended For 7 days...", time() + 20, '/');
                    }
                }
            }
            elseif ($operation == "ordertoinvoice"){
                $orderid = $_GET["orderid"];
                $selectorder = $db->query("select * from orders where id='$orderid'")->fetch();
                if ($selectorder["ordertype"] == 3 && $selectorder["status"] == 3){
                    echo "<h4>This order was converted to invoice before. Please check!</h4>";
                }
                else{
                    $maxorder = $db->query("SELECT max(id) as maxid FROM orders")->fetch();
                    $sonid = $maxorder["maxid"] + 1;
                    if ($sonid == 1 && empty($sonid)){
                        $db = null;
                    }
                    $maxinvoice = $db->query("SELECT max(invoiceno) as maxid FROM orders where isdeleted<>1")->fetch();
                    $invoiceno = $maxinvoice["maxid"] + 1;
                    if ($invoiceno == 1000 && empty($sonid)){
                        $db = null;
                    }

                    $invoicedate = date("Y-m-d H:i:s");
                    $orderdate = date("Y-m-d H:i:s");
                    $cartdate = date("Y-m-d H:i:s");

                    $allorder = $db->query("insert into orders(id, invoiceno, ordertype, status, companyid, addressid, shiptoaddressid, subtotal, vattotal, entotal, userid, cartdate, orderdate, postcodeid, isnextday, deliverytime, deliverytype, deliverypallet, deliveryprice, notes, customernotes, couponcode, discountrate, discountprice, invoicedate,pono, shipviaid, officenotes, websiteid) values('$sonid','$invoiceno','4','0','".$selectorder["companyid"]."','".$selectorder["addressid"]."','".$selectorder["shiptoaddressid"]."','".$selectorder["entotal"]."','".$selectorder["vattotal"]."','".$selectorder["entotal"]."','".$selectorder["userid"]."','$cartdate','$orderdate','".$selectorder["postcodeid"]."','".$selectorder["isnextday"]."','".$selectorder["deliverytime"]."','".$selectorder["deliverytype"]."','".$selectorder["deliverypallet"]."','".$selectorder["deliveryprice"]."','".$selectorder["notes"]."','".$selectorder["customernotes"]."','".$selectorder["couponcode"]."','".$selectorder["discountrate"]."','".$selectorder["discountprice"]."','$invoicedate','".$selectorder["pono"]."','".$selectorder["shipviaid"]."','".$selectorder["officenotes"]."','".$selectorder["websiteid"]."')");

                    if ($selectorder["couponcode"] != ""){
                        $upcpn = $db->query("UPDATE coupons set isused=1 WHERE couponcode='".$selectorder["couponcode"]."'");
                    }

                    $uporder = $db->query("UPDATE orders set status=3, isplacedorder=1, invoiceid='$sonid' WHERE id='$orderid'");

                    $savebasket = $db->query("insert into basket(orderid, productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate, isordered, websiteid) select '$sonid', productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate,  isordered, websiteid from basket where orderid='$orderid'");

                    $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                    $processdate = date("Y-m-d H:i:s");
                    $tablename = "orders";
                    $tableid = $sonid;
                    $parentid = 0;
                    $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
                    $userid = $paneluser["id"];
                    $log_notes = "order to invoice";
                    $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");
                    if ($savelogs->rowCount()){
                        header("Location: ../index.php?p=invoice&inv=open&pg=panel-invoice-edit&orderid=".$sonid);
                    }
                }
            }
            elseif ($operation == "ordertosample"){
                $orderid = $_GET["orderid"];
                $order = $db->query("select * from orders where id='$orderid'")->fetch();
                $companyid = $order["companyid"];
                $cartdate = date("Y-m-d");
                $orderdate = date("Y-m-d");
                $invoicedate = date("Y-m-d");
                $max = $db->query("select max(id) as maxid from orders")->fetch();
                $sonid = $max["maxid"] + 1;
                if (empty($sonid)){ $sonid = 1; }
                $maxno = $db->query("SELECT max(sampleno) as maxid FROM orders where isdeleted<>1")->fetch();
                $sampleno = $maxno["maxid"] + 1;

                $saveorder = $db->query("insert into orders (id,sampleno,ordertype,status,companyid,addressid,shiptoaddressid,userid,cartdate,orderdate,ispaid,isplacedorder,notes,customernotes,invoicedate,officenotes) values('$sonid','$sampleno','1','1','".$order['companyid']."','".$order['addressid']."','".$order['shiptoaddressid']."','".$order['userid']."','$cartdate','$orderdate','0','0','Converted from web order no: $orderid','".$order['customernotes']."','$invoicedate','".$order['officenotes']."')");

                $savebasket = $db->query("insert into basket(orderid, productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate, isordered, websiteid) select '$sonid', productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate, isordered, websiteid from basket where orderid='$orderid'");

                $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                $processdate = date("Y-m-d H:i:s");
                $tablename = "orders";
                $tableid = $sonid;
                $parentid = 0;
                $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
                $userid = $paneluser["id"];
                $log_notes = "order to sample";
                $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");
                if ($savebasket && $saveorder){
                    header("Location: ../index.php?p=sample_management&smp=approvedsample&pg=panel-sample-edit&orderid=".$orderid);
                }
            }
            elseif ($operation == "proformatoorder"){

                $type = $_GET["type"];
                $st = $_GET["st"];
                $what = $_GET["what"];
                $orderid = $_GET["orderid"];

                $selectorder = $db->query("SELECT * FROM orders where id='$orderid'")->fetch();
                $maxid = $db->query("SELECT max(id) as maxid FROM orders")->fetch();
                $sonid = $maxid["maxid"] + 1;
                if (empty($sonid)){ $sonid = 1; }

                if ($what == "duplicate"){
                    $maxorder = $db->query("SELECT max(orderno) as maxid FROM orders")->fetch();
                    $orderno = $maxorder["maxid"] + 1;
                    if (empty($orderno)){ $orderno = 1000; }

                    $orderdate = date("Y-m-d H:i:s");
                    $cartdate = date("Y-m-d H:i:s");

                    $allorder = $db->query("insert into orders(id, orderno, ordertype, status, companyid, addressid, shiptoaddressid, subtotal, vattotal, entotal, userid, cartdate, orderdate, postcodeid, isnextday, deliverytime, deliverytype, deliverypallet, deliveryprice, notes, customernotes, couponcode, discountrate, discountprice, pono, shipviaid, officenotes, websiteid) values('$sonid','$orderno','3','1','".$selectorder["companyid"]."','".$selectorder["addressid"]."','".$selectorder["shiptoaddressid"]."','".$selectorder["entotal"]."','".$selectorder["vattotal"]."','".$selectorder["entotal"]."','".$selectorder["userid"]."','$cartdate','$orderdate','".$selectorder["postcodeid"]."','".$selectorder["isnextday"]."','".$selectorder["deliverytime"]."','".$selectorder["deliverytype"]."','".$selectorder["deliverypallet"]."','".$selectorder["deliveryprice"]."','".$selectorder["notes"]."','".$selectorder["customernotes"]."','".$selectorder["couponcode"]."','".$selectorder["discountrate"]."','".$selectorder["discountprice"]."','".$selectorder["pono"]."','".$selectorder["shipviaid"]."','".$selectorder["officenotes"]."','".$selectorder["websiteid"]."')");

                    if ($selectorder["couponcode"] != ""){ $couponcode = 1; }

                    $savebasket = $db->query("insert into basket(orderid, productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate, isordered, websiteid) select '$sonid', productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate, isordered, websiteid from basket where orderid='$orderid'");

                    $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                    $processdate = date("Y-m-d H:i:s");
                    $tablename = "orders";
                    $tableid = $sonid;
                    $parentid = 0;
                    $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
                    $userid = $paneluser["id"];
                    $log_notes = "duplicate order";
                    $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");
                    if ($savebasket && $allorder){
                        header("Location: ../index.php?p=sales&st=open&pg=panel-order-edit&orderid=".$sonid."&type=".$type);
                    }
                }
                elseif ($what == "webordertoproforma"){
                    $maxproforma = $db->query("SELECT max(proformano) as maxid FROM orders")->fetch();
                    $proformano = $maxproforma["maxid"] + 1;
                    if (empty($proformano)){ $proformano = 1000; }

                    $orderdate = date("Y-m-d H:i:s");
                    $cartdate = date("Y-m-d H:i:s");
                    if ($_GET["companyid"] != ""){
                        $allorder = $db->query("insert into orders(id, proformano, ordertype, status, companyid, addressid, shiptoaddressid, subtotal, vattotal, entotal, userid, cartdate, orderdate, postcodeid, isnextday, deliverytime, deliverytype, deliverypallet, deliveryprice, notes, customernotes, couponcode, discountrate, discountprice, pono, shipviaid, officenotes, websiteid) values('$sonid','$proformano','2','0','".$selectorder["companyid"]."','".$selectorder["addressid"]."','".$selectorder["shiptoaddressid"]."','".$selectorder["subtotal"]."','".$selectorder["vattotal"]."','".$selectorder["entotal"]."','".$selectorder["userid"]."','$cartdate','$orderdate','".$selectorder["postcodeid"]."','".$selectorder["isnextday"]."','".$selectorder["deliverytime"]."','".$selectorder["deliverytype"]."','".$selectorder["deliverypallet"]."','".$selectorder["deliveryprice"]."','".$selectorder["notes"]."','".$selectorder["customernotes"]."','".$selectorder["couponcode"]."','".$selectorder["discountrate"]."','".$selectorder["discountprice"]."','".$selectorder["pono"]."','".$selectorder["shipviaid"]."','".$selectorder["officenotes"]."','".$selectorder["websiteid"]."')");
                    }
                    else{
                        $allorder = $db->query("insert into orders(id, proformano, ordertype, status, subtotal, vattotal, entotal, userid, cartdate, orderdate, postcodeid, isnextday, deliverytime, deliverytype, deliverypallet, deliveryprice, notes, customernotes, couponcode, discountrate, discountprice, pono, shipviaid, officenotes, websiteid) values('$sonid','$proformano','2','0','".$selectorder["subtotal"]."','".$selectorder["vattotal"]."','".$selectorder["entotal"]."','".$selectorder["userid"]."','$cartdate','$orderdate','".$selectorder["postcodeid"]."','".$selectorder["isnextday"]."','".$selectorder["deliverytime"]."','".$selectorder["deliverytype"]."','".$selectorder["deliverypallet"]."','".$selectorder["deliveryprice"]."','".$selectorder["notes"]."','".$selectorder["customernotes"]."','".$selectorder["couponcode"]."','".$selectorder["discountrate"]."','".$selectorder["discountprice"]."','".$selectorder["pono"]."','".$selectorder["shipviaid"]."','".$selectorder["officenotes"]."','".$selectorder["websiteid"]."')");
                    }

                    if ($selectorder["couponcode"] != ""){ $couponcode = 1; }

                    $savebasket = $db->query("insert into basket(orderid, productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate, isordered, websiteid) select '$sonid', productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate, isordered, websiteid from basket where orderid='$orderid'");

                    $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                    $processdate = date("Y-m-d H:i:s");
                    $tablename = "orders";
                    $tableid = $sonid;
                    $parentid = 0;
                    $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
                    $userid = $paneluser["id"];
                    $log_notes = "web order to proforma";
                    $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");
                    if ($savelogs->rowCount()){
                        header("Location: ../index.php?p=sales&st=web&pg=panel-order-edit&orderid=".$sonid."&type=proforma");
                    }
                }
                else{

                    $max = $db->query("select max(orderno) as maxno from orders")->fetch();
                    $orderno = $max["maxno"] + 1;

                    /*
                    $upneworder = $db->prepare("update orders set proformano=:proformano, orderno=:orderno, ordertype=:ordertype, status=:status, isplacedorder=:isplacedorder, neworderid=:neworderid where id=:id");
                    $upneworder->bindValue(":proformano",NULL,PDO::PARAM_NULL);
                    $upneworder->bindValue(":orderno",$orderno,PDO::PARAM_INT);
                    $upneworder->bindValue(":ordertype",3,PDO::PARAM_INT);
                    $upneworder->bindValue(":status",3,PDO::PARAM_INT);
                    $upneworder->bindValue(":isplacedorder",1,PDO::PARAM_INT);
                    $upneworder->bindValue(":neworderid",$sonid,PDO::PARAM_INT);
                    $upneworder->bindValue(":id",$orderid,PDO::PARAM_INT);
                    $upneworder->execute();

                    $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                    $processdate = date("Y-m-d H:i:s");
                    $tablename = "orders";
                    $tableid = $sonid;
                    $parentid = 0;
                    $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
                    $userid = $paneluser["id"];
                    $log_notes = "proforma to order";
                    $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");
                    if ($savelogs->rowCount()){
                        header("Location: ../index.php?p=sales&st=archived&pg=panel-order-edit&orderid=".$orderid."&type=order");
                    }
                    */

                    // Önceki haliyle burdaki gibi....

                    $orderdate = date("Y-m-d H:i:s");
                    $cartdate = date("Y-m-d H:i:s");

                    $allorder = $db->query("insert into orders(id, orderno, ordertype, status, companyid, addressid, shiptoaddressid, subtotal, vattotal, entotal, userid, cartdate, orderdate, postcodeid, isnextday, deliverytime, deliverytype, deliverypallet, deliveryprice, notes, customernotes, couponcode, discountrate, discountprice, pono, shipviaid, officenotes, websiteid) values('$sonid','$orderno','3','1','".$selectorder["companyid"]."','".$selectorder["addressid"]."','".$selectorder["shiptoaddressid"]."','".$selectorder["entotal"]."','".$selectorder["vattotal"]."','".$selectorder["entotal"]."','".$selectorder["userid"]."','$cartdate','$orderdate','".$selectorder["postcodeid"]."','".$selectorder["isnextday"]."','".$selectorder["deliverytime"]."','".$selectorder["deliverytype"]."','".$selectorder["deliverypallet"]."','".$selectorder["deliveryprice"]."','".$selectorder["notes"]."','".$selectorder["customernotes"]."','".$selectorder["couponcode"]."','".$selectorder["discountrate"]."','".$selectorder["discountprice"]."','".$selectorder["pono"]."','".$selectorder["shipviaid"]."','".$selectorder["officenotes"]."','".$selectorder["websiteid"]."')");

                    if ($selectorder["couponcode"] != ""){
                        $upcpn = $db->query("UPDATE coupons set isused=1 WHERE couponcode='".$selectorder["couponcode"]."'");
                    }

                    $upneworder = $db->query("UPDATE orders set status=3, isplacedorder=1, neworderid='$sonid' WHERE id='$orderid'");

                    $savebasket = $db->query("insert into basket(orderid, productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate, isordered, websiteid) select '$sonid', productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate,  isordered, websiteid from basket where orderid='$orderid'");

                    $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                    $processdate = date("Y-m-d H:i:s");
                    $tablename = "orders";
                    $tableid = $sonid;
                    $parentid = 0;
                    $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
                    $userid = $paneluser["id"];
                    $log_notes = "proforma to order";
                    $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");
                    if ($savelogs->rowCount()){
                        header("Location: ../index.php?p=sales&st=archived&pg=panel-order-edit&orderid=".$orderid."&type=proforma");
                    }

                }
            }
            elseif ($operation == "adddelivery"){
                $st = $_GET["st"];
                $orderid = $_GET["orderid"];
                $type = $_GET["type"];
                $delivery = $db->query("select * from orders where id='$orderid'")->fetch();
                $sessionid = $delivery["sessionid"];
                $deliveryvalue = $delivery["deliveryprice"];
                $deliverytime = $delivery["deliverytime"];
                $isnextday = $delivery["isnextday"];
                $createdate = $delivery["orderdate"];
                $websiteid = $delivery["websiteid"];
                $entotal = $delivery["entotal"];
                $entotal = $deliveryvalue + $entotal;
                if ($deliveryvalue != ""){
                    if ($deliverytime != ""){
                        $aranan = "_".$deliverytime."_";
                    }
                    else{
                        if ($isnextday == "0"){
                            $aranan = "_economy_";
                        }
                    }
                    $deliverytype = $delivery["deliverytype"];
                    if (strstr($deliverytype, "Royal")){
                        $aranan = "_royalmail_";
                    }
                    if (strstr($deliverytype, "Courier")){
                        $aranan = "_courier_";
                    }
                    $apply = $db->query("SELECT sizes.size, sizes.id as sizeid, product_sizes.id as product_sizeid, products.name, products.szid, products.pr_id FROM products left join product_sizes on product_sizes.productid=products.szid left join sizes on sizes.id=product_sizes.sizeid where product_sizes.note='$aranan' and products.websiteid='$websiteid'")->fetch();
                    $productid = $apply["pr_id"];
                    $productname = $apply["name"];
                    $sizeid = $apply["sizeid"];
                    $product_sizeid = $apply["product_sizeid"];
                    $sizename = $apply["size"];
                    $deliverysave = $db->query("insert into basket (orderid, productid, sizeid, sessionid, quantity, vatrate, vatid, isdelivery, createdate, isordered, websiteid) values('$orderid','$productid','$sizeid','$sessionid','1','20','29','1','$createdate','1','$websiteid')");
                    if ($deliverysave->rowCount()){
                        //$uptotal = $db->query("update orders set entotal='$entotal' where id='$orderid'");
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=".$st."&type=".$type);
                    }
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                }

            }
            elseif ($operation == "addvoucher"){
                $st = $_GET["st"];
                $orderid = $_GET["orderid"];
                $type = $_GET["type"];
                $delivery = $db->query("select * from orders where id='$orderid'")->fetch();

                $sessionid = $delivery["sessionid"];
                $deliveryvalue = $delivery["deliveryprice"];
                $vouchervalue = $delivery["discountprice"];
                $deliverytime = $delivery["deliverytime"];
                $isnextday = $delivery["isnextday"];
                $createdate = $delivery["orderdate"];
                $websiteid = $delivery["websiteid"];
                $entotal = $delivery["entotal"];
                $entotal = $deliveryvalue + $entotal;

                $voucher = "_voucher_";
                $discount = $db->query("SELECT sizes.size, sizes.id as sizeid, product_sizes.id as product_sizeid, products.name, products.szid, products.pr_id FROM products left join product_sizes on product_sizes.productid=products.szid left join sizes on sizes.id=product_sizes.sizeid where product_sizes.note='$voucher' and products.websiteid='$websiteid'")->fetch();
                $productid = $discount["pr_id"];
                $productname = $discount["name"];
                $sizeid = $discount["sizeid"];
                $product_sizeid = $discount["product_sizeid"];
                $sizename = $discount["size"];
                if ($vouchervalue != ""){
                    $vouchervalue = 0 - $vouchervalue;
                }
                $discountsave = $db->query("insert into basket (orderid, productid, sizeid, sessionid, quantity, vatrate, vatid, isdiscount, isdeleted, isnondiscountable, createdate, isordered, websiteid) values('$orderid','$productid','$sizeid','$sessionid','1','20','29','1','0','1','$createdate','1','$websiteid')");
                if ($discountsave->rowCount()){
                    //$uptotal = $db->query("update orders set entotal='$entotal' where id='$orderid'");
                    if ($type == "proforma"){
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                    }
                    elseif ($type == "order"){
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                    }
                    elseif ($type == "invoice"){
                        header("Location: ../index.php?p=invoice&inv=open&pg=panel-invoice-edit&orderid=".$orderid."&type=".$type);
                    }

                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&type=".$type);
                }
            }
            elseif (isset($_POST["updateorder"])){
                $orderid = $_GET["orderid"];
                $st = $_GET["st"];
                $type = $_GET["type"];
                $IDsite = strip_tags(trim($_POST["websiteid"]));
                $notes = strip_tags(trim($_POST["notes"]));
                $customernotes = strip_tags(trim($_POST["customernotes"]));
                $officenotes = strip_tags(trim($_POST["officenotes"]));
                $pono = strip_tags(trim($_POST["pono"]));

                $rq = strtotime($_POST["orderdateup"]);
                $request = date('Y-m-d H:i:s', $rq);
                $shp = strtotime($_POST["deliverydateup"]);
                $shipment = date('Y-m-d H:i:s', $shp);

                $orderdata = $db->query("update orders set orderdate='$request', deliverydate='$shipment', notes='$notes', customernotes='$customernotes', pono='$pono', officenotes='$officenotes', websiteid='$IDsite' where id='$orderid'");
                if ($orderdata->rowCount()){
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                    setcookie('testcookie', "true|Order Data Updated...", time() + 20, '/');
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                    setcookie('testcookie', "false|Order Data Not Updated...", time() + 20, '/');
                }
            }
            elseif (isset($_POST["updateproforma"])){
                $orderid = $_GET["orderid"];
                $st = $_GET["st"];
                $type = $_GET["type"];
                $IDsite = strip_tags(trim($_POST["websiteid"]));
                $notes = strip_tags(trim($_POST["notes"]));
                $customernotes = strip_tags(trim($_POST["customernotes"]));
                $officenotes = strip_tags(trim($_POST["officenotes"]));
                $pono = strip_tags(trim($_POST["pono"]));

                $rq = strtotime($_POST["orderdateup"]);
                $request = date('Y-m-d H:i:s', $rq);
                $shp = strtotime($_POST["deliverydateup"]);
                $shipment = date('Y-m-d H:i:s', $shp);

                $orderdata = $db->query("update orders set orderdate='$request', deliverydate='$shipment', notes='$notes', customernotes='$customernotes', pono='$pono', officenotes='$officenotes', websiteid='$IDsite' where id='$orderid'");
                if ($orderdata->rowCount()){
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                    setcookie('testcookie', "true|Proforma Data Updated...", time() + 20, '/');
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                    setcookie('testcookie', "false|Proforma Data Not Updated...", time() + 20, '/');
                }
            }
            elseif ($operation == "orderdeleteweb"){
                $st = $_GET["st"];
                $type = $_GET["type"];
                $onemonth = strtotime("-1 month",date("Y-m-d H:i:s"));
                $onemonth = date("Y-m-d H:i:s",$onemonth);
                $order = $db->query("SELECT * FROM orders where status=0 and (ordertype=2 or ordertype=3) and isdeleted<>1 and orders.orderdate<'$onemonth'");
                if ($order->rowCount()){
                    foreach($order as $or){
                        $upor = $db->query("UPDATE orders set isdeleted=1 WHERE id='".$or['id']."'");
                        if ($or["userid"] != ""){
                            $upusr = $db->query("UPDATE users set isdeleted=1 WHERE id='".$or['id']."'");
                        }
                    }
                }
                $uporder = $db->query("UPDATE orders set isdeleted=1 where status = 0 and (ordertype=2 or ordertype=3) and isdeleted<>1 and orders.orderdate<'$onemonth'");
                if ($uporder){
                    header("Location: ../index.php?p=sales&st=".$st."&type=".$type);
                    setcookie('testcookie', "true|Proforma and Order Data Deleted...", time() + 20, '/');
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&type=".$type);
                    setcookie('testcookie', "true|Proforma and Order Data Not Deleted...", time() + 20, '/');
                }
            }
        }
        elseif ($process == "enterorder"){
            if (isset($_POST["posttype"])){
                $selectCustomer = strip_tags(trim($_POST["selectCustomer"]));
                $selectAddress = strip_tags(trim($_POST["selectAddress"]));
                $selectWebsite = strip_tags(trim($_POST["selectWebsite"]));
                $addNotes = strip_tags(trim($_POST["addNotes"]));

                //echo $selectCustomer."  --  ".$selectAddress." -- ".$selectWebsite." -- ".$addNotes;

                $customerSelect = $db->query("select * from companies where id='$selectCustomer'")->fetch();
                $addressSelect = $db->query("select * from company_address where id='$selectAddress'")->fetch();

                $company_id = $customerSelect["pr_id"];
                $company_name = $customerSelect["companyname"];
                $cusotmerfirstname = $customerSelect["firstname"];
                $customerlastname = $customerSelect["lastname"];
                $customerEmail = $customerSelect["email"];
                $customerNote = $customerSelect["note"];
                $customerTel1 = $customerSelect["tel1"];

                $addressID = $addressSelect["id"];
                $customerHouse = $addressSelect["house"];
                $customerStreet = $addressSelect["street"];
                $customerCity = $addressSelect["city"];
                $customerCounty = $addressSelect["county"];
                $customerPostcode = $addressSelect["postcode"];
                $customerCountry = $addressSelect["country"];

                $ipadresi = $_REQUEST['REMOTE_ADDR'];
                $linkyapisi = @unserialize(file_get_contents('http://ip-api.com/php/'.$ipadresi));
                $ipno = $linkyapisi["query"];
                $usertype = "order";
                $qtyorder = strip_tags(trim($_POST["qtyorder"]));
                $orderproductid = strip_tags(trim($_POST["orderproductid"]));
                $new_selectroder = strip_tags(trim($_POST["new_selectrorder"]));
                $select_website = strip_tags(trim($_POST["select_website"]));
                $maxCmpny = $db->query("select max(pr_id) as maxcmpnyid from companies")->fetch();
                $maxCmpnyID = $maxCmpny["maxcmpnyid"] + 1;

                $addUser = $db->query("insert into users (email, companyname, companyid, firstname, lastname, house, street, city, county, country, postcode, tel, notes, ipno, usertype) values ('$customerEmail','$company_name','$company_id','$cusotmerfirstname','$customerlastname','$customerHouse','$customerStreet','$customerCity','$customerCounty','$customerCountry','$customerPostcode','$customerTel1','$customerNote','$ipno','$usertype')");

                $maxorderid = $db->query("select max(orderid) as maxorderid from basket")->fetch();
                $maxorderno = $db->query("select max(orderno) as maxorderno from orders")->fetch();
                $maxuser = $db->query("select max(id) as maxuserid from users")->fetch();
                $maxorderID = $maxorderid["maxorderid"];
                $maxorderNo = $maxorderno["maxorderno"] + 1;
                //$userID = $maxuser["maxuserid"] + 1;
                $userID = $maxuser["maxuserid"];
                $ordertype = 3;
                $status = 1;
                $cartdate = date("Y-m-d H:i:s");
                $orderdate = date("Y-m-d H:i:s");
                $panelsession = $_SESSION["panelordersession"];

                $prlist = $db->query("select * from basket where sessionid='$panelsession'");
                $subprices = array();
                $prquantity = array();
                $k = 0;
                foreach($prlist as $prls){
                    $pr_id = $prls["productid"];
                    $size_id = $prls["sizeid"];
                    $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_website'")->fetch();
                    $szid = $pr["szid"];
                    $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                    $qtySl = $db->query("select * from basket where sessionid='$panelsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                    $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                    $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                    $ceil = ceil($item);
                    $quantity = $ceil * $sizes["area"] / 10000;
                    if ($prls["sample"] == 1){
                        $prquantity[$k] = $prls["quantity"];
                    }
                    else{
                        $subprice = $quantity * $prsizes["price"];
                        $subprices[$k] = $subprice;
                    }
                    $k++;
                }
                if (array_sum($subprices) == 0){
                    if (array_sum($prquantity) > 2){
                        $dlvry = array_sum($prquantity) - 2;
                        $deliverytotal = $dlvry * 2;
                        $entotal = array_sum($subprices) + $deliverytotal;
                    }
                    else{
                        $entotal = array_sum($subprices);
                    }
                }
                else{
                    if (array_sum($prquantity) > 2){
                        $dlvry = array_sum($prquantity) - 2;
                        $deliverytotal = $dlvry * 2;
                        $entotal = array_sum($subprices) + $deliverytotal;
                    }
                    else{
                        $entotal = array_sum($subprices);
                    }
                }

                $enterorder = $db->query("insert into orders (id,orderno,ordertype,status,companyid,addressid,entotal,sessionid,userid,cartdate,orderdate,postcodeid,notes,websiteid) values('$maxorderID','$maxorderNo','$ordertype','$status','$company_id','$addressID','$entotal','$panelsession','$userID','$cartdate','$orderdate','$customerPostcode','$addNotes','$selectWebsite')");
                if ($addUser->rowCount()){
                    if ($enterorder->rowCount()){
                        unset($_SESSION["panelordersession"]);
                        echo 1;
                    }
                    else{
                        unset($_SESSION["panelordersession"]);
                        echo 0;
                    }
                }
                else{
                    unset($_SESSION["panelordersession"]);
                    echo 0;
                }
            }
            else{
                $selectCustomer = strip_tags(trim($_POST["selectCustomer"]));
                $selectAddress = strip_tags(trim($_POST["selectAddress"]));
                $selectWebsite = strip_tags(trim($_POST["selectWebsite"]));
                $addNotes = strip_tags(trim($_POST["addNotes"]));

                //echo $selectCustomer."  --  ".$selectAddress." -- ".$selectWebsite." -- ".$addNotes;

                $customerSelect = $db->query("select * from companies where id='$selectCustomer'")->fetch();
                $addressSelect = $db->query("select * from company_address where id='$selectAddress'")->fetch();

                $company_id = $customerSelect["pr_id"];
                $company_name = $customerSelect["companyname"];
                $cusotmerfirstname = $customerSelect["firstname"];
                $customerlastname = $customerSelect["lastname"];
                $customerEmail = $customerSelect["email"];
                $customerNote = $customerSelect["note"];
                $customerTel1 = $customerSelect["tel1"];

                $addressID = $addressSelect["id"];
                $customerHouse = $addressSelect["house"];
                $customerStreet = $addressSelect["street"];
                $customerCity = $addressSelect["city"];
                $customerCounty = $addressSelect["county"];
                $customerPostcode = $addressSelect["postcode"];
                $customerCountry = $addressSelect["country"];

                $ipadresi = $_REQUEST['REMOTE_ADDR'];
                $linkyapisi = @unserialize(file_get_contents('http://ip-api.com/php/'.$ipadresi));
                $ipno = $linkyapisi["query"];
                $usertype = "order";
                $qtyorder = strip_tags(trim($_POST["qtyorder"]));
                $orderproductid = strip_tags(trim($_POST["orderproductid"]));
                $new_selectroder = strip_tags(trim($_POST["new_selectrorder"]));
                $select_website = strip_tags(trim($_POST["select_website"]));
                $maxCmpny = $db->query("select max(pr_id) as maxcmpnyid from companies")->fetch();
                $maxCmpnyID = $maxCmpny["maxcmpnyid"] + 1;

                $addUser = $db->query("insert into users (email, companyname, companyid, firstname, lastname, house, street, city, county, country, postcode, tel, notes, ipno, usertype) values ('$customerEmail','$company_name','$company_id','$cusotmerfirstname','$customerlastname','$customerHouse','$customerStreet','$customerCity','$customerCounty','$customerCountry','$customerPostcode','$customerTel1','$customerNote','$ipno','$usertype')");

                $maxorderid = $db->query("select max(orderid) as maxorderid from basket")->fetch();
                $maxorderno = $db->query("select max(orderno) as maxorderno from orders")->fetch();
                $maxuser = $db->query("select max(id) as maxuserid from users")->fetch();
                $maxorderID = $maxorderid["maxorderid"];
                $maxorderNo = $maxorderno["maxorderno"] + 1;
                //$userID = $maxuser["maxuserid"] + 1;
                $userID = $maxuser["maxuserid"];
                $ordertype = 3;
                $status = 1;
                $cartdate = date("Y-m-d H:i:s");
                $orderdate = date("Y-m-d H:i:s");
                $panelsession = $_SESSION["panelordersession"];

                $prlist = $db->query("select * from basket where sessionid='$panelsession'");
                $subprices = array();
                $prquantity = array();
                $k = 0;
                foreach($prlist as $prls){
                    $pr_id = $prls["productid"];
                    $size_id = $prls["sizeid"];
                    $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_website'")->fetch();
                    $szid = $pr["szid"];
                    $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                    $qtySl = $db->query("select * from basket where sessionid='$panelsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                    $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                    $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                    $ceil = ceil($item);
                    $quantity = $ceil * $sizes["area"] / 10000;
                    if ($prls["sample"] == 1){
                        $prquantity[$k] = $prls["quantity"];
                    }
                    else{
                        $subprice = $quantity * $prsizes["price"];
                        $subprices[$k] = $subprice;
                    }
                    $k++;
                }
                if (array_sum($subprices) == 0){
                    if (array_sum($prquantity) > 2){
                        $dlvry = array_sum($prquantity) - 2;
                        $deliverytotal = $dlvry * 2;
                        $entotal = array_sum($subprices) + $deliverytotal;
                    }
                    else{
                        $entotal = array_sum($subprices);
                    }
                }
                else{
                    if (array_sum($prquantity) > 2){
                        $dlvry = array_sum($prquantity) - 2;
                        $deliverytotal = $dlvry * 2;
                        $entotal = array_sum($subprices) + $deliverytotal;
                    }
                    else{
                        $entotal = array_sum($subprices);
                    }
                }

                $enterorder = $db->query("insert into orders (id,orderno,ordertype,status,companyid,addressid,entotal,sessionid,userid,cartdate,orderdate,postcodeid,notes,websiteid) values('$maxorderID','$maxorderNo','$ordertype','$status','$company_id','$addressID','$entotal','$panelsession','$userID','$cartdate','$orderdate','$customerPostcode','$addNotes','$selectWebsite')");
                if ($addUser->rowCount()){
                    if ($enterorder->rowCount()){
                        unset($_SESSION["panelordersession"]);
                        header("Location: ../index.php?p=sales&st=open&pg=panel-order-edit&orderid=".$maxorderID."&type=order");
                        setcookie('testcookie', "true|Order Inserted...", time() + 20, '/');
                    }
                    else{
                        unset($_SESSION["panelordersession"]);
                        header("Location: ../index.php?p=sales&st=open&type=order");
                        setcookie('testcookie', "false|Order Not Inserted...", time() + 20, '/');
                    }
                }
                else{
                    unset($_SESSION["panelordersession"]);
                    header("Location: ../index.php?p=sales&st=open&type=order");
                    setcookie('testcookie', "false|User Not Inserted...", time() + 20, '/');
                }
            }
            /*
            $customerName = strip_tags(trim($_POST["customerName"]));
            $contactName = strip_tags(trim($_POST["contactName"]));
            $contactName2 = strip_tags(trim($_POST["contactName2"]));
            $tel = strip_tags(trim($_POST["tel"]));
            $tel2 = strip_tags(trim($_POST["tel2"]));
            $fax = strip_tags(trim($_POST["fax"]));
            $vat = strip_tags(trim($_POST["vat"]));
            $mail = strip_tags(trim($_POST["mail"]));
            $web = strip_tags(trim($_POST["web"]));
            $companyType = strip_tags(trim($_POST["comboSelectcompanyType"]));
            $fpoc = strip_tags(trim($_POST["comboSelectfpoc"]));
            $paymentMethod = strip_tags(trim($_POST["comboSelectpaymentterm"]));
            $balance = strip_tags(trim($_POST["balance"]));
            $isSupplier = strip_tags(trim($_POST["isSupplier"]));
            $isCustomer = strip_tags(trim($_POST["isCustomer"]));
            $note = strip_tags(trim($_POST["note"]));
            $locationName = strip_tags(trim($_POST["locationName"]));
            $house = strip_tags(trim($_POST["house"]));
            $street = strip_tags(trim($_POST["street"]));
            $city = strip_tags(trim($_POST["city"]));
            $county = strip_tags(trim($_POST["county"]));
            $postcode = strip_tags(trim($_POST["postcode"]));
            $country = strip_tags(trim($_POST["country"]));
            $isMain = strip_tags(trim($_POST["isMain"]));
            if ($isSupplier=="on"){$isSupplier = "1";}else{$isSupplier="0";}
            if ($isCustomer=="on"){$isCustomer = "1";}else{$isCustomer="0";}
            if ($isMain=="on"){$isMain = "1";}else{$isMain="0";}
            $ipadresi = $_REQUEST['REMOTE_ADDR'];
            $linkyapisi = @unserialize(file_get_contents('http://ip-api.com/php/'.$ipadresi));
            $ipno = $linkyapisi["query"];
            $usertype = "order";
            $qtyorder = strip_tags(trim($_POST["qtyorder"]));
            $orderproductid = $_POST["orderproductid"];
            $new_selectroder = $_POST["new_selectrorder"];
            $select_website = $_POST["select_website"];
            $maxCmpny = $db->query("select max(pr_id) as maxcmpnyid from companies")->fetch();
            $maxCmpnyID = $maxCmpny["maxcmpnyid"] + 1;

            $addCustomer = $db->query("insert into companies (id,companyname,firstname,lastname,tel1,tel2,fax,email,web,note,fpoc,companytype,paymentterm,issupplier,iscustomer,isinactive,vatno) VALUES ('$maxCmpnyID','$customerName','$contactName','$contactName2','$tel','$tel2','$fax','$mail','$web','$note','$fpoc','$companyType','$paymentMethod','$isSupplier','$isCustomer','0','$vat')");

            $companyID = $db->lastInsertId();
            $addAdress = $db->query("insert into company_address (addressname, companyid, house, street, city, county, postcode, country, ismain) VALUES ('$locationName', '$companyID', '$house', '$street', '$city', '$county', '$postcode', '$country', '$isMain')");

            $addressID = $db->lastInsertId();
            $addUser = $db->query("insert into users (email, companyname, companyid, firstname, lastname, house, street, city, county, country, postcode, tel, notes, ipno, usertype) values ('$mail','$customerName','$companyID','$contactName','$contactName2','$house','$street','$city','$county','$country','$postcode','$tel','$note','$ipno','$usertype')");

            $maxorderid = $db->query("select max(orderid) as maxorderid from basket")->fetch();
            $maxorderno = $db->query("select max(orderno) as maxorderno from orders")->fetch();
            $maxuser = $db->query("select max(id) as maxuserid from users")->fetch();
            $maxorderID = $maxorderid["maxorderid"];
            $maxorderNo = $maxorderno["maxorderno"] + 1;
            $userID = $maxuser["maxuserid"] + 1;
            $ordertype = 3;
            $status = 0;
            $cartdate = date("Y-m-d");
            $orderdate = date("Y-m-d");
            $panelsession = $_SESSION["panelordersession"];

            $enterorder = $db->query("insert into orders (id,orderno,ordertype,status,companyid,addressid,sessionid,userid,cartdate,orderdate,postcodeid,notes,websiteid) values('$maxorderID','$maxorderNo','$ordertype','$status','$companyID','$addressID','$panelsession','$userID','$cartdate','$orderdate','$postcode','$note','$select_website')");
            if ($addCustomer->rowCount()){
                if ($addAdress->rowCount()){
                    if ($enterorder->rowCount()){
                        unset($_SESSION["panelordersession"]);
                        header("Location: ../index.php?p=sales&st=web&type=order");
                        setcookie('testcookie', "true|Order Inserted...", time() + 20, '/');
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=web&type=order");
                        setcookie('testcookie', "false|Order Not Inserted...", time() + 20, '/');
                    }
                }
                else{
                    header("Location: ../index.php?p=sales&st=web&type=order");
                    setcookie('testcookie', "false|Address Not Inserted...", time() + 20, '/');
                }
            }
            else{
                header("Location: ../index.php?p=sales&st=web&type=order");
                setcookie('testcookie', "false|Customer Not Inserted...", time() + 20, '/');
            }
            */
        }
        elseif ($process == "enterproforma"){
            if (isset($_POST["posttype"])){
                $selectCustomer = strip_tags(trim($_POST["selectCustomer"]));
                $selectAddress = strip_tags(trim($_POST["selectAddress"]));
                $select_website = strip_tags(trim($_POST["selectWebsite"]));
                $addvoucher = strip_tags(trim($_POST["addvoucher"]));
                $addNotes = strip_tags(trim($_POST["addNotes"]));

                $customerSelect = $db->query("select * from companies where id='$selectCustomer'")->fetch();
                $addressSelect = $db->query("select * from company_address where id='$selectAddress'")->fetch();

                $company_id = $customerSelect["pr_id"];
                $company_name = $customerSelect["companyname"];
                $cusotmerfirstname = $customerSelect["firstname"];
                $customerlastname = $customerSelect["lastname"];
                $customerEmail = $customerSelect["email"];
                $customerNote = $customerSelect["note"];
                $customerTel1 = $customerSelect["tel1"];

                $addressID = $addressSelect["id"];
                $customerHouse = $addressSelect["house"];
                $customerStreet = $addressSelect["street"];
                $customerCity = $addressSelect["city"];
                $customerCounty = $addressSelect["county"];
                $customerPostcode = $addressSelect["postcode"];
                $customerCountry = $addressSelect["country"];

                $ipadresi = $_REQUEST['REMOTE_ADDR'];
                $linkyapisi = @unserialize(file_get_contents('http://ip-api.com/php/'.$ipadresi));
                $ipno = $linkyapisi["query"];
                $usertype = "order";

                $maxCmpny = $db->query("select max(pr_id) as maxcmpnyid from companies")->fetch();
                $maxCmpnyID = $maxCmpny["maxcmpnyid"] + 1;

                $addUser = $db->query("insert into users (email, companyname, companyid, firstname, lastname, house, street, city, county, country, postcode, tel, notes, ipno, usertype) values ('$customerEmail','$company_name','$company_id','$cusotmerfirstname','$customerlastname','$customerHouse','$customerStreet','$customerCity','$customerCounty','$customerCountry','$customerPostcode','$customerTel1','$customerNote','$ipno','$usertype')");

                $maxorderid = $db->query("select max(orderid) as maxorderid from basket")->fetch();
                $maxproformano = $db->query("select max(proformano) as maxproformano from orders")->fetch();
                $maxuser = $db->query("select max(id) as maxuserid from users")->fetch();
                $maxorderID = $maxorderid["maxorderid"];
                $maxproformaNo = $maxproformano["maxproformano"] + 1;
                //$userID = $maxuser["maxuserid"] + 1;
                $userID = $maxuser["maxuserid"];

                $ordertype = 2;
                $status = 1;
                $cartdate = date("Y-m-d H:i:s");
                $orderdate = date("Y-m-d H:i:s");
                $panelsession = $_SESSION["panelordersession"];

                $prlist = $db->query("select * from basket where sessionid='$panelsession'");
                $subprices = array();
                $prquantity = array();
                $k = 0;
                foreach($prlist as $prls){
                    $pr_id = $prls["productid"];
                    $size_id = $prls["sizeid"];
                    $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_website'")->fetch();
                    $szid = $pr["szid"];
                    $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                    $qtySl = $db->query("select * from basket where sessionid='$panelsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                    $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                    $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                    $ceil = ceil($item);
                    $quantity = $ceil * $sizes["area"] / 10000;
                    if ($prls["sample"] == 1){
                        $prquantity[$k] = $prls["quantity"];
                    }
                    else{
                        $subprice = $quantity * $prsizes["price"];
                        $subprices[$k] = $subprice;
                    }
                    $k++;
                }
                if (array_sum($subprices) == 0){
                    if (array_sum($prquantity) > 2){
                        $dlvry = array_sum($prquantity) - 2;
                        $deliverytotal = $dlvry * 2;
                        $entotal = array_sum($subprices) + $deliverytotal;
                    }
                    else{
                        $entotal = array_sum($subprices);
                    }
                }
                else{
                    if (array_sum($prquantity) > 2){
                        $dlvry = array_sum($prquantity) - 2;
                        $deliverytotal = $dlvry * 2;
                        $entotal = array_sum($subprices) + $deliverytotal;
                    }
                    else{
                        $entotal = array_sum($subprices);
                    }
                }

                $enterproforma = $db->query("insert into orders (id,proformano,ordertype,status,companyid,addressid,entotal,sessionid,userid,cartdate,orderdate,postcodeid,notes,websiteid) values('$maxorderID','$maxproformaNo','$ordertype','$status','$company_id','$addressID','$entotal','$panelsession','$userID','$cartdate','$orderdate','$customerPostcode','$addNotes','$select_website')");
                if ($addUser){
                    if ($enterproforma){
                        unset($_SESSION["panelordersession"]);
                        echo 1;
                        //header("Location: ../index.php?p=sales&st=open&pg=panel-order-edit&orderid=".$maxorderID."&type=proforma");
                        //setcookie('testcookie', "true|Proforma Inserted...", time() + 20, '/');
                    }
                    else{
                        echo 0;
                        //header("Location: ../index.php?p=sales&st=open&type=proforma");
                        //setcookie('testcookie', "false|Proforma Not Inserted...", time() + 20, '/');
                    }
                }
                else{
                    echo 0;
                    //header("Location: ../index.php?p=sales&st=open&type=proforma");
                    //setcookie('testcookie', "false|User Not Inserted...", time() + 20, '/');
                }
            }
            else{
                $selectCustomer = strip_tags(trim($_POST["selectCustomer"]));
                $selectAddress = strip_tags(trim($_POST["selectAddress"]));
                $select_website = strip_tags(trim($_POST["selectWebsite"]));
                $addvoucher = strip_tags(trim($_POST["addvoucher"]));
                $addNotes = strip_tags(trim($_POST["addNotes"]));

                $customerSelect = $db->query("select * from companies where id='$selectCustomer'")->fetch();
                $addressSelect = $db->query("select * from company_address where id='$selectAddress'")->fetch();

                $company_id = $customerSelect["pr_id"];
                $company_name = $customerSelect["companyname"];
                $cusotmerfirstname = $customerSelect["firstname"];
                $customerlastname = $customerSelect["lastname"];
                $customerEmail = $customerSelect["email"];
                $customerNote = $customerSelect["note"];
                $customerTel1 = $customerSelect["tel1"];

                $addressID = $addressSelect["id"];
                $customerHouse = $addressSelect["house"];
                $customerStreet = $addressSelect["street"];
                $customerCity = $addressSelect["city"];
                $customerCounty = $addressSelect["county"];
                $customerPostcode = $addressSelect["postcode"];
                $customerCountry = $addressSelect["country"];

                $ipadresi = $_REQUEST['REMOTE_ADDR'];
                $linkyapisi = @unserialize(file_get_contents('http://ip-api.com/php/'.$ipadresi));
                $ipno = $linkyapisi["query"];
                $usertype = "order";
                $qtyorder = strip_tags(trim($_POST["qtyorder"]));
                $orderproductid = strip_tags(trim($_POST["orderproductid"]));
                $new_selectroder = strip_tags(trim($_POST["new_selectrorder"]));
                $select_website = strip_tags(trim($_POST["selectWebsite"]));
                $maxCmpny = $db->query("select max(pr_id) as maxcmpnyid from companies")->fetch();
                $maxCmpnyID = $maxCmpny["maxcmpnyid"] + 1;

                $addUser = $db->query("insert into users (email, companyname, companyid, firstname, lastname, house, street, city, county, country, postcode, tel, notes, ipno, usertype) values ('$customerEmail','$company_name','$company_id','$cusotmerfirstname','$customerlastname','$customerHouse','$customerStreet','$customerCity','$customerCounty','$customerCountry','$customerPostcode','$customerTel1','$customerNote','$ipno','$usertype')");

                $maxorderid = $db->query("select max(orderid) as maxorderid from basket")->fetch();
                $maxproformano = $db->query("select max(proformano) as maxproformano from orders")->fetch();
                $maxuser = $db->query("select max(id) as maxuserid from users")->fetch();
                $maxorderID = $maxorderid["maxorderid"];
                $maxproformaNo = $maxproformano["maxproformano"] + 1;
                //$userID = $maxuser["maxuserid"] + 1;
                $userID = $maxuser["maxuserid"];

                $ordertype = 2;
                $status = 1;
                $cartdate = date("Y-m-d H:i:s");
                $orderdate = date("Y-m-d H:i:s");
                $panelsession = $_SESSION["panelordersession"];

                $prlist = $db->query("select * from basket where sessionid='$panelsession'");
                $subprices = array();
                $prquantity = array();
                $k = 0;
                foreach($prlist as $prls){
                    $pr_id = $prls["productid"];
                    $size_id = $prls["sizeid"];
                    $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_website'")->fetch();
                    $szid = $pr["szid"];
                    $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                    $qtySl = $db->query("select * from basket where sessionid='$panelsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                    $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                    $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                    $ceil = ceil($item);
                    $quantity = $ceil * $sizes["area"] / 10000;
                    if ($prls["sample"] == 1){
                        $prquantity[$k] = $prls["quantity"];
                    }
                    else{
                        $subprice = $quantity * $prsizes["price"];
                        $subprices[$k] = $subprice;
                    }
                    $k++;
                }
                if (array_sum($subprices) == 0){
                    if (array_sum($prquantity) > 2){
                        $dlvry = array_sum($prquantity) - 2;
                        $deliverytotal = $dlvry * 2;
                        $entotal = array_sum($subprices) + $deliverytotal;
                    }
                    else{
                        $entotal = array_sum($subprices);
                    }
                }
                else{
                    if (array_sum($prquantity) > 2){
                        $dlvry = array_sum($prquantity) - 2;
                        $deliverytotal = $dlvry * 2;
                        $entotal = array_sum($subprices) + $deliverytotal;
                    }
                    else{
                        $entotal = array_sum($subprices);
                    }
                }

                $enterproforma = $db->query("insert into orders (id,proformano,ordertype,status,companyid,addressid,entotal,sessionid,userid,cartdate,orderdate,postcodeid,notes,websiteid) values('$maxorderID','$maxproformaNo','$ordertype','$status','$company_id','$addressID','$entotal','$panelsession','$userID','$cartdate','$orderdate','$customerPostcode','$addNotes','$select_website')");
                if ($addUser){
                    if ($enterproforma){
                        unset($_SESSION["panelordersession"]);
                        header("Location: ../index.php?p=sales&st=open&pg=panel-order-edit&orderid=".$maxorderID."&type=proforma");
                        setcookie('testcookie', "true|Proforma Inserted...", time() + 20, '/');
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=open&type=proforma");
                        setcookie('testcookie', "false|Proforma Not Inserted...", time() + 20, '/');
                    }
                }
                else{
                    header("Location: ../index.php?p=sales&st=open&type=proforma");
                    setcookie('testcookie', "false|User Not Inserted...", time() + 20, '/');
                }
            }

        }
        elseif ($process == "qtyupdate"){
            $bsid = $_POST["id"];
            $qtyval = strip_tags(trim($_POST["qtyval"]));
            $qtyup = $db->query("update basket set quantity='$qtyval' where id='$bsid'");
            if ($qtyup->rowCount()){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($process == "vatupdate"){
            $bsid = $_POST["id"];
            $vatval = strip_tags(trim($_POST["vatval"]));
            //$qtyup = $db->query("update basket set vatrate='$vatval' where id='$bsid'");
            $cmb = $db->query("select * from combos where id='$vatval'")->fetch();
            $amount = $cmb["amount"];
            $qtyup = $db->prepare("update basket set vatrate=:vatrate, vatid=:vatid where id=:id");
            $qtyup->bindValue(":vatrate",$amount,PDO::PARAM_INT);
            $qtyup->bindValue(":vatid",$vatval,PDO::PARAM_INT);
            $qtyup->bindValue(":id",$bsid,PDO::PARAM_INT);
            $qtyup->execute();

            if ($qtyup->rowCount()){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($process == "vatpriceup"){
            $bsid = $_POST["id"];
            $vatpr = strip_tags(trim($_POST["vatpr"]));
            $bs = $db->query("select * from basket where id='$bsid'")->fetch();
            $pr_id = $bs["productid"];
            $web_id = $bs["websiteid"];
            $sizeid = $bs["sizeid"];
            $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$web_id'")->fetch();
            $szid = $pr["szid"];

            $qtyup = $db->query("update product_sizes set price='$vatpr' where productid='$szid' and sizeid='$sizeid'");
            if ($qtyup->rowCount()){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($process == "vatsizup"){
            $bsid = $_POST["id"];
            $vatsiz = strip_tags(trim($_POST["vatsiz"]));

            $bs = $db->query("select * from basket where id='$bsid'")->fetch();
            $pr_id = $bs["productid"];
            $web_id = $bs["websiteid"];
            $sizeid = $bs["sizeid"];
            $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$web_id'")->fetch();
            $szid = $pr["szid"];
            $qtyup = $db->query("update product_sizes set price='$vatsiz' where productid='$szid' and sizeid='$sizeid'");
            if ($qtyup->rowCount()){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($process == "selectProduct") {
            $site = strip_tags(trim($_POST["site"]));
            $selectpr = $db->query("select * from products where (showsite=1 or isdummy=1) and websiteid='$site'");
            if ($selectpr->rowCount()) {
                foreach ($selectpr as $pr) {
                    ?>
                    <option value="<?php echo $pr["pr_id"]; ?>"><?php echo $pr["name"]; ?></option>
                    <?php
                }
            }
        }
        elseif ($process == "enterbasket"){
            $qty = strip_tags(trim($_POST["qtyorder"]));
            $productid = strip_tags(trim($_POST["orderproductid"]));
            $sizeid = strip_tags(trim($_POST["new_selectorder"]));
            $select_site_name = strip_tags(trim($_POST["select_website"]));
            $web = $db->query("select * from websites where websiteid='$select_site_name'")->fetch();
            $ordersite = $web["websitename"];
            $sessionid = date("Ymdhis").$productid;
            $createdate = date("Y.m.d h:i:s");
            if ($_SESSION["panelordersession"]){
                $default = $_SESSION["panelordersession"];
                $notsample = false;
                if ($sizeid == 88 && $notsample == false) {
                    $pr_query = $db->query("select * from basket where productid='$productid' and sizeid='$sizeid' and sessionid='$default'")->fetch();
                    if ($pr_query["productid"] != $productid && $pr_query["sizeid"] != $sizeid){
                        $bid = $db->query("SELECT * FROM orders where sessionid='$default' and status=0 and isordered=0")->fetch();
                        $bs_query = $db->query("select * from basket where sessionid='$default'")->fetch();
                        if ($bid["sessionid"] != $default) {
                            $orderid = $bs_query["orderid"];
                            $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,sample,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$default','1','$qty','20','29','$createdate','1','$select_site_name')");
                            ?>
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th># Item</th>
                                <th>Quantity</th>
                                <th>&nbsp;</th>
                                <th>Price</th>
                                <th>Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $newsession = $_SESSION["panelordersession"];
                            $prlist = $db->query("select * from basket where sessionid='$newsession'");
                            $subprices = array();
                            $prquantity = array();
                            $k = 0;
                            foreach ($prlist as $product) {
                                $pr_id = $product["productid"];
                                $size_id = $product["sizeid"];
                                $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                                $primage = $db->query("select * from images where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                                $szid = $pr["szid"];
                                $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                                $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                                $qtySl = $db->query("select * from basket where sessionid='$newsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                                $itemunit = $sizes["itemunit"];
                                $qtyunit = $sizes["qtyunit"];
                                $szunit = $sizes["sizeunit"];
                                $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                                $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();

                                ?>

                                <tr id="<?php echo $pr_id; ?>">
                                    <td>
                                        <img src="../../<?php echo $ordersite;  ?>/img/products/thumbs/<?php echo $primage["imagename"]; ?>"
                                             width="53" height="40" align="absmiddle" class="hidden-xs">
                                        <a href="?p=product_details&prid=<?php echo $product["productid"]; ?>&imgid=<?php echo $primage["id"]; ?>"
                                           class="text-info">
                                            <?php echo $pr["name"] . " • " . $sizes["size"] . "       "; ?>
                                            <?php
                                            if ($szunit != "None") {
                                                $unit = $db->query("select * from combos where id='$szunit'")->fetch();
                                                echo $unit["name"];
                                            }
                                            ?>
                                        </a>
                                    </td>
                                    <?php
                                    $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                                    $ceil = ceil($item);
                                    $quantity = $ceil * $sizes["area"] / 10000;
                                    ?>
                                    <td class="vert-align"><?php if ($product["sample"] == 1){ $prquantity[$k] = $product["quantity"]; echo $product["quantity"]; }elseif ($product["productid"] == 146){ echo $product["quantity"]; }elseif ($product["productid"] == 145){ echo $product["quantity"]; }elseif ($product["productid"] == 134){ echo $product["quantity"]; }elseif ($product["productid"] == 138){ echo $product["quantity"]; }elseif ($product["productid"] == 248){ echo $product["quantity"]; }else{ echo $ceil; } ?>
                                        &nbsp;<?php echo $cmbitem["name"]; ?></td>
                                    <td class="vert-align">
                                        <input name="qty2<?php echo $qtySl['id']; ?>" id="qty2<?php echo $qtySl['id']; ?>" type="text"
                                               value="<?php echo number_format($quantity, 3); ?>" size="4"
                                               class="required number form-control form-control-auto clear-input"
                                               aria-required="true" onchange="updateqty(<?php echo $qtySl['id']; ?>)">&nbsp;<?php echo $cmb["name"]; ?>
                                    </td>
                                    <td class="vert-align text-center">
                                        &nbsp;
                                        <a class="text-danger removeitem"
                                           onclick="removeitem(<?php echo $pr_id; ?>, <?php echo $product["id"]; ?>);">
                                            <i class="fa fa-times fa-lg" style="color: #843534;"></i>
                                        </a>
                                        &nbsp;
                                    </td>
                                    <?php
                                    $subprice = $quantity * $prsizes["price"];
                                    $subprices[$k] = $subprice;
                                    ?>
                                    <td class="vert-align">£ <?php echo number_format($prsizes["price"], 2); ?>
                                        <input type="hidden" name="prprice" id="prprice"
                                               value="<?php echo number_format($prsizes["price"], 2); ?>">
                                    </td>
                                    <td class="vert-align">£ <?php echo number_format($subprice, 2); ?>
                                        <input type="hidden" name="subprice" id="subprice"
                                               value="<?php echo number_format($subprice, 2); ?>">
                                    </td>
                                </tr>
                                <?php $k++;
                            } ?>
                            </tbody>
                            <?php
                        }
                        else {
                            $orderid = $bs_query["orderid"];
                            if (empty($bid["userid"]) && $bid["userid"] == "") {
                                $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,sample,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$default','1','$qty','20','29','$createdate','1','$select_site_name')");
                            }
                            else {
                                $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,sample,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$default','1','$qty','20','29','$createdate','1','$select_site_name')");
                            }

                            ?>
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th># Item</th>
                                <th>Quantity</th>
                                <th>&nbsp;</th>
                                <th>Price</th>
                                <th>Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $newsession = $_SESSION["panelordersession"];
                            $prlist = $db->query("select * from basket where sessionid='$newsession'");
                            $subprices = array();
                            $k = 0;
                            foreach ($prlist as $product) {
                                $pr_id = $product["productid"];
                                $size_id = $product["sizeid"];
                                $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                                $primage = $db->query("select * from images where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                                $szid = $pr["szid"];
                                $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                                $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                                $qtySl = $db->query("select * from basket where sessionid='$newsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                                $itemunit = $sizes["itemunit"];
                                $qtyunit = $sizes["qtyunit"];
                                $szunit = $sizes["sizeunit"];
                                $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                                $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();
                                ?>

                                <tr id="<?php echo $pr_id; ?>">
                                    <td>
                                        <img src="../../<?php echo $ordersite;  ?>/img/products/thumbs/<?php echo $primage["imagename"]; ?>"
                                             width="53" height="40" align="absmiddle" class="hidden-xs">
                                        <a href="?p=product_details&prid=<?php echo $product["productid"]; ?>&imgid=<?php echo $primage["id"]; ?>"
                                           class="text-info">
                                            <?php echo $pr["name"] . " • " . $sizes["size"]; ?>
                                        </a>
                                    </td>
                                    <?php
                                    $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                                    $ceil = ceil($item);
                                    $quantity = $ceil * $sizes["area"] / 10000;
                                    ?>
                                    <td class="vert-align"><?php if ($product["sample"] == 1){ $prquantity[$k] = $product["quantity"]; echo $product["quantity"]; }elseif ($product["productid"] == 146){ echo $product["quantity"]; }elseif ($product["productid"] == 145){ echo $product["quantity"]; }elseif ($product["productid"] == 134){ echo $product["quantity"]; }elseif ($product["productid"] == 138){ echo $product["quantity"]; }elseif ($product["productid"] == 248){ echo $product["quantity"]; }else{ echo $ceil; } ?>
                                        &nbsp;<?php echo $cmbitem["name"]; ?></td>
                                    <td class="vert-align">
                                        <input name="qty2<?php echo $qtySl['id']; ?>" id="qty2<?php echo $qtySl['id']; ?>" type="text"
                                               value="<?php echo number_format($quantity, 3); ?>" size="4"
                                               class="required number form-control form-control-auto clear-input"
                                               aria-required="true" onchange="updateqty(<?php echo $qtySl['id']; ?>)">&nbsp;sqm
                                    </td>
                                    <td class="vert-align text-center">
                                        &nbsp;
                                        <a class="text-danger removeitem"
                                           onclick="removeitem(<?php echo $pr_id; ?>, <?php echo $product["id"]; ?>);">
                                            <i class="fa fa-times fa-lg" style="color: #843534;"></i>
                                        </a>
                                        &nbsp;
                                    </td>
                                    <?php
                                    $subprice = $quantity * $prsizes["price"];
                                    $subprices[$k] = $subprice;
                                    ?>
                                    <td class="vert-align">£ <?php echo number_format($prsizes["price"], 2); ?>
                                        <input type="hidden" name="prprice" id="prprice"
                                               value="<?php echo number_format($prsizes["price"], 2); ?>">
                                    </td>
                                    <td class="vert-align">£ <?php echo number_format($subprice, 2); ?>
                                        <input type="hidden" name="subprice" id="subprice"
                                               value="<?php echo number_format($subprice, 2); ?>">
                                    </td>
                                </tr>
                                <?php $k++;
                            } ?>
                            </tbody>
                            <?php
                        }
                    }
                    else {
                        ?>
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th># Item</th>
                            <th>Quantity</th>
                            <th>&nbsp;</th>
                            <th>Price</th>
                            <th>Sub Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $newsession = $_SESSION["panelordersession"];
                        $prlist = $db->query("select * from basket where sessionid='$newsession'");
                        $subprices = array();
                        $prquantity = array();
                        $k = 0;
                        foreach ($prlist as $product) {
                            $pr_id = $product["productid"];
                            $size_id = $product["sizeid"];
                            $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $primage = $db->query("select * from images where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $szid = $pr["szid"];
                            $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                            $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                            $qtySl = $db->query("select * from basket where sessionid='$newsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                            $itemunit = $sizes["itemunit"];
                            $qtyunit = $sizes["qtyunit"];
                            $szunit = $sizes["sizeunit"];
                            $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                            $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();
                            ?>

                            <tr id="<?php echo $pr_id; ?>">
                                <td>
                                    <img src="../../<?php echo $ordersite;  ?>/img/products/thumbs/<?php echo $primage["imagename"]; ?>"
                                         width="53"
                                         height="40" align="absmiddle" class="hidden-xs">
                                    <a href="?p=product_details&prid=<?php echo $product["productid"]; ?>&imgid=<?php echo $primage["id"]; ?>"
                                       class="text-info">
                                        <?php echo $pr["name"] . " • " . $sizes["size"]; ?>
                                    </a>
                                </td>
                                <?php
                                $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                                $ceil = ceil($item);
                                $quantity = $ceil * $sizes["area"] / 10000;
                                ?>
                                <td class="vert-align"><?php if ($product["sample"] == 1){ $prquantity[$k] = $product["quantity"]; echo $product["quantity"]; }elseif ($product["productid"] == 146){ echo $product["quantity"]; }elseif ($product["productid"] == 145){ echo $product["quantity"]; }elseif ($product["productid"] == 134){ echo $product["quantity"]; }elseif ($product["productid"] == 138){ echo $product["quantity"]; }elseif ($product["productid"] == 248){ echo $product["quantity"]; }else{ echo $ceil; } ?>
                                    &nbsp;<?php echo $cmbitem["name"]; ?></td>
                                <td class="vert-align">
                                    <input name="qty2<?php echo $qtySl['id']; ?>" id="qty2<?php echo $qtySl['id']; ?>" type="text"
                                           value="<?php echo number_format($quantity, 3); ?>" size="4"
                                           class="required number form-control form-control-auto clear-input"
                                           aria-required="true" onchange="updateqty(<?php echo $qtySl['id']; ?>)">&nbsp;sqm
                                </td>
                                <td class="vert-align text-center">
                                    &nbsp;
                                    <a class="text-danger removeitem"
                                       onclick="removeitem(<?php echo $pr_id; ?>, <?php echo $product["id"]; ?>);">
                                        <i class="fa fa-times fa-lg" style="color: #843534;"></i>
                                    </a>
                                    &nbsp;
                                </td>
                                <?php
                                $subprice = $quantity * $prsizes["price"];
                                $subprices[$k] = $subprice;
                                ?>
                                <td class="vert-align" id="prprice">£ <?php echo number_format($prsizes["price"], 2); ?>
                                    <input type="hidden" name="prprice" id="prprice"
                                           value="<?php echo number_format($prsizes["price"], 2); ?>">
                                </td>
                                <td class="vert-align" id="subprice">£ <?php echo number_format($subprice, 2); ?>
                                    <input type="hidden" name="subprice" id="subprice"
                                           value="<?php echo number_format($subprice, 2); ?>">
                                </td>
                            </tr>
                            <?php $k++;
                        } ?>
                        </tbody>
                        <?php
                    }
                }
                else {
                    $notsample = true;
                    $pr_query = $db->query("select * from basket where productid='$productid' and sizeid='$sizeid' and sessionid='$default'")->fetch();
                    if ($pr_query["productid"] != $productid && $pr_query["sizeid"] != $sizeid) {
                        //$bid = $db->query("select * from orders order by id desc limit 0,1")->fetch();
                        $bid = $db->query("SELECT * FROM orders where sessionid='$default' and status=0 and isordered=0")->fetch();
                        $bs_query = $db->query("select * from basket where sessionid='$default'")->fetch();
                        if ($bid["sessionid"] != $default) {
                            $orderid = $bs_query["orderid"];
                            $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$default','$qty','20','29','$createdate','1','$select_site_name')");
                            ?>
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th># Item</th>
                                <th>Quantity</th>
                                <th>&nbsp;</th>
                                <th>Price</th>
                                <th>Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $newsession = $_SESSION["panelordersession"];
                            $prlist = $db->query("select * from basket where sessionid='$newsession'");
                            $subprices = array();
                            $prquantity = array();
                            $k = 0;
                            foreach ($prlist as $product) {
                                $pr_id = $product["productid"];
                                $size_id = $product["sizeid"];
                                $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                                $primage = $db->query("select * from images where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                                $szid = $pr["szid"];
                                $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                                $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                                $qtySl = $db->query("select * from basket where sessionid='$newsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                                $itemunit = $sizes["itemunit"];
                                $qtyunit = $sizes["qtyunit"];
                                $szunit = $sizes["sizeunit"];
                                $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                                $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();

                                ?>

                                <tr id="<?php echo $pr_id; ?>">
                                    <td>
                                        <img src="../../<?php echo $ordersite;  ?>/img/products/thumbs/<?php echo $primage["imagename"]; ?>"
                                             width="53" height="40" align="absmiddle" class="hidden-xs">
                                        <a href="?p=product_details&prid=<?php echo $product["productid"]; ?>&imgid=<?php echo $primage["id"]; ?>"
                                           class="text-info">
                                            <?php echo $pr["name"] . " • " . $sizes["size"] . "       "; ?>
                                            <?php
                                            if ($szunit != "None") {
                                                $unit = $db->query("select * from combos where id='$szunit'")->fetch();
                                                echo $unit["name"];
                                            }
                                            ?>
                                        </a>
                                    </td>
                                    <?php
                                    $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                                    $ceil = ceil($item);
                                    $quantity = $ceil * $sizes["area"] / 10000;
                                    ?>
                                    <td class="vert-align"><?php if ($product["sample"] == 1){ $prquantity[$k] = $product["quantity"]; echo $product["quantity"]; }elseif ($product["productid"] == 146){ echo $product["quantity"]; }elseif ($product["productid"] == 145){ echo $product["quantity"]; }elseif ($product["productid"] == 134){ echo $product["quantity"]; }elseif ($product["productid"] == 138){ echo $product["quantity"]; }elseif ($product["productid"] == 248){ echo $product["quantity"]; }else{ echo $ceil; } ?>
                                        &nbsp;<?php echo $cmbitem["name"]; ?></td>
                                    <td class="vert-align">
                                        <input name="qty2<?php echo $qtySl['id']; ?>" id="qty2<?php echo $qtySl['id']; ?>" type="text"
                                               value="<?php echo number_format($quantity, 3); ?>" size="4"
                                               class="required number form-control form-control-auto clear-input"
                                               aria-required="true" onchange="updateqty(<?php echo $qtySl['id']; ?>)">&nbsp;<?php echo $cmb["name"]; ?>
                                    </td>
                                    <td class="vert-align text-center">
                                        &nbsp;
                                        <a class="text-danger removeitem"
                                           onclick="removeitem(<?php echo $pr_id; ?>, <?php echo $product["id"]; ?>);">
                                            <i class="fa fa-times fa-lg" style="color: #843534;"></i>
                                        </a>
                                        &nbsp;
                                    </td>
                                    <?php
                                    $subprice = $quantity * $prsizes["price"];
                                    $subprices[$k] = $subprice;
                                    ?>
                                    <td class="vert-align">£ <?php echo number_format($prsizes["price"], 2); ?>
                                        <input type="hidden" name="prprice" id="prprice"
                                               value="<?php echo number_format($prsizes["price"], 2); ?>">
                                    </td>
                                    <td class="vert-align">£ <?php echo number_format($subprice, 2); ?>
                                        <input type="hidden" name="subprice" id="subprice"
                                               value="<?php echo number_format($subprice, 2); ?>">
                                    </td>
                                </tr>
                                <?php $k++;
                            } ?>
                            </tbody>
                            <?php
                        }
                        else {
                            $orderid = $bs_query["orderid"];
                            if (empty($bid["userid"]) && $bid["userid"] == "") {
                                $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$default','$qty','20','29','$createdate','1','$select_site_name')");

                            } else {
                                $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$default','$qty','20','29','$createdate','1','$select_site_name')");

                            }
                            ?>
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th># Item</th>
                                <th>Quantity</th>
                                <th>&nbsp;</th>
                                <th>Price</th>
                                <th>Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $newsession = $_SESSION["panelordersession"];
                            $prlist = $db->query("select * from basket where sessionid='$newsession'");
                            $subprices = array();
                            $prquantity = array();
                            $k = 0;
                            foreach ($prlist as $product) {
                                $pr_id = $product["productid"];
                                $size_id = $product["sizeid"];
                                $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                                $primage = $db->query("select * from images where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                                $szid = $pr["szid"];
                                $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                                $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                                $qtySl = $db->query("select * from basket where sessionid='$newsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                                $itemunit = $sizes["itemunit"];
                                $qtyunit = $sizes["qtyunit"];
                                $szunit = $sizes["sizeunit"];
                                $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                                $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();
                                ?>

                                <tr id="<?php echo $pr_id; ?>">
                                    <td>
                                        <img src="../../<?php echo $ordersite;  ?>/img/products/thumbs/<?php echo $primage["imagename"]; ?>"
                                             width="53" height="40" align="absmiddle" class="hidden-xs">
                                        <a href="?p=product_details&prid=<?php echo $product["productid"]; ?>&imgid=<?php echo $primage["id"]; ?>"
                                           class="text-info">
                                            <?php echo $pr["name"] . " • " . $sizes["size"]; ?>
                                        </a>
                                    </td>
                                    <?php
                                    $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                                    $ceil = ceil($item);
                                    $quantity = $ceil * $sizes["area"] / 10000;
                                    ?>
                                    <td class="vert-align"><?php if ($product["sample"] == 1){ $prquantity[$k] = $product["quantity"]; echo $product["quantity"]; }elseif ($product["productid"] == 146){ echo $product["quantity"]; }elseif ($product["productid"] == 145){ echo $product["quantity"]; }elseif ($product["productid"] == 134){ echo $product["quantity"]; }elseif ($product["productid"] == 138){ echo $product["quantity"]; }elseif ($product["productid"] == 248){ echo $product["quantity"]; }else{ echo $ceil; } ?>
                                        &nbsp;<?php echo $cmbitem["name"]; ?></td>
                                    <td class="vert-align">
                                        <input name="qty2<?php echo $qtySl['id']; ?>" id="qty2<?php echo $qtySl['id']; ?>" type="text"
                                               value="<?php echo number_format($quantity, 3); ?>" size="4"
                                               class="required number form-control form-control-auto clear-input"
                                               aria-required="true" onchange="updateqty(<?php echo $qtySl['id']; ?>)">&nbsp;sqm
                                    </td>
                                    <td class="vert-align text-center">
                                        &nbsp;
                                        <a class="text-danger removeitem"
                                           onclick="removeitem(<?php echo $pr_id; ?>, <?php echo $product["id"]; ?>);">
                                            <i class="fa fa-times fa-lg" style="color: #843534;"></i>
                                        </a>
                                        &nbsp;
                                    </td>
                                    <?php
                                    $subprice = $quantity * $prsizes["price"];
                                    $subprices[$k] = $subprice;
                                    ?>
                                    <td class="vert-align">£ <?php echo number_format($prsizes["price"], 2); ?>
                                        <input type="hidden" name="prprice" id="prprice"
                                               value="<?php echo number_format($prsizes["price"], 2); ?>">
                                    </td>
                                    <td class="vert-align">£ <?php echo number_format($subprice, 2); ?>
                                        <input type="hidden" name="subprice" id="subprice"
                                               value="<?php echo number_format($subprice, 2); ?>">
                                    </td>
                                </tr>
                                <?php $k++;
                            } ?>
                            </tbody>
                            <?php
                        }
                    }
                    else {
                        ?>
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th># Item</th>
                            <th>Quantity</th>
                            <th>&nbsp;</th>
                            <th>Price</th>
                            <th>Sub Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $newsession = $_SESSION["panelordersession"];
                        $prlist = $db->query("select * from basket where sessionid='$newsession'");
                        $subprices = array();
                        $prquantity = array();
                        $k = 0;
                        foreach ($prlist as $product) {
                            $pr_id = $product["productid"];
                            $size_id = $product["sizeid"];
                            $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $primage = $db->query("select * from images where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $szid = $pr["szid"];
                            $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                            $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                            $qtySl = $db->query("select * from basket where sessionid='$newsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                            $itemunit = $sizes["itemunit"];
                            $qtyunit = $sizes["qtyunit"];
                            $szunit = $sizes["sizeunit"];
                            $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                            $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();
                            ?>

                            <tr id="<?php echo $pr_id; ?>">
                                <td>
                                    <img src="../../<?php echo $ordersite;  ?>/img/products/thumbs/<?php echo $primage["imagename"]; ?>"
                                         width="53"
                                         height="40" align="absmiddle" class="hidden-xs">
                                    <a href="?p=product_details&prid=<?php echo $product["productid"]; ?>&imgid=<?php echo $primage["id"]; ?>"
                                       class="text-info">
                                        <?php echo $pr["name"] . " • " . $sizes["size"]; ?>
                                    </a>
                                </td>
                                <?php
                                $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                                $ceil = ceil($item);
                                $quantity = $ceil * $sizes["area"] / 10000;
                                ?>
                                <td class="vert-align"><?php if ($product["sample"] == 1){ $prquantity[$k] = $product["quantity"]; echo $product["quantity"]; }elseif ($product["productid"] == 146){ echo $product["quantity"]; }elseif ($product["productid"] == 145){ echo $product["quantity"]; }elseif ($product["productid"] == 134){ echo $product["quantity"]; }elseif ($product["productid"] == 138){ echo $product["quantity"]; }elseif ($product["productid"] == 248){ echo $product["quantity"]; }else{ echo $ceil; } ?>
                                    &nbsp;<?php echo $cmbitem["name"]; ?></td>
                                <td class="vert-align">
                                    <input name="qty2<?php echo $qtySl['id']; ?>" id="qty2<?php echo $qtySl['id']; ?>" type="text"
                                           value="<?php echo number_format($quantity, 3); ?>" size="4"
                                           class="required number form-control form-control-auto clear-input"
                                           aria-required="true" onchange="updateqty(<?php echo $qtySl['id']; ?>)">&nbsp;sqm
                                </td>
                                <td class="vert-align text-center">
                                    &nbsp;
                                    <a class="text-danger removeitem"
                                       onclick="removeitem(<?php echo $pr_id; ?>, <?php echo $product["id"]; ?>);">
                                        <i class="fa fa-times fa-lg" style="color: #843534;"></i>
                                    </a>
                                    &nbsp;
                                </td>
                                <?php
                                $subprice = $quantity * $prsizes["price"];
                                $subprices[$k] = $subprice;
                                ?>
                                <td class="vert-align" id="prprice">£ <?php echo number_format($prsizes["price"], 2); ?>
                                    <input type="hidden" name="prprice" id="prprice"
                                           value="<?php echo number_format($prsizes["price"], 2); ?>">
                                </td>
                                <td class="vert-align" id="subprice">£ <?php echo number_format($subprice, 2); ?>
                                    <input type="hidden" name="subprice" id="subprice"
                                           value="<?php echo number_format($subprice, 2); ?>">
                                </td>
                            </tr>
                            <?php $k++;
                        } ?>
                        </tbody>
                        <?php
                    }
                }
            }
            else{
                $_SESSION["panelordersession"] = $sessionid;
                $notsample = false;
                if ($sizeid == 88 && $notsample == false) {
                    $pr_query = $db->query("select * from basket where sessionid='$sessionid'")->fetch();
                    $bid = $db->query("SELECT * FROM orders where sessionid='$default' and status=0 and isordered=0")->fetch();
                    if ($bid["sessionid"] != $sessionid) {
                        $bid = $db->query("select * from orders order by id desc limit 0,1")->fetch();
                        $orderid = $bid["id"] + 1;
                        $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,sample,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$default','1','$qty','20','29','$createdate','1','$select_site_name')");
                        ?>
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th># Item</th>
                            <th>Quantity</th>
                            <th>&nbsp;</th>
                            <th>Price</th>
                            <th>Sub Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $newsession = $_SESSION["panelordersession"];
                        $prlist = $db->query("select * from basket where sessionid='$newsession'");
                        $subprices = array();
                        $prquantity = array();
                        $k = 0;
                        foreach ($prlist as $product) {
                            $pr_id = $product["productid"];
                            $size_id = $product["sizeid"];
                            $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $primage = $db->query("select * from images where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $szid = $pr["szid"];
                            $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                            $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                            $qtySl = $db->query("select * from basket where sessionid='$newsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                            $itemunit = $sizes["itemunit"];
                            $qtyunit = $sizes["qtyunit"];
                            $szunit = $sizes["sizeunit"];
                            $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                            $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();

                            ?>

                            <tr id="<?php echo $pr_id; ?>">
                                <td>
                                    <img src="../../<?php echo $ordersite;  ?>/img/products/thumbs/<?php echo $primage["imagename"]; ?>"
                                         width="53" height="40" align="absmiddle" class="hidden-xs">
                                    <a href="?p=product_details&prid=<?php echo $product["productid"]; ?>&imgid=<?php echo $primage["id"]; ?>"
                                       class="text-info">
                                        <?php echo $pr["name"] . " • " . $sizes["size"] . "       "; ?>
                                        <?php
                                        if ($szunit != "None") {
                                            $unit = $db->query("select * from combos where id='$szunit'")->fetch();
                                            echo $unit["name"];
                                        }
                                        ?>
                                    </a>
                                </td>
                                <?php
                                $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                                $ceil = ceil($item);
                                $quantity = $ceil * $sizes["area"] / 10000;
                                ?>
                                <td class="vert-align"><?php if ($product["sample"] == 1){ $prquantity[$k] = $product["quantity"]; echo $product["quantity"]; }elseif ($product["productid"] == 146){ echo $product["quantity"]; }elseif ($product["productid"] == 145){ echo $product["quantity"]; }elseif ($product["productid"] == 134){ echo $product["quantity"]; }elseif ($product["productid"] == 138){ echo $product["quantity"]; }elseif ($product["productid"] == 248){ echo $product["quantity"]; }else{ echo $ceil; } ?>
                                    &nbsp;<?php echo $cmbitem["name"]; ?></td>
                                <td class="vert-align">
                                    <input name="qty2<?php echo $qtySl['id']; ?>" id="qty2<?php echo $qtySl['id']; ?>" type="text"
                                           value="<?php echo number_format($quantity, 3); ?>" size="4"
                                           class="required number form-control form-control-auto clear-input"
                                           aria-required="true" onchange="updateqty(<?php echo $qtySl['id']; ?>)">&nbsp;<?php echo $cmb["name"]; ?>
                                </td>
                                <td class="vert-align text-center">
                                    &nbsp;
                                    <a class="text-danger removeitem"
                                       onclick="removeitem(<?php echo $pr_id; ?>, <?php echo $product["id"]; ?>);">
                                        <i class="fa fa-times fa-lg" style="color: #843534;"></i>
                                    </a>
                                    &nbsp;
                                </td>
                                <?php
                                $subprice = $quantity * $prsizes["price"];
                                $subprices[$k] = $subprice;
                                ?>
                                <td class="vert-align">£ <?php echo number_format($prsizes["price"], 2); ?>
                                    <input type="hidden" name="prprice" id="prprice"
                                           value="<?php echo number_format($prsizes["price"], 2); ?>">
                                </td>
                                <td class="vert-align">£ <?php echo number_format($subprice, 2); ?>
                                    <input type="hidden" name="subprice" id="subprice"
                                           value="<?php echo number_format($subprice, 2); ?>">
                                </td>
                            </tr>
                            <?php $k++;
                        } ?>
                        </tbody>
                        <?php
                    }
                    else {
                        $orderid = $pr_query["orderid"];
                        if (empty($bid["userid"]) && $bid["userid"] == "") {
                            $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,sample,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$default','1','$qty','20','29','$createdate','1','$select_site_name')");

                        }
                        else {
                            $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,sample,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$default','1','$qty','20','29','$createdate','1','$select_site_name')");

                        }

                        ?>
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th># Item</th>
                            <th>Quantity</th>
                            <th>&nbsp;</th>
                            <th>Price</th>
                            <th>Sub Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $newsession = $_SESSION["panelordersession"];
                        $prlist = $db->query("select * from basket where sessionid='$newsession'");
                        $subprices = array();
                        $prquantity = array();
                        $k = 0;
                        foreach ($prlist as $product) {
                            $pr_id = $product["productid"];
                            $size_id = $product["sizeid"];
                            $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $primage = $db->query("select * from images where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $szid = $pr["szid"];
                            $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                            $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                            $qtySl = $db->query("select * from basket where sessionid='$newsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                            $itemunit = $sizes["itemunit"];
                            $qtyunit = $sizes["qtyunit"];
                            $szunit = $sizes["sizeunit"];
                            $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                            $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();
                            ?>

                            <tr id="<?php echo $pr_id; ?>">
                                <td>
                                    <img src="../../<?php echo $ordersite;  ?>/img/products/thumbs/<?php echo $primage["imagename"]; ?>"
                                         width="53" height="40" align="absmiddle" class="hidden-xs">
                                    <a href="?p=product_details&prid=<?php echo $product["productid"]; ?>&imgid=<?php echo $primage["id"]; ?>"
                                       class="text-info">
                                        <?php echo $pr["name"] . " • " . $sizes["size"]; ?>
                                    </a>
                                </td>
                                <?php
                                $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                                $ceil = ceil($item);
                                $quantity = $ceil * $sizes["area"] / 10000;
                                ?>
                                <td class="vert-align"><?php if ($product["sample"] == 1){ $prquantity[$k] = $product["quantity"]; echo $product["quantity"]; }elseif ($product["productid"] == 146){ echo $product["quantity"]; }elseif ($product["productid"] == 145){ echo $product["quantity"]; }elseif ($product["productid"] == 134){ echo $product["quantity"]; }elseif ($product["productid"] == 138){ echo $product["quantity"]; }elseif ($product["productid"] == 248){ echo $product["quantity"]; }else{ echo $ceil; } ?>
                                    &nbsp;<?php echo $cmbitem["name"]; ?></td>
                                <td class="vert-align">
                                    <input name="qty2<?php echo $qtySl['id']; ?>" id="qty2<?php echo $qtySl['id']; ?>" type="text"
                                           value="<?php echo number_format($quantity, 3); ?>" size="4"
                                           class="required number form-control form-control-auto clear-input"
                                           aria-required="true" onchange="updateqty(<?php echo $qtySl['id']; ?>)">&nbsp;sqm
                                </td>
                                <td class="vert-align text-center">
                                    &nbsp;
                                    <a class="text-danger removeitem"
                                       onclick="removeitem(<?php echo $pr_id; ?>, <?php echo $product["id"]; ?>);">
                                        <i class="fa fa-times fa-lg" style="color: #843534;"></i>
                                    </a>
                                    &nbsp;
                                </td>
                                <?php
                                $subprice = $quantity * $prsizes["price"];
                                $subprices[$k] = $subprice;
                                ?>
                                <td class="vert-align">£ <?php echo number_format($prsizes["price"], 2); ?>
                                    <input type="hidden" name="prprice" id="prprice"
                                           value="<?php echo number_format($prsizes["price"], 2); ?>">
                                </td>
                                <td class="vert-align">£ <?php echo number_format($subprice, 2); ?>
                                    <input type="hidden" name="subprice" id="subprice"
                                           value="<?php echo number_format($subprice, 2); ?>">
                                </td>
                            </tr>
                            <?php $k++;
                        } ?>
                        </tbody>
                        <?php
                    }
                }
                else {
                    $notsample = true;
                    $pr_query = $db->query("select * from basket where sessionid='$sessionid'")->fetch();
                    //$bid = $db->query("select * from orders order by id desc limit 0,1")->fetch();
                    $bid = $db->query("SELECT * FROM orders where sessionid='$default' and status=0 and isordered=0")->fetch();
                    if ($bid["sessionid"] != $sessionid) {
                        $bid = $db->query("select * from orders order by id desc limit 0,1")->fetch();
                        $orderid = $bid["id"] + 1;
                        $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$sessionid','$qty','20','29','$createdate','1','$select_site_name')");
                        ?>
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th># Item</th>
                            <th>Quantity</th>
                            <th>&nbsp;</th>
                            <th>Price</th>
                            <th>Sub Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $newsession = $_SESSION["panelordersession"];
                        $prlist = $db->query("select * from basket where sessionid='$newsession'");
                        $subprices = array();
                        $prquantity = array();
                        $k = 0;
                        foreach ($prlist as $product) {
                            $pr_id = $product["productid"];
                            $size_id = $product["sizeid"];
                            $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $primage = $db->query("select * from images where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $szid = $pr["szid"];
                            $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                            $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                            $qtySl = $db->query("select * from basket where sessionid='$newsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                            $itemunit = $sizes["itemunit"];
                            $qtyunit = $sizes["qtyunit"];
                            $szunit = $sizes["sizeunit"];
                            $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                            $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();
                            ?>

                            <tr id="<?php echo $pr_id; ?>">
                                <td>
                                    <img src="../../<?php echo $ordersite;  ?>/img/products/thumbs/<?php echo $primage["imagename"]; ?>"
                                         width="53"
                                         height="40" align="absmiddle" class="hidden-xs">
                                    <a href="?p=product_details&prid=<?php echo $product["productid"]; ?>&imgid=<?php echo $primage["id"]; ?>"
                                       class="text-info">
                                        <?php echo $pr["name"] . " • " . $sizes["size"]; ?>
                                    </a>
                                </td>
                                <?php
                                $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                                $ceil = ceil($item);
                                $quantity = $ceil * $sizes["area"] / 10000;
                                ?>
                                <td class="vert-align"><?php if ($product["sample"] == 1){ $prquantity[$k] = $product["quantity"]; echo $product["quantity"]; }elseif ($product["productid"] == 146){ echo $product["quantity"]; }elseif ($product["productid"] == 145){ echo $product["quantity"]; }elseif ($product["productid"] == 134){ echo $product["quantity"]; }elseif ($product["productid"] == 138){ echo $product["quantity"]; }elseif ($product["productid"] == 248){ echo $product["quantity"]; }else{ echo $ceil; } ?>
                                    &nbsp;<?php echo $cmbitem["name"]; ?></td>
                                <td class="vert-align">
                                    <input name="qty2<?php echo $qtySl['id']; ?>" id="qty2<?php echo $qtySl['id']; ?>" type="text"
                                           value="<?php echo number_format($quantity, 3); ?>" size="4"
                                           class="required number form-control form-control-auto clear-input"
                                           aria-required="true" onchange="updateqty(<?php echo $qtySl['id']; ?>)">&nbsp;sqm
                                </td>
                                <td class="vert-align text-center">
                                    &nbsp;
                                    <a class="text-danger removeitem"
                                       onclick="removeitem(<?php echo $pr_id; ?>, <?php echo $product["id"]; ?>);">
                                        <i class="fa fa-times fa-lg" style="color: #843534;"></i>
                                    </a>
                                    &nbsp;
                                </td>
                                <?php
                                $subprice = $quantity * $prsizes["price"];
                                $subprices[$k] = $subprice;
                                ?>
                                <td class="vert-align" id="prprice">£ <?php echo number_format($prsizes["price"], 2); ?>
                                    <input type="hidden" name="prprice" id="prprice"
                                           value="<?php echo number_format($prsizes["price"], 2); ?>">
                                </td>
                                <td class="vert-align" id="subprice">£ <?php echo number_format($subprice, 2); ?>
                                    <input type="hidden" name="subprice" id="subprice"
                                           value="<?php echo number_format($subprice, 2); ?>">
                                </td>
                            </tr>
                            <?php $k++;
                        } ?>
                        </tbody>
                        <?php
                    }
                    else {
                        $orderid = $pr_query["orderid"];
                        $basketinsert = $db->query("insert into basket(orderid,productid,sizeid,sessionid,quantity,vatrate,vatid,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$sessionid','$qty','20','29','$createdate','1','$select_site_name')");
                        ?>
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th># Item</th>
                            <th>Quantity</th>
                            <th>&nbsp;</th>
                            <th>Price</th>
                            <th>Sub Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $newsession = $_SESSION["panelordersession"];
                        $prlist = $db->query("select * from basket where sessionid='$newsession'");
                        $subprices = array();
                        $prquantity = array();
                        $k = 0;
                        foreach ($prlist as $product) {
                            $pr_id = $product["productid"];
                            $size_id = $product["sizeid"];
                            $pr = $db->query("select * from products where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $primage = $db->query("select * from images where pr_id='$pr_id' and websiteid='$select_site_name'")->fetch();
                            $szid = $pr["szid"];
                            $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$size_id'")->fetch();
                            $sizes = $db->query("select * from sizes where id='$size_id'")->fetch();
                            $qtySl = $db->query("select * from basket where sessionid='$newsession' and productid='$pr_id' and sizeid='$size_id'")->fetch();
                            $itemunit = $sizes["itemunit"];
                            $qtyunit = $sizes["qtyunit"];
                            $szunit = $sizes["sizeunit"];
                            $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                            $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();
                            ?>
                            <tr>
                                <td>
                                    <img src="../../<?php echo $ordersite;  ?>/img/products/thumbs/<?php echo $primage["imagename"]; ?>"
                                         width="53"
                                         height="40" align="absmiddle" class="hidden-xs">
                                    <a href="?p=product_details&prid=<?php echo $product["productid"]; ?>&imgid=<?php echo $primage["id"]; ?>"
                                       class="text-info">
                                        <?php echo $pr["name"] . " • " . $sizes["size"]; ?>
                                    </a>
                                </td>
                                <?php
                                $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
                                $ceil = ceil($item);
                                $quantity = $ceil * $sizes["area"] / 10000;
                                ?>
                                <td class="vert-align"><?php if ($product["sample"] == 1){ $prquantity[$k] = $product["quantity"]; echo $product["quantity"]; }elseif ($product["productid"] == 146){ echo $product["quantity"]; }elseif ($product["productid"] == 145){ echo $product["quantity"]; }elseif ($product["productid"] == 134){ echo $product["quantity"]; }elseif ($product["productid"] == 138){ echo $product["quantity"]; }elseif ($product["productid"] == 248){ echo $product["quantity"]; }else{ echo $ceil; } ?>
                                    &nbsp;<?php echo $cmbitem["name"]; ?></td>
                                <td class="vert-align">
                                    <input name="qty2<?php echo $qtySl['id']; ?>" id="qty2<?php echo $qtySl['id']; ?>" type="text"
                                           value="<?php echo number_format($quantity, 3); ?>" size="4"
                                           class="required number form-control form-control-auto clear-input"
                                           aria-required="true" onchange="updateqty(<?php echo $qtySl['id']; ?>)">&nbsp;sqm
                                </td>
                                <td class="vert-align text-center">
                                    &nbsp;
                                    <a class="text-danger removeitem"
                                       onclick="removeitem(<?php echo $pr_id; ?>, <?php echo $product["id"]; ?>);">
                                        <i class="fa fa-times fa-lg" style="color: #843534;"></i>
                                    </a>
                                    &nbsp;
                                    <!--  ?pg=salesOrder&process=cartremove&prid=<?php // echo $product["productid"];
                                    ?>&bid=<?php // echo $product["id"];
                                    ?>  -->
                                </td>
                                <?php
                                $subprice = $quantity * $prsizes["price"];
                                $subprices[$k] = $subprice;
                                ?>
                                <td class="vert-align" id="prprice">£ <?php echo number_format($prsizes["price"], 2); ?>
                                    <input type="hidden" name="prprice" id="prprice"
                                           value="<?php echo number_format($prsizes["price"], 2); ?>">
                                </td>
                                <td class="vert-align" id="subprice">£ <?php echo number_format($subprice, 2); ?>
                                    <input type="hidden" name="subprice" id="subprice"
                                           value="<?php echo number_format($subprice, 2); ?>">
                                </td>
                            </tr>
                            <?php $k++;
                        } ?>
                        </tbody>
                        <?php
                    }
                }
            }
        }
        elseif ($process == "cartremove"){
            $prid = $_GET["product_id"];
            $basketid = $_GET["basketid"];
            $prdel = $db->query("DELETE FROM basket WHERE id='$basketid' and productid='$prid'");
            if ($prdel){
                if (isset($_SESSION["panelordersession"])){
                    $sessionid = $_SESSION["panelordersession"];
                    $session = $db->query("select * from basket where sessionid='$sessionid'");
                    $cntsess = $session->rowCount();
                    if ($cntsess != 0){
                        echo 1;
                    }
                    else{
                        unset($_SESSION["panelordersession"]);
                        echo 1;
                    }
                }
                else{
                    unset($_SESSION["panelordersession"]);
                    echo 1;
                }
            }
            else{
                echo 0;
            }
        }

    }

    elseif ($pg == "invoice"){
        $operation = $_GET["operation"];
        $process = $_GET["process"];
        if ($operation == "enterinvoice"){
            if (isset($_POST["invoiceAdd"])){
                $selectCustomer = strip_tags(trim($_POST["selectCustomer"]));
                $selectAddress = strip_tags(trim($_POST["selectAddress"]));
                $selectWebsite = strip_tags(trim($_POST["selectWebsite"]));
                $addNotes = strip_tags(trim($_POST["addNotes"]));

                $sessionid = date("Ymdhis").$selectCustomer.$selectAddress.$selectWebsite;

                $max = $db->query("SELECT max(id) as maxid FROM orders")->fetch();
                $sonid = $max["maxid"] + 1;
                if (empty($sonid)){ $sonid = 1; }
                $maxinv = $db->query("SELECT max(invoiceno) as maxid FROM orders where isdeleted<>1")->fetch();
                $invoiceNo = $maxinv["maxid"] + 1;
                if (empty($invoiceNo)){ $invoiceNo = 1000; }

                $invoicedate = date("Y-m-d");
                $cartdate = date("Y-m-d");
                $orderdate = date("Y-m-d");

                // Bu sorgu da userid kolonu da kullanılmıştı fakat user id yi alabileceğimiz bir yer şu anda yok....
                $saveInvoice = $db->query("insert into orders(id,invoiceno,ordertype,status,companyid,addressid,shiptoaddressid,sessionid,cartdate,orderdate,notes,invoicedate,websiteid) values('$sonid','$invoiceNo','4','0','$selectCustomer','$selectAddress','$selectAddress','$sessionid','$cartdate','$orderdate','$addNotes','$invoicedate','$selectWebsite')");

                $pnlusr = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                $processdate = date("Y-m-d H:i:s");
                $tablename = "orders";
                $tableid = $sonid;
                $parentid = 0;
                $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
                $userid = $pnlusr["id"];
                $log_notes = "Invoice";

                $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");

                if ($saveInvoice){
                    header("Location: ../index.php?p=invoice&inv=open");
                    setcookie('testcookie', "true|Invoice Saved...", time() + 20, '/');
                }
                else{
                    header("Location: ../index.php?p=invoice&inv=open");
                    setcookie('testcookie', "false|Invoice Not Saved...", time() + 20, '/');
                }
            }
            elseif (isset($_POST["posttype"])){
                $selectCustomer = strip_tags(trim($_POST["selectCustomer"]));
                $selectAddress = strip_tags(trim($_POST["selectAddress"]));
                $selectWebsite = strip_tags(trim($_POST["selectWebsite"]));
                $addNotes = strip_tags(trim($_POST["addNotes"]));

                $sessionid = date("Ymdhis").$selectCustomer.$selectAddress.$selectWebsite;

                $max = $db->query("SELECT max(id) as maxid FROM orders")->fetch();
                $sonid = $max["maxid"] + 1;
                if (empty($sonid)){ $sonid = 1; }
                $maxinv = $db->query("SELECT max(invoiceno) as maxid FROM orders where isdeleted<>1")->fetch();
                $invoiceNo = $maxinv["maxid"] + 1;
                if (empty($invoiceNo)){ $invoiceNo = 1000; }

                $invoicedate = date("Y-m-d");
                $cartdate = date("Y-m-d");
                $orderdate = date("Y-m-d");

                // Bu sorgu da userid kolonu da kullanılmıştı fakat user id yi alabileceğimiz bir yer şu anda yok....
                $saveInvoice = $db->query("insert into orders(id,invoiceno,ordertype,status,companyid,addressid,shiptoaddressid,sessionid,cartdate,orderdate,notes,invoicedate,websiteid) values('$sonid','$invoiceNo','4','0','$selectCustomer','$selectAddress','$selectAddress','$sessionid','$cartdate','$orderdate','$addNotes','$invoicedate','$selectWebsite')");

                $pnlusr = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                $processdate = date("Y-m-d H:i:s");
                $tablename = "orders";
                $tableid = $sonid;
                $parentid = 0;
                $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
                $userid = $pnlusr["id"];
                $log_notes = "Invoice";

                $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");

                if ($saveInvoice){
                    echo 1;
                }
                else{
                    echo 0;
                }
            }
        }
        elseif ($process == "invoiceeditok"){
            $inv = $_GET["inv"];
            $type = $_GET["type"];
            $orderid = $_GET["orderid"];
            if (isset($_POST["updateinvoice"])){
                $IDsite = strip_tags(trim($_POST["websiteid"]));
                $notes = strip_tags(trim($_POST["notes"]));
                $customernotes = strip_tags(trim($_POST["customernotes"]));
                $officenotes = strip_tags(trim($_POST["officenotes"]));
                $pono = strip_tags(trim($_POST["pono"]));

                $invdt = strtotime($_POST["invoicedate"]);
                $invoicedate = date("Y-m-d",$invdt);
                $rq = strtotime($_POST["orderdateup"]);
                $request = date('Y-m-d H:i:s', $rq);
                $shp = strtotime($_POST["deliverydate"]);
                $shipment = date('Y-m-d H:i:s', $shp);

                $orderdata = $db->query("update orders set orderdate='$request', deliverydate='$shipment', notes='$notes', customernotes='$customernotes', invoicedate='$invoicedate', pono='$pono', officenotes='$officenotes', websiteid='$IDsite' where id='$orderid'");
                if ($orderdata->rowCount()){
                    header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
                    setcookie('testcookie', "true|Invoice Data Updated...", time() + 20, '/');
                }
                else{
                    header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
                    setcookie('testcookie', "false|Invoice Data Not Updated...", time() + 20, '/');
                }
            }
        }
        elseif ($process == "panel-invoice-edit"){
            $inv = $_GET["inv"];
            $orid = $_GET["orderid"];
            $type = $_GET["type"];
            $website = $_GET["websiteid"];
            if (isset($_POST["button5"])){
                $qty = strip_tags(trim($_POST["qty"]));
                $productid = $_POST["productid"];
                $product_sizeid = $_POST["product_sizeid"];
                $createdate = date("Y.m.d h:i:s");
                $orderselect = $db->query("select * from orders where id='$orid'")->fetch();
                $sessionid = $orderselect["sessionid"];
                if ($productid == 88){
                    $basket = $db->query("insert into basket(orderid,productid,sizeid,sessionid,sample,quantity,vatrate,createdate,isordered,websiteid) values('$orid','$productid','$product_sizeid','$sessionid','1','$qty','20','$createdate','1','$website')");
                    if ($basket->rowCount()){
                        header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
                    }
                    else{
                        header("Location: ../index.php?p=invoice");
                    }
                }
                else{
                    $basket = $db->query("insert into basket(orderid,productid,sizeid,sessionid,quantity,vatrate,createdate,isordered,websiteid) values('$orid','$productid','$product_sizeid','$sessionid','$qty','20','$createdate','1','$website')");
                    if ($basket->rowCount()){
                        header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
                    }
                    else{
                        header("Location: ../index.php?p=invoice");
                    }
                }
            }

            if ($_POST["isnextday"] == "0"){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                $zn = $codepst["zone"];
                $zoneselect = $db->query("select * from zones where id='$zn'")->fetch();
                $isnextday = $_POST["isnextday"];
                $zone = $zoneselect["economy"];
                //$updelivery = $db->query("update orders set deliverydate=NULL, isnextday='$isnextday', deliverytime=NULL, deliveryprice='$zone' where id='$orid'");
                $updelivery = $db->prepare("update orders set deliverydate=:deliverydate, isnextday=:isnextday, deliverytime=:deliverytime, deliveryprice=:deliveryprice where id=:id");
                $updelivery->bindValue(':deliverydate',NULL,PDO::PARAM_NULL);
                $updelivery->bindValue(':isnextday',$isnextday,PDO::PARAM_INT);
                $updelivery->bindValue(':deliverytime',NULL,PDO::PARAM_NULL);
                $updelivery->bindValue(':deliveryprice',$zone,PDO::PARAM_INT);
                $updelivery->bindValue(':id',$orid,PDO::PARAM_INT);
                $updelivery->execute();

                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
            }
            if ($_POST["isnextday"] == "1"){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                $zn = $codepst["zone"];
                $zoneselect = $db->query("select * from zones where id='$zn'")->fetch();
                $isnextday = $_POST["isnextday"];
                $zone = $zoneselect["nextday"];
                //$updelivery = $db->query("update orders set deliverydate=NULL, isnextday='$isnextday', deliverytime=NULL, deliveryprice='$zone' where id='$orid'");
                $updelivery = $db->prepare("update orders set deliverydate=:deliverydate, isnextday=:isnextday, deliverytime=:deliverytime, deliveryprice=:deliveryprice where id=:id");
                $updelivery->bindValue(':deliverydate',NULL,PDO::PARAM_NULL);
                $updelivery->bindValue(':isnextday',$isnextday,PDO::PARAM_INT);
                $updelivery->bindValue(':deliverytime',NULL,PDO::PARAM_NULL);
                $updelivery->bindValue(':deliveryprice',$zone,PDO::PARAM_INT);
                $updelivery->bindValue(':id',$orid,PDO::PARAM_INT);
                $updelivery->execute();

                header("Location: ../index.php?p=invoice&inv=" . $inv . "&pg=panel-invoice-edit&orderid=" . $orid);
            }
            if ($_POST["deliverytime"] == "standard"){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                $zn = $codepst["zone"];
                $zone = $db->query("select * from zones where id='$zn'")->fetch();
                $deliveryprice = $zone["nextday"];
                $uptime = $db->query("update orders set deliverytime='".$_POST["deliverytime"]."', deliveryprice='$deliveryprice' where id='$orid'");
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
            }
            if ($_POST["deliverytime"] == "am"){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                $zn = $codepst["zone"];
                $zone = $db->query("select * from zones where id='$zn'")->fetch();
                $deliveryprice = $zone["nextday"] + $zone["ampm"];
                $uptime = $db->query("update orders set deliverytime='".$_POST["deliverytime"]."', deliveryprice='$deliveryprice' where id='$orid'");
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
            }
            if ($_POST["deliverytime"] == "pm"){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                $zn = $codepst["zone"];
                $zone = $db->query("select * from zones where id='$zn'")->fetch();
                $deliveryprice = $zone["nextday"] + $zone["ampm"];
                $uptime = $db->query("update orders set deliverytime='".$_POST["deliverytime"]."', deliveryprice='$deliveryprice' where id='$orid'");
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
            }
            if ($_POST["deliverytime"] == "saturdayam"){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                $zn = $codepst["zone"];
                $zone = $db->query("select * from zones where id='$zn'")->fetch();
                $deliveryprice = $zone["nextday"] + $zone["saturdayam"];
                $uptime = $db->query("update orders set deliverytime='".$_POST["deliverytime"]."', deliveryprice='$deliveryprice' where id='$orid'");
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
            }
            if ($_POST["deliverytime"] == "saturdaypm"){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                $zn = $codepst["zone"];
                $zone = $db->query("select * from zones where id='$zn'")->fetch();
                $deliveryprice = $zone["nextday"] + $zone["saturdaypm"];
                $uptime = $db->query("update orders set deliverytime='".$_POST["deliverytime"]."', deliveryprice='$deliveryprice' where id='$orid'");
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
            }
            if (isset($_POST["datepicker"])){
                $pt = strtotime($_POST["datepicker"]);
                $tr = date('Y-m-d',$pt);
                $uptime = $db->query("update orders set deliverydate='$tr' where id='$orid'");
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
            }
            if (isset($_POST["deliverydate"])){
                $ptdt = strtotime($_POST["deliverydate"]);
                $trdt = date('Y-m-d',$ptdt);
                $updeliverytime = $db->query("update orders set deliverydate='$trdt' where id='$orid'");
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
            }
            if (isset($_POST["postcodeid"])){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$_POST["postcodeid"]."'")->fetch();
                $zn = $codepst["zone"];
                $zoneselect = $db->query("select * from zones where id='$zn'")->fetch();
                $pstup = $db->query("update orders set postcodeid='".$_POST["postcodeid"]."', deliveryprice='".$zoneselect["nextday"]."' where id='$orid'");
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
            }
            if (isset($_POST["couponbutton"])){
                if (isset($_POST["couponcode"]) && $_POST["couponcode"] != "" && $_POST["orderid"] != ""){
                    $orderid = $orid;
                    $couponcode = strip_tags(trim($_POST["couponcode"]));
                    $cpn = $db->query("SELECT * FROM orders where id='$orderid'")->fetch();
                    if ($couponcode == $cpn["couponcode"]){
                        $valid = 1;
                        header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid."&beforevalid=1");
                    }
                    else{
                        $sizes = $db->query("select * from coupons where couponcode='$couponcode'");
                        if ($sizes->rowCount()){
                            foreach($sizes as $sz){
                                $couponcode = $sz["couponcode"];
                                $ste = strtotime($sz["validdate"]);
                                $validdate = date("Y-m-d",$ste);
                                $validdate = strtotime($validdate);
                                $now = strtotime(date("Y-m-d"));
                                if (($sz["isused"] == "0") && ($validdate >= $now)){
                                    $cpnup = $db->query("update orders set couponcode='$couponcode' where id='$orderid'");
                                    if ($cpnup->rowCount()){
                                        $or = $db->query("select * from orders where id='$orderid'")->fetch();
                                        $subdis = $or["subtotal"] * ($sz["discountrate"] / 100);
                                        $sub = $or["subtotal"] - $subdis;
                                        $vatdis = $or["vattotal"] * ($sz["discountrate"] / 100);
                                        $vat = $or["vattotal"] - $vatdis;
                                        $entdis = $or["entotal"] * ($sz["discountrate"] / 100);
                                        $ent = $or["entotal"] - $entdis;
                                        $upent = $db->query("update orders set subtotal='$sub', vattotal='$vat', entotal='$ent', discountrate='".$sz["discountrate"]."', discountprice='$entdis' where id='$orderid'");
                                        if ($upent->rowCount()){
                                            $voucher = "_voucher_";
                                            $discount = $db->query("SELECT sizes.size, sizes.id as sizeid, product_sizes.id as product_sizeid, products.name, products.szid, products.pr_id FROM products left join product_sizes on product_sizes.productid=products.szid left join sizes on sizes.id=product_sizes.sizeid where product_sizes.note='$voucher' and products.websiteid='$website'")->fetch();
                                            $productid = $discount["pr_id"];
                                            $productname = $discount["name"];
                                            $sizeid = $discount["sizeid"];
                                            $product_sizeid = $discount["product_sizeid"];
                                            $sizename = $discount["size"];

                                            $createdate = $or["orderdate"];
                                            $sessionid = $or["sessionid"];

                                            $discountsave = $db->query("insert into basket (orderid, productid, sizeid, sessionid, quantity, vatrate, vatid, isdiscount, isdeleted, isnondiscountable, createdate, isordered, websiteid) values('$orid','$productid','$sizeid','$sessionid','1','20','29','1','0','1','$createdate','1','$website')");
                                            if ($discountsave->rowCount()){
                                                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid."&valid=1");
                                            }
                                            else{
                                                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
                                            }

                                        }
                                        else{
                                            header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
                                        }
                                    }
                                    else{
                                        header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
                                    }
                                }
                                else{
                                    if ($validdate < $now){
                                        $notvalid = 3;
                                        header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid."&notvalid=".$notvalid);
                                    }
                                    elseif ($sz["isused"] == "1"){
                                        $notvalid = 2;
                                        $couponcode = "";
                                        $cpnup = $db->query("update orders set couponcode='$couponcode' where id='$orderid'");
                                        if ($cpnup->rowCount()){
                                            header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid."&notvalid=".$notvalid);
                                        }
                                        else{
                                            header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
                                        }
                                    }
                                    else{
                                        header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
                                    }
                                }
                            }
                        }
                        else{
                            $notvalid = 1;
                            $couponcode = "";
                            $cpnup = $db->query("update orders set couponcode='$couponcode' where id='$orderid'");
                            if ($cpnup->rowCount()){
                                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid."&notvalid=".$notvalid);
                            }
                            else{
                                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
                            }
                        }
                    }
                }
                else{
                    header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orid);
                }
            }

        }
        elseif ($operation == "orderdispatched"){

            $type = $_GET["type"];
            $orderid = $_GET["orderid"];
            $status = $_GET["status"];
            $inv = $_GET["inv"];

            $bs = $db->query("SELECT id from basket where orderid='$orderid'");

            if ($bs->rowCount()){
                $customer = $db->query("SELECT company_address.*, orders.*, orders.isdeleted as oisdeleted, companies.* FROM company_address left join orders on company_address.id=orders.addressid left join companies on companies.id=orders.companyid where orders.id='$orderid'")->fetch();
                $websiteid = $customer["websiteid"];
                $strName = $customer["firstname"]."  ".$customer["lastname"];
                $strEmail = $customer["email"];
                $couponcode = $customer["couponcode"];
                $isplacedorder = 1;

                if ($customer["deliverydate"] == "" || empty($customer["deliverydate"])){
                    $deliverydate = date("Y-m-d");
                    $uporder = $db->query("update orders set status='$status', deliverydate='$deliverydate', isplacedorder='$isplacedorder' where id='$orderid'");
                    /*
                    $uporder = $db->prepare("update orders set status=:status, deliverydate=:deliverydate, isplacedorder=:isplacedorder where id:id");
                    $uporder->bindValue(':status',$status,PDO::PARAM_INT);
                    $uporder->bindValue(':deliverydate',$deliverydate,PDO::PARAM_INT);
                    $uporder->bindValue(':isplacedorder',$isplacedorder,PDO::PARAM_INT);
                    $uporder->bindValue(':id',$orderid,PDO::PARAM_INT);
                    $uporder->execute();
                    */
                }
                else{
                    $deliverydate = NULL;
                    $uporder = $db->query("update orders set status='$status', deliverydate='$deliverydate', isplacedorder='$isplacedorder' where id='$orderid'");
                    /*
                    $uporder = $db->prepare("update orders set status=:status, deliverydate=:deliverydate, isplacedorder=:isplacedorder where id:id");
                    $uporder->bindValue(':status',$status,PDO::PARAM_INT);
                    $uporder->bindValue(':deliverydate',NULL,PDO::PARAM_NULL);
                    $uporder->bindValue(':isplacedorder',$isplacedorder,PDO::PARAM_INT);
                    $uporder->bindValue(':id',$orderid,PDO::PARAM_INT);
                    $uporder->execute();
                    */
                }

                $strAddress = $customer["house"]." , ".$customer["street"]." , ".$customer["city"]." , ".$customer["county"]." , ".$customer["postcode"];

                //$uporder = $db->query("update orders set status='$status', deliverydate='$deliverydate', isplacedorder='$isplacedorder' where id='$orderid'");
                if ($couponcode != ""){
                    $upcpn = $db->query("UPDATE coupons set isused=1 WHERE couponcode='$couponcode'");
                }

                $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                $processdate = date("Y-m-d H:i:s");
                $tablename = "orders";
                $tableid = $sonid;
                $parentid = 0;
                $logtypeid = 43;     // 42 New- 43 Update- 44 Delete
                $userid = $paneluser["id"];
                $log_notes = $pagequery." Shipped Email";

                $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");

                if ($_GET["email"] == "noemail"){
                    if ($uporder){
                        setcookie('testcookie', "true|Order dispatched but not send email...", time() + 20, '/');
                        header("Location: ../index.php?p=invoice&inv=delivered&pg=panel-invoice-edit&orderid=".$orderid);
                    }
                    else{
                        setcookie('testcookie', "false|Order Not dispatched and not send email...", time() + 20, '/');
                        header("Location: ../index.php?p=invoice&inv=delivered&pg=panel-invoice-edit&orderid=".$orderid);
                    }
                }
                else{
                    $forwhat = "Order-Dispatched";
                    if ($websiteid == 2){
                        $strFromEmail = "sales@travertinetilesuk.com";
                    }
                    elseif ($websiteid == 1){
                        $strFromEmail = "info@stonedeals.co.uk";
                    }

                    /* MAIL */

                }

            }
            else{
                setcookie('testcookie', "false|No products added to the order. Please first add a product to make shipment...", time() + 20, '/');
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
            }
        }
        elseif ($operation == "invoicedeleteall"){

            $orderid = $_GET["orderid"];

            $upbs = $db->query("UPDATE basket SET isdeleted=1 WHERE orderid='$orderid'");
            $uporder = $db->query("UPDATE orders SET isdeleted=1 WHERE id='$orderid'");

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d H:i:s");
            $tablename = "orders";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 44;     // 42 New- 43 Update- 44 Delete
            $userid = $paneluser["id"];
            $log_notes = "Invoice";

            $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");

            if ($uporder){
                setcookie('testcookie', "true|Invoice All Data Deleted...", time() + 20, '/');
                header("Location: ../index.php?p=invoice&inv=open");
            }
            else{
                setcookie('testcookie', "false|Invoice All Data Not Deleted...", time() + 20, '/');
                header("Location: ../index.php?p=invoice&inv=open");
            }

        }
        elseif ($operation == "invoicestatus"){
            $inv = $_GET["inv"];
            $status = $_GET["status"];
            $orderid = $_GET["orderid"];

            $orderselect = $db->query("select * from orders where id='$orderid'")->fetch();

            if ($status == 1 && ($orderselect["deliverydate"] == "" || empty($orderselect["deliverydate"]))){
                $deliverydate = date("Y-m-d");
                $uporder = $db->prepare("update orders set status=:status, deliverydate=:deliverydate where id:id");
                $uporder->bindValue(":status",$status,PDO::PARAM_INT);
                $uporder->bindValue(":deliverydate",$deliverydate,PDO::PARAM_INT);
                $uporder->bindValue(":id",$orderid,PDO::PARAM_INT);
                $uporder->execute();
            }
            elseif ($status == 2){

                $upacc = $db->query("update orders set status='$status' where id='$orderid'");

                $selectorder = $db->query("SELECT * FROM orders where id='$orderid'")->fetch();

                $companyid = $selectorder["companyid"];
                $entotal = $selectorder["entotal"];
                $vattotal = $selectorder["vattotal"];
                $pono = $selectorder["pono"];

                if ($selectorder["couponcode"] != ""){ $couponcode = $selectorder["couponcode"]; }

                $max = $db->query("SELECT max(id) as maxid FROM accounts")->fetch();
                $sonid = $max["maxid"] + 1;
                if ($sonid == "" || empty($sonid)){ $sonid = 1; }

                $transactiondate = date("Y-m-d");

                $saveaccount = $db->query("insert into accounts(id, transactiondate, companyid, invoiceid) values('$sonid','$transactiondate','$companyid','$orderid')");

                $amount = str_replace(",",".",$entotal);

                if ($amount == ""){ $amount = 0; }
                $transactiontype = 17;
                $cmb = $db->query("SELECT * FROM combos where id='$transactiontype'")->fetch();
                $transactiontype = $cmb["name"];
                if (strstr($transactiontype,"()")){

                }
                elseif (strstr($transactiontype,"(+)")){
                    if ($amount > 0){

                    }
                    else{
                        $amount = 0 - ($amount);
                    }
                }
                elseif (strstr($transactiontype,"(-)")){
                    if ($amount > 0){
                        $amount = 0 - ($amount);
                    }
                    else{

                    }
                }

                $upacc2 = $db->query("update accounts set amount='$amount' where id='$sonid'");

                $vatamount = str_replace(",",".",$vattotal);

                if ($vatamount == ""){ $vatamount = 0; }
                $transactiontype = 17;
                $cmb = $db->query("SELECT * FROM combos where id='$transactiontype'")->fetch();
                $transactiontype = $cmb["name"];
                if (strstr($transactiontype,"()")){

                }
                elseif (strstr($transactiontype,"(+)")){
                    if ($vatamount > 0){

                    }
                    else{
                        $vatamount = 0 - ($vatamount);
                    }
                }
                elseif (strstr($transactiontype,"(-)")){
                    if ($vatamount > 0){
                        $vatamount = 0 - ($vatamount);
                    }
                    else{

                    }
                }

                $upacc3 = $db->query("update accounts set vatamount='$vatamount' where id='$sonid'");

                $upacc4 = $db->query("update accounts set transactiontype='17', paymentmethod='5', reference='$pono', bankid='36', note='Invoice' where id='$sonid'");
                if ($couponcode != ""){
                    $upcpn = $db->query("UPDATE coupons set isused=1 WHERE couponcode='$couponcode'");
                }

                $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                $processdate = date("Y-m-d H:i:s");
                $tablename = "orders";
                $tableid = $orderid;
                $parentid = 0;
                $logtypeid = 43;     // 42 New- 43 Update- 44 Delete
                $userid = $paneluser["id"];

                if ($status == 2){
                    $log_notes = "Invoice Status Change : ".$status;
                }
                else{
                    $log_notes = "Invoice Accounted and Archived : ".$amount;
                }

                $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");

                if ($upacc && $upacc2 && $upacc3 && $upacc4){
                    header("Location: ../index.php?p=invoice&inv=archived&pg=panel-invoice-edit&orderid=".$orderid);
                }
                else{
                    header("Location: ../index.php?p=invoice&inv=archived");
                }
            }
        }
        elseif ($operation == "invoicetopurchase"){
            $inv = $_GET["inv"];
            $orderid = $_GET["orderid"];

            $max = $db->query("SELECT max(id) as maxid FROM purchases")->fetch();
            $sonid = $max["maxid"] + 1;
            if ($sonid == "" || empty($sonid)){ $sonid = 1; }

            $maxpur = $db->query("SELECT max(purchaseno) as maxid FROM purchases where isdeleted<>1")->fetch();
            $purchaseno = $maxpur["maxid"] + 1;
            if ($purchaseno == "" || empty($purchaseno)){ $purchaseno = 1000; }

            $cmp = $db->query("SELECT id from company_address where companyid=67 and ismain=1")->fetch();
            $addressid = $cmp["id"];

            $order = $db->query("SELECT id, invoiceno, websiteid FROM orders where id='$orderid'")->fetch();
            $invoiceno = $order["invoiceno"];
            $websiteid = $order["websiteid"];
            if ($websiteid == 2){
                $website = "TS";
            }
            elseif ($websiteid == 1){
                $website = "SD";
            }

            $invoicedate = date("Y-m-d");
            if ($invoiceno != ""){ $salesinvoiceno = $invoiceno; }

            $savepurchase = $db->query("insert into purchases (id, purchaseno, companyid, status, invoicedate, addressid, salesinvoiceno) values('$sonid','$purchaseno','67','0','$invoicedate','$addressid','$salesinvoiceno')");

            $selectbasket = $db->query("select * from basket left join sizes on sizes.id=basket.sizeid left join products on products.pr_id=basket.productid WHERE basket.orderid='$orderid' and sizes.size<>'Sample' and sizes.size not like '%Delivery%' and sizes.size not like '%Discount%' and (products.showsite=1 or products.isdummy=1")->fetch();

            $pr_id = $selectbasket["productid"];
            $vatid = $selcetbasket["vatid"];
            $vatrate = $selectbasket["vatrate"];
            $sizeid = $selectbasket["sizeid"];
            $szid = $selectbasket["szid"];
            $sz = $db->query("select * from product_sizes where productid='$szid' and sizeid='$sizeid'")->fetch();
            $product_sizeid = $sz["id"];
            $wasprice = $sz["wasprice"];
            $price = $wasprice * 0.94;
            $productname = "PRODUCTS AND SERVICES ON";
            $sizes = $db->query("select * from sizes where id='$sizeid'")->fetch();

            $qtyunit = $sizes["qtyunit"];

            $qtySl = $db->query("select * from basket where orderid='$orderid' and productid='$pr_id' and sizeid='$sizeid'")->fetch();
            $item = $qtySl["quantity"] / ($sizes["area"] / 10000);
            $ceil = ceil($item);
            $qty = $ceil * $sizes["area"] / 10000;

            $savepurchasecart = $db->query("insert into purchase_cart (purchaseid, product_sizeid, productname, qty, unit, expenseid, price, isdeleted, vatid, vatrate, salesinvoiceno) values('$sonid','$product_sizeid','$productname','$qty','$qtyunit','48','$price','0','$vatid','$vatrate','$invoiceno')");
            $selectpurchase = $db->query("SELECT * FROM purchase_cart where purchaseid='$sonid'");

            if ($selectpurchase->rowCount()){
                foreach($selectpurchase as $purchase){
                    $qty = 0;
                    $qty = $purchase["qty"];
                    $qty = ceil($qty);
                    $price = 0;
                    $price = $purchase["price"];
                    $subprice = 0;
                    $subprice = $price * $qty;
                    $cmb = $db->query("SELECT * FROM combos where menu='vat' and id='".$purchase["vatid"]."'")->fetch();
                    $vatrate = $cmb["amount"];
                    $vatprice = 0;
                    $vatprice = $subprice * $vatrate / (100 + $vatrate);
                    $vattotal = $vattotal + $vatprice;
                    $entotal = $entotal + $subprice;
                }
            }

            if ($vattotal != ""){
                $vattotal = number_format($vattotal,2);
            }
            else{
                $vattotal = 0;
            }
            if ($entotal != ""){
                $entotal = number_format($entotal,2);
            }
            else{
                $entotal = 0;
            }
            $subtotal = ($entotal - $vattotal);
            if ($subtotal != ""){
                $subtotal = number_format($subtotal,2);
            }
            else{
                $subtotal = 0;
            }

            $uppurchase = $db->query("update purchases set subtotal='$subtotal',vattotal='$vattotal',entotal='$entotal' where id='$sonid'");

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d H:i:s");
            $tablename = "purchases";
            $tableid = $sonid;
            $parentid = 0;
            $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
            $userid = $paneluser["id"];
            $log_notes = "invoice to purchase";

            $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");

            if ($uppurchase){
                header("Location: ../index.php?p=supplier&spp=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid);
            }
            else{
                header("Location: ../index.php?p=supplier&spp=findpurchase");
            }
        }
        elseif ($operation == "converttocreditnote"){
            $inv = $_GET["inv"];
            $orderid = $_GET["orderid"];
            $companyid = $_GET["companyid"];
            $type = $_GET["type"];

            $order = $db->query("select * from orders where id='$orderid'")->fetch();

            $max = $db->query("SELECT max(id) as maxid FROM orders")->fetch();
            $sonid = $max["maxid"] + 1;
            if ($sonid == "" || empty($sonid)){ $sonid = 1; }
            $maxinv = $db->query("SELECT max(invoiceno) as maxid FROM orders")->fetch();
            $invoiceno = $maxinv["maxid"] + 1;
            if ($invoiceno == "" || empty($invoiceno)){ $invoiceno = 19352; }

            $invoicedate = date("Y-m-d");
            $cartdate = date("Y-m-d");
            $orderdate = date("Y-m-d");

            $savecredit = $db->query("insert into orders (id, invoiceno, ordertype, status, companyid, addressid, shiptoaddressid, subtotal, vattotal, entotal, cartdate, orderdate, postcodeid, isnextday, deliverytime, deliverytype, deliverypallet, nextdaydate, deliveryprice, notes, couponcode, discountrate, discountprice, invoicedate, pono, shipviaid, officenotes, websiteid) values('$sonid','$invoiceno','5','0','".$order["companyid"]."','".$order["addressid"]."','".$order["shiptoaddressid"]."','".$order["subtotal"]."','".$order["vattotal"]."','".$order["entotal"]."','$cartdate','$orderdate','".$order["postcodeid"]."','".$order["isnextday"]."','".$order["deliverytime"]."','".$order["deliverytype"]."','".$order["deliverypallet"]."','".$order["nextdaydate"]."','".$order["deliveryprice"]."','".$order["notes"]."','".$order["couponcode"]."','".$order["discountrate"]."','".$order["discountprice"]."','$invoicedate','".$order["pono"]."','".$order["shipviaid"]."','".$order["officenotes"]."','".$order["websiteid"]."')");

            $savebasket = $db->query("insert into basket(orderid, productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount,isdeleted, isnondiscountable, grout, createdate, isordered, websiteid) select '$sonid', productid, sizeid, sessionid, sample, quantity, vatrate, isdelivery, isdiscount, isdeleted, isnondiscountable, grout, createdate, isordered, websiteid from basket where orderid='$orderid'");

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d H:i:s");
            $tablename = "orders";
            $tableid = $sonid;
            $parentid = 0;
            $logtypeid = 42;     // 42 New- 43 Update- 44 Delete
            $userid = $paneluser["id"];
            $log_notes = "invoice to credit note";

            $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");

            if ($savecredit){
                header("Location: ../index.php?p=credit&crd=open&pg=panel-credit-edit&orderid=".$orderid);
            }
            else{
                header("Location: ../index.php?p=credit&crd=open");
            }
        }
        elseif ($operation == "ispaymentreceived"){
            $orderid = $_GET["orderid"];
            $ispaymentreceived = $_GET["ispaymentreceived"];
            $inv = $_GET["inv"];

            $upispayment = $db->query("UPDATE orders set ispaymentreceived='$ispaymentreceived' WHERE id='$orderid'");

            header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);

        }
        elseif ($operation == "requestphoto"){
            $inv = $_GET["inv"];
            $orderid = $_GET["orderid"];
            if ($orderid != ""){
                $customer = $db->query("SELECT company_address.*, orders.*, orders.isdeleted as oisdeleted, companies.* FROM company_address left join orders on company_address.id=orders.addressid left join companies on companies.id=orders.companyid where orders.id='$orderid'")->fetch();
                $strName = $customer["firstname"]."  ".$customer["lastname"];
                $strEmail = $customer["email"];
                $strAddress = $customer["house"]." , ".$customer["street"]." , ".$customer["city"]." , ".$customer["county"]." , ".$customer["postcode"];
                $pht = $db->query("SELECT * FROM photomail where orderid='$orderid'");
                if ($pht->rowCount()){
                    header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
                }
                else{
                    $maildate = date("Y-m-d");
                    $savephtmail = $db->query("insert into photomail (orderid, closed, maildate) values('$orderid','0','$maildate')");

                    $forwhat = "Request-Photo";
                    $strFromEmail = "info@stonedeals.co.uk";

                    /* MAIL */

                    $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                    $processdate = date("Y-m-d H:i:s");
                    $tablename = "photomail";
                    $tableid = $orderid;
                    $parentid = 0;
                    $logtypeid = 43;     // 42 New- 43 Update- 44 Delete
                    $userid = $paneluser["id"];
                    $log_notes = "Photo Email";

                    $savelogs = $db->query("insert into logs(processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$userid','$log_notes')");

                    if ($_GET["list"] == 1){
                        setcookie('testcookie', "true|REQUEST PHOTO EMAIL SENT!...", time() + 20, '/');
                        header("Location: ../index.php?p=invoice&inv=".$inv."&photomails=1&p=archived");
                    }
                    else{
                        if ($savephtmail){
                            header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
                        }
                        else{
                            header("Location: ../index.php?p=invoice&inv=".$inv);
                        }
                    }
                }
            }
            else{
                setcookie('testcookie', "false|NO order ID...", time() + 20, '/');
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
            }
        }
        elseif ($operation == "photostatus"){

            $orderid = $_GET["orderid"];
            $close = $_GET["close"];
            $inv = $_GET["inv"];

            $pht = $db->query("SELECT * FROM photomail where orderid='$orderid'");

            if ($pht->rowCount()){
                $uppht = $db->query("update photomail set closed='$close' where orderid='$orderid'");
            }
            else{
                $savepht = $db->query("insert into photomail(orderid, closed) values('$orderid','$close')");
            }

            if ($_GET["list"] == 1){
                header("Location: ../index.php?p=invoice&inv=".$inv);
            }
            else{
                header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
            }

        }
        elseif ($operation == "markasspecialphoto"){
            $orderid = $_GET["orderid"];
            $specialfollow = $_GET["specialfollow"];
            $inv = $_GET["inv"];

            $pht = $db->query("SELECT * FROM photomail where orderid='$orderid'");

            if ($pht->rowCount()){
                $uppht = $db->query("update photomail set specialfollow='$specialfollow' where orderid='$orderid'");
                if ($uppht){
                    header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
                }
                else{
                    setcookie('testcookie', "false|Mark for photo not follow up...", time() + 20, '/');
                    header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
                }
            }
            else{
                $savepht = $db->query("insert into photomail (orderid, closed, specialfollow) values('$orderid','0','$specialfollow')");
                if ($savepht){
                    header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
                }
                else{
                    setcookie('testcookie', "false|Mark for photo not follow up...", time() + 20, '/');
                    header("Location: ../index.php?p=invoice&inv=".$inv."&pg=panel-invoice-edit&orderid=".$orderid);
                }
            }
        }
    }

    elseif ($pg == "receipt"){
        $operation = $_GET["operation"];
        if ($operation == "enterreceipt"){
            $acts = $db->query("SELECT id FROM accounts order by id desc");
            if ($acts->rowCount()){
                $acc = $db->query("SELECT id FROM accounts order by id desc")->fetch();
                $sonid = $acc["id"] + 1;
            }
            else{
                $sonid = 1;
            }

            if ($_POST["transactiondate"] != ""){
                $transactiondate = strtotime($_POST["transactiondate"]);
                $transactiondate = date("Y-m-d",$transactiondate);
            }
            else{
                $transactiondate = null;
            }

            $companyid = strip_tags(trim($_POST["companyid"]));
            $newpaymentterm = strip_tags(trim($_POST["newpaymentterm"]));
            $reference = strip_tags(trim($_POST["reference"]));
            $note = strip_tags(trim($_POST["note"]));

            if ($_POST["invoiceid"] != ""){ $invoiceid = $_POST["invoiceid"]; }
            if ($_POST["employeeid"] != ""){ $employeeid = $_POST["employeeid"]; }
            $amount = str_replace(",",".",strip_tags(trim($_POST["amount"])));
            if ($amount == ""){ $amount = 0; }

            $transactiontype = strip_tags(trim($_POST["transactiontype"]));
            $cmb = $db->query("select * from combos where id='$transactiontype'")->fetch();
            $transactiontype = $cmb["name"];
            if (strstr($transactiontype,"()")){

            }
            elseif (strstr($transactiontype,"(+)")){
                if ($amount > 0){

                }
                else{
                    $amount = 0 - ($amount);
                }
            }
            elseif (strstr($transactiontype,"(-)")){
                if ($amount > 0){
                    $amount = 0 - ($amount);
                }
                else{

                }
            }

            if ($_POST["bankid"] != ""){ $bankid = $_POST["bankid"]; }else{ $bankid = null; }
            if ($_POST["billid"] != ""){ $billid = $_POST["billid"]; }else{ $billid = null; }
            if ($_POST["invoiceid"] != ""){ $invoiceid = $_POST["invoiceid"]; }else{ $invoiceid = null; }

            /* invoiceid ve billid olan transaction ekleme için */
            //$saveaccount = $db->query("insert into accounts(id, transactiondate, companyid, amount, transactiontype, paymentmethod, reference, bankid, note,invoiceid,billid) values('$sonid','$transactiondate','$companyid','$amount','".$_POST["transactiontype"]."','$newpaymentterm','$reference','$bankid','$note','$invoiceid','$billid')");
            $saveaccount = $db->query("insert into accounts(id, transactiondate, companyid, amount, transactiontype, paymentmethod, reference, bankid, note) values('$sonid','$transactiondate','$companyid','$amount','".$_POST["transactiontype"]."','$newpaymentterm','$reference','$bankid','$note')");

            /* Not Deposite Değilse Banka Hesabına at */
            if ($bankid != 24){
                $banktran = $db->query("SELECT max(id) as maxid FROM banktransactions")->fetch();
                $sonbankid = $banktran["maxid"] + 1;
                if (empty($sonbankid)){ $sonbankid = 1; }

                $transamount = $amount;
                if ($transamount > 0){
                    $transamount = 0 - ($amount);
                }

                $transactiondate = strtotime($_POST["transactiondate"]);
                $transactiondate = date("Y-m-d",$transactiondate);

                $savebanktran = $db->query("insert into banktransactions(id,bankid,transactiondate,amount) values('$sonbankid','$bankid','$transactiondate','$transamount')");

                $upacc = $db->query("update accounts set banktransactionid='$sonbankid' where id='$sonid'");
            }
            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d H:i:s");
            $tablename = "accounts";
            $tableid = $sonid;
            $parentid = 0;
            $logtypeid = 42;
            $iduser = $paneluser["id"];
            $notes = "transaction type :".$_POST["transactiontype"]." - amount : ".$_POST["amount"];
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($saveaccount && $upacc){
                setcookie('testcookie', "true|Receipt Saved...", time() + 20, '/');
                header("Location: ../index.php?p=receipt&rcp=transactions");
            }
            else{
                setcookie('testcookie', "false|Receipt Not Saved...", time() + 20, '/');
                header("Location: ../index.php?p=receipt&rcp=transactions");
            }
        }
        elseif ($operation == "edittransaction"){
            $id = $_GET["id"];
            if (isset($_POST["updatetransaction"])) {
                if ($_POST["banktransactionid"] != "") {
                    $banktransactionid = strip_tags(trim($_POST["banktransactionid"]));
                } else {
                    $banktransactionid = NULL;
                }

                $companyid = strip_tags(trim($_POST["companyid"]));

                if ($_POST["invoiceid"] != "") {
                    $invoiceid = $_POST["invoiceid"];
                } else {
                    $invoiceid = NULL;
                }

                $transactiontype = strip_tags(trim($_POST["transactiontype"]));
                $transactionname = strip_tags(trim($_POST["transactiontype"]));
                $amount = str_replace(",", ".", strip_tags(trim($_POST["amount"])));
                if ($amount == "") {
                    $amount = 0;
                }
                $cmb = $db->query("SELECT * FROM combos where id='$transactiontype'")->fetch();
                $transactiontype = $cmb["name"];

                if (strstr($transactiontype, "()")) {

                } elseif (strstr($transactiontype, "(+)")) {
                    if ($amount > 0) {

                    } else {
                        $amount = 0 - ($amount);
                    }
                } elseif (strstr($transactiontype, "(-)")) {
                    if ($amount > 0) {
                        $amount = 0 - ($amount);
                    } else {

                    }
                }
                $acc = $db->query("SELECT * FROM accounts where id='$id'")->fetch();
                $eskiamount = $acc["amount"];
                if ($eskiamount != $amount) {
                    $paidamount = $amount;
                    if ($paidamount > 0) {

                    } else {
                        $paidamount = 0 - ($paidamount);
                    }
                    if ($_POST["transactiontype"] == 20) {
                        $upaccinv = $db->query("update account_invoice set paidamount='$paidamount' where accountid='$id'");
                    }
                    if ($_POST["transactiontype"] == 18) {
                        $upaccinv = $db->query("update account_invoice set paidamount='$paidamount' where accountid='$id'");
                    }
                }
                if ($_POST["bankid"] != "") {
                    $bankid = $_POST["bankid"];
                } else {
                    $bankid = NULL;
                }
                if ($_POST["billid"] != "") {
                    $billid = $_POST["billid"];
                } else {
                    $billid = NULL;
                }


                if (!is_null($_POST["transactiondate22"]) || !empty($_POST["transactiondate22"])) {
                    $rawdate = htmlentities($_POST['transactiondate22']);
                    $transactiondate = strtotime($rawdate);
                    $transactiondate = date("Y-m-d",$transactiondate);
                    $upacc = $db->prepare("update accounts set transactiondate=:transactiondate, companyid=:companyid, amount=:amount, transactiontype=:transactiontype, paymentmethod=:paymentmethod, reference=:reference, bankid=:bankid, note=:note, invoiceid=:invoiceid, billid=:billid, banktransactionid=:banktransactionid where id=:id");
                    $upacc->bindValue(":transactiondate",$transactiondate,PDO::PARAM_INT);
                    $upacc->bindValue(":companyid",$companyid,PDO::PARAM_INT);
                    $upacc->bindValue(":amount",$amount,PDO::PARAM_INT);
                    $upacc->bindValue(":transactiontype",$transactionname,PDO::PARAM_INT);
                    $upacc->bindValue(":paymentmethod",$_POST["paymentmethod"],PDO::PARAM_INT);
                    $upacc->bindValue(":reference",$_POST["reference"],PDO::PARAM_INT);
                    $upacc->bindValue(":bankid",$bankid,PDO::PARAM_INT);
                    $upacc->bindValue(":note",$_POST["note"],PDO::PARAM_STR);
                    $upacc->bindValue(":invoiceid",$invoiceid,PDO::PARAM_INT);
                    $upacc->bindValue(":billid",$billid,PDO::PARAM_INT);
                    $upacc->bindValue(":banktransactionid",$banktransactionid,PDO::PARAM_INT);
                    $upacc->bindValue(":id",$id,PDO::PARAM_INT);
                    $upacc->execute();
                }
                else {
                    $upacc = $db->prepare("update accounts set transactiondate=:transactiondate, companyid=:companyid, amount=:amount, transactiontype=:transactiontype, paymentmethod=:paymentmethod, reference=:reference, bankid=:bankid, note=:note, invoiceid=:invoiceid, billid=:billid, banktransactionid=:banktransactionid where id=:id");
                    $upacc->bindValue(":transactiondate",NULL,PDO::PARAM_NULL);
                    $upacc->bindValue(":companyid",$companyid,PDO::PARAM_INT);
                    $upacc->bindValue(":amount",$amount,PDO::PARAM_INT);
                    $upacc->bindValue(":transactiontype",$transactionname,PDO::PARAM_INT);
                    $upacc->bindValue(":paymentmethod",$_POST["paymentmethod"],PDO::PARAM_INT);
                    $upacc->bindValue(":reference",$_POST["reference"],PDO::PARAM_INT);
                    $upacc->bindValue(":bankid",$bankid,PDO::PARAM_INT);
                    $upacc->bindValue(":note",$_POST["note"],PDO::PARAM_INT);
                    $upacc->bindValue(":invoiceid",$invoiceid,PDO::PARAM_INT);
                    $upacc->bindValue(":billid",$billid,PDO::PARAM_INT);
                    $upacc->bindValue(":banktransactionid",$banktransactionid,PDO::PARAM_INT);
                    $upacc->bindValue(":id",$id,PDO::PARAM_INT);
                    $upacc->execute();
                }
                //$upacc = $db->query("update accounts set transactiondate='$transactiondate', companyid='$companyid', amount='$amount', transactiontype='$transactionname', paymentmethod='" . $_POST["paymentmethod"] . "', reference='" . $_POST["reference"] . "', bankid='$bankid', note='" . $_POST["note"] . "', invoiceid='$invoiceid', billid='$billid', banktransactionid='$banktransactionid' where id='$id'");

                if ($upacc) {
                    setcookie('testcookie', "true|Transaction Updated...", time() + 20, '/');
                    header("Location: ../index.php?p=receipt&rcp=findreceipt");
                } else {
                    setcookie('testcookie', "false|Transaction Not Updated...", time() + 20, '/');
                    header("Location: ../index.php?p=receipt&rcp=findreceipt&pg=panel-transaction-edit&id=" . $id);
                }
            }
        }
    }

    elseif ($pg == "customer_refund"){
        $operation = $_GET["operation"];
        if ($operation == "enterCheque"){
            $acts = $db->query("SELECT id FROM accounts order by id desc");
            if ($acts->rowCount()){
                $acc = $db->query("SELECT id FROM accounts order by id desc")->fetch();
                $sonid = $acc["id"] + 1;
            }
            else{
                $sonid = 1;
            }

            if ($_POST["transactiondate"] != ""){
                $transactiondate = strtotime($_POST["transactiondate"]);
                $transactiondate = date("Y-m-d",$transactiondate);
            }
            else{
                $transactiondate = NULL;
            }

            $companyid = strip_tags(trim($_POST["companyid"]));
            $newpaymentterm = strip_tags(trim($_POST["paymentmethod"]));
            $reference = strip_tags(trim($_POST["reference"]));
            $note = strip_tags(trim($_POST["note"]));

            if ($_POST["invoiceid"] != ""){ $invoiceid = $_POST["invoiceid"]; }
            if ($_POST["employeeid"] != ""){ $employeeid = $_POST["employeeid"]; }
            $amount = str_replace(",",".",strip_tags(trim($_POST["amount"])));
            if ($amount == ""){ $amount = 0; }

            $transactiontype = strip_tags(trim($_POST["transactiontype"]));
            $cmb = $db->query("select * from combos where id='$transactiontype'")->fetch();
            $transactiontype = $cmb["name"];
            if (strstr($transactiontype,"()")){

            }
            elseif (strstr($transactiontype,"(+)")){
                if ($amount > 0){

                }
                else{
                    $amount = 0 - ($amount);
                }
            }
            elseif (strstr($transactiontype,"(-)")){
                if ($amount > 0){
                    $amount = 0 - ($amount);
                }
                else{

                }
            }

            if ($_POST["bankid"] != ""){ $bankid = $_POST["bankid"]; }else{ $bankid = NULL; }
            if ($_POST["billid"] != ""){ $billid = $_POST["billid"]; }else{ $billid = NULL; }
            if ($_POST["invoiceid"] != ""){ $invoiceid = $_POST["invoiceid"]; }else{ $invoiceid = NULL; }

            /* invoiceid ve billid olan transaction ekleme için */
            //$saveaccount = $db->query("insert into accounts(id, transactiondate, companyid, amount, transactiontype, paymentmethod, reference, bankid, note,invoiceid,billid) values('$sonid','$transactiondate','$companyid','$amount','".$_POST["transactiontype"]."','$newpaymentterm','$reference','$bankid','$note','$invoiceid','$billid')");
            $saveaccount = $db->query("insert into accounts(id, transactiondate, companyid, amount, transactiontype, paymentmethod, reference, bankid, note) values('$sonid','$transactiondate','$companyid','$amount','".$_POST["transactiontype"]."','$newpaymentterm','$reference','$bankid','$note')");

            /* Not Deposite Değilse Banka Hesabına at */
            if ($bankid != 24){
                $banktran = $db->query("SELECT max(id) as maxid FROM banktransactions")->fetch();
                $sonbankid = $banktran["maxid"] + 1;
                if (empty($sonbankid)){ $sonbankid = 1; }

                $transamount = $amount;
                if ($transamount > 0){
                    $transamount = 0 - ($amount);
                }

                $transactiondate = strtotime($_POST["transactiondate"]);
                $transactiondate = date("Y-m-d",$transactiondate);

                $savebanktran = $db->query("insert into banktransactions(id,bankid,transactiondate,amount) values('$sonbankid','$bankid','$transactiondate','$transamount')");

                $upacc = $db->query("update accounts set banktransactionid='$sonbankid' where id='$sonid'");
            }

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d H:i:s");
            $tablename = "accounts";
            $tableid = $sonid;
            $parentid = 0;
            $logtypeid = 42;
            $iduser = $paneluser["id"];
            $notes = "transaction type :".$_POST["transactiontype"]." - amount : ".$_POST["amount"];
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($saveaccount && $upacc){
                setcookie('testcookie', "true|Customer Refund Saved...", time() + 20, '/');
                header("Location: ../index.php?p=customer_refund&rfd=findcustrefund");
            }
            else{
                setcookie('testcookie', "false|Customer Refund Not Saved...", time() + 20, '/');
                header("Location: ../index.php?p=customer_refund&rfd=findcustrefund");
            }
        }
        elseif ($operation == "edittransaction"){
            $id = $_GET["id"];
            if (isset($_POST["updatetransaction"])) {
                if ($_POST["banktransactionid"] != "") {
                    $banktransactionid = strip_tags(trim($_POST["banktransactionid"]));
                } else {
                    $banktransactionid = NULL;
                }

                $companyid = strip_tags(trim($_POST["companyid"]));

                if ($_POST["invoiceid"] != "") {
                    $invoiceid = $_POST["invoiceid"];
                } else {
                    $invoiceid = NULL;
                }

                $transactiontype = strip_tags(trim($_POST["transactiontype"]));
                $transactionname = strip_tags(trim($_POST["transactiontype"]));
                $amount = str_replace(",", ".", strip_tags(trim($_POST["amount"])));
                if ($amount == "") {
                    $amount = 0;
                }
                $cmb = $db->query("SELECT * FROM combos where id='$transactiontype'")->fetch();
                $transactiontype = $cmb["name"];

                if (strstr($transactiontype, "()")) {

                } elseif (strstr($transactiontype, "(+)")) {
                    if ($amount > 0) {

                    } else {
                        $amount = 0 - ($amount);
                    }
                } elseif (strstr($transactiontype, "(-)")) {
                    if ($amount > 0) {
                        $amount = 0 - ($amount);
                    } else {

                    }
                }
                $acc = $db->query("SELECT * FROM accounts where id='$id'")->fetch();
                $eskiamount = $acc["amount"];
                if ($eskiamount != $amount) {
                    $paidamount = $amount;
                    if ($paidamount > 0) {

                    } else {
                        $paidamount = 0 - ($paidamount);
                    }
                    if ($_POST["transactiontype"] == 20) {
                        $upaccinv = $db->query("update account_invoice set paidamount='$paidamount' where accountid='$id'");
                    }
                    if ($_POST["transactiontype"] == 18) {
                        $upaccinv = $db->query("update account_invoice set paidamount='$paidamount' where accountid='$id'");
                    }
                }
                if ($_POST["bankid"] != "") {
                    $bankid = $_POST["bankid"];
                } else {
                    $bankid = NULL;
                }
                if ($_POST["billid"] != "") {
                    $billid = $_POST["billid"];
                } else {
                    $billid = NULL;
                }


                if (!is_null($_POST["transactiondate22"]) || !empty($_POST["transactiondate22"])) {
                    $rawdate = htmlentities($_POST['transactiondate22']);
                    $transactiondate = strtotime($rawdate);
                    $transactiondate = date("Y-m-d",$transactiondate);
                    $upacc = $db->prepare("update accounts set transactiondate=:transactiondate, companyid=:companyid, amount=:amount, transactiontype=:transactiontype, paymentmethod=:paymentmethod, reference=:reference, bankid=:bankid, note=:note, invoiceid=:invoiceid, billid=:billid, banktransactionid=:banktransactionid where id=:id");
                    $upacc->bindValue(":transactiondate",$transactiondate,PDO::PARAM_INT);
                    $upacc->bindValue(":companyid",$companyid,PDO::PARAM_INT);
                    $upacc->bindValue(":amount",$amount,PDO::PARAM_INT);
                    $upacc->bindValue(":transactiontype",$transactionname,PDO::PARAM_INT);
                    $upacc->bindValue(":paymentmethod",$_POST["paymentmethod"],PDO::PARAM_INT);
                    $upacc->bindValue(":reference",$_POST["reference"],PDO::PARAM_INT);
                    $upacc->bindValue(":bankid",$bankid,PDO::PARAM_INT);
                    $upacc->bindValue(":note",$_POST["note"],PDO::PARAM_STR);
                    $upacc->bindValue(":invoiceid",$invoiceid,PDO::PARAM_INT);
                    $upacc->bindValue(":billid",$billid,PDO::PARAM_INT);
                    $upacc->bindValue(":banktransactionid",$banktransactionid,PDO::PARAM_INT);
                    $upacc->bindValue(":id",$id,PDO::PARAM_INT);
                    $upacc->execute();
                }
                else {
                    $upacc = $db->prepare("update accounts set transactiondate=:transactiondate, companyid=:companyid, amount=:amount, transactiontype=:transactiontype, paymentmethod=:paymentmethod, reference=:reference, bankid=:bankid, note=:note, invoiceid=:invoiceid, billid=:billid, banktransactionid=:banktransactionid where id=:id");
                    $upacc->bindValue(":transactiondate",NULL,PDO::PARAM_NULL);
                    $upacc->bindValue(":companyid",$companyid,PDO::PARAM_INT);
                    $upacc->bindValue(":amount",$amount,PDO::PARAM_INT);
                    $upacc->bindValue(":transactiontype",$transactionname,PDO::PARAM_INT);
                    $upacc->bindValue(":paymentmethod",$_POST["paymentmethod"],PDO::PARAM_INT);
                    $upacc->bindValue(":reference",$_POST["reference"],PDO::PARAM_INT);
                    $upacc->bindValue(":bankid",$bankid,PDO::PARAM_INT);
                    $upacc->bindValue(":note",$_POST["note"],PDO::PARAM_INT);
                    $upacc->bindValue(":invoiceid",$invoiceid,PDO::PARAM_INT);
                    $upacc->bindValue(":billid",$billid,PDO::PARAM_INT);
                    $upacc->bindValue(":banktransactionid",$banktransactionid,PDO::PARAM_INT);
                    $upacc->bindValue(":id",$id,PDO::PARAM_INT);
                    $upacc->execute();
                }
                //$upacc = $db->query("update accounts set transactiondate='$transactiondate', companyid='$companyid', amount='$amount', transactiontype='$transactionname', paymentmethod='" . $_POST["paymentmethod"] . "', reference='" . $_POST["reference"] . "', bankid='$bankid', note='" . $_POST["note"] . "', invoiceid='$invoiceid', billid='$billid', banktransactionid='$banktransactionid' where id='$id'");

                if ($upacc) {
                    setcookie('testcookie', "true|Transaction Updated...", time() + 20, '/');
                    header("Location: ../index.php?p=customer_refund&rfd=findcustrefund");
                } else {
                    setcookie('testcookie', "false|Transaction Not Updated...", time() + 20, '/');
                    header("Location: ../index.php?p=customer_refund&rfd=findcustrefund&pg=panel-transaction-edit&id=" . $id);
                }
            }
        }
    }

    elseif ($pg == "purchase"){
        $operation = $_GET["operation"];
        if ($operation == "purchasenewok"){
            if (isset($_POST["savepurchase"])){
                $supplierid = strip_tags(trim($_POST["supplierid"]));
                $addressid = strip_tags(trim($_POST["addressid"]));
                $notes = strip_tags(trim($_POST["notes"]));
                $max = $db->query("SELECT max(id) as maxid FROM purchases")->fetch();
                $sonid = $max["maxid"] + 1;
                if (empty($sonid)){ $sonid = 1; }
                $maxno = $db->query("SELECT max(purchaseno) as maxid FROM purchases where isdeleted<>1")->fetch();
                $invoiceno = $maxno["maxid"] + 1;
                if (empty($invoiceno)){ $invoiceno = 1000; }
                $invoicedate = date("Y-m-d");

                $savepurchase = $db->query("insert into purchases(id, purchaseno, companyid, status, invoicedate, notes, addressid) values('$sonid','$invoiceno','$supplierid','0','$invoicedate','$notes','$addressid')");

                $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                $processdate = date("Y-m-d");
                $tablename = "purchases";
                $tableid = $sonid;
                $parentid = 0;
                $logtypeid = 42;
                $iduser = $paneluser["id"];
                $notes = "";
                $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

                if ($savepurchase){
                    setcookie('testcookie', "true|Purchase Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=purchase&prc=findpurchase");
                }
                else{
                    setcookie('testcookie', "false|Purchase Not Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=purchase&prc=newpurchase");
                }
            }
        }
        elseif ($operation == "purchaseeditok"){
            $orderid = $_GET["orderid"];
            $type = $_GET["type"];
            if (isset($_POST["updatepurchase"])){
                if (isset($_POST["salesinvoiceno"])){ $salesinvoiceno = strip_tags(trim($_POST["salesinvoiceno"])); }else{ $salesinvoiceno = ""; }
                $pono = strip_tags(trim($_POST["pono"]));
                $purchaseno = strip_tags(trim($_POST["purchaseno"]));
                $invoicedate = strip_tags(trim($_POST["invoicedate"]));
                $deliverydate = strip_tags(trim($_POST["deliverydate"]));
                $notes = strip_tags(trim($_POST["notes"]));
                $invoicedate = strtotime($invoicedate);
                $invoicedate = date("Y-m-d",$invoicedate);
                $deliverydate = strtotime($deliverydate);
                $deliverydate = date("Y-m-d",$deliverydate);

                $uppurc = $db->query("update purchases set purchaseno='$purchaseno', deliverydate='$deliverydate', invoicedate='$invoicedate', pono='$pono', notes='$notes', salesinvoiceno='$salesinvoiceno' where id='$orderid'");
                if ($uppurc){
                    if ($type == "purchase"){
                        setcookie('testcookie', "true|Purchase Data Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
                    }
                    elseif ($type == "goods"){
                        setcookie('testcookie', "true|Goods Received Data Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=goods&gd=findgoods&pg=panel-goods-edit&orderid=".$orderid."&type=".$type);
                    }
                }
                else{
                    if ($type == "purchase"){
                        setcookie('testcookie', "false|Purchase Data Not Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
                    }
                    elseif ($type == "goods"){
                        setcookie('testcookie', "false|Goods Received Data Not Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=goods&gd=findgoods&pg=panel-goods-edit&orderid=".$orderid."&type=".$type);
                    }
                }
            }
            else{
                if (isset($_POST["companyid"])){
                    $companyid = strip_tags(trim($_POST["companyid"]));
                    $uppurc = $db->query("update purchases set companyid='$companyid' where id='$orderid'");
                    if ($uppurc){
                        if ($type == "purchase"){
                            setcookie('testcookie', "true|Purchase Company Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
                        }
                        elseif ($type == "goods"){
                            setcookie('testcookie', "true|Goods Received Company Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=goods&gd=findgoods&pg=panel-goods-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                    else{
                        if ($type == "purchase"){
                            setcookie('testcookie', "false|Purchase Company Not Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
                        }
                        elseif ($type == "goods"){
                            setcookie('testcookie', "false|Goods Received Company Not Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=goods&gd=findgoods&pg=panel-goods-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                }
                if (isset($_POST["addressid"])){
                    $addressid = strip_tags(trim($_POST["addressid"]));
                    $uppurc = $db->query("update purchases set addressid='$addressid' where id='$orderid'");
                    if ($uppurc){
                        if ($type == "purchase"){
                            setcookie('testcookie', "true|Purchase Company Address Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
                        }
                        elseif ($type == "goods"){
                            setcookie('testcookie', "true|Goods Received Company Address Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=goods&gd=findgoods&pg=panel-goods-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                    else{
                        if ($type == "purchase"){
                            setcookie('testcookie', "false|Purchase Company Address Not Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
                        }
                        elseif ($type == "goods"){
                            setcookie('testcookie', "false|Goods Received Company Address Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=goods&gd=findgoods&pg=panel-goods-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                }
                if (isset($_POST["shipviaid"])){
                    $shipviaid = strip_tags(trim($_POST["shipviaid"]));
                    $uppurc = $db->query("update purchases set shipviaid='$shipviaid' where id='$orderid'");
                    if ($uppurc){
                        if ($type == "purchase"){
                            setcookie('testcookie', "true|Purchase Shipvia Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
                        }
                        elseif ($type == "goods"){
                            setcookie('testcookie', "true|Goods Received Shipvia Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=goods&gd=findgoods&pg=panel-goods-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                    else{
                        if ($type == "purchase"){
                            setcookie('testcookie', "false|Purchase Shipvia Not Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
                        }
                        elseif ($type == "goods"){
                            setcookie('testcookie', "false|Goods Received Shipvia Not Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=goods&gd=findgoods&pg=panel-goods-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                }
            }
        }
        elseif ($operation == "additemtopurchase"){
            $type = $_GET["type"];
            $orderid = $_GET["orderid"];
            $prname = strtoupper(strip_tags(trim($_POST["productname"])));
            $qty = 0;
            $qty = strip_tags(trim($_POST["qty"]));
            $qty = round($qty,3);
            $unit = strip_tags(trim($_POST["unit"]));
            $expenseid = strip_tags(trim($_POST["expenseid"]));
            if ($_POST["product_sizeid"] != ""){ $product_sizeid = strip_tags(trim($_POST["product_sizeid"])); }else{ $product_sizeid = ""; }
            if ($_POST["salesinvoiceno"] != ""){ $salesinvoiceno = strip_tags(trim($_POST["salesinvoiceno"])); }else{ $salesinvoiceno = ""; }
            $price = 0;
            $price = strip_tags(trim($_POST["price"]));
            if (($_GET["type"] == "debit") && $price > 0){ $price = -$price; }
            $subprice = $price * $qty;
            $vatid = strip_tags(trim($_POST["vatid"]));
            $vatrate = "";
            $cmb = $db->query("SELECT * FROM combos where menu='vat' and id='$vatid'")->fetch();
            $vatrate = $cmb["amount"];
            $isdeleted = 0;

            $savepurcart = $db->query("insert into purchase_cart(purchaseid, product_sizeid, productname, qty, unit, expenseid, price, isdeleted, vatid, vatrate, salesinvoiceno) values('$orderid','$product_sizeid','$prname','$qty','$unit','$expenseid','$price','$isdeleted','$vatid','$vatrate','$salesinvoiceno')");

            $prcart = $db->query("SELECT * FROM purchase_cart where purchaseid='$orderid'");
            if ($prcart->rowCount()){
                foreach($prcart as $crt){
                    $qty = 0;
                    $qty = $crt["qty"];
                    $qty = round($qty,3);
                    $price = 0;
                    $price = $crt["price"];
                    if (($_GET["type"] == "debit") && $price > 0){ $price = -$price; }
                    $subprice = 0;
                    $subprice = $price * $qty;
                    $vatrate = "";
                    $vatrate = $crt["vatrate"];
                    $vatprice = 0;
                    $vatprice = $subprice * $vatrate / (100 + $vatrate);
                    $vattotal = $vattotal + $vatprice;
                    $entotal = $entotal + $subprice;
                }
            }
            if ($vattotal != ""){
                $vattotal = number_format($vattotal,2);
            }
            else{
                $vattotal = 0;
            }
            if ($entotal != ""){
                $entotal = number_format($entotal,2);
            }
            else{
                $entotal = 0;
            }

            $subtotal = $entotal - $vattotal;
            $uppurchase = $db->query("update purchases set subtotal='$subtotal', vattotal='$vattotal', entotal='$entotal' where id='$orderid'");

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "purchases";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 43;
            $iduser = $paneluser["id"];
            $notes = "add item total amount : ".$subtotal;
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($savepurcart && $uppurchase){
                setcookie('testcookie', "true|Purchase Order Saved...", time() + 20, '/');
                header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
            }
            else{
                setcookie('testcookie', "false|Purchase Order Not Saved...", time() + 20, '/');
                header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
            }
        }
        elseif ($operation == "purchasestatus"){
            $orderid = $_GET["orderid"];
            $status = $_GET["status"];
            $purchase = $db->query("SELECT * FROM purchases where id='$orderid'")->fetch();
            //if(isset($_POST["pono"])){ $pono = strip_tags(trim($_POST["pono"])); }else{ $pono = ""; }
            //if (isset($_POST["shipvia"])){ $shipvia = strip_tags(trim($_POST["shipvia"])); }else{ $shipvia = ""; }
            if ($status == "1" && ($purchase["deliverydate"] == "" || empty($purchase["deliverydate"]))){
                $now = date("Y-m-d");
                $uppurc = $db->query("update purchases set status='$status', deliverydate='$now' where id='$orderid'");
            }
            else{
                $uppurc = $db->query("update purchases set status='$status' where id='$orderid'");
            }

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "purchases";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 43;
            $iduser = $paneluser["id"];
            $notes = "Purchases status change : ".$status;
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($uppurc){
                header("Location: ../index.php?p=goods&gd=findgoods&pg=panel-goods-edit&orderid=".$orderid);
            }
            else{
                header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=purchase");
            }

        }
        elseif ($operation == "purchasedeleteall"){
            $orderid = $_GET["orderid"];
            $userid = $_GET["userid"];
            $type = $_GET["type"];

            $updel = $db->query("UPDATE purchase_cart set isdeleted=1 WHERE purchaseid='$orderid'");
            $updelpurc = $db->query("UPDATE purchases set isdeleted=1 WHERE id='$orderid'");

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "purchases";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 43;
            $iduser = $paneluser["id"];
            $notes = "Purchases status change : ".$status;
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($updel && $updelpurc){
                setcookie('testcookie', "true|Purchase Order Deleted...", time() + 20, '/');
                header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
            }
            else{
                setcookie('testcookie', "false|Purchase Order Not Deleted...", time() + 20, '/');
                header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
            }
        }
        elseif ($operation == "purchasedeleteallreal"){
            $orderid = $_GET["orderid"];
            $userid = $_GET["userid"];
            $type = $_GET["type"];

            $delrealcart = $db->query("delete from purchase_cart WHERE purchaseid='$orderid'");
            $delrealpurc = $db->query("delete from purchases WHERE id='$orderid'");

            if ($delrealcart && $delrealpurc){
                setcookie('testcookie', "true|Purchase Order All Data Deleted...", time() + 20, '/');
                header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
            }
            else{
                setcookie('testcookie', "false|Purchase Order All Data Not Deleted...", time() + 20, '/');
                header("Location: ../index.php?p=purchase&prc=findpurchase&pg=panel-purchasing-edit&orderid=".$orderid."&type=".$type);
            }
        }
        elseif ($oepration == "purchasetobill"){
            $orderid = $_GET["orderid"];
            $max = $db->query("SELECT max(id) as maxid FROM bills")->fetch();
            $sonid = $max["maxid"] + 1;
            if (empty($sonid)){ $sonid = 1; }

            $maxno = $db->query("SELECT max(invoiceno) as maxid FROM bills where isdeleted<>1")->fetch();
            $invoiceno = $maxno["maxid"] + 1;
            if (empty($invoiceno)){ $invoiceno = 1000; }

            $pur = $db->query("SELECT * FROM purchases where id='$orderid'")->fetch();

            $now = date("Y-m-d");
            if ($pur["salesinvoiceno"] != ""){ $salesinvoiceno = $pur["salesinvoiceno"]; }

            $savebill = $db->query("insert into bills(id, invoiceno, salesinvoiceno, companyid, billtype, status, invoicedate, notes, addressid) values('$sonid','$invoiceno','$salesinvoiceno','".$pur["companyid"]."','0','0','$now','".$pur["notes"]."','".$pur["addressid"]."')");

            $savebillcart = $db->query("insert into bill_cart (billid, product_sizeid, productname, qty, unit, expenseid, price, isdeleted, vatid, vatrate, salesinvoiceno) select '$sonid', product_sizeid, productname, qty, unit, expenseid, price, isdeleted, vatid, vatrate, salesinvoiceno from purchase_cart WHERE purchaseid='$orderid'");

            $bill_cart = $db->query("SELECT * FROM bill_cart where billid='$sonid'");
            foreach($bill_cart as $cart){
                $qty = 0;
                $qty = $cart["qty"];
                $qty = round($qty,3);
                $price = 0;
                $price = $cart["price"];
                $subprice = 0;
                $subprice = $price * $qty;
                $vatrate = "";
                $cmb = $db->query("SELECT * FROM combos where menu='vat' and id='".$cart["vatid"]."'")->fetch();
                $vatrate = $cmb["amount"];
                $vatprice = 0;
                $vatprice = $subprice * $vatrate / (100 + $vatrate);
                $vattotal = $vattotal + $vatprice;
                $entotal = $entotal + $subprice;
            }

            if($vattotal != ""){
                $vattotal = number_format($vattotal,2);
            }
            else{
                $vattotal = 0;
            }

            if ($entotal != ""){
                $entotal = number_format($entotal,2);
            }
            else{
                $entotal = 0;
            }

            $subtotal = $entotal - $vattotal;
            if ($subtotal != ""){
                $subtotal = number_format($subtotal,2);
            }
            else{
                $subtotal = 0;
            }

            $upbills = $db->query("update bills set subtotal='$subtotal', vattotal='$vattotal', entotal='$entotal' where id='$sonid'");

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "bills";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 43;
            $iduser = $paneluser["id"];
            $notes = "purchasetobill";
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

        }
        elseif ($operation == "qtyupdate"){
            $qtyval = strip_tags(trim($_POST["qtyval"]));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update purchase_cart set qty='$qtyval' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "sizeupdate"){
            $size = strip_tags(trim($_POST["size"]));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update purchase_cart set product_sizeid='$size' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "unitupdate"){
            $unit = strip_tags(trim($_POST["unit"]));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update purchase_cart set unit='$unit' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "nameupdate"){
            $name = strtoupper(strip_tags(trim($_POST["name"])));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update purchase_cart set productname='$name' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "salesinvoiceupdate"){
            $sales = strtoupper(strip_tags(trim($_POST["sales"])));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update purchase_cart set salesinvoiceno='$sales' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "expenseupdate"){
            $expenseid = strtoupper(strip_tags(trim($_POST["expense"])));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update purchase_cart set expenseid='$expenseid' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "vatupdate"){
            $vatid = strtoupper(strip_tags(trim($_POST["vatid"])));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update purchase_cart set vatid='$vatid' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "priceupdate"){
            $price = strip_tags(trim($_POST["ss"]));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update purchase_cart set price='$price' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "vatpriceupdate"){
            $price = strip_tags(trim($_POST["ks"]));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update purchase_cart set price='$price' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
    }

    elseif ($pg == "goods"){
        $operation = $_GET["operation"];
        if ($operation == "addgoodsorder"){
            $productsizeid = strip_tags(trim($_POST["productsizeid"]));
            $serviceproductname = strtoupper(strip_tags(trim($_POST["serviceproductname"])));
            if ($_POST["salesinvoiceno"] != ""){ $salesinvoiceno = strip_tags(trim($_POST["salesinvoiceno"])); }else{ $salesinvoiceno = ""; }

            //$vatsiznew = strip_tags(trim($_POST["vatsiznew"]));

            $unit = strip_tags(trim($_POST["unit"]));

            $qty = 0;
            $qty = strip_tags(trim($_POST["qty"]));
            $qty = round($qty,3);

            $price = 0;
            $price = strip_tags(trim($_POST["vatlinew"]));
            if (($_GET["type"] == "debit") && $price > 0){ $price = -$price; }
            $subprice = $price * $qty;

            $expenseid = strip_tags(trim($_POST["expenseid"]));
            $vatid = strip_tags(trim($_POST["vatid"]));
            $vatrate = "";
            $cmb = $db->query("SELECT * FROM combos where menu='vat' and id='$vatid'")->fetch();
            $vatrate = $cmb["amount"];
            $isdeleted = 0;

            $max = $db->query("select max(id) as maxid from purchases where isdeleted<>1")->fetch();
            $orderid = $max["maxid"] + 1;

            $select = $db->query("select * from purchase_cart where purchaseid='$orderid'");
            $savepurcart = $db->query("insert into purchase_cart(purchaseid, product_sizeid, productname, qty, unit, expenseid, price, isdeleted, vatid, vatrate, salesinvoiceno) values('$orderid','$productsizeid','$serviceproductname','$qty','$unit','$expenseid','$price','$isdeleted','$vatid','$vatrate','$salesinvoiceno')");

            echo $orderid;

        }
        elseif ($operation == "remove"){
            $productsizeid = strip_tags(trim($_POST["c"]));
            $qty = strip_tags(trim($_POST["e"]));

            $select = $db->query("select * from purchase_cart where product_sizeid='$productsizeid' and qty='$qty'");
            if ($select->rowCount()){
                $delete = $db->query("delete from purchase_cart where product_sizeid='$productsizeid' and qty='$qty'");
            }
        }
        elseif ($operation == "goodsnewok"){
            $supplierid = strip_tags(trim($_POST["supplierid"]));
            $addressid = strip_tags(trim($_POST["addressid"]));
            $notes = strip_tags(trim($_POST["notes"]));
            $goodsid = strip_tags(trim($_POST["goodsid"]));

            if (empty($goodsid)){ $goodsid = 1; }
            $maxno = $db->query("SELECT max(purchaseno) as maxid FROM purchases where isdeleted<>1")->fetch();
            $invoiceno = $maxno["maxid"] + 1;
            if (empty($invoiceno)){ $invoiceno = 1000; }
            $invoicedate = date("Y-m-d");

            $savepurchase = $db->query("insert into purchases(id, purchaseno, companyid, status, invoicedate, notes, addressid) values('$goodsid','$invoiceno','$supplierid','1','$invoicedate','$notes','$addressid')");


            $prcart = $db->query("SELECT * FROM purchase_cart where purchaseid='$goodsid'");
            if ($prcart->rowCount()){
                foreach($prcart as $crt){
                    $qty = 0;
                    $qty = $crt["qty"];
                    $qty = round($qty,3);
                    $price = 0;
                    $price = $crt["price"];
                    if (($_GET["type"] == "debit") && $price > 0){ $price = -$price; }
                    $subprice = 0;
                    $subprice = $price * $qty;
                    $vatrate = "";
                    $vatrate = $crt["vatrate"];
                    $vatprice = 0;
                    $vatprice = $subprice * $vatrate / (100 + $vatrate);
                    $vattotal = $vattotal + $vatprice;
                    $entotal = $entotal + $subprice;
                }
            }
            if ($vattotal != ""){
                $vattotal = number_format($vattotal,2);
            }
            else{
                $vattotal = 0;
            }
            if ($entotal != ""){
                $entotal = number_format($entotal,2);
            }
            else{
                $entotal = 0;
            }

            $subtotal = $entotal - $vattotal;
            $uppurchase = $db->query("update purchases set subtotal='$subtotal', vattotal='$vattotal', entotal='$entotal' where id='$goodsid'");

            if ($savepurchase && $uppurchase){
                setcookie('testcookie', "true|New Goods Saved...", time() + 20, '/');
                header("Location: ../index.php?p=goods&gd=findgoods");
            }
            else{
                setcookie('testcookie', "false|New Goods Not Saved...", time() + 20, '/');
                header("Location: ../index.php?p=goods&gd=newgoods");
            }
        }
    }

    elseif ($pg == "bill"){
        $operation = $_GET["operation"];
        if ($operation == "applypaymenttobill"){
            $accountid = $_GET["accountid"];
            $companyid = $_GET["companyid"];
            $orderid = $_GET["orderid"];
            $tobeallocated = $_GET["tobeallocated"];
            $transactiontype = $_GET["transactiontype"];

            $saveaccinv = $db->query("insert into account_invoice(accountid, paidamount, transactiontype, billid) values('$accountid','$tobeallocated','$transactiontype','$orderid')");

            $totalpaidamount = 0;
            $billaccinv = $db->query("SELECT * FROM account_invoice where billid='$orderid'");
            foreach($billaccinv as $billinv){
                $paidamount = $billinv["paidamount"];
                $totalpaidamount = $totalpaidamount + $paidamount;
            }

            $bills = $db->query("SELECT entotal, ispaymentreceived FROM bills where id='$orderid'")->fetch();
            $entotal = $bills["entotal"];
            if (number_format($totalpaidamount,2) == number_format($entotal,2)){
                $upbills = $db->query("update bills set ispaymentreceived=1 where id='$orderid'");
            }

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "account_invoice";
            $tableid = $accountid;
            $parentid = 0;
            $logtypeid = 42;
            $iduser = $paneluser["id"];
            $notes = "apply payment in bill - amount : ".$paidamount;
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($saveaccinv){
                setcookie('testcookie', "true|Apply to Bill...", time() + 20, '/');
                header("Location: ../index.php?p=bill&bl=archived&pg=panel-bill-edit&orderid=".$orderid."&type=bill");
            }
            else{
                setcookie('testcookie', "false|Not Apply to Bill...", time() + 20, '/');
                header("Location: ../index.php?p=bill&bl=archived&pg=panel-bill-edit&orderid=".$orderid."&type=bill");
            }
        }
        elseif ($operation == "billnewok"){
            $type = $_GET["type"];
            if(isset($_POST["savebill"])){
                $supplierid = strip_tags(trim($_POST["supplierid"]));
                $addressid = strip_tags(trim($_POST["addressid"]));
                $notes = strip_tags(trim($_POST["notes"]));
                $max = $db->query("SELECT max(id) as maxid FROM bills")->fetch();
                $sonid = $max["maxid"] + 1;
                if (empty($sonid)){ $sonid = 1; }
                $maxno = $db->query("SELECT max(invoiceno) as maxid FROM bills where isdeleted<>1")->fetch();
                $invoiceno = $maxno["maxid"] + 1;
                if (empty($invoiceno)){ $invoiceno = 1000; }

                if ($type == "debit"){
                    $billtype = 1;
                    $header = "Debit Note";
                }
                else{
                    $billtype = 0;
                    $header = "Bill";
                }

                $status = 0;
                $invoicedate = date("Y-m-d");

                $savebills = $db->query("insert into bills(id, invoiceno, companyid, billtype, status, invoicedate, notes, addressid) values('$sonid','$invoiceno','$supplierid','$billtype','$status','$invoicedate','$notes','$addressid')");

                $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                $processdate = date("Y-m-d");
                $tablename = "bills";
                $tableid = $sonid;
                $parentid = 0;
                $logtypeid = 42;
                $iduser = $paneluser["id"];
                $notes = "";
                $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

                if ($savebills){
                    setcookie('testcookie', "true|".$header." Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=bill&bl=open&type=".$type);
                }
                else{
                    setcookie('testcookie', "false|".$header." Not Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=bill&bl=newbill&type=".$type);
                }
            }
        }
        elseif ($operation == "billeditok"){
            $orderid = $_GET["orderid"];
            $type = $_GET["type"];
            $bl = $_GET["bl"];
            if (isset($_POST["updatebill"])){
                if (isset($_POST["salesinvoiceno"])){ $salesinvoiceno = strip_tags(trim($_POST["salesinvoiceno"])); }else{ $salesinvoiceno = ""; }
                $pono = strip_tags(trim($_POST["pono"]));
                $invoiceno = strip_tags(trim($_POST["invoiceno"]));
                $invoicedate = strip_tags(trim($_POST["invoicedate"]));
                $deliverydate = strip_tags(trim($_POST["deliverydate"]));
                $notes = strip_tags(trim($_POST["notes"]));
                $invoicedate = strtotime($invoicedate);
                $invoicedate = date("Y-m-d",$invoicedate);
                $deliverydate = strtotime($deliverydate);
                $deliverydate = date("Y-m-d",$deliverydate);

                $uppurc = $db->query("update bills set invoiceno='$invoiceno', salesinvoiceno='$salesinvoiceno', deliverydate='$deliverydate', invoicedate='$invoicedate', pono='$pono', notes='$notes' where id='$orderid'");
                if ($uppurc){
                    if ($type == "debit"){
                        setcookie('testcookie', "true|Debit Note Data Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                    }
                    else{
                        setcookie('testcookie', "true|Bill Data Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                    }
                }
                else{
                    if ($type == "debit"){
                        setcookie('testcookie', "false|Debit Note Data Not Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                    }
                    else{
                        setcookie('testcookie', "false|Bill Data Not Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                    }
                }
            }
            else{
                if (isset($_POST["companyid"])){
                    $companyid = strip_tags(trim($_POST["companyid"]));
                    $uppurc = $db->query("update bills set companyid='$companyid' where id='$orderid'");
                    if ($uppurc){
                        if ($type == "debit"){
                            setcookie('testcookie', "true|Debit Note Company Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                        else{
                            setcookie('testcookie', "true|Bill Company Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                    else{
                        if ($type == "debit"){
                            setcookie('testcookie', "false|Debit Note Not Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                        else{
                            setcookie('testcookie', "false|Bill Company Not Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                }
                if (isset($_POST["addressid"])){
                    $addressid = strip_tags(trim($_POST["addressid"]));
                    $uppurc = $db->query("update bills set addressid='$addressid' where id='$orderid'");
                    if ($uppurc){
                        if ($type == "debit"){
                            setcookie('testcookie', "true|Debit Note Company Address Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                        else{
                            setcookie('testcookie', "true|Bill Company Address Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                    else{
                        if ($type == "debit"){
                            setcookie('testcookie', "false|Debit Note Company Address Not Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                        else{
                            setcookie('testcookie', "false|Bill Company Address Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                }
                if (isset($_POST["shipviaid"])){
                    $shipviaid = strip_tags(trim($_POST["shipviaid"]));
                    $uppurc = $db->query("update bills set shipviaid='$shipviaid' where id='$orderid'");
                    if ($uppurc){
                        if ($type == "debit"){
                            setcookie('testcookie', "true|Debit Note Shipvia Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                        else{
                            setcookie('testcookie', "true|Bill Shipvia Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                    else{
                        if ($type == "debit"){
                            setcookie('testcookie', "false|Debit Note Shipvia Not Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                        else{
                            setcookie('testcookie', "false|Bill Shipvia Not Updated...", time() + 20, '/');
                            header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                        }
                    }
                }
            }
        }
        elseif ($operation == "additemtobill"){
            $type = $_GET["type"];
            $bl = $_GET["bl"];
            $orderid = $_GET["orderid"];
            $prname = strtoupper(strip_tags(trim($_POST["productname"])));
            $qty = 0;
            $qty = strip_tags(trim($_POST["qty"]));
            $qty = round($qty,3);
            $unit = strip_tags(trim($_POST["unit"]));
            $expenseid = strip_tags(trim($_POST["expenseid"]));
            if ($_POST["product_sizeid"] != ""){ $product_sizeid = strip_tags(trim($_POST["product_sizeid"])); }else{ $product_sizeid = ""; }
            if ($_POST["salesinvoiceno"] != ""){ $salesinvoiceno = strip_tags(trim($_POST["salesinvoiceno"])); }else{ $salesinvoiceno = ""; }
            $price = 0;
            $price = strip_tags(trim($_POST["price"]));
            if (($_GET["type"] == "debit") && $price > 0){ $price = -$price; }
            $subprice = $price * $qty;
            $vatid = strip_tags(trim($_POST["vatid"]));
            $vatrate = "";
            $cmb = $db->query("SELECT * FROM combos where menu='vat' and id='$vatid'")->fetch();
            $vatrate = $cmb["amount"];
            $isdeleted = 0;

            $savebillcart = $db->query("insert into bill_cart(billid, product_sizeid, productname, qty, unit, expenseid, price, isdeleted, vatid, salesinvoiceno, vatrate) values('$orderid','$product_sizeid','$prname','$qty','$unit','$expenseid','$price','$isdeleted','$vatid','$salesinvoiceno','$vatrate')");

            $prcart = $db->query("SELECT * FROM bill_cart where billid='$orderid'");
            if ($prcart->rowCount()){
                foreach($prcart as $crt){
                    $qty = 0;
                    $qty = $crt["qty"];
                    $qty = round($qty,3);
                    $price = 0;
                    $price = $crt["price"];
                    if (($_GET["type"] == "debit") && $price > 0){ $price = -$price; }
                    $subprice = 0;
                    $subprice = $price * $qty;
                    $vatrate = "";
                    $vatrate = $crt["vatrate"];
                    $vatprice = 0;
                    $vatprice = $subprice * $vatrate / (100 + $vatrate);
                    $vattotal = $vattotal + $vatprice;
                    $entotal = $entotal + $subprice;
                }
            }
            if ($vattotal != ""){
                $vattotal = number_format($vattotal,2);
            }
            else{
                $vattotal = 0;
            }
            if ($entotal != ""){
                $entotal = number_format($entotal,2);
            }
            else{
                $entotal = 0;
            }

            $subtotal = $entotal - $vattotal;
            $upbills = $db->query("update bills set subtotal='$subtotal', vattotal='$vattotal', entotal='$entotal' where id='$orderid'");

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "bills";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 43;
            $iduser = $paneluser["id"];
            $notes = "add item total amount : ".$subtotal;
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($savebillcart && $upbills){
                if ($type == "debit"){
                    setcookie('testcookie', "true|Debit Note Order Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                }
                else{
                    setcookie('testcookie', "true|Bill Order Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                }
            }
            else{
                if ($type == "debit"){
                    setcookie('testcookie', "false|Debit Note Order Not Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                }
                else{
                    setcookie('testcookie', "false|Bill Order Not Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                }
            }
        }
        elseif ($operation == "billdeleteall"){
            $orderid = $_GET["orderid"];
            $type = $_GET["type"];
            $userid = $_GET["userid"];
            $bl = $_GET["bl"];

            $upbillcart = $db->query("UPDATE bill_cart set isdeleted=1 WHERE billid='$orderid'");
            $upbill = $db->query("UPDATE bills set isdeleted=1 WHERE id='$orderid'");

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "bills";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 44;
            $iduser = $paneluser["id"];
            $notes = "";
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($upbillcart && $upbill){
                if ($type == "debit"){
                    setcookie('testcookie', "true|Debit Note Data Deleted...", time() + 20, '/');
                    header("Location: ../index.php?p=bill&bl=".$bl."&type=".$type);
                }
                else{
                    setcookie('testcookie', "true|Bill Data Deleted...", time() + 20, '/');
                    header("Location: ../index.php?p=bill&bl=".$bl."&type=".$type);
                }

            }
            else{
                if ($type == "debit"){
                    setcookie('testcookie', "false|Debit Note Order Not Deleted...", time() + 20, '/');
                    header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                }
                else{
                    setcookie('testcookie', "false|Bill Data Not Deleted...", time() + 20, '/');
                    header("Location: ../index.php?p=bill&bl=".$bl."&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
                }

            }
        }
        elseif ($operation == "billstatus"){
            $orderid = $_GET["orderid"];
            $status = $_GET["status"];
            $type = $_GET["type"];
            $bill = $db->query("SELECT * FROM bills where id='$orderid'")->fetch();

            //if(isset($_POST["pono"])){ $pono = strip_tags(trim($_POST["pono"])); }else{ $pono = ""; }
            //if (isset($_POST["shipvia"])){ $shipvia = strip_tags(trim($_POST["shipvia"])); }else{ $shipvia = ""; }

            if ($status == "1" && ($bill["deliverydate"] == "" || empty($bill["deliverydate"]))){
                $now = date("Y-m-d");
                $upbill = $db->query("update bills set status='$status', deliverydate='$now' where id='$orderid'");
            }
            else{
                $upbill = $db->query("update bills set status='$status' where id='$orderid'");
            }

            if ($status == 1){
                $bills = $db->query("SELECT * FROM bills where id='$orderid'")->fetch();
                $companyid = $bills["companyid"];
                $entotal = $bills["entotal"];
                $pono = $bills["pono"];
                $max = $db->query("SELECT max(id) as maxid FROM accounts")->fetch();
                $sonid = $max["maxid"] + 1;
                if (empty($sonid)){ $sonid = 1; }

                $amount = str_replace(",",".",$entotal);
                if ($amount == ""){ $amount = 0; }
                if ($type == "debit"){
                    $transactiontype = 41;
                }
                else{
                    $transactiontype = 19;
                }

                $cmb = $db->query("SELECT * FROM combos where id='$transactiontype'")->fetch();
                $transactiontype = $cmb["name"];

                if (strstr($transactiontype,"()")){

                }
                elseif (strstr($transactiontype,"(+)")){
                    if ($amount > 0){

                    }
                    else{
                        $amount = 0 - ($amount);
                    }
                }
                elseif (strstr($transactiontype,"(-)")){
                    if ($amount > 0){
                        $amount = 0 - ($amount);
                    }
                    else{

                    }
                }

                if ($type == "debit"){
                    $transactiontype = 41;
                }
                else{
                    $transactiontype = 19;
                }

                $transactiondate = date("Y-m-d");
                $saveacc = $db->query("insert into accounts (id, transactiondate, companyid, amount, transactiontype, paymentmethod, reference, bankid, note, billid) values('$sonid','$transactiondate','$companyid','$amount','$transactiontype','5','$pono','36','bill','$orderid')");

            }

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "bills";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 43;
            $iduser = $paneluser["id"];
            $notes = "Bill status change : ".$status;
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($upbill && $saveacc){
                header("Location: ../index.php?p=bill&bl=archived&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
            }
            else{
                header("Location: ../index.php?p=bill&bl=open&pg=panel-bill-edit&orderid=".$orderid."&type=".$type);
            }
        }
        elseif ($operation == "qtyupdate"){
            $qtyval = strip_tags(trim($_POST["qtyval"]));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update bill_cart set qty='$qtyval' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "sizeupdate"){
            $size = strip_tags(trim($_POST["size"]));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update bill_cart set product_sizeid='$size' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "unitupdate"){
            $unit = strip_tags(trim($_POST["unit"]));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update bill_cart set unit='$unit' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "nameupdate"){
            $name = strtoupper(strip_tags(trim($_POST["name"])));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update bill_cart set productname='$name' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "salesinvoiceupdate"){
            $sales = strtoupper(strip_tags(trim($_POST["sales"])));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update bill_cart set salesinvoiceno='$sales' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "expenseupdate"){
            $expenseid = strtoupper(strip_tags(trim($_POST["expense"])));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update bill_cart set expenseid='$expenseid' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "vatupdate"){
            $vatid = strtoupper(strip_tags(trim($_POST["vatid"])));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update bill_cart set vatid='$vatid' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "priceupdate"){
            $price = strip_tags(trim($_POST["ss"]));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update bill_cart set price='$price' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
        elseif ($operation == "vatpriceupdate"){
            $price = strip_tags(trim($_POST["ks"]));
            $id = strip_tags(trim($_POST["id"]));
            $uppurcart = $db->query("update bill_cart set price='$price' where id='$id'");
            if ($uppurcart){
                echo "true";
            }
            else{
                echo "false";
            }
        }
    }

    elseif ($pg == "credit"){
        $operation = $_GET["operation"];
        if ($operation == "creditnewok"){
            $companyid = strip_tags(trim($_POST["customerid"]));
            $addressid = strip_tags(trim($_POST["addressid"]));
            $notes = strip_tags(trim($_POST["notes"]));
            $max = $db->query("SELECT max(id) as maxid FROM orders")->fetch();
            $sonid = $max["maxid"] + 1;
            if (empty($sonid)){ $sonid = 1; }
            $maxno = $db->query("SELECT max(invoiceno) as maxid FROM orders where isdeleted<>1")->fetch();
            $invoiceno = $maxno["maxid"] + 1;
            if (empty($invoiceno)){ $invoiceno = 1000; }

            $cartdate = date("Y-m-d");
            $orderdate = date("Y-m-d");
            $invoicedate = date("Y-m-d");

            $savecredit = $db->query("insert into orders(id, invoiceno, ordertype, status, companyid, addressid, shiptoaddressid, cartdate, orderdate, notes, invoicedate) values('$sonid','$invoiceno','5','0','$companyid','$addressid','$addressid','$cartdate','$orderdate','$notes','$invoicedate')");

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "orders";
            $tableid = $sonid;
            $parentid = 0;
            $logtypeid = 42;
            $iduser = $paneluser["id"];
            $notes = "Credit Note";
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if($savecredit){
                setcookie('testcookie', "true|Credit Note Saved...", time() + 20, '/');
                header("Location: ../index.php?p=credit&crd=open");
            }
            else{
                setcookie('testcookie', "false|Credit Note Not Saved...", time() + 20, '/');
                header("Location: ../index.php?p=credit&crd=newcredit");
            }
        }
        elseif ($operation == "crediteditok"){
            $crd = $_GET["crd"];
            $orderid = $_GET["orderid"];
            if (isset($_POST["updatecredit"])){
                $websiteid = strip_tags(trim($_POST["websiteid"]));
                $pono = strip_tags(trim($_POST["pono"]));
                $invoicedate = strtotime($_POST["creditnotedate"]);
                $invoicedate = date("Y-m-d",$invoicedate);
                $notes = strip_tags(trim($_POST["notes"]));
                $customernotes = strip_tags(trim($_POST["customernotes"]));
                $upcredit = $db->query("update orders set notes='$notes', customernotes='$customernotes', invoicedate='$invoicedate', pono='$pono', websiteid='$websiteid' where id='$orderid'");
                if($upcredit){
                    setcookie('testcookie', "true|Credit Note Data Updated...", time() + 20, '/');
                    header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orderid);
                }
                else{
                    setcookie('testcookie', "false|Credit Note Data Not Updated...", time() + 20, '/');
                    header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orderid);
                }
            }
        }
        elseif ($operation == "panel-credit-edit"){
            $orid = $_GET["orderid"];
            $type = $_GET["type"];
            $crd = $_GET["crd"];
            if (isset($_POST["button5"])){
                $qty = strip_tags(trim($_POST["qty"]));
                $productid = $_POST["productid"];
                $product_sizeid = $_POST["product_sizeid"];
                $createdate = date("Y.m.d h:i:s");
                $orderselect = $db->query("select * from orders where id='$orid'")->fetch();
                $sessionid = $orderselect["sessionid"];
                $website = $orderselect["websiteid"];
                if ($productid == 88){
                    $basket = $db->query("insert into basket(orderid,productid,sizeid,sessionid,sample,quantity,vatrate,createdate,isordered,websiteid) values('$orid','$productid','$product_sizeid','$sessionid','1','$qty','20','$createdate','1','$website')");
                    if ($basket->rowCount()){
                        header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                    }
                    else{
                        header("Location: ../index.php?p=credit&crd=".$crd);
                    }
                }
                else{
                    $basket = $db->query("insert into basket(orderid,productid,sizeid,sessionid,quantity,vatrate,createdate,isordered,websiteid) values('$orid','$productid','$product_sizeid','$sessionid','$qty','20','$createdate','1','$website')");
                    if ($basket->rowCount()){
                        header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                    }
                    else{
                        header("Location: ../index.php?p=credit&crd=".$crd);
                    }
                }
            }
            if ($_POST["isnextday"] == "0"){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                $zn = $codepst["zone"];
                $zoneselect = $db->query("select * from zones where id='$zn'")->fetch();
                $isnextday = $_POST["isnextday"];
                $zone = $zoneselect["economy"];
                //$updelivery = $db->query("update orders set deliverydate=NULL, isnextday='$isnextday', deliverytime=NULL, deliveryprice='$zone' where id='$orid'");
                $updelivery = $db->prepare("update orders set deliverydate=:deliverydate, isnextday=:isnextday, deliverytime=:deliverytime, deliveryprice=:deliveryprice where id=:id");
                //$updelivery = $db->prepare("update orders set deliverydate=?, isnextday=?, deliverytime=?, deliveryprice=? where id=?");

                $updelivery->bindValue(':deliverydate',NULL,PDO::PARAM_NULL);
                $updelivery->bindValue(':isnextday',$isnextday,PDO::PARAM_INT);
                $updelivery->bindValue(':deliverytime',NULL,PDO::PARAM_NULL);
                $updelivery->bindValue(':deliveryprice',$zone,PDO::PARAM_INT);
                $updelivery->bindValue(':id',$orid,PDO::PARAM_INT);

                $updelivery->execute();

                if ($type == "credit"){
                    header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                }
            }
            if ($_POST["isnextday"] == "1"){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                $zn = $codepst["zone"];
                $zoneselect = $db->query("select * from zones where id='$zn'")->fetch();
                $isnextday = $_POST["isnextday"];
                $zone = $zoneselect["nextday"];
                //$updelivery = $db->query("update orders set deliverydate=NULL, isnextday='$isnextday', deliverytime=NULL, deliveryprice='$zone' where id='$orid'");
                $updelivery2 = $db->prepare("update orders set deliverydate=:deliverydate, isnextday=:isnextday, deliverytime=:deliverytime, deliveryprice=:deliveryprice where id=:id");
                //$updelivery = $db->prepare("update orders set deliverydate=?, isnextday=?, deliverytime=?, deliveryprice=? where id=?");

                $updelivery2->bindValue(':deliverydate',NULL,PDO::PARAM_NULL);
                $updelivery2->bindValue(':isnextday',$isnextday,PDO::PARAM_INT);
                $updelivery2->bindValue(':deliverytime',NULL,PDO::PARAM_NULL);
                $updelivery2->bindValue(':deliveryprice',$zone,PDO::PARAM_INT);
                $updelivery2->bindValue(':id',$orid,PDO::PARAM_INT);
                $updelivery2->execute();

                if ($type == "credit"){
                    header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                }
                else{
                    header("Location: ../index.php?p=sales&st=" . $st . "&pg=panel-order-edit&orderid=" . $orid . "&type=" . $type);
                }
            }
            if (isset($_POST["deliverytime"])){
                if ($_POST["deliverytime"] == "standard"){
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                    $zn = $codepst["zone"];
                    $zone = $db->query("select * from zones where id='$zn'")->fetch();
                    $deliveryprice = $zone["nextday"];
                    $uptime = $db->query("update orders set deliverytime='".$_POST["deliverytime"]."', deliveryprice='$deliveryprice' where id='$orid'");

                    if ($type == "credit"){
                        header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                    }
                }
                elseif ($_POST["deliverytime"] == "am"){
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                    $zn = $codepst["zone"];
                    $zone = $db->query("select * from zones where id='$zn'")->fetch();
                    $deliveryprice = $zone["nextday"] + $zone["ampm"];
                    $uptime = $db->query("update orders set deliverytime='".$_POST["deliverytime"]."', deliveryprice='$deliveryprice' where id='$orid'");

                    if ($type == "credit"){
                        header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                    }
                }
                elseif ($_POST["deliverytime"] == "pm"){
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                    $zn = $codepst["zone"];
                    $zone = $db->query("select * from zones where id='$zn'")->fetch();
                    $deliveryprice = $zone["nextday"] + $zone["ampm"];
                    $uptime = $db->query("update orders set deliverytime='".$_POST["deliverytime"]."', deliveryprice='$deliveryprice' where id='$orid'");

                    if ($type == "credit"){
                        header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                    }
                }
                elseif ($_POST["deliverytime"] == "saturdayam"){
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                    $zn = $codepst["zone"];
                    $zone = $db->query("select * from zones where id='$zn'")->fetch();
                    $deliveryprice = $zone["nextday"] + $zone["saturdayam"];
                    $uptime = $db->query("update orders set deliverytime='".$_POST["deliverytime"]."', deliveryprice='$deliveryprice' where id='$orid'");
                    if ($type == "credit"){
                        header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                    }
                }
                elseif ($_POST["deliverytime"] == "saturdaypm"){
                    $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                    $codepst = $db->query("select * from postcodes where id='".$orderpst['postcodeid']."'")->fetch();
                    $zn = $codepst["zone"];
                    $zone = $db->query("select * from zones where id='$zn'")->fetch();
                    $deliveryprice = $zone["nextday"] + $zone["saturdaypm"];
                    $uptime = $db->query("update orders set deliverytime='".$_POST["deliverytime"]."', deliveryprice='$deliveryprice' where id='$orid'");
                    if ($type == "credit"){
                        header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                    }
                    else{
                        header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                    }
                }
            }

            if (isset($_POST["datepicker"])){
                $pt = strtotime($_POST["datepicker"]);
                $tr = date('Y-m-d',$pt);
                $uptime = $db->query("update orders set deliverydate='$tr' where id='$orid'");
                if ($type == "credit"){
                    header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                }
            }
            if (isset($_POST["deliverydate"])){
                $ptdt = strtotime($_POST["deliverydate"]);
                $trdt = date('Y-m-d',$ptdt);
                $updeliverytime = $db->query("update orders set deliverydate='$trdt' where id='$orid'");
                if ($type == "credit"){
                    header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                }
            }
            if (isset($_POST["postcodeid"])){
                $orderpst = $db->query("select * from orders where id='$orid'")->fetch();
                $codepst = $db->query("select * from postcodes where id='".$_POST["postcodeid"]."'")->fetch();
                $zn = $codepst["zone"];
                $zoneselect = $db->query("select * from zones where id='$zn'")->fetch();
                $pstup = $db->query("update orders set postcodeid='".$_POST["postcodeid"]."', deliveryprice='".$zoneselect["nextday"]."' where id='$orid'");
                if ($type == "credit"){
                    header("Location: ../index.php?p=credit&crd=".$crd."&pg=panel-credit-edit&orderid=".$orid);
                }
                else{
                    header("Location: ../index.php?p=sales&st=".$st."&pg=panel-order-edit&orderid=".$orid."&type=".$type);
                }
            }
        }
        elseif ($operation == "creditstatus"){
            $status = $_GET["status"];
            $orderid = $_GET["orderid"];

            $basket = $db->query("select * from basket where orderid='$orderid'");
            if ($basket->rowCount()){

            }
            else{
                $noproduct = 1;
            }
            if ($noproduct == 1){

            }
            else{
                $order = $db->query("SELECT * FROM orders where id='$orderid'")->fetch();
                $companyid = $order["companyid"];
                $entotal = $order["entotal"];
                $pono = $order["pono"];

                $uporder = $db->query("update orders set status='$status' where id='$orderid'");

                $max = $db->query("SELECT max(id) as maxid FROM accounts")->fetch();
                $sonid = $max["maxid"] + 1;
                if (empty($sonid)){ $sonid = 1; }

                $transactiondate = date("Y-m-d");

                $amount = str_replace(",",".",$entotal);
                if ($amount == ""){ $amount = 0; }

                $transactiontype = 38;
                $cmb = $db->query("SELECT * FROM combos where id='$transactiontype'")->fetch();
                $transactiontype = $cmb["name"];
                if(strstr($transactiontype,"()")){

                }
                elseif (strstr($transactiontype,"(+)")){
                    if ($amount > 0){

                    }
                    else{
                        $amount = 0 - ($amount);
                    }
                }
                elseif (strstr($transactiontype,"(-)")){
                    if($amount > 0){
                        $amount = 0 - ($amount);
                    }
                    else{

                    }
                }

                $saveacc = $db->query("insert into accounts(id, transactiondate, companyid, amount, transactiontype, paymentmethod, reference, bankid, note, invoiceid) values('$sonid','$transactiondate','$companyid','$amount','38','5','$pono',36,'Credit Note','$orderid')");

                if ($saveacc){
                    setcookie('testcookie', "true|Credit Note Archived...", time() + 20, '/');
                    header("Location: ../index.php?p=credit&crd=archived");
                }
                else{
                    setcookie('testcookie', "false|Credit Note Not Archived...", time() + 20, '/');
                    header("Location: ../index.php?p=credit&crd=open&pg=panel-credit-edit&orderid=".$orderid);
                }
            }
        }
        elseif ($operation == "creditdeleteall"){
            $orderid = $_GET["orderid"];
            $upcart = $db->query("UPDATE cart set isdeleted=1 WHERE orderid = '$orderid'");
            $uporder = $db->query("UPDATE orders set isdeleted=1 WHERE id = '$orderid'");

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "orders";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 44;
            $iduser = $paneluser["id"];
            $notes = "Credit Note";
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

            if ($upcart && $uporder){
                setcookie('testcookie', "true|Credit Note Deleted...", time() + 20, '/');
                header("Location: ../index.php?p=credit&crd=open");
            }
            else{
                setcookie('testcookie', "false|Credit Note Not Deleted...", time() + 20, '/');
                header("Location: ../index.php?p=credit&crd=open&pg=panel-credit-edit&orderid=".$orderid);
            }
        }
    }

    elseif ($pg == "banking"){
        $operation = $_GET["operation"];
        $type = $_GET["type"];
        if ($operation == "newbanktransaction"){
            if (isset($_POST["savetransaction"])){
                $max = $db->query("SELECT max(id) as maxid FROM banktransactions")->fetch();
                $sonbankid = $max["maxid"] + 1;
                if (empty($sonbankid)){ $sonbankid = 1; }
                $bankid = strip_tags(trim($_POST["bankid"]));
                $transactiondate = $_POST["transactiondate"];
                $transactiondate = strtotime($transactiondate);
                $transactiondate = date("Y-m-d H:i:s",$transactiondate);
                $amount = strip_tags(trim($_POST["amount"]));
                $notes = strip_tags(trim($_POST["notes"]));
                $expenseid = strip_tags(trim($_POST["expenseid"]));
                if ($type == "payment"){
                    if ($amount < 0){

                    }
                    else{
                        $amount = 0 - ($amount);
                    }
                }
                else{
                    if ($amount > 0){

                    }
                    else{
                        $amount = 0 - ($amount);
                    }
                }
                $savepayment = $db->query("insert into banktransactions(id, bankid, transactiondate, amount, notes, expenseid) values('$sonbankid','$bankid','$transactiondate','$amount','$notes','$expenseid')");
                if ($savepayment){
                    if ($type == "payment"){
                        setcookie('testcookie', "true|Bank Payment Saved...", time() + 20, '/');
                        header("Location: ../index.php?p=banking&bnk=findbankpayment");
                    }
                    else{
                        setcookie('testcookie', "true|Bank Receipt Saved...", time() + 20, '/');
                        header("Location: ../index.php?p=banking&bnk=findbankreceipt");
                    }
                }
                else{
                    if ($type == "payment"){
                        setcookie('testcookie', "false|Bank Payment Saved...", time() + 20, '/');
                        header("Location: ../index.php?p=banking&bnk=newbankpayment");
                    }
                    else{
                        setcookie('testcookie', "false|Bank Receipt Not Saved...", time() + 20, '/');
                        header("Location: ../index.php?p=banking&bnk=newbankreceipt");
                    }
                }
            }
        }
        elseif ($operation == "newbanktransferfunds"){
            $max = $db->query("SELECT max(id) as maxid FROM banktransactions")->fetch();
            $sonbankid = $max["maxid"] + 1;
            if (empty($sonbankid)){ $sonbankid = 1; }
            $transactiondate = strtotime($_POST["transactiondate"]);
            $transactiondate = date("Y-m-d",$transactiondate);
            $amount = strip_tags(trim($_POST["amount"]));
            $amount = 0 - ($amount);

            $savefund = $db->query("insert into banktransactions (id, bankid, transactiondate, amount, notes, transferbankid) values('$sonbankid','".$_POST["frombankid"]."','$transactiondate','$amount','".$_POST["notes"]."','".$_POST["tobankid"]."')");

            $max2 = $db->query("SELECT max(id) as maxid FROM banktransactions")->fetch();
            $sonbankid = $max2["maxid"] + 1;
            if (empty($sonbankid)){ $sonbankid = 1; }
            $amount = strip_tags(trim($_POST["amount"]));

            $savefund2 = $db->query("insert into banktransactions (id, bankid, transactiondate, amount, notes, transferbankid) values('$sonbankid','".$_POST["tobankid"]."','$transactiondate','$amount','".$_POST["notes"]."','".$_POST["frombankid"]."')");

            if ($savefund && $savefund2){
                setcookie('testcookie', "true|Transfer Fund Saved...", time() + 20, '/');
                header("Location: ../index.php?p=banking&bnk=findtransferfund");
            }
            else{
                setcookie('testcookie', "false|Transfer Fund Not Saved...", time() + 20, '/');
                header("Location: ../index.php?p=banking&bnk=banktransactions");
            }
        }
        elseif ($operation == "newdepositreceipts"){
            if (isset($_POST["savetransaction"])){
                $max = $db->query("SELECT max(id) as maxid FROM banktransactions")->fetch();
                $max2 = $db->query("SELECT max(id) as maxid FROM accounts")->fetch();
                $sonbankid = $max["maxid"] + 1;
                $sonid = $max2["maxid"] + 1;
                if (empty($sonbankid)){ $sonbankid = 1; }
                $bankid = strip_tags(trim($_POST["bankid"]));
                $transactiondate = $_POST["transactiondate"];
                $transactiondate = strtotime($transactiondate);
                $transactiondate = date("Y-m-d H:i:s",$transactiondate);
                $companyid = strip_tags(trim($_POST["companyid"]));
                $amount = strip_tags(trim($_POST["amount"]));
                $notes = strip_tags(trim($_POST["note"]));
                $transactiontype = strip_tags(trim($_POST["transactiontype"]));
                $newpaymentterm = strip_tags(trim($_POST["newpaymentterm"]));
                $reference = strip_tags(trim($_POST["reference"]));

                if ($type == "payment"){
                    if ($amount < 0){

                    }
                    else{
                        $amount = 0 - ($amount);
                    }
                }
                else{
                    if ($amount > 0){

                    }
                    else{
                        $amount = 0 - ($amount);
                    }
                }

                $saveacc = $db->query("insert into accounts (id, transactiondate, companyid, amount, transactiontype, paymentmethod, reference, bankid, note) values('$sonid','$transactiondate','$companyid','$amount','$transactiontype','$newpaymentterm','$reference','24','$notes')");

                $savedeposit = $db->query("insert into banktransactions(id, bankid, transactiondate, amount, notes) values('$sonbankid','24','$transactiondate','$amount','$notes')");

                if ($savedeposit && $saveacc){
                    setcookie('testcookie', "true|Deposit Receipt Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=banking&bnk=finddepositreceipts");
                }
                else{
                    setcookie('testcookie', "false|Deposit Receipt Not Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=banking&bnk=finddepositreceipts");
                }
            }
        }
        elseif ($operation == "accountstobank"){
            if ($_POST["bankid"] != 24){
                $chkCnt = count($_POST["csec"]);
                $i = 0;
                if ($chkCnt > 0){
                    do{
                        $gelen = $_POST["csec"][$i];
                        if ($ahaid != ""){
                            $ahaid = $ahaid.",".$gelen;
                        }
                        else{
                            $ahaid = $gelen;
                        }
                        $i++;
                        $gelen = "";
                    }while($i < $chkCnt);
                }
                if ($ahaid != ""){
                    $max = $db->query("SELECT max(id) as maxid FROM banktransactions")->fetch();
                    $sonid = $max["maxid"] + 1;
                    if ($sonid == "" || empty($sonid)){
                        $sonid = 1;
                    }
                    $transactiondate = $_POST["date"];
                    $transactiondate = strtotime($transactiondate);
                    $transactiondate = date("Y-m-d",$transactiondate);
                    $bankid = $_POST["bankid"];
                    $amount = $_POST["totalamount"];

                    $savebanktran = $db->query("insert into banktransactions(id, bankid, transactiondate, amount) values('$sonid','$bankid','$transactiondate','$amount')");

                    $acc = $db->query("SELECT * from accounts where accounts.id in(".$ahaid.") order by transactiondate desc, accounts.id desc");
                    foreach($acc as $accts){
                        $id = $accts["id"];
                        $upacc = $db->query("update accounts set bankid='$bankid', banktransactionid='$sonid' where id='$id'");
                    }
                }

                $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                $processdate = date("Y-m-d");
                $tablename = "banktransactions";
                $tableid = $sonid;
                $parentid = 0;
                $logtypeid = 42;
                $iduser = $paneluser["id"];
                $notes = "accounts to bank amount : ".$amount;
                $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

                if ($upacc && $savebanktran){
                    setcookie('testcookie', "true|Deposit Receipt...", time() + 20, '/');
                    header("Location: ../index.php?p=banking&bnk=finddepositreceipts");
                }
                else{
                    setcookie('testcookie', "false|Deposit Not Receipt...", time() + 20, '/');
                    header("Location: ../index.php?p=banking&bnk=finddepositreceipts");
                }
            }
        }
        elseif ($operation == "newbankaccount"){
            if (isset($_POST["savetransaction"])){
                $transactiondate = strtotime($_POST["transactiondate"]);
                $transactiondate = date("Y-m-d",$transactiondate);
                $amount = strip_tags(trim($_POST["amount"]));
                $bankid = $_POST["bankid"];
                $notes = strip_tags(trim($_POST["notes"]));
                $expenseid = $_POST["expenseid"];
                $max = $db->query("SELECT max(id) as maxid FROM banktransactions")->fetch();
                $sonbankid = $max["maxid"] + 1;

                if ($sonbankid == "" || empty($sonbankid)){
                    $sonbankid = 1;
                }

                if ($amount > 0){

                }
                else{
                    $amount = 0 - ($amount);
                }

                $savebanktr = $db->query("insert into banktransactions(id, bankid, transactiondate, amount, notes, expenseid) values('$sonbankid','$bankid','$transactiondate','$amount','$notes','$expenseid')");

                if ($savebanktr){
                    setcookie('testcookie', "true|Bank Account Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=banking&bnk=banktransactions&pg=panel-bank-edit&id=".$sonbankid);
                }
                else{
                    setcookie('testcookie', "false|Bank Account Not Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=banking&bnk=newbankaccount");
                }
            }
        }
        elseif ($operation == "getexpense"){
            $type = $_POST["type"];
            if ($type == "income"){
                $cmb2 = $db->query("SELECT * FROM combos where menu='$type' order by sira");
                ?>
                <option disabled selected>Select Income</option>
                <?php
                foreach($cmb2 as $ex){
                    ?>
                    <option value="<?php echo $ex["id"]; ?>"><?php echo $ex["name"]; ?></option>
                    <?php
                }
            }
            elseif ($type == "expense"){
                $cmb2 = $db->query("SELECT * FROM combos where menu='$type' order by sira");
                ?>
                <option disabled selected>Select Expense</option>
                <?php
                foreach($cmb2 as $ex){
                    ?>
                    <option value="<?php echo $ex["id"]; ?>"><?php echo $ex["name"]; ?></option>
                    <?php
                }
            }
        }
    }

    elseif ($pg == "samplemanagement"){
        $operation = $_GET["operation"];
        if ($operation == "insertsample"){
            if (isset($_POST["sessid"])){
                ?>
                <option value="">Please Select Product Sample</option>
                <?php
                $samples = $db->query("SELECT products.*, sizes.size FROM products left join product_sizes on product_sizes.productid=products.szid left join sizes on product_sizes.sizeid=sizes.id where products.freesample=1 and (products.showsite=1 or products.isdummy=1) and sizes.size='Sample' and websiteid='".$_POST["sessid"]."' order by products.name asc");
                if ($samples->rowCount()){
                    foreach($samples as $sample){
                        ?>
                        <option value="<?php echo $sample["pr_id"]; ?>" data-name="<?php echo $sample["name"]; ?>" data-sizename="<?php echo $sample["size"]; ?>">
                            <?php echo $sample["name"]; ?> - <?php echo $sample["size"]; ?>
                        </option>
                        <?php
                    }
                }
            }
            elseif (isset($_POST["savesample"])){

                $ipadresi = $_REQUEST['REMOTE_ADDR'];
                $linkyapisi = @unserialize(file_get_contents('http://ip-api.com/php/'.$ipadresi));
                $ipno = $linkyapisi["query"];

                $firstname = strtoupper($_POST["firstname"]);
                $lastname = strtoupper($_POST["lastname"]);
                $house = strtoupper($_POST["house"]);
                $street = strtoupper($_POST["street"]);
                $city = strtoupper($_POST["city"]);
                $county = strtoupper($_POST["county"]);
                //$country = strtoupper($_POST["country"]);
                $postcode = strtoupper($_POST["postcode"]);
                $email = strtoupper($_POST["email"]);
                $tel = strtoupper($_POST["tel"]);
                $companyname = strtoupper($_POST["companyname"]);
                $company = $_POST["company"];
                $website = $_POST["website"];

                if ($_POST["companyid"] != ""){ $companyid = $_POST["companyid"]; }
                $usertype = "request-sample";

                $saveuser = $db->query("insert into users (email, companyname, companyid, firstname, lastname, house, street, city, county, postcode, tel, ipno, usertype) values('$email','$companyname','$companyid','$firstname','$lastname','$house','$street','$city','$county','$postcode','$tel','$ipno','$usertype')");

                $maxuser = $db->query("SELECT max(id) as maxid FROM users where firstname='$firstname'")->fetch();
                $userid = $maxuser["maxid"];

                $samplesession = $_SESSION["samplesession"];

                $maxorder = $db->query("select * from basket where sessionid='$samplesession'")->fetch();
                $sonid = $maxorder["orderid"];
                $orderid = $sonid;

                if (empty($sonid)){ $sonid = 1; }

                $nosmp = $db->query("SELECT max(sampleno) as maxid FROM orders")->fetch();
                $sampleno = $nosmp["maxid"] + 1;
                if (empty($sampleno)){ $sampleno = 1000; }

                if ($_POST["companyid"] != ""){ $companyid = $_POST["companyid"]; }
                if ($_POST["ispaid"] != ""){ $ispaid = $_POST["ispaid"]; }

                $cartdate = date("Y-m-d");
                $orderdate = date("Y-m-d");

                $orderup = $db->query("update orders set sampleno='$sampleno', ordertype='1', status='1', companyid='$companyid', userid='$userid', ipno='$ipno', cartdate='$cartdate', orderdate='$orderdate', company='$company', ispaid='$ispaid', websiteid='$website' where id='$sonid' and sessionid='$samplesession'");

                $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
                $processdate = date("Y-m-d");
                $tablename = "orders";
                $tableid = $sonid;
                $parentid = 0;
                $logtypeid = 42;
                $iduser = $paneluser["id"];
                $notes = "Sample";
                $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");

                if ($_POST["addvoucher"] == "1"){
                    $user = $firstname."        ".$lastname;
                    header("Location: include/operations.php?pg=salesOrder&process=ordereditok&operation=newcouponok&orderid=".$orderid."&type=order&user='$user'");
                    unset($_SESSION["samplesession"]);
                }
                else{
                    if ($loginsert){
                        setcookie('testcookie', "true|Sample Order Inserted...", time() + 20, '/');
                        header("Location: ../index.php?p=sample_management&smp=approvedsample");
                        unset($_SESSION["samplesession"]);
                    }
                    else{
                        setcookie('testcookie', "false|Sample Order Not Inserted...", time() + 20, '/');
                        header("Location: ../index.php?p=sample_management&smp=approvedsample");
                        unset($_SESSION["samplesession"]);
                    }
                }
            }
        }
        elseif ($operation == "addsample"){

            $productid = strip_tags(trim($_POST["productid"]));
            $samplesize = strip_tags(trim($_POST["samplesize"]));
            $qty = strip_tags(trim($_POST["qty"]));
            $wbst = strip_tags(trim($_POST["wbst"]));
            $sessionid = date("Ymdhis").$productid;
            $createdate = date("Y.m.d h:i:s");

            if ($wbst == 1){
                $totalqty = $totalqty + $qty;
                if ($totalqty > 2){
                    $total = $totalqty - 2;
                    $entotal = $total * 2;
                }
            }
            elseif ($wbst == 2){
                $totalqty = $totalqty + $qty;
                if ($totalqty > 2){
                    $total = $totalqty - 2;
                    $entotal = $total * 2.25;
                }
            }

            $szSelect = $db->query("SELECT products.*, sizes.size, sizes.id as sizeid, sizes.sizeunit, sizes.itemunit, sizes.qtyunit, product_sizes.id as product_sizeid FROM products left join product_sizes on product_sizes.productid=products.szid left join sizes on product_sizes.sizeid=sizes.id where products.pr_id='$productid' and sizes.size='Sample' and products.websiteid='$wbst' order by products.name asc")->fetch();

            $sizeid = $szSelect["sizeid"];

            if($_SESSION["samplesession"]){
                $sessionsample = $_SESSION["samplesession"];
                $session = $db->query("select * from basket where sessionid='$sessionsample'")->fetch();
                $orderid = $session["orderid"];

                $savebasket = $db->query("insert into basket(orderid, productid, sizeid, sessionid, sample, quantity, vatrate, vatid, samplesize, createdate, isordered, websiteid) values('$orderid','$productid','$sizeid','$sessionsample','1','$qty','20','29','$samplesize','$createdate','1','$wbst')");

                $uporder = $db->query("update orders set entotal='$entotal' where id='$orderid'");

            }
            else{
                $max = $db->query("select max(id) as maxorderid from orders")->fetch(); //basket taki max orderid de kullanılabilirdi...
                $orderid = $max["maxorderid"] + 1;
                $_SESSION["samplesession"] = $sessionid;

                $savebasket = $db->query("insert into basket(orderid, productid, sizeid, sessionid, sample, quantity, vatrate, vatid, samplesize, createdate, isordered, websiteid) values('$orderid','$productid','$sizeid','$sessionid','1','$qty','20','29','$samplesize','$createdate','1','$wbst')");

                $saveorder = $db->query("insert into orders(id, sessionid, websiteid) values('$orderid','$sessionid','$wbst')");
                $uporder = $db->query("update orders set entotal='$entotal' where id='$orderid'");
            }
        }
        elseif ($operation == "remove"){
            $productid = strip_tags(trim($_POST["c"]));
            $samplesize = strip_tags(trim($_POST["d"]));
            $qty = strip_tags(trim($_POST["e"]));

            $samplesession = $_SESSION["samplesession"];

            $select = $db->query("select * from basket where productid='$productid' and quantity='$qty' and samplesize='$samplesize'");
            if ($select->rowCount()){
                $delete = $db->query("delete from basket where productid='$productid' and quantity='$qty' and samplesize='$samplesize'");
            }
            else{
                $orderdelete = $db->query("delete from orders where sessionid='$samplesession'");
                unset($_SESSION["samplesession"]);
            }
        }
        elseif ($operation == "sampleeditok"){
            $orderid = $_GET["orderid"];
            $smp = $_GET["smp"];
            $companyid = strip_tags(trim($_POST["companyid"]));
            $contactname = strip_tags(trim($_POST["contactname"]));
            $name = explode(" ",$contactname);
            $firstname = $name[0];
            $lastname = $name[1];
            $email = strip_tags(trim($_POST["email"]));
            $house = strip_tags(trim($_POST["house"]));
            $street = strip_tags(trim($_POST["street"]));
            $city = strip_tags(trim($_POST["city"]));
            $county = strip_tags(trim($_POST["county"]));
            $postcode = strip_tags(trim($_POST["postcode"]));
            $tel = strip_tags(trim($_POST["tel"]));
            $company = strip_tags(trim($_POST["company"]));
            $websiteid = strip_tags(trim($_POST["websiteid"]));
            $ispaid = strip_tags(trim($_POST["ispaid"]));
            $isplacedorder = strip_tags(trim($_POST["isplacedorder"]));
            $notes = strip_tags(trim($_POST["notes"]));

            $ipadresi = $_REQUEST['REMOTE_ADDR'];
            $linkyapisi = @unserialize(file_get_contents('http://ip-api.com/php/'.$ipadresi));
            $ipno = $linkyapisi["query"];

            $order = $db->query("SELECT * FROM orders where id='$orderid'")->fetch();
            $userid = $order["userid"];

            $userdata = $db->query("update users set email='$email', companyname='$contactname', companyid='$companyid', firstname='$firstname', lastname='$lastname', house='$house', street='$street', city='$city', county='$county', postcode='$postcode', tel='$tel', notes='$notes', ipno='$ipno' where id='$userid'");

            if ($userdata){
                $orderdata = $db->query("update orders set companyid='$companyid', company='$company', ispaid='$ispaid', isplacedorder='$isplacedorder', websiteid='$websiteid' where id='$orderid'");
                if ($orderdata){
                    header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
                    setcookie('testcookie', "true|Sample Data Updated...", time() + 20, '/');
                }
                else{
                    header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
                    setcookie('testcookie', "false|Sample Data Not Updated...", time() + 20, '/');
                }
            }
            else{
                header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
                setcookie('testcookie', "false|Sample Data Not Updated...", time() + 20, '/');
            }

        }
        elseif ($operation == "samplestatus"){
            $orderid = $_GET["orderid"];
            $status = $_GET["status"];
            $stup = $db->query("update orders set status='$status' where id='$orderid'");
            if ($stup->rowCount()){
                setcookie('testcookie', "true|Sample Approved...", time() + 20, '/');
                header("Location: ../index.php?p=sample_management&smp=approvedsample&pg=panel-sample-edit&orderid='$orderid'");
            }
            else{
                setcookie('testcookie', "false|Sample Not Approved...", time() + 20, '/');
                header("Location: ../index.php?p=sample_management&smp=approvedsample&pg=panel-sample-edit&orderid='$orderid'");
            }
        }
        elseif ($operation == "sampledeleteall"){
            $orderid = $_GET["orderid"];
            $userid = $_GET["userid"];
            $basketdelete = $db->query("delete from basket where orderid='$orderid'");
            if ($basketdelete->rowCount()){
                $orderdelete = $db->query("delete from orders where id='$orderid'");
                if ($orderdelete->rowCount()){
                    if ($userid != ""){
                        $userdelete = $db->query("delete from users where id='$userid'");
                        if ($userdelete->rowCount()){
                            setcookie('testcookie', "true|Sample Basket, Order and User Deleted...", time() + 20, '/');
                            header("Location: ../index.php?p=sample_management&smp=receivedsample");
                        }
                        else{
                            setcookie('testcookie', "false|Sample Basket, Order Deleted but User Not Deleted...", time() + 20, '/');
                            header("Location: ../index.php?p=sample_management&smp=receivedsample");
                        }
                    }
                    else{
                        setcookie('testcookie', "false|Sample Basket, Order Deleted but User Not Deleted...", time() + 20, '/');
                        header("Location: ../index.php?p=sample_management&smp=receivedsample");
                    }
                }
                else{
                    setcookie('testcookie', "false|Sample Basket Deleted but Order Not Deleted...", time() + 20, '/');
                    header("Location: ../index.php?p=sample_management&smp=receivedsample");
                }
            }
            else{
                setcookie('testcookie', "false|Sample Basket Not Deleted...", time() + 20, '/');
                header("Location: ../index.php?p=sample_management&smp=receivedsample");
            }
        }
        elseif ($operation == "sampleshipped"){
            $orderid = $_GET["orderid"];
            $user = $db->query("SELECT users.*, orders.* FROM users left join orders on users.id=orders.userid where orders.id='$orderid'")->fetch();
            $websiteid = $user["websiteid"];
            $strName = $user["firstname"]."  ".$user["lastname"];
            $strEmail = $user["email"];
            $status = 2;

            $stup = $db->query("updaet orders set status='$status' where id='$orderid'");

            if ($user["deliverydate"] == "" || empty($user["deliverydate"])){
                $date = date("Y-m-d");
            }
            $isplaceorder = 0;
            $strAddress = $user["house"]."   ".$user["street"]."   ".$user["city"]."   ".$user["county"]."   ".$user["postcode"];

            /*
             * MAIL
             *
             *  Buraya email göndermek için gereken işlemler gelecek... panel-process2.asp?p=sampleshipped bölümünden bakabilirsin
             *
             */

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "orders";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 43;
            $iduser = $paneluser["id"];
            $notes = "Sample Shipped Email";
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");
            if ($loginsert->rowCount()){
                setcookie('testcookie', "true|Sample Post and Send Email...", time() + 20, '/');
                header("Location: ../index.php?p=sample_management&smp=archivedsample&&pg=panel-sample-edit&orderid='$orderid'");
            }
            else{
                setcookie('testcookie', "false|Sample Not Post and Send Email...", time() + 20, '/');
                header("Location: ../index.php?p=sample_management&smp=archivedsample&&pg=panel-sample-edit&orderid='$orderid'");
            }
        }
        elseif ($operation == "firstfollowup"){
            $orderid = $_GET["orderid"];
            $pagequery = $_GET["type"];
            if ($type == "proforma"){
                $companies = $db->query8("SELECT companies.*, orders.* FROM companies left join orders on companies.id=orders.companyid where orders.id='$orderid'")->fetch();
                $websiteid = $companies["websiteid"];
                $strName = $companies["firstname"]."   ".$companies["lastname"];
                $strEmail = $companies["email"];
                $status = 2;
                $fupfirstdate = date("Y-m-d");
                $couponcode = $companies["couponcode"];
                $fupfirstdate = date("Y-m-d");
                $upproforma = $db->query("update orders set status='$status', fupfirstdate='$fupfirstdate' where id='$orderid'");
            }
            else{
                $companies = $db->query("SELECT users.*, orders.* FROM users left join orders on users.id=orders.userid where orders.id='$orderid'")->fetch();
                $strName = $companies["firstname"]."   ".$companies["lastname"];
                $strEmail = $companies["email"];
                $status = 2;
                $fupfirstdate = date("Y-m-d");
                $couponcode = $companies["couponcode"];
                $websiteid = $companies["websiteid"];
                $uporder = $db->query("update orders set status='$status', fupfirstdate='$fupfirstdate' where id='$orderid'");
            }

            if ($type == "proforma"){
                $forwhat = "Proforma-Firstfollowup";
            }
            else{
                $forwhat = "Sample-Firstfollowup";
            }

            if ($couponcode != ""){
                $forwhat = $forwhat."voucher";
            }
            if ($websiteid == 2){
                $strFromEmail = "sales@travertinetilesuk.com";
            }
            else{
                $strFromEmail = "info@stonedeals.co.uk";
            }

            /*
             * MAIL
             *
             *  Buraya email göndermek için gereken işlemler gelecek... panel-process2.asp?p=sampleshipped bölümünden bakabilirsin
             *
             */

            $paneluser = $db->query("select * from panelusers where username='".$_SESSION['admin']['username']."'")->fetch();
            $processdate = date("Y-m-d");
            $tablename = "orders";
            $tableid = $orderid;
            $parentid = 0;
            $logtypeid = 43;
            $iduser = $paneluser["id"];
            $notes = "Sample Follow Up";
            $loginsert = $db->query("insert into logs (processdate, tablename, tableid, parentid, logtypeid, userid, notes) values('$processdate','$tablename','$tableid','$parentid','$logtypeid','$iduser','$notes')");
            if ($loginsert->rowCount()){
                if ($type == "proforma"){
                    setcookie('testcookie', "true|Sample Post and Send Email...", time() + 20, '/');
                    header("Location: ../index.php?p=sales&type=proforma");
                }
                else{
                    setcookie('testcookie', "true|Sample Post and Send Email...", time() + 20, '/');
                    header("Location: ../index.php?p=sample_management&smp=archivedsample&&pg=panel-sample-edit&orderid='$orderid'");
                }

            }
            else{
                if ($type == "proforma"){
                    setcookie('testcookie', "false|Sample Not Post and Send Email...", time() + 20, '/');
                    header("Location: ../index.php?p=sales&type=proforma");
                }
                else{
                    setcookie('testcookie', "false|Sample Not Post and Send Email...", time() + 20, '/');
                    header("Location: ../index.php?p=sample_management&smp=archivedsample&&pg=panel-sample-edit&orderid='$orderid'");
                }
            }
        }
        elseif ($operation == "closefollowup"){
            $orderid = $_GET["orderid"];
            $status = 3;
            $stup = $db->query("update orders set status='$status' where id='$orderid'");
            if ($stup->rowCount()){
                if ($_GET["type"] == "proforma"){
                    setcookie('testcookie', "true|Close Sample...", time() + 20, '/');
                    header("Location: ../index.php?p=sales&st=lost&pg=panel-order-edit&orderid=".$orderid."&type=".$type);
                }
                else{
                    setcookie('testcookie', "false|Not Close Sample...", time() + 20, '/');
                    header("Location: ../index.php?p=sample_management&smp=archivedsample&pg=panel-sample-edit&orderid='$orderid'");
                }
            }
            else{
                setcookie('testcookie', "false|Not Close Sample...", time() + 20, '/');
                header("Location: ../index.php?p=sample_management&smp=archivedsample&pg=panel-sample-edit&orderid='$orderid'");
            }
        }
        elseif ($operation == "sampleadd"){
            $orderid = $_GET["orderid"];
            $smp = $_GET["smp"];
            if (isset($_POST["button5"])){
                $qty = strip_tags(trim($_POST["qty"]));
                $qty = ceil($qty);
                $productid = $_POST["productid"];
                $samplesize = $_POST["samplesize"];
                $createdate = date("Y.m.d h:i:s");

                $orderselect = $db->query("select * from orders where id='$orderid'")->fetch();
                $sessionid = $orderselect["sessionid"];
                $website = $orderselect["websiteid"];

                $pr = $db->query("SELECT products.*, sizes.size, sizes.id as sizeid, sizes.sizeunit, sizes.itemunit, sizes.qtyunit, product_sizes.id as product_sizeid FROM products left join product_sizes on product_sizes.productid=products.szid left join sizes on product_sizes.sizeid=sizes.id where products.pr_id='$productid' and sizes.size='Sample' and products.websiteid='$website'")->fetch();

                $sizeid = $pr["sizeid"];

                $basket = $db->query("insert into basket(orderid,productid,sizeid,sessionid,sample,quantity,vatrate,vatid,samplesize,createdate,isordered,websiteid) values('$orderid','$productid','$sizeid','$sessionid','1','$qty','20','29','$samplesize','$createdate','1','$website')");
                if ($basket){

                    if ($website == 1){
                        $bs = $db->query("select SUM(quantity) AS totalqty from basket where orderid='$orderid'")->fetch();
                        if ($bs["totalqty"] > 2){
                            $total = $bs["totalqty"] - 2;
                            $entotal = $total * 2;
                            $uporder = $db->query("update orders set entotal='$entotal' where id='$orderid'");
                        }
                    }
                    elseif ($website == 2){
                        $bs = $db->query("select SUM(quantity) AS totalqty from basket where orderid='$orderid'")->fetch();
                        if ($bs["totalqty"] > 2){
                            $total = $bs["totalqty"] - 2;
                            $entotal = $total * 2.25;
                            $uporder = $db->query("update orders set entotal='$entotal' where id='$orderid'");
                        }
                    }

                    setcookie('testcookie', "true|Sample Added...", time() + 20, '/');
                    header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
                }
                else{
                    setcookie('testcookie', "false|Sample Not Added...", time() + 20, '/');
                    header("Location: ../index.php?p=sample_management&smp=".$smp);
                }
            }
        }
        elseif ($operation == "samplesizeup"){
            $bsid = $_POST["id"];
            $size = $_POST["size"];

            $bsup = $db->query("update basket set samplesize='$size' where id='$bsid'");
            if ($bsup->rowCount()){
                echo "true";
            }
            else{
                echo "false";
            }

        }
        elseif ($operation == "deletesample"){
            $bsid = $_GET["id"];
            $orderid = $_GET["orderid"];
            $smp = $_GET["smp"];
            $sampledelete = $db->query("delete from basket where id='$bsid' and orderid='$orderid'");
            if ($sampledelete){
                $bs = $db->query("select SUM(quantity) AS totalqty from basket where orderid='$orderid'")->fetch();
                if ($bs["totalqty"] <= 2){
                    $uporder = $db->query("update orders set entotal=0 where id='$orderid'");
                }
                header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
            }
            else{
                header("Location: ../index.php?p=sample_management&smp=".$smp."&pg=panel-sample-edit&orderid=".$orderid);
            }
        }

    }

    elseif ($pg == "reviews"){
        $process = $_GET["process"];
        $q = $_GET["q"];
        if ($process == "editreview"){
            if ($q == "get"){
                $reviewID = $_GET["reviewID"];
                $reviewSelect = $db->query("select * from reviews where id='$reviewID'");

                $review_data = array();
                $prcnt = $reviewSelect->rowCount();
                if ($prcnt) {
                    foreach ($reviewSelect as $product_edit) {
                        $pr_id = $product_edit["productid"];
                        $site = $product_edit["websiteid"];

                        if ($site == 1){
                            $prd = $db->query("select * from products where pr_id='$pr_id' and websiteid='$site'")->fetch();
                        }
                        elseif ($site == 2){
                            $prds = $db->query("select * from products where pr_id='$pr_id' and websiteid='$site'")->fetch();
                            $pr_id = $prds["szid"];
                            $prd = $db->query("select * from products where szid='$pr_id' and websiteid='$site'")->fetch();
                        }

                        $st = $db->query("select * from websites where websiteid='$site'")->fetch();
                        $review_data["review_data"] = array(
                            "id" => $product_edit["id"],
                            "productid" => $product_edit["productid"],
                            "name" => $product_edit["name"],
                            "comment" => $product_edit["comment"],
                            "tarih" => $product_edit["tarih"],
                            "email" => $product_edit["email"],
                            "show" => $product_edit["show"],
                            "rating" => $product_edit["rating"],
                            "websiteid" => $product_edit["websiteid"],
                            "productname" => $prd["name"],
                            "websitename" => $st["websitename"],
                            "prcnt" => $prcnt
                        );
                    }
                }
                echo json_encode($review_data);
            }
            elseif ($q == "update"){
                if (isset($_POST["review_update"])){
                    $review_hidden = strip_tags(trim($_POST["review_hidden"]));
                    $review_name = strip_tags(trim($_POST["review_name"]));
                    $review_email = strip_tags(trim($_POST["review_email"]));
                    $review_time = strip_tags(trim($_POST["review_time"]));
                    $review_review = strip_tags(trim($_POST["review_review"]));
                    $review_product = strip_tags(trim($_POST["review_product"]));
                    $review_rating = strip_tags(trim($_POST["review_rating"]));
                    $review_website= strip_tags(trim($_POST["review_website"]));
                    $review_show = $_POST["review_show"];
                    if ($review_show == "on"){$review_show = "1";}else{$review_show = "0";}
                    $asd = date("H:i:s");
                    $review_time = $review_time.$asd;

                    $review_time = strtotime($review_time);
                    $review_time = date("Y-m-d H:i:s",$review_time);

                    $uprev = $db->query("update reviews set productid='$review_product', name='$review_name', comment='$review_review', tarih='$review_time', email='$review_email', `show`='$review_show', rating='$review_rating', websiteid='$review_website' where id='$review_hidden'");
                    if ($uprev){
                        setcookie('testcookie', "true|Reviews Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=reviews");
                    }
                    else{
                        setcookie('testcookie', "false|Reviews Not Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=reviews");
                    }
                }
            }
        }
        elseif ($process == "review_delete"){
            $rvID = $_GET["rvID"];
            $reviewDelete = $db->query("delete from reviews where id='$rvID'");
            if ($reviewDelete){
                setcookie('testcookie', "true|Reviews Deleted...", time() + 20, '/');
            }
            else{
                setcookie('testcookie', "false|Reviews Not Deleted...", time() + 20, '/');
            }
        }
    }

    elseif ($pg == "questions"){
        $process = $_GET["process"];
        $q = $_GET["q"];
        if ($process == "editquestion"){
            if ($q == "get"){
                $queID = $_GET["queID"];
                $questionSelect = $db->query("select * from questions where id='$queID'");
                $question_data = array();
                $prcnt = $questionSelect->rowCount();
                if ($prcnt) {
                    foreach ($questionSelect as $product_edit) {
                        $pr_id = $product_edit["productid"];
                        $site = $product_edit["websiteid"];
                        if ($site == 1){
                            $prd = $db->query("select * from products where pr_id='$pr_id' and websiteid='$site'")->fetch();
                        }
                        elseif ($site == 2){
                            $prds = $db->query("select * from products where pr_id='$pr_id' and websiteid='$site'")->fetch();
                            $pr_id = $prds["szid"];
                            $prd = $db->query("select * from products where szid='$pr_id' and websiteid='$site'")->fetch();
                        }
                        $st = $db->query("select * from websites where websiteid='$site'")->fetch();
                        $question_data["question_data"] = array(
                            "id" => $product_edit["id"],
                            "productid" => $product_edit["productid"],
                            "name" => $product_edit["name"],
                            "comment" => $product_edit["comment"],
                            "answer" => $product_edit["answer"],
                            "tarih" => $product_edit["tarih"],
                            "email" => $product_edit["email"],
                            "show" => $product_edit["show"],
                            "isemailsent" => $product_edit["isemailsent"],
                            "websiteid" => $product_edit["websiteid"],
                            "productname" => $prd["name"],
                            "websitename" => $st["websitename"],
                            "prcnt" => $prcnt
                        );
                    }
                }
                echo json_encode($question_data);
            }
            elseif ($q == "update"){
                if (isset($_POST["question_update"])){
                    $question_hidden = strip_tags(trim($_POST["question_hidden"]));
                    $question_name = strip_tags(trim($_POST["question_name"]));
                    $question_email = strip_tags(trim($_POST["question_email"]));
                    $question_time = strip_tags(trim($_POST["question_time"]));
                    $question_question = strip_tags(trim($_POST["question_question"]));
                    $question_answer = strip_tags(trim($_POST["question_answer"]));
                    $question_product = strip_tags(trim($_POST["question_product"]));
                    $question_website = strip_tags(trim($_POST["question_website"]));
                    $question_show = $_POST["question_show"];
                    $question_emailsent = $_POST["question_emailsent"];
                    if ($question_show == "on"){$question_show = "1";}else{$question_show = "0";}
                    if ($question_emailsent == "on"){$question_emailsent = "1";}else{$question_emailsent = "0";}
                    $asd = date("H:i:s");
                    $question_time = $question_time.$asd;

                    $question_time = strtotime($question_time);
                    $question_time = date("Y-m-d H:i:s",$question_time);

                    $upque = $db->query("update questions set productid='$question_product', name='$question_name', comment='$question_question', answer='$question_answer', tarih='$question_time', email='$question_email', `show`='$question_show', `isemailsent`='$question_emailsent', websiteid='$question_website' where id='$question_hidden'");
                    if ($upque){
                        setcookie('testcookie', "true|Question Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=questions");
                    }
                    else{
                        setcookie('testcookie', "false|Question Not Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=questions");
                    }
                }
            }
        }
        elseif ($process == "question_delete"){
            $queID = $_GET["queID"];
            $questionDelete = $db->query("delete from questions where id='$queID'");
            if ($questionDelete){
                setcookie('testcookie', "true|Question Deleted...", time() + 20, '/');
            }
            else{
                setcookie('testcookie', "false|Question Not Deleted...", time() + 20, '/');
            }
        }

    }

    elseif ($pg == "emailformats"){
        $process = $_GET["process"];
        $q = $_GET["q"];
        if ($process == "editemailformat"){
            if ($q == "get"){
                $frID = intval($_POST["frID"]);
                $query = "SELECT * FROM emailformats WHERE id=:id";
                $stmt = $db->prepare( $query );
                $stmt->execute(array(':id'=>$frID));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                echo json_encode($row);
            }
            elseif ($q == "update"){
                if (isset($_POST["updateEmailFormat"])){
                    $forwhat = strip_tags(trim($_POST["forwhat"]));
                    $subject = strip_tags(trim($_POST["subject"]));
                    $emailformatdescription = $_POST["emailformatdescription"];
                    $emailformatwebsite2 = strip_tags(trim($_POST["emailformatwebsite"]));
                    $emailformatid = $_POST["emailformatid"];

                    $upformat = $db->query("update emailformats set subject='$subject', emailtext='$emailformatdescription', forwhat='$forwhat', websiteid='$emailformatwebsite2' where id='$emailformatid'");
                    if ($upformat){
                        setcookie('testcookie', "true|Email Format Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=emailformats");
                    }
                    else{
                        setcookie('testcookie', "false|Email Format Not Updated...", time() + 20, '/');
                        header("Location: ../index.php?p=emailformats");
                    }
                }
            }
        }
        elseif ($process == "insert_emailformat"){
            if (isset($_POST["insertEmailFormat"])){
                $forwhat2 = strip_tags(trim($_POST["forwhat2"]));
                $subject2 = strip_tags(trim($_POST["subject2"]));
                $emailformatdescription2 = $_POST["emailformatdescription2"];
                $emailformatwebsite2 = strip_tags(trim($_POST["emailformatwebsite2"]));

                $saveformat = $db->query("insert into emailformats(subject, emailtext, forwhat, websiteid) values('$subject2','$emailformatdescription2','$forwhat2','$emailformatwebsite2')");
                if ($saveformat){
                    setcookie('testcookie', "true|Email Format Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=emailformats");
                }
                else{
                    setcookie('testcookie', "false|Email Format Not Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=emailformats");
                }
            }
        }
        elseif ($process == "delete_emailformat"){
            $frID = $_GET["frID"];
            $formatDelete = $db->query("delete from emailformats where id='$frID'");
            if ($formatDelete){
                setcookie('testcookie', "true|Email Format Deleted...", time() + 20, '/');
            }
            else{
                setcookie('testcookie', "false|Email Format Not Deleted...", time() + 20, '/');
            }
        }
    }

    elseif ($pg == "postcodes"){
        $process = $_GET["process"];
        if ($process == "insert_postcode"){
            if (isset($_POST["insertpostcode"])){
                $newpostcode = strtoupper(strip_tags(trim($_POST["newpostcode"])));
                $newzone = $_POST["newzone"];
                $newcourierzone = $_POST["newcourierzone"];
                $savepostcode = $db->query("insert into postcodes(`postcode`, `zone`, `zone2`) values('$newpostcode','$newzone','$newcourierzone')");
                if ($savepostcode){
                    setcookie('testcookie', "true|Postcode Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=postcodes");
                }
                else{
                    setcookie('testcookie', "false|Postcode Not Saved...", time() + 20, '/');
                    header("Location: ../index.php?p=postcodes");
                }
            }
        }
        elseif ($process == "postcodeUp"){
            if (isset($_POST["pst"]) && isset($_POST["id3"])){
                $pst = strtoupper(strip_tags(trim($_POST["pst"])));
                $id3 = strip_tags(trim($_POST["id3"]));
                $uppost = $db->query("update postcodes set postcode='$pst' where id='$id3'");
            }
        }
        elseif ($process == "zoneUp"){
            if (isset($_POST["znid"]) && isset($_POST["id"])){
                $znid = strip_tags(trim($_POST["znid"]));
                $id = strip_tags(trim($_POST["id"]));
                $uppost = $db->query("update postcodes set zone='$znid' where id='$id'");
            }
        }
        elseif ($process == "zoneUp2"){
            if (isset($_POST["znid2"]) && isset($_POST["id2"])){
                $znid2 = strip_tags(trim($_POST["znid2"]));
                $id2 = strip_tags(trim($_POST["id2"]));
                $uppost = $db->query("update postcodes set zone2='$znid2' where id='$id2'");
            }
        }
        elseif ($process == "delete_postcode"){
            $pstID = $_GET["pstID"];
            $postcodeDelete = $db->query("delete from postcodes where id='$pstID'");
            if ($postcodeDelete){
                setcookie('testcookie', "true|Postcode Deleted...", time() + 20, '/');
            }
            else{
                setcookie('testcookie', "false|Postcode Not Deleted...", time() + 20, '/');
            }
        }
    }

    elseif ($pg == "zones"){
        $process = $_GET["process"];
        if ($process == "insert_zone"){
            $economy = strip_tags(trim($_POST["economy"]));
            $nextday = strip_tags(trim($_POST["nextday"]));
            $saturdayam = strip_tags(trim($_POST["saturdayam"]));
            $saturdaypm = strip_tags(trim($_POST["saturdayam"]));
            $low = strip_tags(trim($_POST["low"]));
            $ampm = strip_tags(trim($_POST["ampm"]));
            $savezone = $db->query("insert into zones(economy, nextday, ampm, saturdayam, saturdaypm, low) values('$economy','$nextday','$ampm','$saturdayam','$saturdaypm','$low')");
            if ($savezone){
                setcookie('testcookie', "true|Zones Saved...", time() + 20, '/');
                header("Location: ../index.php?p=deliverysettings");
            }
            else{
                setcookie('testcookie', "false|Zones Not Saved...", time() + 20, '/');
                header("Location: ../index.php?p=deliverysettings");
            }
        }
        elseif ($process == "delete_zone"){
            $znID = $_GET["znID"];
            $zoneDelete = $db->query("delete from zones where id='$znID'");
            if ($zoneDelete){
                //setcookie('testcookie', "true|Zone Deleted...", time() + 20, '/');
                echo 1;
            }
            else{
                //setcookie('testcookie', "false|Zone Not Deleted...", time() + 20, '/');
                echo 0;
            }
        }
        elseif ($process == "zonelowpriceUp"){
            if (isset($_POST["low"]) && isset($_POST["zoneid"])){
                $low = strip_tags(trim($_POST["low"]));
                $zoneid = strip_tags(trim($_POST["zoneid"]));
                $upzone = $db->query("update zones set low='$low' where id='$zoneid'");
            }
        }
        elseif ($process == "zoneeconomypriceUp"){
            if (isset($_POST["economy"]) && isset($_POST["zoneid2"])){
                $economy = strip_tags(trim($_POST["economy"]));
                $zoneid2 = strip_tags(trim($_POST["zoneid2"]));
                $upzone = $db->query("update zones set economy='$economy' where id='$zoneid2'");
            }
        }
        elseif ($process == "zonenextdaypriceUp"){
            if (isset($_POST["nextday"]) && isset($_POST["zoneid3"])){
                $nextday = strip_tags(trim($_POST["nextday"]));
                $zoneid3 = strip_tags(trim($_POST["zoneid3"]));
                $upzone = $db->query("update zones set nextday='$nextday' where id='$zoneid3'");
            }
        }
        elseif ($process == "zoneampmpriceUp"){
            if (isset($_POST["ampm"]) && isset($_POST["zoneid4"])){
                $ampm = strip_tags(trim($_POST["ampm"]));
                $zoneid4 = strip_tags(trim($_POST["zoneid4"]));
                $upzone = $db->query("update zones set ampm='$ampm' where id='$zoneid4'");
            }
        }
        elseif ($process == "zonesaturdayampriceUp"){
            if (isset($_POST["saturdayam"]) && isset($_POST["zoneid5"])){
                $saturdayam = strip_tags(trim($_POST["saturdayam"]));
                $zoneid5 = strip_tags(trim($_POST["zoneid5"]));
                $upzone = $db->query("update zones set saturdayam='$saturdayam' where id='$zoneid5'");
            }
        }
        elseif ($process == "zonesaturdaypmpriceUp"){
            if (isset($_POST["saturdaypm"]) && isset($_POST["zoneid6"])){
                $saturdaypm = strip_tags(trim($_POST["saturdaypm"]));
                $zoneid6 = strip_tags(trim($_POST["zoneid6"]));
                $upzone = $db->query("update zones set saturdaypm='$saturdaypm' where id='$zoneid6'");
            }
        }
        elseif ($process == "insert_courierzone"){
            $cou = number_format($_POST["cou"],0);
            $savecourier = $db->query("insert into zones_courier(price) values('$cou')");
            if ($savecourier){
                $allcouriers = $db->query("select * from zones_courier");
                foreach($allcouriers as $courier){
                    ?>
                    <tr id="zncourier_<?php echo $courier["id"]; ?>">
                        <td> <?php echo $courier["id"]; ?> </td>
                        <td>
                            <input type="text" name="zonecourierprice<?php echo $courier["id"];  ?>" id="zonecourierprice<?php echo $courier["id"];  ?>" class="form-control" onchange="zonecourierpriceUp(this.value,<?php echo $courier["id"]; ?>)" value="<?php echo number_format($courier["price"],2); ?>" />
                        </td>
                        <td>
                            <a id="courierzone_<?php echo $courier["id"]; ?>" class="delete_courierzone">
                                <button class="pull-right btn btn-circle mr-lg">
                                    <i class="fa fa-trash" aria-hidden="false"></i>
                                </button>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            }
        }
        elseif ($process == "delete_courierzone"){
            $couID = $_GET["couID"];
            $zonecourierDelete = $db->query("delete from zones_courier where id='$couID'");
            if ($zonecourierDelete){
                //setcookie('testcookie', "true|Courier Zone Deleted...", time() + 20, '/');
                echo 1;
            }
            else{
                //setcookie('testcookie', "false|Courier Zone Not Deleted...", time() + 20, '/');
                echo 0;
            }
        }
        elseif ($process == "courierUp"){
            if (isset($_POST["couprice"]) && isset($_POST["couid"])){
                $couprice = strip_tags(trim($_POST["couprice"]));
                $couid = strip_tags(trim($_POST["couid"]));
                $upzonecourier = $db->query("update zones_courier set price='$couprice' where id='$couid'");
            }
        }
        elseif ($process == "insert_royalmail"){
            $royal = number_format($_POST["royal"],0);
            $saveroyal = $db->query("insert into zones_royalmail(price) values('$royal')");
            if ($saveroyal){
                $allroyal = $db->query("select * from zones_royalmail");
                foreach($allroyal as $royal){
                    ?>
                    <tr id="royalmail_<?php echo $royal["id"]; ?>">
                        <td> <?php echo $royal["id"]; ?> </td>
                        <td>
                            <input type="text" name="royalmailprice<?php echo $royal["id"];  ?>" id="royalmailprice<?php echo $royal["id"]; ?>" class="form-control" onchange="zoneroyalmailUp(this.value,<?php echo $royal["id"]; ?>)" value="<?php echo number_format($royal["price"],2); ?>" />
                        </td>
                        <td>
                            <a id="mailroyal_<?php echo $royal["id"]; ?>" class="delete_royalmail">
                                <button class="pull-right btn btn-circle mr-lg">
                                    <i class="fa fa-trash" aria-hidden="false"></i>
                                </button>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            }
        }
        elseif ($process == "royalmailUp"){
            if (isset($_POST["royalprice"]) && isset($_POST["royalid"])){
                $royalprice = strip_tags(trim($_POST["royalprice"]));
                $royalid = strip_tags(trim($_POST["royalid"]));
                $uproyalmail = $db->query("update zones_royalmail set price='$royalprice' where id='$royalid'");
            }
        }
        elseif ($process == "delete_royalmail"){
            $royalID = $_GET["royalID"];
            $royalmailDelete = $db->query("delete from zones_royalmail where id='$royalID'");
            if ($royalmailDelete){
                //setcookie('testcookie', "true|Royal Mail Deleted...", time() + 20, '/');
                echo 1;
            }
            else{
                //setcookie('testcookie', "false|Royal Mail Not Deleted...", time() + 20, '/');
                echo 0;
            }
        }
    }

    else if ($pg == "select-size"){
        if (isset($_POST["get_option"])){
            $get_option = $_POST["get_option"];
            $size = $db->query("select * from products where pr_id='$get_option'")->fetch();
            $szid = $size["szid"];
            $prsz = $db->query("select * from product_sizes where productid='$szid' and `isdeleted`=0 and (`show`=1 or `isdummy`=1)");
            foreach ($prsz as $item){
                $id = $item["sizeid"];
                $sz = $db->query("select * from sizes where id='$id'")->fetch();
                ?>
                <option value="<?php echo $sz["id"]; ?>"><?php echo $sz["size"]; ?></option>
                <?php
            }
            /*
            $id = $id + 1;
            echo '<option value='.$id.'>Sample - £0</option>';
            */
        }
        elseif (isset($_POST["pr_option"])){
            $pr_option = $_POST["pr_option"];
            $size = $db->query("select * from products where pr_id='$pr_option'")->fetch();
            $szid = $size["szid"];
            $prsz2 = $db->query("select * from product_sizes where productid='$szid' and `isdeleted`=0 and (`show`=1 or `isdummy`=1)");
            foreach ($prsz2 as $item2){
                $id2 = $item2["sizeid"];
                $sz2 = $db->query("select * from sizes where id='$id2'")->fetch();
                ?>
                <option value="<?php echo $sz2["id"]; ?>"><?php echo $sz2["size"]; ?></option>
                <?php
            }
            /*
            $id2 = $id2 + 1;
            echo '<option value='.$id2.'>Sample - £0</option>';
            */
        }
        elseif (isset($_POST["webID"])){
            $webid = $_POST["webID"];
            $prlist = $db->query("select * from products where showsite=1 and websiteid='$webid' order by name asc");
            foreach ($prlist as $item){
                ?>
                <option value="<?php echo $item["pr_id"]; ?>"><?php echo $item["name"]; ?></option>
                <?php
            }
        }
    }

    elseif ($pg == "select-company"){
        $cmpnyid = $_POST["cmpnyid"];
        $orderid = $_POST["id"];
        $savecmpny = $db->query("update orders set companyid='$cmpnyid' where id='$orderid'");
        $addressSelect = $db->query("select * from company_address where companyid='$cmpnyid' and `isdeleted`<>1");
        foreach ($addressSelect as $address) {
            if ($address["companyid"] != ""){
                if ($address["ismain"] == 1){
                    $ismain = "Main";
                }
                ?>
                <option value="<?php echo $address["id"]; ?>"><?php echo $address["addressname"]." - ".$address["house"]." - ".$address["street"]." - ".$address["city"].$address["county"].$address["postcode"]." - ".$address["country"]."  ".$ismain; ?></option>
                <?php
            }
        }
    }

    elseif ($pg == "addressUp"){
        $IDorder = $_POST["IDorder"];
        $IDaddress = $_POST["IDaddress"];
        $upadress = $db->query("update orders set addressid='$IDaddress' where id='$IDorder'");
        if ($upadress->rowCount()){
            setcookie('testcookie', "true|Address Updated...", time() + 20, '/');
        }
        else{
            setcookie('testcookie', "false|Address Not Updated...", time() + 20, '/');
        }
    }
    elseif ($pg == "ShipaddressUp"){
        $orderID = $_POST["orderID"];
        $IDShipaddress = $_POST["IDShipaddress"];
        $upadress = $db->query("update orders set shiptoaddressid='$IDShipaddress' where id='$orderID'");
        if ($upadress->rowCount()){
            setcookie('testcookie', "true|Ship Address Updated...", time() + 20, '/');
        }
        else{
            setcookie('testcookie', "false|Ship Address Not Updated...", time() + 20, '/');
        }
    }
    elseif ($pg == "ShipviaUp"){
        $orderID = $_POST["orderID"];
        $shipviaID = $_POST["shipviaID"];
        $upshipvia = $db->query("update orders set shipviaid='$shipviaID' where id='$orderID'");
        if ($upshipvia->rowCount()){
            setcookie('testcookie', "true|ShipVia Updated...", time() + 20, '/');
        }
        else{
            setcookie('testcookie', "false|ShipVia Not Updated...", time() + 20, '/');
        }
    }
}
else{
    if ($pg == "login"){
        if (isset($_POST["login_username"]) && isset($_POST["login_password"])){
            $login_username = strip_tags(trim($_POST["login_username"]));
            $login_password = md5(sha1(strip_tags(trim($_POST["login_password"]))));
            $userSelect = $db->query("select * from panelusers where username='$login_username' and password='$login_password'");

            if ($userSelect->rowCount()){
                foreach ($userSelect as $user){
                    //$_SESSION["admin"] = $user["username"];
                    $_SESSION["admin"] = array(
                        "id"           => $user["id"],
                        "username"     => $user["username"],
                        "email"        => $user["email"],
                        "usertype"     => $user["usertype"],
                        "websiteid"    => $user["websiteid"]
                    );
                    $date = date("Y-m-d H:i:s");
                    $login = $db->query("update panelusers set lastlogin='$date' where id='".$user["id"]."'");
                    setcookie('testcookie', "true|Login Correctly...", time() + 20, '/');
                    header("Location: ../../index.php");
                }
            }
            else{
                setcookie('testcookie', "false|Login Failed...", time() + 20, '/');
                header("Location: ../../index.php");
            }

        }

    }
}

function category($db, $id = 0, $string = 0, $top_id, $siteID){
    $_SESSION["admin"]["websiteid"] = $siteID;
    $cat_query = $db->query("select * from categories where category_top_id='$id' and websiteid='".$_SESSION["admin"]["websiteid"]."'");
    if ($cat_query->rowCount()){
        foreach ($cat_query as $cat){
            echo '<option value="'.$cat["id"].'">'.str_repeat(" --- ", $string).$cat["category"].'</option>';
            //echo $row["id"] == $top_id ? " selected " : null;
            //echo 'value="'.$cat["id"].'">'.str_repeat(" --- ", $string).$cat["category"].'</option>';
            category($db, $cat["id"], $string + 1, $top_id, $siteID);
        }
    }
    else{
        return false;
    }
}

function comboAdd($type,$menuname){
    global $db;
    $peradd = $db->query("insert into combos (name,menu) values('$menuname','$type')");
    if ($peradd->rowCount()){
        echo 1;
    }else{
        echo 0;
    }
}

function comboGet($comboType){
    ?>
    <form action="" method="post" id="<? echo $comboType; ?>Form">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Seq#</th>
                <th>Value</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            global $db;
            $cmpSelect = $db->query("select * from combos where menu='$comboType'");
            if ($cmpSelect->rowCount()){

                foreach ($cmpSelect as $cmp) {
                    ?>
                    <tr>
                        <td><?php echo $cmp["id"]; ?></td>
                        <td><input onchange="updateCombo('<?php echo $cmp["id"]; ?>', $(this).val(),'name');" type="text" name="comboname<?php echo $cmp["id"]; ?>" class="form-control" value="<?php echo strtoupper($cmp["name"]); ?>"></td>
                        <td><input onchange="updateCombo('<?php echo $cmp["id"]; ?>', $(this).val(),'sira');" type="text" name="combosira<?php echo $cmp["id"]; ?>" class="form-control" value="<?php echo $cmp["sira"]; ?>"></td>
                        <td><input onchange="updateCombo('<?php echo $cmp["id"]; ?>', $(this).val(),'amount');" type="text" name="comboamount<?php echo $cmp["id"]; ?>" class="form-control" value="<?php echo $cmp["amount"]; ?>"></td>
                        <td><a href="javascript:;" class="btn red" onclick="deleteCombo(<?php echo $cmp["id"]; ?>,<?php echo '\''.$cmp["menu"].'\''; ?>)"><i class="fa fa-trash"></i></a></td>
                    </tr>
                    <?php
                }
            }
            ?>
            </tbody>
        </table>
    </form>

    <?php
}

function comboSelect($type){
    global $db;
    $companyType = $db->query("select * from combos where menu='$type'");

    foreach ($companyType as $getCombo){
        echo '<option value="'.$getCombo["id"].'">'.$getCombo["name"].'</option>';
    }
}

function customerSelect(){
    global $db;
    $customerSelect = $db->query("select * from companies where iscustomer='1' AND isinactive='0' ORDER BY companyname, firstname");
    echo '<option value="0" disabled selected>Select Customer</option>';
    foreach ($customerSelect as $getCustomer){
        if($getCustomer["companyname"]==""){
            $name = $getCustomer["firstname"];
        }
        else{
            $name = $getCustomer["companyname"];
        }
        echo'<option value="'.$getCustomer["pr_id"].'">'.$name.'</option>';
    }
}

function getAddressDetails($pr_id){
    global $db;
    $address = $db->query("select * from company_address WHERE id='$pr_id'")->fetch();
    /*$getAddress = array();
    $getAddress["getAdressDetail"] = array(
            "addressName"   =>  $address["addressname"],
            "companyid"     =>  $address["companyid"],
            "house"         =>  $address["house"],
            "street"        =>  $address["street"],
            "city"          =>  $address["city"],
            "county"        =>  $address["county"],
            "postcode"      =>  $address["postcode"],
            "country"       =>  $address["country"],
            "ismain"        =>  $address["ismain"]
    );*/
    //echo json_encode($getAddress);
    echo'<div class="row">
        <div class="col-md-2 text-right">Address</div>
        <div class="col-md-9">
            <div>'.$address["addressname"].' '.$address["house"].' '.$address["street"].' '.$address["city"].' '.$address["county"].' '.$address["country"].' '.$address["postcode"].'</div>
        </div>
    </div>';
    ?>

    <?php
}

function permalink($str, $options = array()){
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => true
    );
    $options = array_merge($defaults, $options);
    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',
        // Latin symbols
        '©' => '(c)',
        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',
        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',
        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',
        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    $str = trim($str, $options['delimiter']);
    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

function connect($link){
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$link);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    $operation = curl_exec($ch);
    curl_close($ch);
    return $operation;
}

?>

