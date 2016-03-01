jQuery(document).ready(function() {
    
    
    jQuery("#generate_shortcode_button").click(function () {
        UpdateShortcode();
    });
    
    
    
    
});

function UpdateShortcode(){
    var facebook_page_id = jQuery("#facebook_page_id").val();
    var only_media = jQuery("#only_media").val();
    var stream_theme = jQuery("#stream_theme").val();
    var stream_limit = jQuery("#stream_limit").val();
    var cols_limit = jQuery("#cols_limit").val();
    var margin_bottom = jQuery("#margin_bottom").val();
    var boxes_padding = jQuery("#boxes_padding").val();
    var only_owners_posts = jQuery("#only_owner_posts").val();
    
    var complete_code = '[facebook-stream limit="'+stream_limit+'" only_owners_posts="'+only_owners_posts+'" cols="'+cols_limit+'" theme="'+stream_theme+'" padding="'+boxes_padding+'" margin_bottom="'+margin_bottom+'" fb_page_id="'+facebook_page_id+'" hide_no_media="'+only_media+'"]';
    jQuery("#shortcode_text_area").text(complete_code);

};