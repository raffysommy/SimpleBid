function successSet(obj){$("#load").fadeOut();
  $("#nomeoggetto").html(obj.objname);
  $("#descrizione").html(obj.objdescr);
  $("#bid").html(obj.objbid);
  $("#immogg").attr("src","img/"+obj.objimg);
  if(obj.email===null){
      $("#bidder").html("Email: N/A");
  }else{
      $("#bidder").html("Email: " + obj.email);
  }
}
function errorSet(){
  $("#load").fadeOut();
  $("#nomeoggetto").html("Oggetto non disponibile");
  $("#bid").html("N/A");
  $("#bidder").html("N/A");
}
$(document).ready(function(){
    $.ajax({
        url:"ajaxasta.php",
        type: "GET",
        success:function(result){
          try {
                if(result==="<h1>Errore nella Connessione al database. Riprova più tardi</h1>"||result.length===0||result.id===null){
                  errorSet();
                  console.error("Dato nullo");
                }else{
                  successSet(result);
                }
          } catch (e) {
                console.error("Errore nel parsing del JSON");
                errorSet();
          }
        },
        error: function(){
                console.error("Richiesta Ajax non riuscita");
        }
    });
});
