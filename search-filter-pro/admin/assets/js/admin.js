(function ( $ ) {
	"use strict";

	$(function () {
	
		var meta_prefs_action_name = "meta_prefs_set";

		// Place your administration-specific JavaScript here
		var $metabox_prefs = $('#screen-meta #screen-options-wrap .metabox-prefs');
		var $screen_options_link = $('#screen-options-link-wrap');
		var $screen_options_wrap = $('#screen-options-wrap');
		
		//then metabox not found
		if($metabox_prefs.length==0)
		{
			$metabox_prefs = $('#screen-meta #screen-options-wrap h5').after('<div class="metabox-prefs"><label for="welcome-hide" class="handle-custom-prefs"><input  data-target="#search-filter-welcome-panel" name="welcome-hide" type="checkbox" id="welcome-hide" value="welcome">Welcome</label><br class="clear" /></div>');
		}
		$metabox_prefs.find('.clear').before('<label for="welcome-hide" class="handle-custom-prefs"><input  data-target="#search-filter-welcome-panel" name="welcome-hide" type="checkbox" id="welcome-hide" value="welcome">Welcome</label>');
		
		//initialise checked state by seeing if the target has hidden class
		$metabox_prefs.find(".handle-custom-prefs input[type=checkbox]").each(function()
		{
			var $this = $(this);
			var hide_element = $(this).attr('data-target');
			
			if(!$(hide_element).hasClass("hidden"))
			{
				$this.attr("checked", "checked");
			}
		
		});
		
		//
		$metabox_prefs.find(".handle-custom-prefs input[type=checkbox]").off("change"); //remove existing handlers
		$metabox_prefs.find(".handle-custom-prefs input[type=checkbox]").change(function()
		{
			var hide_element = $(this).attr('data-target');
			var show_option_value = 0;
			
			if(this.checked) {
				$(hide_element).removeClass("hidden");
				show_option_value = 1;
			}
			else
			{
				$(hide_element).addClass("hidden");
			}
			
			//run ajax to set option
			$.post( ajaxurl, {show: show_option_value, action: meta_prefs_action_name});/*.done(function(data)
			{//don't do anything
				
				if(data)
				{
					alert(data);
				}
			});*/
			
		});
		
		$(".handle-dismiss-button").click(function()
		{
			var hide_element = $(this).attr('data-target');
			var show_option_value = 0;
			
			//hide element
			$(hide_element).addClass("hidden");
			
			//uncheck checkbox
			$metabox_prefs.find('.handle-custom-prefs input[type=checkbox][data-target="'+hide_element+'"]').removeAttr("checked");
			
			//add user feedback that the element has been hidden and can be shown again by adding flicker effect to the screen options button 
			//$screen_options_link.css('background-color', '#f00');
			if($screen_options_wrap.css("display")=="none")
			{//don't run if the screen options are open
			
				$screen_options_link.delay(200).queue(function(next){
					$(this).addClass('highlight');
					next();
				}).delay(600).queue(function(next){
					$(this).removeClass('highlight');
					next();
				});
			}
				
			//run ajax to set option
			$.post( ajaxurl, {show: "0", action: meta_prefs_action_name});
		});
		
		
	});

}(jQuery));