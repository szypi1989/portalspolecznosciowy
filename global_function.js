var gl_wait_window_information;
var gl_building_status=0;
var gl_let_animation=true;
var gl_building_string_wait=".";
var init_global;
var gl_timeout_wait;
var al_choose;
var al_stop=true;
var al_fn_related;
var al_fn_event;
var arr_vars="";
var timeout_ms;
$(document).ready(function() {  
refresh_missed_message();
function refresh_missed_message(){
var timeout_ms;
    timeout_ms=setTimeout(function(){ 
    id_friend_field=($("#id_friend").length==0)?"":"&id_friend="+encodeURIComponent($("#id_friend").html().trim());    
    $.get("message_sql.php?field=missed_message&id_login="+encodeURIComponent($('#global_id_login').html().trim())+id_friend_field,function(data){ 
    $("#ml_num_no_get_back").html(data);   
    });
    refresh_missed_message();    
    },1000);  
}

gl_wait_window_information=function gl_wait_window_information(element_html,text){
if(gl_building_status>5){
    gl_building_string_wait="";
    gl_building_status=0;
}
gl_building_status++;
clearTimeout(gl_timeout_wait);
gl_timeout_wait=setTimeout(function(){   
if(gl_let_animation){
$(element_html).html(text+gl_building_string_wait); 
gl_building_string_wait=gl_building_string_wait+".";
gl_wait_window_information(element_html,text);
}
},150); 
}

al_choose_cntrl=function al_choose_cntrl(default_al){  
switch (default_al) {
case 1:
$('#alert_menu_inf').css("left","40%"); 
if(window.arr_vars==""){
$('#alert_menu_inf').css("top","40%");
}else{
$('#alert_menu_inf').css("top",window.arr_vars);    
}
var p = $( "#alert_menu_inf" );
var position = p.position();
$("#alert_menu_inf").css("top",position.top-$(document).scrollTop()+"px");
$("#alert_menu_inf" ).animate({
    opacity: "0",
    width:"0px", 
    height:"0px",
    left:position.left+$("#alert_menu" ).width()/2+"px",
    top:position.top-$(document).scrollTop()+"px"
  }, 150, function() {

  });  
$('#alert_menu_inf').css("visibility","hidden");      
break;
default:
$('#alert_menu').css("left","40%"); 
if(window.arr_vars==""){
$('#alert_menu').css("top","40%");
}else{
$('#alert_menu').css("top",window.arr_vars);    
}
var p = $( "#alert_menu" );
var position = p.position();
$("#alert_menu").css("top",position.top-$(document).scrollTop()+"px");
$("#alert_menu" ).animate({
    opacity: "0",
    width:"0px", 
    height:"0px",
    left:position.left+$("#alert_menu" ).width()/2+"px",
    top:position.top-$(document).scrollTop()+"px"
  }, 150, function() {

  });
$('#alert_menu').css("visibility","hidden");     
break;
}
}
//default_al wybór typu okna informacyjnego,default-dla okna inforamcyjnego z opcjami "tak","nie"
//function_related nazwa funkcji powracającej po potwierdzeniu
//fn_event argumenty(tablica obiektu) funkcji powracającej po potwierdzeniu
//w javascript nie muszą być deklarowane wszystkie argumenty przy wywoływaniu funkcji!!!
al_choose_create=function create_alert(function_related,fn_event,default_al,arr_vars){  
window.arr_vars="";
switch (default_al) {
case 1:
$('#alert_menu_inf #al_contents_inf').html("Operacja została wykonana");    
$('#alert_menu_inf').css("top","40%");  
if(arr_vars){
    if(arr_vars['text']){
    $('#alert_menu_inf #al_contents_inf').html(arr_vars['text']);    
    } 
    if(arr_vars['top']){
    $('#alert_menu_inf').css("top",arr_vars['top']); 
    window.arr_vars=arr_vars['top'];
    }
}
$('#alert_menu_inf').css("width",$('#alert').css("width")); 
$('#alert_menu_inf').css("height",$('#alert').css("height")); 
$('#alert_menu_inf').css("visibility","visible");   
$('#alert_menu_inf').css("left","40%"); 
var p = $( "#alert_menu_inf" );
var position = p.position();
$('#alert_menu_inf').css("left",position.left+($('#alert_menu_inf').width()/2)+"px");
$('#alert_menu_inf').css("top",(position.top+($('#alert_menu_inf').height()/2)-$(document).scrollTop())+"px");
$('#alert_menu_inf').css("width","0px"); 
$('#alert_menu_inf').css("height","0px"); 
$("#alert_menu_inf" ).animate({
opacity: "1",
width:$('#alert_inf').css("width"), 
height:$('#alert_inf').css("height"),
left:position.left+"px",
top:(position.top-$(document).scrollTop())+"px"
}, 150, function() {

});    
break;
default:   
$('#alert_menu #al_contents_inf').html("Czy napewno chcesz wykonać tą operację?");    
$('#alert_menu').css("top","40%");  
if(arr_vars){
    if(arr_vars['text']){
    $('#alert_menu #al_contents_inf').html(arr_vars['text']); 
    } 
    if(arr_vars['top']){
    $('#alert_menu').css("top",arr_vars['top']); 
    window.arr_vars=arr_vars['top'];
    }
}    
al_fn_related=function_related;
al_fn_event=fn_event;
$('#alert_menu').css("width",$('#alert').css("width")); 
$('#alert_menu').css("height",$('#alert').css("height")); 
$('#alert_menu').css("visibility","visible");   
$('#alert_menu').css("left","40%"); 
var p = $( "#alert_menu" );
var position = p.position();
$('#alert_menu').css("left",position.left+($('#alert_menu').width()/2)+"px");
$('#alert_menu').css("top",(position.top+($('#alert_menu').height()/2)-$(document).scrollTop())+"px");
$('#alert_menu').css("width","0px"); 
$('#alert_menu').css("height","0px"); 
$("#alert_menu" ).animate({
opacity: "1",
width:$('#alert').css("width"), 
height:$('#alert').css("height"),
left:position.left+"px",
top:(position.top-$(document).scrollTop())+"px"
}, 150, function() {

});   
break;
}
}


init_global=function init_global(){
$("#al_yes").on("click", function() {  
al_fn_event.data.choose=true;  
al_fn_event.data.confirmed=true;
al_choose_cntrl();
window[al_fn_related](al_fn_event);
});
$("#al_no").on("click", function() {
al_fn_event.data.choose=false;  
al_fn_event.data.confirmed=true;
al_choose_cntrl();
window[al_fn_related](al_fn_event);
});
$("#al_ok_inf").on("click", function() { 
al_choose_cntrl(1);
});    
}
});
