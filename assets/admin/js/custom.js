 (function ($) {
    $(document).ready(function() {
		
        $.validate({
            modules : 'location, date, security, file',
            onModulesLoaded : function() {               
                return true;
                //window.location.href = admin_url + 'Staffs/Staff';
            }
        });
        $('#ks-maxlength-area').restrictLength($('#ks-maxlength-label'));

    });
 
})(jQuery);

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $(input).parent().siblings().find(".img-avatar").attr('src', e.target.result);
            // $('.img-avatar').attr('src', e.target.result);
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
    
$("#Val_Staff_Profile_Image, #Val_Displayimage, #Val_Displayicon, #Val_Wdisplayimage, #Val_Pfeaturedimage, #Val_Rfeaturedimage, #Val_Fdisplayimage, #Val_ProfileImage, #Val_Sdisplayimage").change(function(){
    readURL(this);
});
