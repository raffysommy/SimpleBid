function checkOffer() {
    $("#invalidbid").hide();
    $("#bidmin").hide();
    $thr_i=parseFloat($("#thr").val().replace(',', '.'));
    if(isNaN($thr_i)){
        $("#invalidbid").show();
        return false;
    }else{
        if(checkBid($thr_i)) {
            return true;
        }else{
            $("#bidmin").show();
            return false;
        }
    }
}
function checkBid($thr_i) {
    $bid=parseFloat($("#bid").html());
    return $thr_i>$bid;
}
