$(document).ready(function() {  
var t=0;
var z=0;
$("#username").keydown(function() {
    clearTimeout(t);
    clearTimeout(z);
});

$("#username").keyup(function() {
    z=setTimeout(function(){
    $('div.login p').text("sprawdzanie zgodności loginu");
    },800);
    t=setTimeout(function(){
    value=$("#username").attr('value');
    $('div.login p').load('registerjs.php?filter=login&login='+value);
    },2000);
});

$("#passwordctrl").keydown(function() {
    clearTimeout(t);
    clearTimeout(z);
});

$("#passwordctrl").keyup(function() {
    z=setTimeout(function(){
    $('div.passwordctl p').text("sprawdzanie zgodności hasła");
    },800);
    t=setTimeout(function(){
    value=$("#password").attr('value');
    valuecmp=$("#passwordctrl").attr('value');
    $('div.passwordctrl p').eq(0).load('registerjs.php?filter=password&password='+valuecmp);
    $('div.passwordctrl p').eq(1).load('registerjs.php?filter=compare_password&password='+ value + '&compare_password='+valuecmp);
    },2000);
});

$("#password").keydown(function() {
    clearTimeout(t);
    clearTimeout(z);
});

$("#password").keyup(function() {
    z=setTimeout(function(){
    $('div.password p').text("sprawdzanie zgodności hasła");
    },800);
    t=setTimeout(function(){
    value=$("#password").attr('value');
    $('div.password p').load('registerjs.php?filter=password&password='+value);
    },2000);
});

$("#email").keydown(function() {
    clearTimeout(t);
    clearTimeout(z);
});

$("#email").keyup(function() {
    z=setTimeout(function(){
    $('div.email p').text("sprawdzanie zgodności emaila");
    },800);
    t=setTimeout(function(){
    value=$("#email").attr('value');
    $('div.email p').load('registerjs.php?filter=email&email='+value);
    },2000);
});
    
//$("body").on("keyup","#username", function(){
 //  clearTimeout(t);
 //  t=setTimeout(function(){
 //  value=$("#username").attr('value');
 //  $('div.login p').load('registerjs.php?filter=login&login='+value);    
//   },2000);
//});

//$("body").on("keyup" && "mouseleave","#password", function(){
 //   value=$("#password").attr('value');
 //   $('div.password p').load('registerjs.php?filter=password&password='+value);
//});




}); 