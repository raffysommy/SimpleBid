function showMessage(type) {
    if(type==="invaliduser"){
        $("#invusername").show();
    }else if(type==="dupuser"){
        $("#dupusername").show();
    }else if(type==="invalidpass"){
        $("#invpassword").show();
    }
}