
$('#myform').on('change', 'select', function (e) {
    var siteID = $(e.target).val();
    $.post('include/operations.php?pg=websites',{siteID: siteID},function(){
        window.location = "index.php";
    })
});

$('#myform2').on('change', 'select', function (e) {
    var siteID = $(e.target).val();
    window.location.href = "index.php?p=settings&site="+siteID;
});

$('#myform3').on('change', 'select', function (e) {
    var siteID3 = $(e.target).val();
    window.location.href = "index.php?p=categories&site="+siteID3;
});

$(document).ready(function () {
    $.removeCookie("testcookie",{path:'/'});
});

function panelqty(id) {
    var qtyval = $("#qty"+id+"").val();
    if($.isNumeric(qtyval)){
        $.post("include/operations.php?pg=salesOrder&process=qtyupdate", {
            qtyval: qtyval,
            id: id
        }, function (output) {
            location.reload();
        })
    }
}

function panelvat(id) {
    var vatval = $("#vatid"+id+"").val();
    if($.isNumeric(vatval)){
        $.post("include/operations.php?pg=salesOrder&process=vatupdate", {
            vatval: vatval,
            id: id
        }, function (output) {
            location.reload();
        })
    }
}

function vatprice(id) {
    var x = document.getElementById("price"+id);
    var y = document.getElementById("vatsiz"+id);
    var vatid2 = $("#vatid"+id+"").find("option:selected").data("vat");
    if (vatid2 == "" || vatid2 == undefined || vatid2 == null){
        alert("Please Select VAT");
    }
    else{
        var ss = Math.round((100 * x.value) / (Math.round(vatid2 * 100) / 100 + 100) * 100) / 100;
    }
    var vatpr = x.value;
    $.post("include/operations.php?pg=salesOrder&process=vatpriceup", {
        vatpr: vatpr,
        id: id
    }, function (output) {

    })
    y.value = ss.toFixed(1);
}

function price(id) {
    var x = document.getElementById("vatsiz"+id);
    var y = document.getElementById("price"+id);
    var vatid2 = $("#vatid"+id+"").find("option:selected").data("vat");
    if (vatid2 == "" || vatid2 == undefined || vatid2 == null){
        alert("Please Select VAT");
    }
    else{
        var vatsiz = Math.round((Math.round(x.value * 100) / 100 + Math.round((x.value * vatid2 / 100) * 100) / 100) * 100) / 100;
    }
    $.post("include/operations.php?pg=salesOrder&process=vatsizup", {
        vatsiz: vatsiz,
        id: id
    }, function (output) {

    })
    y.value = vatsiz.toFixed(1);
}

function vatsiz22(){
    var x = document.getElementById("vatsiznew");
    var y = document.getElementById("vatlinew");
    var vatid2 = $("#vatid222").find("option:selected").data("vat");
    if (vatid2 == "" || vatid2 == undefined || vatid2 == null){
        alert("Please Select VAT");
    }
    else{
        var ss = Math.round((Math.round(x.value * 100) / 100 + Math.round((x.value * vatid2 / 100) * 100) / 100) * 100) / 100;
        y.value = ss.toFixed(1);
    }
}

function vatli22(){
    var x = document.getElementById("vatlinew");
    var y = document.getElementById("vatsiznew");
    var vatid2 = $("#vatid222").find("option:selected").data("vat");
    if (vatid2 == "" || vatid2 == undefined || vatid2 == null){
        alert("Please Select VAT");
    }
    else{
        var ss = Math.round((100 * x.value) / (Math.round(vatid2 * 100) / 100 + 100) * 100) / 100;
        y.value = ss.toFixed(1);
    }
}

function panelpurchaseqty(id) {
    var qtyval = $("#qty"+id+"").val();
    if($.isNumeric(qtyval)){
        $.post("include/operations.php?pg=purchase&operation=qtyupdate", {
            qtyval: qtyval,
            id: id
        }, function (output) {
            location.reload();
        })
    }
}

function purchasesizeupdate(id){
    var size = $("#product_sizeid"+id+"").val();
    $.post("include/operations.php?pg=purchase&operation=sizeupdate", {
        size: size,
        id: id
    }, function (output) {
        location.reload();
    })
}

function purchaseunitupdate(id){
    var unit = $("#unit"+id+"").val();
    $.post("include/operations.php?pg=purchase&operation=unitupdate", {
        unit: unit,
        id: id
    }, function (output) {
        location.reload();
    })
}

function purchasenameupdate(id){
    var name = $("#productname"+id+"").val();
    $.post("include/operations.php?pg=purchase&operation=nameupdate", {
        name: name,
        id: id
    }, function (output) {
        location.reload();
    })
}

function purchasesalesupdate(id){
    var sales = $("#salesinvoiceno"+id+"").val();
    if($.isNumeric(sales)){
        $.post("include/operations.php?pg=purchase&operation=salesinvoiceupdate", {
            sales: sales,
            id: id
        }, function (output) {
            location.reload();
        })
    }
    if (sales == ""){
        $.post("include/operations.php?pg=purchase&operation=salesinvoiceupdate", {
            sales: sales,
            id: id
        }, function (output) {
            location.reload();
        })
    }
}

function purchaseexpenseupdate(id){
    var expense = $("#expenseid"+id+"").val();
    $.post("include/operations.php?pg=purchase&operation=expenseupdate", {
        expense: expense,
        id: id
    }, function (output) {
        location.reload();
    })
}

function purchasevatupdate(id){
    var vatid = $("#vatid"+id+"").val();
    $.post("include/operations.php?pg=purchase&operation=vatupdate", {
        vatid: vatid,
        id: id
    }, function (output) {
        location.reload();
    })
}

function purchasevatsizupdate(id){
    var x = document.getElementById("vatsiz"+id);
    var y = document.getElementById("price"+id);
    var vatid2 = $("#vatid"+id+"").find("option:selected").data("vat");
    if (vatid2 == "" || vatid2 == undefined || vatid2 == null){
        alert("Please Select VAT");
    }
    else{
        var ss = Math.round((Math.round(x.value * 100) / 100 + Math.round((x.value * vatid2 / 100) * 100) / 100) * 100) / 100;
    }
    $.post("include/operations.php?pg=purchase&operation=priceupdate", {
        ss: ss,
        id: id
    }, function (output) {

    })
    y.value = ss.toFixed(1);
}

function purchasevatpriceupdate(id){
    var x = document.getElementById("price"+id);
    var y = document.getElementById("vatsiz"+id);
    var vatid2 = $("#vatid"+id+"").find("option:selected").data("vat");
    if (vatid2 == "" || vatid2 == undefined || vatid2 == null){
        alert("Please Select VAT");
    }
    else{
        var ss = Math.round((100 * x.value) / (Math.round(vatid2 * 100) / 100 + 100) * 100) / 100;
    }
    var ks = x.value;
    $.post("include/operations.php?pg=purchase&operation=vatpriceupdate", {
        ks: ks,
        id: id
    }, function (output) {

    })
    y.value = ss.toFixed(1);
}

function panelbillqty(id){
    var qtyval = $("#qty"+id+"").val();
    if($.isNumeric(qtyval)){
        $.post("include/operations.php?pg=bill&operation=qtyupdate", {
            qtyval: qtyval,
            id: id
        }, function (output) {
            location.reload();
        })
    }
}

function billsizeupdate(id){
    var size = $("#product_sizeid"+id+"").val();
    $.post("include/operations.php?pg=bill&operation=sizeupdate", {
        size: size,
        id: id
    }, function (output) {
        location.reload();
    })
}

function billunitupdate(id){
    var unit = $("#unit"+id+"").val();
    $.post("include/operations.php?pg=bill&operation=unitupdate", {
        unit: unit,
        id: id
    }, function (output) {
        location.reload();
    })
}

function billnameupdate(id){
    var name = $("#productname"+id+"").val();
    $.post("include/operations.php?pg=bill&operation=nameupdate", {
        name: name,
        id: id
    }, function (output) {
        location.reload();
    })
}

function billsalesupdate(id){
    var sales = $("#salesinvoiceno"+id+"").val();
    if($.isNumeric(sales)){
        $.post("include/operations.php?pg=bill&operation=salesinvoiceupdate", {
            sales: sales,
            id: id
        }, function (output) {
            location.reload();
        })
    }
    if (sales == ""){
        $.post("include/operations.php?pg=bill&operation=salesinvoiceupdate", {
            sales: sales,
            id: id
        }, function (output) {
            location.reload();
        })
    }
}

function billexpenseupdate(id){
    var expense = $("#expenseid"+id+"").val();
    $.post("include/operations.php?pg=bill&operation=expenseupdate", {
        expense: expense,
        id: id
    }, function (output) {
        location.reload();
    })
}

function billvatupdate(id){
    var vatid = $("#vatid"+id+"").val();
    $.post("include/operations.php?pg=bill&operation=vatupdate", {
        vatid: vatid,
        id: id
    }, function (output) {
        location.reload();
    })
}

function billvatsizupdate(id){
    var x = document.getElementById("vatsiz"+id);
    var y = document.getElementById("price"+id);
    var vatid2 = $("#vatid"+id+"").find("option:selected").data("vat");
    if (vatid2 == "" || vatid2 == undefined || vatid2 == null){
        alert("Please Select VAT");
    }
    else{
        var ss = Math.round((Math.round(x.value * 100) / 100 + Math.round((x.value * vatid2 / 100) * 100) / 100) * 100) / 100;
    }
    $.post("include/operations.php?pg=bill&operation=priceupdate", {
        ss: ss,
        id: id
    }, function (output) {

    })
    y.value = ss.toFixed(1);
}

function billvatpriceupdate(id){
    var x = document.getElementById("price"+id);
    var y = document.getElementById("vatsiz"+id);
    var vatid2 = $("#vatid"+id+"").find("option:selected").data("vat");
    if (vatid2 == "" || vatid2 == undefined || vatid2 == null){
        alert("Please Select VAT");
    }
    else{
        var ss = Math.round((100 * x.value) / (Math.round(vatid2 * 100) / 100 + 100) * 100) / 100;
    }
    var ks = x.value;
    $.post("include/operations.php?pg=bill&operation=vatpriceupdate", {
        ks: ks,
        id: id
    }, function (output) {

    })
    y.value = ss.toFixed(1);
}

$('input:radio[name="select"]').on("click",function(){
    var type = $(this).val();
    $.post("include/operations.php?pg=banking&operation=getexpense", {
        type: type
    }, function (output) {
        if (type == "income"){
            $("#bnktype").text("Income Type");
            $("#incomeorex").attr("href","#new"+type+"");
        }
        else if (type == "expense"){
            $("#bnktype").text("Expense Type");
            $("#incomeorex").attr("href","#new"+type+"");
        }
        $("#expenseid").html(output);
    })
});

function updateqty(id){
    var qty2 = $("#qty2"+id+"").val();
    //alert(qty2);
    /*
    if($.isNumeric(qty2)){
        $.post("operations.php?pg=updatecost",{
            qty2: qty2,
            id: id
        },function (output){
            if (output == "true"){
                window.location.reload(output);
            }
            else {
                window.location.reload(output);
            }
        })
    }
    */
}






