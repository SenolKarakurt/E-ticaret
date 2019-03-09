/*
jQuery(document).ready(function(){

    var b = $('#totalcost').val();

    $('#add1').click(function(e){
        var a = $('#qty').val();
        a++;
        $('#qty').val(a);

        var sonuc = a * b;
        var sayi = math.round(sonuc,2);
        $('#totalcost').val(sayi);


    });

    $("#minus1").click(function(e) {

        var c = $('#qty').val();
        c--;
        if(c<1){
            return false;
        }

        $('#qty').val(c);

        var sonuc2 = c * b;
        var sayi2 = math.round(sonuc2,2);
        $('#totalcost').val(sayi2);

    });

});

$(function(){$("#slider6").noUiSlider('init', { scale: [0,200], tracker:
    function(){
        low=$('#slider6').noUiSlider('getValue')[0]
        document.searchform.lowForS6.value=parseInt(low)
        high=$('#slider6').noUiSlider('getValue', {point: 'upper'});
        document.searchform.highForS6.value=parseInt(high)}
});
});
*/

