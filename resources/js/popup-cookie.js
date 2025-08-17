 jQuery(window).load(function() {
    if(Cookies.get('RazerTOSAgreed') === undefined) {
            if($(document).width() < 600){
                $('#cookies_popup_container').css('right', '2.5%').delay(1000).show();
                console.log("<600");
            }else{
                $('#cookies_popup_container').show().delay(1000).css('right', '2.5%');
                 console.log(">600");
            }
    }

    

    // $('.close_btn').click(function(){
    //         if($(document).width() < 600){$('#cookies_popup_container').hide();}else{
    //             $('#cookies_popup_container').css('right', '-190%');
    //              console.log("close");
    //         }
    // });
    $('#agree_btn_container').click(function(){
            if($(document).width() < 600){$('#cookies_popup_container').hide();}else{
                $('#cookies_popup_container').css('right', '-190%');
                  console.log("agree");
            }
      Cookies.set('RazerTOSAgreed', 'true', { expires: 1825 });
    });
   
});