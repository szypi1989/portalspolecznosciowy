    $(document).ready(function() {  



var i=0;
var j;
var iclick1=0;
var iclick2=1;
var temp2;
var imagenr;
var temp;
var waga=0;
var arrowok;

$(".menuli").eq(0).click(function() {
    showbigimage(0);
});
$(".menuli").eq(2).click(function() {
   //   $('html, body').scrollTop($(window).height());
  $('html, body').animate({ 
                          
                  "scrollTop": $(window).height()        
},500, 

	function() { 
  //////////////////////
   $('#email').animate({ 
                          
                  "opacity": 1        
},200, 

	function() { 
  
   $('#email').animate({ 
                          
                  "opacity": 0.5        
},200, 

	function() { 
     $('#email').animate({ 
                          
                  "opacity": 1        
},200, 

	function() { 
  
   $('#email').animate({ 
                          
                  "opacity": 0.5        
},200, 

	function() { 
  
});
});
});
});
  /////////////////////////////////////
});
});


function showbigimage(iclick2){
arrowok=0;
$("#arrows").css("visibility",'hidden');
 //$('#blacklight').css({"height":$(window).height()+"px","width":"100px"});
 //alert(/$(document).height());
             $('html, body').css("scrollTop","0");
     $("#lightbox img").remove(); 
    $("#lightbox").css({"height":"0px","width":"0px"});
 
    imagenr=iclick2;
 
  $("#lightbox").css("top",$('html, body').scrollTop()+$(window).height()/2-$("#lightbox").height()/2+"px");

$("#lightbox").css("left",$('html, body').scrollLeft()+$(window).width()/2-$("#lightbox").width()/2+"px");
  
$("#lightbox").css("visibility",'visible');

temp=(parseInt(setdisplayobject(iclick2)[0]) > $(window).width() ) ? '0px' : ($(window).width()-parseInt(setdisplayobject(iclick2)[0]))/2; 

if(setdisplayobject(iclick2)[0] < $(window).width()){
    
    $("#arrows").css("width",setdisplayobject(iclick2)[0]);
     $("#arrows").css("left",($(window).width()-$("#arrows").width())/2);
}else{
     $("#arrows").css("width",$(window).width() + "px");
}

temp2=(setdisplayobject(iclick2)[1]>$(window).height())? $('html, body').scrollTop():$('html, body').scrollTop()+$(window).height()/2-setdisplayobject(iclick2)[1]/2;
$("#lightbox").animate({ 

		"height":setdisplayobject(iclick2)[1] + "px",
                "width": setdisplayobject(iclick2)[0] + "px",
                "top":temp2,
                "left": temp
	},300, 

	function() { 

temp = $("<img>");
$("#lightbox .lightimage").append(temp);
    
$("#lightbox img").not(".arrowl").attr({'src':'image' + iclick2 + 'big.jpg'})
                      .load(function() {
                      
                          $("#info").css("top",$(window).height()/2 + $('html, body').scrollTop() - $("#info").height()/2 + "px");
                             $("#center2").css("height",parseInt(setdisplayobject(iclick2)[1]) + $('html, body').scrollTop()-400 + "px");
                      $("#info").css("visibility",'visible');
                       $("#blacklight").css({"visibility" : "visible","height":$(document).height()+"px","width":$(window).width()+"px"});
                      
                      $("#info").animate({ 
                          
                  "opacity": "0.8"        
},1000, 

	function() { 
            
                              $("#info").animate({ 
                                  
              	"opacity": "0"                
},1500, 

	function() { 
            
           arrowok=1;
            
               });
            
               });
            
                     });

});

 
   $("#arrows").css("top",$(window).height()/2 + $('html, body').scrollTop() - $("#arrows").height()/2 +  "px");

  

}

function setdisplayobject(index){
    switch(index)
{
    case 0:
     
       return ["1035","1621"];
      
        break;
        case 1:
             return ["1036","1276"];
 
        break;
           case 2:
             return ["500","500"];
 
        break;
         case 3:
             return ["600","450"];
  break;
    case 4:
             return ["600","450"];
  break;
   case 5:
             return ["800","520"];
  break;
}

};
$(".link a").hover(
 function(e)
{
   
   $(this).css("color",'#FFF'); 
     $(this).parent().next().children().css("color",'#ccc'); 
}, function() { 
   $(this).css("color",'#CCC');
 $(this).parent().next().children().css("color",'#444'); 
});

$(".block").hover(
 function(e)
{
   
   $("#arrows .arrowl").css("visibility",'hidden'); 
}, function() { 

});
$("#lightbox .lightimage").mousemove(function(e) {

if(arrowok==1){
  $("#arrows").css("top",$(window).height()/2 + $('html, body').scrollTop() - $("#arrows").height()/2 +  "px");  
    if(e.pageX<$(window).width()/2){
     
       $(".arrowl").eq(0).css("visibility",'visible');  
        $(".arrowl").eq(1).css("visibility",'hidden');
    }else{
      $(".arrowl").eq(1).css("visibility",'visible');  
  $(".arrowl").eq(0).css("visibility",'hidden');
    }
}
});
$(".arrowl").click(function() {
    /////////
    $('html, body').scrollTop("0");
    
    /////////
       iclick1=".arrowl";
iclick2=$(iclick1).index(this);
if(iclick2){

    imagenr++;
    showbigimage(imagenr);
}else{

    if(imagenr>0){
  
     imagenr--;
    showbigimage(imagenr); 
    }
    
}

});

$("#lightbox").dblclick(function() {
 $("#blacklight").css({"visibility" : "hidden","height":"0px","width":"0px"});
    $("#lightbox").animate({ 

		"height":"0px",
                "width": "0px"
                
	},300, 

	function() { 
$("#lightbox").css("visibility","hidden");

 $("#info").css("visibility",'hidden');
$(".arrowl").css("visibility",'hidden');
    $("#arrows").css("left",'0px');
      $("#arrows").css("top",'0px');
     $("#lightbox").css("width",'0px');
     $("#lightbox").css("height",'0px');
       $("#center2").css("height",'auto');
      $("#lightimage").css("height",'0px');
      $("#lightbox img").remove(); 
      
});
  


     
});


    
$('.image').click(function() {
    iclick1=".image";
iclick2=$(iclick1).index(this);
iclick2+=waga;

showbigimage(iclick2);


});

$('div#arrowright').click(function() {
   
waga++;
    //////////////////////////animation opacity
$("div#image0").animate({ 

		"opacity": "0" 

	},100, 

	function() { 

i++;

$('div#image0').css("background-image","url(image" + i + ".jpg)");//2
$("div#image0").animate({ 

		"opacity": "1" 

	},100, 

	function() { 

	});

	});
       
        $("div#image0").animate({ 

		"opacity": "0" 

	}, 100, 

	function() { 

   $('div#image1').css("background-image","url(image" + (i+1) + ".jpg)");//3
$("div#image1").animate({ 

		"opacity": "1" 

	},100, 

	function() { 

	});

	});

	});
   

$('div#arrowleft').click(function() {
    if(i>0){
  waga--;
   $("div#image0").animate({ 

		"opacity": "0" 

	},100, 

	function() { 

i--;

$('div#image0').css("background-image","url(image" + i + ".jpg)");


$("div#image0").animate({ 

		"opacity": "1" 

	},100, 

	function() { 

	});

});

$("div#image1").animate({ 

		"opacity": "0" 

	},100, 

	function() { 
           
    
$('div#image1').css("background-image","url(image" + (i+1) + ".jpg)");


$("div#image1").animate({ 

		"opacity": "1" 

	},100, 

	function() { 

	});

});

    }
	});


$('#contimage').mousemove(function(e){

if($.browser.mozzilla==true ) {
$('.arrow').eq(iclick2).css("margin-left","auto");
$('.arrow').eq(iclick2).css("opacity","0.8");
			
}

});
    
    
    
    


$('.arrow').hover(
 function(e)
{
if ($.browser.mozilla==true) {
       

 
	iclick1=".arrow";
iclick2=$(iclick1).index(this);
j=(iclick2 == 1) ? '10px' : '-10px'; 

$('.arrow').eq(iclick2).animate({ 

	 "margin-left": j,
         "opacity":"1"

	},50, 

	function() { 

	});
	 }		
}, function() { 
    if ($.browser.mozilla==true) {
$('.arrow').eq(iclick2).css("margin-left","auto");
$('.arrow').eq(iclick2).css("opacity","0.8");
    }
});







	    }); 