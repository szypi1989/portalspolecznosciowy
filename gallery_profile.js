$(document).ready(function() {  
var position=0;
var name_file;
var img;
var array_get_image;
var screenImage;
var theImage;
var index_img;
var imageWidth;
var imageHeight;
var is_process_load=false;
var number_img;
var switch_option_gallery=true;
var blocked_again_click=true;
var time_out_al;
var delete_comment;
var append_comment;
var left_sign_cm=200;
var array_vars_al = new Array();
function init() {
if ( $.browser.msie ) {
$('.cmt_cont_context').css("height","40px");
}    
//#KOD01 DODAWANIE FOTOGRAFII. 
$('#plik').on('change', function()            
{   
        $("#image_upload").ajaxForm({ 
               url:'upload_file_gallery.php',
               type:'post',
               beforeSend: function() {
               $('#cloud_check_upload_file').css("opacity","1");
               $("#cloud_check_upload_file" ).html("sprawdzanie poprawności pliku");
               position = $('#cloud_check_upload_file').position();
                $('#cloud_check_upload_file').css("left",(position.left-($('#cloud_check_upload_file').width()/2))+"px");
                $('#cloud_check_upload_file').css("visibility","visible");
                $("#cloud_check_upload_file" ).animate({
                opacity: 1
                }, 400, function() {
                });
               },
                uploadProgress: function(e, position, total, percent){
                $('#cloud_check_upload_file').css("left","50%");
                $("#cloud_check_upload_file" ).html("plik poprawny,wgrywanie pliku :"+percent +"%");
                position = $('#cloud_check_upload_file').position();
                $('#cloud_check_upload_file').css("left",(position.left-($('#cloud_check_upload_file').width()/2))+"px");
                },
               success: function(data) {
                if(((data.trim().substring(0,5)).toLowerCase())=="error"){
                $("#cloud_check_upload_file" ).html(data);
                $('#cloud_check_upload_file').css("left","50%");
                position = $('#cloud_check_upload_file').position();
                $('#cloud_check_upload_file').css("left",(position.left-($('#cloud_check_upload_file').width()/2))+"px");  
                $("#cloud_check_upload_file" ).animate({
                opacity: 0
                }, 1700, function() {
                $('#cloud_check_upload_file').css("visibility","hidden");
                $('#cloud_check_upload_file').css("left","50%");
                position = $('#cloud_check_upload_file').position();
                $('#cloud_check_upload_file').css("left",(position.left-($('#cloud_check_upload_file').width()/2))+"px");  
                });
                }else{
                if(($("#num_rows_gallery" ).text().trim)()=="0"){
                $("#view_empty_gallery").remove();   
                }
                $("#num_rows_gallery").html((parseInt($("#num_rows_gallery").html().trim())+1));
                $('#gallery').append(
                $('<div class="cont_photo"><div class="photo_options_cont"><div class="photo_options"><span>Usuń</span></div></div><img src="gallery/'+$("#form_id_login").val()+'/'+data.trim()+'.jpg?'+new Date().getTime()+'" height="130" width="110"><div class="photo_options_but"><span>Opcje</span></div></div>', {
                })); 
                $("#cloud_check_upload_file" ).html("Plik został zapisany");
                $('#cloud_check_upload_file').css("left","50%");
                position = $('#cloud_check_upload_file').position();
                $('#cloud_check_upload_file').css("left",(position.left-($('#cloud_check_upload_file').width()/2))+"px");  
                $('.cont_photo').last().css("opacity","0");
                 $("#cloud_check_upload_file" ).animate({
                opacity: 0
                }, 400, function() {
                $(".cont_photo" ).last().animate({
                opacity: 1
                }, 700, function() {
                $('#cloud_check_upload_file').css("visibility","hidden");
                $('#cloud_check_upload_file').css("left","50%");
                $('*').unbind();
              //  $('*').unbind(get_big_image);
                init_clicked();
                $('#plik').val("");
                });
                //
                });
                };
              }
              }).submit();
}); 
//#KOD02 ZDARZENIE ODPOWIEDZIALNE ZA USUWANIE FOTOGRAFII. 
$(".photo_options span").on("click", function() {
if(blocked_again_click){
blocked_again_click=false;
index=$(".photo_options span").index(this);
$.get("delete_file_gallery.php?id_user="+encodeURIComponent($("#form_id_login").val())+"&id_foto=" + encodeURIComponent(index), function(data){
if(((data.trim().substring(0,5)).toLowerCase())=="error"){
alert(data);   
}else{
alert(data); 
if(($("#num_rows_gallery" ).text().trim)()=="1"){ 
$('#gallery').append(
$('<div id="view_empty_gallery"><span>osoba nie posiada zdjęć</span></div>', {
})); 
}
$("#num_rows_gallery").html((parseInt($("#num_rows_gallery").html().trim())-1));
$(".cont_photo" ).eq(index).animate({
opacity: 0
}, 700, function() {
$( ".cont_photo" ).eq(index).remove();
blocked_again_click=true;
});
}
});
};
});
//#KOD03 ZDARZENIE ODPOWIEDZIALNE ZA ODKRYCIE OPCJI FOTOGRAFII. 
    $(".photo_options_but").on("click", function() {
    $('.photo_options').children('span').css("text-decoration","none");
    if(switch_option_gallery){
    $('.photo_options').eq($(".photo_options_but").index(this)).css("visibility","visible");
    $(".photo_options" ).eq($(".photo_options_but").index(this)).animate({
    opacity: 0.7
    }, 400, function() {
    switch_option_gallery=false;
    });
    }else{
    $(".photo_options" ).eq($(".photo_options_but").index(this)).animate({
    opacity: 0
    }, 100, function() {
    switch_option_gallery=true;
    $('.photo_options').eq($(".photo_options_but").index(this)).css("visibility","hidden");
    });
    }
    });
//#KOD04
    $(".cont_photo").on("hover", 
    function(e) {
    }, function() {
    $('.photo_options').css("visibility","hidden");
    $('.photo_options').css("opacity","0");
    $('.photo_options').children('span').css("text-decoration","none");
    switch_option_gallery=true;
    }
    );

    $(".photo_options_but").on("hover", 
    function(e) {
    $('.photo_options').children('span').css("text-decoration","none");
    }
    );
    $( ".photo_options span" ).on("hover",
    function() {
    $('.photo_options').children('span').css("text-decoration","none");
    },function() {
    $('.photo_options').eq($(".photo_options span").index(this)).children('span').css("text-decoration","underline");
    }
    );          
//#KOD05 ZDARZENIE ODPOWIEDZIALNE ZA URUCHAMIANIE FOTOGRAFII W POWIĘKSZONEJ GALERII ZDJĘĆ(ODKRYCIE WIDOKU GALLERY_VIEW). 
$(".cont_photo img").on("click", function() {
is_process_load=true;
$("#loading_sign").css("left",(($("#gallery_view_cont").width()/2)-($("#loading_sign").width()/2))+"px");
$("#loading_sign").css("top",((($("#gallery_view_cont").height()/2)-($("#loading_sign").height()/2)))+"px"); 
$("#gallery_img_cont").css("visibility","hidden");
$(".arrow").css("visibility","hidden");
index_img=$(".cont_photo img").index(this);
$('#gallery_bg').css("visibility","visible");
$("#gallery_bg").css({
"width":"0px",
"height":"0px",
"margin-left":"50%",
"top":"50%"
});
 $("#gallery_bg" ).animate({
"width": "150%",
"height": "100%",
"margin-left":"-50%",
"top":"0px"
}, 700, function() {
$("#loading_sign").css("visibility","visible");
$("#gallery_view_cont").css("visibility","visible");
      $.get("get_info_image.php?id_user="+encodeURIComponent($("#form_id_profile").val())+"&id_foto=" + encodeURIComponent(index_img), function(data){
      array_get_image=data.split(';');                           
        name_file=$.trim(array_get_image[0]);
            img =  $('#gallery_img_cont img#big_img').eq(0).attr('src',"gallery/"+$("#form_id_profile").val()+"/"+name_file+"?"+new Date().getTime())
            .load(function() {
            $("#loading_sign").css("visibility","hidden");
            screenImage = $('#gallery_img_cont img#big_img');
            theImage = new Image();
            theImage.src = screenImage.attr("src");
            imageWidth = theImage.width;
            imageHeight = theImage.height;
              if(imageWidth>1000 || imageHeight>500){
                if(imageWidth>imageHeight){
                i=imageWidth/imageHeight;
                j=1000/i;
                i=1000;
                if(j>500){
                j=j/500;
                i=1000/j;
                j=500;
                }
                $('#gallery_img_cont img#big_img').attr('width',i);
                $('#gallery_img_cont img#big_img').attr('height',j); 
                $("#gallery_img_cont").css("width",i+"px");
                $("#gallery_img_cont").css("height",j+"px");
                  $("#gallery_img_cont").css("top",(($("#gallery_data").height()-j)/2)+"px");
                  $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-i)/2)+"px");
                  $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px"); 
                }else{
                i=imageHeight/imageWidth;
                j=500/i;
                i=500;
                if(j>1000){
                j=j/1000;
                i=500/j;
                j=1000;
                }
                $('#gallery_img_cont img#big_img').attr('width',j);
                $('#gallery_img_cont img#big_img').attr('height',i);
                $("#gallery_img_cont").css("width",j+"px");
                $("#gallery_img_cont").css("height",i+"px");
                $("#gallery_img_cont").css("top",(($("#gallery_data").height()-i)/2)+"px");
                $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-j)/2)+"px");
                $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px"); 
                }    
              }else if(imageWidth<=600 || imageHeight<=400){
              //solution compresing resolution image
              if(imageWidth<imageHeight){
              i=imageHeight/imageWidth;
              j=400/i;
                $('#gallery_img_cont img#big_img').attr('width',j);
                $('#gallery_img_cont img#big_img').attr('height',400);
                $("#gallery_img_cont").css("width",j+"px");
                $("#gallery_img_cont").css("height","400px");
                $("#gallery_img_cont").css("top",(($("#gallery_data").height()-400)/2)+"px");
                $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-j)/2)+"px");
                $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px"); 
              }else if(imageWidth==imageHeight){
             $('#gallery_img_cont img#big_img').attr('width',400);
                $('#gallery_img_cont img#big_img').attr('height',400);
                $("#gallery_img_cont").css("width","400px");
                $("#gallery_img_cont").css("height","400px");
                $("#gallery_img_cont").css("top",(($("#gallery_data").height()-400)/2)+"px");
                $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-400)/2)+"px");
                $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px"); 
              }else{
              i=imageWidth/imageHeight;
              j=600/i;  
               $('#gallery_img_cont img#big_img').attr('width',600);
              $('#gallery_img_cont img#big_img').attr('height',j); 
              $("#gallery_img_cont").css("width","600px");
                $("#gallery_img_cont").css("height",j+"px");
               $("#gallery_img_cont").css("top",(($("#gallery_data").height()-j)/2)+"px");
                $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-600)/2)+"px");
                $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px"); 
              }
              }else{
              $('#gallery_img_cont img#big_img').attr('width',imageWidth);
                $('#gallery_img_cont img#big_img').attr('height',imageHeight); 
                $("#gallery_img_cont").css("width",imageWidth+"px");
                $("#gallery_img_cont").css("height",imageHeight+"px");
                $("#gallery_img_cont").css("top",(($("#gallery_data").height()-imageHeight)/2)+"px");
                $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-imageWidth)/2)+"px");
               // $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px");
                }
              $("#gallery_img_cont").css("visibility","visible");
               //warunek spełnia się podczas uruchomienia okna komentarzy(zmiana położenia zdjęcia)
                if($("#gallery_info_cont" ).css("right")=="0px"){
                    if(((a=(($("#gallery_data" ).width()/2)+($("#gallery_img_cont" ).width()/2))-($("#gallery_data" ).width()-$("#gallery_info_cont" ).width()-$("#gl_inf_but_visibility_cnt" ).width()))>0)){
                        if((((($("#gallery_data" ).width()-($("#gallery_img_cont" ).width()-$("#gallery_info_cont" ).width()-$("#gl_inf_but_visibility_cnt" ).width()))>0))&&(($("#gallery_data" ).width()/2)-($("#gallery_img_cont" ).width()/2)-a-$("#gl_inf_but_visibility_cnt" ).width())>=0)){
                        $("#gallery_img_cont").css("margin-left",(($("#gallery_data" ).width()/2)-($("#gallery_img_cont" ).width()/2)-a)+"px");   
                        $("#gallery_arrow_cont").css("width",$("#gallery_img_cont").width()+"px"); 
                        }else{
                        $("#gallery_img_cont").css("margin-left","0px"); 
                        $("#gallery_arrow_cont").css("width",($("#gallery_arrow_cont" ).width()+($("#gallery_data" ).width()-($("#gallery_img_cont" ).width()+$("#gallery_info_cont" ).width()+$("#gl_inf_but_visibility_cnt" ).width())))+"px");   
                        }
                    }  
                }else{
                $("#gallery_arrow_cont").css("width",$("#gallery_img_cont").width()+"px");            
                $("#gallery_img_cont").css("margin-left",((($("#gallery_data").width())/2)-($("#gallery_img_cont").width()/2))+"px");               
                }
                $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px"); 
                if(array_get_image[1]!="0"){
                $("#arrow_left").css("visibility","visible");
                }
                if(index_img<($(".cont_photo img").length-1)){
                $("#arrow_right").css("visibility","visible");
                }
            //$("#off_gallery").css("left",$("#gallery_img_cont").width()-$("#off_gallery").width()+"px");
            is_process_load=false;
            get_comment(index_img);
            get_info_photo(index_img);
    });
    number_img=index_img;
    });
});  
});   
//#KOD06 WYŁĄCZENIE GALERII POWIĘKSZONYCH ZDJĘĆ. 
$("#off_gallery").on("click", function() {
$("#gallery_bg").css("visibility","hidden");
$("#gallery_view_cont").css("visibility","hidden");
$("#gallery_img_cont").css("visibility","hidden");
$("#description").css("visibility","hidden");
$(".arrow").css("visibility","hidden");
$('.comment').css("visibility","hidden");
$('#gallery_info_cont').css("right","-400px");
$('#gl_inf_but_visibility_cnt').css("right","0px");
}); 
//#KOD07 ZDARZENIA ODPOWIEDZIALNE ZA ZMIANĘ FOTOGRAFII W GALERII POWIĘKSZONYCH FOTOGRAFII. 
$("#arrow_right").on("click", function() {
if(!is_process_load){
index_img+=1;
get_big_image(index_img);
}
}); 
//#KOD07 ZDARZENIA ODPOWIEDZIALNE ZA ZMIANĘ FOTOGRAFII W GALERII POWIĘKSZONYCH FOTOGRAFII. 
$("#arrow_left").on("click", function() {
if(!is_process_load){
index_img-=1;
get_big_image(index_img);
}
});   
//#KOD08 ZDARZENIE ODPOWIEDZIALNE ZA ODKRYWANIE PEŁNEJ ZAWARTOŚCI KOMENTARZA. 
//kod odpowiedzialny za odkrywanie całego komentarza
$(".view_all_cm").on("click", function() {
$("#cont_cloud_comment_contents" ).css({
opacity: "0",
width:"0px"   
});

index = $(this).parent().parent().prevAll().length;
$('#cloud_comment_contents').text($('.full_cm').eq(index).text());
var p = $(".comment").eq(index);
var offset = p.offset();
var top_cm=offset.top;
p = $("#gallery_data");
offset = p.offset();
var top_gl=offset.top;
//$('#cont_cloud_comment_contents').css("top",(offset.top)+"px");
$('#cont_cloud_comment_contents').css("top",(top_cm-top_gl)+"px");
$('#cont_cloud_comment_contents').css("opacity","0");
$('#cont_cloud_comment_contents').css("visibility","visible");
$('#cont_cloud_comment_contents').css("width","0px");
$('#cont_cloud_comment_contents').css("visibility","visible");
$("#cont_cloud_comment_contents" ).animate({
    opacity: "1",
    width:$('#cloud_comment_contents').css("width")
  }, 400, function() {

  });
});    
//#KOD09 ZDARZENIE UKRYWA ELEMENT UKAZUJĄCY PEŁNĄ TREŚĆ KOMENTARZA. 
$("#body").find("div").on("click", function(e) {
if($(this).attr("class")!="view_all_cm"){
$("#cont_cloud_comment_contents" ).animate({
opacity: "0",
width:"0px"
}, 50, function() {
});       
}else{
e.stopPropagation();   
}
});  

//$(".but_delete_comment").on("click", function(e) {
//alert($(".but_delete_comment").index(this));    
//});  
//usuwanie komentarzy
//$(".but_delete_comment").on( "click", {
//id:0,
//confirmed:false
//}, window.delete_comment );
//
//#KOD010 ZDARZENIE WYWOŁUJĄCE FUNKCJĘ USUWANIA KOMENTARZY, WRAZ Z PARAMETRAMI DLA OKNA INFORMACYJNEGO POTWIERDZAJĄCEGO TĄ CZYNNOŚĆ. 
 $(".but_delete_comment").on( "click", {
id:0,
confirmed:false
}, window.delete_comment );
//#KOD011
 $("#bt_send_cm").on( "click", {
confirmed:false
}, window.append_comment );

//#KOD012 ODKRYCIE/UKRYCIE OKNA WPISYWANIA KOMENTARZY. 
$("#bt_write_cm").on("click", function(e) {
    if($('textarea#contents_comment').css("display")==="none"){
    $('#text_left_cm_cont').css("display","inline-block");
    $('textarea#contents_comment').css("display","inline-block");
    $('#bt_send_cm_cont').css("display","inline-block");   
    $("#bt_write_cm").val("X");    
    }else{
    $('#text_left_cm_cont').css("display","none");
    $('textarea#contents_comment').css("display","none");
    $("#bt_write_cm").val("Napisz komentarz");    
    $('#bt_send_cm_cont').css("display","none"); 
    } 
});
//#KOD013 ZDARZENIE ODPOWIEDZIALNE ZA SYGNALIZOWANIE LIMITU POZOSTAŁYCH ZNAKÓW DO WPISANIA KOMENTARZA. 
$("#contents_comment").keydown(function() {
    if(left_sign_cm-(($("#contents_comment").val()).length+1)>=0){
    $("#text_left_cm").html(left_sign_cm-(($("#contents_comment").val()).length+1));    
    }else{
    $("#contents_comment").val($("#contents_comment").val().substring(0,199));    
    }  
});
//#KOD014
//$('body').delegate('#contents_comment', 'keyup change', function(){
  //  if(left_sign_cm-(($("#contents_comment").val()).length+1)>=0){
  //  $("#text_left_cm").html(left_sign_cm-(($("#contents_comment").val()).length+1));    
 //   }else{
//    $("#contents_comment").val($("#contents_comment").val().substring(0,199));    
//    } 
//});
//#KOD015 ODKRYCIE/UKRYCIE OKNA ZMIANY OPISU. 
$("#description").on("click", function() {
if($("#form_id_login").val()==$("#form_id_profile").val()){
    if($('#cont_edit_description').css("visibility")=="visible"){
    $('#cont_edit_description').css("visibility","hidden");     
    }else{
    $('#cont_edit_description').css("visibility","visible");     
    }
}
});
//#KOD016 ZDARZENIE ODPOWIEDZIALNE ZA ZMIANĘ OPISU. 
$("#bt_set_description").on("click", function() {
if($("#form_id_login").val()==$("#form_id_profile").val()){
    if($("#ed_context").val()==""){
    alert("Pole zmiany opisu jest puste");    
    }else{ 
        if($("#ed_context").val().length<=100){
        $.get("image_sql.php?field=set_description&id_user="+encodeURIComponent($("#form_id_login").val())+"&id_photo=" + encodeURIComponent(number_img)+"&context="+encodeURIComponent($("#ed_context").val()), function(data){         
            if(((data.trim().substring(0,5)).toLowerCase())=="error"){
            alert("Błąd w bazie danych");   
            }else{
            $('#description_info span').text($("#ed_context").val()); 
            $('#cont_edit_description').css("visibility","hidden"); 
            }
        });
        }else{
        alert("Opis nie może przekraczać 100 znaków");    
        }
    }
}
});
//#KOD017 UKRYCIE/ODRYCIE OKNA OPISU. 
$("#big_img").on("hover", 
function() {
if($('#description').css("visibility")=="visible"){
$('#description').css("visibility","hidden");    
}else{
$('#description').css("visibility","visible");    
}
}
);
//#KOD018 UKRYCIE KONTENERA ZMIANY OPISU. 
$("#big_img").on("click", function() {
$('#cont_edit_description').css("visibility","hidden");     
});
//#KOD019 ODKRYCIE/UKRYCIE KONTENERA WYŚWIETLAJĄCEGO INFORMACJE O FOTOGRAFII I KOMENTARZE. 
$("#gl_inf_but_visibility_cnt").on("click", function() {
    if($("#gallery_info_cont" ).css("right")=="0px"){
    //#KOD019A-ANIMACJA OKNA INFORMACYJNEGO. 
    $("#gallery_info_cont" ).animate({
    right: "-400px"
    }, 700, function() {
    $("#gallery_img_cont" ).animate({
    "margin-left":(($("#gallery_data" ).width()/2)-($("#gallery_img_cont" ).width()/2))+"px"
    }, 700, function() {
    });      
    });
    $("#gl_inf_but_visibility_cnt" ).animate({
    right: "0px"
    }, 700, function() {
    $("#bt_inf" ).attr("value","r\n\no\n\nz\n\nw\n\ni\n\nń\n\n\n\nk\n\no\n\nm\n\ne\n\nn\n\nt\n\na\n\nr\n\nz\n\ne");   
    }); 
    //#KOD019B-ZMIANA SZEROKOŚCI KONTENERA PRZYCISKÓW ZMIANY ZDJĘCIA. 
    $("#gallery_arrow_cont" ).animate({
    "width":  $("#gallery_img_cont" ).width()+"px"
    }, 200, function() {
    });   
    }else{
    //#KOD019C-ZMIANA POZYCJI FOTOGRAFII W ZALEŻNOŚCI OD JEJ SZEROKOŚCI W STOSUNKU DO PRZESUNIĘCIA OKNA INFORMACYJNEGO. 
    $("#gallery_info_cont" ).animate({
    right: "0px"
    }, 700, function() {
        //zdjęcie jest wyśrodkowane ale gdy okno komentarzy zostanie uruchomione to:
        //element img zostanie przesunięty gdy zostanie widocznie okno komentarzy tak aby był widoczny całe zdjęcie 
        //jednak gdy zdjęcie będzie miała przechodzić poza kontener komentarzy i zdjęcie,wówczas zdjęcie zostanie przesunięte maxymalnie do lewej konteneru
        if(((a=(($("#gallery_data" ).width()/2)+($("#gallery_img_cont" ).width()/2))-($("#gallery_data" ).width()-$("#gallery_info_cont" ).width()-$("#gl_inf_but_visibility_cnt" ).width()))>0)){
            if((((($("#gallery_data" ).width()-($("#gallery_img_cont" ).width()-$("#gallery_info_cont" ).width()-$("#gl_inf_but_visibility_cnt" ).width()))>0))&&(($("#gallery_data" ).width()/2)-($("#gallery_img_cont" ).width()/2)-a-$("#gl_inf_but_visibility_cnt" ).width())>=0)){
            $("#gallery_img_cont" ).animate({
            "margin-left": (($("#gallery_data" ).width()/2)-($("#gallery_img_cont" ).width()/2)-a)+"px"
            }, 700, function() {
            }); 
            $("#gallery_arrow_cont").css("width",$("#gallery_img_cont").width()+"px");   
            }else{
            $("#gallery_img_cont" ).animate({
            "margin-left":"0px"
            }, 700, function() {
            }); 
            $("#gallery_arrow_cont" ).animate({
            "width":  ($("#gallery_arrow_cont" ).width()+($("#gallery_data" ).width()-($("#gallery_img_cont" ).width()+$("#gallery_info_cont" ).width()+$("#gl_inf_but_visibility_cnt" ).width())))+"px"
            }, 700, function() {
            });   
            }
        }    
    });
    $("#gl_inf_but_visibility_cnt" ).animate({
    right: "400px"
    }, 700, function() {
    $("#bt_inf" ).val("z\n\nw\n\ni\n\nń\n\n\n\nk\n\no\n\nm\n\ne\n\nn\n\nt\n\na\n\nr\n\nz\n\ne");      
    }); 
    }

});
//exit init
///////////////
/////////////
}
//#KOD020-FUNKCJA ODPOWIEDZIALNA ZA USUWANIE KOMENTARZY. 
//GLOBALNA FUNKCJA ZE WZGLĘDU NA ROZWIĄZANIE
//FUNKCJA URUCHAMIA GLOBALNĄ FUNKCJE TWORZENIA OKNA INFORMACYJNEGO Z POTWIERDZENIEM DANEJ CZYNNOŚCI
//event.data.confirmed=false->JEŚLI NIE ZOSTAŁA WYBRANA OPCJA POTWIERDZENIA OPERACJI,TRUE->PODCZAS WYBRANIA OPERACJI
//event.data.choose odpowiada za wybór opcji w alercie(okna inforamcyjnego)
window.delete_comment=function delete_comment(event){ 
//get the index from choose button
//zabezpieczenie przed spreparowanym zapytaniem java script
if($("#form_id_login").val()==$("#form_id_profile").val()){
    event.data.id=$(event.target).parents(".comment").prevAll(".comment").size();
    if(event.data.confirmed){
        if(event.data.choose){
            $.get("get_comment.php?field=delete_comment&id_user="+encodeURIComponent($("#form_id_login").val())+"&id_photo=" + encodeURIComponent(number_img)+"&id_choose_photo=" + encodeURIComponent(event.data.id), function(data){    
            $(".comment" ).eq(event.data.id).animate({
            opacity: "0",
            height:"0px"
            }, 150, function() {
            $('.comment').eq(event.data.id).remove();    
            if($('.comment').size()<1){
            $('#cont_gv_comment').prepend('<div class="comment"><center><span class="empty_cm">brak komentarzy</span></center></div>');      
            }    
            //poprawal_choose_create("delete_comment",event);  
            al_choose_create(0,0,1);  
            });
            }); 
        }else{
        
        }
    event.data.confirmed=false; 
    }else{  
    al_choose_create("delete_comment",event); 
    }
}
}
//#KOD021-FUNKCJA ODPOWIEDZIALNA ZA DODAWANIE KOMENTARZY. 
//GLOBALNA FUNKCJA ZE WZGLĘDU NA ROZWIĄZANIE
//FUNKCJA URUCHAMIA GLOBALNĄ FUNKCJE TWORZENIA OKNA INFORMACYJNEGO Z POTWIERDZENIEM DANEJ CZYNNOŚCI
//event.data.confirmed=false->JEŚLI NIE ZOSTAŁA WYBRANA OPCJA POTWIERDZENIA OPERACJI,TRUE->PODCZAS WYBRANIA OPERACJI
//event.data.choose odpowiada za wybór opcji w alercie(okna inforamcyjnego)
window.append_comment=function append_comment(event){     
//get the index from choose button
//zabezpieczenie przed spreparowanym zapytaniem java script
//if($("#form_id_login").val()==$("#form_id_profile").val()){
    if(event.data.confirmed){
        if(event.data.choose){
        $.getJSON("get_comment.php?field=append_comment&context="+encodeURIComponent($("#contents_comment").val())+"&id_profile="+encodeURIComponent($("#form_id_profile").val())+"&id_user="+encodeURIComponent($("#form_id_login").val())+"&id_photo=" + encodeURIComponent(number_img), function(data){             
        if(data['error']){
        alert("błąd w bazie danych:"+data['error']);    
        }else{
        var context;    
        var view_all="";
        var part="";
        $(".empty_cm").parents('.comment').remove();
        //#KOD021A-KOD PROGRAMU TWORZĄCY DODATKOWY ELEMENT PRZYCISKU, KTÓRY MA SŁUŻYĆ DŁUGIM KOMENTARZĄ. 
        if(($("#contents_comment").val().length)>120){
        context=$("#contents_comment").val().substring(0,100);
        view_all='<div class="view_all_cm">[pokaż cały]</div>';
        part=" part";
        }else{
        context=$("#contents_comment").val();  
        }
        //#KOD021B-KOD PROGRAMU BUDUJĄCY ELEMENTY HTML DLA KOMENTARZY. 
        var personal=($("#form_id_login").val()==$("#form_id_profile").val())?" personal":"";
        var delete_cm=($("#form_id_login").val()==$("#form_id_profile").val())?'<div class="but_delete_comment"><span class="delete_cmt">[usuń]</span></div>':"";
        $('#contents_full_cm').prepend('<div class="full_cm">'+$("#contents_comment").val()+'</div>');   
        $('#cont_gv_comment').prepend('<div class="comment'+personal+part+'"><div class="cmt_content"><div class="cmt_info"><div class="cmt_cont_img"><img src="gallery/'+$("#form_id_login").val()+'.jpg" height="30" width="30"></div><div class="cmt_surname"><a href="'+$("#user_href").html().trim()+'"><span>'+(data['name'].substring(0,1).toUpperCase()+data['name'].substring(1,data['name'].length).toLowerCase())+' '+(data['surname'].substring(0,1).toUpperCase()+data['surname'].substring(1,data['surname'].length).toLowerCase())+'</span></a></div><div class="cmt_time">'+data['time']+'</div></div></div><div class="cmt_cont_context"><div class="cont_context">'+context+'</div>'+view_all+delete_cm+'</div></div></div></div></div>');        
        }
        //
        $('*').unbind();
        init_clicked(); 
        });
        }
    event.data.confirmed=false; 
    }else{
        if($("#contents_comment").val()==""){
        alert("Pole wpisywania komentarz jest puste");    
        }else{
        al_choose_create("append_comment",event);
        }
    }
//}
}
//#KOD022-FUNKCJA ODPOWIEDZIALNA ZA POBIERANIE INFORMACJI O FOTOGRAFII.
//Użycie typu danych JSON
function get_info_photo(id) {
//getJSON wymaga zwrotu danych json,w przeciwnym wypadku nastąpi błąd
$.getJSON("get_comment.php?field=get_info_photo&id_user="+encodeURIComponent($("#form_id_profile").val())+"&id_photo="+encodeURIComponent(id), function(data){  
if(data['description']==""){
$('#description_info span').html("brak opisu,kliknij aby dodać");    
}else{
$('#description_info span').html(data['description']);    
}
$('#gv_time_contents').html(data['time']);
});
}
//#KOD023-FUNKCJA POBIERAJĄCA KOMENTARZE DLA WYBRANEJ FOTOGRAFII. 
function get_comment(id){  
$.get("get_comment.php?field=get_comments_html&id_user="+encodeURIComponent($("#form_id_login").val())+"&id_photo=" + encodeURIComponent(id)+"&id_profile="+encodeURIComponent($("#form_id_profile").val()),{ "_": $.now() }, function(data){    
    if((($.trim(data.substring(0,6)).toLowerCase()))=="error"){     
    }else if((($.trim(data.substring(0,6).toLowerCase())))=="empty"){
    $('#cont_gv_comment').html('<div class="comment"><center><span class="empty_cm">brak komentarzy</span></center></div>'); 
    $('*').unbind();
    init_clicked();     
    }else{
    $('.comment').remove();    
    $('.comment').css("visibility","hidden"); 
    $('#cont_gv_comment').html($.trim(data));
    $('.cmt_cont_context').each(function(index) { 
    $('#contents_full_cm').append('<div class="full_cm"></div>');
    $('div.full_cm').eq(index).text($('.cmt_cont_context').eq(index).children(".cont_context").justtext());
        if(($('.cmt_cont_context').eq(index).text().length)>110){
        $('.cmt_cont_context').eq(index).append('<div class="view_all_cm">[pokaż cały]</div>'); 
        $('.cmt_cont_context').eq(index).parent().addClass( "part" );
        }      
        $('.comment').css("visibility","visible");
       
    }); 
    //ODŚWIEŻANIE DRZEWA "DOM"
    $('*').unbind();
    init_clicked(); 
    //
    }
});


}
//WYCIĄGANIE TYLKO TEKSTU Z ELEMENTU
//DODAWANIE NOWEJ FUNKCJI DO JQUERY
jQuery.fn.justtext = function() {
    return $(this).clone()
            .children()
            .remove()
            .end()
            .text();

};

init_clicked();
//#KOD024-FUNKCJA POBIERA WYBRANĄ FOTOGRAFIĘ I UMIESZCZA DO KONTENERA GALERII POWIĘKSZONYCH FOTOGRAFII. 
function get_big_image(index_img){
is_process_load=true;
$("#loading_sign").css("left",(($("#gallery_view_cont").width()/2)-($("#loading_sign").width()/2))+"px");
$("#loading_sign").css("top",((($("#gallery_view_cont").height()/2)-($("#loading_sign").height()/2)))+"px"); 
$("#gallery_img_cont").css("visibility","hidden");
$(".arrow").css("visibility","hidden");
$("#loading_sign").css("visibility","visible");
$("#gallery_view_cont").css("visibility","visible");
    $.get("get_info_image.php?id_user="+encodeURIComponent($("#form_id_profile").val())+"&id_foto=" + encodeURIComponent(index_img), function(data){
      array_get_image=data.split(';');
        name_file=$.trim(array_get_image[0]);       
            img =  $('#gallery_img_cont img#big_img').eq(0).attr('src',"gallery/"+$("#form_id_profile").val()+"/"+name_file+"?"+new Date().getTime())
            .load(function(responseText, textStatus, XMLHttpRequest) {
            $("#loading_sign").css("visibility","hidden");
            screenImage = $('#gallery_img_cont img#big_img');
            theImage = new Image();
            theImage.src = screenImage.attr("src");
            imageWidth = theImage.width;
            imageHeight = theImage.height;
              if(imageWidth>1000 || imageHeight>500){
                if(imageWidth>imageHeight){
                i=imageWidth/imageHeight;
                j=1000/i;
                i=1000;
                if(j>500){
                j=j/500;
                i=1000/j;
                j=500;
                }
                $('#gallery_img_cont img#big_img').attr('width',i);
                $('#gallery_img_cont img#big_img').attr('height',j); 
                $("#gallery_img_cont").css("width",i+"px");
                $("#gallery_img_cont").css("height",j+"px");
                  $("#gallery_img_cont").css("top",(($("#gallery_data").height()-j)/2)+"px");
                  $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-i)/2)+"px");
                  $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px"); 
                }else{
                i=imageHeight/imageWidth;
                j=500/i;
                i=500;
                if(j>1000){
                j=j/1000;
                i=500/j;
                j=1000;
                }
                $('#gallery_img_cont img#big_img').attr('width',j);
                $('#gallery_img_cont img#big_img').attr('height',i);
                $("#gallery_img_cont").css("width",j+"px");
                $("#gallery_img_cont").css("height",i+"px");
                $("#gallery_img_cont").css("top",(($("#gallery_data").height()-i)/2)+"px");
                $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px");                         
                }    
              }else if(imageWidth<=600 || imageHeight<=400){
              //solution compresing resolution image
                    if(imageWidth<imageHeight){
                    i=imageHeight/imageWidth;
                    j=400/i;
                    $('#gallery_img_cont img#big_img').attr('width',j);
                    $('#gallery_img_cont img#big_img').attr('height',400);
                    $("#gallery_img_cont").css("width",j+"px");
                    $("#gallery_img_cont").css("height","400px");
                    $("#gallery_img_cont").css("top",(($("#gallery_data").height()-400)/2)+"px");
                    $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-j)/2)+"px");
                    $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px"); 
                    }else if(imageWidth==imageHeight){
                    $('#gallery_img_cont img#big_img').attr('width',400);
                    $('#gallery_img_cont img#big_img').attr('height',400);
                    $("#gallery_img_cont").css("width","400px");
                    $("#gallery_img_cont").css("height","400px");
                    $("#gallery_img_cont").css("top",(($("#gallery_data").height()-400)/2)+"px");
                    $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-400)/2)+"px");
                    $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px"); 
                    }else{
                    i=imageWidth/imageHeight;
                    j=600/i;  
                    $('#gallery_img_cont img#big_img').attr('width',600);
                    $('#gallery_img_cont img#big_img').attr('height',j); 
                    $("#gallery_img_cont").css("width","600px");
                    $("#gallery_img_cont").css("height",j+"px");
                    $("#gallery_img_cont").css("top",(($("#gallery_data").height()-j)/2)+"px");
                    $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-600)/2)+"px");
                    $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px"); 
                    }
                }else{    
                $('#gallery_img_cont img#big_img').attr('width',imageWidth);
                $('#gallery_img_cont img#big_img').attr('height',imageHeight); 
                $("#gallery_img_cont").css("width",imageWidth+"px");
                $("#gallery_img_cont").css("height",imageHeight+"px");
                $("#gallery_img_cont").css("top",(($("#gallery_data").height()-imageHeight)/2)+"px");
                $("#gallery_img_cont").css("margin-left",(($("#gallery_data").width()-imageWidth)/2)+"px");
                $("#gallery_arrow_cont").css("margin-top",((($("#gallery_img_cont").height()/2)-($("#gallery_arrow_cont").height()/2)))+"px");     
                $("#gallery_arrow_cont").css("width",$("#gallery_img_cont").width()+"px");
                }
                $("#gallery_img_cont").css("visibility","visible");
                //warunek spełnia się podczas uruchomienia okna komentarzy(zmiana położenia zdjęcia)
                //zdjęcie jest wyśrodkowane ale gdy okno komentarzy zostanie uruchomione to:
                //element img zostanie przesunięty gdy zostanie widocznie okno komentarzy tak aby był widoczny całe zdjęcie 
                //jednak gdy zdjęcie będzie miała przechodzić poza kontener komentarzy i zdjęcie,wówczas zdjęcie zostanie przesunięte maxymalnie do lewej konteneru
                if($("#gallery_info_cont" ).css("right")=="0px"){                 
                    if(((a=(($("#gallery_data" ).width()/2)+($("#gallery_img_cont" ).width()/2))-($("#gallery_data" ).width()-$("#gallery_info_cont" ).width()-$("#gl_inf_but_visibility_cnt" ).width()))>0)){
                        if((((($("#gallery_data" ).width()-($("#gallery_img_cont" ).width()-$("#gallery_info_cont" ).width()-$("#gl_inf_but_visibility_cnt" ).width()))>0))&&(($("#gallery_data" ).width()/2)-($("#gallery_img_cont" ).width()/2)-a-$("#gl_inf_but_visibility_cnt" ).width())>=0)){
                        $("#gallery_img_cont").css("margin-left",(($("#gallery_data" ).width()/2)-($("#gallery_img_cont" ).width()/2)-a)+"px");   
                        $("#gallery_arrow_cont").css("width",$("#gallery_img_cont").width()+"px"); 
                        }else{
                        $("#gallery_img_cont").css("margin-left","0px"); 
                        $("#gallery_arrow_cont").css("width",($("#gallery_arrow_cont" ).width()+($("#gallery_data" ).width()-($("#gallery_img_cont" ).width()+$("#gallery_info_cont" ).width()+$("#gl_inf_but_visibility_cnt" ).width())))+"px");   
                        }
                    }else{
                    //warunek jest spełniany gdy zdjęcie nie wystaje po za komentarz    
                    $("#gallery_arrow_cont").css("width",$("#gallery_img_cont").width()+"px");          
                    $("#gallery_img_cont").css("margin-left",((($("#gallery_data").width())/2)-($("#gallery_img_cont").width()/2))+"px");    
                    }  
                }else{
                $("#gallery_arrow_cont").css("width",$("#gallery_img_cont").width()+"px");        
                $("#gallery_img_cont").css("margin-left",((($("#gallery_data").width())/2)-($("#gallery_img_cont").width()/2))+"px");       
                }
                //$("#off_gallery").css("left",$("#gallery_img_cont").width()-$("#off_gallery").width()+"px");
                is_process_load=false;
                if(array_get_image[1]!="0"){
                $("#arrow_left").css("visibility","visible");
                }
                if(index_img<($(".cont_photo img").length-1)){
                $("#arrow_right").css("visibility","visible");
                }
                get_comment(index_img);
                get_info_photo(index_img);
    });
    });   
    number_img=index_img;
};
//#KOD025 - URUCHAMIA FUNKCJE PRODUKUJĄCE WSZYSTKIE ZDARZENIA DLA MODUŁU GALERII ZDJĘĆ. 
//funkcja init_clicked musi być na końcu!!!!,ze względu na to że daje akcje przyciskom różnych funkcji. 
function init_clicked() {
    init();
    init_global();
}
});


