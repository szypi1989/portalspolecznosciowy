$(document).ready(function() {  
var index;
//#KOD01 ZDARZENIE ODPOWIEDZIALNE ZA AKCEPTOWANIE ZNAJOMEGO.
$(".accept").on("click", function() {
index=$(".accept").index(this);
$.get("accept_friends.php?id_user="+encodeURIComponent($("#id_login").val())+"&string_ids="+encodeURIComponent($("#building_string_friends").val())+"&id_accept=" + encodeURIComponent(index), function(data){
if(((data.trim().substring(0,5)).toLowerCase())=="error"){
alert('błąd przy dodawaniu znajomego');   
}else{
$(".accept").eq(index).attr( "disabled", "disabled" );
}
});
});

});


