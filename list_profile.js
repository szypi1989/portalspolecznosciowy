$(document).ready(function() {  
//#KOD01 ZDARZENIE ODPOWIEDZIALNE ZA USUWANIE UŻYTKOWNIKA. 
$(".delete_friend").on("click", function() {
index=$(".delete_friend").index(this);
$.get("admin_ajax.php?field=delete_user&user_id="+encodeURIComponent(index)+"&string_ids=" + encodeURIComponent($("#building_string_friends").val()), function(data){
if(((data.trim().substring(0,5)).toLowerCase())=="error"){
alert('błąd przy usuwaniu użytkownika');   
}else{
alert('Usunięto użytkownika');     
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

$("input[name='allow_write_msg']").on('change', function()            
{
index=$("input[name='allow_write_msg']").index(this);    
value=(($(this).is(':checked'))?'1':"0");
$.get("admin_ajax.php?field=change_allow_write_msg&value="+value+"&user_id="+encodeURIComponent(index)+"&string_ids=" + encodeURIComponent($("#building_string_friends").val()), function(data){
if(((data.trim().substring(0,5)).toLowerCase())=="error"){
alert('błąd przy zmiany praw dostępu do pisania wiadomości');   
}else{
alert('Zmieniono prawa dostępu do pisania wiadomości');
};
})    
});



});


