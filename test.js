$(document).ready(function() {  
var switch_cloud_let_move=false;
var result;
var value;
var timeout=600;
var array_get_mysql;
var array_get_mysql_pos;
var array_save_mysql_new;
var array_save_mysql_old;
var limit_time=1000;
var click_nr;
var txt_splt;
var txt_splt2;
var buffer_text=[];
var blocked_hover=false;
var name_element;
var out_element_search=false;
var element_name;
var offset;
var live;
var school;
var work;
var switch_content_search_school;
var switch_content_search_work;
var temp;
var t=0;
var z=0;
var timeout_wait;
var building_string_wait=".";
var building_status=0;
var txtEncoded;
var let_animation=false;
//#KOD01 KOD ODPOWIEDZIALNY ZA URUCHOMIENIE PODPOWIEDZI W TRAKCIE OPUSZCZENIA PRZYCISKU
$("input#live,input#school,input#work").keydown(function() {
var name_element=$(this).attr("id");
$('.input_search,.input_search div').css("visibility","hidden");
clearTimeout(timeout);
timeout=setTimeout(function(){
//działanie,kiedy zostanie opuszczony przycisk na więcej niż limit_time
//działąnie usunięcie (value) i dodanie nowej klasy podkreślającej wartość
blocked_hover=false;
$('#form_'+name_element+'_search').css("visibility","visible");
$('#form_'+name_element+'_search div').css("visibility","visible");
$('#form_'+name_element+'_search div').css("visibility","hidden");
value=$("#"+ name_element).val();
$.ajaxSetup({
cache: false
});
$.ajax({
  type: "GET",
  url: "ep_get_mysql.php?field=" + name_element + "&value=" + encodeURIComponent(value),
  cache: false
})
  .done(function( data ) {
  if(data!="" && data!=" "){
  get_mysql(data,value,name_element); 
  }else{
  $('.input_search,.input_search div').css("visibility","hidden");   
  };
  });

},limit_time);
});
//#KOD02 KOD ODPOWIEDZIALNY ZA WYŚWIETLANIE PÓL PODPOWIEDZI
function get_mysql(data,value,name_element){
//dzielenie informacji typu 'x,y,z;x,y,z' na informacje a[x],b[x]
value=value.toLowerCase();
array_get_mysql=data.split(';');
array_get_mysql_pos=array_get_mysql[1].split(',');
array_get_mysql[0]=array_get_mysql[0].trim();
array_get_mysql=array_get_mysql[0].split(',');
$('#form_'+name_element+'_search').css("height",(26*array_get_mysql.length)+"px");
    for (var i = 0; i < array_get_mysql.length; i++) {
    $('#form_'+name_element+'_search div').eq(i).css("visibility","visible");
    //array_get_mysql[0]=array_get_mysql[0].replace(new RegExp(" ","g"),'&nbsp;')  
    buffer_text[1]=value;
    if(parseInt(array_get_mysql_pos[i])==1){
    buffer_text[2]=array_get_mysql[i].substring(parseInt(array_get_mysql_pos[i])-1+value.length);   
    buffer_text[1]=value;
    buffer_text[0]='';
    }else{
    buffer_text[2]=array_get_mysql[i].substring(parseInt(array_get_mysql_pos[i])-1);
    buffer_text[2]=buffer_text[2].replace(buffer_text[1],'');
    buffer_text[0]=array_get_mysql[i].replace(buffer_text[1]+buffer_text[2],'');

    }
    buffer_text[0]=buffer_text[0].replace(new RegExp(" ","g"),'&nbsp;');
    buffer_text[1]=buffer_text[1].replace(new RegExp(" ","g"),'&nbsp;'); 
    buffer_text[2]=buffer_text[2].replace(new RegExp(" ","g"),'&nbsp;'); 
    $('.form_'+name_element+'_search').eq(i).html("<span>"+ buffer_text[0] + "</span><span>" + buffer_text[1] + "</span><span>" + buffer_text[2] + "</span>");
    }
};
//#KOD03 KOD ODPOWIEDZIALNY ZA KLIKNIĘCIE NA POLE EDYCJI NAZWY SZKOŁY
$("input#school").on("click", function() {
blocked_hover=false;
$('#form_live_search').css("visibility","hidden");
$('.form_live_search').css("visibility","hidden");
$('.form_live_search span').remove();
});
//#KOD04 KOD ODPOWIEDZIALNY ZA KLIKNIĘCIE NA POLE EDYCJI NAZWY MIEJSCOWOŚCI
$("input#live").on("click", function() {
blocked_hover=false;
$('#form_live_search').css("visibility","hidden");
$('.form_live_search').css("visibility","hidden");
$('.form_live_search span').remove();
});
//#KOD05 KOD ODPOWIEDZIALNY ZA KLIKNIĘCIE NA DANE POLE PODPOWIEDZI
$("#form_live_search").on("click", ".form_live_search", function() {
let_animation=false;
blocked_hover=false;
action_after_change_control();
//let_animation,building_strin_wait belong to function wait_window_information()
//let_animation lets function wait_window_information() works
live=$(".form_live_search").eq($(".form_live_search").index(this)).text();
$("#live").val(live);
$('#form_live_search').css("visibility","hidden");
$('.form_live_search').css("visibility","hidden");
$('.form_live_search span').remove();
$.get("ep_save_mysql.php?field=save_live&id_profile="+encodeURIComponent($(".id_profile").text())+"&live="+encodeURIComponent($("#live").val()),function(data){
 action_after_change_control();
 if(data!=" "){
    $('#control_live span').html(data);   
    action_after_change_live();
    }else{
    $('#control_live span').html("zapisano miejscowość");
    action_after_change_live();
    }
});
});
//#KOD06 KOD ODPOWIEDZIALNY ZA ZMIANĘ ZAWARTOŚCI POLA EDYCJI MIEJSCOWOŚCI POPRZEZ NAJECHANIE MYSZKĄ NA POLE PODPOWIEDZI
$("#form_live_search").on("hover", ".form_live_search", function() {
element_name=$(this).attr("class");
out_element_search=false;
if(!blocked_hover){
if(($(".form_live_search").eq($(".form_live_search").index(this)).text())==null || ($(".form_live_search").eq($(".form_live_search").index(this)).text())==""){
$("#live").val(live);  
}else{
$("#live").val($(".form_live_search").eq($(".form_live_search").index(this)).text());    
} 
}
});
$("#school").on("click",function() {
$('#form_school_search').css("visibility","hidden");
$('.form_school_search').css("visibility","hidden");
$('.form_school_search span').remove();   
});
//UKRYCIE OKIEN POPRZEZ KLIKNIĘCIE NA DANE POLE EDYCJI
$("#work").on("click",function() {
$('#form_work_search').css("visibility","hidden");
$('.form_work_search').css("visibility","hidden");
$('.form_work_search span').remove();   
});
///
$("#place_work").on("click",function() {
$('#form_work_search').css("visibility","hidden");
$('.form_work_search').css("visibility","hidden");
$('.form_work_search span').remove();   
});
///
$("#place_school").on("click",function() {
$('#form_school_search').css("visibility","hidden");
$('.form_school_search').css("visibility","hidden");
$('.form_school_search span').remove();   
});
//#KOD07 KOD ODPOWIEDZIALNY ZA ZMIANĘ ZAWARTOŚCI POLA EDYCJI SZKOŁY ORAZ ZA URUCHOMIENIE FUNKCJI PODPOWIEDZI DLA SPECJALIZACJI WEDŁÓG ZAWARTOŚCI EDYCJI SZKOŁY
$("#form_school_search").on("click", ".form_school_search", function() {
blocked_hover=false;
get_info_school($(".form_school_search").eq($(".form_school_search").index(this)).text());
school=$(".form_school_search").eq($(".form_school_search").index(this)).text();
$("#school").val(school);
$('#form_school_search').css("visibility","hidden");
$('.form_school_search').css("visibility","hidden");
$('.form_school_search span').remove();
});
//#KOD08 KOD ODPOWIEDZIALNY ZA ZMIANĘ ZAWARTOŚCI POLA EDYCJI PRACODAWCY ORAZ ZA URUCHOMIENIE FUNKCJI PODPOWIEDZI DLA ZAWODÓW WEDŁÓG ZAWARTOŚCI EDYCJI PRACODAWCY
$("#form_work_search").on("click", ".form_work_search", function() {
blocked_hover=false;
$('#form_work_info_search').css("height","0px");
get_info_work($(".form_work_search").eq($(".form_work_search").index(this)).text());
work=$(".form_work_search").eq($(".form_work_search").index(this)).text();
$("#work").val(work);
$('#form_work_search').css("visibility","hidden");
$('.form_work_search').css("visibility","hidden");
$('.form_work_search span').remove();
});
//#KOD09A KOD ZMIENIAJĄCY ZAWARTOŚĆ POLA EDYCJI SZKOŁY NA ZAWARTOŚĆ ELEMENTU POLA PODPOWIEDZI NA KTÓRE UŻYTKOWNIK NAJECHAŁ KURSOREM
$("#form_school_search").on("hover", ".form_school_search", function() {
element_name=$(this).attr("class");
out_element_search=false;
if(!blocked_hover){
$("#school").val(school);  
}
});
//#KOD09B KOD ZMIENIAJĄCY ZAWARTOŚĆ POLA EDYCJI PRACODAWCY NA ZAWARTOŚĆ ELEMENTU POLA PODPOWIEDZI NA KTÓRE UŻYTKOWNIK NAJECHAŁ KURSOREM
$("#form_work_search").on("hover", ".form_work_search", function() {
element_name=$(this).attr("class");
out_element_search=false;
if(!blocked_hover){
$("#work").val(work);  
}
});
//#KOD010 KODY SYGNALIZUJĄCE WYJECHANIE KURSOREM POZA POLA PODPOWIEDZI
$('#form_school_search').mouseout(function() {
out_element_search=true;
});
$('#form_work_search').mouseout(function() {
out_element_search=true;
});

$('#form_live_search').mouseout(function() {
out_element_search=true;
});
$('#form_school_info_search').mouseout(function() {
out_element_search=true;
blocked_hover=false;
});
$('#form_work_info_search').mouseout(function() {
out_element_search=true;
blocked_hover=false;
});

$("body").on("click", "div", function() {
if(out_element_search && !blocked_hover){
blocked_hover=true;
$('.' + element_name).parent().css("visibility","hidden");
$('.' + element_name).css("visibility","hidden");
$('.' + element_name).children("span").css("visibility","hidden");
}
});
//#KOD011A BUDOWANIE PODPOWIEDZI "SPECJALIZACJI" ODNOSZĄC SIĘ DO ARGUMENTU VALUE(WYBRANEGO POLA Z PODPOWIEDZI SZKOŁY)
function get_info_school(value){
$.get("ep_get_mysql.php?field=school_info&value=" + encodeURIComponent(value), function(data){
if(data!="" && data!=" "){
array_get_mysql=data.split(',');
$('#form_school_info_search').css("height",(26*array_get_mysql.length)+"px");
$('#form_school_info_search').css("visibility","visible");
$('.form_school_info_search').remove();
for (var i = 0; i < array_get_mysql.length; i++) {
$('#form_school_info_search').append(
$('<div></div>', {
class:'form_school_info_search'
}));

$('.form_school_info_search').eq(i).append(
$('<span>'+array_get_mysql[i]+'</span>', {
}));

}
};
return;
});  
}
//#KOD011B BUDOWANIE PODPOWIEDZI "ZAWODÓW" ODNOSZĄC SIĘ DO ARGUMENTU VALUE(WYBRANEGO POLA Z PODPOWIEDZI PRACODAWCY)
function get_info_work(value){
$.get("ep_get_mysql.php?field=work_info&value=" + encodeURIComponent(value), function(data){
if(data!="" && data!=" "){
array_get_mysql=data.split(',');
$('#form_work_info_search').css("height",(26*array_get_mysql.length)+"px");
$('#form_work_info_search').css("visibility","visible");
$('.form_work_info_search').remove();
for (var i = 0; i < array_get_mysql.length; i++) {
$('#form_work_info_search').append(
$('<div></div>', {
class:'form_work_info_search'
}));

$('.form_work_info_search').eq(i).append(
$('<span>'+array_get_mysql[i]+'</span>', {
}));

}
};
return;
});  
}
//#KOD012 ZMIANA POLA EDYCJI "SPECJALIZACJI" POPRZEZ KLIKNIĘCIE NA POLE PODPOWIEDZI SPECJALIZACJI
$("#form_school_info_search").on("click", ".form_school_info_search", function() {
blocked_hover=true;
//$("#specjalization").val($(".form_school_info_search").index(this).text()); 
$("#specjalization").val($(".form_school_info_search").eq($(".form_school_info_search").index(this)).text()); 
$('#form_school_info_search').css("height","0px");
$('#form_school_info_search').css("visibility","hidden");
$('.form_school_info_search').remove();
});
//#KOD013 KOD ZMIENIAJĄCY ZAWARTOŚĆ POLA EDYCJI "SPECJALIZACJI" NA ZAWARTOŚĆ ELEMENTU POLA PODPOWIEDZI NA KTÓRE UŻYTKOWNIK NAJECHAŁ KURSOREM
$("#form_school_info_search").on("hover", ".form_school_info_search", function() {
element_name=$(this).attr("class");
out_element_search=false;
if(!blocked_hover){
$("#specjalization").val($(".form_school_info_search").eq($(".form_school_info_search").index(this)).text()); 
}
});
//#KOD014 KOD ZMIENIAJĄCY ZAWARTOŚĆ POLA EDYCJI "ZAWODU" NA ZAWARTOŚĆ ELEMENTU POLA PODPOWIEDZI NA KTÓRE UŻYTKOWNIK KLIKNĄŁ
$("#form_work_info_search").on("click", ".form_work_info_search", function() {
blocked_hover=true;
$("#profession").val($(".form_work_info_search").eq($(".form_work_info_search").index(this)).text()); 
$('#form_work_info_search').css("height","0px");
$('#form_work_info_search').css("visibility","hidden");
$('.form_work_info_search').remove();
});
//#KOD015 KOD ZMIENIAJĄCY ZAWARTOŚĆ POLA EDYCJI "SPECJALIZACJI" NA ZAWARTOŚĆ ELEMENTU POLA PODPOWIEDZI NA KTÓRE UŻYTKOWNIK NAJECHAŁ KURSOREM
$("#form_work_info_search").on("hover", ".form_work_info_search", function() {
element_name=$(this).attr("class");
out_element_search=false;
if(!blocked_hover){
$("#profession").val($(".form_work_info_search").eq($(".form_work_info_search").index(this)).text()); 
}
});

$("#append_school").click(function() {
$('#form_school_name').clone().appendTo('.table_form:eq(0)');
$('#school_obiekt').clone().appendTo('.table_form:eq(1)');
 });
//#KOD016A KOD WYŚWIETLAJĄCY ELEMENTY DO EDYCJI PÓL SZKOŁY I JEJ INFOMRACJI
//przycisk edytuj ,edycja parametrów szkoły
$("#form_schools_list_type .edit_row_school").click(function() {
switch_content_search_school=0;
index=$(".edit_row_school").index(this);
$('#content_search_school').css("top",($(".school_edit_but:eq(0)").offset().top)-($("#centertop").offset().top) + "px");
$('#content_search_school').css("visibility","visible");
school=$("#school_get_mysql .rows_get_school").eq(index).children("span").eq(0).text();
$('#school').val(school);
$('#place_school').val($("#school_get_mysql .rows_get_school").eq(index).children("span").eq(5).text());
$('#specjalization').val($("#school_get_mysql .rows_get_school").eq(index).children("span").eq(3).text());
}); 
//#KOD016B KOD WYŚWIETLAJĄCY ELEMENTY DO EDYCJI PÓL PRACODAWCY I JEGO INFOMRACJI
 $("#form_works_list_type .edit_row_school").click(function() {
switch_content_search_work=0;
index=$("#form_works_list_type .edit_row_school").index(this);
$('#content_search_work').css("top",($(".school_edit_but:eq(0)").offset().top)-($("#centertop").offset().top) + "px");
$('#content_search_work').css("visibility","visible");
work=$("#work_get_mysql .rows_get_work").eq(index).children("span").eq(0).text();
$('#work').val(work);
$('#place_work').val($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(5).text());
$('#profession').val($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(3).text());
 }); 
//#KOD017A KOD WYŚWIETLAJĄCY ELEMENTY DO DODAWANIA PÓL PRACODAWCY I JEGO INFOMRACJI
 $("#form_works_list_type .append_row_school").click(function() {
switch_content_search_work=1;
index=$("#form_works_list_type .edit_row_school").index(this);
$('#content_search_work').css("top",($(".school_edit_but:eq(0)").offset().top)-($("#centertop").offset().top) + "px");
$('#content_search_work').css("visibility","visible");
work=$("#work_get_mysql .rows_get_work").eq(index).children("span").eq(0).text();
$('#work').val(work);
$('#place_work').val($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(5).text());
$('#profession').val($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(3).text());
 }); 
//#KOD018A KOD WYSYŁAJĄCY ŻĄDANIE O DODAWNIE/EDYCJĘ INFORMACJI O UCZELNI DO BAZY DANYCH
$("#save_cont_but_school").on("click", function() {
if(switch_content_search_school==0){
temp="edit_school";    
url="ep_save_mysql.php?field="+temp+"&id_profile="+encodeURIComponent($(".id_profile").text())+"&place_school_new="+encodeURIComponent($("#place_school").val())+"&place_school_old="+encodeURIComponent($("#school_get_mysql .rows_get_school").eq(index).children("span").eq(5).text())+"&name_school_new="+encodeURIComponent($("#school").val())+"&year_start_new="+encodeURIComponent($("select[name='year_start_sch'] option:selected" ).text())+"&year_finish_new="+encodeURIComponent($("select[name='year_finish_sch'] option:selected" ).text())+"&specialization_new="+encodeURIComponent($("#specjalization").val())+"&name_school_old="+encodeURIComponent($("#school_get_mysql .rows_get_school").eq(index).children("span").eq(0).text())+"&year_start_old="+encodeURIComponent($(".form_schools_list_type").eq(0).children("span").eq(index).text())+"&year_finish_old="+encodeURIComponent($(".form_schools_list_type").eq(1).children("span").eq(index).text())+"&specialization_old="+encodeURIComponent($("#school_get_mysql .rows_get_school").eq(index).children("span").eq(3).text());
}else{
temp="append_school";  
url="ep_save_mysql.php?field="+temp+"&id_profile="+encodeURIComponent($(".id_profile").text())+"&place_school_new="+encodeURIComponent($("#place_school").val())+"&name_school_new="+encodeURIComponent($("#school").val())+"&year_start_new="+encodeURIComponent($("select[name='year_start_sch'] option:selected" ).text())+"&year_finish_new="+encodeURIComponent($("select[name='year_finish_sch'] option:selected" ).text())+"&specialization_new="+encodeURIComponent($("#specjalization").val());
}
$.get(url,function(data){
//jeśli pierwszych 5 znaków będzie ciąg error wtedy pokaże kod błędu
if(((data.trim().substring(0,5)).toLowerCase())=="error"){
alert((data.replace(new RegExp(",","g"),'\n')));    
}else{
alert(data);
if(switch_content_search_school==0){
$("#school_get_mysql .rows_get_school").eq(index).children("span").eq(5).html($("#place_school").val());
$("#school_get_mysql .rows_get_school").eq(index).children("span").eq(0).html($("#school").val());
$("#school_get_mysql .rows_get_school").eq(index).children("span").eq(3).html($("#specjalization").val());
$(".place_schools").eq((index)).html(convert_to_view($("#place_school").val(),'school',5));
$(".name_schools").eq((index+1)).html(convert_to_view($("#school").val(),'school',0));
$(".form_schools_list_type").eq(0).children("span").eq(index).html($("select[name='year_start_sch'] option:selected" ).text());
$(".form_schools_list_type").eq(1).children("span").eq(index).html($("select[name='year_finish_sch'] option:selected" ).text());
$(".form_schools_list_type").eq(2).children("span").eq(index).html(convert_to_view($("#specjalization").val(),'school',3));    
$("#work_get_mysql .rows_get_work").eq(index).children("span").eq(0).html();
$('#place_work').val($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(5).text());
$('#profession').val($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(3).text());
}else{
$('#form_schools_list').append(
$('<div>'+$("#school").val()+'</div>', {
class:'name_schools'
}));
$('#form_schools_list').append(
$('<div>'+$("#place_school").val()+'</div>', {
class:'place_schools'
})); 

$('.form_schools_list_type').eq(0).append(
$('<span>'+$("select[name='year_start_sch'] option:selected" ).text()+'</span>', {
class:'edit_info'
}));


$('.form_schools_list_type').eq(1).append(
$('<span>'+$("select[name='year_finish_sch'] option:selected" ).text()+'</span>', {
class:'edit_info'
}));

$('.form_schools_list_type').eq(2).append(
$('<span>'+$("#specjalization").val()+'</span>', {
class:'edit_info'
}));
$('.form_schools_list_type').eq(2).append(
$('<div class="edit_but"><div class="school_edit_but"><span>[</span></div><div class="school_edit_but"><span class="edit_row_school">Edytuj</span></div><div class="school_edit_but"><span class="delete_row_school">Usuń</span></div><div class="school_edit_but"><span class="append_row_school">Dodaj</span></div><div class="school_edit_but"><span>]</span></div></div>', {
}));
location.reload();
}
$('#form_school_search').css("visibility","hidden");
$('.form_school_search').css("visibility","hidden");
$('.form_school_search span').remove();  
$('#content_search_school').css("visibility","hidden");
};
});    
});


$("#append_school_button").click(function() {
switch_content_search_school=1;
$('#content_search_school').css("top","50px");
$('#content_search_school').css("visibility","visible");
});
$("#append_work_button").click(function() {
switch_content_search_work=1;
$('#content_search_work').css("top","50px");
$('#content_search_work').css("visibility","visible");
});
//#KOD017B KOD WYŚWIETLAJĄCY ELEMENTY DO DODAWANIA PÓL SZKOŁY I JEGO INFOMRACJI
 $("#form_schools_list_type .append_row_school").click(function() {
switch_content_search_school=1;
index=$(".append_row_school").index(this);
$('#content_search_school').css("top",($(".school_edit_but:eq(0)").offset().top)-($("#centertop").offset().top) + "px");
$('#content_search_school').css("visibility","visible");
school=$("#school_get_mysql .rows_get_school").eq(index).children("span").eq(0).text();
$('#school').val(school);
$('#place_school').val($("#school_get_mysql .rows_get_school").eq(index).children("span").eq(5).text());
$('#specjalization').val($("#school_get_mysql .rows_get_school").eq(index).children("span").eq(3).text());
 });
//#KOD019A KOD WYSYŁAJĄCY ŻĄDANIE O USUNIĘCIE DANEJ UCZELNI Z BAZY DANYCH
$("#form_schools_list_type .delete_row_school").click(function() {
index=$("#form_schools_list_type .delete_row_school").index(this);
school_name=$("#school_get_mysql .rows_get_school").eq(index).children("span").eq(0).text();
place_school=$("#school_get_mysql .rows_get_school").eq(index).children("span").eq(5).text();
special=$("#school_get_mysql .rows_get_school").eq(index).children("span").eq(3).text();
$('#profession').val($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(3).text());
$.get("ep_save_mysql.php?field=delete_school&id_profile="+encodeURIComponent($(".id_profile").text())+"&place_school_old="+encodeURIComponent(place_school)+"&name_school_old="+encodeURIComponent(school_name)+"&year_start_old="+encodeURIComponent($(".form_schools_list_type").eq(0).children("span").eq(index).text())+"&year_finish_old="+encodeURIComponent($(".form_schools_list_type").eq(1).children("span").eq(index).text())+"&specialization_old="+encodeURIComponent(special),function(data){
location.reload();
});
});
//#KOD019B KOD WYSYŁAJĄCY ŻĄDANIE O USUNIĘCIE DANEGO PRACODAWCY Z BAZY DANYCH
$("#form_works_list_type .delete_row_school").click(function() {
index=$("#form_works_list_type .delete_row_school").index(this);
work_name=$("#work_get_mysql .rows_get_work").eq(index).children("span").eq(0).text();
place_work=$("#work_get_mysql .rows_get_work").eq(index).children("span").eq(5).text();
special=$("#work_get_mysql .rows_get_work").eq(index).children("span").eq(3).text();
$.get("ep_save_mysql.php?field=delete_work&id_profile="+encodeURIComponent($(".id_profile").text())+"&place_work_old="+encodeURIComponent(place_work)+"&name_work_old="+encodeURIComponent(work_name)+"&year_start_old="+encodeURIComponent($(".form_works_list_type").eq(0).children("span").eq(index).text())+"&year_finish_old="+encodeURIComponent($(".form_works_list_type").eq(1).children("span").eq(index).text())+"&profession_old="+encodeURIComponent(special),function(data){
location.reload();
});
});
//przyciski anulowania edycji danych
$("#exit_cont_but_work").on("click", function() {
$('#content_search_work').css("visibility","hidden");
});
$("#exit_cont_but_school").on("click", function() {
$('#content_search_school').css("visibility","hidden");
});
//#KOD018B KOD WYSYŁAJĄCY ŻĄDANIE O DODAWNIE/EDYCJĘ INFORMACJI O PRACODAWCY DO BAZY DANYCH
$("#save_cont_but_work").on("click", function() {
//switch_content_search_work=>0 -> if options==edit
//switch_content_search_work=>1 -> if options==append
if(switch_content_search_work==0){
temp="edit_work";  
url="ep_save_mysql.php?field="+temp+"&id_profile="+encodeURIComponent($(".id_profile").text())+"&place_work_new="+encodeURIComponent($("#place_work").val())+"&place_work_old="+encodeURIComponent($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(5).text())+"&name_work_new="+encodeURIComponent($("#work").val())+"&year_start_new="+encodeURIComponent($("select[name='year_start_wk'] option:selected" ).text())+"&year_finish_new="+encodeURIComponent($("select[name='year_finish_wk'] option:selected" ).text())+"&profession_new="+encodeURIComponent($("#profession").val())+"&name_work_old="+encodeURIComponent($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(0).text())+"&year_start_old="+encodeURIComponent($(".form_works_list_type").eq(0).children("span").eq(index).text())+"&year_finish_old="+encodeURIComponent($(".form_works_list_type").eq(1).children("span").eq(index).text())+"&profession_old="+encodeURIComponent($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(3).text());
}else{
temp="append_work";  
url="ep_save_mysql.php?field="+temp+"&id_profile="+encodeURIComponent($(".id_profile").text())+"&place_work_new="+encodeURIComponent($("#place_work").val())+"&name_work_new="+encodeURIComponent($("#work").val())+"&year_start_new="+encodeURIComponent($("select[name='year_start_wk'] option:selected" ).text())+"&year_finish_new="+encodeURIComponent($("select[name='year_finish_wk'] option:selected" ).text())+"&profession_new="+encodeURIComponent($("#profession").val());
}
$.get(url,function(data){
if(((data.trim().substring(0,5)).toLowerCase())=="error"){
alert((data.replace(new RegExp(",","g"),'\n')));    
}else{
alert(data);
if(switch_content_search_work==0){
$("#work_get_mysql .rows_get_work").eq(index).children("span").eq(5).html($("#place_work").val());
$("#work_get_mysql .rows_get_work").eq(index).children("span").eq(0).html($("#work").val());
$("#work_get_mysql .rows_get_work").eq(index).children("span").eq(3).html($("#profession").val());
$(".place_works").eq((index)).html(convert_to_view($("#place_work").val(),'work',5));
$(".name_works").eq((index+1)).html(convert_to_view($("#work").val(),'work',0));
$(".form_works_list_type").eq(0).children("span").eq(index).html($("select[name='year_start_wk'] option:selected" ).text());
$(".form_works_list_type").eq(1).children("span").eq(index).html($("select[name='year_finish_wk'] option:selected" ).text());
$(".form_works_list_type").eq(2).children("span").eq(index).html(convert_to_view($("#profession").val(),'work',3));
}else{
$('#form_works_list').append(
$('<div>'+$("#work").val()+'</div>', {
class:'name_works'
}));
$('#form_works_list').append(
$('<div>'+$("#place_work").val()+'</div>', {
class:'place_works'
})); 

$('.form_works_list_type').eq(0).append(
$('<span>'+$("select[name='year_start_wk'] option:selected" ).text()+'</span>', {
class:'edit_info'
}));


$('.form_schools_list_type').eq(1).append(
$('<span>'+$("select[name='year_finish_sch'] option:selected" ).text()+'</span>', {
class:'edit_info'
}));

$('.form_schools_list_type').eq(2).append(
$('<span>'+$("#specjalization").val()+'</span>', {
class:'edit_info'
}));
$('.form_works_list_type').eq(2).append(
$('<div class="edit_but"><div class="school_edit_but"><span>[</span></div><div class="school_edit_but"><span class="edit_row_school">Edytuj</span></div><div class="school_edit_but"><span class="delete_row_school">Usuń</span></div><div class="school_edit_but"><span class="append_row_school">Dodaj</span></div><div class="school_edit_but"><span>]</span></div></div>', {
}));
location.reload();
}
$('#form_work_search').css("visibility","hidden");
$('.form_work_search').css("visibility","hidden");
$('.form_work_search span').remove();  
$('#content_search_work').css("visibility","hidden");
};
});    

 });
//#KOD020A KOD WYSYŁAJĄCY ŻĄDANIE O ZMIANĘ POLA "ZAINTERESOWANIA" PODCZAS EDYTOWANIA DANYCH ELEMENTU "HOBBY"
$("#hobby").keydown(function() {
action_after_change_control();
//let_animation,building_strin_wait belong to function wait_window_information()
//let_animation lets function wait_window_information() works
let_animation=true;
building_string_wait=".";
wait_window_information('#control_hobby span',"proszę czekać,trwa zmienianie informacji");
clearTimeout(z);
z=setTimeout(function(){
$.get("ep_save_mysql.php?field=save_hobby&id_profile="+encodeURIComponent($(".id_profile").text())+"&hobby="+encodeURIComponent($("#hobby").val()),function(data){
    let_animation=false;
    if(data!=" "){
    $('#control_hobby span').html(data);   
    }else{
    $('#control_hobby span').html("zapisano zainteresowania");
    }
});
},5000);   
});
//#KOD020B KOD WYSYŁAJĄCY ŻĄDANIE O ZMIANĘ POLA "MIEJSCOWOŚCI" PODCZAS EDYTOWANIA DANYCH ELEMENTU "LIVE"
$("#live").keydown(function() {
    action_after_change_control();
    $('#control_live span').html("proszę czekać,trwa zmienianie informacji"); 
    action_after_change_live();
    //let_animation,building_strin_wait belong to function wait_window_information()
    //let_animation lets function wait_window_information() works
    let_animation=true;
    building_string_wait=".";
    wait_window_information('#control_live span',"proszę czekać,trwa zmienianie informacji");
    clearTimeout(z);
    z=setTimeout(function(){
    lives=$("#live").val();
    $.get("ep_save_mysql.php?field=save_live&id_profile="+encodeURIComponent($(".id_profile").text())+"&live="+encodeURIComponent(lives),function(data){
    let_animation=false;    
    if(data!=" "){
    $('#control_live span').html(data);   
    action_after_change_live();
    }else{
    $('#control_live span').html("zapisano miejscowość");
    action_after_change_live();
    }
    });
    },5000);   
});
//#KOD020C KOD WYSYŁAJĄCY ŻĄDANIE O ZMIANĘ POLA "NAZWISKA" PODCZAS EDYTOWANIA DANYCH ELEMENTU "SURNAME"
$("#surname").keydown(function() {
    action_after_change_control();
    $('#control_surname span').html("proszę czekać,trwa zmienianie informacji"); 
    action_after_change_surname();
    //let_animation,building_strin_wait belong to function wait_window_information()
    //let_animation lets function wait_window_information() works
    let_animation=true;
    building_string_wait=".";
    wait_window_information('#control_surname span',"proszę czekać,trwa zmienianie informacji");
    clearTimeout(z);
    z=setTimeout(function(){
    $.get("ep_save_mysql.php?field=save_surname&id_profile="+encodeURIComponent($(".id_profile").text())+"&surname="+encodeURIComponent($("#surname").val()),function(data){
    let_animation=false;
    if(data!=" "){
    $('#control_surname span').html(data); 
    action_after_change_surname();
    }else{
    $('#control_surname span').html("zapisano nazwisko");
    action_after_change_surname();
    }
    });
    },100);   
});
//#KOD020D KOD WYSYŁAJĄCY ŻĄDANIE O ZMIANĘ POLA "IMIENIA" PODCZAS EDYTOWANIA DANYCH ELEMENTU "NAME"
$("#name").keydown(function() {
    action_after_change_control();
    $('#control_surname span').html("proszę czekać,trwa zmienianie informacji"); 
    action_after_change_surname();
    //let_animation,building_strin_wait belong to function wait_window_information()
    //let_animation lets function wait_window_information() works
    let_animation=true;
    building_string_wait=".";
    wait_window_information('#control_surname span',"proszę czekać,trwa zmienianie informacji");
    clearTimeout(z);
    z=setTimeout(function(){
    $.get("ep_save_mysql.php?field=save_name&id_profile="+encodeURIComponent($(".id_profile").text())+"&name="+encodeURIComponent($("#name").val()),function(data){
    let_animation=false;
    if(data!=" "){
    $('#control_surname span').html(data);   
    action_after_change_surname();
    }else{
    $('#control_surname span').html("zapisano imię");
    action_after_change_surname();
    }
    });
    },100);   
});
//$("#name,#surname").keyup(function() {
//action_after_change_control();
//action_after_change_surname();
//});
//$("#live").keyup(function() {
//action_after_change_control();
//action_after_change_live();
//});
//funkcja czyści elementy informacji o błędach
function action_after_change_control(){
$('.control_error span').not('#control_birth span').html("");     
}
//#KOD021A FUNKCJA REGULUJE WYSOKOŚĆ KONTENERA BŁĘDÓW W ZALEŻNOŚCI OD DŁUGOŚCI BŁĘDÓW DLA POLA EDYCJI "MIEJSCOWOŚCI"
//funkcja poprzez zmianę wysokości elementów ukazuje widoczne błędy
function action_after_change_live(){
$('#control_live').css('height',$('#control_live span').css('height'));
height=$('#control_live').css('height');
$('#control_surname').css('height',height);
}
//#KOD021B FUNKCJA REGULUJE WYSOKOŚĆ KONTENERA BŁĘDÓW W ZALEŻNOŚCI OD DŁUGOŚCI BŁĘDÓW DLA POLA EDYCJI "NAZWISKA"
function action_after_change_surname(){
$('#control_surname').css('height',$('#control_surname span').css('height'));
height=$('#control_surname').css('height');
$('#control_live').css('height',height);
}

//#KOD022A KOD ZDARZENIA PODCZAS NAJECHANIA NA ELEMENT POLA NAZWY UCZELNI KTÓRY WYŚWIETLA CHMURĘ PEŁNEJ NAZWY UCZELNI
$( ".name_schools" ).not("#name_schools_head").hover(
function(e) {
index=$( ".name_schools" ).index(this);
$("#cloud_info_school #school_name").html($("#school_get_mysql .rows_get_school").eq(index-1).children("span").eq(0).html());
$("#cloud_info_school #count_people_school").html($("#school_get_mysql .rows_get_school").eq(index-1).children("span.school_get_mysql_count").html());
switch_cloud_let_move=true;
$("#cloud_info_school").css("visibility","visible");
$("#cloud_info_school" ).animate({
opacity: 1
}, 400, function() {
});
}, function() {
switch_cloud_let_move=false;
$("#cloud_info_school" ).animate({
opacity: 0
}, 100, function() {
$("#cloud_info_school").css("visibility","hidden");
});
}
);
//#KOD023 KOD ZDARZENIA ZMIENIAJĄCY POZYCJĘ CHMURY W ZALEŻNOŚCI OD KURSORA
$( ".name_schools" ).mousemove(function(e) {
if(switch_cloud_let_move){
var p = $("#top_menu");
var offset = p.offset();     
 $("#cloud_info_school").css({
    "left":e.pageX-offset.left+"px",
    "top":e.pageY-$("#cloud_info_school").height()-15+($("#cloud_info_school").height()/2)+"px"
 });
};
}); 
$( ".place_schools" ).mousemove(function(e) {
if(switch_cloud_let_move){    
var p = $("#top_menu");
var offset = p.offset();     
 $("#cloud_info_school_place").css({
    "left":e.pageX-offset.left+"px",
    "top":e.pageY-$("#cloud_info_school_place").height()-15+($("#cloud_info_school_place").height()/2)+"px"
 });
};
});
//#KOD022B KOD ZDARZENIA PODCZAS NAJECHANIA NA ELEMENT POLA MIEJSCE UCZELNI KTÓRY WYŚWIETLA CHMURĘ PEŁNEJ NAZWY MIEJSCA UCZELNI
$( ".place_schools" ).hover(
function(e) {
index=$( ".place_schools" ).index(this);
$("#cloud_info_school_place #school_place").html($("#school_get_mysql .rows_get_school").eq(index).children("span").eq(5).html());

switch_cloud_let_move=true;
$("#cloud_info_school_place").css("visibility","visible");
$("#cloud_info_school_place" ).animate({
opacity: 1
}, 400, function() {
});
}, function() {
switch_cloud_let_move=false;
$("#cloud_info_school_place" ).animate({
opacity: 0
}, 100, function() {
$("#cloud_info_school_place").css("visibility","hidden");
});
}
);

$(".form_schools_list_type").eq(2).children("span").mousemove(function(e) {
if(switch_cloud_let_move){
var p = $("#top_menu");
var offset = p.offset();     
 $("#cloud_info_school_specialization").css({
    "left":e.pageX-offset.left+"px",
    "top":e.pageY-$("#cloud_info_school_specialization").height()-15+($("#cloud_info_school_specialization").height()/2)+"px"
 });
};
});
//#KOD022C KOD ZDARZENIA PODCZAS NAJECHANIA NA ELEMENT POLA SPECJALIZACJI UCZELNI KTÓRY WYŚWIETLA CHMURĘ PEŁNEJ NAZWY SPECJALIZACJI UCZELNI
$(".form_schools_list_type").eq(2).children("span").hover(
function(e) {
index=$(".form_schools_list_type").eq(2).children("span").index(this);
$("#cloud_info_school_specialization #school_specialization").html($("#school_get_mysql .rows_get_school").eq(index).children("span").eq(3).html());
switch_cloud_let_move=true;
$("#cloud_info_school_specialization").css("visibility","visible");
$("#cloud_info_school_specialization" ).animate({
opacity: 1
}, 400, function() {
});
}, function() {
switch_cloud_let_move=false;
$("#cloud_info_school_specialization" ).animate({
opacity: 0
}, 100, function() {
$("#cloud_info_school_specialization").css("visibility","hidden");
});
}
);
//#KOD022D KOD ZDARZENIA PODCZAS NAJECHANIA NA ELEMENT POLA NAZWY FIRMY KTÓRY WYŚWIETLA CHMURĘ PEŁNEJ NAZWY FIRMY
$( ".name_works" ).not("#name_works_head").hover(
function(e) {
index=$( ".name_works" ).index(this);
$("#cloud_info_work #work_name").html($("#work_get_mysql .rows_get_work").eq(index-1).children("span").eq(0).html());
$("#cloud_info_work #count_people_work").html($("#work_get_mysql .rows_get_work").eq(index-1).children("span.work_get_mysql_count").html());
switch_cloud_let_move=true;
$("#cloud_info_work").css("visibility","visible");
$("#cloud_info_work" ).animate({
opacity: 1
}, 400, function() {
});
}, function() {
switch_cloud_let_move=false;
$("#cloud_info_work" ).animate({
opacity: 0
}, 100, function() {
$("#cloud_info_work").css("visibility","hidden");
});
}
);
$( ".name_works" ).mousemove(function(e) {
if(switch_cloud_let_move){
var p = $("#top_menu");
var offset = p.offset();     
 $("#cloud_info_work").css({
    "left":e.pageX-offset.left+"px",
    "top":e.pageY-$("#cloud_info_work").height()-15+($("#cloud_info_work").height()/2)+"px"
 });
};
}); 
$( ".place_works" ).mousemove(function(e) {
if(switch_cloud_let_move){
var p = $("#top_menu");
var offset = p.offset();     
 $("#cloud_info_work_place").css({
    "left":e.pageX-offset.left+"px",
    "top":e.pageY-$("#cloud_info_work_place").height()-15+($("#cloud_info_work_place").height()/2)+"px"
 });
};
});
$( ".place_works" ).mousemove(function(e) {
if(switch_cloud_let_move){
var p = $("#top_menu");
var offset = p.offset();     
 $("#cloud_info_work_place").css({
    "left":e.pageX-offset.left+"px",
    "top":e.pageY-$("#cloud_info_work_place").height()-15+($("#cloud_info_work_place").height()/2)+"px"
 });
};
});
//#KOD022E KOD ZDARZENIA PODCZAS NAJECHANIA NA ELEMENT POLA MIEJSCE FIRMY KTÓRY WYŚWIETLA CHMURĘ PEŁNEJ NAZWY MIEJSCA FIRMY
$( ".place_works" ).hover(
function(e) {
index=$( ".place_works" ).index(this);
$("#cloud_info_work_place #work_place").html($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(5).html());
switch_cloud_let_move=true;
$("#cloud_info_work_place").css("visibility","visible");
$("#cloud_info_work_place" ).animate({
opacity: 1
}, 400, function() {
});
}, function() {
switch_cloud_let_move=false;
$("#cloud_info_work_place" ).animate({
opacity: 0
}, 100, function() {
$("#cloud_info_work_place").css("visibility","hidden");
});
}
);
//
$(".form_works_list_type").eq(2).children("span").mousemove(function(e) {
if(switch_cloud_let_move){
var p = $("#top_menu");
var offset = p.offset(); 
 $("#cloud_info_work_profession").css({
    "left":e.pageX-offset.left+"px",
    "top":e.pageY-$("#cloud_info_work_profession").height()-15+($("#cloud_info_work_profession").height()/2)+"px"
 });
};
});
$(".form_works_list_type").eq(2).children("span").hover(
function(e) {
index=$(".form_works_list_type").eq(2).children("span").index(this);
$("#cloud_info_work_profession #work_profession").html($("#work_get_mysql .rows_get_work").eq(index).children("span").eq(3).html());
switch_cloud_let_move=true;
$("#cloud_info_work_profession").css("visibility","visible");
$("#cloud_info_work_profession" ).animate({
opacity: 1
}, 400, function() {
});
}, function() {
switch_cloud_let_move=false;
$("#cloud_info_work_profession" ).animate({
opacity: 0
}, 100, function() {
$("#cloud_info_work_profession").css("visibility","hidden");
});
}
);
//#KOD023 FUNKCJA KONWERTUJE DANE TAK ABY ZMIEŚCIŁY SIĘ W POLACH.
//SKRACANIE ZAWARTOŚCI DANYCH W PRZYPADKU PRZEKROCZENIA OGRANICZONEJ ILOŚCI ZNAKÓW.
function convert_to_view(data,name_get,num_get){
var tablica = new Array();
var school = new Array();
var work = new Array();
tablica['work'] = work;
tablica['school'] = school;
school[0]=45;
school[3]=25;
school[5]=45;
work[0]=45;
work[3]=25;
work[5]=45;
len_validate=tablica[name_get][num_get]?tablica[name_get][num_get]:false;
data_control_len=((data.length>len_validate)&&(len_validate!=false))?data.substring(0,tablica[name_get][num_get])+'...':data;
return data_control_len;
};
//function html_entity_decode(txt){
   // var randomID = Math.floor((Math.random()*100000)+1);
   // $('body').append('<div id="random'+randomID+'"></div>');
  //  $('#random'+randomID).html(txt);
  //  var entity_decoded = $('#random'+randomID).html();
  //  $('#random'+randomID).remove();
  //  return entity_decoded;
//}
//#KOD024 ZDARZENIE KTÓRE WYSTĘPUJE PODCZAS ZMIANY ZAWARTOŚCI JEDNEGO Z ELEMENTÓW EDYCJI "POLA URODZENIA"
$("select[name='day'],select[name='month'],select[name='year']").on('change', function()            
{   
    $('#control_birth span').html("proszę czekać,trwa zmienianie daty"); 
    //let_animation,building_strin_wait belong to function wait_window_information()
    //let_animation lets function wait_window_information() works
    let_animation=true;
    building_string_wait=".";
    wait_window_information('#control_birth span',"proszę czekać,trwa zmienianie daty");
    clearTimeout(z);
    z=setTimeout(function(){   
    $.get("ep_save_mysql.php?field=save_birth&id_profile="+encodeURIComponent($(".id_profile").text())+"&day="+encodeURIComponent($("select[name='day'] option:selected" ).text())+"&month="+encodeURIComponent($("select[name='month'] option:selected" ).attr("value"))+"&year="+encodeURIComponent($("select[name='year'] option:selected" ).text()),function(data){
    let_animation=false;
    $('#control_birth span').html(data);         
     });
    },5000);
}); 
//#KOD025 FUNCKJA URUCHAMIAJĄCA ANIMACJE TEKSTU .
//przykład użycia=wait_window_information('#control_hobby span',"proszę czekać,trwa zmienianie informacji");
function wait_window_information(element_html,text){
if(building_status>4){
    building_string_wait=".";
    building_status=0;
}
building_status++;
clearTimeout(timeout_wait);
timeout_wait=setTimeout(function(){   
if(let_animation){
$(element_html).html(text+building_string_wait); 
building_string_wait=building_string_wait+".";
wait_window_information(element_html,text);
}
},100);

}

});


