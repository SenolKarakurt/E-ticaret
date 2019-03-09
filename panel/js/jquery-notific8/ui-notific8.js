function UINotific8(output,message,status,userID) {
    if (output == "true") {
        if (status == "lime") {
            var settings = {
                    theme: status,
                    sticky: "",
                    horizontalEdge: "right",
                    verticalEdge: "top"
                },
                $button = $(this);

            settings.heading = "Operation Correctly";
            settings.life = "3000";
            $.notific8('zindex', 11500);
            $.notific8(message, settings);
            $button.attr('disabled', 'disabled');
            setTimeout(function () {
                $button.removeAttr('disabled');
            }, 3000);

        }
        else if (message == "Login"){
            var settings = {
                    theme: status,
                    sticky: "",
                    horizontalEdge: "right",
                    verticalEdge: "top"
                },
                $button = $(this);

            settings.heading = "Login";
            settings.life = "3000";
            $.notific8('zindex', 11500);
            $.notific8(message, settings);
            $button.attr('disabled', 'disabled');
            setTimeout(function () {
                $button.removeAttr('disabled');
            }, 3000);
        }
    }
    else if(output == "false"){
        if (status == "ruby"){
            var settings = {
                    theme: status,
                    sticky: "",
                    horizontalEdge: "right",
                    verticalEdge: "top"
                },
                $button = $(this);

            settings.heading = "Operation Failed";
            settings.life = "3000";
            $.notific8('zindex', 11500);
            $.notific8(message, settings);
            $button.attr('disabled', 'disabled');
            setTimeout(function () {
                $button.removeAttr('disabled');
            }, 3000);
        }
        else if(status == "tangerine"){
            var settings = {
                    theme: status,
                    sticky: "",
                    horizontalEdge: "right",
                    verticalEdge: "top"
                },
                $button = $(this);

            settings.heading = "Operation Failed";
            settings.life = "3000";
            $.notific8('zindex', 11500);
            $.notific8(message, settings);
            $button.attr('disabled', 'disabled');
            setTimeout(function () {
                $button.removeAttr('disabled');
            }, 3000);
        }
    }
}

$(document).ready(function(){

    var cookies = $.cookie("testcookie");
    if (cookies) {
        var splt_cookies = cookies.split('|');
        if (splt_cookies[0] == "true") {
            var status = "lime";
            var message = splt_cookies[1];
            UINotific8("true", message, status);
        }
        else if (splt_cookies[0] == "false") {
            var status = "ruby";
            var message = splt_cookies[1];
            UINotific8("false", message, status);
        }
    }
});

$(document).ready(function(){// operations'dan yanlız gelen datalar

    $(document).on('click', '#editPages', function(e){

        e.preventDefault();

        var pid = $(this).data('id'); // get id of clicked row

        $('#dynamic-content').hide(); // hide dive for loader
        $('#modal-loader').show();  // load ajax loader
        //$('input[type="checkbox"]').prop('checked',false);
        $.ajax({
            url: 'include/operations.php?pg=pages&process=editPages',
            type: 'POST',
            data: 'pid='+pid,
            dataType: 'json'
        })
            .done(function(data){
                $('#website2 option[value='+data.websiteid+']').prop('selected',false);
                $('#dynamic-content').hide(); // hide dynamic div
                $('#dynamic-content').show(); // show dynamic div
                $('#pageImg').attr("src","../img/thumbs/"+data.image);
                $('#pageImgDel').attr("href","../img/thumbs/"+data.image);
                $('#pageimg').attr("value",data.image);
                $('#pageTitle').val(data.title);
                $('#pageTitle2').val(data.title2);
                $('#website2 option[value='+data.websiteid+']').prop('selected',true);
                $('#id').val(pid);
                if (data.status === "1"){
                    $('#pageStatus').prop('checked',true);
                }else {
                    $('#pageStatus').prop('checked',false);
                }
                $("#page-form .page-form").empty();
                    var markup45 = '<div id="'+data.id+'" class=" cbp-caption-active cbp-caption-overlayBottomReveal cbp-ready">'+
                        '<div class="cbp-item">'+
                        '<div class="cbp-item-wrapper">'+
                        '<div class="cbp-caption">'+
                        '<div class="cbp-caption-defaultWrap">'+
                        '<img src="../img/thumbs/'+data.image+'" alt="" id="pageImg">'+
                        '</div>'+
                        '<div class="cbp-caption-activeWrap">'+
                        '<div class="cbp-l-caption-alignCenter">'+
                        '<div class="cbp-l-caption-body">'+
                        '<a href="#" class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase delete-img" onclick="pgimgdlt('+data.id+')">Delete</a>'+
                        '</div>'+
                        '</div>'+
                        '</div>'+
                        '</div>'+
                        '</div>'+
                        '</div>'+
                        '</div>';
                    //var markup30 = '<img src="../img/products/thumbs/'+product_data["images"][n]["imagename"]+'" alt="" class="pr_image2">';
                //../img/thumbs/'+data.image+'
                    $("#page-form .page-form").append(markup45);

                CKEDITOR.instances['pageDescription'].setData(data.description);
                CKEDITOR.instances['pageText'].setData(data.pagecontent);
                $('#modal-loader').hide();    // hide ajax loader
            })
            .fail(function(){
                $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
            });
    });
    $(document).on('click', '#editemailformat', function(e){
        e.preventDefault();

        var pid = $(this).data('id'); // get id of clicked row
        $.ajax({
            url: 'include/operations.php?pg=emailformats&process=editemailformat&q=get',
            type: 'POST',
            data: 'frID='+pid,
            dataType: 'json'
        })
        .done(function(data){
            $('#dynamic-content').hide(); // hide dynamic div
            $('#dynamic-content').show(); // show dynamic div

            var stid = data.websiteid;

            $("#format_form #emailformatwebsite").find("option:selected").removeAttr("selected");
            $('#format_form #emailformatwebsite option[value='+stid+']').prop("selected",false);

            $("#format_form #forwhat").attr("value",data.forwhat);
            $("#format_form #subject").attr("value",data.subject);
            //$("#format_form #emailformatdescription").text(format_data["format_data"]["emailtext"]);

            $('#format_form #emailformatwebsite option[value='+stid+']').prop("selected",true);

            $("#format_form #emailformatid").attr("value",pid);

            CKEDITOR.instances['emailformatdescription'].setData(data.emailtext);
            $('#modal-loader').hide();    // hide ajax loader
        })
        .fail(function(){
            $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
        });
    })
});

$(document).ready(function(){//"product name" sayfasının boş döngülerinin temislenme bloğu....
    var tds  = $("#product_names table thead th").length;

    $("#product_names table tbody tr").each(function() {
        var i = 0;
        $(this).children().each(function() {
            if ($(this).html() == "") {
                i++;
            }
        });
        if(i==tds){
            $(this).remove();
        }
    });
});

$(document).ready(function(){
    $.post('include/operations.php?pg=websites&process=website_edit&q=getSites',function (output) {
        var sites = output.split("\n");
        var site_id = sites[0];
        var site_id2 = sites[1];
        var cat_site = $("#cat_site").val();
        $.post('include/operations.php?pg=categories&process=category_edit&q=get',{site_id:site_id},function (output) {
            $(".cathtml").html(output);
            $("#form-horizontal-pr2 #pr_cat_top_id2").html(output);
        });
        $.post('include/operations.php?pg=categories&process=category_edit&q=get',{site_id:cat_site},function (output) {
            $("#cat_top_id").html(output);
            $("#cat_top_id2").html(output);
        });
        $.post('include/operations.php?pg=products&process=product_edit&q=getRelated',{site_id:site_id},function (output) {
            $("#product_related").html(output);
            $("#product_related2").html(output);
        });
    });
});

function catSelect(id){
    var site_id = id;
    $.post('include/operations.php?pg=categories&process=category_edit&q=get',{site_id:site_id},function (output) {
        $(".cathtml").html(output);
        $("#form-horizontal-pr2 #pr_cat_top_id2").html(output);
    });
}

function pgimgdlt(id) {
    var imgID = id;
    bootbox.confirm('Silme İşleminden Emin Misiniz?', function (result) {
        if (result){
            $.get('include/operations.php?pg=pages&process=pg_img',{imgID:imgID},function (output) {
                var status = "lime";
                var message = "Deleted";
                UINotific8(output,message,status,imgID);
                document.getElementById(imgID).style.display = "none";
            })
        }
    });
}

function getAddress() {
    $('#getAddressResult').html("");
    $.ajax({
        type: "POST",
        url: "include/operations.php?pg=customer&process=getAddress",
        data: "pr_id="+ $("#pr_id").val(),
        cache: false,
        success: function (data) {
            $('#getAddressResult').html(data);
        },
        error: function(err) {
            console.log(err);
        }
    });
}

function getProformas(){
    $('#getProformasResult #proformaResult tbody').html("");
    $.ajax({
        type: "POST",
        url: "include/operations.php?pg=customer&process=getProformas",
        data: "proforma_id="+ $("#proforma_id").val(),
        cache: false,
        success: function (data) {
            $('#getProformasResult #proformaResult tbody').html(data);
        },
        error: function(err) {
            console.log(err);
        }
    });
}

function getOrders(){
    $('#getOrdersResult #ordersResult tbody').html("");
    $.ajax({
        type: "POST",
        url: "include/operations.php?pg=customer&process=getOrders",
        data: "order_id="+ $("#orders_id").val(),
        cache: false,
        success: function (data) {
            $('#getOrdersResult #ordersResult tbody').html(data);
        },
        error: function(err) {
            console.log(err);
        }
    });
}

function getInvoices(){
    $('#getInvoicesResult #invoicesResult tbody').html("");
    $.ajax({
        type: "POST",
        url: "include/operations.php?pg=customer&process=getInvoices",
        data: "invoice_id="+ $("#invoices_id").val(),
        cache: false,
        success: function (data) {
            $('#getInvoicesResult #invoicesResult tbody').html(data);
        },
        error: function(err) {
            console.log(err);
        }
    });
}

function getTransactions(){
    $('#getTransactionsResult #sample_15 tbody').html("");
    $.ajax({
        type: "POST",
        url: "include/operations.php?pg=customer&process=getTransactions",
        data: "transaction_id="+ $("#transactions_id").val(),
        cache: false,
        success: function (data) {
            $('#getTransactionsResult #sample_15 tbody').html(data);
        },
        error: function(err) {
            console.log(err);
        }
    });
}

function getAddressDetails(){
    var adr_id = $("#selectAddress").val();
    $.post("include/operations.php?pg=salesOrder&process=getAddressDetails",{
        adr_id: adr_id
    },function (output) {
        $(".addressDetails").html(output);
    })
}

function modalgetAddressDetails(num){
    var adr_id = $("#selectAddress"+num+"").val();
    $.post("include/operations.php?pg=salesOrder&process=getAddressDetails",{
        adr_id: adr_id
    },function (output) {
        $(".addressDetails"+num+"").html(output);
    })
}

function getAddressNames() {
    var pr_id = $("#selectCustomer").val();
   $("#selectAddress").html("");

    $.post("include/operations.php?pg=salesOrder&process=getAddress",{
        pr_id: pr_id
    },function (output) {
        $("#selectAddress").html(output);
        getAddressDetails();
    })
}

function modalgetAddressNames(num) {
    var pr_id = $("#selectCustomer"+num+"").val();
   $("#selectAddress"+num+"").html("");

    $.post("include/operations.php?pg=salesOrder&process=getAddress",{
        pr_id: pr_id
    },function (output) {
        $("#selectAddress"+num+"").html(output);
        modalgetAddressDetails();
    })
}

function updateAddress(addressID) {
    var locationName2ad = $("#locationName2ad"+addressID).val();
    var house2ad = $("#house2ad"+addressID).val();
    var street2ad = $("#street2ad"+addressID).val();
    var city2ad = $("#city2ad"+addressID).val();
    var county2ad = $("#county2ad"+addressID).val();
    var postcode2ad = $("#postcode2ad"+addressID).val();
    var country2ad = $("#country2ad"+addressID).val();
    if($("#isMain2ad"+addressID).is(":checked")){var isMain2ad = "1"}else{var isMain2ad = "0"};

    /*
    if (customerName == "" || contactName == "" || contactName2 == "" || tel == "" || tel2 == "" || house == "" || street == "" || city == "" || county == "" ||postcode == "" || country == ""){
        var settings = {
                theme: "smoke",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "NULL";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("NOT NULL SPACE", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else{
        $.ajax({
            url: "include/operations.php?pg=customer&process=addcompany",
            type: "POST",
            data: {
                address:"address",
                addressID:addressID,
                locationName2ad:locationName2ad,
                house2ad:house2ad,
                street2ad:street2ad,
                city2ad:city2ad,
                county2ad:county2ad,
                postcode2ad:postcode2ad,
                country2ad:country2ad,
                isMain2ad:isMain2ad
            },
            success: function (output){
                location.reload();
            }
        });
    }
    */

    $.post('include/operations.php?pg=customer&process=update',{
        address:"address",
        addressID:addressID,
        locationName2ad:locationName2ad,
        house2ad:house2ad,
        street2ad:street2ad,
        city2ad:city2ad,
        county2ad:county2ad,
        postcode2ad:postcode2ad,
        country2ad:country2ad,
        isMain2ad:isMain2ad
    },function (output) {
        if (output == 1){
            var status = "lime";
            var message = "Customer Address Details Updated";
            UINotific8("true",message,status);
            getAddress();
        }
        else if(output == 0){
            var status = "ruby";
            var message = "Customer Address Details Not Updated!..";
            UINotific8("false",message,status);
        }
    })

}

function editCustomer(pr_id) {
    $("#editCustomer .active").removeClass("active");
    $("#editCustomer in").removeClass("in");
    $(".firstSelect").addClass("active");
    $("#entryDetails").addClass("active in");

    //$("#customerName2, #contactName21,#contactName22,#tel21, #tel22, #fax2, #vat2, #mail2, #web2, #note2, #balance2").val("");
    $.get('include/operations.php?pg=customer&process=editCustomer', {pr_id:pr_id},function(output){
        var customerData = JSON.parse(output);
        $("#customerName2").attr("value", customerData["getCustomer"]["customername"]);
        $("#contactName21").attr("value", customerData["getCustomer"]["firstname"]);
        $("#contactName22").attr("value", customerData["getCustomer"]["lastname"]);
        $("#tel21").attr("value", customerData["getCustomer"]["tel1"]);
        $("#tel22").attr("value", customerData["getCustomer"]["tel2"]);
        $("#fax2").attr("value", customerData["getCustomer"]["fax"]);
        $("#vat2").attr("value", customerData["getCustomer"]["vatno"]);
        $("#mail2").attr("value", customerData["getCustomer"]["email"]);
        $("#web2").attr("value", customerData["getCustomer"]["web"]);
        $("#note2").html(customerData["getCustomer"]["note"]);
        $("#pr_id").attr("value", pr_id);
        $("#proforma_id").attr("value", pr_id);
        $("#orders_id").attr("value", pr_id);
        $("#invoices_id").attr("value", pr_id);
        $("#transactions_id").attr("value", pr_id);
        $("#prid-nw").attr("value", pr_id);
        $("#addNewAddressPrId").attr("value", pr_id);
        //$("select[name='comboSelectfpoc'] option[value=" + customerData['getCustomer']['fpoc'] + "]").attr("selected", true);
        $("select[name='comboSelectcompanyType'] option[value=" + customerData['getCustomer']['companytype'] + "]").attr("selected", true);
        $("select[name='comboSelectpaymentterm'] option[value=" + customerData['getCustomer']['paymentterm'] + "]").attr("selected", true);


        if (customerData["getCustomer"]["issupplier"] === "1") {
            $(".comp").html("");
            $(".comp").empty();
            $(".comp").html('<label class="col-md-2 control-label supp">Is Supplier</label> ' +
            '<div class="col-md-2 supp">'+
                '<div class="md-checkbox" style="margin-top: 5px;">'+
                '<input type="checkbox" id="isSupplier2" name="isSupplier2" class="md-check">'+
                '<label for="isSupplier2">'+
                '<span class="inc"></span>'+
                '<span class="check"></span>'+
                '<span class="box"></span>'+
                '</label>'+
                '</div>'+
                '</div>'+
            '<label class="col-md-2 control-label">Is Inactive</label>'+
                '<div class="col-md-2">'+
                '<div class="md-checkbox" style="margin-top: 5px;">'+
                '<input type="checkbox" id="isinactive" name="isinactive" class="md-check">'+
                '<label for="isinactive">'+
                '<span class="inc"></span>'+
                '<span class="check"></span>'+
                '<span class="box"></span>'+
                '</label>'+
                '</div>'+
                '</div>');
            $("#isSupplier2").prop("checked", true);
        }

        else if (customerData["getCustomer"]["iscustomer"] === "1") {
            $(".comp").html("");
            $(".comp").empty();
            $(".comp").html('<label class="col-md-2 control-label">Is Customer</label> ' +
                '<div class="col-md-2">'+
                '<div class="md-checkbox" style="margin-top: 5px;">'+
                '<input type="checkbox" id="isCustomer2" name="isCustomer2" class="md-check">'+
                '<label for="isCustomer2">'+
                '<span class="inc"></span>'+
                '<span class="check"></span>'+
                '<span class="box"></span>'+
                '</label>'+
                '</div>'+
                '</div>'+
                '<label class="col-md-2 control-label">Is Inactive</label>'+
                '<div class="col-md-2">'+
                '<div class="md-checkbox" style="margin-top: 5px;">'+
                '<input type="checkbox" id="isinactive" name="isinactive" class="md-check">'+
                '<label for="isinactive">'+
                '<span class="inc"></span>'+
                '<span class="check"></span>'+
                '<span class="box"></span>'+
                '</label>'+
                '</div>'+
                '</div>');
            $("#isCustomer2").prop("checked", true);
        }
        else{
            if (customerData["getCustomer"]["issupplier"] != "1"){
                $(".comp").html("");
                $(".comp").empty();
                $(".comp").html('<label class="col-md-2 control-label">Is Supplier</label> ' +
                    '<div class="col-md-2">'+
                    '<div class="md-checkbox" style="margin-top: 5px;">'+
                    '<input type="checkbox" id="isSupplier2" name="isSupplier2" class="md-check">'+
                    '<label for="isSupplier2">'+
                    '<span class="inc"></span>'+
                    '<span class="check"></span>'+
                    '<span class="box"></span>'+
                    '</label>'+
                    '</div>'+
                    '</div>'+
                    '<label class="col-md-2 control-label">Is Inactive</label>'+
                    '<div class="col-md-2">'+
                    '<div class="md-checkbox" style="margin-top: 5px;">'+
                    '<input type="checkbox" id="isinactive" name="isinactive" class="md-check">'+
                    '<label for="isinactive">'+
                    '<span class="inc"></span>'+
                    '<span class="check"></span>'+
                    '<span class="box"></span>'+
                    '</label>'+
                    '</div>'+
                    '</div>');
                $("#isSupplier2").prop("checked", false);
            }
            else{
                $(".comp").html("");
                $(".comp").empty();
                $(".comp").html('<label class="col-md-2 control-label">Is Customer</label> ' +
                    '<div class="col-md-2">'+
                    '<div class="md-checkbox" style="margin-top: 5px;">'+
                    '<input type="checkbox" id="isCustomer2" name="isCustomer2" class="md-check">'+
                    '<label for="isCustomer2">'+
                    '<span class="inc"></span>'+
                    '<span class="check"></span>'+
                    '<span class="box"></span>'+
                    '</label>'+
                    '</div>'+
                    '</div>'+
                    '<label class="col-md-2 control-label">Is Inactive</label>'+
                    '<div class="col-md-2">'+
                    '<div class="md-checkbox" style="margin-top: 5px;">'+
                    '<input type="checkbox" id="isinactive" name="isinactive" class="md-check">'+
                    '<label for="isinactive">'+
                    '<span class="inc"></span>'+
                    '<span class="check"></span>'+
                    '<span class="box"></span>'+
                    '</label>'+
                    '</div>'+
                    '</div>');
                $("#isCustomer2").prop("checked", false);
            }

        }
        if (customerData["getCustomer"]["isinactive"] === "1") {
            $("#isinactive").prop("checked", true);
        }
        else{
            $("#isinactive").prop("checked", false);
        }
    });
}

function addCustomer() {
         var customerName = $("#addCustomer #customerName").val();
         var contactName = $("#addCustomer #contactName").val();
         var contactName2 = $("#addCustomer #contactName2").val();
         var tel = $("#addCustomer #tel").val();
         var tel2 = $("#addCustomer #tel2").val();
         var fax = $("#addCustomer #fax").val();
         var vat = $("#addCustomer #vat").val();
         var mail = $("#addCustomer #mail").val();
         var web = $("#addCustomer #web").val();
         var comboSelectcompanyType = $("#addCustomer #comboSelectcompanyType").val();
         //var comboSelectfpoc = $("#addCustomer #comboSelectfpoc").val();
         var comboSelectpaymentterm = $("#addCustomer #comboSelectpaymentterm").val();
         var balance = $("#addCustomer #balance").val();
         //var isSupplier = $("#addCustomer #isSupplier").val();
         var isCustomer = $("#addCustomer #isCustomer").val();
         var note = $("#addCustomer #note").val();
         var locationName = $("#addCustomer #locationName").val();
         var house = $("#addCustomer #house").val();
         var street = $("#addCustomer #street").val();
         var city = $("#addCustomer #city").val();
         var county = $("#addCustomer #county").val();
         var postcode = $("#addCustomer #postcode").val();
         var country = $("#addCustomer #country").val();
         var isMain = $("#addCustomer #isMain").val();

    if (customerName == "" || contactName == "" || contactName2 == "" || tel == "" || tel2 == "" || house == "" || street == "" || city == "" || county == "" || postcode == "" || country == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else{
        $.ajax({
            url: "include/operations.php?pg=customer&process=addcompany",
            type: "POST",
            data: {
                customerAdd: "customerAdd",
                customerName: customerName,
                contactName: contactName,
                contactName2: contactName2,
                tel: tel,
                tel2: tel2,
                fax: fax,
                vat: vat,
                mail: mail,
                web: web,
                comboSelectcompanyType: comboSelectcompanyType,
                //comboSelectfpoc: comboSelectfpoc,
                comboSelectpaymentterm: comboSelectpaymentterm,
                balance: balance,
                //isSupplier: isSupplier,
                isCustomer: isCustomer,
                note: note,
                locationName: locationName,
                house: house,
                street: street,
                city: city,
                county: county,
                country: country,
                postcode: postcode,
                isMain: isMain
            },
            success: function (output){
                location.reload();
            }
        });
    }

         /*
        $.post("include/operations.php?pg=customer&process=addcompany",
        {
            customerAdd: "customerAdd",
            customerName: customerName,
            contactName: contactName,
            contactName2: contactName2,
            tel: tel,
            tel2: tel2,
            fax: fax,
            vat: vat,
            mail: mail,
            web: web,
            comboSelectcompanyType: comboSelectcompanyType,
            comboSelectfpoc: comboSelectfpoc,
            comboSelectpaymentterm: comboSelectpaymentterm,
            balance: balance,
            isSupplier: isSupplier,
            isCustomer: isCustomer,
            note: note,
            locationName: locationName,
            house: house,
            street: street,
            city: city,
            county: county,
            country: country,
            postcode: postcode,
            isMain: isMain
        },
        function(data, status){
            if (data == "true"){
                var status = "lime";
                var message = "New Customer Added";
                UINotific8(data,message,status);
                location.reload();
                $("#addCustomer").modal("hide");
            }
            else if(data == "false"){
                var status = "ruby";
                var message = "Customer Not Added";
                UINotific8(data,message,status);
                location.reload();
            }
        });
        */
}

function getCustomers() {
    $("#selectCustomer").html("");
    $.post("include/operations.php?pg=customer&process=getCustomer",{

    },function (output) {
        $("#selectCustomer").html(output);
    })
}

function modalgetCustomers(num) {
    $("#selectCustomer"+num+"").html("");
    $.post("include/operations.php?pg=customer&process=getCustomer",{

    },function (output) {
        $("#selectCustomer"+num+"").html(output);
    })
}

function updateDetails() {
    var customerName2 = $("#customerName2").val();
    var contactName21 = $("#contactName21").val();
    var contactName22 = $("#contactName22").val();
    var tel21 = $("#tel21").val();
    var tel22 = $("#tel22").val();
    var fax2 = $("#fax2").val();
    var vat2 = $("#vat2").val();
    var mail2 = $("#mail2").val();
    var web2 = $("#web2").val();
    var note2 = $("#note2").val();
    var pr_id = $("#pr_id").val();
    //var fpoc2 = $("#entryDetails select[name='comboSelectfpoc']").val();
    var companyType2 = $("#entryDetails select[name='comboSelectcompanyType']").val();
    var paymentMethod2 = $("#entryDetails select[name='comboSelectpaymentterm']").val();
    //if($("#isSupplier2").is(":checked")){var isSupplier2 = "on"}else{var isSupplier2 = "off"}
    if($("#isCustomer2").is(":checked")){var isCustomer2 = "on"}else{var isCustomer2 = "off"}
    if($("#isinactive").is(":checked")){var isinactive = "on"}else{var isinactive = "off"}

    if (customerName2 == "" || contactName21 == "" || contactName22 == "" || tel21 == "" || tel22 == "" || companyType2 == "" || paymentMethod2 == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else{
        $.ajax({
            url: "include/operations.php?pg=customer&process=update",
            type: "POST",
            data: {
                pr_id:pr_id,
                customerName2:customerName2,
                contactName21:contactName21,
                contactName22:contactName22,
                tel21:tel21,
                tel22:tel22,
                fax2:fax2,
                vat2:vat2,
                mail2:mail2,
                web2:web2,
                note2:note2,
                //fpoc2:fpoc2,
                companyType2:companyType2,
                paymentMethod2:paymentMethod2,
                //isSupplier2:isSupplier2,
                isCustomer2:isCustomer2,
                isinactive:isinactive,
                details:"details"
            },
            success: function (output){
                //location.reload();

                if (output == 1){
                    var status = "lime";
                    var message = "Customer Details Updated";
                    UINotific8("true",message,status);
                }
                else if(output == 0){
                    var status = "ruby";
                    var message = "Customer Details Not Updated!..";
                    UINotific8("false",message,status);
                }

            }
        });
    }
    /*
    $.post('include/operations.php?pg=customer&process=update',{
        pr_id:pr_id,
        customerName2:customerName2,
        contactName21:contactName21,
        contactName22:contactName22,
        tel21:tel21,
        tel22:tel22,
        fax2:fax2,
        vat2:vat2,
        mail2:mail2,
        web2:web2,
        note2:note2,
        fpoc2:fpoc2,
        companyType2:companyType2,
        paymentMethod2:paymentMethod2,
        isSupplier2:isSupplier2,
        isCustomer2:isCustomer2,
        isinactive:isinactive,
        details:"details"

    },function (output) {
        if (output == "true"){
            var status = "lime";
            var message = "Customer Details Updated";
            UINotific8(output,message,status);
            getAddress();
        }
        else if(output == "false"){
            var status = "ruby";
            var message = "Customer Details Not Updated!..";
            UINotific8(output,message,status);
        }
    })
    */

}

function deleteAddress(id) {
    bootbox.confirm('Silme İşleminden Emin Misiniz?', function (result) {
        if (result){
            $.post('include/operations.php?pg=customer&process=deleteAddress',{addressid:id},function (output) {
                if (output == 1){
                    var status = "lime";
                    var message = "Address Deleted";
                    UINotific8("true",message,status);
                    getAddress();
                }
                else if(output == 0){
                    var status = "ruby";
                    var message = "Address Not Deleted";
                    UINotific8("false",message,status);
                }
            })
        }
    });
}

function addnewaddress() {

    var selectCustomer = $("#selectCustomer").val();
    if (selectCustomer != null){
        var companyID = selectCustomer;
    }
    else{
        var companyID = $("#prid-nw").val();
    }

    var addnewaddress = "addnewaddress";
    var locationName = $("#locationName-nw").val();
    var house = $("#house-nw").val();
    var street = $("#street-nw").val();
    var city = $("#city-nw").val();
    var county = $("#county-nw").val();
    var postcode = $("#postcode-nw").val();
    var country = $("#country-nw").val();

    //var companyID = $("#addNewAddressPrId").val(selectCustomer);
    if($("#isMain_nw").is(":checked")){var isMain_nw = "1";}else{var isMain_nw = "0";}

    if (locationName == "" || house == "" || street == "" || city == "" || county == "" || postcode == "" || country == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else{
        $.ajax({
            url: "include/operations.php?pg=customer&process=addcompany",
            type: "POST",
            data: {
                addnewaddress: addnewaddress,
                locationName: locationName,
                house: house,
                street: street,
                city: city,
                county: county,
                country: country,
                postcode: postcode,
                isMain: isMain_nw,
                companyID: companyID
            },
            success: function (output){
                if (output == 1){
                    var status = "lime";
                    var message = "Address Add";
                    UINotific8("true",message,status);
                    $("#addnewaddress").modal("hide");
                    $('#addnewaddress form')[0].reset();
                    getAddress();
                }
                else if(output == 0){
                    var status = "ruby";
                    var message = "Address Not Add";
                    UINotific8("false",message,status);
                }
            }
        });
    }
    /*
    $.post("include/operations.php?pg=customer&process=addcompany",
        {
            addnewaddress: addnewaddress,
            locationName: locationName,
            house: house,
            street: street,
            city: city,
            county: county,
            country: country,
            postcode: postcode,
            isMain: isMain_nw,
            companyID: companyID
        },
        function(data, status){
            if (data == "true"){
                var status = "lime";
                var message = "Address Add";
                UINotific8(data,message,status);
                $("#addnewaddress").modal("hide");
                $('#addnewaddress form')[0].reset(); //MODAL FORM RESET......
                getAddress();
            }
            else if(data == "false"){
                var status = "ruby";
                var message = "Address Not Add";
                UINotific8(data,message,status);
            }
        });
    */
}

function order_plus(num){
    var qtyorder = $("#qtyorder"+num+"").val();
    var orderproductid = $("#orderproductid"+num+"").val();
    var new_selectorder = $("#new_selectorder"+num+"").val();
    var select_website = $("#selectWebsite"+num+"").val();
    if (qtyorder == "" || orderproductid == "" || new_selectorder == "" || select_website == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL<b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else{
        $.post("include/operations.php?pg=salesOrder&process=enterbasket",{
            qtyorder: qtyorder,
            orderproductid: orderproductid,
            new_selectorder: new_selectorder,
            select_website: select_website
        },function (output) {
            $("#productlist"+num+"").html(output);
            $("#qtyorder"+num+"").val("");
            $("#orderproductid"+num+"").val("");
            $("#new_selectorder"+num+"").val("");
        })
    }
}

function addProforma(num){
    var selectCustomer = $("#selectCustomer"+num+"").val();
    var selectAddress = $("#selectAddress"+num+"").val();
    var selectWebsite = $("#selectWebsite"+num+"").val();
    var addvoucher = $("#addvoucher"+num+"").val();
    var addNotes = $("#addNotes"+num+"").val();
    if (selectCustomer == "" || selectAddress == "" || selectWebsite == "" || addvoucher == "" || addNotes == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else{
        $.post("include/operations.php?pg=salesOrder&process=enterproforma",{
            posttype: "ajaxenter",
            selectCustomer: selectCustomer,
            selectAddress: selectAddress,
            selectWebsite: selectWebsite,
            addvoucher: addvoucher,
            addNotes: addNotes
        },function (output){
            if (output == 1){
                var status = "lime";
                var message = "New Proforma Saved";
                UINotific8("true", message, status);
                $('#addproforma'+num+'').find('input:text, select, textarea').val('');
                modalgetCustomers(num);
                $(".addressDetails"+num+"").empty();
                $("#productlist"+num+"").empty();
                $("#addproforma").modal("hide");
            }
            else if (output == 0){
                var status = "ruby";
                var message = "Proforma Not Saved";
                UINotific8("false", message, status);
            }
        });
    }
}

function addOrder(num){
    var selectCustomer = $("#selectCustomer"+num+"").val();
    var selectAddress = $("#selectAddress"+num+"").val();
    var selectWebsite = $("#selectWebsite"+num+"").val();
    var addNotes = $("#addNotes"+num+"").val();
    if (selectCustomer == "" || selectAddress == "" || selectWebsite == "" || addNotes == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else{
        $.post("include/operations.php?pg=salesOrder&process=enterorder",{
            posttype: "ajaxenter",
            selectCustomer: selectCustomer,
            selectAddress: selectAddress,
            selectWebsite: selectWebsite,
            addNotes: addNotes
        },function (output){
            if (output == 1){
                var status = "lime";
                var message = "New Order Saved";
                UINotific8("true", message, status);
                $('#addorder'+num+'').find('input:text, select, textarea').val('');
                modalgetCustomers(num);
                $(".addressDetails"+num+"").empty();
                $("#productlist"+num+"").empty();
                $("#addorder").modal("hide");
            }
            else if (output == 0){
                var status = "ruby";
                var message = "Order Not Saved";
                UINotific8("false", message, status);
            }
        });
    }
}

function addInvoice(num){
    var selectCustomer = $("#selectCustomer"+num+"").val();
    var selectAddress = $("#selectAddress"+num+"").val();
    var selectWebsite = $("#selectWebsite"+num+"").val();
    var addNotes = $("#addNotes"+num+"").val();
    if (selectCustomer == "" || selectAddress == "" || selectWebsite == "" || addNotes == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else{
        $.post("include/operations.php?pg=invoice&operation=enterinvoice",{
            posttype: "ajaxenter",
            selectCustomer: selectCustomer,
            selectAddress: selectAddress,
            selectWebsite: selectWebsite,
            addNotes: addNotes
        },function (output){
            if (output == 1){
                var status = "lime";
                var message = "New Invoice Saved";
                UINotific8("true", message, status);
                $('#addinvoices'+num+'').find('select, textarea').val('');
                modalgetCustomers(num);
                $(".addressDetails"+num+"").empty();
                $("#addinvoices").modal("hide");
            }
            else if (output == 0){
                var status = "ruby";
                var message = "Invoice Not Saved";
                UINotific8("false", message, status);
            }
        });
    }
}

function product_select(prid,num){
    var pr_option = prid;
    $.post("include/operations.php?pg=select-size",{pr_option: pr_option},function (result) {
        $("#new_selectorder"+num+"").html(result);
    });
}

function website_select(id,num){
    var webID = id;
    $.post("include/operations.php?pg=select-size",{webID: webID},function (result) {
        $("#orderproductid"+num+"").html(result);
    });
}

$(".deletePage").on("click",function () {
    var pageID = $(this).attr("data-id");
    //alert(pageID);
    bootbox.confirm('Silme İşleminden Emin Misiniz?', function (result) {
        //alert(result);
        if (result){
            $.get('include/operations.php?pg=pages&process=deletePages',{pageID:pageID},function (output) {
                if (output == "true"){
                    var status = "lime";
                    var message = "Deleted";
                    UINotific8(output,message,status);
                    document.getElementById(pageID).style.display = "none";
                }
                else if(output == "false"){
                    var status = "ruby";
                    var message = "Not Deleted";
                    UINotific8(output,message,status);
                }
            })
        }
    });
});

$("#user_add").on("click", function () {
    var username = $("#username").val();
    var password = $("#password").val();
    var email = $("#email").val();
    var name = $("#name").val();
    var usertype = $("#usertype").val();
    if (username == "" || password == "" || email == "" || name == "" || usertype == ""){
        var settings = {
                theme: "smoke",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "NULL";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("NOT NULL SPACE", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $.ajax({
            url: "include/operations.php?pg=users&process=user_add",
            type: "POST",
            data: {
                username:username,
                password:password,
                email:email,
                name:name,
                usertype:usertype
            },
            success: function (output){
                location.reload();
            }
        });
        /*
        $.post('include/operations.php?pg=users&process=user_add',{username:username,password:password,email:email,name:name,usertype:usertype},function(output){
            if (output == "true"){
                var status = "lime";
                var message = "Inserted";
                $("#insert_user").modal("hide");
                UINotific8(output,message,status);
                $("#username").val("");
                $("#password").val("");
                $("#email").val("");
                $("#name").val("");
                $("#usertype").val("");
            }
            else if(output == "false"){
                var status = "ruby";
                var message = "Not Inserted";
                UINotific8(output,message,status);
            }
        })
        */
    }
});

$(".delete_user").on("click", function () {
    var userID = $(this).attr("id");
    bootbox.confirm('Silme İşleminden Emin Misiniz?', function (result) {
        if (result){
            $.ajax({
                url: "include/operations.php?pg=users&process=user_delete",
                type: "GET",
                data: {
                    userID: userID
                },
                success: function (output){
                    location.reload();
                }
            });
            /*
            $.get('include/operations.php?pg=users&process=user_delete',{userID:userID},function (output) {
                location.reload();
            })
            */
        }
    });
});

$(".user_update").on("click",function () {
    var username2 = $("#username2").val();
    var password2 = $("#password2").val();
    var email2 = $("#email2").val();
    var name2 = $("#name2").val();
    var usertype2 = $("#usertype2").val();
    var ID = $("#user_hidden").val();
    if (username2 == "" || password2 == "" || email2 == "" || name2 == "" || usertype2 == ""){
        var settings = {
                theme: "smoke",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "NULL";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("NOT NULL SPACE", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $.ajax({
            url: "include/operations.php?pg=users&process=user_update&q=update",
            type: "POST",
            data: {
                username2:username2,
                password2:password2,
                email2:email2,
                name2:name2,
                usertype2:usertype2,
                ID:ID
            },
            success: function (output){
                location.reload();
            }
        });
        /*
        $.post('include/operations.php?pg=users&process=user_update&q=update',{username2:username2,password2:password2,email2:email2,name2:name2,usertype2:usertype2,ID:ID},function(output){
            if (output == "true"){
                var status = "lime";
                var message = "Updated";
                $("#edit_user").modal("hide");
                UINotific8(output,message,status);
            }
            else if(output == "false"){
                var status = "ruby";
                var message = "Not Updated";
                $("#basic").modal("hide");
                UINotific8(output,message,status);
            }
        })
        */
    }

});

function edit_user(user_id) {
    var userID = user_id;
    $.get('include/operations.php?pg=users&process=user_update&q=get',{userID:userID},function(output){
        var user_data = JSON.parse(output);
        $("#username2").attr("value",user_data["user"]["username"]);
        $("#password2").attr("value",user_data["user"]["password"]);
        $("#email2").attr("value",user_data["user"]["email"]);
        $("#name2").attr("value",user_data["user"]["name"]);
        $("#usertype2").attr("value",user_data["user"]["usertype"]);
        $("#user_hidden").attr("value",userID);
    })
}

function edit_website(website_id) {
    var websiteID = website_id;
    $.get('include/operations.php?pg=websites&process=website_edit&q=get',{websiteID:websiteID},function(output){
        var website_data = JSON.parse(output);
        $("#websitename2").attr("value",website_data["web_data"]["websitename"]);
        $("#websitelink2").attr("value",website_data["web_data"]["websitelink"]);
        $("#website_hidden").attr("value",websiteID);
    })
}

$(".website_edit").on("click",function () {
    var websitename2 = $("#websitename2").val();
    var websitelink2 = $("#websitelink2").val();
    var webID = $("#website_hidden").val();
    if (websitename2 == "" || websitelink2 == ""){
        var settings = {
                theme: "smoke",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $.post('include/operations.php?pg=websites&process=website_edit&q=update',{websitename2:websitename2,websitelink2:websitelink2,webID:webID},function(output){
            if (output == "true"){
                var status = "lime";
                var message = "Updated";
                $("#edit_website").modal("hide");
                UINotific8(output,message,status);
            }
            else if(output == "false"){
                var status = "ruby";
                var message = "Not Updated";
                $("#basic").modal("hide");
                UINotific8(output,message,status);
            }
        })
    }
});

$("#website_add").on("click", function () {
    var websitename = $("#websitename").val();
    var websitelink = $("#websitelink").val();
    if (websitename == "" || websitelink == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $.post('include/operations.php?pg=websites&process=website_add',{websitename:websitename,websitelink:websitelink},function(output){
            if (output == "true"){
                var status = "lime";
                var message = "Kaydedildi";
                $("#insert_website").modal("hide");
                UINotific8(output,message,status);
                $("#websitename").val("");
                $("#websitelink").val("");
            }
            else if(output == "false"){
                var status = "ruby";
                var message = "Kayıt Başarısız";
                UINotific8(output,message,status);
            }
        })
    }
});

$(".delete_category").on("click", function () {
    var catID = $(this).attr("id");
    var cat_site = $("#cat_site").val();
    //alert(userID);
    bootbox.confirm('Silme İşleminden Emin Misiniz?', function (result) {
        //alert(result);
        if (result){
            $.get('include/operations.php?pg=categories&process=category_delete',{catID:catID,cat_site:cat_site},function (output) {
                location.reload();
            })
        }
    });
});

function edit_category(cat_id) {
    var cat_id = cat_id;
    $.get('include/operations.php?pg=categories&process=category_edit&q=get',{cat_id:cat_id},function(output){
        var category_data = JSON.parse(output);
        var cti = category_data["cat_data"]["cat_top_id"];
        $('#cat_top_id2 option[value='+cti+']').prop("selected",false);
        $('#cat_top_id2 option[value='+cti+']').prop("selected",true);
        $("#cat_name2").attr("value",category_data["cat_data"]["category"]);
        $("#form-horizontal-cat #cat_top_id2 option:selected").attr("value",category_data["cat_data"]["cat_top_id"]);
        $("#sira2").attr("value",category_data["cat_data"]["sira"]);
        $("#subcategory_hidden").attr("value",cat_id);
        $("#cat_image_hidden").attr("value",category_data["cat_data"]["cat_image"]);
        //alert(category_data["cat_data"]["cat_image"]);
        //$("#form-horizontal-cat22 #cat_image22").attr("src","../../"+category_data["cat_data"]["site_name"]+"/img/"+category_data["cat_data"]["cat_image"]);
        $("#form-horizontal-cat22 #cat_image22").attr("src","../img/"+category_data["cat_data"]["cat_image"]);
        //alert(cat_id+"          "+"../img/thumbs/"+category_data["cat_data"]["cat_image"]);
        $("#c_title2").attr("value",category_data["cat_data"]["ctitle"]);
        $("#cat_title2").attr("value",category_data["cat_data"]["categorytitle"]);
        $("#cat_keyword2").attr("value",category_data["cat_data"]["keyword"]);
        $("#cat_description2").attr("value",category_data["cat_data"]["description"]);
        $("#cat_link2").attr("value",category_data["cat_data"]["link"]);
        $("#header_content2").attr("value",category_data["cat_data"]["headercontent"]);
        $("#pagecontent2").attr("value",category_data["cat_data"]["pagecontent"]);
        $("#h2title2").attr("value",category_data["cat_data"]["h2title"]);
    })
}

function edit_slider(slider_id) {

    $.get('include/operations.php?pg=slider&process=slider_edit&q=get',{slider_id:slider_id},function(output){
        var slider_data = JSON.parse(output);

        $('#sliderSite2 option[value='+slider_data["slider_data"]["slider_site"]+']').prop('selected',false);

        $("#slide_img").attr("src","../img/slides/thumbs/"+slider_data["slider_data"]["slider_name"]);
        //$("#slider_name2").attr("value",slider_data["slider_data"]["slider_name"]);
        $("#slider_description2").attr("value",slider_data["slider_data"]["slider_description"]);
        $("#sliderLink2").attr("value",slider_data["slider_data"]["slider_link"]);
        $('#sliderSite2 option[value='+slider_data["slider_data"]["slider_site"]+']').prop('selected',true);
        $("#slider_site2").attr("value",slider_data["slider_data"]["slider_site"]);
        $("#slider_order2").attr("value",slider_data["slider_data"]["slider_order"]);
        //$("#slider_status2").attr("value",slider_data["slider_data"]["slider_status"]);
        $("#slider_title2").attr("value",slider_data["slider_data"]["slider_title"]);
        $("#slider_hidden").attr("value",slider_id);
        $("#slider_hidden2").attr("value",slider_data["slider_data"]["slider_name"]);
        if (slider_data["slider_data"]["slider_status"] === "1"){
            $("#slider_status2").prop("checked", true);
        }
        else if(slider_data["slider_data"]["slider_status"] === "0"){
            $("#slider_status2").prop("checked", false);
        }
        $("#pagesSelect").html("");
        $("#pagesSelect").append("<option value='0'>Select a page for slider</option>");
        for (i=0; i<slider_data["lenght"]["lenght"]; i++){
            $("#pagesSelect").append("<option value='"+slider_data["pages"][i]["id"]+"'>"+slider_data["pages"][i]["title"]+"</option>");
        }
        $('#pagesSelect option[value='+slider_data["slider_data"]["slider_link"]+']').attr("selected",true);

    })
}

$(".delete_slider").on("click", function () {
    var sliderID = $(this).attr("id");
    bootbox.confirm('Silme İşleminden Emin Misiniz?', function (result) {
        if (result){
            $.get('include/operations.php?pg=slider&process=slider_delete',{sliderID:sliderID},function (output) {
                var status = "lime";
                var message = "Deleted";
                UINotific8(output,message,status,sliderID);
                $("#"+sliderID).parent().parent().hide(300);
            })
        }
    });
});

$(".sliderOrder").on("change",function () {
    var order = $(this).val();
    var id = $(this).attr("data");
    $.post("include/operations.php?pg=slider&process=orderUpdate",{order:order,id:id},function (output) {

    })
});

function edit_tabs(tab_id) {
    var tab_id = tab_id;
    $.get('include/operations.php?pg=tabs&process=tabs_edit&q=get',{tab_id:tab_id},function(output){
        var tab_data = JSON.parse(output);

        $('#tab_site2 option[value='+tab_data["tab_data"]["tab_site"]+']').prop('selected',false);

        $("#tab_name2").attr("value",tab_data["tab_data"]["tab_name"]);
        $("#tab_order2").attr("value",tab_data["tab_data"]["tab_order"]);
        $('#tab_site2 option[value='+tab_data["tab_data"]["tab_site"]+']').prop('selected',true);
        $("input[name='tab_status-active2']").attr("value",tab_data["tab_data"]["tab_status"]);
        $("#tab_hidden").attr("value",tab_id);

        if (tab_data["tab_data"]["tab_status"] === "1"){
            //$("#slider_status-active2").attr("value",slider_data["slider_data"]["slider_status"]);
            //$("#slider_status-passive2").prop("checked", false);
            $("input[name='tab_status-active2']").prop("checked", true);
            $("input[name='tab_status-active2']").attr("checked","checked");
        }
        else if(tab_data["tab_data"]["tab_status"] === "0"){
            //$("#slider_status-passive2").attr("value",slider_data["slider_data"]["slider_status"]);
            $("input[name='tab_status-active2']").prop("checked", false);
            $("input[name='tab_status-active2']").attr("checked",false);
        }
    })
}

$("#tabs_add").on("click", function () {
    var tab_name = $("#tab_name").val();
    var tab_order = $("#tab_order").val();
    if (tab_name == "" || tab_order == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        if ($("input[name='tab_status-active']").prop("checked")) {
            $("input[name='tab_status-active']").val("1");
            var tabs_active = $("input[name='tab_status-active']").val();
            //alert(tabs_active);
            $.post('include/operations.php?pg=tabs&process=tabs_add', {
                tab_name: tab_name,
                tab_order: tab_order,
                tabs_active: tabs_active
            }, function (output) {
                if (output == "true") {
                    var status = "lime";
                    var message = "Inserted";
                    $("#insert_tabs").modal("hide");
                    UINotific8(output, message, status);
                    $("#tab_name").val("");
                    $("#tab_order").val("");
                }
                else if (output == "false") {
                    var status = "ruby";
                    var message = "Not Inserted";
                    UINotific8(output, message, status);
                }
            })
        }
        else {
            $("input[name='tab_status-active']").val("0");
            var tabs_passive = $("input[name='tab_status-active']").val();
            // alert(tabs_passive);
            $.post('include/operations.php?pg=tabs&process=tabs_add', {
                tab_name: tab_name,
                tab_order: tab_order,
                tabs_passive: tabs_passive
            }, function (output) {
                if (output == "true") {
                    var status = "lime";
                    var message = "Kaydedildi";
                    $("#insert_tabs").modal("hide");
                    UINotific8(output, message, status);
                }
                else if (output == "false") {
                    var status = "ruby";
                    var message = "Kayıt Başarısız";
                    UINotific8(output, message, status);
                }
            })
        }
    }
});

$("input[name='tab_status-active']").click(function() {
    if($("input[name='tab_status-active']").val() == 1) {
        $("input[name='tab_status-active']").val(0);
        $("input[name='tab_status-active']").attr("checked",false);
    }
    else {
        $("input[name='tab_status-active']").val(1);
        $("input[name='tab_status-active']").attr("checked","checked");
    }
});

$("input[name='tab_status-active2']").click(function() {
    if($("input[name='tab_status-active2']").val() == 1) {
        $("input[name='tab_status-active2']").val(0);
        $("input[name='tab_status-active2']").attr("checked",false);
    }
    else {
        $("input[name='tab_status-active2']").val(1);
        $("input[name='tab_status-active2']").attr("checked","checked");
    }
});

$("#tab_edit").on("click", function () {
    var tab_name2 = $("#tab_name2").val();
    var tab_order2 = $("#tab_order2").val();
    var tabID = $("#tab_hidden").val();
    var tab_status2 = $("input[name='tab_status-active2']").val();
    if (tab_name2 == "" || tab_order2 == "" || tab_status2 == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $.post('include/operations.php?pg=tabs&process=tabs_edit&q=update',{tab_name2:tab_name2,tab_order2:tab_order2,tab_status2:tab_status2,tabID:tabID},function(output){
            if (output == "true"){
                var status = "lime";
                var message = "Updated";
                $("#edit_tabs").modal("hide");
                UINotific8(output,message,status);
            }
            else if(output == "false"){
                var status = "ruby";
                var message = "Not Updated";
                UINotific8(output,message,status);
            }
        })
    }
});

$(".delete_tabs").on("click", function () {
    var tabID = $(this).attr("id");
    //alert(userID);
    bootbox.confirm('Silme İşleminden Emin Misiniz?', function (result) {
        //alert(result);
        if (result){
            $.get('include/operations.php?pg=tabs&process=tabs_delete',{tabID:tabID},function (output) {
                var status = "lime";
                var message = "Deleted";
                UINotific8(output,message,status,tabID);
            })
        }
    });
});

function pimgdlt(id){
    var imgID = id;
    bootbox.confirm('Silme İşleminden Emin Misiniz?', function (result) {
        if (result){
            $.get('include/operations.php?pg=products&process=img_delete',{imgID:imgID},function (output) {
                var status = "lime";
                var message = "Deleted";
                UINotific8(output,message,status,imgID);
                document.getElementById(imgID).style.display = "none";
            })
        }
    });
}

var t;
var u;
var sum=0;
var a=0;
var z=0;
var op=5;
var per=5;
var plus=5;
var sl_plus=5;
var pr = new Array();
var sl = new Array();
var ch = new Array();
var plus2 = 5;
var sl_plus2 = 5;
var s=0;
var last_ct;
var last_ch;
var last_kk;
var last_slt;

var sycsz = 0;
$("#plus").on("click",function (){
    sum = sum + 3;
    var size = $("#options2 option:selected").val();
    var select_size = $('#options2 option[value=' + size + ']').text();
    var stock_status = $("#stock_status option:selected").val();
    var select_stock = $('#stock_status option[value=' + stock_status + ']').text();
    var vat = $("#vatid option:selected").val();
    var select_vatid = $('#vatid option[value=' + vat + ']').text();
    var price = $("#price").val();
    var price2 = $("#price2").val();
    var note1 = $("#note1").val();

    var markup = '<tr id="sizenone'+sycsz+'">' +
        '<td>' +
        '<select class="form-control select2me slc se1" id="options' + plus + '" name="options' + plus + '">' +
        '<option value=' + size + ' selected disabled>' + select_size + '</option>' +
        '</select>' +
        '</td>' +
        '<td></td>' +
        '<td>' +
        '<input class="form-control input-small in1" name="price[]" type="text" id="price' + plus + '" value="' + price + '" size="2" aria-invalid="false" style="width: 75%;">' +
        '</td>' +
        '<td>' +
        '<input class="form-control input-small in1" name="price2[]" type="text" id="price2' + plus + '" value="' + price2 + '" size="2" aria-invalid="false" style="width: 75%;">' +
        '</td>' +
        '<td>' +
        '<select name="stock_status' + plus + '" id="stock_status' + plus + '" class="select2me col-md-9 se1 stk1" style="height: 33px;">' +
        '<option value=' + stock_status + ' selected disabled>' + select_stock + '</option>' +
        '</select>' +
        '</td>' +
        '<td>' +
        '<select name="vatid' + plus + '" id="vatid' + plus + '" class="select2me col-md-9 se1 vt1" style="height: 33px;">' +
        '<option value=' + vat + ' selected disabled>' + select_vatid + '</option>' +
        '</select>' +
        '</td>';

    var markup2 = '<td>' +
        '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkbox' + plus + '"' +
        'name="product_show[]" class="md-check ch1"';

    var at = $("#checkbox1").prop("checked") ? "checked" : "";

    var markup3 = at + '><label for="checkbox' + plus + '"' +
        '><span class="inc"></span><span class="check"></span>' +
        '<span class="box"></span></label></div></div>' +
        '</td>';
    plus = plus + 1;
    var markup4 = '<td>' +
        '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkbox' + plus + '"' +
        'name="dummy[]" class="md-check ch1"';

    var at2 = $("#checkbox2").prop("checked") ? "checked" : "";

    var markup5 = at2 + '><label for="checkbox' + plus + '"' +
        '><span class="inc"></span><span class="check"></span>' +
        '<span class="box"></span></label></div></div>' +
        '</td>';
    plus = plus + 1;
    var markup6 = '<td>' +
        '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkbox' + plus + '"' +
        'name="new-size[]" class="md-check ch1"';

    var at3 = $("#checkbox3").prop("checked") ? "checked" : "";

    var markup7 = at3 + '><label for="checkbox' + plus + '"' +
        '><span class="inc"></span><span class="check"></span>' +
        '<span class="box"></span></label></div></div>' +
        '</td>';
    plus = plus + 1;
    var markup8 = '<td>' +
        '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkbox' + plus + '"' +
        'name="special[]" class="md-check ch1"';

    var at4 = $("#checkbox4").prop("checked") ? "checked" : "";

    var markup9 = at4 + '><label for="checkbox' + plus + '"' +
        '><span class="inc"></span><span class="check"></span>' +
        '<span class="box"></span></label></div></div>' +
        '</td>';

    var markup10 = '<td>' +
        '<input class="form-control input-small in1" name="note1[]" type="text" id="note1' + plus + '" value=' + note1 + '>' +
        '</td>';
    plus = plus + 1;
    var markup11 = '<td><a href="#" onclick="displaynone('+"'size'"+','+sycsz+')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';

    /*
     var markup11 = '<th style="padding-top: 15px;">'+
     '<a href="#" style="margin-right: 20px;">'+
     '<i class="fa fa-pencil" aria-hidden="true"></i></a>'+
     '<a href="#"><i class="fa fa-trash" aria-hidden="true"></i>'+
     '</a>'+
     '</th>' +
     '</tr>';
     */
    if (price == "" || price2 == "" || note1 == "" || size == "Select" || stock_status == "Select" || vat == "Select") {
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
    $("#form-horizontal-pr #size-table tbody").append(markup + markup2 + markup3 + markup4 + markup5 + markup6 + markup7 + markup8 + markup9 + markup10 + markup11);

    $("#size-table tbody .stk1").append($("<option></option>").val(1).html("In Stock"));
    $("#size-table tbody .stk1").append($("<option></option>").val(2).html("Low Stock"));
    $("#size-table tbody .stk1").append($("<option></option>").val(3).html("Out Stock"));

    $.get('include/operations.php?pg=products&process=product_add&q=add', function (output) {
        var size_data = JSON.parse(output);
        for (t = 0; t < size_data["sizes"][0]["cntsz"]; t++) {
            $("#form-horizontal-pr #size-table tbody .slc").append($("<option></option>").val(size_data["sizes"][t]["id"]).html(size_data["sizes"][t]["size"]));
        }
        for (u = 0; u < size_data["combos"][0]["countcmbs"]; u++) {
            $("#size-table tbody .vt1").append($("<option></option>").val(size_data["combos"][u]["id"]).html(size_data["combos"][u]["name"]));
        }
    });
    sl_plus = sl_plus + 4;

    var each = 5;
    var at1;
    var i = 0;

    var b = 0;
    var countpr = 0;
    var countsl = 0;
    var countch = 0;

    $("#size-table tbody tr td").children("input.form-control").each(function () {
        pr[i] = $(this).val();
        //alert("input.form-control pr["+i+"]:"+pr[i]);
        i++;
        countpr++;
        //alert("countpr:"+countpr);
    });

    sl[a] = size;
        //alert("sl["+a+"]:"+sl[a]);
    a++;
    sl[a] = stock_status;
        //alert("sl["+a+"]:"+sl[a]);
    a++;
    sl[a] = vat;
        //alert("sl["+a+"]:"+sl[a]);
    a++;
    /*
     $("#size-table table tr td").children("select.se1").each(function(){
     sl[a] = $(this).val();
     alert(sl[a]);
     a++;
     countsl++;
     alert(countsl);
     });
     */
    $("#size-table tbody tr td input[type=checkbox]").each(function () {
        at1 = $("#checkbox" + each + "").prop("checked") ? "1" : "0";
        ch[b] = at1;
        //alert("checkbox["+each+"] => ch["+b+"]:"+ch[b]);
        b++;
        each++;
        countch++;
        //alert("countch:"+countch);
    });

    $("#hiddenpr").val(countpr);
    $("#hiddensl").val(countsl);
    $("#hiddench").val(countch);
    $("#price").val("");
    $("#price2").val("");
    $("#note1").val("");
    $("#checkbox1").prop("checked", false);
    $("#checkbox2").prop("checked", false);
    $("#checkbox3").prop("checked", false);
    $("#checkbox4").prop("checked", false);
    //alert(JSON.stringify(sl));

        sycsz++;
    }
});

var plus_rlt = 5;
var rltpr = new Array();
var rltch = new Array();

var syc = 0;
$("#plus_related").on("click",function () {
    var pr_id = $("#product_related option:selected").val();
    var select_pr = $('#product_related option[value='+pr_id+']').text();

    var markup = '<tr id="relatednone'+syc+'">' +
        '<td>' +
        '<select name="product_related'+plus_rlt+'" id="product_related'+plus_rlt+'" class="select2me col-md-6 slcrlt" style="height: 33px;">'+
        '<option value='+pr_id+' selected disabled>'+select_pr+'</option>'+
        '</select>' +
        '</td>';

    var markup2 = '<td>' +
        '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="ismaintenance'+plus_rlt+'"'+
        'name="ismaintenance'+plus_rlt+'" class="md-check"';

    var at = $("#ismaintenance").prop("checked") ? "checked" : "";

    var markup3 = at+'><label for="ismaintenance'+plus_rlt+'"'+
        '><span class="inc"></span><span class="check"></span>'+
        '<span class="box"></span></label></div></div>'+
        '</td>';

    plus_rlt = plus_rlt + 1;

    var markup4 = '<td><a href="#" class="nonedisplayrelated" onclick="displaynone('+"'related'"+','+syc+')"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';

    $("#related-pr tbody").append(markup+markup2+markup3+markup4);

    $.get('include/operations.php?pg=products&process=product_add&q=add',function (output) {
        var size_data = JSON.parse(output);
        for (t=0; t<size_data["products"][0]["cntpr"]; t++) {
            $("#related-pr tbody .slcrlt").append($("<option></option>").val(size_data["products"][t]["pr_id"]).html(size_data["products"][t]["name"]));
        }
    })

    var each=5;
    var at1;
    var a=0;
    var b=0;
    var pr=0;
    var ch=0;

    //$("#related-pr table tr td").children("select").each(function(){
        rltpr[z] = pr_id;
        z++;

    //});

    $("#related-pr table tr td input[type=checkbox]").each(function(){
        at1 = $("#ismaintenance"+each+"").prop("checked") ? "1" : "0";
        rltch[b] = at1;
        b++;
        each++;
        ch++;
    });
    //alert("rltpr:   "+JSON.stringify(rltpr));
    //alert("rltch:   "+JSON.stringify(rltch));

    $("#rltpr").val(z);
    $("#rltch").val(ch);
    $("#ismaintenance").prop("checked",false);

    syc++;

});

function displaynone(loc,cnt){
    var syc = cnt;
    $("#"+loc+"none"+syc+"").remove();
}

var IDproduct;
var IDproduct2;

function edit_product(pr_id,wsid){
    var i;
    var kk;
    var t;
    var m;
    var n;
    var p;
    var webID = wsid;

    $.get('include/operations.php?pg=products&process=product_edit&q=get',{pr_id:pr_id},function (output) {

        var product_data = JSON.parse(output);

        IDproduct = product_data["product_data"]["pr_id"];
        IDproduct2 = product_data["product_data"]["szID"];
        //webID = product_data["product_data"]["websiteid"];

        //$("#form-horizontal-pr2 #pr_cat_top_id2").find("option:selected").removeAttr("selected");
        $("#form-horizontal-pr2 #pr_cat_top_id2").find("option:selected").removeProp("selected");
        $("#form-horizontal-pr2 #pr_cat_top_id2").find("option:selected").prop("selected",false);

        var cti = product_data["product_data"]["categoryid"];
        $("#productname2").attr("value",product_data["product_data"]["name"]);
        //$("#productlink2").attr("value",product_data["product_data"]["link"]);
        $("#fromprice2").attr("value",product_data["product_data"]["fromprice"]);
        //$("#form-horizontal-pr2 #pr_cat_top_id2 option:selected").prop("value",product_data["product_data"]["categoryid"]);
        $('#form-horizontal-pr2 #pr_cat_top_id2 option[value='+cti+']').prop("selected",true);
        $("#notes2").attr("value",product_data["product_data"]["note"]);
        $("#generalname2").attr("value",product_data["product_data"]["generalname"]);
        //$("#pr_image2").attr("src","../../"+product_data["product_data"]["websitename"]+"/img/products/thumbs/"+product_data["product_data"]["pr_image"]);
        //$("#pr_image2").attr("src","../img/products/thumbs/"+product_data["product_data"]["pr_image"]);
        $("#productinfo2").text(product_data["product_data"]["productinfo"]);
        $("#product_hidden").attr("value",product_data["product_data"]["pr_id"]);
        $("#sz_hidden").attr("value",product_data["product_data"]["szID"]);
        $("#product_img").attr("value",product_data["product_data"]["pr_image"]);

        $("#edge2").find("option:selected").removeProp("selected");
        $("#room2").find("option:selected").removeProp("selected");
        $("#material2").find("option:selected").removeProp("selected");
        $("#finish2").find("option:selected").removeProp("selected");
        $("#traffic2").find("option:selected").removeProp("selected");
        $("#wall2").find("option:selected").removeProp("selected");
        $("#colour4").find("option:selected").removeProp("selected");
        $("#colour25").find("option:selected").removeProp("selected");
        $("#pop2").find("option:selected").removeProp("selected");
        $("#optionstype2").find("option:selected").removeProp("selected");
        $("#pertype2").find("option:selected").removeProp("selected");

        $('#edge2 option[value='+product_data["product_data"]["edge"]+']').prop('selected',false);
        $('#room2 option[value='+product_data["product_data"]["room"]+']').prop("selected",false);
        $('#material2 option[value='+product_data["product_data"]["material"]+']').prop("selected",false);
        $('#finish2 option[value='+product_data["product_data"]["finish"]+']').prop("selected",false);
        $('#traffic2 option[value='+product_data["product_data"]["traffic"]+']').prop("selected",false);
        $('#wall2 option[value='+product_data["product_data"]["wall"]+']').prop("selected",false);
        $('#colour4 option[value='+product_data["product_data"]["colour"]+']').prop("selected",false);
        $('#colour25 option[value='+product_data["product_data"]["colour2"]+']').prop("selected",false);
        $('#pop2 option[value='+product_data["product_data"]["pop"]+']').prop("selected",false);
        $('#optionstype2 option[value='+product_data["product_data"]["optionstype"]+']').prop("selected",false);
        $('#pertype2 option[value='+product_data["product_data"]["pertype"]+']').prop("selected",false);

        //var stredge = $('#edge2 option[value='+product_data["product_data"]["edge"]+']').text();
        $('#edge2 option[value='+product_data["product_data"]["edge"]+']').prop("selected",true);
        //$("#edge2 option:selected").prop("value",product_data["product_data"]["edge"]);
        //$('#edge2 option[value='+product_data["product_data"]["edge"]+']').text(stredge);

        //var strroom = $('#room2 option[value='+product_data["product_data"]["room"]+']').text();
        //$("#room2 option:selected").prop("value",product_data["product_data"]["room"]);
        $('#room2 option[value='+product_data["product_data"]["room"]+']').prop("selected",true);
        //$('#room2 option[value='+product_data["product_data"]["room"]+']').text(strroom);

        //var strmaterial = $('#material2 option[value='+product_data["product_data"]["material"]+']').text();
        //$("#material2 option:selected").prop("value",product_data["product_data"]["material"]);
        $('#material2 option[value='+product_data["product_data"]["material"]+']').prop("selected",true);
        //$('#material2 option[value='+product_data["product_data"]["material"]+']').text(strmaterial);

        //var strfinish = $('#finish2 option[value='+product_data["product_data"]["finish"]+']').text();
        //$("#finish2 option:selected").prop("value",product_data["product_data"]["finish"]);
        $('#finish2 option[value='+product_data["product_data"]["finish"]+']').prop("selected",true);
        //$('#finish2 option[value='+product_data["product_data"]["finish"]+']').text(strfinish);

        //var strtraffic = $('#traffic2 option[value='+product_data["product_data"]["traffic"]+']').text();
        $("#traffic2 option:selected").prop("value",product_data["product_data"]["traffic"]);
        $('#traffic2 option[value='+product_data["product_data"]["traffic"]+']').prop("selected",true);
        //$('#traffic2 option[value='+product_data["product_data"]["traffic"]+']').attr(strtraffic);

        //var strwall = $('#wall2 option[value='+product_data["product_data"]["wall"]+']').text();
        //$("#wall2 option:selected").prop("value",product_data["product_data"]["wall"]);
        $('#wall2 option[value='+product_data["product_data"]["wall"]+']').prop("selected",true);
        //$('#wall2 option[value='+product_data["product_data"]["wall"]+']').text(strwall);

        //var strcolour = $('#colour4 option[value='+product_data["product_data"]["colour"]+']').text();
        //$("#colour4 option:selected").prop("value",product_data["product_data"]["colour"]);
        $('#colour4 option[value='+product_data["product_data"]["colour"]+']').prop("selected",true);
        //$('#colour4 option[value='+product_data["product_data"]["colour"]+']').text(strcolour);

        //var strcolour25 = $('#colour25 option[value='+product_data["product_data"]["colour2"]+']').text();
        //$("#colour25 option:selected").prop("value",product_data["product_data"]["colour2"]);
        $('#colour25 option[value='+product_data["product_data"]["colour2"]+']').prop("selected",true);
        //$('#colour25 option[value='+product_data["product_data"]["colour2"]+']').text(strcolour25);

        //var strpop2 = $('#pop2 option[value='+product_data["product_data"]["pop"]+']').text();
        //$("#pop2 option:selected").prop("value",product_data["product_data"]["pop"]);
        $('#pop2 option[value='+product_data["product_data"]["pop"]+']').prop("selected",true);
        //$('#pop2 option[value='+product_data["product_data"]["pop"]+']').text(strpop2);

        //var stroptionstype2 = $('#optionstype2 option[value='+product_data["product_data"]["optionstype"]+']').text();
        //$("#optionstype2 option:selected").prop("value",product_data["product_data"]["optionstype"]);
        $('#optionstype2 option[value='+product_data["product_data"]["optionstype"]+']').prop("selected",true);
        //$('#optionstype2 option[value='+product_data["product_data"]["optionstype"]+']').text(stroptionstype2);

        //var strpertype2 = $('#pertype2 option[value='+product_data["product_data"]["pertype"]+']').text();
        //$("#pertype2 option:selected").prop("value",product_data["product_data"]["pertype"]);
        $('#pertype2 option[value='+product_data["product_data"]["pertype"]+']').prop("selected",true);
        //$('#pertype2 option[value='+product_data["product_data"]["pertype"]+']').text(strpertype2);

        $("#gap2").attr("value",product_data["product_data"]["gap"]);

        var new2 = product_data["product_data"]["new"] == 1 ? "checked" : "";
        var soffer2 = product_data["product_data"]["soffer"] == 1 ? "checked" : "";
        var freesample2 = product_data["product_data"]["freesample"] == 1 ? "checked" : "";
        var projects2 = product_data["product_data"]["projects"] == 1 ? "checked" : "";
        var show2 = product_data["product_data"]["show"] == 1 ? "checked" : "";
        //alert("new2:     "+new2+"       soffer2:   "+soffer2+"     free:    "+freesample2+"     pro:     "+projects2+"       show2:     "+show2);
        $("#checkboxa51").prop("checked",new2);
        $("#checkboxa52").prop("checked",soffer2);
        $("#checkboxa53").prop("checked",freesample2);
        $("#checkboxa54").prop("checked",projects2);
        $("#checkboxa55").prop("checked",show2);

        //$("#size_hidden").attr("value",product_data["size_data"]["pr_sizeid"]);
        $("#form-horizontal-pr2 #size2 #size-table2 tbody .empty").empty();
        $("#form-horizontal-pr2 #related2 #related-pr2 tbody .empty").empty();
        $("#form-horizontal-pr2 #img-table thead tr").empty();

        $("#form-horizontal-pr2 #size2 #size-table2 tbody .empty").remove();
        $("#form-horizontal-pr2 #related2 #related-pr2 tbody .empty").remove();

        var st = 0;
        $.each(product_data["size_data"], function(index, value) {
            st++;
        });
        //var st = product_data["size_data"][0]["countsz"];
        //var st = product_data["size_data"].length;
        if (st != 0) {
            for (i = 0; i < st; i++) {
                var markup = '<tr class="empty" id="sizetr'+i+'">' +
                    '<td>' +
                    '<select class="form-control select2me ss se1' + i + '" id="options22' + i + '" name="options22' + i + '">' +
                    '<option value=' + product_data["size_data"][i]["sizeid"] + ' selected disabled>' + product_data["size_pr"][i]["size"] + '</option>' +
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input class="form-control input-small inp in1' + i + '" name="price12[]" type="text" id="price12' + i + '" value="' + product_data["size_data"][i]["price"] + '" size="2" aria-invalid="false" style="width: 75%;">' +
                    '</td>' +
                    '<td>' +
                    '<input class="form-control input-small inp in1' + i + '" name="price22[]" type="text" id="price22' + i + '" value="' + product_data["size_data"][i]["wasprice"] + '" size="2" aria-invalid="false" style="width: 75%;">' +
                    '</td>';

                if (product_data["size_data"][i]["stock"] == 1) {
                    var instk = "In Stock";
                    var m1 = '<td>' +
                        '<select name="stock_status2' + i + '" id="stock_status2' + i + '" class="select2me col-md-9 stk se1' + i + '" style="height: 33px;">' +
                        '<option value=' + product_data["size_data"][i]["stock"] + ' selected disabled>' + instk + '</option>' +
                        '</select>' +
                        '</td>' +
                        '<td>' +
                        '<select name="vatid2' + i + '" id="vatid' + i + '" class="select2me col-md-9 se1 vt" style="height: 33px;">' +
                        '<option value=' + product_data["vat"][i]["id"] + ' selected disabled>' + product_data["vat"][i]["name"] + '</option>' +
                        '</select>' +
                        '</td>';
                }
                else if (product_data["size_data"][i]["stock"] == 2) {
                    var lowstk = "Low Stock";
                    var m2 = '<td>' +
                        '<select name="stock_status2' + i + '" id="stock_status2' + i + '" class="select2me col-md-9 stk se1' + i + '" style="height: 33px;">' +
                        '<option value=' + product_data["size_data"][i]["stock"] + ' selected disabled>' + lowstk + '</option>' +
                        '</select>' +
                        '</td>' +
                        '<td>' +
                        '<select name="vatid2' + i + '" id="vatid' + i + '" class="select2me col-md-9 se1 vt" style="height: 33px;">' +
                        '<option value=' + product_data["vat"][i]["id"] + ' selected disabled>' + product_data["vat"][i]["name"] + '</option>' +
                        '</select>' +
                        '</td>';
                }
                else if (product_data["size_data"][i]["stock"] == 3) {
                    var outstk = "Out Stock";
                    var m3 = '<td>' +
                        '<select name="stock_status2' + i + '" id="stock_status2' + i + '" class="select2me col-md-9 stk se1' + i + '" style="height: 33px;">' +
                        '<option value=' + product_data["size_data"][i]["stock"] + ' selected disabled>' + outstk + '</option>' +
                        '</select>' +
                        '</td>' +
                        '<td>' +
                        '<select name="vatid2' + i + '" id="vatid' + i + '" class="select2me col-md-9 se1 vt" style="height: 33px;">' +
                        '<option value=' + product_data["vat"][i]["id"] + ' selected disabled>' + product_data["vat"][i]["name"] + '</option>' +
                        '</select>' +
                        '</td>';
                }

                var markup2 = '<td>' +
                    '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + s + '"' +
                    'name="product_showa[]" class="md-check ch2' + i + '"';

                var at = product_data["size_data"][i]["show"] == 1 ? "checked" : "";

                var markup3 = at + '><label for="checkboxa2' + s + '"' +
                    '><span class="inc"></span><span class="check"></span>' +
                    '<span class="box"></span></label></div></div>' +
                    '</td>';
                s = s + 1;
                var markup4 = '<td>' +
                    '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + s + '"' +
                    'name="dummya[]" class="md-check ch2' + i + '"';

                var at2 = product_data["size_data"][i]["isdummy"] == 1 ? "checked" : "";

                var markup5 = at2 + '><label for="checkboxa2' + s + '"' +
                    '><span class="inc"></span><span class="check"></span>' +
                    '<span class="box"></span></label></div></div>' +
                    '</td>';
                s = s + 1;
                var markup6 = '<td>' +
                    '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + s + '"' +
                    'name="new-sizea[]" class="md-check ch2' + i + '"';

                var at3 = product_data["size_data"][i]["new"] == 1 ? "checked" : "";

                var markup7 = at3 + '><label for="checkboxa2' + s + '"' +
                    '><span class="inc"></span><span class="check"></span>' +
                    '<span class="box"></span></label></div></div>' +
                    '</td>';
                s = s + 1;
                var markup8 = '<td>' +
                    '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + s + '"' +
                    'name="speciala[]" class="md-check ch2' + i + '"';

                var at4 = product_data["size_data"][i]["soffer"] == 1 ? "checked" : "";

                var markup9 = at4 + '><label for="checkboxa2' + s + '"' +
                    '><span class="inc"></span><span class="check"></span>' +
                    '<span class="box"></span></label></div></div>' +
                    '</td>';
                s = s + 1;
                var markup10 = '<td>' +
                    '<input class="form-control input-small inp in1' + i + '" name="note12[]" type="text" id="note12' + i + '" value=' + product_data["size_data"][i]["note"] + '>' +
                    '<input type="hidden" class="btn green inp" id="size_hidden' + i + '" name="size_hidden' + i + '" value="' + product_data["size_data"][i]["pr_sizeid"] + '">' +
                    '</td>';
                last_ch = s;
                var markup11 = '<td><a href="#" class="sizetr'+i+'" onclick="sizetrclick('+i+');"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';

                if (product_data["size_data"][i]["stock"] == 1) {
                    $("#form-horizontal-pr2 #size2 #size-table2 tbody").append(markup + m1 + markup2 + markup3 + markup4 + markup5 + markup6 + markup7 + markup8 + markup9 + markup10 + markup11);
                }
                else if (product_data["size_data"][i]["stock"] == 2) {
                    $("#form-horizontal-pr2 #size2 #size-table2 tbody").append(markup + m2 + markup2 + markup3 + markup4 + markup5 + markup6 + markup7 + markup8 + markup9 + markup10 + markup11);
                }
                else if (product_data["size_data"][i]["stock"] == 3) {
                    $("#form-horizontal-pr2 #size2 #size-table2 tbody").append(markup + m3 + markup2 + markup3 + markup4 + markup5 + markup6 + markup7 + markup8 + markup9 + markup10 + markup11);
                }
            }
            last_ct = st;
        }
        var sz = 0;
        $.each(product_data["sizes"], function(index, value) {
            sz++;
        });
        for (t=0; t<sz; t++){
            $("#form-horizontal-pr2 #size2 #size-table2 tbody .ss").append($("<option></option>").val(product_data["sizes"][t]["id"]).html(product_data["sizes"][t]["size"]));
        }
        var cm = 0;
        $.each(product_data["combos"], function(index, value) {
            cm++;
        });
        for (m=0; m<cm; m++){
            $("#form-horizontal-pr2 #size2 #size-table2 tbody .vt").append($("<option></option>").val(product_data["combos"][m]["id"]).html(product_data["combos"][m]["name"]));
        }
        var mg = 0;
        $.each(product_data["images"], function(index, value) {
            mg++;
        });
        for (im=0; im<mg; im++){
            var markup20 = '<tr class="empty"><input type="hidden" class="btn green imghidden" id="img_hidden'+im+'" name="img_hidden[]" value="'+product_data["images"][im]["imagename"]+'">' +
                '<input type="hidden" class="btn green imgidhidden" id="img_idhidden'+im+'" name="img_idhidden[]" value="'+product_data["images"][im]["id"]+'"></tr>';
            $("#form-horizontal-pr2 #size2 #size-table2 tbody").append(markup20);
        }

        $("#form-horizontal-pr2 #size2 #size-table2 tbody .stk").append($("<option></option>").val(1).html("In Stock"));
        $("#form-horizontal-pr2 #size2 #size-table2 tbody .stk").append($("<option></option>").val(2).html("Low Stock"));
        $("#form-horizontal-pr2 #size2 #size-table2 tbody .stk").append($("<option></option>").val(3).html("Out Stock"));
        var dt = 0;
        $.each(product_data["related_data"], function(index, value) {
            dt++;
        });
        //var dt = product_data["related_data"][0]["countrltd"];
        //var dt = product_data["related_data"].length;
        if (dt != 0) {
            for (kk = 0; kk < dt; kk++) {
                var markup12 = '<tr class="empty" id="relatedtr'+kk+'">' +
                    '<td>' +
                    '<select name="product_related2' + kk + '" id="product_related2' + kk + '" class="select2me col-md-6 rlt" style="height: 33px;">' +
                    '<option value=' + product_data["related_data"][kk]["relatedproductid"] + ' selected disabled>' + product_data["product_related"][kk]["name"] + '</option>' +
                    '</select>' +
                    '</td>';

                var markup13 = '<td>' +
                    '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="ismaintenance2' + kk + '"' +
                    'name="ismaintenance2' + kk + '" class="md-check"';

                var at = product_data["related_data"][kk]["ismaintenance"] == 1 ? "checked" : "";

                var markup14 = at + '><label for="ismaintenance2' + kk + '"' +
                    '><span class="inc"></span><span class="check"></span>' +
                    '<span class="box"></span></label></div></div>' +
                    '<input type="hidden" class="btn green rlthidden" id="related_hidden' + kk + '" name="related_hidden' + kk + '" value="' + product_data["related_data"][kk]["related_id"] + '">' +
                    '</td>';

                var markup15 = '<td><a href="#" class="relatedtr'+kk+'" onclick="relatedtrclick('+kk+');"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';

                $("#form-horizontal-pr2 #related2 #related-pr2 tbody").append(markup12 + markup13 + markup14 + markup15);
            }
            last_kk = dt;
        }

        var rt = 0;
        $.each(product_data["product_related"], function(index, value) {
            rt++;
        });
        for (p=0; p<rt; p++){
            $("#form-horizontal-pr2 #related2 #related-pr2 tbody .rlt").append($("<option></option>").val(product_data["product_related"][p]["pr_id"]).html(product_data["product_related"][p]["name"]));
        }
        var rp = 0;
        $.each(product_data["products"], function(index, value) {
            rp++;
        });
        for (m=0; m<rp; m++){
            $("#form-horizontal-pr2 #related2 #related-pr2 tbody .rlt").append($("<option></option>").val(product_data["products"][m]["pr_id"]).html(product_data["products"][m]["name"]));
        }
        //var mt = product_data["images"][0]["countimg"];
        var mt = 0;
        $.each(product_data["images"], function(index, value) {
            mt++;
        });
        $("#form-horizontal-pr2 #img-table thead tr").html("");
        for (n=0; n<mt; n++){
            var markup34 = '<td>'+
                '<div id="'+product_data["images"][n]["id"]+'" class=" cbp-caption-active cbp-caption-overlayBottomReveal cbp-ready">'+
                '<div class="cbp-item">'+
                '<div class="cbp-item-wrapper">'+
                '<div class="cbp-caption">'+
                '<div class="cbp-caption-defaultWrap" id="img-caption">'+
                //'<img src="../../'+product_data["images"][n]["websitename"]+'/img/products/thumbs/'+product_data["images"][n]["imagename"]+'" alt="" class="pr_image2" width="480" height="360">'+
                '<img src="../img/products/thumbs/'+product_data["images"][n]["imagename"]+'" alt="" class="pr_image2" width="480" height="360">'+
                '</div>'+
                '<div class="cbp-caption-activeWrap">'+
                '<div class="cbp-l-caption-alignCenter">'+
                '<div class="cbp-l-caption-body" id="img-caption-body">'+
                '<a href="#" class="btn red uppercase delete-img" onclick="pimgdlt('+product_data["images"][n]["id"]+')">Delete</a>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</td>';
            //var markup30 = '<img src="../img/products/thumbs/'+product_data["images"][n]["imagename"]+'" alt="" class="pr_image2">';
            //'<a href="#" class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase delete-img" onclick="pimgdlt('+product_data["images"][n]["id"]+')">Delete</a>'
            $("#form-horizontal-pr2 #img-table thead tr").append(markup34);
        }
        $("#img_count").val(mt);

    });
}

function addSiteSelect(webID,productID){
    $(".addSiteSelect").val(webID);
    $(".productImgId").val(productID);
    catSelect(webID);
    getRelated(webID);
}

function SelectSite(webID){
    $(".addSiteSelect").val(webID);
    catSelect(webID);
    getRelated(webID);
}

function getProduct(id){
    $.post('include/operations.php?pg=products&process=product_edit&q=getProduct',{id:id},function (output) {
        $("#pr_name").html(output);
        $("#review_product").html(output);
    })
}

function getRelated(id){
    var site_id = id;
    $.post('include/operations.php?pg=products&process=product_edit&q=getRelated',{site_id:site_id},function (output) {
        $("#product_related").html(output);
        $("#product_related2").html(output);
    });
}

function siteSelect(webID){
    var i;
    var kk;
    var t;
    var m;
    var n;
    var p;
    var productID;

    if (webID == 1){
        productID = IDproduct;
    }
    if (webID == 2){
        productID = IDproduct2;
    }

    addSiteSelect(webID,productID);
    catSelect(webID);
    getRelated(webID);

    $.get('include/operations.php?pg=products&process=product_edit&q=get',{productID:productID,webID:webID},function (output) {

        var product_data = JSON.parse(output);

        //$("#form-horizontal-pr2 #pr_cat_top_id2").find("option:selected").removeAttr("selected");
        $("#form-horizontal-pr2 #pr_cat_top_id2").find("option:selected").removeProp("selected");
        $("#form-horizontal-pr2 #pr_cat_top_id2").find("option:selected").prop("selected",false);

        var cti = product_data["product_data"]["categoryid"];
        $("#productname2").attr("value",product_data["product_data"]["name"]);
        //$("#productlink2").attr("value",product_data["product_data"]["link"]);
        $("#fromprice2").attr("value",product_data["product_data"]["fromprice"]);
        //$("#form-horizontal-pr2 #pr_cat_top_id2 option:selected").prop("value",product_data["product_data"]["categoryid"]);
        $('#form-horizontal-pr2 #pr_cat_top_id2 option[value='+cti+']').prop("selected",true);
        $("#notes2").attr("value",product_data["product_data"]["note"]);
        $("#generalname2").attr("value",product_data["product_data"]["generalname"]);
        //$("#pr_image2").attr("src","../../"+product_data["product_data"]["websitename"]+"/img/products/thumbs/"+product_data["product_data"]["pr_image"]);
        //$("#pr_image2").attr("src","../img/products/thumbs/"+product_data["product_data"]["pr_image"]);
        $("#productinfo2").text(product_data["product_data"]["productinfo"]);
        $("#product_hidden").attr("value",product_data["product_data"]["pr_id"]);
        $("#sz_hidden").attr("value",product_data["product_data"]["szID"]);
        $("#product_img").attr("value",product_data["product_data"]["pr_image"]);

        $("#edge2").find("option:selected").removeProp("selected");
        $("#room2").find("option:selected").removeProp("selected");
        $("#material2").find("option:selected").removeProp("selected");
        $("#finish2").find("option:selected").removeProp("selected");
        $("#traffic2").find("option:selected").removeProp("selected");
        $("#wall2").find("option:selected").removeProp("selected");
        $("#colour4").find("option:selected").removeProp("selected");
        $("#colour25").find("option:selected").removeProp("selected");
        $("#pop2").find("option:selected").removeProp("selected");
        $("#optionstype2").find("option:selected").removeProp("selected");
        $("#pertype2").find("option:selected").removeProp("selected");

        $('#edge2 option[value='+product_data["product_data"]["edge"]+']').prop('selected',false);
        $('#room2 option[value='+product_data["product_data"]["room"]+']').prop("selected",false);
        $('#material2 option[value='+product_data["product_data"]["material"]+']').prop("selected",false);
        $('#finish2 option[value='+product_data["product_data"]["finish"]+']').prop("selected",false);
        $('#traffic2 option[value='+product_data["product_data"]["traffic"]+']').prop("selected",false);
        $('#wall2 option[value='+product_data["product_data"]["wall"]+']').prop("selected",false);
        $('#colour4 option[value='+product_data["product_data"]["colour"]+']').prop("selected",false);
        $('#colour25 option[value='+product_data["product_data"]["colour2"]+']').prop("selected",false);
        $('#pop2 option[value='+product_data["product_data"]["pop"]+']').prop("selected",false);
        $('#optionstype2 option[value='+product_data["product_data"]["optionstype"]+']').prop("selected",false);
        $('#pertype2 option[value='+product_data["product_data"]["pertype"]+']').prop("selected",false);

        //var stredge = $('#edge2 option[value='+product_data["product_data"]["edge"]+']').text();
        $('#edge2 option[value='+product_data["product_data"]["edge"]+']').prop("selected",true);
        //$("#edge2 option:selected").prop("value",product_data["product_data"]["edge"]);
        //$('#edge2 option[value='+product_data["product_data"]["edge"]+']').text(stredge);

        //var strroom = $('#room2 option[value='+product_data["product_data"]["room"]+']').text();
        //$("#room2 option:selected").prop("value",product_data["product_data"]["room"]);
        $('#room2 option[value='+product_data["product_data"]["room"]+']').prop("selected",true);
        //$('#room2 option[value='+product_data["product_data"]["room"]+']').text(strroom);

        //var strmaterial = $('#material2 option[value='+product_data["product_data"]["material"]+']').text();
        //$("#material2 option:selected").prop("value",product_data["product_data"]["material"]);
        $('#material2 option[value='+product_data["product_data"]["material"]+']').prop("selected",true);
        //$('#material2 option[value='+product_data["product_data"]["material"]+']').text(strmaterial);

        //var strfinish = $('#finish2 option[value='+product_data["product_data"]["finish"]+']').text();
        //$("#finish2 option:selected").prop("value",product_data["product_data"]["finish"]);
        $('#finish2 option[value='+product_data["product_data"]["finish"]+']').prop("selected",true);
        //$('#finish2 option[value='+product_data["product_data"]["finish"]+']').text(strfinish);

        //var strtraffic = $('#traffic2 option[value='+product_data["product_data"]["traffic"]+']').text();
        $("#traffic2 option:selected").prop("value",product_data["product_data"]["traffic"]);
        $('#traffic2 option[value='+product_data["product_data"]["traffic"]+']').prop("selected",true);
        //$('#traffic2 option[value='+product_data["product_data"]["traffic"]+']').attr(strtraffic);

        //var strwall = $('#wall2 option[value='+product_data["product_data"]["wall"]+']').text();
        //$("#wall2 option:selected").prop("value",product_data["product_data"]["wall"]);
        $('#wall2 option[value='+product_data["product_data"]["wall"]+']').prop("selected",true);
        //$('#wall2 option[value='+product_data["product_data"]["wall"]+']').text(strwall);

        //var strcolour = $('#colour4 option[value='+product_data["product_data"]["colour"]+']').text();
        //$("#colour4 option:selected").prop("value",product_data["product_data"]["colour"]);
        $('#colour4 option[value='+product_data["product_data"]["colour"]+']').prop("selected",true);
        //$('#colour4 option[value='+product_data["product_data"]["colour"]+']').text(strcolour);

        //var strcolour25 = $('#colour25 option[value='+product_data["product_data"]["colour2"]+']').text();
        //$("#colour25 option:selected").prop("value",product_data["product_data"]["colour2"]);
        $('#colour25 option[value='+product_data["product_data"]["colour2"]+']').prop("selected",true);
        //$('#colour25 option[value='+product_data["product_data"]["colour2"]+']').text(strcolour25);

        //var strpop2 = $('#pop2 option[value='+product_data["product_data"]["pop"]+']').text();
        //$("#pop2 option:selected").prop("value",product_data["product_data"]["pop"]);
        $('#pop2 option[value='+product_data["product_data"]["pop"]+']').prop("selected",true);
        //$('#pop2 option[value='+product_data["product_data"]["pop"]+']').text(strpop2);

        //var stroptionstype2 = $('#optionstype2 option[value='+product_data["product_data"]["optionstype"]+']').text();
        //$("#optionstype2 option:selected").prop("value",product_data["product_data"]["optionstype"]);
        $('#optionstype2 option[value='+product_data["product_data"]["optionstype"]+']').prop("selected",true);
        //$('#optionstype2 option[value='+product_data["product_data"]["optionstype"]+']').text(stroptionstype2);

        //var strpertype2 = $('#pertype2 option[value='+product_data["product_data"]["pertype"]+']').text();
        //$("#pertype2 option:selected").prop("value",product_data["product_data"]["pertype"]);
        $('#pertype2 option[value='+product_data["product_data"]["pertype"]+']').prop("selected",true);
        //$('#pertype2 option[value='+product_data["product_data"]["pertype"]+']').text(strpertype2);

        $("#gap2").attr("value",product_data["product_data"]["gap"]);

        var new2 = product_data["product_data"]["new"] == 1 ? "checked" : "";
        var soffer2 = product_data["product_data"]["soffer"] == 1 ? "checked" : "";
        var freesample2 = product_data["product_data"]["freesample"] == 1 ? "checked" : "";
        var projects2 = product_data["product_data"]["projects"] == 1 ? "checked" : "";
        var show2 = product_data["product_data"]["show"] == 1 ? "checked" : "";
        //alert("new2:     "+new2+"       soffer2:   "+soffer2+"     free:    "+freesample2+"     pro:     "+projects2+"       show2:     "+show2);
        $("#checkboxa51").prop("checked",new2);
        $("#checkboxa52").prop("checked",soffer2);
        $("#checkboxa53").prop("checked",freesample2);
        $("#checkboxa54").prop("checked",projects2);
        $("#checkboxa55").prop("checked",show2);

        //$("#size_hidden").attr("value",product_data["size_data"]["pr_sizeid"]);
        $("#form-horizontal-pr2 #size2 #size-table2 tbody .empty").empty();
        $("#form-horizontal-pr2 #related2 #related-pr2 tbody .empty").empty();
        $("#form-horizontal-pr2 #img-table thead tr").empty();

        $("#form-horizontal-pr2 #size2 #size-table2 tbody .empty").remove();
        $("#form-horizontal-pr2 #related2 #related-pr2 tbody .empty").remove();

        var st = 0;
        $.each(product_data["size_data"], function(index, value) {
            st++;
        });
        //var st = product_data["size_data"][0]["countsz"];
        //var st = product_data["size_data"].length;
        if (st != 0) {
            for (i = 0; i < st; i++) {
                var markup = '<tr class="empty" id="sizetr'+i+'">' +
                    '<td>' +
                    '<select class="form-control select2me ss se1' + i + '" id="options22' + i + '" name="options22' + i + '">' +
                    '<option value=' + product_data["size_data"][i]["sizeid"] + ' selected disabled>' + product_data["size_pr"][i]["size"] + '</option>' +
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input class="form-control input-small inp in1' + i + '" name="price12[]" type="text" id="price12' + i + '" value="' + product_data["size_data"][i]["price"] + '" size="2" aria-invalid="false" style="width: 75%;">' +
                    '</td>' +
                    '<td>' +
                    '<input class="form-control input-small inp in1' + i + '" name="price22[]" type="text" id="price22' + i + '" value="' + product_data["size_data"][i]["wasprice"] + '" size="2" aria-invalid="false" style="width: 75%;">' +
                    '</td>';

                if (product_data["size_data"][i]["stock"] == 1) {
                    var instk = "In Stock";
                    var m1 = '<td>' +
                        '<select name="stock_status2' + i + '" id="stock_status2' + i + '" class="select2me col-md-9 stk se1' + i + '" style="height: 33px;">' +
                        '<option value=' + product_data["size_data"][i]["stock"] + ' selected disabled>' + instk + '</option>' +
                        '</select>' +
                        '</td>' +
                        '<td>' +
                        '<select name="vatid2' + i + '" id="vatid' + i + '" class="select2me col-md-9 se1 vt" style="height: 33px;">' +
                        '<option value=' + product_data["vat"][i]["id"] + ' selected disabled>' + product_data["vat"][i]["name"] + '</option>' +
                        '</select>' +
                        '</td>';
                }
                else if (product_data["size_data"][i]["stock"] == 2) {
                    var lowstk = "Low Stock";
                    var m2 = '<td>' +
                        '<select name="stock_status2' + i + '" id="stock_status2' + i + '" class="select2me col-md-9 stk se1' + i + '" style="height: 33px;">' +
                        '<option value=' + product_data["size_data"][i]["stock"] + ' selected disabled>' + lowstk + '</option>' +
                        '</select>' +
                        '</td>' +
                        '<td>' +
                        '<select name="vatid2' + i + '" id="vatid' + i + '" class="select2me col-md-9 se1 vt" style="height: 33px;">' +
                        '<option value=' + product_data["vat"][i]["id"] + ' selected disabled>' + product_data["vat"][i]["name"] + '</option>' +
                        '</select>' +
                        '</td>';
                }
                else if (product_data["size_data"][i]["stock"] == 3) {
                    var outstk = "Out Stock";
                    var m3 = '<td>' +
                        '<select name="stock_status2' + i + '" id="stock_status2' + i + '" class="select2me col-md-9 stk se1' + i + '" style="height: 33px;">' +
                        '<option value=' + product_data["size_data"][i]["stock"] + ' selected disabled>' + outstk + '</option>' +
                        '</select>' +
                        '</td>' +
                        '<td>' +
                        '<select name="vatid2' + i + '" id="vatid' + i + '" class="select2me col-md-9 se1 vt" style="height: 33px;">' +
                        '<option value=' + product_data["vat"][i]["id"] + ' selected disabled>' + product_data["vat"][i]["name"] + '</option>' +
                        '</select>' +
                        '</td>';
                }

                var markup2 = '<td>' +
                    '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + s + '"' +
                    'name="product_showa[]" class="md-check ch2' + i + '"';

                var at = product_data["size_data"][i]["show"] == 1 ? "checked" : "";

                var markup3 = at + '><label for="checkboxa2' + s + '"' +
                    '><span class="inc"></span><span class="check"></span>' +
                    '<span class="box"></span></label></div></div>' +
                    '</td>';
                s = s + 1;
                var markup4 = '<td>' +
                    '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + s + '"' +
                    'name="dummya[]" class="md-check ch2' + i + '"';

                var at2 = product_data["size_data"][i]["isdummy"] == 1 ? "checked" : "";

                var markup5 = at2 + '><label for="checkboxa2' + s + '"' +
                    '><span class="inc"></span><span class="check"></span>' +
                    '<span class="box"></span></label></div></div>' +
                    '</td>';
                s = s + 1;
                var markup6 = '<td>' +
                    '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + s + '"' +
                    'name="new-sizea[]" class="md-check ch2' + i + '"';

                var at3 = product_data["size_data"][i]["new"] == 1 ? "checked" : "";

                var markup7 = at3 + '><label for="checkboxa2' + s + '"' +
                    '><span class="inc"></span><span class="check"></span>' +
                    '<span class="box"></span></label></div></div>' +
                    '</td>';
                s = s + 1;
                var markup8 = '<td>' +
                    '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + s + '"' +
                    'name="speciala[]" class="md-check ch2' + i + '"';

                var at4 = product_data["size_data"][i]["soffer"] == 1 ? "checked" : "";

                var markup9 = at4 + '><label for="checkboxa2' + s + '"' +
                    '><span class="inc"></span><span class="check"></span>' +
                    '<span class="box"></span></label></div></div>' +
                    '</td>';
                s = s + 1;
                var markup10 = '<td>' +
                    '<input class="form-control input-small inp in1' + i + '" name="note12[]" type="text" id="note12' + i + '" value=' + product_data["size_data"][i]["note"] + '>' +
                    '<input type="hidden" class="btn green inp" id="size_hidden' + i + '" name="size_hidden' + i + '" value="' + product_data["size_data"][i]["pr_sizeid"] + '">' +
                    '</td>';
                last_ch = s;
                var markup11 = '<td><a href="#" class="sizetr'+i+'" onclick="sizetrclick('+i+');"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';

                if (product_data["size_data"][i]["stock"] == 1) {
                    $("#form-horizontal-pr2 #size2 #size-table2 tbody").append(markup + m1 + markup2 + markup3 + markup4 + markup5 + markup6 + markup7 + markup8 + markup9 + markup10 + markup11);
                }
                else if (product_data["size_data"][i]["stock"] == 2) {
                    $("#form-horizontal-pr2 #size2 #size-table2 tbody").append(markup + m2 + markup2 + markup3 + markup4 + markup5 + markup6 + markup7 + markup8 + markup9 + markup10 + markup11);
                }
                else if (product_data["size_data"][i]["stock"] == 3) {
                    $("#form-horizontal-pr2 #size2 #size-table2 tbody").append(markup + m3 + markup2 + markup3 + markup4 + markup5 + markup6 + markup7 + markup8 + markup9 + markup10 + markup11);
                }
            }
            last_ct = st;
        }
        var sz = 0;
        $.each(product_data["sizes"], function(index, value) {
            sz++;
        });
        for (t=0; t<sz; t++){
            $("#form-horizontal-pr2 #size2 #size-table2 tbody .ss").append($("<option></option>").val(product_data["sizes"][t]["id"]).html(product_data["sizes"][t]["size"]));
        }
        var cm = 0;
        $.each(product_data["combos"], function(index, value) {
            cm++;
        });
        for (m=0; m<cm; m++){
            $("#form-horizontal-pr2 #size2 #size-table2 tbody .vt").append($("<option></option>").val(product_data["combos"][m]["id"]).html(product_data["combos"][m]["name"]));
        }
        var mg = 0;
        $.each(product_data["images"], function(index, value) {
            mg++;
        });
        for (im=0; im<mg; im++){
            var markup20 = '<tr class="empty"><input type="hidden" class="btn green imghidden" id="img_hidden'+im+'" name="img_hidden[]" value="'+product_data["images"][im]["imagename"]+'">' +
                '<input type="hidden" class="btn green imgidhidden" id="img_idhidden'+im+'" name="img_idhidden[]" value="'+product_data["images"][im]["id"]+'"></tr>';
            $("#form-horizontal-pr2 #size2 #size-table2 tbody").append(markup20);
        }

        $("#form-horizontal-pr2 #size2 #size-table2 tbody .stk").append($("<option></option>").val(1).html("In Stock"));
        $("#form-horizontal-pr2 #size2 #size-table2 tbody .stk").append($("<option></option>").val(2).html("Low Stock"));
        $("#form-horizontal-pr2 #size2 #size-table2 tbody .stk").append($("<option></option>").val(3).html("Out Stock"));
        var dt = 0;
        $.each(product_data["related_data"], function(index, value) {
            dt++;
        });
        //var dt = product_data["related_data"][0]["countrltd"];
        //var dt = product_data["related_data"].length;
        if (dt != 0) {
            for (kk = 0; kk < dt; kk++) {
                var markup12 = '<tr class="empty" id="relatedtr'+kk+'">' +
                    '<td>' +
                    '<select name="product_related2' + kk + '" id="product_related2' + kk + '" class="select2me col-md-6 rlt" style="height: 33px;">' +
                    '<option value=' + product_data["related_data"][kk]["relatedproductid"] + ' selected disabled>' + product_data["product_related"][kk]["name"] + '</option>' +
                    '</select>' +
                    '</td>';

                var markup13 = '<td>' +
                    '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="ismaintenance2' + kk + '"' +
                    'name="ismaintenance2' + kk + '" class="md-check"';

                var at = product_data["related_data"][kk]["ismaintenance"] == 1 ? "checked" : "";

                var markup14 = at + '><label for="ismaintenance2' + kk + '"' +
                    '><span class="inc"></span><span class="check"></span>' +
                    '<span class="box"></span></label></div></div>' +
                    '<input type="hidden" class="btn green rlthidden" id="related_hidden' + kk + '" name="related_hidden' + kk + '" value="' + product_data["related_data"][kk]["related_id"] + '">' +
                    '</td>';

                var markup15 = '<td><a href="#" class="relatedtr'+kk+'" onclick="relatedtrclick('+kk+');"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';

                $("#form-horizontal-pr2 #related2 #related-pr2 tbody").append(markup12 + markup13 + markup14 + markup15);
            }
            last_kk = dt;
        }

        var rt = 0;
        $.each(product_data["product_related"], function(index, value) {
            rt++;
        });
        for (p=0; p<rt; p++){
            $("#form-horizontal-pr2 #related2 #related-pr2 tbody .rlt").append($("<option></option>").val(product_data["product_related"][p]["pr_id"]).html(product_data["product_related"][p]["name"]));
        }
        var rp = 0;
        $.each(product_data["products"], function(index, value) {
            rp++;
        });
        for (m=0; m<rp; m++){
            $("#form-horizontal-pr2 #related2 #related-pr2 tbody .rlt").append($("<option></option>").val(product_data["products"][m]["pr_id"]).html(product_data["products"][m]["name"]));
        }
        //var mt = product_data["images"][0]["countimg"];
        var mt = 0;
        $.each(product_data["images"], function(index, value) {
            mt++;
        });
        $("#form-horizontal-pr2 #img-table thead tr").html("");
        for (n=0; n<mt; n++){
            var markup34 = '<td>'+
                '<div id="'+product_data["images"][n]["id"]+'" class=" cbp-caption-active cbp-caption-overlayBottomReveal cbp-ready">'+
                '<div class="cbp-item">'+
                '<div class="cbp-item-wrapper">'+
                '<div class="cbp-caption">'+
                '<div class="cbp-caption-defaultWrap" id="img-caption">'+
                //'<img src="../../'+product_data["images"][n]["websitename"]+'/img/products/thumbs/'+product_data["images"][n]["imagename"]+'" alt="" class="pr_image2" width="480" height="360">'+
                '<img src="../img/products/thumbs/'+product_data["images"][n]["imagename"]+'" alt="" class="pr_image2" width="480" height="360">'+
                '</div>'+
                '<div class="cbp-caption-activeWrap">'+
                '<div class="cbp-l-caption-alignCenter">'+
                '<div class="cbp-l-caption-body" id="img-caption-body">'+
                '<a href="#" class="btn red uppercase delete-img" onclick="pimgdlt('+product_data["images"][n]["id"]+')">Delete</a>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</td>';
            //var markup30 = '<img src="../img/products/thumbs/'+product_data["images"][n]["imagename"]+'" alt="" class="pr_image2">';
            //'<a href="#" class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase delete-img" onclick="pimgdlt('+product_data["images"][n]["id"]+')">Delete</a>'
            $("#form-horizontal-pr2 #img-table thead tr").append(markup34);
        }
        $("#img_count").val(mt);

    });

}

$("#plus_related2").on("click",function (){
    var pr_id = $("#product_related2 option:selected").val();
    var select_pr = $('#product_related2 option[value='+pr_id+']').text();
    var sz_id = $("#sz_hidden").val();

    var pr_site = $(".addSiteSelect").val();

    var markup = '<tr class="empty" id="relatedtr'+last_kk+'">' +
        '<td>' +
        '<select name="product_related2'+last_kk+'" id="product_related2'+last_kk+'" class="select2me col-md-6 rlt slcrlt" style="height: 33px;">'+
        '<option value='+pr_id+' selected disabled>'+select_pr+'</option>'+
        '</select>' +
        '</td>';

    var markup2 = '<td>' +
        '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="ismaintenance2'+last_kk+'"'+
        'name="ismaintenance2'+last_kk+'" class="md-check"';

    var at = $("#ismaintenance2").prop("checked") ? "checked" : "";

    var markup3 = at+'><label for="ismaintenance2'+last_kk+'"'+
        '><span class="inc"></span><span class="check"></span>'+
        '<span class="box"></span></label></div></div>' +
        '<input type="hidden" class="btn green rlthidden" id="related_hidden' + last_kk + '" name="related_hidden' + last_kk + '" value="">' +
        '</td>';

    $.post('include/operations.php?pg=products&process=product_add&q=addrlt',{
        last_kk: last_kk,
        pr_id: pr_id,
        sz_id: sz_id,
        at: at,
        pr_site: pr_site
    }, function (rltid){
        var out = rltid.split("-");
        $("#related_hidden" + out[1] + "").val(out[0]);
    });

    var markup4 = '<td><a href="#" class="relatedtr'+last_kk+'" onclick="relatedtrclick('+last_kk+');"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';

    last_kk = last_kk + 1;

    $("#related-pr2 tbody").append(markup+markup2+markup3+markup4);

    $.get('include/operations.php?pg=products&process=product_add&q=add',function (output) {
        var size_data = JSON.parse(output);
        for (t=0; t<size_data["products"][0]["cntpr"]; t++) {
            $("#related-pr2 tbody .slcrlt").append($("<option></option>").val(size_data["products"][t]["pr_id"]).html(size_data["products"][t]["name"]));
        }
    });

    var each=0;
    var at1;
    var a=0;
    var b=0;
    var pr=0;
    var ch=0;

    //$("#related-pr table tr td").children("select").each(function(){
    rltpr[z] = pr_id;
    z++;

    //});

    $("#related-pr2 table tr td input[type=checkbox]").each(function(){
        at1 = $("#ismaintenance2"+each+"").prop("checked") ? "1" : "0";
        rltch[b] = at1;
        b++;
        each++;
        ch++;
    });
    //alert("rltpr:   "+JSON.stringify(rltpr));
    //alert("rltch:   "+JSON.stringify(rltch));

    $("#rltpr2").val(z);
    $("#rltch2").val(ch);
    $("#ismaintenance2").prop("checked",false);
});

$("#plus2").on("click",function (){
    //alert("last_ch: "+last_ch+"         last_ct: "+last_ct);
    sum = sum + 3;
    var size = $("#sz_options22 option:selected").val();
    var select_size = $('#sz_options22 option[value=' + size + ']').text();
    var stock_status = $("#stock_status2 option:selected").val();
    var select_stock = $('#stock_status2 option[value=' + stock_status + ']').text();
    var vat = $("#vatid2 option:selected").val();
    var select_vatid = $('#vatid2 option[value=' + vat + ']').text();
    var price = $("#price12").val();
    var price2 = $("#price22").val();
    var note1 = $("#note12").val();

    var pr_id = $("#product_hidden").val();
    var sz_id = $("#sz_hidden").val();

    var markup = '<tr class="empty" id="sizetr'+last_ct+'">' +
        '<td>' +
        '<select class="form-control select2me ss slc se1" id="options22' + last_ct + '" name="options22' + last_ct + '">' +
        '<option value=' + size + ' selected disabled>' + select_size + '</option>' +
        '</select>' +
        '</td>' +
        '<td>' +
        '<input class="form-control input-small inp in1" name="price12[]" type="text" id="price12' + last_ct + '" value="' + price + '" size="2" aria-invalid="false" style="width: 75%;">' +
        '</td>' +
        '<td>' +
        '<input class="form-control input-small inp in1" name="price22[]" type="text" id="price22' + last_ct + '" value="' + price2 + '" size="2" aria-invalid="false" style="width: 75%;">' +
        '</td>' +
        '<td>' +
        '<select name="stock_status2' + last_ct + '" id="stock_status2' + last_ct + '" class="select2me col-md-9 se1 stk stk1" style="height: 33px;">' +
        '<option value=' + stock_status + ' selected disabled>' + select_stock + '</option>' +
        '</select>' +
        '</td>' +
        '<td>' +
        '<select name="vatid2' + last_ct + '" id="vatid2' + last_ct + '" class="select2me col-md-9 se1 vt vt1" style="height: 33px;">' +
        '<option value=' + vat + ' selected disabled>' + select_vatid + '</option>' +
        '</select>' +
        '</td>';

    var markup2 = '<td>' +
        '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + last_ch + '"' +
        'name="product_showa" class="md-check ch1"';

    var at = $("#product_showa").prop("checked") ? "checked" : "";

    var markup3 = at + '><label for="checkboxa2' + last_ch + '"' +
        '><span class="inc"></span><span class="check"></span>' +
        '<span class="box"></span></label></div></div>' +
        '</td>';
    last_ch = last_ch + 1;
    var markup4 = '<td>' +
        '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + last_ch + '"' +
        'name="dummya" class="md-check ch1"';

    var at2 = $("#dummya").prop("checked") ? "checked" : "";

    var markup5 = at2 + '><label for="checkboxa2' + last_ch + '"' +
        '><span class="inc"></span><span class="check"></span>' +
        '<span class="box"></span></label></div></div>' +
        '</td>';

    last_ch = last_ch + 1;

    var markup6 = '<td>' +
        '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + last_ch + '"' +
        'name="new-sizea" class="md-check ch1"';

    var at3 = $("#new-sizea").prop("checked") ? "checked" : "";

    var markup7 = at3 + '><label for="checkboxa2' + last_ch + '"' +
        '><span class="inc"></span><span class="check"></span>' +
        '<span class="box"></span></label></div></div>' +
        '</td>';

    last_ch = last_ch + 1;

    var markup8 = '<td>' +
        '<div class="col-md-4"><div class="md-checkbox" style="margin-top: 5px;"><input type="checkbox" id="checkboxa2' + last_ch + '"' +
        'name="speciala" class="md-check ch1"';

    var at4 = $("#speciala").prop("checked") ? "checked" : "";

    var markup9 = at4 + '><label for="checkboxa2' + last_ch + '"' +
        '><span class="inc"></span><span class="check"></span>' +
        '<span class="box"></span></label></div></div>' +
        '</td>';
    var markup10 = '<td>' +
        '<input class="form-control input-small inp in1" name="note12[]" type="text" id="note12' + last_ch + '" value=' + note1 + '>' +
        '<input type="hidden" class="btn green inp" id="size_hidden' + last_ct + '" name="size_hidden' + last_ct + '" value="">' +
        '</td>';

    $.post('include/operations.php?pg=products&process=product_add&q=addsz',{
        last_ct: last_ct,
        size: size,
        pr_id: pr_id,
        sz_id: sz_id,
        price: price,
        price2: price2,
        stock_status: stock_status,
        vat: vat,
        note1: note1,
        at: at,
        at2: at2,
        at3: at3,
        at4: at4
    }, function (sizeid) {
        var out = sizeid.split("-");
        $("#size_hidden" + out[1] + "").val(out[0]);
    });

    last_ch = last_ch + 1;
    var markup11 = '<td><a href="#" class="sizetr'+last_ct+'" onclick="sizetrclick('+last_ct+');"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';
    last_ct = last_ct + 1;
    /*
     var markup11 = '<th style="padding-top: 15px;">'+
     '<a href="#" style="margin-right: 20px;">'+
     '<i class="fa fa-pencil" aria-hidden="true"></i></a>'+
     '<a href="#"><i class="fa fa-trash" aria-hidden="true"></i>'+
     '</a>'+
     '</th>' +
     '</tr>';
     */
    if (price == "" || price2 == "" || size == "Select" || stock_status == "Select" || vat == "Select") {
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $("#form-horizontal-pr2 #size-table2 tbody").append(markup + markup2 + markup3 + markup4 + markup5 + markup6 + markup7 + markup8 + markup9 + markup10 + markup11);

        $("#size-table2 tbody .stk1").append($("<option></option>").val(1).html("In Stock"));
        $("#size-table2 tbody .stk1").append($("<option></option>").val(2).html("Low Stock"));
        $("#size-table2 tbody .stk1").append($("<option></option>").val(3).html("Out Stock"));

        $.get('include/operations.php?pg=products&process=product_add&q=add', function (output) {
            var size_data = JSON.parse(output);
            for (t = 0; t < size_data["sizes"][0]["cntsz"]; t++) {
                $("#form-horizontal-pr2 #size-table tbody .slc").append($("<option></option>").val(size_data["sizes"][t]["id"]).html(size_data["sizes"][t]["size"]));
            }
            for (u = 0; u < size_data["combos"][0]["countcmbs"]; u++) {
                $("#size-table2 tbody .vt1").append($("<option></option>").val(size_data["combos"][u]["id"]).html(size_data["combos"][u]["name"]));
            }
        });
        sl_plus2 = sl_plus2 + 4;

        var each = 5;
        var at1;
        var i = 0;

        var b = 0;
        var countpr = 0;
        var countsl = 0;
        var countch = 0;

        $("#size-table2 table tr td").children("input.form-control").each(function () {
            pr[i] = $(this).val();
            i++;
            countpr++;
        });

        sl[a] = size;
        a++;
        sl[a] = stock_status;
        a++;
        sl[a] = vat;
        a++;
        $("#size-table2 table tr td input[type=checkbox]").each(function () {
            at1 = $("#checkbox" + each + "").prop("checked") ? "1" : "0";
            ch[b] = at1;
            b++;
            each++;
            countch++;
        });

        $("#hiddenpr2").val(countpr);
        $("#hiddensl2").val(countsl);
        $("#hiddench2").val(countch);
        $("#price12").val("");
        $("#price22").val("");
        $("#note12").val("");
        $("#product_showa").prop("checked", false);
        $("#dummya").prop("checked", false);
        $("#speciala").prop("checked", false);
        $("#new-sizea").prop("checked", false);
    }
});

function sizetrclick(val) {
    var attr = $("#sizetr"+val+"").attr("id");
    var selectsz = $("#options22"+val+" option:selected").val();
    var prid = $("#product_hidden").val();
    $.get('include/operations.php?pg=products&process=PrSizeDelete',{
        prid:  prid,
        selectsz: selectsz
    },function (output) {
        if (output == 1) {
            var status = "lime";
            var message = "Deleted";
            UINotific8("true", message, status);
            $("#"+attr).css("display","none");
        }
        else if (output == 0) {
            var status = "ruby";
            var message = "Not Deleted";
            UINotific8("false", message, status);
        }
    });
}

function relatedtrclick(val) {
    var attr = $("#relatedtr"+val+"").attr("id");
    var selectsz = $("#product_related2"+val+" option:selected").val();
    var prid = $("#product_hidden").val();
    $.get('include/operations.php?pg=products&process=PrRelatedDelete',{
        prid: prid,
        selectsz: selectsz
    },function (output) {
        if (output == 1) {
            var status = "lime";
            var message = "Deleted";
            UINotific8("true", message, status);
            $("#"+attr).css("display","none");
        }
        else if (output == 0) {
            var status = "ruby";
            var message = "Not Deleted";
            UINotific8("false", message, status);
        }
    });
}

function edit_gallery(ID) {
    var imgid = ID;
    $.get('include/operations.php?pg=gallery&process=editGalleryImage&q=get',{imgid:imgid},function (output) {
        var galleryData = JSON.parse(output);
        var imgcti = galleryData["gallery_data"]["categoryid"];

        //$("#gallery_image_cat2").find("option:selected").removeAttr("selected");

        $("#editGalleryform").each(function(index, element) {
            $("#gallery_image_cat2").find("option:selected").removeAttr("selected");
        });

        $("#gallery_img2").attr("src","../img/gallery/thumbs/"+galleryData["gallery_data"]["image"]);
        $("#imageTitle2").attr("value",galleryData["gallery_data"]["imagetitle"]);
        //$("#gallery_image_cat2 option:selected").attr("value",galleryData["gallery_data"]["categoryid"]);
        $('#gallery_image_cat2 option[value='+imgcti+']').attr("selected",true);
        $('#gallery_image_cat2 option[value='+imgcti+']').text(galleryData["gallery_data"]["catname"]);
        $("#gallery_hidden").attr("value",imgid);
        $("#gallery_hidden2").attr("value",galleryData["gallery_data"]["image"]);
    });
}

function siteImage(id){
    var nt;
    var imageID = $(".productImgId").val();
    var pr_id = imageID;
    var image_site = id;
    var webID = image_site;

    $.get('include/operations.php?pg=products&process=product_edit&q=get',{pr_id:pr_id,webID:webID},function (output) {

        var product_data = JSON.parse(output);
        IDproduct = product_data["product_data"]["pr_id"];
        IDproduct2 = product_data["product_data"]["szID"];

    });

    if (webID == 1){
        addSiteSelect(webID,IDproduct);
        imageID = IDproduct;
    }
    if (webID == 2){
        addSiteSelect(webID,IDproduct2);
        imageID = IDproduct2;
    }

    $.get("include/operations.php?pg=products&process=image&pr=primage",{
        imageID: imageID,
        image_site: image_site
    },function (result){
         var image_data = JSON.parse(result);

        var cti = image_data["images"][0]["pr_id"];

         $("#image_edit #pr_name2").find("option:selected").removeProp("selected");
         $('#image_edit #pr_name2 option[value='+cti+']').prop("selected",true);
         //$('#image_edit #pr_name2').attr("disabled","disabled");
         //$('#image_edit #pr_name2 option[value='+cti+']').text(image_data["images"][0]["productname"]);
         $(".productImgId").attr("value",imageID);
         var mtt = 0;
         $.each(image_data["images"], function(index, value) {
            mtt++;
         });
         $("#image_edit #imgs thead tr").html("");
         $("#image_edit #imgs thead tr").empty();
         for (nt=0; nt<mtt; nt++){
             var markup34 = '<td>'+
             '<div id="'+image_data["images"][nt]["id"]+'" class=" cbp-caption-active cbp-caption-overlayBottomReveal cbp-ready">'+
             '<div class="cbp-item">'+
             '<div class="cbp-item-wrapper">'+
             '<div class="cbp-caption">'+
             '<div class="cbp-caption-defaultWrap" id="img-caption">'+
             //'<img src="../../'+image_data["images"][nt]["websitename"]+'/img/products/thumbs/'+image_data["images"][nt]["imagename"]+'" alt="" class="pr_image2" width="480" height="360">'+
             '<img src="../img/products/thumbs/'+image_data["images"][nt]["imagename"]+'" alt="" class="pr_image2" width="480" height="360">'+
             '</div>'+
             '<div class="cbp-caption-activeWrap">'+
             '<div class="cbp-l-caption-alignCenter">'+
             '<div class="cbp-l-caption-body" id="img-caption-body">'+
             '<a href="#" class="btn red uppercase delete-img" onclick="pimgdlt('+image_data["images"][nt]["id"]+')">Delete</a>'+
             '</div>'+
             '</div>'+
             '</div>'+
             '</div>'+
             '</div>'+
             '</div>'+
             '</div>'+
             '</td>';
             //var markup30 = '<img src="../img/products/thumbs/'+product_data["images"][n]["imagename"]+'" alt="" class="pr_image2">';
             $("#image_edit #imgs thead tr").append(markup34);
         }

    });
}

function edit_image(id){
    var nt;
    var imageID = id;
    var pr_id = imageID;
    var image_site = $(".addSiteSelect").val();
    var webID = image_site;

    $.get('include/operations.php?pg=products&process=product_edit&q=get',{pr_id:pr_id,webID:webID},function (output) {

        var product_data = JSON.parse(output);
        IDproduct = product_data["product_data"]["pr_id"];
        IDproduct2 = product_data["product_data"]["szID"];

    });

    if (webID == 1){
        addSiteSelect(webID,IDproduct);
        imageID = IDproduct;
    }
    if (webID == 2){
        addSiteSelect(webID,IDproduct2);
        imageID = IDproduct2;
    }

    $.get("include/operations.php?pg=products&process=image&pr=primage",{
        imageID: imageID,
        image_site: image_site
    },function (result){

        var image_data = JSON.parse(result);

        var cti = image_data["images"][0]["pr_id"];

        $("#image_edit #pr_name2").find("option:selected").removeProp("selected");
        $('#image_edit #pr_name2 option[value='+cti+']').prop("selected",true);

        //$('#image_edit #pr_name2').attr("disabled","disabled");
        //$('#image_edit #pr_name2 option[value='+cti+']').text(image_data["images"][0]["productname"]);

        $("#productImgId").attr("value",imageID);
        var mtt = 0;
        $.each(image_data["images"], function(index, value) {
            mtt++;
        });

        $("#image_edit #imgs thead tr").html("");
        $("#image_edit #imgs thead tr").empty();

        for (nt=0; nt<mtt; nt++){
            var markup34 = '<td>'+
                '<div id="'+image_data["images"][nt]["id"]+'" class=" cbp-caption-active cbp-caption-overlayBottomReveal cbp-ready">'+
                '<div class="cbp-item">'+
                '<div class="cbp-item-wrapper">'+
                '<div class="cbp-caption">'+
                '<div class="cbp-caption-defaultWrap" id="img-caption">'+
                //'<img src="../../'+image_data["images"][nt]["websitename"]+'/img/products/thumbs/'+image_data["images"][nt]["imagename"]+'" alt="" class="pr_image2" width="480" height="360">'+
                '<img src="../img/products/thumbs/'+image_data["images"][nt]["imagename"]+'" alt="" class="pr_image2" width="480" height="360">'+
                '</div>'+
                '<div class="cbp-caption-activeWrap">'+
                '<div class="cbp-l-caption-alignCenter">'+
                '<div class="cbp-l-caption-body" id="img-caption-body">'+
                '<a href="#" class="btn red uppercase delete-img" onclick="pimgdlt('+image_data["images"][nt]["id"]+')">Delete</a>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '</td>';
            //var markup30 = '<img src="../img/products/thumbs/'+product_data["images"][n]["imagename"]+'" alt="" class="pr_image2">';
            $("#image_edit #imgs thead tr").append(markup34);
        }

    });
}

var i;
var y=0;
var im;

var primg = new Array();
var imgid = new Array();
var pr2 = new Array();
var sl2 = new Array();
var stk2 = new Array();
var vat2 = new Array();
var ch2 = new Array();
var edgech2 = new Array();
var rltsl2 = new Array();
var rltch2 = new Array();
var rlthd = new Array();

function editData(){

    /*
    var a = "gitti";
    alert(a);

    $.post('include/operations.php?pg=products&process=product_edit&pr=prupdate', {
        a: a
    }, function (output) {
        if (output == "true") {
            //var status = "lime";
            //var message = "Inserted";
            $("#edit_product").modal("hide");
            //UINotific8(output, message, status);

        }
        else if (output == "false") {
            // var status = "ruby";
            //var message = "Not Inserted";
            // UINotific8(output, message, status);
        }
    })
*/
/*
    for (var ie = primg.length; ie > 0; ie--) {
        primg.pop();
    }
    for (var k = imgid.length; k > 0; k--) {
        imgid.pop();
    }
    for (var l = pr2.length; l > 0; l--) {
        pr2.pop();
    }
    for (var m = sl2.length; m > 0; m--) {
        sl2.pop();
    }
    for (var n = stk2.length; n > 0; n--) {
        stk2.pop();
    }
    for (var o = vat2.length; o > 0; o--) {
        vat2.pop();
    }
    for (var pe = edgech2.length; pe > 0; pe--) {
        edgech2.pop();
    }
    for (var re = ch2.length; re > 0; re--) {
        ch2.pop();
    }
    for (var ye = rlthd.length; ye > 0; ye--) {
        rlthd.pop();
    }
    for (var te = rltsl2.length; te > 0; te--) {
        rltsl2.pop();
    }
    for (var ue = rltch2.length; ue > 0; ue--) {
        rltch2.pop();
    }
*/

    var a=0;
    var b=0;
    var c=0;
    var d=0;
    var f=0;
    var g=0;
    var h=0;
    var p=0;
    var yy=0;
    var r=0;
    var z=0;
    var each=0;
    var each2=0;
    var tt,tt2;
    var td1;
    var td2=1;

    var productname2 = $("#productname2").val();
    var generalname2 = $("#generalname2").val();
    //var productlink2 = $("#productlink2").val();
    var fromprice2 = $("#fromprice2").val();
    var notes2 = $("#notes2").val();
    var productinfo2 = $("#productinfo2").val();
    var pr_cat_top_id2 = $("#pr_cat_top_id2").val();
    var product_hidden = $("#product_hidden").val();
    var sz_hidden = $("#sz_hidden").val();
    var img_count = $("#img_count").val();

    var edge2 = $("#edge2").val();
    var room2 = $("#room2").val();
    var material2 = $("#material2").val();
    var finish2 = $("#finish2").val();
    var traffic2 = $("#traffic2").val();
    var wall2 = $("#wall2").val();
    var colour4 = $("#colour4").val();
    var colour25 = $("#colour25").val();
    var pop2 = $("#pop2").val();
    var gap2 = $("#gap2").val();

    var optionstype2 = $("#optionstype2").val();
    var pertype2 = $("#pertype2").val();

    var pr_site = $("#pr_site").val();

    $("#size2 #size-table2 tr").children("input.imghidden").each(function(){
        primg[yy] = $(this).val();
        //alert("imghidden -> primg["+yy+"] -> "+primg[yy]);
        yy++;
    });
    $("#size2 #size-table2 tr").children("input.imgidhidden").each(function(){
        imgid[z] = $(this).val();
        //alert("imgidhidden -> imgid["+z+"] -> "+imgid[z]);
        z++;
    });
    $("#size2 #size-table2 tr td").children("input.inp").each(function(){
        pr2[a] = $(this).val();
        //alert("inp -> pr2["+a+"] -> "+pr2[a]);
        a++;
    });
    $('select.ss').find('option:selected').each(function() {
        sl2[b] = $(this).val();
        //alert("ss -> sl2["+b+"] -> "+sl2[b]);
        b++;
    });
    $('select.stk').find('option:selected').each(function() {
        stk2[r] = $(this).val();
        //alert("stk -> stk2["+r+"] -> "+stk2[r]);
        r++;
        //alert("r ->"+r);
    });
    $('select.vt').find('option:selected').each(function() {
        vat2[d] = $(this).val();
        //alert("vt -> vat2["+d+"] -> "+vat2[d]);
        d++;
    });
    $("#edge-room2 tr td input[type=checkbox]").each(function(){
        td1 = $("#checkboxa5"+td2+"").prop("checked") ? "1" : "0";
        edgech2[p] = td1;
        //alert("edge-room2 -> edgech2["+p+"] -> "+edgech2[p]);
        p++;
        //  alert("td2:"+td2);
        td2++;
    });
    $("#size2 #size-table2 tr td input[type=checkbox]").each(function(){
        tt = $("#checkboxa2"+each+"").prop("checked") ? "1" : "0";
        ch2[c] = tt;
        //alert("checkboxa2 -> ch2["+c+"] -> "+ch2[c]);
        c++;
        // alert("each:"+each);
        each++;
    });
    $("#related2 #related-pr2 tr td").children("input.rlthidden").each(function(){
        rlthd[h] = $(this).val();
        //alert("rlthidden -> rlthd["+h+"] -> "+rlthd[h]);
        h++;
    });
    $('select.rlt').find('option:selected').each(function() {
        rltsl2[f] = $(this).val();
        //alert("rlt -> rltsl2["+f+"] -> "+rltsl2[f]);
        f++;
    });
    $("#related2 #related-pr2 tr td input[type=checkbox]").each(function(){
        tt2 = $("#ismaintenance2"+each2+"").prop("checked") ? "1" : "0";
        rltch2[g] = tt2;
        //alert("related2 -> rltch2["+g+"] -> "+rltch2[g]);
        g++;
        //alert("each2:"+each2);
        each2++;
    });
    var prd2 = JSON.stringify(pr2);
    var slc2 = JSON.stringify(sl2);
    var stock2 = JSON.stringify(stk2);
    var vt2 = JSON.stringify(vat2);
    var check = JSON.stringify(ch2);
    var chedge2 = JSON.stringify(edgech2);
    var slrlt2 = JSON.stringify(rltsl2);
    var chrlt2 = JSON.stringify(rltch2);
    var hdrlt2 = JSON.stringify(rlthd);
    var imgpr2 = JSON.stringify(primg);
    var idimg2 = JSON.stringify(imgid);
    prd2 = JSON.parse(prd2);
    slc2 = JSON.parse(slc2);
    stock2 = JSON.parse(stock2);
    vt2 = JSON.parse(vt2);
    check = JSON.parse(check);
    chedge2 = JSON.parse(chedge2);
    slrlt2 = JSON.parse(slrlt2);
    chrlt2 = JSON.parse(chrlt2);
    hdrlt2 = JSON.parse(hdrlt2);

    /*
    imgpr2 = JSON.parse(imgpr2);
    idimg2 = JSON.parse(idimg2);
    */
    /*
    alert(prd2);
    alert(slc2);
    alert(stock2);
    alert(vt2);
    alert(check);
    alert(chedge2);
    alert(slrlt2);
    alert(chrlt2);
    alert(hdrlt2);
    */
/*
    JSON.stringify(pr2);
    JSON.stringify(sl2);
    JSON.stringify(stk2);
    JSON.stringify(vat2);
    JSON.stringify(ch2);
    JSON.stringify(edgech2);
    JSON.stringify(rltsl2);
    JSON.stringify(rltch2);
    JSON.stringify(rlthd);
    JSON.stringify(primg);
    JSON.stringify(imgid);
*/
/*
    alert("a: "+a);
    alert("b: "+b);
    alert("c: "+c);
    alert("d: "+d);
    alert("f: "+f);
    alert("g: "+g);
    alert("h: "+h);
    alert("r: "+r);
    alert("p: "+p);
*/
    /*
    alert("pr2: "+prd2);
    alert("sl2: "+slc2);
    alert("stk2: "+stock2);
    alert("vat2: "+vt2);
    alert("ch2: "+check);
    alert("edgech2: "+chedge2);
    alert("rltsl2: "+slrlt2);
    alert("rltch2: "+chrlt2);
    alert("rlthd: "+hdrlt2);
    alert("primg: "+imgpr2);
    alert("imgid: "+idimg2);
*/

    if (productname2 == "" || generalname2 == "" || fromprice2 == "" || pr_cat_top_id2 == "0"){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $.ajax({
            url: "include/operations.php?pg=products&process=product_edit&pr=prupdate",
            type: "POST",
            data: {
                productname2: productname2,
                generalname2: generalname2,
                //productlink2: productlink2,
                fromprice2: fromprice2,
                notes2: notes2,
                productinfo2: productinfo2,
                pr_cat_top_id2: pr_cat_top_id2,
                product_hidden: product_hidden,
                sz_hidden: sz_hidden,
                edge2: edge2,
                room2: room2,
                material2: material2,
                finish2: finish2,
                traffic2: traffic2,
                wall2: wall2,
                colour4: colour4,
                colour25: colour25,
                pop2: pop2,
                gap2: gap2,
                optionstype2: optionstype2,
                pertype2: pertype2,
                pr_site: pr_site,
                prd2: prd2,
                slc2: slc2,
                stock2: stock2,
                vt2: vt2,
                check: check,
                chedge2: chedge2,
                slrlt2: slrlt2,
                chrlt2: chrlt2,
                hdrlt2: hdrlt2,
                a: a,
                b: b,
                c: c,
                d: d,
                f: f,
                g: g,
                h: h,
                r: r,
                p: p
            },
            success: function (output){
                location.reload();
            }
        });
    }
}

function addData(){
    var productname = $("#productname").val();
    //var productlink = $("#productlink").val();
    var fromprice = $("#fromprice").val();
    var notes = $("#notes").val();
    var productinfo = $("#productinfo").val();
    var pr_cat_top_id = $("#options22").val();
    var generalname = $("#generalname").val();
    var hiddenpr = $("#hiddenpr").val();
    var hiddensl = $("#hiddensl").val();
    var hiddench = $("#hiddench").val();
    var rltprcnt = $("#rltpr").val();
    var rltchcnt = $("#rltch").val();

    var site = $(".addSiteSelect").val();

    var at1 = $("#new").prop("checked") ? "1" : "0";
    var soffer = $("#soffer").prop("checked") ? "1" : "0";
    var freesample = $("#freesample").prop("checked") ? "1" : "0";
    var projects = $("#projects").prop("checked") ? "1" : "0";
    var show = $("#show").prop("checked") ? "1" : "0";

    var edge = $("#edge").val();
    var room = $("#room").val();
    var material = $("#material").val();
    var finish = $("#finish").val();
    var traffic = $("#traffic").val();
    var wall = $("#wall").val();
    var colour = $("#colour").val();
    var colour2 = $("#colour2").val();
    var pop = $("#pop").val();
    var gap = $("#gap").val();
    var optionstype = $("#comboSelectoptionstype").val();
    var pertype = $("#comboSelectpertype").val();

    JSON.stringify(pr);
    JSON.stringify(sl);
    JSON.stringify(ch);
    JSON.stringify(rltpr);
    JSON.stringify(rltch);

    if (productname == "" || generalname == "" || fromprice == "" || notes == "" || productinfo == "" || pr_cat_top_id == "0"){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $.ajax({
            url: "include/operations.php?pg=products&process=product_add&pr=pradd",
            type: "POST",
            data: {
                edge: edge,
                room: room,
                material: material,
                finish: finish,
                traffic: traffic,
                wall: wall,
                colour: colour,
                colour2: colour2,
                pop: pop,
                gap: gap,
                at1: at1,
                soffer: soffer,
                freesample: freesample,
                projects: projects,
                show: show,
                optionstype: optionstype,
                pertype: pertype,
                productname: productname,
                generalname: generalname,
                //productlink: productlink,
                fromprice: fromprice,
                notes: notes,
                productinfo: productinfo,
                pr_cat_top_id: pr_cat_top_id,
                pr:pr,
                sl:sl,
                ch:ch,
                z:z,
                rltpr:rltpr,
                rltch:rltch,
                hiddenpr:hiddenpr,
                hiddensl:hiddensl,
                hiddench:hiddench,
                rltprcnt:rltprcnt,
                rltchcnt:rltchcnt,
                sum: sum,
                site: site
            },
            success: function (output){
                //alert(output);
                location.reload();
            }
        });
        /*
         $.post('include/operations.php?pg=products&process=product_add&pr=pradd', {
         edge: edge,
         room: room,
         material: material,
         finish: finish,
         traffic: traffic,
         wall: wall,
         colour: colour,
         colour2: colour2,
         pop: pop,
         gap: gap,
         at1: at1,
         soffer: soffer,
         freesample: freesample,
         projects: projects,
         show: show,
         optionstype: optionstype,
         pertype: pertype,
         productname: productname,
         generalname: generalname,
         productlink: productlink,
         fromprice: fromprice,
         notes: notes,
         productinfo: productinfo,
         pr_cat_top_id: pr_cat_top_id,
         pr:pr,
         sl:sl,
         ch:ch,
         z:z,
         rltpr:rltpr,
         rltch:rltch,
         hiddenpr:hiddenpr,
         hiddensl:hiddensl,
         hiddench:hiddench,
         rltprcnt:rltprcnt,
         rltchcnt:rltchcnt,
         sum: sum
         }, function (output) {
         if (output == "true") {
         $("#insert_product").modal("hide");
         $("#productname").val("");
         $("#productlink").val("");
         $("#fromprice").val("");
         $("#notes").val("");
         $("#productinfo").val("");
         $("#price").val("");
         $("#price2").val("");
         $("#note1").val("");
         }
         else if (output == "false") {
         var status = "ruby";
         var message = "Not Inserted";
         UINotific8(output, message, status);
         }
         })
         */
    }
}

/*
$("#product_add").on("click",function (){
    var productname = $("#productname").val();
    var productlink = $("#productlink").val();
    var fromprice = $("#fromprice").val();
    var notes = $("#notes").val();
    var productinfo = $("#productinfo").val();
    var pr_cat_top_id = $("#options22").val();
    var generalname = $("#generalname").val();
    var hiddenpr = $("#hiddenpr").val();
    var hiddensl = $("#hiddensl").val();
    var hiddench = $("#hiddench").val();
    var rltprcnt = $("#rltpr").val();
    var rltchcnt = $("#rltch").val();

    var at1 = $("#new").prop("checked") ? "1" : "0";
    var soffer = $("#soffer").prop("checked") ? "1" : "0";
    var freesample = $("#freesample").prop("checked") ? "1" : "0";
    var projects = $("#projects").prop("checked") ? "1" : "0";
    var show = $("#show").prop("checked") ? "1" : "0";

    var edge = $("#edge").val();
    var room = $("#room").val();
    var material = $("#material").val();
    var finish = $("#finish").val();
    var traffic = $("#traffic").val();
    var wall = $("#wall").val();
    var colour = $("#colour").val();
    var colour2 = $("#colour2").val();
    var pop = $("#pop").val();
    var gap = $("#gap").val();
    var optionstype = $("#comboSelectoptionstype").val();
    var pertype = $("#comboSelectpertype").val();

    JSON.stringify(pr);
    JSON.stringify(sl);
    JSON.stringify(ch);
    JSON.stringify(rltpr);
    JSON.stringify(rltch);

    if (productname == "" || generalname == "" || productlink == "" || fromprice == "" || notes == "" || productinfo == ""){
        var settings = {
                theme: "smoke",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "BOŞ";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("BOŞ ALAN KALMASIN", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $.ajax({
            url: "include/operations.php?pg=products&process=product_add&pr=pradd",
            type: "POST",
            data: {
                edge: edge,
                room: room,
                material: material,
                finish: finish,
                traffic: traffic,
                wall: wall,
                colour: colour,
                colour2: colour2,
                pop: pop,
                gap: gap,
                at1: at1,
                soffer: soffer,
                freesample: freesample,
                projects: projects,
                show: show,
                optionstype: optionstype,
                pertype: pertype,
                productname: productname,
                generalname: generalname,
                productlink: productlink,
                fromprice: fromprice,
                notes: notes,
                productinfo: productinfo,
                pr_cat_top_id: pr_cat_top_id,
                pr:pr,
                sl:sl,
                ch:ch,
                z:z,
                rltpr:rltpr,
                rltch:rltch,
                hiddenpr:hiddenpr,
                hiddensl:hiddensl,
                hiddench:hiddench,
                rltprcnt:rltprcnt,
                rltchcnt:rltchcnt,
                sum: sum
            },
            success: function (output){
                location.reload();
            }
        });
        /*
        $.post('include/operations.php?pg=products&process=product_add&pr=pradd', {
            edge: edge,
            room: room,
            material: material,
            finish: finish,
            traffic: traffic,
            wall: wall,
            colour: colour,
            colour2: colour2,
            pop: pop,
            gap: gap,
            at1: at1,
            soffer: soffer,
            freesample: freesample,
            projects: projects,
            show: show,
            optionstype: optionstype,
            pertype: pertype,
            productname: productname,
            generalname: generalname,
            productlink: productlink,
            fromprice: fromprice,
            notes: notes,
            productinfo: productinfo,
            pr_cat_top_id: pr_cat_top_id,
            pr:pr,
            sl:sl,
            ch:ch,
            z:z,
            rltpr:rltpr,
            rltch:rltch,
            hiddenpr:hiddenpr,
            hiddensl:hiddensl,
            hiddench:hiddench,
            rltprcnt:rltprcnt,
            rltchcnt:rltchcnt,
            sum: sum
        }, function (output) {
            if (output == "true") {
                $("#insert_product").modal("hide");
                $("#productname").val("");
                $("#productlink").val("");
                $("#fromprice").val("");
                $("#notes").val("");
                $("#productinfo").val("");
                $("#price").val("");
                $("#price2").val("");
                $("#note1").val("");
            }
            else if (output == "false") {
                var status = "ruby";
                var message = "Not Inserted";
                UINotific8(output, message, status);
            }
        })

    }
});
*/

function edit_size(size_id) {
    var size_id = size_id;
    $.get('include/operations.php?pg=sizes&process=size_edit&q=get',{size_id:size_id},function (output) {
        var sizes_data = JSON.parse(output);
        $("#size2").attr("value",sizes_data["sizes_data"]["size"]);
        $("#sizeot2").attr("value",sizes_data["sizes_data"]["sizeot"]);
        $("#width2").attr("value",sizes_data["sizes_data"]["width"]);
        $("#height2").attr("value",sizes_data["sizes_data"]["height"]);
        $("#thickness2").attr("value",sizes_data["sizes_data"]["thickness"]);
        $("#area2").attr("value",sizes_data["sizes_data"]["area"]);
        $("#volume2").attr("value",sizes_data["sizes_data"]["volume"]);
        $("#weight2").attr("value",sizes_data["sizes_data"]["weight"]);
        $("#itemunit2").attr("value",sizes_data["sizes_data"]["itemunit"]);
        $("#sizetype2").attr("value",sizes_data["sizes_data"]["sizetype"]);
        $("#sizeunit2").attr("value",sizes_data["sizes_data"]["sizeunit"]);
        $("#qtyunit2").attr("value",sizes_data["sizes_data"]["qtyunit"]);
        $("#size_hidden").attr("value",size_id);
    })
}

$(".delete_product").on("click",function (){
    var come = $(this).attr("id");
    var arr = come.split("_");
    var prID = arr[1];
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        if (result){
            $.get('include/operations.php?pg=products&process=product_delete',{prID:prID},function (output) {
                location.reload();
                /*
                if (output == "true"){
                    location.reload();

                    var status = "lime";
                    var message = "Deleted";
                    UINotific8(output,message,status);
                 $("#sample_1 #"+prID+"").css("display","none");
                }
                else if(output == "false"){
                    var status = "ruby";
                    var message = "Not Deleted";
                    UINotific8(output,message,status);
                }
                */
            })
        }
    });
});

$("#size_add").on("click",function () {
    var size = $("#size").val();
    var sizeot = $("#sizeot").val();
    var width = $("#width").val();
    var height = $("#height").val();
    var thickness = $("#thickness").val();
    var area = $("#area").val();
    var volume = $("#volume").val();
    var weight = $("#weight").val();
    var itemunit = $("#itemunit").val();
    var sizetype = $("#sizetype").val();
    var sizeunit = $("#sizeunit").val();
    var qtyunit = $("#qtyunit").val();
    if (size == "" || sizeot == "" || width == "" || height == "" || thickness == "" || area == "" || volume == "" || weight == "" || itemunit == "" || sizetype == "" || sizeunit == "" || qtyunit == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $.post('include/operations.php?pg=sizes&process=size_add', {
            size: size,
            sizeot: sizeot,
            width: width,
            height: height,
            thickness: thickness,
            area: area,
            volume: volume,
            weight: weight,
            itemunit: itemunit,
            sizetype: sizetype,
            sizeunit: sizeunit,
            qtyunit: qtyunit
        }, function (output) {
            if (output) {
                var status = "lime";
                var message = "Inserted";
                $("#insert_size").modal("hide");
                UINotific8(output, message, status);
                $("#size").val("");
                $("#sizeot").val("");
                $("#width").val("");
                $("#height").val("");
                $("#thickness").val("");
                $("#area").val("");
                $("#volume").val("");
                $("#weight").val("");
                $("#itemunit").val("");
                $("#sizetype").val("");
                $("#qtyunit").val("");
                $("#sizeunit").val("");
                var sz = output.split("-");
                $("#options2").append($("<option></option>").val(sz[1]).html(sz[0]));
                location.reload();
            }
            else if (output == "false") {
                var status = "ruby";
                var message = "Not Inserted";
                UINotific8(output, message, status);
            }
        })
    }
})

$("#size_edit").on("click",function () {
    var size2 = $("#size2").val();
    var sizeot2 = $("#sizeot2").val();
    var width2 = $("#width2").val();
    var height2 = $("#height2").val();
    var thickness2 = $("#thickness2").val();
    var area2 = $("#area2").val();
    var volume2 = $("#volume2").val();
    var weight2 = $("#weight2").val();
    var itemunit2 = $("#itemunit2").val();
    var sizetype2 = $("#sizetype2").val();
    var sizeunit2 = $("#sizeunit2").val();
    var qtyunit2 = $("#qtyunit2").val();
    var size_id = $("#size_hidden").val();
    if (size2 == "" || sizeot2 == "" || width2 == "" || height2 == "" || thickness2 == "" || area2 == "" || volume2 == "" || weight2 == "" || itemunit2 == "" || sizetype2 == "" || sizeunit2 == "" || qtyunit2 == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else {
        $.post('include/operations.php?pg=sizes&process=size_edit&q=update', {
            size2: size2,
            sizeot2: sizeot2,
            width2: width2,
            height2: height2,
            thickness2: thickness2,
            area2: area2,
            volume2: volume2,
            weight2: weight2,
            itemunit2: itemunit2,
            sizetype2: sizetype2,
            sizeunit2: sizeunit2,
            qtyunit2: qtyunit2,
            size_id:size_id
        }, function (output) {
            if (output == 1) {
                var status = "lime";
                var message = "Updated";
                $("#edit_size").modal("hide");
                UINotific8("true", message, status);
            }
            else if (output == 0) {
                var status = "ruby";
                var message = "Not Updated";
                UINotific8("false", message, status);
            }
        })
    }
})

$(".delete_sizess").on("click",function () {
    var come = $(this).attr("id");
    var arr = come.split("_");
    var sizeID = arr[1];
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        //alert(result);
        if (result){
            $.get('include/operations.php?pg=sizes&process=size_delete',{sizeID:sizeID},function (output) {

                if (output == 1){
                    var status = "lime";
                    var message = "Size Deleted";
                    UINotific8("true",message,status);
                    document.getElementById("sizess_"+sizeID).style.display = "none";
                }
                else if (output == 0){
                    var status = "ruby";
                    var message = "Size Not Deleted";
                    UINotific8("false",message,status);
                }

            })
        }
    });
});

$(".delete_optionstype").on("click",function () {
    var optID = $(this).attr("id");
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        //alert(result);
        if (result){
            $.get('include/operations.php?pg=combos&process=combos_delete&q=optionstype',{optID:optID},function (output) {
                var status = "lime";
                var message = "Deleted";
                UINotific8(output,message,status);
                document.getElementById(optID).style.display = "none";
            })
        }
    });
})

$(".delete_pertype").on("click",function (){
    var perID = $(this).attr("id");
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        //alert(result);
        if (result){
            $.get('include/operations.php?pg=combos&process=combos_delete&q=pertype',{perID:perID},function (output) {
                var status = "lime";
                var message = "Deleted";
                UINotific8(output,message,status);
                document.getElementById(perID).style.display = "none";
            })
        }
    });
})

$(".delete_review").on("click",function () {
    var come = $(this).attr("id");
    var arr = come.split("_");
    var rvID = arr[1];
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        if (result){
            $.get('include/operations.php?pg=reviews&process=review_delete',{rvID:rvID},function (output) {
                location.reload();
            })
        }
    });
});

$(".delete_question").on("click",function () {
    var come = $(this).attr("id");
    var arr = come.split("_");
    var queID = arr[1];
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        if (result){
            $.get('include/operations.php?pg=questions&process=qustion_delete',{queID:queID},function (output) {
                location.reload();
            })
        }
    });
});

$(".delete_emailformat").on("click",function () {
    var come = $(this).attr("id");
    var arr = come.split("_");
    var frID = arr[1];
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        if (result){
            $.get('include/operations.php?pg=emailformats&process=delete_emailformat',{frID:frID},function (output) {
                location.reload();
            })
        }
    });
});

$(".delete_postcode").on("click",function () {
    var come = $(this).attr("id");
    var arr = come.split("_");
    var pstID = arr[1];
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        if (result){
            $.get('include/operations.php?pg=postcodes&process=delete_postcode',{pstID:pstID},function (output) {
                location.reload();
            })
        }
    });
});

$(".delete_zone").on("click",function () {
    var come = $(this).attr("id");
    var arr = come.split("_");
    var znID = arr[1];
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        if (result){
            $.get('include/operations.php?pg=zones&process=delete_zone',{znID:znID},function (output) {
                //location.reload();
                if (output == 1){
                    var status = "lime";
                    var message = "Zone Deleted";
                    UINotific8("true",message,status);
                    $("#zoness_"+znID).css("display","none");
                }
                else{
                    var status = "ruby";
                    var message = "Zone Not Deleted";
                    UINotific8("false",message,status);
                }
            })
        }
    });
});

$(".delete_courierzone").on("click",function () {
    var come = $(this).attr("id");
    var arr = come.split("_");
    var couID = arr[1];
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        if (result){
            $.get('include/operations.php?pg=zones&process=delete_courierzone',{couID:couID},function (output) {
                //location.reload();
                if (output == 1){
                    var status = "lime";
                    var message = "Zone Courier Deleted";
                    UINotific8("true",message,status);
                    $("#zncourier_"+couID).css("display","none");
                }
                else{
                    var status = "ruby";
                    var message = "Zone Courier Not Deleted";
                    UINotific8("false",message,status);
                }
            })
        }
    });
})

$(".delete_royalmail").on("click",function () {
    var come = $(this).attr("id");
    var arr = come.split("_");
    var royalID = arr[1];
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        if (result){
            $.get('include/operations.php?pg=zones&process=delete_royalmail',{royalID:royalID},function (output) {
                //location.reload();
                if (output == 1){
                    var status = "lime";
                    var message = "Zone Royal Mail Deleted";
                    UINotific8("true",message,status);
                    $("#royalmail_"+royalID).css("display","none");
                }
                else{
                    var status = "ruby";
                    var message = "Zone Royal Mail Not Deleted";
                    UINotific8("false",message,status);
                }
            })
        }
    });
})

$("#insert_courierzone").click(function () {
    var cou = 0;
    $.post('include/operations.php?pg=zones&process=insert_courierzone',{
        cou: cou
    },function (output) {
        $("#sample_35 tbody").html(output);
    })
})

$("#insert_royalmail").click(function () {
    var royal = 0;
    $.post('include/operations.php?pg=zones&process=insert_royalmail',{
        royal: royal
    },function (output) {
        $("#sample_36 tbody").html(output);
    })
})

var opttype = new Array();
var amounthidden = new Array();
var sira = new Array();
var amounts = new Array();
var i=0;
var a=0;
var b=0;
var c=0;

var pername = new Array();
var peramount = new Array();
var persira = new Array();
var perhidden = new Array();
var cm=0;
var mnt=0;
var sr=0;
var hd=0;

function editcoupon(id) {
    var cpnID = id;
    $.get("include/operations.php?pg=salesOrder&process=ordereditok&operation=coupon_edit&dt=coupon",{
        cpnID: cpnID
    },function (output) {
        var coupon_data = JSON.parse(output);
        var isused = coupon_data["coupon_data"]["isused"];
        var cpnid = coupon_data["coupon_data"]["coupontype"];
        $("#form55 tr td h4").text(coupon_data["coupon_data"]["couponcode"]);
        $("#form55 tr .create-date").text(coupon_data["coupon_data"]["createdate"]);
        $("#hiddencoupon").attr("value",coupon_data["coupon_data"]["id"]);
        $("#discountrate").attr("value",coupon_data["coupon_data"]["discountrate"]);
        $("#discountmoney").attr("value",coupon_data["coupon_data"]["discountmoney"]);
        $("#datevalidedit").attr("value",coupon_data["coupon_data"]["validdate"]);
        $("#forwhom").attr("value",coupon_data["coupon_data"]["forwhom"]);
        $('#isused option[value='+isused+']').attr("selected",true);
        $('#coupontype option[value='+cpnid+']').attr("selected",true);
        $('#coupontype option[value='+cpnid+']').text();
    })
}

function edit_review(id){
    var reviewID = id;
    $.get("include/operations.php?pg=reviews&process=editreview&q=get",{
        reviewID: reviewID
    },function (output) {

        var review_data = JSON.parse(output);

        var rvid = review_data["review_data"]["productid"];
        var rat = review_data["review_data"]["rating"];
        var st = review_data["review_data"]["websiteid"];

        getProduct(st);

        $("#review_form #review_product").find("option:selected").removeProp("selected");
        $('#review_form #review_product option[value='+rvid+']').prop("selected",false);
        $("#review_form #review_rating").find("option:selected").removeProp("selected");
        $('#review_form #review_rating option[value='+rat+']').prop("selected",false);
        $("#review_form #review_website").find("option:selected").removeProp("selected");
        $('#review_form #review_website option[value='+st+']').prop("selected",false);

        $("#review_form #review_name").attr("value",review_data["review_data"]["name"]);
        $("#review_form #review_email").attr("value",review_data["review_data"]["email"]);
        $("#review_form #review_time").attr("value",review_data["review_data"]["tarih"]);
        $("#review_form #review_hidden").attr("value",reviewID);

        $('#review_form #review_product option[value='+rvid+']').prop("selected",true);

        $('#review_form #review_website option[value='+st+']').prop("selected",true);

        $('#review_form #review_rating option[value='+rat+']').prop("selected",true);

        $("#review_form #review_review").text(review_data["review_data"]["comment"]);

        var show = review_data["review_data"]["show"] == 1 ? "checked" : "";

        $("#review_show").prop("checked",show);

    })
}

function edit_question(id){
    var queID = id;
    $.get("include/operations.php?pg=questions&process=editquestion&q=get",{
        queID: queID
    },function (output) {
        var question_data = JSON.parse(output);

        var queid = question_data["question_data"]["productid"];
        var stid = question_data["question_data"]["websiteid"];

        $.post("include/operations.php?pg=products&process=product_edit&q=getProduct",{
            id: stid
        },function (output) {
            $("#question_product").html(output);
        });

        $("#question_form #question_product").find("option:selected").removeProp("selected");
        $('#question_form #question_product option[value='+queid+']').prop("selected",false);
        $("#question_form #question_website").find("option:selected").removeProp("selected");
        $('#question_form #question_website option[value='+stid+']').prop("selected",false);

        $("#question_form #question_name").attr("value",question_data["question_data"]["name"]);
        $("#question_form #question_email").attr("value",question_data["question_data"]["email"]);
        $("#question_form #question_time").attr("value",question_data["question_data"]["tarih"]);
        $("#question_form #question_hidden").attr("value",queID);

        $('#question_form #question_product option[value='+queid+']').prop("selected",true);
        //$('#question_form #question_product option[value='+queid+']').text(question_data["question_data"]["productname"]);

        $('#question_form #question_website option[value='+stid+']').prop("selected",true);
        //$('#question_form #question_website option[value='+stid+']').text(question_data["question_data"]["websitename"]);

        $("#question_form #question_question").text(question_data["question_data"]["comment"]);
        $("#question_form #question_answer").text(question_data["question_data"]["answer"]);

        var show = question_data["question_data"]["show"] == 1 ? "checked" : "";
        var show2 = question_data["question_data"]["isemailsent"] == 1 ? "checked" : "";

        $("#question_show").prop("checked",show);
        $("#question_emailsent").prop("checked",show2);

    })
}

function comboGet(type) {
    $("#comboResult"+type).html("");

    $.post("include/operations.php?pg=combos&process=comboGet",{
        type: type
    },function (output) {
        $("#comboResult"+type).html(output);
    })
}

function combos(type) {
    var menuname = $("#newcomboname"+type).val();
    if (menuname == "" || menuname == null){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else{
        $.post("include/operations.php?pg=combos&process=combos_add",{
            menuname: menuname,
            type: type
        },function (output) {
            if (output == 1){
                var status = "lime";
                var message = "Inserted";
                UINotific8("true",message,status);
                $("input[name='newcomboname']").val("");
                comboGet(type);
            }
            else if(output == 0){
                var status = "tangerine";
                var message = "Not Inserted";
                UINotific8("false",message,status);
            }
        })
    }
}

function updateCombo(id, value,column) {
    $.post("include/operations.php?pg=combos&process=comboUpdate",{
        id: id,
        value: value,
        column: column
    },function (output) {
        if (output == "true"){
            var status = "lime";
            var message = "Updated";
            UINotific8(output,message,status);
        }
        else if(output == "false"){
            var status = "tangerine";
            var message = "Not Updated";
            UINotific8(output,message,status);
        }
    })
}

function deleteCombo(id,type) {
   //$(".bootbox").css({zIndex: 15500});
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        if (result){
            $.post("include/operations.php?pg=combos&process=comboDelete",{
                id: id,
                postSecure:"idDelete"
            },function (output) {
                 if (output == 1){
                 var status = "lime";
                 var message = "Deleted";
                 UINotific8("true",message,status);
                 comboGet(type);
                 }
                 else if(output == 0){
                 var status = "tangerine";
                 var message = "Not deleted";
                 UINotific8("false",message,status);
                 }

            })
        }
    });
}

function company_select(cmpny,id) {
    var cmpnyid = cmpny;
    $.post("include/operations.php?pg=select-company",{
        cmpnyid: cmpnyid,
        id: id
    },function (result) {
        $("#addressid").html(result);
        $("#shiptoaddressid").html(result);
        location.reload();
    });
}

function addressUp(IDaddress,IDorder) {
    $.post("include/operations.php?pg=addressUp",{
        IDaddress: IDaddress,
        IDorder: IDorder
    },function () {
        location.reload();
    });
}

function ShipaddressUp(IDShipaddress,orderID) {
    $.post("include/operations.php?pg=ShipaddressUp",{
        IDShipaddress: IDShipaddress,
        orderID: orderID
    },function () {
        location.reload();
    });
}

function ShipVia(shipviaID,orderID) {
    $.post("include/operations.php?pg=ShipviaUp",{
        shipviaID: shipviaID,
        orderID: orderID
    },function () {
        location.reload();
    });
}

function comboSelect(type) {
        $.post("include/operations.php?pg=combos&process=comboSelect",{
            type: type
        },function (output) {
            $("select[name='comboSelect"+type+"']").html(output);
        })
}

function dup_product(id) {
    var pr_id = id;
    $("#dup_id").val(pr_id);
}

$("#plus_order").on("click",function () {
    var qtyorder = $("#qtyorder").val();
    var orderproductid = $("#orderproductid").val();
    var new_selectorder = $("#new_selectorder").val();
    var select_website = $("#selectWebsite").val();
    if (qtyorder == "" || orderproductid == "" || new_selectorder == "" || select_website == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);
        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
    else{
        $.post("include/operations.php?pg=salesOrder&process=enterbasket",{
            qtyorder: qtyorder,
            orderproductid: orderproductid,
            new_selectorder: new_selectorder,
            select_website: select_website
        },function (output) {
            $("#productlist").html(output);
            $("#qtyorder").val("");
            $("#orderproductid").val("");
            $("#new_selectorder").val("");
        })
    }
});

$("#selectWebsite").on("change",function () {
    var site = $(this).val();
    $.post("include/operations.php?pg=salesOrder&process=selectProduct",{
        site: site
    },function (output){
        $("#orderproductid").html(output);
    })
});

$("#addbutton").click(function () {
    var productid = [];
    productid = ($("#productid option:selected").val());
    var sizename = [];
    sizename = ($("#productid option:selected").attr("data-sizename"));
    var productname = [];
    productname = ($("#productid option:selected").attr("data-name"));
    var samplesize = [];
    samplesize = ($("#samplesize option:selected").val());
    var qty = [];
    qty = $('#qty').val();
    samplestext = qty + " - " + productname + " - " + sizename + " - " + samplesize;
    samples = qty + "|" + productid + "|" + sizename + "|" + samplesize;
    if (productid.length != 0) {
        $('#samples').append("<option value='" + samples + "'  selected>" + samplestext + "</option>");
    }
    $.post('include/operations.php?pg=samplemanagement&operation=addsample',{
        productid: productid,
        samplesize: samplesize,
        qty: qty
    },function (output) {

    })
});

$('#btn-remove').click(function () {
    var a = 0;
    var samples = new Array();
    var b;
    var c = new Array();
    $('#samples option:selected').each(function () {
        $(this).remove();
        samples[a] = $(this).val();
        b = samples[a].split("|");
        c = b[1];
        d = b[3];
        e = b[0];
        $.post('include/operations.php?pg=samplemanagement&operation=remove',{
            c: c,
            d: d,
            e: e
        },function () {

        });
        a++;
    });
});

$("#addproduct").click(function () {
    var productsizeid = [];
    productsizeid = ($("#product_sizeid option:selected").val());
    var sizename = [];
    sizename = ($("#product_sizeid option:selected").attr("data-sizename"));
    var productname = [];
    productname = ($("#product_sizeid option:selected").attr("data-tokens"));
    var serviceproductname = [];
    serviceproductname = ($("#productname").val());
    var salesinvoiceno = [];
    salesinvoiceno = ($("#salesinvoiceno").val());
    var expenseid = [];
    expenseid = ($("#expenseid option:selected").val());
    var vatid = [];
    vatid = ($("#vatid222 option:selected").val());
    var vatsiznew = [];
    vatsiznew = ($("#vatsiznew").val());
    var vatlinew = [];
    vatlinew = ($("#vatlinew").val());
    var unit = [];
    unit = ($("#unit option:selected").val());
    var qty = [];
    qty = $('#qty').val();
    samplestext = qty + " - " + productname + " - " + sizename;
    samples = qty + "|" + productsizeid + "|" + sizename;

    if (productsizeid.length != 0) {
        $('#products').append("<option value='" + samples + "'  selected>" + samplestext + "</option>");
    }
    $.post('include/operations.php?pg=goods&operation=addgoodsorder',{
        productsizeid: productsizeid,
        serviceproductname: serviceproductname,
        salesinvoiceno: salesinvoiceno,
        expenseid: expenseid,
        vatid: vatid,
        vatsiznew: vatsiznew,
        vatlinew: vatlinew,
        unit: unit,
        qty: qty
    },function (output) {
        $("#goodsid").val(output);
    })

});

$('#btn-pr-remove').click(function () {
    bootbox.confirm('Are you sure you want to delete?', function (result) {
        if (result){
            var a = 0;
            var samples = new Array();
            var b;
            var c = new Array();
            $('#products option:selected').each(function () {
                $(this).remove();
                samples[a] = $(this).val();
                b = samples[a].split("|");
                c = b[1];
                e = b[0];
                $.post('include/operations.php?pg=goods&operation=remove',{
                    c: c,
                    e: e
                },function () {

                });
                a++;
            });
        }
    });
});

function postcodeUp(pst,id3){
    $.ajax({
        url: "include/operations.php?pg=postcodes&process=postcodeUp",
        type: "POST",
        data: {
            pst: pst,
            id3: id3
        },
        success: function (output){
            location.reload(output);
        }
    });
}

function zoneUp(znid,id){
    $.ajax({
        url: "include/operations.php?pg=postcodes&process=zoneUp",
        type: "POST",
        data: {
            znid: znid,
            id: id
        },
        success: function (output){
            location.reload(output);
        }
    });
}

function zoneUp2(znid2,id2){
    $.ajax({
        url: "include/operations.php?pg=postcodes&process=zoneUp2",
        type: "POST",
        data: {
            znid2: znid2,
            id2: id2
        },
        success: function (output){
            location.reload(output);
        }
    });
}

function zonelowpriceUp(low,zoneid){
    if($.isNumeric(low)){
        $.ajax({
            url: "include/operations.php?pg=zones&process=zonelowpriceUp",
            type: "POST",
            data: {
                low: low,
                zoneid: zoneid
            },
            success: function (output){

            }
        });
    }
    else{
        var status = "ruby";
        var message = "Please Enter Valid Value";
        UINotific8("false",message,status);
    }
}

function zoneeconomypriceUp(economy,zoneid2){
    if($.isNumeric(economy)){
        $.ajax({
            url: "include/operations.php?pg=zones&process=zoneeconomypriceUp",
            type: "POST",
            data: {
                economy: economy,
                zoneid2: zoneid2
            },
            success: function (output){

            }
        });
    }
    else{
        var status = "ruby";
        var message = "Please Enter Valid Value";
        UINotific8("false",message,status);
    }
}

function zonenextdaypriceUp(nextday,zoneid3) {
    if($.isNumeric(nextday)){
        $.ajax({
            url: "include/operations.php?pg=zones&process=zonenextdaypriceUp",
            type: "POST",
            data: {
                nextday: nextday,
                zoneid3: zoneid3
            },
            success: function (output){

            }
        });
    }
    else{
        var status = "ruby";
        var message = "Please Enter Valid Value";
        UINotific8("false",message,status);
    }
}

function zoneampmpriceUp(ampm,zoneid4) {
    if($.isNumeric(ampm)){
        $.ajax({
            url: "include/operations.php?pg=zones&process=zoneampmpriceUp",
            type: "POST",
            data: {
                ampm: ampm,
                zoneid4: zoneid4
            },
            success: function (output){

            }
        });
    }
    else{
        var status = "ruby";
        var message = "Please Enter Valid Value";
        UINotific8("false",message,status);
    }
}

function zonesaturdayampriceUp(saturdayam,zoneid5) {
    if($.isNumeric(saturdayam)){
        $.ajax({
            url: "include/operations.php?pg=zones&process=zonesaturdayampriceUp",
            type: "POST",
            data: {
                saturdayam: saturdayam,
                zoneid5: zoneid5
            },
            success: function (output){

            }
        });
    }
    else{
        var status = "ruby";
        var message = "Please Enter Valid Value";
        UINotific8("false",message,status);
    }
}

function zonesaturdaypmpriceUp(saturdaypm,zoneid6) {
    if($.isNumeric(saturdaypm)){
        $.ajax({
            url: "include/operations.php?pg=zones&process=zonesaturdaypmpriceUp",
            type: "POST",
            data: {
                saturdaypm: saturdaypm,
                zoneid6: zoneid6
            },
            success: function (output){

            }
        });
    }
    else{
        var status = "ruby";
        var message = "Please Enter Valid Value";
        UINotific8("false",message,status);
    }
}

function zonecourierpriceUp(couprice,couid){
    if($.isNumeric(couprice)){
        $.ajax({
            url: "include/operations.php?pg=zones&process=courierUp",
            type: "POST",
            data: {
                couprice: couprice,
                couid: couid
            },
            success: function (output){
                location.reload(output);
            }
        });
    }
    else{
        var status = "ruby";
        var message = "Please Enter Valid Value";
        UINotific8("false",message,status);
    }
}

function zoneroyalmailUp(royalprice,royalid){
    if($.isNumeric(royalprice)){
        $.ajax({
            url: "include/operations.php?pg=zones&process=royalmailUp",
            type: "POST",
            data: {
                royalprice: royalprice,
                royalid: royalid
            },
            success: function (output){
                location.reload(output);
            }
        });
    }
    else{
        var status = "ruby";
        var message = "Please Enter Valid Value";
        UINotific8("false",message,status);
    }
}

function samplesizeup(size,id){
    $.ajax({
        url: "include/operations.php?pg=samplemanagement&operation=samplesizeup",
        type: "POST",
        data: {
            id: id,
            size: size
        },
        success: function (output){
           location.reload(output);
        }
    });
}

function removeitem(product_id,basketid){
     $.get('include/operations.php?pg=salesOrder&process=cartremove',{
         product_id: product_id,
         basketid: basketid
     },function (output) {
         if (output == 1){
             //var status = "lime";
             //var message = "Deleted";
             //UINotific8("true",message,status);
             document.getElementById(product_id).style.display = "none";
         }
         else{
             var status = "ruby";
             var message = "Not Deleted";
             UINotific8("false",message,status);
         }

     })
}

$(".order").on("change",function () {
    var order = $(this).val();
    var id = $(this).attr("id");
    $.post("include/operations.php?pg=categories&process=category_edit&q=orderUpdate",{order:order,id:id},function (output) {

    })
});
/*
$("#login").on("click",function () {
    $("#login-form").attr("action","include/operations.php?pg=login");
    $("#login-form").attr("method","post");
    var login_username = $("#login_username").val();
    var login_password = $("#login_password").val();
    $.ajax({
        url: "include/operations.php?pg=login",
        type: "POST",
        data: {
            login_username: login_username,
            login_password: login_password
        },
        success: function (output){
            location.reload();
        }
    });
});
*/
function login_user() {
    var login_username = $("#login_username").val();
    var login_password = $("#login_password").val();
    $.ajax({
        url: "include/operations.php?pg=login",
        type: "POST",
        data: {
            login_username: login_username,
            login_password: login_password
        },
        success: function (output){
            location.reload();
        }
    });
}
/*
$("#login_password").keyup(function(event) {
    if (event.keyCode === 13) {
        $("#login-form").attr("action","include/operations.php?pg=login");
        $("#login-form").attr("method","post");
        $("#login").click();
        location.reload();
    }
});
*/
function isNull() {
    var username = $("#username").val();
    var password = $("#password").val();
    var email = $("#email").val();
    var name = $("#name").val();
    var usertype = $("#usertype").val();
    if (username == "" || password == "" || email == "" || name == "" || usertype == ""){
        var settings = {
                theme: "lemon",
                sticky: "",
                horizontalEdge: "right",
                verticalEdge: "top"
            },
            $button = $(this);

        settings.heading = "<b>NULL</b>";
        settings.life = "3000";
        $.notific8('zindex', 11500);
        $.notific8("Fill in the required fields", settings);
        $button.attr('disabled', 'disabled');
        setTimeout(function () {
            $button.removeAttr('disabled');
        }, 3000);
    }
}

/*
if ($("input[name='product_show']").val() == 1){
    $("input[name='product_show']").attr("checked","checked");
}

$("input[name='product_show']").click(function () {
    if($("input[name='product_show']").val() == 1) {
        $("input[name='product_show']").val(0);
        $("input[name='product_show']").attr("checked",false);
    }
    else {
        $("input[name='product_show']").val(1);
        $("input[name='product_show']").attr("checked","checked");
    }
})

if ($("input[name='dummy']").val() == 1){
    $("input[name='dummy']").attr("checked","checked");
}

$("input[name='dummy']").click(function () {
    if($("input[name='dummy']").val() == 1) {
        $("input[name='dummy']").val(0);
        $("input[name='dummy']").attr("checked",false);
    }
    else {
        $("input[name='dummy']").val(1);
        $("input[name='dummy']").attr("checked","checked");
    }
})

if ($("input[name='new-size']").val() == 1){
    $("input[name='new-size']").attr("checked","checked");
}

$("input[name='new-size']").click(function () {
    if($("input[name='new-size']").val() == 1) {
        $("input[name='new-size']").val(0);
        $("input[name='new-size']").attr("checked",false);
    }
    else {
        $("input[name='new-size']").val(1);
        $("input[name='new-size']").attr("checked","checked");
    }
})

if ($("input[name='special']").val() == 1){
    $("input[name='special']").attr("checked","checked");
}

$("input[name='special']").click(function () {
    if($("input[name='special']").val() == 1) {
        $("input[name='special']").val(0);
        $("input[name='special']").attr("checked",false);
    }
    else {
        $("input[name='special']").val(1);
        $("input[name='special']").attr("checked","checked");
    }
})
*/



