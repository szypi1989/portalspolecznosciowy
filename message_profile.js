$(document).ready(function() {  
//szypi_code
//ZEROWANIE STRINGA //building_ids_message=NULL===ERROR(nie można tak czyścić zawartości)
var building_ids_message="";
var vars_build=1;
var last_click_but_options="";
var controler_options_but = new Array();
var ajax_change_control= new Array();
var allows_refresh_inbox=true;
var temp_refresh=true;
var timeout_msg;
var status_write_msg=false;
var check_write_actual=true;
var elements_bin=false;
var timeoutsnd;
var allows_ajax_send=false;
var break_actual_inbox=false; 
var break_actual_bin=false; 
var countes=0;
var array_vars_al = new Array();
controler_options_but['but_inbox'] = "#message_all_cont_text_inbox";
controler_options_but['but_outbox'] = "#message_all_cont_text_outbox"; 
controler_options_but['but_bin_message'] = "#message_all_cont_text_bin";
send_actual_write_msg(true);
//#KOD08 FUNKCJA PRODUKUJE ZDARZENIA DLA WSZYSTKICH ELEMENTÓW OKREŚLONEGO MODUŁU
function init_refresh_dom(){
    $('*').unbind();
    init_global();
    if(temp_refresh){
    ajax_refresh_data_inbox();
    ajax_check_write_actual();
    temp_refresh=false;
    }
    // #KOD01 ZDARZENIE ODPOWIEDZIALNE za przełączanie menu options w skrzynkach
    $("#message_all_cont_text_options .but_enabled_options_message,#message_all_cont_text_options .but_disabled_options_message").on("click", function() {
    ///budowanie zapytań ajax gdy zostało coś wyrzucone do kosza #KOD01A
    if(check_ajax_change_control($(this).attr("id"))){
    refresh_data_message($(this).attr("id"));
    }
    ///exit #KOD01A
    ///odkrywanie i ukrywanie wybranych elementów ,w zależności tego jaka skrzynka została wybrana #KOD01B
    if(last_click_but_options==$(this).attr("id")){
    $(".message_cont").css("visibility","hidden");
    $("#message_all_cont_text_options .but_enabled_options_message,#message_all_cont_text_options .but_disabled_options_message").attr("class","but_disabled_options_message");
    last_click_but_options="";
    }else{
    $("#message_all_cont_text_options .but_enabled_options_message,#message_all_cont_text_options .but_disabled_options_message").attr("class","but_disabled_options_message");
    $(".message_cont").css("visibility","hidden");
    $(controler_options_but[$(this).attr("id")]).css("visibility","visible");
    $("#"+$(this).attr("id")).attr("class","but_enabled_options_message");
    last_click_but_options=$(this).attr("id");
    }
    ///exit #KOD01B
    });
    //exit #KOD01
    
    // #KOD02 ZDARZENIE ODPOWIEDZIALNE ZA ODKRYCIE MENU RÓŻNYCH SKRZYNEK WIADOMOŚCI (SKRZYNKA ODBIORCZA,NADAWCZA I KOSZ)
    $("#but_message_all").on("click", function() {  
    if($("#message_all_cont_text").css("visibility")=="hidden"){
    $("#message_all_cont_text").css({
    "visibility":"visible"
    });
    $(".text_header").css({
    "color":"#10244A"
    });
    $(".username_friend_head").css({
    "color":"#425863"
    });
    $("#message_all_cont_text_options .but_enabled_options_message,#message_all_cont_text_options .but_disabled_options_message").attr("class","but_disabled_options_message");
    $(".message_cont").css("visibility","hidden");
    $("#message_all_cont_text_inbox").css("visibility","visible");
    $("#but_inbox").attr("class","but_enabled_options_message");
    $("#message_cont_text").css("opacity","0.35");
    if($("#block_for_all_message").children("div").attr("id")!='message_options_bin'){
    $("#block_for_all_message").css("height","40px");   
    }
    }else{
    $("div#message_all_cont_text").css({
    "visibility":"hidden"
    });
    $(".text_header").css({
    "color":"#425863"
    });  
    $(".username_friend_head").css({
    "color":"#10244A"
    });
    $("#message_all_cont_text_options .but_enabled_options_message,#message_all_cont_text_options .but_disabled_options_message").attr("class","but_disabled_options_message");
    $(".message_cont").css("visibility","hidden");
    $("#message_cont_text").css("opacity","1");
    if($("#block_for_all_message").children("div").attr("id")!='message_options_bin'){
    $("#block_for_all_message").css("height","0px");   
    }
    }
    });
    //exit #KOD02
    //#KOD03 ZDARZENIE ODPOWIEDZIALNE ZA ZMIANĘ WIDOKU WIADOMOŚCI AKTYWNYCH WSZYSTKIE/W KOSZU 
    $("#but_all_options_bin,#but_only_delete_options_bin").on("click", function() {  
    $("#message_options_bin .but_disabled_options_message_bin,#message_options_bin .but_enabled_options_message_bin").removeClass("but_enabled_options_message_bin").addClass("but_disabled_options_message_bin"); 
    $("#"+$(this).attr("id")).removeClass( "but_disabled_options_message_bin").addClass("but_enabled_options_message_bin");
    switch($(this).attr("id")) {
    case ("but_all_options_bin"):
    $(".message_cont_text").css("display","inline-block");
    elements_bin=false;
    break;
    case ("but_only_delete_options_bin"):
    elements_bin=true;
    //$(".message_cont_text.active").css("display","none");
    $(".message_cont_text.active").css("visibility","hidden");
    break;
    }
    });
    //exit #KOD03
    //#KOD04 zdarzenie przycisku jest odpowiedzialne za wrzucanie wiadomości do skrzynki wiadomości usuniętych i przywracanie
    $("#but_delete_marks,#but_delete_marks_bin").on("click", function() {  
    var i=0;
    allows_refresh_inbox=false;
    var name_this=$(this).attr("id");
    var delete_id = new Array();
    var switch_field_type = new Array();
    switch_field_type['but_delete_marks'] = "delete_message_main";
    switch_field_type['but_delete_marks_bin'] = "return_message_bin";
    switch_field_type['but_delete_marks_alert'] = "Zaznaczone wiadomości zostały wrzucone do kosza";
    switch_field_type['but_delete_marks_bin_alert'] = "Zaznaczone wiadomości zostały przywrócone z kosza";
    //ZEROWANIE STRINGA //building_ids_message=NULL===ERROR(nie można tak czyścić zawartości)
    building_ids_message="";
    $('.message_cont_text input:checkbox[name=mark_delete]').each(function() 
    {  
        if($(this).is(':checked')){
        building_ids_message+=$(".message_cont_text:not(.active) .id_message").eq(i).html().trim(); 
        building_ids_message+=",";
        delete_id[delete_id.length]=i;
        //closest:Opis funkcji: dla każdego elementu w zbiorze, mieć pierwszy element, 
        //który pasuje do selekcjonera przez sprawdzanie elementu samego i przemierzanie w górę dzięki jego przodkom w drzewie DOM.
       // $(this).closest(".message_cont_text").remove();
        }     
    ++i;
    });
    if(delete_id.length==0){
        //ustawianie wartości dla okna informacyjnego
        array_vars_al['text']="Żadna wiadomość nie została zaznaczona";
        array_vars_al['top']="30%";
        //uruchomienie okna informacyjnego
        al_choose_create(0,0,1,array_vars_al);  
        return;
    }
    //ZEROWANIE STRINGA //building_ids_message[0]="*"===ERROR(nie można tak czyścić zawartości)
    //building_ids_message=building_ids_message.substring(0,3);
    //operacja usuwania obiektów html powinna być wywołana dopiero po upewnieniu się czy zawartość została usunięta z bazy danych
    building_ids_message=building_ids_message.substring(0,building_ids_message.length-1);
    $.get("delete_message.php?field="+encodeURIComponent(switch_field_type[name_this])+"&ids_message="+encodeURIComponent(building_ids_message)+"&id_login="+$('input:hidden[name=id_login]').val().trim(),{ "_": $.now() },function(data){
    //usuwanie elementów html dotyczących wybranych wiadomości
    //podczas usuwania elementów indexy poszczególnych elementów zmieniają się dlatego zapis (delete_id[j])-j) powoduję usunięcie dobrych elementów
    if(((data.trim().substring(0,5)).toLowerCase())=="error"){
    alert(data);    
    }else{
        if(name_this=="but_delete_marks"){
            for (var j = 0; j <delete_id.length; j++) {
            $(".message_cont_text:not(.active)").eq((delete_id[j])-j).remove();  
            }
            if($(".message_cont_text").size()==0){
            $("#message_cont_text_contents").remove();
            }
        }else{
            for (var j = 0; j <delete_id.length; j++) {
            $(".message_cont_text:not(.active) input:checkbox[name=mark_delete]").eq((delete_id[j])-j).remove();    
            $(".message_cont_text:not(.active)").eq((delete_id[j])-j).addClass("active"); 
            }
            $('.options_message_delete input:checkbox[name=mark_delete]').attr('checked', false);
        }
    if(delete_id.length==i){
    $("#message_cont_text").children(".cont_but_options_marks_delete").remove();
    $('#message_cont_text').append(
    $('<div class="empty_message">brak wiadomości</div>', {
    })); 
    }
    if(name_this=="but_delete_marks"){
    $("#count_message").html($("#message_cont_text .message_cont_text").size());   
    }
    //ustawianie wartości dla okna informacyjnego
    array_vars_al['text']=switch_field_type[name_this+"_alert"];
    array_vars_al['top']="30%";
    //uruchomienie okna informacyjnego
    al_choose_create(0,0,1,array_vars_al);    
    ajax_change_control["but_bin_message"]=true;
    ajax_change_control["but_inbox"]=true;
    ajax_change_control["but_outbox"]=true;
    }
    allows_refresh_inbox=true;
    });
    //
    });
    //exit #KOD04
    //#KOD05 ZDARZENIA ZAZNACZAJĄCE/ODZNACZAJĄCE WSZYSTKIE ELEMENTY CHECBOX ( ELEMENTY WIADOMOŚCI ) 
    $("#but_mark_bin,#but_mark_all").on("click", function() { 
    $('.options_message_delete input:checkbox[name=mark_delete]').attr('checked', true);
    });
    $("#but_not_mark_bin,#but_not_mark_all").on("click", function() { 
    $('.options_message_delete input:checkbox[name=mark_delete]').attr('checked', false);
    });
    $("#but_mark_all_inbox").on("click", function() { 
    $('.options_message_delete input:checkbox[name=mark_delete_inbox]').attr('checked', true);
    });
    $("#but_not_mark_all_inbox").on("click", function() { 
    $('.options_message_delete input:checkbox[name=mark_delete_inbox]').attr('checked', false);
    });
    $("#but_mark_all_outbox").on("click", function() { 
    $('.options_message_delete input:checkbox[name=mark_delete_outbox]').attr('checked', true);
    });
    $("#but_not_mark_all_outbox").on("click", function() { 
    $('.options_message_delete input:checkbox[name=mark_delete_outbox]').attr('checked', false);
    });
    $("#but_mark_all_bin").on("click", function() { 
    $('.options_message_delete input:checkbox[name=mark_delete_bin]').attr('checked', true);
    });
    $("#but_not_mark_all_bin").on("click", function() { 
    $('.options_message_delete input:checkbox[name=mark_delete_bin]').attr('checked', false);
    });
    //exit #KOD05
    //#KOD06 zdarzenie przycisku jest odpowiedzialne za usuwanie grup wiadomości z różnych skrzynek
    $("#but_delete_marks_inbox,#but_delete_marks_outbox,#but_delete_marks_all_bin").on("click", function() {  
    var i=0;
    var name_this=$(this).attr("id");
    var delete_id = new Array();
    var switch_field_type = new Array();
    switch_field_type['but_delete_marks_inbox'] = "delete_message_inbox";
    switch_field_type['but_delete_marks_outbox'] = "delete_message_outbox";
    switch_field_type['but_delete_marks_all_bin'] = "return_message_all_bin";
    switch_field_type['but_delete_marks_inbox_field'] = ".message_cont_text_all_inbox";
    switch_field_type['but_delete_marks_outbox_field'] = ".message_cont_text_all_outbox";
    switch_field_type['but_delete_marks_all_bin_field'] = ".message_cont_text_all_bin";
    switch_field_type['but_delete_marks_inbox_parent'] = "#message_all_cont_text_inbox";
    switch_field_type['but_delete_marks_outbox_parent'] = "#message_all_cont_text_outbox";
    switch_field_type['but_delete_marks_all_bin_parent'] = "#message_all_cont_text_bin";
    switch_field_type['but_delete_marks_inbox_alert'] = "Zaznaczona konwersacja została wrzucona do kosza";
    switch_field_type['but_delete_marks_outbox_alert'] = "Zaznaczona konwersacja została wrzucona do kosza";
    switch_field_type['but_delete_marks_all_bin_alert'] = "Zaznaczona konwersacja została przywrócona z kosza";
    switch_field_type['but_delete_marks_inbox_checkbox'] = "mark_delete_inbox";
    switch_field_type['but_delete_marks_outbox_checkbox'] = "mark_delete_outbox";
    switch_field_type['but_delete_marks_all_bin_checkbox'] = "mark_delete_bin";
    //ZEROWANIE STRINGA //building_ids_message=NULL===ERROR(nie można tak czyścić zawartości)
    building_ids_message="";
    building_type_message="";
    $(switch_field_type[name_this+'_field']+" input:checkbox[name="+switch_field_type[name_this+"_checkbox"]+"]").each(function() 
    {  
        if($(this).is(':checked')){
        //konwersja url:wydobycie zawartośc zminnej $_GET['id_talk']
        //przykład (message_profile.php?surname=mariusz.szypóla.0&id_talk=37)==37
        if(name_this=="but_delete_marks_all_bin"){  
        building_ids_message+=(($(switch_field_type[name_this+'_field']+" .message_cont_info a.first_link").eq(i).attr('href').trim().split('id_talk')[1]).substring(1,($(switch_field_type[name_this+'_field']+" .message_cont_info a").eq(i).attr('href').trim().split('id_talk')[1]).length)).split('&')[0];  
        }else{
        building_ids_message+=($(switch_field_type[name_this+'_field']+" .message_cont_info a.first_link").eq(i).attr('href').trim().split('id_talk')[1]).substring(1,($(switch_field_type[name_this+'_field']+" .message_cont_info a").eq(i).attr('href').trim().split('id_talk')[1]).length);    
        }     
        building_ids_message+=",";
        delete_id[delete_id.length]=i;
        }     
    ++i;
    });

    if(delete_id.length==0){
        //alert("Żadna konwersacja nie została zaznaczona");
        //ustawianie wartości dla okna informacyjnego
        array_vars_al['text']="Żadna konwersacja nie została zaznaczona";
        array_vars_al['top']="30%";
        //uruchomienie okna informacyjnego
        al_choose_create(0,0,1,array_vars_al);   
        return;
    }
    building_ids_message=building_ids_message.substring(0,building_ids_message.length-1);
    $.get("delete_message.php?field="+encodeURIComponent(switch_field_type[name_this])+"&id_talk="+encodeURIComponent(building_ids_message)+"&id_login="+encodeURIComponent($('input:hidden[name=id_login]').val().trim()),{ "_": $.now() },function(data){
    //usuwanie elementów html dotyczących wybranych wiadomości
    //podczas usuwania elementów indexy poszczególnych elementów zmieniają się dlatego zapis (delete_id[j])-j) powoduję usunięcie dobrych elementów
    if(((data.trim().substring(0,5)).toLowerCase())=="error"){
    alert(data);    
    }else{
        for (var j = 0; j <delete_id.length; j++) {
        $(switch_field_type[name_this+"_field"]).eq((delete_id[j])-j).remove();  
        }
    if(delete_id.length==i){
    $(switch_field_type[name_this+'_parent']).children(".cont_but_options_marks_delete").remove();
    $(switch_field_type[name_this+'_parent']).append(
    $('<div class="empty_message">brak wiadomości</div>', {
    })); 
    }
    //alert(switch_field_type[name_this+"_alert"]);
    //ustawianie wartości dla okna informacyjnego
    array_vars_al['text']=switch_field_type[name_this+"_alert"];
    array_vars_al['top']="30%";
    //uruchomienie okna informacyjnego
    al_choose_create(0,0,1,array_vars_al);   
    ajax_change_control["but_bin_message"]=true;
    ajax_change_control["but_inbox"]=true;
    ajax_change_control["but_outbox"]=true;
    }
    });


    });
    //#KOD07 kod odpowiedzialny za wysyłanie wiadomości
    $("#send_message").on("click", function() {  
    var array_errors=new Array();
    var info_profile=new Array();
    var array_data=new Array();
    var array_result=new Array();
    var j;
    var build_string="";
    var error_data=false;
    check_write_actual=false;
    $.get("message_sql_send.php?field=send&id_login="+$('input:hidden[name=id_login]').val().trim()+"&id_friend="+($("#message_cont_text #id_friend").html().trim())+"&contents="+encodeURIComponent(($("#contents_message").val())),{ "_": $.now() },function(data){
    if(((data.trim().substring(0,5)).toLowerCase())=="error"){   
    array_errors=data.split(";");
    array_errors[0]=array_errors[0].substring(7,array_errors[0].length);
        for (var i=0;i<array_errors.length;i++){
            //ustawianie wartości dla okna informacyjnego
            array_vars_al['text']=array_errors[i].replace(new RegExp("#","g"),'\n');
            array_vars_al['top']="30%";
            //uruchomienie okna informacyjnego
            al_choose_create(0,0,1,array_vars_al);   
        }
    }else{
    array_data=data.split("#");
    j=0;
    //przetwarzanie danych,dzielenie i sprawdzanie zgodności dzielenia #021
    //#data:coś tam#data:drugie#mo#ść#data:nowy==(coś tam,drugie,mość,nowy)
    //porównać z użyciem typu danych JSON
    for (var i=1;i<array_data.length;i++){  
        if(array_data[i].trim().substring(0,5).toLowerCase()=="data:"){ 
            if(error_data){
               array_result[j]=build_string;
               error_data=false;
               j++;
            }
        array_result[j]=array_data[i].substring(5,array_data[i].length);
        j++;
        }else{
        build_string+="#"+array_data[i];   
        error_data=true;
        }  
    } 
    //exit #021
    build_message='<div class="message_cont_text no_get_back"><div class="message_cont_left"><div class="id_message">';
    build_message+=array_result[0].trim();
    build_message+='</div><div class="message_cont_image"><a href="';
    build_message+=array_result[4].trim();
    build_message+='"><img src="gallery/';
    build_message+=array_result[5].trim();
    build_message+='.jpg" height="40" width="40"></a></div></div><div class="message_cont_info"><div class="message_cont_info_head"><div class="date_name_head">';
    build_message+=array_result[2].trim();
    build_message+='</div><div class="date_time_head">';
    build_message+=array_result[3].trim();
    build_message+='</div></div><div class="date_cont_message"><div class="contents"><span>';
    build_message+=array_result[6].trim();
    build_message+='</span></div><div class="options_message_delete"><input name="mark_delete" type="checkbox"></div></div></div></div>';
    if($("#message_cont_text_contents").length==0){
    $( "#cont_send_message" ).after( $( "<div id='message_cont_text_contents'></div>") );
    $( ".empty_message" ).remove().
    $( "#block_for_all_message" ).remove().   
    $("#message_cont_text").append('<div id="but_options_marks_delete" class="cont_but_options_marks_delete"><div id="but_mark_all" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_all" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks" class="but_delete_marks"><span>usuń zaznaczone</span></div></div>');    
    }
    $('#message_cont_text_contents').prepend(
    $(build_message, {
    })); 
    $("#count_message").html($("#message_cont_text .message_cont_text").size());
    ajax_change_control["but_outbox"]=true;
    //alert("wiadomość została wysłana");
    } 
    check_write_actual=true;
    });
    });
    //exit #KOD07
    $("#contents_message").keydown(function() {
    clearTimeout(timeout_msg);
    timeout_msg=setTimeout(function(){
    send_actual_write_msg(true);
    status_write_msg=false;
    },5000);    
    });
    $("#contents_message").keyup(function() {
        if(!status_write_msg){
        send_actual_write_msg();
        }
    });
}
function send_actual_write_msg(order){
       // check_write_actual=false;
        if(!order){
        $.get("message_sql_actual_write_send.php?field=send_actual_write_msg&id_friend="+encodeURIComponent($("#id_friend").html().trim())+"&id_login="+encodeURIComponent($('input:hidden[name=id_login]').val().trim()),{ "_": $.now() },function(data){
        status_write_msg=true;
        });     
        }else{
        $.get("message_sql_actual_write_send_reset.php?field=send_actual_write_msg_reset&id_friend="+encodeURIComponent($("#id_friend").html().trim())+"&id_login="+encodeURIComponent($('input:hidden[name=id_login]').val().trim()),{ "_": $.now() },function(data){
        });        
        }
    }
//#KOD10 KONTROLA DOSTĘPU W PRZYPADKU ZAŻĄDANIA ODŚWIEŻENIA WIADOMOŚCI
function check_ajax_change_control(id_event){
        if(ajax_change_control[id_event]){
            return true;
        }
    }
    function refresh_data_message(id_event){
        var html_inbox_actual="";   
        switch (id_event) {
        case "but_inbox":   
        $.get("message_sql.php?field=refresh_inbox&id_login="+encodeURIComponent($('input:hidden[name=id_login]').val().trim()),{ "_": $.now() },function(data){       
        if(((data.trim().substring(0,5)).toLowerCase())=="error"){
            alert(data);    
            }else{
            $(".message_cont_text_all_inbox").remove();
                $('#message_all_cont_text_inbox .build_message').prepend(
                $(data.trim(), {
                }));         
            if(data.trim()!=""){                 
                    $("#but_options_marks_delete_inbox").remove();   
                    $("#message_all_cont_text_inbox div.empty_message").remove();
                    $(".message_cont_text_all_inbox").remove();
                    $('#message_all_cont_text_inbox .build_message').prepend(
                    $(data.trim(), {
                    }));     
                    $('#message_all_cont_text_inbox').append(
                    $('<div id="but_options_marks_delete_inbox" class="cont_but_options_marks_delete"><div id="but_mark_all_inbox" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_all_inbox" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks_inbox" class="but_delete_marks"><span>usuń zaznaczoną konwersacje</span></div></div>', {
                    }));
                }else{
                $(".message_cont_text_all_inbox").remove();
                $("#but_options_marks_delete_inbox").remove();
                }   
            init_refresh_dom();
            ajax_change_control["but_inbox"]=false;
            $("#num_rows_friends,#ml_num_no_get_back").html($(".message_cont_text_all_inbox.no_get_back").size());
            }    
        });
        break;
        case "but_outbox":
        $.get("message_sql.php?field=refresh_outbox&id_login="+encodeURIComponent($('input:hidden[name=id_login]').val().trim()),{ "_": $.now() },function(data){
            if(((data.trim().substring(0,5)).toLowerCase())=="error"){
            alert(data);    
            }else{
                if(data.trim()!=""){ 
                $("#but_options_marks_delete_outbox").remove();   
                $("#message_all_cont_text_outbox div.empty_message").remove();
                $(".message_cont_text_all_outbox").remove();
                $('#message_all_cont_text_outbox .build_message').prepend(
                $(data.trim(), {
                }));     
                $('#message_all_cont_text_outbox').append(
                $('<div id="but_options_marks_delete_outbox" class="cont_but_options_marks_delete"><div id="but_mark_all_outbox" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_all_outbox" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks_outbox" class="but_delete_marks"><span>usuń zaznaczoną konwersacje</span></div></div>', {
                }));
                }else{
                $(".message_cont_text_all_outbox").remove();
                $("#but_options_marks_delete_outbox").remove();
                }   
            init_refresh_dom();
            ajax_change_control["but_outbox"]=false;
            }
        });    
        break;
        case "but_bin_message":  
        $.get("message_sql.php?field=refresh_bin&id_login="+encodeURIComponent($('input:hidden[name=id_login]').val().trim()),{ "_": $.now() },function(data){
            if(((data.trim().substring(0,5)).toLowerCase())=="error"){
            alert(data);    
            }else{ 
                if(data.trim()!=""){        
                $("#but_options_marks_delete_all_bin").remove();    
                $("#message_all_cont_text_bin div.empty_message").remove();
                $(".message_cont_text_all_bin").remove();
                $('#message_all_cont_text_bin .build_message').prepend(
                $(data.trim(), {
                }));     
                $('#message_all_cont_text_bin').append(
                $('<div id="but_options_marks_delete_all_bin" class="cont_but_options_marks_delete"><div id="but_mark_all_bin" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_all_bin" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks_all_bin" class="but_delete_marks"><span>przywróć zaznaczoną konwersacje</span></div></div>', {
                }));
                }else{    
                $(".message_cont_text_all_bin").remove();    
                $("#but_options_marks_delete_all_bin").remove();
                }   
            init_refresh_dom();
            ajax_change_control["but_bin_message"]=false;
            }
        });    
        break;
        case "but_inbox_actual":
        if($("#id_friend").html()!=""){
            var build_on_checkbox_id="";
            var i=0;
            var class_msg="";
            allows_refresh_inbox=false;
            ///creating an array of selected messages using the checkbox 
            ///in order not to breach the selected checkbox in the Refresh
            $('.message_cont_text input:checkbox[name=mark_delete]').each(function() 
            {  
            if($(this).is(':checked')){
            build_on_checkbox_id+=$(".message_cont_text:not(.active) .id_message").eq(i).html().trim(); 
            build_on_checkbox_id+=",";
            }     
            ++i;
            });
            build_on_checkbox_id=build_on_checkbox_id.substring(0,build_on_checkbox_id.length-1);
            /////////////
            $.get("message_sql_actual.php?field=refresh_inbox_actual&id_login="+encodeURIComponent($('input:hidden[name=id_login]').val().trim())+"&id_friend="+encodeURIComponent($("#id_friend").html().trim())+"&ids_actual_checkbox="+encodeURIComponent(build_on_checkbox_id),{ "_": $.now() },function(data){
                if(((data.trim().substring(0,5)).toLowerCase())=="error"){
                alert(data);    
                }else{
                if(data.trim()!=""){
                    //#message_cont_text_temp zawiera aktulną listę wiadomości
                    //cel: dla celu uniknięcia niepotrzebnego odświerzania tych samych wiadomości
                    $("#message_cont_text_temp .message_cont_text").remove();                
                    $('#message_cont_text_temp').append(
                    $(data.trim(), {
                    })); 
                    $('#message_cont_text_temp .id_message').each(function(index,value) 
                    {  
                        if($('#message_cont_text .id_message').eq(0).html()==$('#message_cont_text_temp .id_message').eq(index).html()){
                            if(index==0){
                            break_actual_inbox=true;
                            }    
                        return false;
                        }else{
                            if($('#message_cont_text_temp').children().eq(index).hasClass( "no_get_back" )){
                            class_msg=" no_get_back";    
                            }else{
                            class_msg="";    
                            }
                        html_inbox_actual+="<div class='message_cont_text"+class_msg+"'>";
                        html_inbox_actual+=$('#message_cont_text_temp').children().eq(index).html();
                        html_inbox_actual+="</div>";
                        break_actual_inbox=false;
                        }               
                    });  
                        if(!break_actual_inbox){         
                        $("#but_options_marks_delete").remove();    
                        $("#message_cont_text div.empty_message").remove(); 
                        //$("#cont_send_message").remove();                   
                       // $(".message_cont_text").remove(); 
                       // $('#message_cont_text #message_cont_text_contents').prepend(html_inbox_actual); 
                        //  $('#message_cont_text #message_cont_text_contents').append(
                        //  $(data.trim(), {
                        //  }));     
                       // alert(html_inbox_actual);
                        if($('#message_cont_text_contents').length==0){
                            $('#message_cont_text').append('<div id="message_cont_text_contents"></div>');
                        }
                        $('#message_cont_text #message_cont_text_contents').prepend(
                        $(html_inbox_actual, {
                        })); 
                        $('#message_cont_text').append(
                        $('<div id="but_options_marks_delete" class="cont_but_options_marks_delete"><div id="but_mark_all" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_all" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks" class="but_delete_marks"><span>usuń zaznaczone</span></div></div>', {
                        }));
                        }
                    }else{
                    $(".message_cont_text").remove();    
                    $("#but_options_marks_delete").remove();
                    }  
                init_refresh_dom();
                ajax_change_control["but_inbox"]=true;
                $("#count_message").html($("#message_cont_text .message_cont_text").size());
                }
                allows_refresh_inbox=true;
            }); 
        };
        break;
        case "but_bin_actual":
        var build_on_checkbox_id="";
        var html_bin_actual=""; 
        var class_msg=""; 
        var i=0;
        countes=countes+1;
        allows_refresh_inbox=false;
        ///creating an array of selected messages using the checkbox 
        ///in order not to breach the selected checkbox in the Refresh
        $('.message_cont_text input:checkbox[name=mark_delete]').each(function() 
        {  
        if($(this).is(':checked')){
        build_on_checkbox_id+=$(".message_cont_text:not(.active) .id_message").eq(i).html().trim(); 
        build_on_checkbox_id+=",";
        }     
        ++i;
        });
        build_on_checkbox_id=build_on_checkbox_id.substring(0,build_on_checkbox_id.length-1);
        ////
        $.get("message_sql_actual.php?field=refresh_bin_actual&id_login="+encodeURIComponent($('input:hidden[name=id_login]').val().trim())+"&id_friend="+encodeURIComponent($("#id_friend").html().trim())+"&ids_actual_checkbox="+encodeURIComponent(build_on_checkbox_id),{ "_": $.now() },function(data){
            if(((data.trim().substring(0,5)).toLowerCase())=="error"){
            alert(data);    
            }else{
                if(data.trim()!=""){ 
                //#message_cont_text_temp zawiera aktulną listę wiadomości
                //cel: dla celu uniknięcia niepotrzebnego odświerzania tych samych wiadomości
                $("#message_cont_text_temp .message_cont_text").remove();                
                $('#message_cont_text_temp').append(
                $(data.trim(), {
                })); 
                    //warunki do odświerzania wiadomości:różnica w ilościach wiadomości podczas przesłania lub różnica w kluczach id według hierarhi
                   // $('#searchin').val($('#message_cont_text:not(:active) .id_message').size()+"countes"+$('#message_cont_text_temp:not(:active) .id_message').size());
                    if($('#message_cont_text .message_cont_text:not(.active)').size()!=$('#message_cont_text_temp .message_cont_text:not(.active)').size()){
                    break_actual_bin=false;    
                    }else{
                    $('#message_cont_text_temp .id_message').each(function(index,value) 
                    {  
                        if($('#message_cont_text .id_message').eq(0).html()==$('#message_cont_text_temp .id_message').eq(index).html()){
                            if(index==0){
                            break_actual_bin=true;
                            }    
                        return false;
                        }else{
                            if($('#message_cont_text_temp').children().eq(index).hasClass( "no_get_back" )){
                            class_msg=" no_get_back";    
                            }else{
                            class_msg="";    
                            }
                        html_bin_actual+="<div class='message_cont_text"+class_msg+"'>";
                        html_bin_actual+=$('#message_cont_text_temp').children().eq(index).html();
                        html_bin_actual+="</div>";
                        break_actual_bin=false;
                        }               
                    });     
                    }                 
                    //refresh inbox when break_actual_bin == false
                    if(!break_actual_bin){   
                    $("#but_options_marks_delete_bin").remove();    
                    $("#message_cont_text div.empty_message").remove();
                    $(".message_cont_text").remove();
                    $('#message_cont_text #message_cont_text_contents').append(
                    $(data.trim(), {
                    }));     
                    $('#message_cont_text').append(
                    $('<div id="but_options_marks_delete_bin" class="cont_but_options_marks_delete"><div id="but_mark_bin" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_bin" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks_bin" class="but_delete_marks"><span>przywróć zaznaczone</span></div></div>', {
                    }));
                    };
                }else{
                $(".message_cont_text").remove();    
                $("#but_options_marks_delete").remove();
                }  
            init_refresh_dom();
            ajax_change_control["but_bin_actual"]=false;
                //DOTYCZY OPCJI ELEMENTÓW W KOSZU,LUB WSZYSTKICH
                if(elements_bin){
                $(".message_cont_text.active").css("display","none");    
                }else{
                $(".message_cont_text").css("display","inline-block");    
                }
            $("#count_message").html($("#message_cont_text .message_cont_text:not('.active')").size());
            }
            allows_refresh_inbox=true;
        });          
        break;
        }  
    }
    //#KOD09 FUNKCJA KONTROLUJE STAN DIALOGU, WYWOŁUJĄC FUNKCJĘ ODŚWIEŻANIA WIADOMOŚCI CO PARĘ SEKUND
    function ajax_refresh_data_inbox(){
    var timeout;
        timeout=setTimeout(function(){
            if(allows_refresh_inbox){
                if($("#message_options_bin").length==0){
                refresh_data_message("but_inbox_actual");
                }else{
                refresh_data_message("but_bin_actual");    
                }
            ajax_refresh_data_inbox();
            }
        },3000);    
    }
    
    function ajax_check_write_actual(){
    var timeout;
        timeout=setTimeout(function(){
            if(check_write_actual){ 
            $.get("message_sql_actual_write.php?field=check_actual_write&id_friend="+encodeURIComponent($("#id_friend").html().trim())+"&id_login="+encodeURIComponent($('input:hidden[name=id_login]').val().trim()),{ "_": $.now() },function(data){
                if(data.trim()=="0"){    
                $("#status_actual_write").css("visibility","hidden");
                //użycie zmiennej globalnej gl_let_animation(global_function.js) w celu zatrzymania animacji funkcji wait_window_information
                gl_let_animation=false;
                }else{ 
                $("#status_actual_write").css("visibility","visible");
                gl_let_animation=true;
                gl_wait_window_information('.status_actual_write:eq(1)',"pisze");
                }
            });  
            ajax_check_write_actual();
            }
        },500);    
    }
init_refresh_dom();
});


