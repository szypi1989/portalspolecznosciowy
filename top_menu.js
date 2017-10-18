$(document).ready(function() {  
    var a=0;

$(window).resize(function() {
   a=1;
}); 
$('*').mousemove(function() {
    if(a==1){
        $('*').scrollTop(-100);  
        $('*').scrollLeft(-100);
    a=0;
}

}); 
}); 