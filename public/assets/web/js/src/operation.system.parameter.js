/**
 * 系统参数
 */
(function ($) {

    $.h.systemparameter = {
       hiddennext:function () {
           if(!$('#create_artno').switchbutton('options').checked){
               $('#prefis_name_length').hide();
           }else {
               $('#prefis_name_length').show();
           }
       },
        lookimg:function () {
            var f = $('#conpany_logo').next().find('input[type=file]')[0];
            if (f.files && f.files[0]){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#preview_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(f.files[0]);
            }
        },

    }
})(jQuery);