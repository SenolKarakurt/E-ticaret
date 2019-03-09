<?php
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

}
?>