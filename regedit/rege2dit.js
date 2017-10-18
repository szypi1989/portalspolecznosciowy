$(document).ready(function() {  
var t=0;
var z=0;
$("#name").keydown(function() {
     z=setTimeout(function(){
    $('div.name p').text("sprawdzanie zgodności nazwy imienia");
    },800);
    t=setTimeout(function(){
    value=$("#name").attr('value');
    $('div.name p').load('registerjs.php?filter=name&name='+encodeURIComponent(value));
    },2000);
});
$("#surname").keydown(function() {
    z=setTimeout(function(){
    $('div.surname p').text("sprawdzanie zgodności nazwy nazwiska");
    },800);
    t=setTimeout(function(){
    value=$("#surname").attr('value');
    $('div.surname p').load('registerjs.php?filter=surname&surname='+encodeURIComponent(value));
    },2000);
});

$("#username").keydown(function() {
    z=setTimeout(function(){
    $('div.login p').text("sprawdzanie zgodności loginu");
    },800);
    t=setTimeout(function(){
    value=$("#username").attr('value');
    $('div.login p').load('registerjs.php?filter=login&login='+encodeURIComponent(value));
    },2000);
});


$("#passwordctrl").keydown(function() {
    z=setTimeout(function(){
    $('div.passwordctl p').text("sprawdzanie zgodności hasła");
    },800);
    t=setTimeout(function(){
    value=$("#password").attr('value');
    valuecmp=$("#passwordctrl").attr('value');
    $('div.passwordctrl span').eq(0).load('registerjs.php?filter=password&password='+encodeURIComponent(valuecmp));
    $('div.passwordctrl span').eq(1).load('registerjs.php?filter=compare_password&password='+ encodeURIComponent(value) + '&compare_password='+encodeURIComponent(valuecmp));
    },2000);
});
$("#password").keydown(function() {
    z=setTimeout(function(){
    $('div.password span').text("sprawdzanie zgodności hasła");
    },800);
    t=setTimeout(function(){
    value=$("#password").attr('value');
    $('div.password span').load('registerjs.php?filter=password&password='+encodeURIComponent(value));
    },2000);
});

$("#email").keydown(function() {
    z=setTimeout(function(){
    $('div.email p').text("sprawdzanie zgodności emaila");
    },800);
    t=setTimeout(function(){
    value=$("#email").attr('value');
    value.replace(new RegExp(" ","g"),'&nbsp;');
    $('div.email p').load('registerjs.php?filter=email&email='+encodeURIComponent(value));
    },2000);
});

$("#but_regedit").on("click", function() {
if(check_control_data()){$('#form_regedit').submit();}
});
function check_control_data(){
username=$.trim($("#username").val());
password=$.trim($("#password").val());
passwordctrl=$.trim($("#passwordctrl").val());
name=$.trim($("#name").val());
surname=$.trim($("#surname").val());
email=$.trim($("#email").val());
sexa=$("#sex[value='1']").is(':checked');
sexb=$("#sex[value='2']").is(':checked');
sex=false;
if(sexa || sexb){
    sex=true;
} 
return_value=true;
if(username==""){
alert("pole nazwa użytkownika musi być wypełnione");
return_value=false;
}
if(password==""){
alert("pole hasła musi być wypełnione");
return_value=false;
}
if(passwordctrl==""){
alert("pole ponownego wpisania hasła musi być wypełnione");
return_value=false;
}
if(name==""){
alert("pole imię musi być wypełnione");
return_value=false;
}
if(surname==""){
alert("pole nazwisko musi być wypełnione");
return_value=false;
}
if(email==""){
alert("pole email musi być wypełnione");
return_value=false;
}
if(!sex){
alert("pole wyboru płci musi być zaznaczone");
return_value=false;
}

return return_value;
}

}); 