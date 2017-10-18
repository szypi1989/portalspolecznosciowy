$(document).ready(function() {  
var index;
//#KOD01 ZDARZENIE ODPOWIEDZIALNE ZA USUWANIE ZNAJOMEGO. 
$(".delete_friend").on("click", function() {
index=$(".delete_friend").index(this);
$.get("delete_friends.php?delete_id="+encodeURIComponent(index)+"&id_user="+encodeURIComponent($("#id_profile").val())+"&string_ids=" + encodeURIComponent($("#building_string_friends").val()), function(data){
if(((data.trim().substring(0,5)).toLowerCase())=="error"){
alert('błąd przy usuwaniu znajomego');   
}else{
alert('Usunięto znajomego');  
$("#building_string_friends").val(data.trim());
$(".cont_friends" ).eq(index).animate({
opacity: 0
}, 700, function() {
$(".cont_friends" ).eq(index).remove();
if(($("#num_rows_friends" ).text().trim)()=="1"){ 
$('#friends').append(
$('<div id="view_empty_friends"><span>osoba nie posiada znajomych</span></div>', {
})); 
}
$("#num_rows_friends").html((parseInt($("#num_rows_friends").html().trim())-1));
});    
};
})    
});

});


