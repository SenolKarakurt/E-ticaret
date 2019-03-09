<?php
ob_start();
session_start();
include "include/connection.php";

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>Stone Deals yeni</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="css/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <link href="css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/login.min.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link href="css/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="js/jquery-notific8/jquery.notific8.min.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="css/themes/light.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->

<?php //session control

$p=$_GET["p"];

if (!isset($_SESSION["admin"]["username"])) {
    if (isset($_POST["login"])){
        $username = strip_tags(trim($_POST["username"]));
        $password = md5(sha1(strip_tags(trim($_POST["password"]))));
        $userSelect = $db->query("select * from panelusers where username='$username' and password='$password'", PDO::FETCH_ASSOC);

        if ($userSelect->rowCount()){
            foreach ($userSelect as $user){
                //$_SESSION["admin"] = $user["username"];
                $_SESSION["admin"] = array(
                    "id"           => $user["id"],
                    "username"     => $user["username"],
                    "email"        => $user["email"],
                    "websiteid"    => $user["websiteid"]
                );
                $date = date("Y-m-d H:i:s");
                $login = $db->query("update panelusers set [lastlogin]='$date' where id='".$user["id"]."'");
                header("Location: index.php?p=websites");
            }
        }
    }else{
        ?>
        <body class="login">
    <div class="logo">
        <a href="index.php">
            <img src="/img/logo-big.png" alt="" />
        </a>
    </div>

    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <form class="login-form" action="" method="post">
            <h3 class="form-title font-green">Sign In</h3>
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span> Enter any username and password. </span>
            </div>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Username</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="text" required autocomplete="off" placeholder="Username" name="username">
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" required autocomplete="off" placeholder="Password" name="password">
            </div>
            <div class="form-actions text-center">
                <button type="submit" name="login" class="btn green uppercase" style="width: 100%;">Login</button>
            </div>
        </form>
        <!-- END LOGIN FORM -->
    </div>
        </body>
        <?php
    }
}
else{ // ************** Session varsa ********************* //
    $websiteQuery = $db->query("select * from websites",PDO::FETCH_ASSOC);

    ?>
    <body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo">
    <?php
    if (empty($_SESSION["admin"]["websiteid"])){ // ************** Web site seçili değilse ********************* //?>
        <div class="page-container">
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content" style="min-height:1117px; margin-left: 0;">
                    <!-- BEGIN PAGE BREADCRUMB -->
                    <div class="page-title" id="page-name">

                    </div>
                    <p></p>
                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="note note-info"  style="border: none;">
                        <form id="myform" action="" method="post" style="text-align: center">
                            <select name="WebSiteID" id="WebSiteID" class="btn btn-lg green input-medium">
                                <option value="" disabled="disabled" selected="selected">Controllable Website</option>
                                <?php if ($websiteQuery->rowCount()){
                                    foreach ($websiteQuery as $webSiteName) {
                                        echo '<option value="'.$webSiteName["websiteid"].'">'.$webSiteName["websitename"].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </form>
                    </div>
                    <!-- END PAGE BASE CONTENT -->
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
    <?php }    // ************** Web site seçildi, tüm işlemler burada dönecek ********************* //
    //$activeSite = $db->query("select * from websites where websiteid='".$_SESSION["admin"]["websiteid"]."'");
    ?>

    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="index.php">
                    <img src="../assets/layouts/layout4/img/logo-light.png" alt="logo" class="logo-default" /> </a>
                <!--<div class="menu-toggler sidebar-toggler">
                    <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header
                </div>-->
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN PAGE ACTIONS -->
            <!-- DOC: Remove "hide" class to enable the page header actions -->

            <div class="page-actions">
                <div class="btn-group">

                </div>
            </div>

            <!-- END PAGE ACTIONS -->
            <!-- BEGIN PAGE TOP -->
            <div class="page-top">
                <!-- BEGIN HEADER SEARCH BOX -->
                <!-- DOC: Apply "search-form-expanded" right after the "search-form" class to have half expanded search box -->
                <form class="search-form" action="page_general_search_2.html" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" placeholder="Search..." name="query">
                        <span class="input-group-btn">
                                <a href="javascript:;" class="btn submit">
                                    <i class="icon-magnifier"></i>
                                </a>
                            </span>
                    </div>
                </form>
                <!-- END HEADER SEARCH BOX -->
                <!-- BEGIN TOP NAVIGATION MENU -->
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <li class="separator hide"> </li>
                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        <!-- END NOTIFICATION DROPDOWN -->
                        <li class="separator hide"> </li>
                        <!-- BEGIN INBOX DROPDOWN -->
                        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->

                        <!-- END INBOX DROPDOWN -->
                        <li class="separator hide"> </li>

                        <li class="dropdown dropdown-user dropdown-dark">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                <span class="username username-hide-on-mobile"> <?php echo $_SESSION["admin"]["username"]; ?> </span>
                                <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                                <img alt="" class="img-circle" src="../assets/layouts/layout4/img/avatar9.jpg" /> </a>
                            <ul class="dropdown-menu dropdown-menu-default">

                                <li>
                                    <a href="index.php?p=logout">
                                        <i class="icon-key"></i> Log Out </a>
                                </li>
                            </ul>
                        </li>
                        <!-- END USER LOGIN DROPDOWN -->
                        <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                        <li class="dropdown dropdown-extended quick-sidebar-toggler">
                            <span class="sr-only">Toggle Quick Sidebar</span>
                            <i class="icon-logout"></i>
                        </li>
                        <!-- END QUICK SIDEBAR TOGGLER -->
                    </ul>
                </div>
                <!-- END TOP NAVIGATION MENU -->
            </div>
            <!-- END PAGE TOP -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->



    <div class="clearfix"></div>
    <!-- END HEADER & CONTENT DIVIDER -->

    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <div class="page-sidebar navbar-collapse collapse">
                <ul class="page-sidebar-menu" data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                    <li class="nav-item start active open">
                        <a href="index.php" class="nav-link nav-toggle">
                            <i class="icon-home"></i>
                            <span class="title">HomePage</span>
                            <span class="selected"></span>
                        </a>
                        <a href="index.php?p=settings" class="nav-link nav-toggle">
                            <i class="icon-settings"></i>
                            <span class="title">Settings</span>
                            <span class="selected"></span>
                        </a>
                        <a href="index.php?p=users" class="nav-link nav-toggle">
                            <i class="icon-diamond"></i>
                            <span class="title">Users</span>
                            <span class="selected"></span>
                        </a>
                        <a href="index.php?p=slider" class="nav-link nav-toggle">
                            <i class="icon-layers"></i>
                            <span class="title">Slider</span>
                            <span class="selected"></span>
                        </a>
                        <a href="index.php?p=websites" class="nav-link nav-toggle">
                            <i class="icon-wallet"></i>
                            <span class="title">WebSite</span>
                            <span class="selected"></span>
                        </a>
                    </li>
            </div>
            <!-- END SIDEBAR -->
        </div>
        <!-- END SIDEBAR -->

        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content" style="min-height:1117px">
                <div class="page-head">
                    <!-- BEGIN PAGE TITLE -->
                    <div class="page-title" id="page-name">



                    </div>
                    <!-- END PAGE TITLE -->

                </div>
                <!-- END PAGE HEAD-->
                <!-- BEGIN PAGE BREADCRUMB -->
                <p></p>
                <!-- END PAGE BREADCRUMB -->

                <?php


                if ($p == "users"){

                ?>

                <div class="row">
                    <div class="col-md-12">
                        <a class="btn-outline sbold" data-toggle="modal" href="#insert_user">
                            <button class="btn btn-success">Add User</button>
                        </a>
                    </div>
                </div>

                <div class="portlet box red" id="user_list" style="margin-top: 20px;">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cogs"></i>Users
                            <?php  echo $_SESSION["admin"]["username"];
                            echo $_SESSION["admin"]["id"];
                            echo $_SESSION["admin"]["email"];
                            echo $_SESSION["admin"]["websiteid"];
                            ?>
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                            <a href="#portlet-config" data-toggle="modal" class="config" data-original-title=""
                               title=""> </a>
                            <a href="javascript:;" class="reload" data-original-title="" title=""> </a>
                            <a href="javascript:;" class="remove" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-responsive">
                            <table class="table" id="table1">
                                <thead>
                                <tr>
                                    <th> #</th>
                                    <th> User Name </th>
                                    <th> LastLogin </th>
                                    <th> Email </th>
                                    <th> Name </th>
                                    <th> UserType </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // $db->query("select * from panelusers", PDO::FETCH_ASSOC);
                                $query_user = $db->query("select * from panelusers");
                                // $query_user = $db->prepare("select * from panelusers");
                                //  $query_user->execute();

                                //while ($user_data = $query_user->fetch(PDO::FETCH_ASSOC)) {
                                foreach ($query_user as $user_data){
                                    $user_id = $user_data["id"];
                                    ?>
                                    <tr>
                                        <td> <?php echo $user_data["id"];  ?> </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>



                        <div class="modal fade bs-modal-lg" id="insert_user" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title">Modal Title</h4>
                                    </div>
                                    <div class="modal-body">

                                        <div class="form-horizontal" id="form-horizontal">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Username</label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="username" id="username" class="form-control input-circle" placeholder="Username">
                                                    </div>
                                                    <label class="col-md-2 control-label">Email</label>
                                                    <div class="col-md-4">
                                                        <input type="email" name="email" id="email" class="form-control input-circle" placeholder="email" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Password</label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="password" id="password" class="form-control input-circle" placeholder="Password">
                                                    </div>
                                                    <label class="col-md-2 control-label">Usertype</label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="usertype" id="usertype" class="form-control input-circle" placeholder="usertype">
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Name</label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="name" id="name" class="form-control input-circle" placeholder="Name">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>

                                                    <input type="submit" class="btn green" id="user_add" name="user_add" value="add" onclick="isNull();">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="modal fade bs-modal-lg" id="edit_user" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title">Modal Title</h4>
                                    </div>
                                    <div class="modal-body">


                                        <div class="form-horizontal" id="form-horizontal">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Username</label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="username2" id="username2" class="form-control input-circle" placeholder="Username" value="">
                                                    </div>
                                                    <label class="col-md-2 control-label">Email</label>
                                                    <div class="col-md-4">
                                                        <input type="email" name="email2" id="email2" class="form-control input-circle" placeholder="email" required value="">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Password</label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="password2" id="password2" class="form-control input-circle" placeholder="Password" value="">
                                                    </div>
                                                    <label class="col-md-2 control-label">Usertype</label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="usertype2" id="usertype2" class="form-control input-circle" placeholder="usertype" value="">
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 control-label">Name</label>
                                                    <div class="col-md-4">
                                                        <input type="text" required name="name2" id="name2" class="form-control input-circle" placeholder="Name" value="">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                                    <input type="hidden" class="btn green user_update" id="user_hidden" name="user_update" value="">
                                                    <input type="submit" class="btn green user_update" id="user_update" name="user_update" value="update">
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>





                    </div>
                </div>
                    <?php

                    } elseif ($p == "logout"){
                        session_start();
                        session_destroy();
                        header("Location: index.php?p=login");
                    }//else{//**************** Hiçbir sayfa yada işlem yoksa index'e gitsin **************//
                    //header("Location: index.php");
                    // }
                    ?>


                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- END CONTAINER -->
        </div>




        <!--[if lt IE 9]>
        <script src="js/respond.min.js"></script>
        <script src="js/excanvas.min.js"></script>
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/js.cookie.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="js/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="js/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="js/app.min.js" type="text/javascript"></script>
        <script src="js/jquery.validate.js" type="text/javascript"></script>
        <script src="js/additional-methods.min.js" type="text/javascript"></script>
        <script src="js/select2.full.min.js" type="text/javascript"></script>
        <script src="js/ui-modals.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="js/app.min.js" type="text/javascript"></script>
        <script src="js/login.min.js" type="text/javascript"></script>
        <script src="js/custom.js" type="text/javascript"></script>
        <script src="js/jquery-notific8/ui-notific8.js" type="text/javascript"></script>
        <script src="js/jquery-notific8/jquery.notific8.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="js/app.min.js" type="text/javascript"></script>
        <script src="js/bootbox/bootbox.min.js" type="text/javascript"></script>
        <script src="js/bootbox/ui-bootbox.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="js/layout.min.js" type="text/javascript"></script>
        <script src="js/demo.min.js" type="text/javascript"></script>
        <script src="js/quick-sidebar.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>
    <?php
}
//ob_end_flush();?>

</html>
