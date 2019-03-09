<?php
include "include/class.phpmailer.php";
include "include/class.smtp.php";
include "include/class.pop3.php";
$db = new PDO("mysql:host=localhost;dbname=newconcept","root","root");
if ($db){

    //$add_pr = $db->query("insert into `products` (pr_id,szid,generalname,name,link,fromprice,categoryid,productinfo,note,new,gap,room,soffer,freesample,colour,colour2,material,finish,wall,edge,traffic,projects,pop,optionstype,pertype,websiteid) values('3232','3232','adsda','dsadas','sadsa','32','233','asdas','sadasd','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','2')");
    //$add_pr = $db->query("INSERT INTO `products`(`pr_id`, `szid`, `generalname`, `name`, `fromprice`, `categoryid`, `productinfo`, `note`, `new`, `gap`, `room`, `soffer`, `freesample`, `colour`, `colour2`, `material`, `finish`, `wall`, `edge`, `traffic`, `projects`, `pop`, `optionstype`, `pertype`, `websiteid`) values('3232','3232','adsda','dsadas','32','23','asdas','sadasd','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','1','2')");


    /*
    $rwsss = $db->query("SELECT ROUND(AVG(CAST(rating AS decimal)),2) as avgrating, COUNT(*) AS reviewcount FROM reviews where productid='331' and websiteid='1' and `show`=1");
    if ($rwsss->rowCount()){
        echo "oldu"."<br>";
        print_r($rwsss);
    }
    else{
        echo "olmadi";
    }

/*
    $file = fopen("pr.txt","r");
    //$file2 = fopen("rp.txt","r");
    $sl = $db->query("select * from product_sizes")->fetch();
    $id = $sl["id"];
    //$tt = 138;
    while(!feof($file)){
        $ad = fgets($file);
        echo  $ad."<br />";
        //$up = $db->query("insert into combos (id) values ('$ad')");
        //$up = $db->query("delete from combos where id='$tt'");
        $up = $db->query("update product_sizes set `show`='$ad' where id='$ad'");
        if ($up){
            echo "oluyor"."     "."<br />";
            $id++;
        }
        else{
            echo "olmuyor"."<br />";
        }
    }

/*
    $mat = $db->query("insert into basket(orderid, productid, sizeid, sessionid, sample, quantity, vatrate, grout, createdate, websiteid) select '13186',productid, sizeid, sessionid, sample, quantity, vatrate, grout, createdate, websiteid from basket where orderid='13185'");
    if ($mat->rowCount()){
        echo "oldu"."<br>";

    }
    else{
        echo "olmadi";
    }
    */
}
else{
    echo"bağlanamadı";
}
?>

<?php
/*
$review = $db->query("SELECT ROUND(AVG(CAST(rating AS FLOAT)), 2) AS avgrating, COUNT (*) AS reviewcount FROM reviews where productid='$prid' and show=1 and websiteid=$site_name")->fetch();
$reviewcount = $review["reviewcount"];
$star = $review["avgrating"];
if ($star != ""){
    $star = round($star);
    $instar = (int) $star;
    $ostar = 5 - $star;
    $ostar = (int) $ostar;
    ?>

    <div class="col-xs-6">
        <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <span itemprop="ratingValue" class="hidden"><?php echo $intstar; ?></span>
            <a href="#reviews"><span class="yellow-star min25">
                                        <?php for ($a = 1; $a < $instar; $a++){ ?> <i class="fa fa-star" aria-hidden="true"></i><?php } ?><?php if ($star > $intstar){ ?><i class="fa fa-star-half-empty" aria-hidden="true"></i> <?php } ?><?php for ($a=1; $a < $ostar; $a++){ ?><i class="fa fa-star-o" aria-hidden="true"></i><?php } ?><span class="text-grey small">(<span itemprop="reviewCount"><?php echo $reviewcount; ?></span> Reviews)</span></span>
            </a>
        </div>
    </div>
    <?php
}
*/
?>
<?php
/*
$sqlmenu="SELECT COUNT (*) AS quecount FROM questions where productid='$prid' and show=1 and websiteid=1";
$menu = $db->query($sqlmenu)->fetch();
$quecount = $menu["quecount"];

if ($quecount!= 0){
    ?>
    <div class="col-xs-6 text-right"><div class="space10"></div>
        <a href="#questions"><i class="fa fa-comments-o" aria-hidden="true"></i><?php echo $quecount; ?> <span class="text-grey small">Question<?php if ($quecount != 1){ echo "s";} ?> & Answer<?php if ($quecount != 1){ echo "s"; } ?></span></a></div>
    <?php
}
 */
 ?>

<?php
$notcard = 1;
$checkout = 1;
/*
include-cart-total sayfası stonedeals için bitti....
*/
//include "include/include-cart-totals.php"; //Bu sayfa iki site için ayrı ayrı düzenlenecek....
function roundup($num){
    if ($num > (int)$num){
        $roundup = (int)$num + 1;
    }
    else{
        $roundup = (int)$num;
    }
}
$paletkg = 1000;
$palet = $weighttotal / $paletkg;
$palet = roundup($palet);
if ($samplesayisi > 1 && $baskavar == 1){
    //2 den fazla varsa her fazlalık icin hesapla
    $sampledelivery = ($samplesayisi - 2) * 2;
}


/* ?p=cart sayfasına eklenecek kısım.
    stonedeals için ayrı travertine tiles için include-cart-totals
*/
if (($record == 1 && $samplesayisi == 1) || ($samplesayisi == 2 && $baskavar != 1)){
    ?>
    <div class="text-right">
        <input type="button" name="aksiyon" id="samplebutton" value="ORDER FREE SAMPLE" class="btn btn-main" />
    </div>
    <?php
}
elseif ($samplesayisi > 1 && $baskavar != 1){
    if ($notcard == 1 && $checkout != 1){

    }
    else{
        ?>
        <div class="row vertical-align">
            <div class="col-md-6 col-xs-6 cartsub-left">Sub-Total : </div>
            <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($total,2); ?></div>
        </div>
        <div class="row vertical-align">
            <div class="col-md-6 col-xs-6 cartsub-left">Delivery : </div>
            <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($deliveryprice,2); ?></div>
        </div>
        <div class="row vertical-align">
            <div class="col-md-6 col-xs-6 cartsub-left">Total : </div>
            <?php $entotal = $total + $deliveryprice; ?>
            <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($entotal,2); ?></div>
        </div>
        <?php
        if ($checkout != 1){
            ?>
            <br />
            <div class="text-right">
                <input type="button" name="aksiyon" id="checkoutbutton" value="CHECKOUT / GET QUOTE" class="btn btn-danger" />
            </div>
            <?php
        }
    }
}
else{
    if ($checkout != 1 && $notcard == 1){

    }
    else{
        ?>
        <div class="row vertical-align">
            <div class="col-md-6 col-xs-6 cartsub-left">Sub-Total : </div>
            <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($total,2); ?></div>
        </div>
        <?php
    }
    ?>
    <div class="row vertical-align">
        <div class="col-md-6 col-xs-6 cartsub-left">Postcode : </div>
        <div class="col-md-6 col-xs-6 cartsub-right">
            <select class="form-control form-control-auto input-sm" name="postcodeid" <?php if ($checkout != 1){ ?> onchange="this.form.submit()"<?php }else{ ?> style="display:none!important"<?php } ?>>
                <option value="">Select</option>
                <?php
                $sqlsizes = $db->query("select * from postcodes");
                foreach ($sqlsizes as $sizes){
                    ?>
                    <option value="<?php echo $sizes["id"]; ?>"
                        <?php
                        if ($sizes["id"] == $postcodeid){
                            $zone = $sizes["zone"];
                            $postcodeyaz = $sizes["postcode"];?>
                            selected="selected"
                            <?php
                        } ?>> <?php echo $sizes["postcode"]; if ($notcard == 1){ ?>&nbsp;Zone: <?php echo $size["zone"]; } ?></option>
                    <?php
                }
                ?>
            </select>
            <?php if ($checkout == 1){ echo $postcodeyaz; } ?>
        </div>
    </div>
    <?php
    if ($postcodeid != ""){
        if ($weighttotal <= 1.9){
            ?>
            <div class="row vertical-align">
                <div class="col-md-6 col-xs-6 cartsub-left">
                    Delivery: <?php if ($notcard != 1){
                        ?>
                        <br />
                        <small><a href="?p=delivery" data-toggle="tooltip" title="Depending on the location, Royal Mail Parcel Service.">Learn More</a></small>
                    <?php } ?>
                </div>
                <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($deliveryprice,2); ?></div>
            </div>
            <?php
        }
        elseif ($weighttotal > 1.9 && $weighttotal <= 20){
            ?>
            <div class="row vertical-align">
                <div class="col-md-6 col-xs-6 cartsub-left">
                    Delivery: <?php if ($notcard == 1){
                        ?>
                        <br />
                        <small><a href="?p=delivery" data-toggle="tooltip" title="Depending on the location, Royal Mail Parcel Service or Next Day Courier Service. ">Learn More</a></small>
                        <?php
                    } ?>
                </div>
                <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($deliveryprice,2); ?></div>
            </div>
            <?php
        }
        elseif ($weighttotal > 20 && $weighttotal <=25){
            ?>
            <div class="row vertical-align">
                <div class="col-md-6 col-xs-6 cartsub-left">
                    Economy Delivery: <?php if ($notcard != 1){
                        ?>
                        <br />
                        <small><a href="?p=delivery" data-toggle="tooltip" title="Economy Delivery : Up to 4 working days. Orders for Scotland and N.Ireland may take up to 5 working days.&#013;Please refer to our delivery page or call us for more information.">Learn More</a></small>
                        <?php
                    }    ?>
                </div>
                <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($deliveryprice,2); ?></div>
            </div>
            <?php
        }
        else{
            ?>
            <div class="row vertical-align">
                <?php
                $sqlzones = $db->query("select * from zones id='$zone'")->fetch();
                if ($sqlzones["nextday"] == 0){ $nonextday = 1; }
                $low = $sqlzones["low"];
                $economy = $sqlzones["economy"];
                $nextday = $sqlzones["nextday"];
                $ampm = $sqlzones["ampm"];
                $saturdayam = $sqlzones["saturdayam"];
                $saturdaypm = $sqlzones["saturdayam"];
                if ($checkout == 1){

                }
                else{
                    ?>
                    <div class="col-md-6 col-xs-6 cartsub-left">
                        Delivery Type :<?php if ($notcard != 1){ ?>
                            <br />
                            <small><a href="?p=delivery" data-toggle="tooltip" title="Economy Delivery : Usually 2-3 working days. Orders for Scotland and N.Ireland may take up to 5 working days.&#013;&#013;Next Day Delivery : The order will be delivered next working day between 8am and 6pm from dispatch.&#013;&#013;Cut off time: Dispatch cut off time is 12pm during the working days. We do not ship goods over the weekends or on public holidays.&#013;&#013;Please refer to our delivery page or call us for more information.">Learn More</a></small><?php } ?>
                    </div>
                    <div class="col-md-6 col-xs-6 cartsub-right">
                        <div class="radio">
                            <label>
                                <input type="radio" name="isnextday" value="0" id="RadioGroup1_0"
                                    <?php if ($isnextday == false || empty($isnextday)){ ?> checked="checked" <?php } ?>> onchange="this.form.submit()" />
                                Economy Delivery
                            </label>
                            <br />
                            <label>
                                <input type="radio" name="isnextday" value="1" id="RadioGroup1_1" <?php if ($nonextday == 1){ ?> disabled="disabled" <?php }else{ if ($isnextday == true){ ?> checked="checked" <?php } } ?> onchange="this.form.submit()" />
                                Next Day Delivery
                            </label>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
            if ($isnextday == false){
                $economytotal = $economy * $palet;
                ?>
                <div class="row vertical-align">
                    <div class="col-md-6 col-xs-6 cartsub-left">Economy Delivery : </div>
                    <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($economytotal,2); if ($notcard == 1 && $checkout != 1){ ?>&nbsp;<small>(&pound;<?php echo number_format($economy,2); ?>x<?php echo $palet; ?>&nbsp;Pallets - <?php echo $weighttotal; ?>kg)</small><?php } ?></div>
                </div>
                <?php
            }
            if ($isnextday == true){
                $nextdaytotal = $nextday * $palet;
                ?>
                <div class="row vertical-align">
                    <div class="col-md-6 col-xs-6 cartsub-left">Next Day Delivery : </div>
                    <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($nextdaytotal,2); if ($notcard == 1 && $checkout != 1){ ?>&nbsp;<small>(&pound;<?php echo number_format($nextday,2); ?>x<?php echo $palet; ?>&nbsp;Pallets - <?php echo  $weighttotal; ?>kg)</small><?php } ?></div>
                </div>
                <?php
            }
            if ($isnextday == true){
                ?>
                <div class="row vertical-align">
                    <div class="col-md-6 col-xs-6 cartsub-left">Additional Charges : </div>
                    <div class="col-md-6 col-xs-6 cartsub-right">
                        <?php
                        if ($checkout != 1){
                            if ($deliverytime == "standard" || empty($deliverytime)){
                                $deliverytime = "standard";
                                $kargosekli = $kargosekli & "Standard - " & $postcode; ?>Standard <small>(no extra charge)</small>
                                <?php
                            }
                            if ($deliverytime == "am"){
                                ?>
                                AM: &pound;<?php echo $ampm;  ?>
                                <?php
                            }
                            if ($deliverytime == "pm"){
                                ?>
                                PM: &pound;<?php echo $ampm;  ?>
                                <?php
                            }
                            if ($deliverytime == "saturdayam"){
                                ?>
                                Saturday AM: &pound;<?php echo $saturdayam; ?>
                                <?php
                            }
                            if ($deliverytime == "saturdaypm"){
                                ?>
                                Saturday PM: &pound;<?php echo $saturdaypm;  ?>
                                <?php
                            }
                        }
                        else{
                            ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="deliverytime" value="standard"
                                        <?php if ($deliverytime == "standard" || empty($deliverytime)){
                                            $deliverytime = "standard";
                                            $kargosekli = $kargosekli & "Standard - " & $postcode;
                                            $checked = "checked";
                                        }
                                        ?>
                                           onchange="this.form.submit()" />
                                    Standard <small>(no extra charge)</small>
                                </label>
                                <br />
                                <label>
                                    <input type="radio" name="deliverytime" value="am"
                                        <?php if ($deliverytime == "am"){ ?> checked="checked" <?php } ?> onchange="this.form.submit()" />
                                    AM Delivery: &pound;<?php echo $ampm; ?>
                                </label>
                                <br />
                                <label>
                                    <input type="radio" name="deliverytime" value="pm" <?php if($deliverytime == "pm"){ ?> checked="checked" <?php } ?> onchange="this.form.submit()" />
                                    PM Delivery: &pound;<?php echo $ampm; ?>
                                </label>
                                <?php
                                if ($now > "22.02.2017" && $now < "02.01.2018"){

                                }
                                else{
                                    ?>
                                    <br />
                                    <label>
                                        <input type="radio" name="deliverytime" value="saturdayam"
                                            <?php if ($deliverytime == "saturdayam"){ ?>
                                                checked="checked" <?php } ?> onchange="this.form.submit()" />
                                        Saturday AM: &pound;<?php echo $saturdayam; ?>
                                    </label>
                                    <br />
                                    <label>
                                        <input type="radio" name="deliverytime" value="saturdaypm"
                                            <?php if ($deliverytime == "saturdaypm"){ ?>
                                                checked="checked" <?php } ?> onchange="this.form.submit()" />
                                        Saturday PM: &pound;<?php echo $saturdaypm; ?>
                                    </label>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <?php
                function saatkarsilastir($ilk_saat,$son_saat){
                    $ilk = strtotime($ilk_saat);
                    $son = strtotime($son_saat);
                    if ($ilk < $son){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }

                $saat1 = date('H:i:s');
                $saat2 = "12:15:00";
                $am = saatkarsilastir($saat1,$saat2);
                if($am == 1){
                    $xdaylater = "+1d";
                }
                else{
                    $xdaylater = "+2d";
                }

                function tarihkarsilastir($ilk_tarih,$son_tarih,$yilbasi){
                    $ilk = strtotime($ilk_tarih);
                    $son = strtotime($son_tarih);
                    $yil = strtotime($yilbasi);
                    if ($ilk > $son &&  $ilk < $yil){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }

                $tarih1 = date("d.m.Y");
                $tarih2 = "22.12.2018";
                $tarih3 = "02.01.2019";
                $th = tarihkarsilastir($tarih1,$tarih2,$tarih3);

                if($th == 1) {

                    ?>
                    <div class="row vertical-align">
                        <div class="col-md-6 col-xs-6 cartsub-left">Choose Date : </div>
                        <div class="col-md-6 col-xs-6 cartsub-right">
                            <div class="input-group col-md-8">
                                <input type="text" id="datepicker" name="datepicker" onchange="this.form.submit()" value="<?php if (isset($_POST["datepicker"])){  echo $_POST["datepicker"];   }   ?>">
                                <label for="datepicker" class="input-group-addon btn">
                                    <i class="fa fa-calendar"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        var disableddates = ["12-23-2018","12-24-2018","12-25-2018","12-26-2018","12-27-2018","12-28-2018","12-29-2018","12-30-2018","12-31-2018","1-1-2019","1-2-2019"];
                        function DisableSpecificDates(date) {
                            var m = date.getMonth();
                            var d = date.getDate();
                            var y = date.getFullYear();

                            // First convert the date in to the mm-dd-yyyy format
                            // Take note that we will increment the month count by 1
                            var currentdate = (m + 1) + '-' + d + '-' + y ;
                            // We will now check if the date belongs to disableddates array
                            for (var i = 0; i < disableddates.length; i++) {

                                // Now check if the current date is in disabled dates array.
                                if ($.inArray(currentdate, disableddates) != -1 ) {
                                    return [false];
                                }
                            }
                            // In case the date is not present in disabled array, we will now check if it is a weekend.
                            // We will use the noWeekends function
                            var weekenddate = $.datepicker.noWeekends(date);
                            return weekenddate;
                        }

                        $( "#datepicker" ).datepicker({
                            beforeShowDay: DisableSpecificDates
                        });
                    </script>
                    <?php
                }
                else {
                    if ($_POST["deliverytime"] == "saturdayam" || $_POST["deliverytime"] == "saturdaypm"){
                        ?>
                        <div class="row vertical-align">
                            <div class="col-md-6 col-xs-6 cartsub-left">Choose Date : </div>
                            <div class="col-md-6 col-xs-6 cartsub-right">
                                <div class="input-group col-md-8">
                                    <input type="text" id="datepicker" name="datepicker" onchange="this.form.submit()" value="<?php if (isset($_POST["datepicker"])){  echo $_POST["datepicker"];   }   ?>">
                                    <label for="datepicker" class="input-group-addon btn">
                                        <i class="fa fa-calendar"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $('#datepicker').datepicker({
                                minDate: "<?php echo $xdaylater;   ?>",
                                maxDate: "+22d",
                                beforeShowDay: noSunday
                            });
                            function noSunday(date) {
                                if (date.getDay() == 6) {
                                    return [true];
                                }
                                else {
                                    return [false];
                                }
                            }
                        </script>
                        <?php
                    }
                    else{
                        $date = date("d.m.Y");
                        $dt = date("N",strtotime($date));
                        $am = saatkarsilastir($saat1,$saat2);
                        if($dt == 5 && $am == 1){

                            ?>
                            <div class="row vertical-align">
                                <div class="col-md-6 col-xs-6 cartsub-left">Choose Date : </div>
                                <div class="col-md-6 col-xs-6 cartsub-right">
                                    <div class="input-group col-md-8">
                                        <input type="text" id="datepicker" name="datepicker" onchange="this.form.submit()" value="<?php if (isset($_POST["datepicker"])){  echo $_POST["datepicker"];   }   ?>">
                                        <label for="datepicker" class="input-group-addon btn">
                                            <i class="fa fa-calendar"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $('#datepicker').datepicker({
                                    minDate: "<?php echo $xdaylater;  ?>",
                                    maxDate: "+22d",
                                    beforeShowDay: noMonday
                                });
                                function noMonday(date) {
                                    if (date.getDay() == 1 || date.getDay() == 6 || date.getDay() == 0) {
                                        return [false];
                                    }
                                    else {
                                        return [true];
                                    }
                                }
                            </script>
                            <?php
                        }
                        elseif ($dt == 6 || $dt == 0){

                            ?>
                            <div class="row vertical-align">
                                <div class="col-md-6 col-xs-6 cartsub-left">Choose Date : </div>
                                <div class="col-md-6 col-xs-6 cartsub-right">
                                    <div class="input-group col-md-8">
                                        <input type="text" id="datepicker" name="datepicker" onchange="this.form.submit()" value="<?php if (isset($_POST["datepicker"])){  echo $_POST["datepicker"];   }   ?>">
                                        <label for="datepicker" class="input-group-addon btn">
                                            <i class="fa fa-calendar"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $('#datepicker').datepicker({
                                    minDate: "<?php echo $xdaylater;   ?>",
                                    maxDate: "+22d",
                                    beforeShowDay: noMonday
                                });
                                function noMonday(date) {
                                    if (date.getDay() == 1 || date.getDay() == 6 || date.getDay() == 0) {
                                        return [false];
                                    }
                                    else {
                                        return [true];
                                    }
                                }
                            </script>
                            <?php
                        }
                        else{

                            ?>
                            <div class="row vertical-align">
                                <div class="col-md-6 col-xs-6 cartsub-left">Choose Date : </div>
                                <div class="col-md-6 col-xs-6 cartsub-right">
                                    <div class="input-group col-md-8">
                                        <input type="text" id="datepicker" name="datepicker" onchange="this.form.submit()" value="<?php if (isset($_POST["datepicker"])){  echo $_POST["datepicker"];   }   ?>">
                                        <label for="datepicker" class="input-group-addon btn">
                                            <i class="fa fa-calendar"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $('#datepicker').datepicker({
                                    minDate: "<?php echo $xdaylater;  ?>",
                                    maxDate: "+22d",
                                    beforeShowDay: $.datepicker.noWeekends

                                });
                            </script>
                            <?php
                        }
                    }
                }
                if ($sampledelivery != 0 && $baskavar == 1){
                    ?>
                    <div class="row vertical-align">
                        <div class="col-md-6 col-xs-6 cartsub-left">Sample Surcharge : </div>
                        <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($sampledelivery,2); ?></div>
                    </div>
                    <?php
                }
                if ($discountprice != ""){
                    ?>
                    <div class="row vertical-align">
                        <div class="col-md-6 col-xs-6 cartsub-left">Discount : </div>
                        <div class="col-md-6 col-xs-6 cartsub-right">&pound;-<?php echo number_format($discountprice,2); ?></div>
                    </div>
                    <?php
                    $total = $total - $discountprice;
                }
                if ($notcard == 1 && $checkout != 1){

                }
                else{
                    ?>
                    <div class="row vertical-align">
                        <div class="col-md-6 col-xs-6 cartsub-left">Total : </div>
                        <?php $entotal = $total + $deliveryprice; ?>
                        <div class="col-md-6 col-xs-6 cartsub-right">&pound;<?php echo number_format($entotal,2); ?></div>
                    </div>
                    <?php
                }
            }
        }
        if ($notcard == 1){

        }
        else{
            if ($postcodeid == "" || empty($postcodeid)){
                ?>
                <div class="space14"></div>
                <p class="text-danger text-right"><i class="fa fa-info-circle fa-lg"></i>&nbsp;Please select postcode for checkout or quotation</p>
                <?php
            }
            else{
                ?>
                <div class="space14" style="margin-top: 14px;"></div>
                <div class="text-right">
                    <input type="button" name="aksiyon" id="checkoutbutton" value="CHECKOUT / GET QUOTE" class="btn btn-danger" />
                </div>
                <?php
            }
        }
    }
    if ($notcard == 1 && $checkout != 1 && ($deliveryprice != 0 || $postcodeid != "")){
        ?>
        <div class="text-right">
            <a href="?p=removedelivery&type=<?php echo $pagequery; ?>&orderid=<?php echo $orderid;  ?>" title="Remove Delivery" class="text-warning" onclick="return confirm('This will delete all delivery information, Are you sure?')">
                <i class="fa fa-times"></i>
            </a>
        </div>
        <?php
    }
}
?>

?p=cartpre&panel=1&orderid=<?php echo $orderid; ?>&type=<?php echo $pagequery; ?>&websiteid=<?php echo $cmpny["websiteid"]; ?>

<?php
$allorder = $db->query("select * from orders");
foreach ($allorder as $all){
    if ($all["orderno"] == "" || empty($all["orderno"])){
        $orderno = 0;
    }
    else{
        $no = $all["orderno"];
        $orderno = $no;
    }
}
$allproforma = $db->query("select * from orders");
foreach ($allproforma as $proforma){
    if ($proforma["proformano"] == "" || empty($proforma["proformano"])){
        $proformano = 0;
    }
    else{
        $noproforma = $proforma["proformano"];
        $proformano = $noproforma;
    }
}
$ilktarih="01.01.2013";//bu ilk kayıt tarihi olsun

$sontarih="05.01.2013";//buda şu anki tarih olsun

$ilktarihstr=strtotime($ilktarih);//ilk tarihi strtotime ile çeviriyom

$sontarihstr=strtotime($sontarih);//ilk tarihi strtotime ile çeviriyom

$fark=($sontarihstr-$ilktarihstr)/86400;//sondan ilki çıkarıp 86400 e bölüyoz bu bize günü verecek

echo "Fark :".$fark." gün <br />";



$ilksaat="10.50.01";//bu ilk saatimiz

$sonsaat="11.50.01";//buda şu anki saat olsun

$ilksaatstr=strtotime($ilksaat);

$sonsaatstr=strtotime($sonsaat);//aynı şekilde saatleride strtotime liyoırum

$fark=$sonsaatstr-$ilksaatstr;//sondan ilki çıkarıyom direk bize saniyeyi verecek

echo "Fark :".$fark." saniye";


?>




