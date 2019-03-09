<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="../css/css1.css" type="text/css" rel="stylesheet">
    <title>Print Jobs</title>
</head>
<body>
<div align="center">
    <?php
    $orderid = $_GET["orderid"];
    $user = $db->query("SELECT users.*, orders.orderdate, orders.* FROM users left join orders on users.id=orders.userid where orders.id='$orderid' order by orders.id desc")->fetch();
    ?>
    <table width="300" border="0" cellspacing="0" cellpadding="0">
        <tbody><tr>
            <td bgcolor="#FFFFFF">
                <div align="center" class="st_sample_print">
                    <div align="center">TRAVERTINE STORE<?php if ($user["company"] == 1){ echo 'eBAY'; }  ?><br>
                        <span class="tre_12">
  0844 545 2002 - www.travertinetilesuk.com</span></div>
                </div></td>
        </tr>
        <tr>
            <td height="10" bgcolor="#FFFFFF"></td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF">
                <div align="center">
                    <span class="tre_12">
                        <?php echo $user["firstname"]."   ".$user["lastname"]; ?><br>
                        <?php echo $user["house"]; ?><br>
                          <?php echo $user["street"]; ?><br>
                          <?php echo $user["city"]; ?><br>
                          <?php echo $user["county"]; ?><br>
                          <?php echo $user["postcode"]; ?>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <td height="10" bgcolor="#FFFFFF"></td>
        </tr>
        </tbody>
    </table>
    <?php
    $basket = $db->query("select basket.*, products.name, products.categoryid, products.pr_id as pidi from basket inner join products on basket.productid = products.pr_id where basket.orderid='$orderid'");
    if ($basket->rowCount()){
        foreach($basket as $bs){
            ?>
            <table width="300" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td height="15"></td>
                </tr>
                <tr>
                    <td height="40" bgcolor="#FFFFFF">
                        <div align="center">
                            <img src="../../img/ts_logo.jpg" alt="" width="200" height="30">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td height="30" bgcolor="#FFFFFF">
                        <div align="center">
                            <span class="tre_12">
                                <strong>
                                    <?php echo $bs["quantity"]." X ".$bs["name"]; ?>
                                </strong>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td height="5" bgcolor="#FFFFFF"></td>
                </tr>
                <tr>
                    <td colspan="2" valign="middle" bgcolor="#FFFFFF" class="padding_left_right">
                        <div align="justify">
                            <span class="tre_9">Please note: This material is natural so please expect variations in colour tones, markings and texture. Small samples are intended as a guide and will not showall variations in any particular stone.
                            </span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td height="2" colspan="2" valign="top" bgcolor="#FFFFFF"></td>
                </tr>
                <tr>
                    <td height="15" colspan="2" valign="top" bgcolor="#FFFFFF">
                        <div align="center">
                            <span class="tre_12">For any questions please call:</span>
                            <span class="tre_tel">
                                <strong>0844 545 2002</strong>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td height="5" valign="top"></td>
                </tr>
                </tbody>
            </table>
    <?php
        }
    }
    ?>
    <p>&nbsp;</p>
</div>
</body>
</html>