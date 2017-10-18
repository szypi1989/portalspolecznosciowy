$(document).ready(function() {  
var switch_employee_but=false;
var height_view_old_employee;
var timeout;
var switch_school_but=false;
var height_view_old_school;
var active_submit=false;
var loaded_avatar_mp=false;
var i=0;
var loaded_avatar_ml=false;
if ( $.browser.msie ) {
$('#gallery_option').css("width","140px");
$('#gallery_option').css("height","160px");
}
//wrzucenie wartości wielkości elementów listy szkół i pracodawców
height_view_old_employee=$('#view_old_employee').css("height");
height_view_old_school=$('#view_old_school').css("height");
$('#view_old_employee').css("height","0px");
// #KOD01 KOD ODPOWIEDZIALNY ZA ODKRYCIE LISTY STARSZYCH PRACODAWCÓW. 
$("#view_old_employee_but").on("click", function() {
if(!$('#view_old_employee').text()==""){
switch_employee_but=!switch_employee_but;
if(switch_employee_but){
$('#view_old_employee').css("visibility","visible");
$("#view_old_employee" ).animate({
    height: height_view_old_employee
  }, 800, function() {
  });
}else{
$("#view_old_employee" ).animate({
    height: "0px"
  }, 300, function() {
  $('#view_old_employee').css("visibility","hidden");
  });
}
}
});
$('#view_old_school').css("height","0px");
// #KOD02 kod odpowiedzialny za odkrycie listy starszych uczelni
$("#view_old_school_but").on("click", function() {
if(!$('#view_old_school').text()==""){
switch_school_but=!switch_school_but;
if(switch_school_but){
$('#view_old_school').css("visibility","visible");
$("#view_old_school" ).animate({
    height: height_view_old_school
  }, 800, function() {
  });
}else{
$("#view_old_school" ).animate({
    height: "0px"
  }, 300, function() {
  $('#view_old_school').css("visibility","hidden");
  });
}
}
});
// #KOD03 kod odpowiedzialny za zmianę głównej fotografii
$('#plik').on('change', function()            
{
loaded_avatar_mp=false;
loaded_avatar_ml=false;  
        $("#image_upload").ajaxForm({ 
               url:'upload_file.php',
               type:'post',
               beforeSend: function() {
               $(".avatar_link").html( "sprawdzanie" );
               $("#upload_link").css("visibility","hidden");
                     $("#loading_image").css("opacity","1");
                     margin_left=(110-$(".avatar_link").width())/2;
                    margin_top=(130-$(".avatar_link").height())/2;
                    $("#loading_image div").css({
                        "width":"auto",
                        "height":"auto",
                        "left":margin_left+"px",
                        "top": margin_top+"px",
                        "position":"relative",
                        "float":"left"
                    });
               },
               uploadProgress: function(e, position, total, percent){
                $(".avatar_link").html( "wgrywanie fotografi:"+percent +"%");
                },
               success: function(response) {
                    if(response!="1"){
                    $(".avatar_link").html( response );
                    margin_left=(110-$(".avatar_link").width())/2;
                    margin_top=(130-$(".avatar_link").height())/2;
                    $("#loading_image div").css({
                        "width":"auto",
                        "height":"auto",
                        "left":margin_left+"px",
                        "top": margin_top+"px",
                        "position":"relative",
                        "float":"left"
                    });
                    $("#loading_image" ).animate({
                    opacity: 0
                    }, 3000, function() {
                    $("#upload_link").css("visibility","visible");
                    });
                    $('#plik').val("");
                    }else{
                    $(".avatar_link").html( "wgrano zdjęcie/proszę chwile czekać" );
                    margin_left=(110-$(".avatar_link").width())/2;
                    margin_top=(130-$(".avatar_link").height())/2;
                    $("#loading_image div").css({
                        "width":"auto",
                        "height":"auto",
                        "left":margin_left+"px",
                        "top": margin_top+"px",
                        "position":"relative",
                        "float":"left"
                    });
                    }
                    complete_loaded_avatar();
                    
              }
              }).submit();
}); 
// #KOD04 kod odpowiedzialny za odświerzenie fotografii
function complete_loaded_avatar() {
$("#loading_image").css("opacity","0");
$("#upload_link").css("visibility","visible");
$("#avatar img").attr('src', "gallery/"+$("#id_profile").attr("value")+".jpg?"+new Date().getTime());
$("img.content_profile").attr('src',  "gallery/"+$("#id_profile").attr("value")+".jpg?"+new Date().getTime());
}
// #KOD05 kod odpowiedzialny za wysyłanie zaproszenia do użytkownika
$("#send_invitation").not('.invitation_sended').on("click", function() {
$.get("send_invitation.php?id_friends="+encodeURIComponent($("#id_profile").val())+"&id_user="+encodeURIComponent($("#id_login").val()), function(data){
if(((data.trim().substring(0,5)).toLowerCase())=="error"){
alert('błąd przy wysyłaniu zaproszenia');   
}else{
$("#send_invitation").addClass("invitation_sended" );   
$("#send_invitation").attr( "disabled", "disabled" );
}
});    
});
// #KOD06 KOD ODPOWIEDZIALNY ZA PRZEKIEROWANIE UŻYTKOWNIKA DO WIDOKU MODUŁU KOMUNIKACJI UŻYTKOWNIKA, NA KTÓRYM AKTUALNIE SIĘ ZNAJDUJE W GŁÓWNYM MODULE.  
$("#send_message").on("click", function() {
location.href="message_profile.php?"+encodeURIComponent($("#user_href").html().split("?")[1])+"&id_talk="+$("#id_profile").val();
});    
});


