
var tt = $(".vert-align input[type=radio]:checked").data("price");
var total = $("#totalcost").val();
var qty = 1;

$(".vert-align input[type=radio]").click(function () {
    tt = $(this).data("price");
    qty = $("#qty").val();
    total = qty * tt;
    $('#totalcost').val(total.toFixed(2));
});
/*
var data;
var min = $("#sizem1").val();
$(".vert-align input[type=radio]").each(function(){
    data = $(this).data("price");
    if (data != 0){
        if (data < min){
            min = data;
        }
    }
    tt = min;
    $('#totalcost').val(tt);
});
*/
var ty;
var tpr;
$("#qty").change(function(){
    ty = $(this).val();
    total = ty * tt;
    tpr = total.toFixed(2);
    $('#totalcost').val(tpr);
});

var a;
var b = $('#totalcost').val();
var c;
var d;
var sayi;
function plusCost() {
    a = $('#qty').val();
    a++;
    $('#qty').val(a);
    total = a * tt;
    sayi = total.toFixed(2);
    $('#totalcost').val(sayi);
}

var sonuc2;
var sayi2;
function subCost() {
    c = $('#qty').val();
    c--;
    if(c<1){
        return false;
    }
    $('#qty').val(c);
    sonuc2 = c * tt;
    //sayi2 = math.round(sonuc2,2);
    $('#totalcost').val(sonuc2.toFixed(2));
}

function SureFunction() {
    var agree = confirm('Silme İşleminden Emin misiniz?');
    if(agree == true) {
        return true;
    }
    else {
        return false;
    }
}

$("#user_add").on("click", function () {
    var gonderilenform = $("#myform").serialize();
    $.post('include/operations.php',{gonderilenform:gonderilenform},function(output){
        alert(output);
    })
});

$('#myform2').on('change', 'select', function (e) {
    var siteID = $(e.target).val();
    var text = $(e.target).find("option:selected").text();
    var name = $(e.target).attr('name');
    //$('p').text("Changed: name="+name+" to val="+siteID+" and text="+text);
    $.post('../include/operations.php',{siteID: siteID},function(){
        window.location = "index.php";
    })
});

/*
// İlk olarak  style="display:none" yapılan div i görüntüleme fonksiyonu....
function show(div) {
    $("#"+div).show();
    $("#"+div).css("display","block");
}
*/

$("#revtoggle").on("click",function () {
    $("#rev").toggle();
});
$(".revtoggle").on("click",function () {
    $("#rev").toggle();
});
$("#quetoggle").on("click",function () {
    $("#que").toggle();
});
$(".quetoggle").on("click",function () {
    $("#que").toggle();
});

function updateqty(id){
    var qty2 = $("#qty2"+id+"").val();
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
}

$("#slider-range").slider({
    range: true,
    min: 0,
    max: 200,
    values: [ 0, 200 ],
    slide: function( event, ui ) {
        $("#lowprice").val(ui.values[ 0 ]);
        $("#highprice").val(ui.values[ 1 ]);
    }
});

$(document).ready(function(){
    var search = $("#search");
    search.autocomplete({
        source: 'panel/include/api.php'
    });
    search.data("ui-autocomplete")._renderItem = function (ul,item){
        var li = $('<li>');
        li.html('<a href="index.php?p=product_details&prid='+item.pr_id+'&imgid='+item.imageid+'">'+item.name+'</a>');
        return li.appendTo(ul);
    }
});



