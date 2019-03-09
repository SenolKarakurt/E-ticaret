<?php
include "include/connection.php";
date_default_timezone_set('Europe/Istanbul');

?>
<html>
<head>
    <link rel="stylesheet" href="css/panel-custom.css" />
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="css/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
    <style>
        .table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
            vertical-align: middle!important;
        }
    </style>
</head>
<body style="padding-top: 0px; background-color: #ffffff" onload="toTitleCase()">
<script type="text/javascript">function toTitleCase(str) {
        return str.replace(/\w\S*/g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }
</script>
<?php
$orderid = $_GET["orderid"];
$type = $_GET["type"];
if ($type == "proforma"){
    $page = "Proforma";
    $pagequery = "proforma";
    $ordertype = 2;
}
elseif ($type == "delivery"){
    $page = "Delivery Note";
    $pagequery = "delivery note";
    $ordertype = 3;
}
else{
    $page = "Order Confirmation";
    $pagequery = "order";
    $ordertype = 3;
}

$companies = $db->query("SELECT company_address.*, orders.*, orders.isdeleted as oisdeleted, companies.* FROM orders left join company_address on company_address.id=orders.addressid left join companies on companies.id=orders.companyid where orders.id='$orderid'")->fetch();
$orderno = $companies["orderno"];
$proformano = $companies["proformano"];
$invoiceno = $companies["invoiceno"];
$websiteid = $companies["websiteid"];

if ($pagequery == "proforma"){
    $documentno = "PR - ".$proformano;
}
elseif ($pagequery == "order"){
    $documentno = "ORD - ".$orderno;
}
elseif ($pagequery == "delivery note"){
    if ($orderno != ""){
        $documentno = "ORD - ".$orderno;
    }
    if ($invoiceno != ""){
        $inv = $db->query("SELECT orderno FROM orders where invoiceid='$orderid'")->fetch();
        $documentno = "ORD - ".$inv["orderno"];
    }
}
?>
<div class="container" style="width: 860px">
    <div class="row">
        <div class="col-xs-6">
            <?php
            if ($websiteid == 1){
                $web = $db->query("select * from websites where websiteid='$websiteid'")->fetch();
                ?>
                <img src="../img/stonedeals.jpg" class="img-responsive">
            <?php
            }
            elseif ($websiteid == 2){
                ?>
                <img src="../img/travertinetiles.png" class="img-responsive">
            <?php
            }
            ?>
            <div>
                <?php
                if ($websiteid == 1){
                    $customer = $db->query("SELECT company_address.*, companies.* FROM companies left join company_address on companies.id=company_address.companyid and company_address.addressname='SD' where companies.id='3'")->fetch();
                }
                elseif ($websiteid == 2){
                    $customer = $db->query("SELECT company_address.*, companies.* FROM companies left join company_address on companies.id=company_address.companyid and company_address.addressname='TS' where companies.id='3'")->fetch();
                }
                $tel = $customer["tel"];
                $email = $customer["email"];
                $web = $customer["web"];
                $vatno = $customer["vatno"];
                $companyno = $customer["note"];
                if ($companyno != ""){
                    $part= "companyno{";
                    $part2 = "}";
                    $basla = strpos($companyno,$part);
                    $bit = strpos($companyno,$part2);
                    $uzunluk = strlen($companyno);
                    $companyno = substr($companyno,$basla+10,$bit-($basla+10));
                }
                $mycompany = $mycompany.$customer["house"];
                if ($customer["street"] != ""){
                    $mycompany = $mycompany." a".$customer["street"];
                }
                if ($customer["county"] != ""){
                    $mycompany = $mycompany.", ".$customer["county"];
                }
                if ($customer["city"] != ""){
                    $mycompany = $mycompany.", ".$customer["city"];
                }
                ?>
                <script>
                    document.write(toTitleCase('<?php echo $customer["companyname"]; ?>'));
                </script>Tile And Stone Depot Ltd
                <br>
                Registered in England &amp; Wales, No: <?php echo $companyno; ?><br>
                <script>
                    document.write(toTitleCase('<?php echo $mycompany; ?>'));
                </script>, <?php echo $customer["postcode"]; ?><br>


            </div>
        </div>
        <div class="col-xs-6">
            <h2 class="text-right"><?php echo $page; ?></h2>
            <table class="table table-bordered table-condensed">
                <tbody><tr>
                    <td class="text-center">Tax Date</td>
                    <td class="text-center"><?php echo $page; ?> No</td>
                </tr>
                <tr>
                    <?php
                    $or = $db->query("select * from orders where id='$orderid'")->fetch();
                    $ot = strtotime($or["orderdate"]);
                    $dt = date("d/m/Y",$ot);
                    ?>
                    <td class="text-center"><?php echo $dt; ?></td>
                    <td class="text-center"><?php echo $documentno; ?></td>
                </tr>
                </tbody></table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-bordered table-condensed">
                <tbody>
                <tr>
                    <td class="text-left" width="50%"><?php if ($ordertype == 2){ echo "Name / Address"; }else{ echo "Invoice To"; } ?></td>
                    <td class="text-left">Ship To</td>
                </tr>
                <tr>
                    <td class="text-left"><?php if ($companies["companyname"] != ""){ echo $companies["companyname"]; }else{ echo $companies["firstname"]; ?>&nbsp;<?php echo $companies["lastname"]; } ?>
                        <br>
                        <?php
                        if ($companies["house"] != ""){
                            $caddress = $companies["house"];
                        }
                        if ($companies["street"] != ""){
                            $caddress = $caddress." ".$companies["street"];
                        }
                        if ($companies["city"] != ""){
                            $caddress = $caddress." ".$companies["city"];
                        }
                        if ($companies["county"] != ""){
                            $caddress = $caddress."<br />".$companies["county"];
                        }
                        if ($companies["postcode"] != ""){
                            $caddress = $caddress."<br />".$companies["postcode"];
                        }
                        if ($companies["country"] != ""){
                            $caddress = $caddress." ".$companies["country"];
                        }
                        echo $caddress;
                        ?>
                    </td>
                    <td class="text-left">
                        <?php
                        if ($companies["shiptoaddressid"] != "" && $companies["shiptoaddressid"] != $companies["addressid"]){
                            $shipaddress = $db->query("SELECT * FROM company_address where id='".$companies['shiptoaddressid']."'")->fetch();
                            if ($companies["companyname"] != ""){
                                echo $companies["companyname"];
                            }
                            else{
                                echo $companies["firstname"]."&nbsp;".$companies["lastname"];
                            }
                        }
                        ?>
                        <br>
                        <?php
                        if ($shipaddress["house"] != ""){
                            $saddress = $shipaddress["house"];
                        }
                        if ($shipaddress["street"] != ""){
                            $saddress = $saddress." ".$shipaddress["street"];
                        }
                        if ($shipaddress["city"] != ""){
                            $saddress = $saddress." ".$shipaddress["city"];
                        }
                        if ($shipaddress["county"] != ""){
                            $saddress = $saddress."<br />".$shipaddress["county"];
                        }
                        if ($shipaddress["postcode"] != ""){
                            $saddress = $saddress."<br />".$shipaddress["postcode"];
                        }
                        if ($shipaddress["country"] != ""){
                            $saddress = $saddress." ".$shipaddress["country"];
                        }
                        echo $saddress;
                        if ($companies["companyname"] != ""){
                            echo $companies["companyname"];
                        }
                        else{
                            echo $companies["firstname"]."&nbsp;".$companies["lastname"];
                        }
                        ?>
                        <br />
                        <?php echo $caddress; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <?php
            if ($type == "order" || $type == "proforma"){
                ?>
                <table class="table table-bordered table-condensed">
                    <tbody>
                    <tr>
                        <td class="text-center">
                            Bank Details:<br>
                            Barclays Bank<br>
                            Sort Code: 20-73-53<br>
                            Account: 83372502
                        </td>
                    </tr>
                    </tbody>
                </table>
            <?php
            }
            ?>
        </div>
        <div class="col-xs-2"></div>
        <div class="<?php if ($page != "creditenote"){ ?>col-xs-6<?php }else{?>col-xs-3 pull-righ<?php } ?>">
            <table class="table table-bordered table-condensed">
                <tbody>
                <tr>
                    <?php if ($invoiceno != ""){ ?><td class="text-center small"><strong>Invoice No</strong></td><?php } ?>
                    <?php if ($type == "order"){ ?><td class="text-center small"><strong>P.O. No</strong></td><?php } ?>
                    <td class="text-center small"><strong>Ship Date</strong></td>
                    <td class="text-center small"><strong>Ship Via</strong></td>
                </tr>
                <tr>
                    <?php if ($invoiceno != ""){ ?><td class="text-center small">I - <?php echo $invoiceno; ?></td><?php } ?>
                    <?php if ($type == "order"){ ?><td class="text-center small"><?php if ($companies["pono"] != ""){ echo $companies["pono"]; }else{ echo "N/A"; } ?></td><?php } ?>
                    <td class="text-center small">
                        <?php
                        if ($companies["deliverydate"] != ""){
                           $dl = strtotime($companies["deliverydate"]);
                           $dlvry = date("d/m/Y",$dl);
                           echo $dlvry;
                        }
                        else{
                            echo "N/A";
                        }
                        ?>
                    </td>
                    <td class="text-center small">
                        <?php
                        if ($companies["shipvia"] != ""){
                            $via = $db->query("select * from combos where id='".$companies["shipvia"]."'")->fetch();
                            echo $via["name"];
                        }
                        else{
                            echo "N/A";
                        }
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
    <?php
    if ($companies["notes"] != "" && $type == "delivery"){  // kontrol.asp içerisinde bosluk adında fonksiyon kullanılmış.....
        ?>
        <table class="table table-bordered table-condensed">
            <tr>
                <td width="10%"><b>Notes : </b></td>
                <td><?php echo strip_tags(trim($companies["notes"])); ?></td>
            </tr>
        </table>
    <?php
    }
    if ($companies["customernotes"] != "" && $type == "order"){
        ?>
        <table class="table table-bordered table-condensed">
            <tr>
                <td width="10%"><b>Notes : </b></td>
                <td><?php echo strip_tags(trim($companies["customernotes"])); ?></td>
            </tr>
        </table>
    <?php
    }
    if (isset($_POST["orderid"]) && $_POST["orderid"] != ""){
        $orderid = $_POST["orderid"];
    }
    elseif (isset($_GET["orderid"])){
        $orderid = $_GET["orderid"];
    }
    else{

    }

    if ($orderid != "") {
        $rscart = $db->query("select * from orders where id='$orderid'");
        if ($rscart->rowCount()) {
            foreach ($rscart as $rs) {
                $siteid = $rs["websiteid"];
                $orderid = $rs["id"];
                $postcodeid = $rs["postcodeid"];
                $isnextday = $rs["isnextday"];
                $deliverytime = $rs["deliverytime"];
                $deliverypallet = $rs["deliverypallet"];
                $kargosekli = $rs["deliverytype"];
                $kargofiyati = $rs["deliveryprice"];
                $addressid = $rs["addressid"];
                $nextdaydate = $rs["nextdaydate"];
            }
        } else {
            $orderid = 0;
        }

        $cmp = $db->query("SELECT company_address.*, orders.*, orders.isdeleted as oisdeleted, companies.* FROM company_address left join orders on company_address.id=orders.addressid left join companies on companies.id=orders.companyid where orders.id='$orderid'")->fetch();
        $firstname = $cmp["firstname"];
        $lastname = $cmp["lastname"];
        $house = $cmp["house"];
        $street = $cmp["street"];
        $city = $cmp["city"];
        $county = $cmp["county"];
        $postcode = $cmp["postcode"];
        $strEmail = $cmp["email"];
        $tel1 = $cmp["tel1"];
        $couponcode = $cmp["couponcode"];

        $strName = $firstname . " " . $lastname;
        $emailtext = str_replace("<$strName>", "$strName", $emailtext);

        //$cmpny = $db->query("select * from orders where id='$orderid' and ordertype='$ordertype'")->fetch();
        //$stid = $cmpny["websiteid"];
        $session = $db->query("select * from orders where id='$orderid'")->fetch();
        $sessionid = $session["sessionid"];
        $bskt = $db->query("select * from basket where orderid='$orderid' and isordered=1 and websiteid='$websiteid'");
        // $bskt sorgusuna " and sessionid='$sessionid'" kolonu da eklendi...
        $record = $bskt->rowCount();
        if ($record == 0){
            ?>
            <h4>No products found</h4>
            <?php
        }
        else{
            ?>
            <div class="table-responsive">
                <table class="table table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>Qty</th>
                        <th>Description</th>
                        <th># Item</th>
                        <?php
                        if ($type != "delivery"){
                            ?>
                            <th>VAT</th>
                            <th>Price</th>
                            <th>Sub Total</th>
                            <?php
                        }
                        ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $m = 1;
                    foreach ($bskt as $prdcts) {
                        if ($prdcts["isdiscount"] == true){
                            $vouchersizeid = 1;
                        }
                        if ($prdcts["isdelivery"] == true){
                            $deliverysizeid = 1;
                        }

                        $wbst = $prdcts["websiteid"];
                        $prid = $prdcts["productid"];
                        $pr = $db->query("select * from products where pr_id='$prid'")->fetch();
                        $primage = $db->query("select * from images where pr_id='$prid'")->fetch();
                        $sizeid = $prdcts["sizeid"];
                        $szid = $pr["szid"];
                        $prsizes = $db->query("select * from product_sizes where productid='$szid' and sizeid='$sizeid'")->fetch();
                        $prszid = $prsizes["id"];
                        $sz = $db->query("select * from sizes where id='$sizeid'")->fetch();
                        $itemunit = $sz["itemunit"];
                        $sizeunit = $sz["sizeunit"];
                        $qtyunit = $sz["qtyunit"];
                        $qtySl = $db->query("select * from basket where orderid='$orderid' and productid='$prid' and sizeid='$sizeid'")->fetch();

                        $area = $sz["area"] / 10000;
                        $quantity = ($prdcts["quantity"] / $area);
                        $ceil = ceil($quantity);
                        $pcs = $ceil * $sz["area"] / 10000;

                        $productsize = $sz["size"];
                        $kg = $sz["weight"];

                        $listprice = $prsizes["price"];
                        $price = $prsizes["wasprice"];

                        if ($listprice == "" || empty($listprice)){
                            $listprice = 0;
                        }

                        if ($kg == "" || empty($kg)){
                            $kg = 25;
                        }
                        $kg = str_replace(",",".",$kg);
                        $weight = $kg * $ceil;
                        $weighttotal = $weighttotal + $weight;
                        $subprice = $listprice * $ceil;
                        $total = $total + $subprice;

                        $grout = $prdcts["grout"];
                        if ($grout != ""){
                            $totalgrout = $totalgrout + $grout;
                        }
                        $totalqty = $totalqty + $ceil;

                        $cmb = $db->query("select * from combos where id='$qtyunit'")->fetch();
                        $cmbitem = $db->query("select * from combos where id='$itemunit'")->fetch();
                        ?>
                        <tr>
                            <td class="vert-align">
                                <?php
                                if ($prdcts["sample"] == 1) {
                                    $prquantity[$k] = $prdcts["quantity"];
                                    echo $prdcts["quantity"];
                                } elseif ($prdcts["productid"] == 146) {
                                    echo $prdcts["quantity"];
                                } elseif ($prdcts["productid"] == 145) {
                                    echo $prdcts["quantity"];
                                } elseif ($prdcts["productid"] == 134) {
                                    echo $prdcts["quantity"];
                                } elseif ($prdcts["productid"] == 138) {
                                    echo $prdcts["quantity"];
                                } elseif ($prdcts["productid"] == 248) {
                                    echo $prdcts["quantity"];
                                } else {
                                    echo number_format($pcs,3);
                                }
                                ?>&nbsp;<?php echo $cmb["name"]; ?>
                            </td>
                            <td class="prlist">
                                    <?php
                                    echo $pr["name"]." • ".$sz["size"]."  ";
                                    if ($sizeunit != "None"){
                                        $szunit = $db->query("select * from combos where id='$sizeunit'")->fetch();
                                        echo $szunit["name"];
                                    }
                                    if ($type == "delivery"){
                                        if ($prdcts["isdelivery"] == true && $deliverypallet != ""){
                                            echo " ".$deliverypallet." pallet(s) ";
                                        }
                                    }
                                    if ($prdcts["isdelivery"] == true && $nextdaydate != ""){
                                        echo " (".$nextdaydate.")";
                                    }
                                    ?>
                            </td>
                            <td class="vert-align" style="padding-top: 12px;">
                                <?php
                                if ($prdcts["sample"] == 1) {
                                    $prquantity[$k] = $prdcts["quantity"];
                                    echo $prdcts["quantity"];
                                } elseif ($prdcts["productid"] == 146) {
                                    echo $prdcts["quantity"];
                                } elseif ($prdcts["productid"] == 145) {
                                    echo $prdcts["quantity"];
                                } elseif ($prdcts["productid"] == 134) {
                                    echo $prdcts["quantity"];
                                } elseif ($prdcts["productid"] == 138) {
                                    echo $prdcts["quantity"];
                                } elseif ($prdcts["productid"] == 248) {
                                    echo $prdcts["quantity"];
                                } else {
                                    echo $ceil;
                                }
                                ?>&nbsp;<?php echo $cmbitem["name"]; ?>
                            </td>
                            <?php
                            if ($type != "delivery"){
                                ?>
                            <td>
                                <?php
                                $menu = $db->query("select * from combos where id='".$prdcts["vatid"]."'");
                                foreach ($menu as $vat){
                                    $vatname = substr($vat["name"],0,1);
                                     echo $vatname.".".number_format($vat["amount"],0); ?>%
                                    <?php
                                }
                                $subprice = $pcs * $prsizes["price"];
                                $subprices[$k] = $subprice;

                                $vatrate = $prdcts["vatrate"];
                                $vatsiz = $listprice * 100 / (100 + $vatrate);
                                //$vatsiz = $price * 100 / (100 + $vatrate);
                                $vatsizsubprice = $subprice * 100 / (100 + $vatrate);
                                $subtotalanlink = $subtotalanlink + $vatsizsubprice;
                                $vatamount = $vatamount + ($vatsizsubprice * ($vatrate / 100));
                                $total = $subtotalanlink + $vatamount;
                                ?>
                            </td>
                            <td>£<?php echo number_format($vatsiz,2); ?></td>
                            <td class="vert-align" style="padding-top: 12px;">£<?php echo number_format($vatsizsubprice,2); ?></td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
        ?>
        </div>
    </div>
<?php
if ($type != "delivery") {
    ?>
    <div class="row">
        <div class="col-xs-7"></div>
        <div class="col-xs-5 pull-right">
            <table class="table table-bordered table-condensed">
                <tr>
                    <td width="60%"><b>Sub Total : </b></td>
                    <td class="text-right">&pound;<?php echo number_format($subtotalanlink,2); ?></td>
                </tr>
                <tr>
                    <td><b>VAT : </b></td>
                    <td class="text-right">&pound;<?php echo number_format($vatamount,2); ?></td>
                </tr>
                <tr>
                    <td><b>Total :</b></td>
                    <td class="text-right">&pound;<?php echo number_format($total,2); ?></td>
                </tr>
            </table>
        </div>
    </div>
    <?php
}
?>
    <?php
    if ($type == "proforma"){
        ?>
        <div id="spacer" style="height: 30px; float: left; display: inline-block; margin-top: 15px;"></div>
    <?php
    }
    else{
        ?>
        <div id="spacer" style="height: 222px; float: left; display: inline-block; margin-top: 15px;"></div>
    <?php
    }
    ?>

    <div class="invoicefooter">
        <div class="container" style="width: 846px">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if ($type == "order"){
                        ?>
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <td class="col-xs-6 small text-danger">When delivery is made, please ensure to remove the protective cover and if any breakages found, state on the delivery note as "DAMAGED". If the delivery note is signed as "UNCHECKED", we reserve the right not to provide replacements.<br />
                                    Please also note that our cut off time is 12.00pm for next day deliveries and all deliveries will be made to KERBSIDE only.</td>
                                <td class="col-xs-6 small">This is the order acknowledgement of your recent order which is subject to our Terms and Conditions of Sale. Please review the order details and call us if any of the details above are incorrect. Only in exceptional circumstances an order can be amended and an amendment charge may be applied if the preparation of the order has started. Please note that delivery times are approximate and normally delivery hours are in between 8.00am and 6.00pm.</td>
                            </tr>
                        </table>
                        <div class="space5"></div>
                    <?php
                    }
                    if ($type == "delivery"){
                        ?>
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <td width="50%"><b>Prepared By:</b></td>
                                <td><b>Packed By:</b></td>
                            </tr>
                            <tr>
                                <td>IMPORTANT NOTE:<br />
                                    Please inspect your order thoroughly before signing receipt of the order. Any damages or discrepancies must be noted on the delivery note and you must inform us within 2 working days. Otherwise we will not accept any responsibility.</td>
                                <td>CUSTOMER NOTE:<br />
                                    The driver cannot carry your order into your site. It will be offloaded at the nearest and safest kerbside, therefore please arrange some help. For more info, see delivery information.</td>
                            </tr>
                        </table>
                        <div class="space5"></div>
                    <?php
                    }
                    ?>
                    <table class="table table-bordered table-condensed">
                        <tbody>
                            <tr>
                                <td class="text-center" width="33%"><small><?php if ($websiteid == 1){ ?>Tel: 0845 108 5533<?php }elseif ($websiteid == 2){ ?>Tel: 0844 545 2002<?php } ?></small></td>
                                <td class="text-center" width="33%"><small>Email : <?php if ($websiteid == 1){ ?>info@stonedeals.co.uk<?php }elseif ($websiteid == 2){ ?>sales@travertinetilesuk.com<?php } ?></small></td>
                                <td class="text-center" width="33%"><small>Web : <?php if ($websiteid == 1){ ?>www.stonedeals.co.uk<?php }elseif ($websiteid == 2){ ?>www.travertinetilesuk.com<?php } ?></small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
else{
    echo "Order ID Error";
}
?>

</body>
</html>