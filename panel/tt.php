<?php
error_reporting(0);
try{
    //$db = new PDO('odbc:DRIVER={SQL Server};SERVER=IONICSTONE-PC\SQLEXPRESS;Database=NewConcept;UID=sa;PWD=2017');
    $db = new PDO("mysql:host=localhost;dbname=newconcept", "root", "root");
}
catch(PDOException $e){
    echo $e->getMessage();
}

$allproduct = array();
$allimg = array();
$prd = array();
$imgs = array();
$allprdct = $db->query("select * from products where websiteid='1'");
$countpr = $allprdct->rowCount();

$pr_rand = $db->query("SELECT * FROM products ORDER BY RAND() LIMIT 8");
$r=0;
$cnt = $pr_rand->rowCount();
foreach ($pr_rand as $item) {
    $id = $item["pr_id"];
    $prd["pr"][$r] = array(
        "pr_id"   =>  $item["pr_id"],
        "name"    =>  $item["name"]
     );
    $allimage = $db->query("select * from images where pr_id='$id'");
    $countimg = $allimage->rowCount();
    if($countimg){
        foreach ($allimage as $img) {
            $imgs["img"][$r] = array(
                "id"        => $img["id"],
                "pr_id"     => $img["pr_id"],
                "imagename" => $img["imagename"]
            );
            //echo $item["pr_id"]."=>".$item["name"]."=>".$img["imagename"]."<br>";
        }
        $r++;
    }
}
$rand_pr = rand(0,$r-1);
echo "<pre>";
print_r($prd);
print_r($rand_pr);
echo "</pre>";
if($countpr){
    $x=0;
    foreach ($allprdct as $item) {
        $allproduct["pr"][$x] = array(
            "pr_id"   =>   $item["pr_id"],
            "name"    =>   $item["name"],
            "countpr" =>   $countpr
        );
        $id = $allproduct["pr"][$x]["pr_id"];
        $allimage = $db->query("select * from images where pr_id='$id'");
        $countimg = $allimage->rowCount();
        if($countimg){
            foreach ($allimage as $img) {
                $allimg["img"][$x] = array(
                    "pr_id"     =>  $img["pr_id"],
                    "imagename" =>  $img["imagename"],
                    "countimg"  =>  $countimg
                );
                $x++;
            }
        }
    }
}
$rand_keys = rand(0,$countpr);

/*
echo $allproduct["pr"][$rand_keys]["pr_id"]."<br>";
echo $allproduct["pr"][$rand_keys]["name"]."<br>";
echo $allproduct["pr"][$rand_keys]["countpr"]."<br>";
echo $allimg["img"][$rand_keys]["pr_id"]."<br>";
echo $allimg["img"][$rand_keys]["imagename"]."<br>";
echo $allimg["img"][$rand_keys]["countimg"]."<br>";
*/
?>

<div class="modal fade bs-modal-lg" id="addCustomer" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add New Customer</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post" class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Customer Name</label>
                            <div class="col-md-4">
                                <input type="text" name="customerName" id="customerName" class="form-control input-circle" placeholder="Customer Name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Contact Name</label>
                            <div class="col-md-4">
                                <input type="text" name="contactName" id="contactName" class="form-control input-circle" placeholder="First Name" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="contactName2" id="contactName2" class="form-control input-circle" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Tel</label>
                            <div class="col-md-4">
                                <input type="tel" name="tel" id="tel" class="form-control input-circle" placeholder="Telephone" >
                            </div>
                            <div class="col-md-4">
                                <input type="tel" name="tel2" id="tel2" class="form-control input-circle" placeholder="Telephone 2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Fax</label>
                            <div class="col-md-4">
                                <input type="tel" name="fax" id="fax" class="form-control input-circle" placeholder="Fax" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">VAT No</label>
                            <div class="col-md-4">
                                <input type="text" name="vat" id="vat" class="form-control input-circle" placeholder="VAT No" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Email</label>
                            <div class="col-md-4">
                                <input type="text" name="mail" id="mail" class="form-control input-circle" placeholder="Email" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Web</label>
                            <div class="col-md-4">
                                <input type="text" name="web" id="web" class="form-control input-circle" placeholder="Web" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Company Type</label>
                            <div class="col-md-4">
                                <select id="comboSelectcompanyType" class="form-control input-circle">
                                    <?php
                                    comboSelect("companyType") ;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1"  style="padding-left: 0">
                                <div class="col-md-1 form-control noborder circle">
                                    <a href="#companytype" data-toggle="modal" class="popup1" style="border: none; padding-left: 0" title="New"><i class="fa fa-plus"></i></a>
                                    <a href="javascript:;" data-toggle="modal" class="popup1" style="border: none; padding-left: 5px" title="Refresh" onclick="comboSelect('companyType')"><i class="fa fa-refresh"></i></a></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">First point of contact</label>
                            <div class="col-md-4">
                                <select id="comboSelectfpoc" class="form-control input-circle" >
                                    <?php
                                    comboSelect("fpoc") ;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1"  style="padding-left: 0">
                                <div class="col-md-1 form-control noborder circle">
                                    <a href="#newfpoc" data-toggle="modal" class="popup1" style="border: none; padding-left: 0" title="New"><i class="fa fa-plus"></i></a>
                                    <a href="javascript:;" data-toggle="modal" class="popup1" style="border: none; padding-left: 5px" title="Refresh" onclick="comboSelect('fpoc')"><i class="fa fa-refresh"></i></a></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Payment Method</label>
                            <div class="col-md-4">
                                <select id="comboSelectpaymentterm" class="form-control input-circle">
                                    <?php
                                    comboSelect("paymentterm") ;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1"  style="padding-left: 0">
                                <div class="col-md-1 form-control noborder circle">
                                    <a href="#paymentterm" data-toggle="modal" class="popup1" style="border: none; padding-left: 0" title="New"><i class="fa fa-plus"></i></a>
                                    <a href="javascript:;" data-toggle="modal" class="popup1" style="border: none; padding-left: 5px" title="Refresh" onclick="comboSelect('paymentterm')"><i class="fa fa-refresh"></i></a></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Opening Balance</label>
                            <div class="col-md-4">
                                <input type="text" name="balance" id="balance" class="form-control input-circle" placeholder="Opening Balance" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Is Supplier</label>
                            <div class="col-md-1">
                                <div class="md-checkbox" style="margin-top: 5px;">
                                    <input type="checkbox" id="isSupplier" name="isSupplier" class="md-check">
                                    <label for="isSupplier">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                            </div>
                            <label class="col-md-2 control-label">Is Customer</label>
                            <div class="col-md-2">
                                <div class="md-checkbox" style="margin-top: 5px;">
                                    <input type="checkbox" id="isCustomer" name="isCustomer" class="md-check" checked>
                                    <label for="isCustomer">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Notes</label>
                            <div class="col-md-6">
                                <textarea name="note" id="note" class="form-control circle" rows="6"></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-4">
                                <h4>Adress</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Location Name</label>
                            <div class="col-md-4">
                                <input type="text" name="locationName" id="locationName" class="form-control input-circle" placeholder="Location Name" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">House</label>
                            <div class="col-md-4">
                                <input type="text" name="house" id="house" class="form-control input-circle" placeholder="House" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Street</label>
                            <div class="col-md-4">
                                <input type="text" name="street" id="street" class="form-control input-circle" placeholder="Street" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">City</label>
                            <div class="col-md-4">
                                <input type="text" name="city" id="city" class="form-control input-circle" placeholder="City" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">County</label>
                            <div class="col-md-4">
                                <input type="text" name="county" id="county" class="form-control input-circle" placeholder="County" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Postcode</label>
                            <div class="col-md-4">
                                <input type="text" name="postcode" id="postcode" class="form-control input-circle" placeholder="Postcode" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Country</label>
                            <div class="col-md-4">
                                <input type="text" name="country" id="country" class="form-control input-circle" placeholder="Country" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Is Main</label>
                            <div class="col-md-2">
                                <div class="md-checkbox" style="margin-top: 5px;">
                                    <input type="checkbox" id="isMain" name="isMain" class="md-check" checked>
                                    <label for="isMain">
                                        <span class="inc"></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            <a href="javascript:void(0)" class="btn green" id="customerAdd" name="customerAdd" onclick="addCustomer()">Add Customer</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bs-modal-lg" id="newOrder" role="basic" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">New Order</h4>
            </div>
            <div class="modal-body">
                <form action="include/operations.php?pg=customer&process=addcompany" method="post" class="form-horizontal">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Select Customer</label>
                            <div class="col-md-4">
                                <select id="selectCustomer" class="form-control input-circle select2me" onchange="getAddressNames()">
                                    <?php
                                    customerSelect();
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1"  style="padding-left: 0">
                                <div class="col-md-1 form-control noborder circle">
                                    <a href="#addCustomer" data-toggle="modal" class="popup1" style="border: none; padding-left: 0" title="New Customer"><i class="fa fa-plus"></i></a>
                                    <a href="javascript:void(0)" data-toggle="modal" class="popup1" style="border: none; padding-left: 5px" title="Refresh" onclick="getCustomers()"><i class="fa fa-refresh"></i></a></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Select Address</label>
                            <div class="col-md-4">
                                <select id="selectAddress" class="form-control" onchange="getAddressDetails()">

                                </select>
                            </div>
                            <div class="col-md-1"  style="padding-left: 0">
                                <div class="col-md-1 form-control noborder circle">
                                    <a href="#addnewaddress" data-toggle="modal" class="popup1" style="border: none; padding-left: 0" title="New Address"><i class="fa fa-plus"></i></a>
                                    <a href="javascript:void(0)" data-toggle="modal" class="popup1" style="border: none; padding-left: 5px" title="Refresh" onclick=""><i class="fa fa-refresh"></i></a></div>
                            </div>
                        </div>
                        <div class="addressDetails">
                            <!-- Adress Details Here -->
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Select Website</label>
                            <div class="col-md-4">
                                <select id="selectWebsite" class="form-control">
                                    <?php
                                    $getWebsite = $db->query("select * from websites");
                                    foreach ($getWebsite as $getWebsites){
                                        echo '<option value="'.$getWebsites["websiteid"].'">'.$getWebsites["websitename"].'</option>';
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Notes</label>
                            <div class="col-md-6">
                                <textarea name="addNotes" id="addNotes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>

                            <input type="submit" class="btn green" id="customerAdd" name="customerAdd" value="Add Customer">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery("#reviewscoukWidget").reviewscouk(reviewscoukTemplate); // reviews.co.uk sitesinden çekilecek veriler bunlar....
</script>

<!-- Reviews için gereken div. reviews.co.uk sitesinden çekilmezse eğer bu kullanılabilir. Kendi db mizdeki reviews tablosundan çekilecekler....  -->
<?php $avg = $db->query("select AVG(rating) as average from reviews where websiteid='$site_name'")->fetch(); ?>
<?php $count = $db->query("select COUNT(rating) as cnt from reviews where websiteid='$site_name'")->fetch(); ?>
<div class="reviewscouk_widget" style="color: rgb(0, 0, 0); border: 1px solid rgb(209, 209, 209); border-radius: 5px; font-size: 12px; box-shadow: none; overflow: hidden; background-color: rgb(255, 255, 255);">
    <div class="reviewscouk_headercontainer" style="width: 100%; position: relative; color: rgb(255, 255, 255); text-align: center; border-bottom-width: 3px; border-bottom-style: solid; border-bottom-color: rgba(0, 0, 0, 0.0980392); background: linear-gradient(135deg, rgb(255, 255, 255) 0%, rgb(255, 255, 255) 100%) rgb(255, 255, 255);">
        <div class="reviewscouk_header" style="font-size: 20px; line-height: 30px; text-transform: uppercase; text-align: center; padding: 5px 10px 0px; text-shadow: rgba(0, 0, 0, 0.2) 0px 1px 0px;">
            <?php
            if ($avg["average"] > 4.5 || $avg["average"] == 5){
                ?>
                <div class="reviewscouk_score" style="color: rgb(65, 100, 117); text-shadow: rgba(0, 0, 0, 0) 0px 0px 1px;">Excellent</div>
                <div class="reviewscouk_rating" style="width: 100%; text-align: center; font-size: 22px; color: rgb(248, 255, 59); line-height: 24px;">

                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                </div>
                <?php
            }
            elseif ($avg["average"] > 4 || $avg["average"] < 4.5 || $avg["average"] == 4.5) {
                ?>
                <div class="reviewscouk_score" style="color: rgb(65, 100, 117); text-shadow: rgba(0, 0, 0, 0) 0px 0px 1px;">Very Good</div>
                <div class="reviewscouk_rating" style="width: 100%; text-align: center; font-size: 22px; color: rgb(248, 255, 59); line-height: 24px;">

                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-half-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                </div>
                <?php
            }
            elseif ($avg["average"] == 4 || $avg["average"] > 3.5 || $avg["average"] < 4){
                ?>
                <div class="reviewscouk_score" style="color: rgb(65, 100, 117); text-shadow: rgba(0, 0, 0, 0) 0px 0px 1px;">Very Good</div>
                <div class="reviewscouk_rating" style="width: 100%; text-align: center; font-size: 22px; color: rgb(248, 255, 59); line-height: 24px;">
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                </div>
                <?php
            }
            elseif ($avg["average"] > 3 || $avg["average"] < 3.5 || $avg["average"] == 3.5){
                ?>
                <div class="reviewscouk_score" style="color: rgb(65, 100, 117); text-shadow: rgba(0, 0, 0, 0) 0px 0px 1px;">Average</div>
                <div class="reviewscouk_rating" style="width: 100%; text-align: center; font-size: 22px; color: rgb(248, 255, 59); line-height: 24px;">
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-half-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                </div>
                <?php
            }
            elseif ($avg["average"] < 3 || $avg["average"] > 2.5 ||$avg["average"] == 3){
                ?>
                <div class="reviewscouk_score" style="color: rgb(65, 100, 117); text-shadow: rgba(0, 0, 0, 0) 0px 0px 1px;">Average</div>
                <div class="reviewscouk_rating" style="width: 100%; text-align: center; font-size: 22px; color: rgb(248, 255, 59); line-height: 24px;">
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                </div>
                <?php
            }
            elseif ($avg["average"] < 2.5 || $avg["average"] > 2 || $avg["average"] == 2.5){
                ?>
                <div class="reviewscouk_score" style="color: rgb(65, 100, 117); text-shadow: rgba(0, 0, 0, 0) 0px 0px 1px;">Fair</div>
                <div class="reviewscouk_rating" style="width: 100%; text-align: center; font-size: 22px; color: rgb(248, 255, 59); line-height: 24px;">
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-half-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                </div>
                <?php
            }
            elseif ($avg["average"] < 2 || $avg["average"] > 1.5 || $avg["average"] == 2){
                ?>
                <div class="reviewscouk_score" style="color: rgb(65, 100, 117); text-shadow: rgba(0, 0, 0, 0) 0px 0px 1px;">Fair</div>
                <div class="reviewscouk_rating" style="width: 100%; text-align: center; font-size: 22px; color: rgb(248, 255, 59); line-height: 24px;">
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                </div>
                <?php
            }
            elseif ($avg["average"] < 1.5 || $avg["average"] > 1 || $avg["average"] == 1.5){
                ?>
                <div class="reviewscouk_score" style="color: rgb(65, 100, 117); text-shadow: rgba(0, 0, 0, 0) 0px 0px 1px;">Fair</div>
                <div class="reviewscouk_rating" style="width: 100%; text-align: center; font-size: 22px; color: rgb(248, 255, 59); line-height: 24px;">
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                    <span class="icon-widgets-half-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                </div>
                <?php
            }
            elseif ($avg["average"] < 1 || $avg["average"] == 1){
                ?>
                <div class="reviewscouk_score" style="color: rgb(65, 100, 117); text-shadow: rgba(0, 0, 0, 0) 0px 0px 1px;">Poor</div>
                <div class="reviewscouk_rating" style="width: 100%; text-align: center; font-size: 22px; color: rgb(248, 255, 59); line-height: 24px;">
                    <span class="icon-widgets-full-star-01" style="margin: 0px 1px; padding: 1px 4px; border-width: 0px; color: rgb(96, 125, 139); border-radius: 0px; box-shadow: rgba(0, 0, 0, 0) 0px 1px 1px 0px; text-shadow: rgba(0, 0, 0, 0) 0px 0px 2px;"></span>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="reviewscouk_subheader" style="padding: 8px 0px 5px; font-size: 12px; line-height: 20px; text-transform: none; text-shadow: rgba(0, 0, 0, 0) 0px 0px 1px; color: rgb(65, 100, 117); background: rgba(0, 0, 0, 0);">
            <div class="reviewscouk_score" style="width: 50%; float: none; text-align: center; text-shadow: rgba(0, 0, 0, 0.2) 0px 1px 0px; margin: auto; font-size: 14px; line-height: 17px;">
                <b><?php echo number_format($avg["average"],2); ?></b>
                Average
            </div>
            <div class="reviewscouk_count" style="width: 50%; float: none; text-align: center; text-shadow: rgba(0, 0, 0, 0.2) 0px 1px 0px; margin: auto; font-size: 14px; line-height: 17px;">
                <b><?php echo $count["cnt"]; ?></b>
                Reviews
            </div>
        </div>
        <a border="0" target="_blank" href="http://www.reviews.co.uk/company-reviews/store/stone-deals" style="text-decoration: none; float: none;">
            <div class="icon-widgets-logo-01" style="font-size: 30px; color: rgb(140, 165, 179); text-shadow: rgba(0, 0, 0, 0.2) 0px 1px 1px; padding: 8px 10px;"></div>
        </a>
    </div>
</div>


<div class=" cbp-caption-active cbp-caption-overlayBottomReveal cbp-ready">
    <div class="cbp-item" >
        <div class="cbp-item-wrapper">
            <div class="cbp-caption">
                <div class="cbp-caption-defaultWrap">
                    <?php
                    if ($products["soffer"] == 1){
                        ?>

                        <div class="pcorner-icon">
                            <p style="margin-top: 0px;">Special</p>
                            <p style="margin-top: -20px; margin-left: 35px;">Offer</p>
                        </div>
                        <?php
                    }
                    elseif ($products["new"] == 1){
                        ?>
                        <div class="pcorner-icon pcorner-icon-new" id="new">
                            <p>NEW</p>
                        </div>
                        <?php
                    }
                    ?>
                    <img src="img/products/<?php echo $img["imagename"]; ?>" alt="" class="img-responsive" style="width: 560px; height: 420px;">
                </div>
                <div class="cbp-caption-activeWrap">
                    <div class="cbp-l-caption-alignCenter">
                        <div class="cbp-l-caption-body">
                            <a href="img/products/<?php echo $img["imagename"]; ?>" class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase" data-title="<?php echo $img["imagename"]; ?>">
                                <i class="fa fa-search-plus" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="well-custom bgnd-white">
    <div class="row">
        <div class="col-md-3 text-center">
            <div class="text-center">
                <h2 style="margin: 0;">
                    <i class="fa fa-star" aria-hidden="true" style="color: #12CF6C"></i>
                    <b> REVIEWS </b>
                </h2>
            </div>
            <div class="text-center" style="color: #607D8B">
                <span><i class="fa fa-star" aria-hidden="true"></i></span>
                <span><i class="fa fa-star" aria-hidden="true"></i></span>
                <span><i class="fa fa-star" aria-hidden="true"></i></span>
                <span><i class="fa fa-star-half-o" aria-hidden="true"></i></span>
            </div>
            <div class="text-center">
                <span style="font-weight: bold">4.67</span> Average
                <span style="font-weight: bold">249</span> Reviews
            </div>
        </div>

        <div class="col-md-9">
            <div class="owl-carousel owl-theme stage-margin rounded-nav" data-plugin-options='{"margin": 10, "loop": true, "nav": true, "dots": false, "stagePadding": 40, "autoplay": false, "autoplayTimeout": 5000}'>
                <?php

                $prid = $_GET["prid"];
                $reviews = $db->query("select * from reviews where productid='$prid'");
                foreach ($reviews as $rev){
                    ?>
                    <div>
                        <div class="text-left">
                            <p>
                                <span style="font-weight: bold;"><?php echo $rev["name"]; ?></span>
                                <?php
                                for($i = 0; $i < $rev["rating"]; $i++){
                                    ?>
                                    <span style="color: #607D8B;"><i class="fa fa-star" aria-hidden="true"></i></span>
                                    <?php
                                }
                                ?>
                            </p>
                        </div>
                        <div class="text-left">
                            <p class="p-carousel">
                                <?php echo $rev["comment"]; ?>
                            </p>
                        </div>
                        <?php
                        $tarih1 = date("Y-m-d");
                        $tarih2 = $rev["tarih"];
                        $ilk = strtotime($tarih1);
                        $son = strtotime($tarih2);
                        $fark = ($ilk - $son) / 86400;
                        if ($fark == 7){
                            $day = 2;
                            $week = $day - 1;
                            ?>
                            <div>
                                <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$week." weeks ago"; ?></p>
                            </div>
                            <?php
                        }
                        elseif ($fark > 7){
                            $day++;
                            $week = $day - 1;
                            ?>
                            <div>
                                <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$week." weeks ago"; ?></p>
                            </div>
                            <?php
                            if ($fark == 7 * $week || $fark > 7 * $week){
                                $day++;
                                $week = $day - 1;
                                ?>
                                <div>
                                    <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$week." weeks ago"; ?></p>
                                </div>
                                <?php
                                if ($fark == 7 * $day || $day == 4){
                                    $day = $day + 4;
                                    $month = 1;
                                    ?>
                                    <div>
                                        <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$month." month ago"; ?></p>
                                    </div>
                                    <?php
                                    if ($fark == 7 * $day || $month == 1){
                                        $day = $day + 4;
                                        $month++;
                                        ?>
                                        <div>
                                            <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$month." months ago"; ?></p>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                        elseif ($fark == 0){
                            ?>
                            <div>
                                <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted today"; ?></p>
                            </div>
                            <?php
                        }
                        elseif ($fark > 0 || $fark < 7){
                            ?>
                            <div>
                                <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$fark." days ago"; ?></p>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>

        </div>

    </div>
</div>



<div class="well-custom bgnd-white">
    <div class="row">
        <div class="col-md-3 text-center">

            <div class="text-center">
                <h2 style="margin: 0;">
                    <i class="fa fa-star" aria-hidden="true" style="color: #12CF6C"></i>
                    <b> REVIEWS </b>
                </h2>
            </div>
            <div class="text-center" style="color: #607D8B">
                <span><i class="fa fa-star" aria-hidden="true"></i></span>
                <span><i class="fa fa-star" aria-hidden="true"></i></span>
                <span><i class="fa fa-star" aria-hidden="true"></i></span>
                <span><i class="fa fa-star-half-o" aria-hidden="true"></i></span>
            </div>
            <?php $avg = $db->query("select AVG(rating) as average from reviews where websiteid='$site_name'")->fetch(); ?>
            <?php $count = $db->query("select COUNT(rating) as cnt from reviews where websiteid='$site_name'")->fetch(); ?>
            <div class="text-center">
                <span style="font-weight: bold"><?php echo number_format($avg["average"],2); ?></span> Average
                <span style="font-weight: bold"><?php echo $count["cnt"]; ?></span> Reviews
            </div>

        </div>
        <div class="col-md-9">
            <div class="owl-carousel owl-theme stage-margin rounded-nav" data-plugin-options='{"margin": 10, "loop": true, "nav": true, "dots": false, "stagePadding": 40, "autoplay": false, "autoplayTimeout": 5000}'>
                <?php
                $rands = $db->query("select * from reviews where websiteid='$site_name' order by RAND() limit 10");
                foreach ($rands as $reviews){
                    ?>
                    <div>
                        <div class="text-left">
                            <p>
                                <span style="font-weight: bold;"><?php  echo $reviews["name"];    ?></span>
                                <?php  for($i = 0; $i < $reviews["rating"]; $i++){
                                    ?>
                                    <span style="color: #607D8B;"><i class="fa fa-star" aria-hidden="true"></i></span>
                                <?php }  ?>
                            </p>
                        </div>
                        <div class="text-left">
                            <p class="p-carousel">
                                <?php  echo $reviews["comment"];     ?>
                            </p>
                        </div>
                        <?php
                        $tarih1 = date("Y-m-d");
                        $tarih2 = $rev["tarih"];
                        $ilk = strtotime($tarih1);
                        $son = strtotime($tarih2);
                        $fark = ($ilk - $son) / 86400;
                        if ($fark == 7){
                            $day = 2;
                            $week = $day - 1;
                            ?>
                            <div>
                                <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$week." weeks ago"; ?></p>
                            </div>
                            <?php
                        }
                        elseif ($fark > 7){
                            $day++;
                            $week = $day - 1;
                            ?>
                            <div>
                                <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$week." weeks ago"; ?></p>
                            </div>
                            <?php
                            if ($fark == 7 * $week || $fark > 7 * $week){
                                $day++;
                                $week = $day - 1;
                                ?>
                                <div>
                                    <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$week." weeks ago"; ?></p>
                                </div>
                                <?php
                                if ($fark == 7 * $day || $day == 4){
                                    $day = $day + 4;
                                    $month = 1;
                                    ?>
                                    <div>
                                        <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$month." month ago"; ?></p>
                                    </div>
                                    <?php
                                    if ($fark == 7 * $day || $month == 1){
                                        $day = $day + 4;
                                        $month++;
                                        ?>
                                        <div>
                                            <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$month." months ago"; ?></p>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                        elseif ($fark == 0){
                            ?>
                            <div>
                                <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted today"; ?></p>
                            </div>
                            <?php
                        }
                        elseif ($fark > 0 || $fark < 7){
                            ?>
                            <div>
                                <p class="p-carousel text-right" style="font-size: 10px; color: #999;"><?php echo "Posted ".$fark." days ago"; ?></p>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-3">
        <div class="product-img-container">
            <a href="?p=product_details&prid=<?php echo $prd["pr"][$rand_pr]["pr_id"]; ?>&imgid=<?php echo $imgs["img"][$rand_pr]["id"];  ?>">
                <img src="img/products/<?php echo $imgs["img"][$rand_pr]["imagename"]; ?>" class="img-thumbnail img-responsive">
            </a>
        </div>
        <div class="text-center">
            <a href="?p=product_details&prid=<?php echo $prd["pr"][$rand_pr]["pr_id"]; ?>&imgid=<?php echo $imgs["img"][$rand_pr]["id"];  ?>">
                <?php echo $prd["pr"][$rand_pr]["name"]; $rand_pr2 = rand(0,$r-1); ?>
            </a>
        </div>
        <div class="text-center">
            <span class="price-text-small">from </span>
            <span class="price-text">£<?php echo $prd["pr"][$rand_pr]["fromprice"]; ?></span>
            <span class="price-text-small"> /m2 incl. VAT</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="product-img-container">
            <a href="?p=product_details&prid=<?php echo $prd["pr"][$rand_pr2]["pr_id"]; ?>&imgid=<?php echo $imgs["img"][$rand_pr2]["id"];  ?>">
                <img src="img/products/<?php echo $imgs["img"][$rand_pr2]["imagename"]; ?>" class="img-thumbnail img-responsive">
            </a>
        </div>
        <div class="text-center">
            <a href="?p=product_details&prid=<?php echo $prd["pr"][$rand_pr2]["pr_id"]; ?>&imgid=<?php echo $imgs["img"][$rand_pr2]["id"];  ?>">
                <?php echo $prd["pr"][$rand_pr2]["name"]; $rand_pr3 = rand(0,$r-1); ?>
            </a>
        </div>
        <div class="text-center">
            <span class="price-text-small">from </span>
            <span class="price-text">£<?php echo $prd["pr"][$rand_pr2]["fromprice"]; ?></span>
            <span class="price-text-small"> /m2 incl. VAT</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="product-img-container">
            <a href="?p=product_details&prid=<?php echo $prd["pr"][$rand_pr3]["pr_id"]; ?>&imgid=<?php echo $imgs["img"][$rand_pr3]["id"];  ?>">
                <img src="img/products/<?php echo $imgs["img"][$rand_pr3]["imagename"]; ?>" class="img-thumbnail img-responsive">
            </a>
        </div>
        <div class="text-center">
            <a href="?p=product_details&prid=<?php echo $prd["pr"][$rand_pr3]["pr_id"]; ?>&imgid=<?php echo $imgs["img"][$rand_pr3]["id"];  ?>">
                <?php echo $prd["pr"][$rand_pr3]["name"]; $rand_pr4 = rand(0,$r-1); ?>
            </a>
        </div>
        <div class="text-center">
            <span class="price-text-small">from </span>
            <span class="price-text">£<?php echo $prd["pr"][$rand_pr3]["fromprice"]; ?></span>
            <span class="price-text-small"> /m2 incl. VAT</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="product-img-container">
            <a href="?p=product_details&prid=<?php echo $prd["pr"][$rand_pr4]["pr_id"]; ?>&imgid=<?php echo $imgs["img"][$rand_pr4]["id"];  ?>">
                <img src="img/products/<?php echo $imgs["img"][$rand_pr4]["imagename"]; ?>" class="img-thumbnail img-responsive">
            </a>
        </div>
        <div class="text-center">
            <a href="?p=product_details&prid=<?php echo $prd["pr"][$rand_pr4]["pr_id"]; ?>&imgid=<?php echo $imgs["img"][$rand_pr4]["id"];  ?>">
                <?php echo $prd["pr"][$rand_pr4]["name"]; ?>
            </a>
        </div>
        <div class="text-center">
            <span class="price-text-small">from </span>
            <span class="price-text">£<?php echo $prd["pr"][$rand_pr4]["fromprice"]; ?></span>
            <span class="price-text-small"> /m2 incl. VAT</span>
        </div>
    </div>
</div>





<?php

if (isset($_POST["product_edit"])) {
    $product_img = array();
    $img_id = array();
    $pr_image = array();
    $productname2 = strip_tags(trim($_POST["productname2"]));
    $generalname2 = strip_tags(trim($_POST["generalname2"]));
    $productlink2 = strip_tags(trim($_POST["productlink2"]));
    $fromprice2 = strip_tags(trim($_POST["fromprice2"]));
    $notes2 = strip_tags(trim($_POST["notes2"]));
    $productinfo2 = strip_tags(trim($_POST["productinfo2"]));
    $pr_cat_top_id2 = $_POST["pr_cat_top_id2"];
    $product_hidden = $_POST["product_hidden"];
    $pr_image = $_FILES["productimage2"]["name"];
    $img_count = $_POST["img_count"];
    $primg = $_POST["primg"];
    $dosya_sayi2 = count($_FILES['productimage2']['name']);
    $i=0;
    $a=0;

    /*
    $productname2 = strip_tags(trim($_POST["productname2"]));
    $generalname2 = strip_tags(trim($_POST["generalname2"]));
    $productlink2 = strip_tags(trim($_POST["productlink2"]));
    $fromprice2 = strip_tags(trim($_POST["fromprice2"]));
    $notes2 = strip_tags(trim($_POST["notes2"]));
    $productinfo2 = strip_tags(trim($_POST["productinfo2"]));
    $pr_cat_top_id2 = $_POST["pr_cat_top_id2"];
    $product_hidden = $_POST["product_hidden"];

    $product_img = $_POST["product_img"];

    $edge2 = $_POST["edge2"];
    $room2 = $_POST["room2"];
    $material2 = $_POST["material2"];
    $finish2 = $_POST["finish2"];
    $traffic2 = $_POST["traffic2"];
    $wall2 = $_POST["wall2"];
    $colour4 = $_POST["colour4"];
    $colour25 = $_POST["colour25"];
    $pop2 = $_POST["pop2"];
    $gap2 = $_POST["gap2"];

    $pr2 = $_POST["pr2"];
    $sl2 = $_POST["sl2"];
    $stk2 = $_POST["stk2"];
    $vat2 = $_POST["vat2"];
    $ch2 = $_POST["ch2"];
    $edgech2 = $_POST["edgech2"];

    /*
    $price12 = $_POST["price12"];
    $price22 = $_POST["price22"];
    $options22 = $_POST["options22"];
    $options22 = $_POST["options22"];

    echo array_shift($price12)."   -->   ".count($price12)."   -->   "."<br />";
    echo "price12"."<br />";
    echo "<pre>";
    print_r($price12);
    echo "</pre>";
    /*
    echo "price22"."<br />";
    echo "<pre>";
    print_r($price22);
    echo "</pre>";
    echo "option22"."<br />";
    echo "<pre>";
    print_r($options22);
    echo "</pre>";
    */
    /*
    for ($i=0; $i < count($price12); $i++){
        $data = array(
                array($options22,$price12[$i],$price22[$i])
        );
    }
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    */

    foreach ($_FILES["productimage2"]["name"] as $file) {
        if (empty($file)) {
            for ($i = 0; $i < $img_count; $i++) {
                $pr_update = $db->query("update products set generalname='$generalname2', name='$productname2', link='$productlink2', fromprice='$fromprice2', categoryid='$pr_cat_top_id2', productinfo='$productinfo2', note='$notes2' where pr_id='$product_hidden'");
                $product_img[$i] = $_POST["img_hidden"][$i];
                $img = $product_img[$i];
                $ex = explode(".", $img);
                $img_id[$i] = $_POST["img_idhidden"][$i];
                $id = $img_id[$i];
                $pr_name2 = permalink($productname2) . "--" . $i . "." . $ex[1];
                //echo "id:       ".$img_id[$i]."</br>";
                //echo "img:      ".$product_img[$i]."</br>";
                $img_update = $db->query("update images set imagename='$pr_name2' where pr_id='$product_hidden' and id='$id'");
                rename("../../img/products/thumbs/" . $img, "../../img/products/thumbs/" . $pr_name2);
                rename("../../img/products/" . $img, "../../img/products/" . $pr_name2);

                setcookie('testcookie', "true|Product Updated...", time() + 20, '/');
                header("location: ../index.php?p=products");
            }
        }
        else{
            $sayi = rand(0, 1000);
            $rkaynagi = $_FILES["productimage2"]["tmp_name"][$a];
            $risimi = $_FILES["productimage2"]["name"][$a];
            $rturu = $_FILES["productimage2"]["type"][$a];
            $rboyutu = $_FILES["productimage2"]["size"][$a];
            $isim_parcala = explode(".", $risimi);
            $rasgeleisim = permalink($productname2) . "-" . $a . $sayi;
            $r_yeniismi2 = $rasgeleisim . "." . $isim_parcala["1"];

            $temp = "../../img/temp";
            $dhedef = "../../img/products";
            $thumb = "../../img/products/thumbs/";
            $isim_parcala = explode(".", $risimi);
            $rasgeleisim = permalink($productname2) . "_" . $a . $sayi;
            $r_yeniismi3 = $rasgeleisim . "." . $isim_parcala["1"];

            if ((!file_exists($dhedef . "/" . $r_yeniismi2) && !file_exists($dhedef . $r_yeniismi2)) || (!file_exists($dhedef . "/" . $r_yeniismi3) && !file_exists($thumb . $r_yeniismi3))) {
                $isim_parcala = explode(".", $risimi);
                $rasgeleisim = permalink($productname2) . "_" . $a . $sayi;
                $r_yeniismi = $rasgeleisim . "." . $isim_parcala["1"];
                $dyukle = move_uploaded_file($_FILES["productimage2"]["tmp_name"][$a], $temp . '/' . $r_yeniismi);
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

                $img_update = $db->query("insert into images(pr_id,imagename) values('$product_hidden','$r_yeniismi')");
                unlink($temp."/".$r_yeniismi);

                setcookie('testcookie', "true|Product Updated...", time() + 20, '/');
                header("location: ../index.php?p=products");
            } else {
                setcookie('testcookie', "false|Dosya Mevcut...", time() + 20, '/');
                header("location: ../index.php?p=products");
            }
            $a++;
        }
    }
}

?>



