$(document).ready(function() {  
var t=0;
var z=0;
var action_get=true;
$("#name").keydown(function() {
    z=setTimeout(function(){
    $('div.name p').text("sprawdzanie zgodności nazwy imienia");
    },800);
    t=setTimeout(function(){
    value=$("#name").attr('value');
    $.get('registerjs.php?filter=name&name='+encodeURIComponent(value), function(data){
    if(data.substring(0,6).trim()=="error"){
    $('#name').css("background-color","#f00");
    $('div.name p').html(data.substring(7,data.length));  
    }else{   
    $('div.name p').html(data);    
    $('#name').css("background-color","#fff");
    }
    });
    },2000);
});
$("#surname").keydown(function() {
    z=setTimeout(function(){
    $('div.surname p').text("sprawdzanie zgodności nazwy nazwiska");
    },800);
    t=setTimeout(function(){
    value=$("#surname").attr('value');
    $('div.surname p').load('registerjs.php?filter=surname&surname='+encodeURIComponent(value));
    $.get('registerjs.php?filter=surname&surname='+encodeURIComponent(value), function(data){
    if(data.substring(0,6).trim()=="error"){
    $('#surname').css("background-color","#f00");
    $('div.surname p').html(data.substring(7,data.length));  
    }else{   
    $('div.surname p').html(data);    
    $('#surname').css("background-color","#fff");
    }
    });
    },2000);
});

$("#username").keydown(function() {
    z=setTimeout(function(){
    $('div.login p').text("sprawdzanie zgodności loginu");
    },800);
    t=setTimeout(function(){
    value=$("#username").attr('value');
    $.get('registerjs.php?filter=login&login='+encodeURIComponent(value), function(data){
    if(data.substring(0,6).trim()=="error"){
    $('#username').css("background-color","#f00");
    $('div.login p').html(data.substring(7,data.length));  
    }else{   
    $('div.login p').html(data);    
    $('#username').css("background-color","#fff");
    }
    });
    },2000);
});
    
$("#passwordctrl").keydown(function() {
    $('div.passwordctrl span').eq(0).html("sprawdzanie zgodności hasła"); 
    t=setTimeout(function(){
    value=$("#password").attr('value');
    valuecmp=$("#passwordctrl").attr('value');
    $.get('registerjs.php?filter=password&password='+encodeURIComponent(valuecmp), function(data){  
    if(data.substring(0,6).trim()=="error"){
    $('#passwordctrl').css("background-color","#f00");
        $.get('registerjs.php?filter=compare_password&password='+ encodeURIComponent(value) + '&compare_password='+encodeURIComponent(valuecmp), function(datacompare){ 
        $('div.passwordctrl span').eq(0).html((data.substring(7,data.length))+"."); 
        if(datacompare.substring(0,6).trim()=="error"){
        $('div.passwordctrl span').eq(1).html(datacompare.substring(7,datacompare.length));     
        }else{
        $('div.passwordctrl span').eq(1).html(datacompare);      
        }  
        });   
    }else{
        $('div.passwordctrl span').eq(0).html((data)+"."); 
        $.get('registerjs.php?filter=compare_password&password='+ encodeURIComponent(value) + '&compare_password='+encodeURIComponent(valuecmp), function(datacompare){ 
            if(datacompare.substring(0,6).trim()=="error"){
            $('div.passwordctrl span').eq(0).html((data)+" ale ");       
            $('div.passwordctrl span').eq(1).html("h"+datacompare.substring(8,datacompare.length));  
            $('#passwordctrl').css("background-color","#f00");    
            }else{
            $('div.passwordctrl span').eq(1).html(datacompare);    
            $('#passwordctrl').css("background-color","#fff");    
            }
        }); 
    }

    });
    },2000);
});
$("#password").keydown(function() {
    z=setTimeout(function(){
    $('div.password span').text("sprawdzanie zgodności hasła");
    },800);
    t=setTimeout(function(){
    value=$("#password").attr('value');
    $.get('registerjs.php?filter=password&password='+encodeURIComponent(value), function(data){
    if(data.substring(0,6).trim()=="error"){
    $('#password').css("background-color","#f00");
    $('div.password span').html(data.substring(7,data.length)); 
        if($("#passwordctrl").val()!=""){
        $("#passwordctrl").keydown();    
        }
    }else{   
    $('div.password span').html(data);    
    $('#password').css("background-color","#fff");
        if($("#passwordctrl").val()!=""){
        $("#passwordctrl").keydown();    
        }
    }
    });    
    },2000);
});

$("#email").keydown(function() {
    z=setTimeout(function(){
    $('div.email p').text("sprawdzanie zgodności emaila");
    },800);
    t=setTimeout(function(){
    value=$("#email").attr('value');
    value.replace(new RegExp(" ","g"),'&nbsp;');
        $.get('registerjs.php?filter=email&email='+encodeURIComponent(value), function(data){
        if(data.substring(0,6).trim()=="error"){
        $('#email').css("background-color","#f00");
        $('div.email p').html(data.substring(7,data.length));  
        }else{   
        $('div.email p').html(data);    
        $('#email').css("background-color","#fff");
        }
        });
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
if($('#acceptance').attr("checked")!="checked"){
alert("zaakceptuj regulamin aby wysłać formularz");    
return_value=false;    
}
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