var usersel=$("#username");
var passsel=$("#password");
var typeTimeout = null;
$("document").ready(function(){
    usersel.on("change keyup input",function() {
        checkEmail();
    });

    usersel.keyup(function() {
            if (typeTimeout !== null) {
                clearTimeout(typeTimeout);
            }
            typeTimeout = setTimeout(function() {
                typeTimeout = null;
                if(checkEmail()) {
                    duplicateExist(usersel.val());
                }
            }, 500);
    });
    passsel.on("change keyup input",function() {
        checkPassword();
    });
});
function checkValue(){
    $("#invusername").hide();//reset status
    $("#invpassword").hide();
    $("#dupusername").hide();
    var mailcheck=checkEmail();
    var passcheck=checkPassword();
     if(!mailcheck){
         showMessage("invaliduser");
     }
     if(!passcheck){
         showMessage("invalidpass");
     }
     return mailcheck&&passcheck;
}
function duplicateExist(user) {
    $.ajax({
        url:"duplicatesearch.php",
        type: "GET",
        data: {
            "user":user
        },
        success:function(result){
            if(result.ispresent===true){
                $("#dupusername").show();
            }else{
                $("#dupusername").hide();
            }
        },
        error: function(){
            console.error("Richiesta Ajax non riuscita");
            return dup;
        }

    });
}
function checkEmail(){
    var email=usersel.val();
    var regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var res=regex.test(email);
    if(res===true){
      usersel.css("color", "green");
    }else{
      usersel.css("color", "red");
    }
    return res;
}
function checkPassword(){
  var password=passsel.val();
  var regex = /^(?:[0-9]+[a-zA-Z]|[a-zA-Z]+[0-9])[A-Za-z0-9]*$/;
  var res=regex.test(password);
  if(res===true){
    passsel.css("color", "green");
  }else{
    passsel.css("color", "red");
  }
  return res;
}
