(function ( $ ) {
	"use strict";

	$(function () {
		/* metabox processing :) */
		
		//thickbox mods
		jQuery.fn.SfPopupMeta = function(options)
		{
			var defaults = {
				startOpened: false
			};
			var opts = jQuery.extend(defaults, options);
			
			//loop through each item matched
			$(this).each(function()
			{
				var item = $(this);
				
				var $widget_ref = $(this).closest('.widget');
				var $meta_options_list = $widget_ref.find('.meta_options_list');
				
				
				function getMetaKeyValues(meta_key, $target)
				{
					
					var meta_prefs_action_name = "get_meta_values";
					
					$.post( ajaxurl, {meta_key: meta_key, action: meta_prefs_action_name}).done(function(data)
					{//don't do anything
						
						if(data)
						{
							$target.html(data);
						}
					});
					
				}
				
				function getMetaKey($this)
				{
					/* ***************** */
					var $the_field = $this.closest(".widget");
					var $meta_key_manual_toggle  = $the_field.find(".meta_key_manual_toggle");
					var $meta_key_manual  = $the_field.find(".meta_key_manual");
					var $meta_key = $the_field.find(".meta_key");
					
					var meta_key_value = $meta_key.val();
					
					if($meta_key_manual_toggle.is(":checked"))
					{
						meta_key_value = $meta_key_manual.val();
					}
					
					if(meta_key_value!="")
					{
						return meta_key_value;
					}
					else 
					{
						return "";
					}
				}
				
				item.click(function()
				{
					//add overlay
					var $overlay = jQuery('<div/>', {
						id: 'foo',
						'class': 'sf-thickbox-overlay',
					}).appendTo('body');
					
					//add popup div
					var $popup = jQuery('<div/>', {
						'class': 'sf-thickbox',
					}).appendTo('body');
					
					var popup_hd_str = "";
					popup_hd_str += '<div class="sf-thickbox-title">';
					popup_hd_str += '<div class=".sf-ajax-window-title"></div>';
					popup_hd_str += '<div class="sf-thickbox-title-inner">Add Options</div>';
					popup_hd_str += '<div class="sf-close-ajax-window">';
					popup_hd_str += '<a href="#" id="TB_closeWindowButton" name="TB_closeWindowButton"></a>';
					popup_hd_str += '<div class="sf-close-icon"></div>';
					popup_hd_str += '</div>';
					popup_hd_str += '</div>';
					
					/* init content */
					var meta_key = getMetaKey($(this));
					
					var popup_content_str = "";
					popup_content_str += '<div class="sf-ajax-content">';
					popup_content_str += '<p>Found the following values in use for meta key `<strong>'+meta_key+'</strong>`</p>';
					popup_content_str += '<p class="sf-thickbox-content">';
					popup_content_str += '<span class="spinner" style="display: block; float:left; text-align:left;"></span>';
					popup_content_str += '</p>';
					popup_content_str += '</div>';
					
					var popup_ft_str = "";
					popup_ft_str += '<div class="sf-thickbox-frame-toolbar">';
					popup_ft_str += '<div class="sf-thickbox-toolbar">';
					popup_ft_str += '<p><a href="#" class="button-secondary sf-meta-select-none">Select None</a> <a href="#" class="button-secondary sf-meta-select-all">Select All</a> <a href="#" class="button-primary sf-thickbox-action-right sf-meta-add-options">Add Options</a> <label class="replace-meta-options-label">Replace current options? &nbsp;<input type="checkbox" class="replace-options-checkbox" /></label></p>';
					popup_ft_str += '</div>';
					popup_ft_str += '</div>';
					
					var $popup_header = $(popup_hd_str);
					var $popup_content = $(popup_content_str);
					var $popup_footer = $(popup_ft_str);
					
					$popup.append($popup_header);
					$popup.append($popup_content);
					$popup.append($popup_footer);
					
					var $close_button = $popup_header.find(".sf-close-ajax-window");
					$close_button.click(function()
					{
						$('.sf-thickbox-overlay').remove();
						$popup.remove();
						
					});
					
					var $select_none_button = $popup_footer.find(".sf-meta-select-none");
					$select_none_button.click(function()
					{
						$popup_content.find(".sf-thickbox-content input[type=checkbox]").each(function(){
						
							this.checked = false;							
						});
						
						return false;
						
					});
					
					var $select_all_button = $popup_footer.find(".sf-meta-select-all");
					$select_all_button.click(function()
					{
						$popup_content.find(".sf-thickbox-content input[type=checkbox]").each(function(){
						
							this.checked = true;							
						});
						
						return false;
						
					});
					
					var $add_options_button = $popup_footer.find(".sf-meta-add-options");
					$add_options_button.click(function()
					{
						var $no_sort_label = $widget_ref.find(".no_sort_label");
						var $replace_options_checkbox = $popup_footer.find('.replace-options-checkbox');
						var $checked_options = $popup_content.find(".sf-thickbox-content input[type=checkbox]:checked");
						
						if($checked_options.length>0)
						{
							if($replace_options_checkbox.prop('checked'))
							{
								var $meta_options = $meta_options_list.find("li:not(.meta-option-template)");
								
								$meta_options.each(function(){
									
									var $meta_option = $(this);
									
									$meta_option.slideUp("fast",function(){
										$meta_option.remove();
										
										if($meta_options_list.find("li:not(.meta-option-template)").length==0)
										{
											$no_sort_label.show();
										}
									});
								});
							}
							
							
							$checked_options.each(function(){
								
								var optionsId = $meta_options_list.find("li:not(.meta-option-template)").length;
								
								var $meta_option = $meta_options_list.find("li.meta-option-template").clone();
								$meta_option.removeClass("meta-option-template");
								
								setOptionVars($meta_option, $widget_ref.attr('data-widget-id'), optionsId);
								$meta_options_list.append($meta_option);
								$meta_option.slideDown("fast");
								
								$meta_option.find("input[type=text]").val($(this).val());
								
								initMetaOptionControls($meta_option, $meta_options_list, $no_sort_label);
								
								$no_sort_label.hide();
								
								
								this.checked = true;					
							});
						}
						
						//hide popup
						$('.sf-thickbox-overlay').remove();
						$popup.remove();
						
						return false;
						
					});
					
					getMetaKeyValues(meta_key, $popup_content.find(".sf-thickbox-content"));
					
					
					return false;
					
					
				});
				
			});
		}
		
		jQuery.fn.SfPopupTax = function(options)
		{
			var defaults = {
				startOpened: false
			};
			var opts = jQuery.extend(defaults, options);
			
			//loop through each item matched
			$(this).each(function()
			{
				function getTaxonomyTerms(taxonomy_name, taxonomy_ids, $target)
				{
					var action_name = "get_taxonomy_terms";
					
					$.get( ajaxurl+"?action="+action_name+"&taxonomy_name="+taxonomy_name+"&taxonomy_ids="+taxonomy_ids ).done(function(data)
					{//don't do anything
						
						if(data)
						{
							$target.html(data);
						}
					});
				}
				
				var item = $(this);
				
				if(item.attr("data-init-popup") != 1)
				{
					item.attr("data-init-popup", 1);
										
					item.click(function()
					{
						if(!item.hasClass("disabled"))
						{
							//add overlay
							var $overlay = jQuery('<div/>', {
								id: 'foo',
								'class': 'sf-thickbox-overlay',
							}).appendTo('body');
							
							//add popup div
							var $popup = jQuery('<div/>', {
								'class': 'sf-thickbox',
							}).appendTo('body');
							
							var popup_hd_str = "";
							popup_hd_str += '<div class="sf-thickbox-title">';
							popup_hd_str += '<div class=".sf-ajax-window-title"></div>';
							popup_hd_str += '<div class="sf-thickbox-title-inner">Update Terms</div>';
							popup_hd_str += '<div class="sf-close-ajax-window">';
							popup_hd_str += '<a href="#" id="TB_closeWindowButton" name="TB_closeWindowButton"></a>';
							popup_hd_str += '<div class="sf-close-icon"></div>';
							popup_hd_str += '</div>';
							popup_hd_str += '</div>';
							
							/* init content */
							var taxonomy_name = $(this).attr("data-taxonomy-name");
							var taxonomy_label = $(this).attr("data-taxonomy-label");
							var taxonomy_ids = $(this).parent().find(".settings_exclude_ids").val();
							var $this = $(this);
							
							var popup_content_str = "";
							popup_content_str += '<div class="sf-ajax-content">';
							popup_content_str += '<p>Choose the terms to include/exclude for `<strong>'+taxonomy_label+'</strong>`</p>';
							popup_content_str += '<p class="sf-thickbox-content">';
							popup_content_str += '<span class="spinner" style="display: block; float:left; text-align:left;"></span>';
							popup_content_str += '</p>';
							popup_content_str += '</div>';
							
							var popup_ft_str = "";
							popup_ft_str += '<div class="sf-thickbox-frame-toolbar">';
							popup_ft_str += '<div class="sf-thickbox-toolbar">';
							popup_ft_str += '<p><a href="#" class="button-secondary sf-meta-select-none">Select None</a> <a href="#" class="button-secondary sf-meta-select-all">Select All</a> <a href="#" class="button-primary sf-thickbox-action-right sf-update-button">Update</a></p>';
							popup_ft_str += '</div>';
							popup_ft_str += '</div>';
							
							var $popup_header = $(popup_hd_str);
							var $popup_content = $(popup_content_str);
							var $popup_footer = $(popup_ft_str);
							
							$popup.append($popup_header);
							$popup.append($popup_content);
							$popup.append($popup_footer);
							
							var $close_button = $popup_header.find(".sf-close-ajax-window");
							$close_button.click(function()
							{
								$('.sf-thickbox-overlay').remove();
								$popup.remove();
								
							});
							
							var $select_none_button = $popup_footer.find(".sf-meta-select-none");
							$select_none_button.click(function()
							{
								$popup_content.find(".sf-thickbox-content input[type=checkbox]").each(function(){
								
									this.checked = false;							
								});
								
								return false;
								
							});
							
							var $select_all_button = $popup_footer.find(".sf-meta-select-all");
							$select_all_button.click(function()
							{
								$popup_content.find(".sf-thickbox-content input[type=checkbox]").each(function(){
								
									this.checked = true;							
								});
								
								return false;
								
							});
							
							var $update_button = $popup_footer.find(".sf-update-button");
							
							$update_button.click(function()
							{
								var $replace_options_checkbox = $popup_footer.find('.replace-options-checkbox');
								var $checked_options = $popup_content.find(".sf-thickbox-content input[type=checkbox]:checked");
								
								var checkedIds = [];
														
								if($checked_options.length>0)
								{
									$checked_options.each(function(){
										
										checkedIds.push($(this).val());
									});
								}
								
								var checkedStr = "";
								
								if(checkedIds.length>0)
								{
									checkedStr = checkedIds.join(',');
								}
								
								$this.parent().find(".settings_exclude_ids").val(checkedStr);
								
								//hide popup
								$('.sf-thickbox-overlay').remove();
								$popup.remove();
								
								return false;
								
							});
							
							getTaxonomyTerms(taxonomy_name, taxonomy_ids, $popup_content.find(".sf-thickbox-content"));
							
						}
						
						return false;
						
						
					});
				}
			});
		}
		
		String.prototype.parseArray = function (arr) {
			return this.replace(/\{([0-9]+)\}/g, function (_, index) { return arr[index]; });
		};
		
		String.prototype.parse = function (a, b, c) {
			return this.parseArray([a, b, c]);
		};
		$.fn.hasAttr = function(name) {  
		   return this.attr(name) !== undefined;
		};
		
		var $search_tax_button = $('#search-filter-settings-box .search-tax-button');
		$search_tax_button.SfPopupTax();
		
		
		$("#search-filter-search-form").addClass("widgets-search-filter-sortables").addClass("ui-search-filter-sortable");
		
		//create all default handlers, open/close/delete/save
		jQuery.fn.makeWidget = function(options)
		{
			var defaults = {
				startOpened: false
			};
			var opts = jQuery.extend(defaults, options);
			
			//loop through each item matched
			this.each(function()
			{
				var item = $(this);
				item.addClass("widget-enabled");
				var field_type = item.attr("data-field-type");
				
				var itemInside = item.find('.widget-inside');
				
				if(opts.startOpened==true)
				{
					itemInside.show();
				}
				var container = item.parent();
			
				item.find('.widget-top').click(function(e){
					
					e.stopPropagation();
					e.preventDefault();
					
					var allowExpand = container.attr("data-allow-expand");
					if(typeof(allowExpand)=="undefined")
					{//init data-dragging if its not on already
						container.attr("data-allow-expand", "1");
						allowExpand = "1";
					}
					
					if(allowExpand==1)
					{					
						var dataDragging = item.attr("data-dragging");
						if(typeof(dataDragging)=="undefined")
						{//init data-dragging if its not on already
							item.attr("data-dragging", "0");
							dataDragging = "0";
						}
						
						if(dataDragging=="0") 
						{
							itemInside.slideToggle("fast");
						}
					}
					
					return false;
				});
				
				item.find('.widget-control-remove').click(function(e){				
					
					item.slideUp("fast", function(){
						
						item.remove();
						if(item.length==1)
						{
							//$emptyPlaceholder.show();
						}
						
					});
					
					return false;
				});
				
				item.find('.widget-control-advanced').click(function(e){
				
					$(this).parent().find(".advanced-settings").slideToggle("fast");
					$(this).toggleClass("active");
					
					return false;
				});
				
				item.find('.widget-control-close').click(function(e){
				
					itemInside.slideUp("fast");
					
					return false;
				
				});
				
				//widget specific JS
				//categories
				if((field_type=="category")||(field_type=="tag")||(field_type=="taxonomy")||(field_type=="post_type"))
				{
					
					var $input_type_field = item.find('.sf_input_type select');
					
					// start off by showing/hiding correct fields
					showHideFields($input_type_field.val());
					
					//grab the input type
					$input_type_field.change(function(e)
					{
						var input_type = $(this).val();
						showHideFields(input_type);
						
					});
					
					var $combobox = item.find(".sf_make_combobox input");
					
					$combobox.change(function()
					{
						var tinput_type = $(this).parent().parent().find('.sf_input_type select').val();
						
						if(tinput_type=="multiselect")
						{
							if($(this).prop("checked"))
							{
								item.find(".sf_all_items_label").show();
							}
							else
							{
								item.find(".sf_all_items_label").hide();
							}
						}
					});
					
					var $tax_button = null;
					
					if(field_type!="post_type")
					{
						var $sync_include_exclude = item.find(".sync_include_exclude");
						
						$sync_include_exclude.on("change", function(){
							
							init_sync_checkbox($(this));
							
						});
						
						init_sync_checkbox($sync_include_exclude);
						
						$tax_button = item.find(".search-tax-button");
						
						if(field_type!="post_tag")
						{
							//include children
							var $operator_select = item.find(".sf_operator select");
							var $children_checkbox = item.find(".sf_include_children input");
							
							$operator_select.on("change", function(){
								
								if($(this).val()=="and")
								{
									$children_checkbox.prop("checked", false);
								}
								
							});
							
							$children_checkbox.on("change", function(){
								
								if($(this).prop("checked")==true)
								{
									$operator_select.val("or");
								}
								
							});
						}
					}
					
					if(field_type=="taxonomy")
					{
						
						var $tsel = item.find('.sf_taxonomy_name select');
						var current_tax_name = $tsel.find("option[value='"+$tsel.val()+"']").html();
						
						
						$tax_button.attr('data-taxonomy-name', $tsel.val());
						$tax_button.attr('data-taxonomy-label', current_tax_name);
					}
					
					if(field_type!="post_type")
					{
						$tax_button.SfPopupTax();
					}
					
				}
				
				function init_sync_checkbox($this)
				{
					if($this.is(":checked"))
					{
						item.find(".exclude_ids").attr("disabled", "disabled");
						item.find(".exclude_ids_hidden").removeAttr("disabled");
						item.find(".exclude_ids_hidden").val(item.find(".exclude_ids").val());
						item.find(".search-tax-button").addClass("disabled");
						
					}
					else
					{
						item.find(".exclude_ids").removeAttr("disabled");
						item.find(".exclude_ids_hidden").attr("disabled", "disabled");
						item.find(".search-tax-button").removeClass("disabled");
					}
				}
				
				if(field_type=="author")
				{
					
					var $input_type_field = item.find('.sf_input_type select');
					
					// start off by showing/hiding correct fields
					showHideFieldsAuthor($input_type_field.val());
					
					var $combobox = item.find(".sf_make_combobox input");
					
					$combobox.change(function()
					{
						var tinput_type = $(this).parent().parent().find('.sf_input_type select').val();
						
						if(tinput_type=="multiselect")
						{
							if($(this).prop("checked"))
							{
								item.find(".sf_all_items_label").show();
							}
							else
							{
								item.find(".sf_all_items_label").hide();
							}
						}
					});
					
					
					//grab the input type
					$input_type_field.change(function(e)
					{
						var input_type = $(this).val();
						showHideFieldsAuthor(input_type);						
					});
				}
				
				if(field_type=="taxonomy")
				{
					var $taxonomy_select = item.find('.sf_taxonomy_name select');
					
					$taxonomy_select.on("change",function()
					{
						var current_tax_name = $taxonomy_select.find("option[value='"+$taxonomy_select.val()+"']").html();
						item.find('.in-widget-title').html(": "+current_tax_name);
						
						var $tax_button = item.find(".search-tax-button");
						$tax_button.attr('data-taxonomy-name', $(this).val());
						$tax_button.attr('data-taxonomy-label', current_tax_name);
					});
					
					var current_tax_name = $taxonomy_select.find("option[value='"+$taxonomy_select.val()+"']").html();
					item.find('.in-widget-title').html(": "+current_tax_name);
					
				}
				
				if(field_type=="sort_order")
				{
					setPostMetaManualFields();
					
					var $meta_key_manual_toggle = item.find('.meta_key_manual_toggle');
					
					var $input_type_field = item.find('.sf_input_type select');
					
					// start off by showing/hiding correct fields
					showHideFields($input_type_field.val());
					
					$input_type_field.change(function(e)
					{
						var input_type = $(this).val();
						showHideFields(input_type);
					});
					
					
					//grab the input type
					$meta_key_manual_toggle.change(function(e)
					{
						setPostMetaManualFields();
					});
				}
				
				if(field_type=="post_meta")
				{
					setPostMetaManualFields();
					
					var $meta_key_manual_toggle = item.find('.meta_key_manual_toggle');
					
					var $input_type_field = item.find('.sf_input_type select');
					
					// start off by showing/hiding correct fields
					showHideFields($input_type_field.val());
					
					$input_type_field.change(function(e)
					{
						var input_type = $(this).val();
						showHideFields(input_type);
					});
					
					
					var $combobox = item.find(".sf_make_combobox input");
					
					$combobox.change(function()
					{
						var tinput_type = $(this).parent().parent().find('.sf_input_type select').val();
						
						if(tinput_type=="multiselect")
						{
							if($(this).prop("checked"))
							{
								item.find(".sf_all_items_label").show();
							}
							else
							{
								item.find(".sf_all_items_label").hide();
							}
						}
					});
					
					//grab the input type
					$meta_key_manual_toggle.change(function(e)
					{
						setPostMetaManualFields();
					});
				}
				
				if(field_type=="sort_order")
				{
					var $add_sort_button = item.find(".add-sort-button");
					var $clear_option_button = item.find(".clear-option-button");
					var $sort_options_list = item.find(".sort_options_list");
					var $no_sort_label = item.find(".no_sort_label");
					
					var $current_sort_options = $sort_options_list.find("li:not(.sort-option-template)");
					$current_sort_options.show();
					
					if($current_sort_options.length>0)
					{

						 $no_sort_label.hide();
					}
					
					$current_sort_options.each(function(){
						
						initSortOptionControls($(this), $sort_options_list, $no_sort_label);
						
					});					
					
					$sort_options_list.sortable({
						opacity: 0.6,
						//revert: 200,
						revert: false,
						cursor: 'move',
						handle: '.slimmove',
						//cancel: '.widget-title-action,h3,.sidebar-description',
						items: 'li:not(.sort-option-template)',
						placeholder: 'sort-option-placeholder',
						'start': function (event, ui) {
							ui.placeholder.show();
						},
						stop: function(e,ui) {
							
							var optionsCount = 0;
							var $sort_options_list = ui.item.find(".sort_options_list");
							var widgetCount = ui.item.attr("data-widget-id");
							
							$sort_options_list.find("li:not(.sort-option-template)").each(function()
							{
								
								setOptionVars($(this), widgetCount, optionsCount);
								optionsCount++;
							
							});
						}
					});
					
					$clear_option_button.click(function(){
					
						
						var $sort_options = $sort_options_list.find("li:not(.sort-option-template)");
								
						$sort_options.each(function(){
							
							var $sort_option = $(this);
							
							$sort_option.slideUp("fast",function(){
								$sort_option.remove();
								
								if($sort_options_list.find("li:not(.sort-option-template)").length==0)
								{
									$no_sort_label.show();
								}
							});
						});
						
						return false;
					
					});
					
					$add_sort_button.click(function(){
					
						
						var $meta_key_manual_toggle  = item.find(".meta_key_manual_toggle");
						var $meta_key_manual  = item.find(".meta_key_manual");
						var $meta_key = item.find(".meta_key");
						
						var meta_key_value = $meta_key.val();
						
						if($meta_key_manual_toggle.is(":checked"))
						{
							meta_key_value = $meta_key_manual.val();
						}
						
						if(meta_key_value!="")
						{
							//reset meta fields
							$meta_key.removeAttr("selected");
							$meta_key[0].selectedIndex = 0;
							$meta_key_manual.val("");
							$meta_key_manual_toggle.prop("checked", false);
							$meta_key.removeAttr("disabled");
							$meta_key_manual.attr("disabled", "disabled");
							
							
							var optionsId = $sort_options_list.find("li:not(.sort-option-template)").length;
							
							var option_html = "";
							
							var $sort_option = $sort_options_list.find("li.sort-option-template").clone();
							$sort_option.removeClass("sort-option-template");
							$sort_option.hide();
							$sort_option.find(".meta_key_val, .meta_disabled, .name").val(meta_key_value);
							
							setOptionVars($sort_option, item.attr('data-widget-id'), optionsId);
							
							var $sort_by_option = $sort_option.find(".sort_by_option");
							
							if($sort_by_option.val()=="meta_value")
							{
								$sort_option.find('.sort-options-advanced').show();
							}
							else
							{
								$sort_option.find('.sort-options-advanced').hide();
							}
							
							$sort_options_list.append($sort_option);
							$sort_option.slideDown("fast");
							
							initSortOptionControls($sort_option, $sort_options_list, $no_sort_label);
							
							$no_sort_label.hide();
							
						}
						return false;
					
					});
				}
				
				if(field_type=="post_meta")
				{
					/* init meta type radios */
					/* set up meta type radios */
					var $meta_type_radio = item.find('.sf_meta_type input[type=radio]');
					var $meta_type_labels = item.find('.sf_meta_type label');
					var $checked_radio = item.find(".sf_meta_type input[data-radio-checked='1']");
					
					$meta_type_radio.each(function(){
						this.checked = false;
						$(this).attr("data-radio-checked", 0);
					});
					
					
					if($checked_radio.length==0)
					{
						$checked_radio = item.find(".sf_meta_type label:first-child input");
						
					}
					
					$checked_radio.attr("data-radio-checked", 1);
					$checked_radio.prop('checked',true);
					var meta_type_val = $checked_radio.val();
					
					metaTypeChange($checked_radio);
					
					$meta_type_radio.change(function()
					{
						$meta_type_radio.attr("data-radio-checked", 0);
						$(this).attr("data-radio-checked", 1);
						metaTypeChange($(this));
						
					});
					
					
					/* ****************************************************** */
					
					
					var $add_option_button = item.find(".add-option-button");
					var $detect_option_button = item.find(".detect-option-button");
					var $clear_option_button = item.find(".clear-option-button");
					var $meta_options_list = item.find(".meta_options_list");
					var $no_sort_label = item.find(".no_sort_label");
					
					var $current_meta_options = $meta_options_list.find("li:not(.meta-option-template)");
					
					$meta_options_list.sortable({
						opacity: 0.6,
						//revert: 200,
						revert: false,
						cursor: 'move',
						handle: '.slimmove',
						//cancel: '.widget-title-action,h3,.sidebar-description',
						items: 'li:not(.meta-option-template)',
						placeholder: 'meta-option-placeholder',
						'start': function (event, ui) {
							ui.placeholder.show();
						},
						stop: function(e,ui) {
							
							var optionsCount = 0;
							var $meta_options_list = ui.item.find(".meta_options_list");
							var widgetCount = ui.item.attr("data-widget-id");
							
							$meta_options_list.find("li:not(.meta-option-template)").each(function()
							{
								
								setOptionVars($(this), widgetCount, optionsCount);
								optionsCount++;
							
							});
							
						}
					});
					
					$current_meta_options.show();
					
					if($current_meta_options.length>0)
					{
						 $no_sort_label.hide();
					}
					
					$current_meta_options.each(function(){
						
						initMetaOptionControls($(this), $meta_options_list, $no_sort_label);
						
					});
					
					$add_option_button.click(function(){
					
						
						var $meta_key_manual_toggle  = item.find(".meta_key_manual_toggle");
						
						var optionsId = $meta_options_list.find("li:not(.meta-option-template)").length;
						
						var option_html = "";
						
						var $meta_option = $meta_options_list.find("li.meta-option-template").clone();
						$meta_option.removeClass("meta-option-template");
						
						//$meta_option.find(".meta_key_val, .meta_disabled, .name").val(meta_key_value);
						
						setOptionVars($meta_option, item.attr('data-widget-id'), optionsId);
						$meta_options_list.append($meta_option);
						$meta_option.slideDown("fast");
						
						initMetaOptionControls($meta_option, $meta_options_list, $no_sort_label);
						
						$no_sort_label.hide();
						
						
						return false;
					
					});
					
					
					$detect_option_button.SfPopupMeta();
					
					$clear_option_button.click(function(){
					
						
						var $meta_options = $meta_options_list.find("li:not(.meta-option-template)");
								
						$meta_options.each(function(){
							
							var $meta_option = $(this);
							
							$meta_option.slideUp("fast",function(){
								$meta_option.remove();
								
								if($meta_options_list.find("li:not(.meta-option-template)").length==0)
								{
									$no_sort_label.show();
								}
							});
						});
						
						return false;
					
					});
				}
				
				function initSortOptionControls($sort_option, $sort_options_list, $no_sort_label)
				{
					var $sort_by_option = $sort_option.find(".sort_by_option");
					
					if($sort_by_option.val()=="meta_value")
					{
						$sort_option.find('.sort-options-advanced').show();
					}
					else
					{
						$sort_option.find('.sort-options-advanced').hide();
					}
					
					$sort_by_option.change(function()
					{
						if($(this).val()=="meta_value")
						{
							$sort_option.find('.sort-options-advanced').slideDown("fast");
						}
						else
						{
							$sort_option.find('.sort-options-advanced').slideUp("fast");
						}
					});
					
					$sort_option.find(".widget-control-option-remove").click(function(){
								
						$sort_option.slideUp("fast",function(){
							$sort_option.remove();
							
							if($sort_options_list.find("li:not(.sort-option-template)").length==0)
							{
								$no_sort_label.show();
							}
						});
						
						return false;
						
					});
					
					/*$advanced_button.click(function(){
						
						$(this).toggleClass("active");
						$sort_option.find('.sort-options-advanced').slideToggle("fast");
						return false;
						
					});*/
					
				}
				
				
				
				function setPostMetaManualFields()
				{
					var $meta_key_manual = item.find(".meta_key_manual");
					var $meta_key_manual_hidden = item.find(".meta_key_manual_hidden");
					var $meta_key = item.find(".meta_key");
					var $meta_key_hidden = item.find(".meta_key_hidden");
					var $meta_key_manual_toggle = item.find(".meta_key_manual_toggle");
					
					if($meta_key_manual_toggle.is(":checked"))
					{
						$meta_key_manual.removeAttr("disabled");
						$meta_key_manual_hidden.attr("disabled", "disabled");
						
						$meta_key_hidden.removeAttr("disabled");
						$meta_key_hidden.val($meta_key.val());
						$meta_key.attr("disabled", "disabled");
						
						$meta_key_manual.focus();
					}
					else
					{
						$meta_key_manual_hidden.removeAttr("disabled");
						$meta_key_manual_hidden.val($meta_key_manual.val());
						$meta_key_manual.attr("disabled", "disabled");
						
						$meta_key.removeAttr("disabled");
						$meta_key_hidden.attr("disabled", "disabled");
					}

				}
				
				function showHideFields(input_type)
				{
					if(input_type=="select")
					{
						item.find(".sf_operator").hide();
						item.find(".sf_drill_down").show();
						item.find(".sf_all_items_label").show();
						item.find(".sf_make_combobox").show();
					}
					else if(input_type=="checkbox")
					{
						item.find(".sf_operator").show();
						item.find(".sf_drill_down").hide();
						item.find(".sf_all_items_label").hide();
						item.find(".sf_make_combobox").hide();
					}
					else if(input_type=="radio")
					{
						item.find(".sf_operator").hide();
						item.find(".sf_drill_down").hide();
						item.find(".sf_make_combobox").hide();
						item.find(".sf_all_items_label").show();
					}
					else if(input_type=="multiselect")
					{
						item.find(".sf_operator").show();
						item.find(".sf_drill_down").hide();
						item.find(".sf_all_items_label").hide();
						item.find(".sf_make_combobox").show();
						
						if(item.find(".sf_make_combobox input").prop("checked"))
						{
							item.find(".sf_all_items_label").show();
						}
					}
					else if(input_type=="range-slider")
					{

					}
					else if(input_type=="range-number")
					{

					}
					else if(input_type=="range-radio")
					{

					}
					else if(input_type=="range-checkbox")
					{

					}
					
				}
				function showHideFieldsAuthor(input_type)
				{
					if(input_type=="select")
					{
						//item.find(".sf_operator").hide();
						item.find(".sf_all_items_label").show();
						item.find(".sf_make_combobox").show();
					}
					else if(input_type=="checkbox")
					{
						//item.find(".sf_operator").show();
						item.find(".sf_all_items_label").hide();
						item.find(".sf_make_combobox").hide();
					}
					else if(input_type=="radio")
					{
						//item.find(".sf_operator").hide();
						item.find(".sf_all_items_label").show();
						item.find(".sf_make_combobox").hide();
					}
					else if(input_type=="multiselect")
					{
						//item.find(".sf_operator").show();
						item.find(".sf_all_items_label").hide();
						item.find(".sf_make_combobox").show();
						
						if(item.find(".sf_make_combobox input").prop("checked"))
						{
							item.find(".sf_all_items_label").show();
						}
					}
					
				}
			})
			
			return this;
		};
		
		jQuery.fn.makeSortables = function(options)
		{
			/*
			//initialise options
			var opts = jQuery.extend(defaults, options);
			*/
			
			setWidgetFormIds();
			
			//loop through each item matched
			this.each(function()
			{
				
				var container = $(this);
				var allowExpand = true;
				
				var allowExpand = container.attr("data-allow-expand");
				if(typeof(allowExpand)=="undefined")
				{//init data-dragging if its not on already
					container.attr("data-allow-expand", "1");
					allowExpand = "1";
				}
				//var $emptyPlaceholder = $(this).find("#empty-placeholder");
				var received = false;
				
				container.sortable({
					opacity: 0.6,
					//revert: container.hasClass("closed") ? false : 200,
					revert: false,
					cursor: 'move',
					handle: '.widget-top',
					cancel: '.widget-title-action,h3,.sidebar-description',
					items: '.inside #search-form > .widget:not(.sidebar-name,.sidebar-disabled)',
					placeholder: 'widget-placeholder',
					connectWith: '.ui-search-filter-sortable',
					stop: function(e,ui){
						
						setWidgetFormIds();
						
						ui.item.attr("data-dragging", "0");
						var field_type = ui.item.attr("data-field-type");
						
						//check to see if the context (the source item) has the class "widget enabled", if it doesn't then it was from the available fields list, if it doesn't then we were moving the item already in the Search Form
						if(!$(ui.item.context).hasClass("widget-enabled"))
						{
							//if we are moving the item from teh Available fields, automatically slide open
							ui.item.find('.widget-inside').slideDown("fast");
						}
						
						
						if(field_type=="post_meta")
						{
							/* set up meta type radios */
							var $meta_type_radio = ui.item.find('.sf_meta_type input[type=radio]');
							var $meta_type_labels = ui.item.find('.sf_meta_type label');
							var $checked_radio = ui.item.find(".sf_meta_type input[data-radio-checked='1']");
							
							$meta_type_radio.each(function(){
								this.checked = false;
								$(this).attr("data-radio-checked", 0);
							});
							
							
							if($checked_radio.length==0)
							{
								$checked_radio = ui.item.find(".sf_meta_type label:first-child input");
								$checked_radio.prop('checked',true);
							}
							
							$checked_radio.attr("data-radio-checked", 1);
							$checked_radio.prop('checked',true);
							var meta_type_val = $checked_radio.val();
							
							metaTypeChange($checked_radio);
							
							$meta_type_radio.change(function()
							{
								$meta_type_radio.attr("data-radio-checked", 0);
								$(this).attr("data-radio-checked", 1);
								metaTypeChange($(this));
								
							});
							
							
						}
						
						
						
						var $date_format_hidden = ui.item.find('.date_format_hidden');
						if($date_format_hidden.length==1)
						{
							var selected_radio = $date_format_hidden.val();
							//find any radios
							var $date_radio_inputs = ui.item.find("input.date_format_radio");
							if($date_radio_inputs.length>0)
							{
								//make sure default is selected
								if($($date_radio_inputs.get(selected_radio)).length>0)
								{
									$($date_radio_inputs.get(selected_radio)).prop('checked', true);
								}
							}
						}
												
						
					},
					over: function(){	
						
						//$emptyPlaceholder.hide();
						
					},
					out: function(){
						if(!received)
						{
							//$emptyPlaceholder.show();
						}
					},
					start: function(e,ui){
						ui.item.attr("data-dragging", "1");
						ui.item.find('.widget-inside').stop(true,true).hide();
						//if(!ui.placeholder.parent().hasClass("inside"))
						//{//if it is getting appended to the wrong place, then force it in to the right container :)
							ui.placeholder.appendTo(ui.placeholder.parent().find(".inside #search-form"));
						//alert("start");
					},
					receive: function(ev, ui)
					{
						received = true;
						//$emptyPlaceholder.hide();
						//alert("receive");
					},
					change: function(e,ui){
						//alert("change");
					}
					
				});
				
				container.click(function()
				{//prevent animation when the container is closed - no need to animate the helper to an invisible DIV.....
					/*if(container.hasClass("closed"))
					{
						
						container.sortable( "option", "revert", false );
					}
					else
					{
						container.sortable( "option", "revert", 200 );
					}*/
				});
				
					
				var items = container.find(".widget");
				items.makeWidget();
				
			});
			
			return this;
		};
		
		$("#search-filter-search-form").makeSortables();
		
		
		$(".widgets-search-filter-draggables .widget").draggable({
            connectToSortable: ".ui-search-filter-sortable",
			helper: 'clone',
			start: startDrag,
			stop: enableNewWidgets
        });
		
		function startDrag(event, ui)
		{
			//@TODO: add and remove hover effect class
			$("#search-filter-search-form").addClass("post-box-hover");
			/*$("#search-filter-search-form").css("border", "1px solid #f00");
			$("#search-filter-search-form").css("width", "100%");
			$("#search-filter-search-form").css("height", "auto");*/
		}
		
		
		function enableNewWidgets(event, ui)
		{
			//@TODO: add and remove hover effect class
			$("#search-filter-search-form").removeClass("post-box-hover");
			
			var $droppedWidget = $('.widgets-search-filter-sortables .widget:not(.widget-enabled)');
			$droppedWidget.makeWidget();
			$droppedWidget.css("width", "");
			$droppedWidget.css("height", "");
			

		}
		
		function setWidgetFormIds()
		{
			var widgetCount = 0;
			
			var $active_widgets = $("#search-filter-search-form").find(".widget");
			
			/*var $widgets_radio = $active_widgets.find("input[type=radio]");
			$widgets_radio.each(function(){
			
				$(this).attr("data-radio-val", $(this).prop("checked"));
				
			});*/
			
			$active_widgets.each(function()
			{
				
				
				setFormVars($(this), widgetCount);
				
				//if type is sort_order then loop through the option
				if($(this).attr("data-field-type")=="sort_order")
				{
					
					var optionsCount = 0;
					var $sort_options_list = $(this).find(".sort_options_list");
					$sort_options_list.find("li:not(.sort-option-template)").each(function()
					{
						
						setOptionVars($(this), widgetCount, optionsCount);
						optionsCount++;
					
					});
				}
				
				if($(this).attr("data-field-type")=="post_meta")
				{
					
					var optionsCount = 0;
					var $meta_options_list = $(this).find(".meta_options_list");
					$meta_options_list.find("li:not(.meta-option-template)").each(function()
					{
						
						setOptionVars($(this), widgetCount, optionsCount);
						optionsCount++;
					
					});
				}

				widgetCount++;
			});
			
			var $widgets_radio = $active_widgets.find("input[type=radio]");
			
			$widgets_radio.each(function()
			{
				if($(this).attr("data-radio-checked")==1)
				{
					$(this).prop("checked", true);
					
					
					
				}
				
			});
			
		}
	
		function setFormVars($droppedWidget, widgetId)
		{
			$droppedWidget.attr("data-widget-id", widgetId);
			var $inputFields = $droppedWidget.find("input, select").not(".ignore-template-init input, .ignore-template-init select");
			var $inputLabels = $droppedWidget.find("label").not(".ignore-template-init label");
			
			$inputFields.each(function()
			{
				//copy structure
				if(!$(this).hasAttr("data-field-template-id"))
				{
					$(this).attr("data-field-template-id", $(this).attr("id"))
				}
				
				if(!$(this).hasAttr("data-field-template-name"))
				{
					$(this).attr("data-field-template-name", $(this).attr("name"))
				}
				
				//rename
				$(this).attr("id",$(this).attr("data-field-template-id").parse("widget-field", widgetId));
				$(this).attr("name",$(this).attr("data-field-template-name").parse("widget-field", widgetId));
				
			});
			
			$inputLabels.each(function()
			{
				//copy structure
				if(!$(this).hasAttr("data-field-template-for"))
				{
					$(this).attr("data-field-template-for", $(this).attr("for"))
				}
				
				$(this).attr("for",$(this).attr("data-field-template-for").parse("widget-field", widgetId));
				
			});
		}
		function setOptionVars($sortOption, widgetId, optionId)
		{
			var $inputFields = $sortOption.find("input, select");
			var $inputLabels = $sortOption.find("label");
			
			$inputFields.each(function()
			{
				//copy structure
				if(!$(this).hasAttr("data-field-template-id"))
				{
					$(this).attr("data-field-template-id", $(this).attr("id"))
				}
				
				if(!$(this).hasAttr("data-field-template-name"))
				{
					$(this).attr("data-field-template-name", $(this).attr("name"))
				}
				
				//rename
				$(this).attr("id",$(this).attr("data-field-template-id").parse("widget-field", widgetId, optionId));
				$(this).attr("name",$(this).attr("data-field-template-name").parse("widget-field", widgetId, optionId));
				
			});
			
			$inputLabels.each(function()
			{
				//copy structure
				if(!$(this).hasAttr("data-field-template-for"))
				{
					$(this).attr("data-field-template-for", $(this).attr("for"))
				}
				
				$(this).attr("for",$(this).attr("data-field-template-for").parse("widget-field", widgetId, optionId));
				
			});
		}
		
		function initMetaOptionControls($meta_option, $meta_options_list, $no_sort_label)
		{
			$meta_option.find(".widget-control-option-remove").click(function(){
						
				$meta_option.slideUp("fast",function(){
					$meta_option.remove();
					
					if($meta_options_list.find("li:not(.meta-option-template)").length==0)
					{
						$no_sort_label.show();
					}
				});
				
				return false;
				
			});
			
			$meta_option.find(".widget-control-option-advanced").click(function(){
				
				$(this).toggleClass("active");
				$meta_option.find('.meta-options-advanced').slideToggle("fast");
				return false;
				
			});
		}
		
		function metaTypeChange($radio_field)
		{
			
			var $meta_type_labels = $radio_field.parent().parent().find("label");
			var item = $radio_field.closest(".widget");
			
			$meta_type_labels.removeClass("active");
			var $meta_type_label = $radio_field.closest("label");
			$meta_type_label.addClass("active");
			
			var radio_val = $radio_field.val();
			
			item.find(".sf_input_type_meta input[data-radio-checked='1']").prop('checked',true);
			
			item.find(".sf_input_type_meta").hide();
			item.find(".sf_field_data").hide();
			
			item.find(".sf_input_type_meta.sf_"+radio_val).show();
			item.find(".sf_field_data.sf_"+radio_val).show();
		}
	
		function initSetupField()
		{
			/* display results - shortcode/archive */
			var $results_toggle = $(".setup .settings_display_results");
			var $template_table = $('.setup .sf_tab_content_template .template_options_table');
			
			var $use_ajax_toggle = $('.setup .sf_tab_content_template .use_ajax_toggle');
			var $selectors_results_div = $('#shortcode-info .results-shortcode');
			
			var $active_results_display = $(".setup .settings_display_results:checked");
			
			var $alabels = $active_results_display.parent().parent().parent().find("label");
			$alabels.removeClass("active");
			$active_results_display.parent().addClass("active");
			
			if($active_results_display.val()=="shortcode")
			{
				$template_table.removeClass("template_archive_options");
				$template_table.addClass("template_shortcode_options");
				$selectors_results_div.show();
			}
			else if($active_results_display.val()=="archive")
			{
				$template_table.removeClass("template_shortcode_options");
				$template_table.addClass("template_archive_options");
				$selectors_results_div.hide();
			}
			
			/* ajax toggle */
			$results_toggle.on("change", function(e){
				
				var $self = $(this);
				var val = $self.val();
				
				var $labels = $self.parent().parent().parent().find("label");
				$labels.removeClass("active");
				$self.parent().addClass("active");
				
				if(val=="shortcode")
				{
					$template_table.removeClass("template_archive_options");
					$template_table.addClass("template_shortcode_options");
					$selectors_results_div.show();
				}
				else if(val=="archive")
				{
					$template_table.removeClass("template_shortcode_options");
					$template_table.addClass("template_archive_options");
					$selectors_results_div.hide();
				}
				
			});
			
			ajaxToggle($use_ajax_toggle);
			$use_ajax_toggle.on("change",function(){
				ajaxToggle($(this));
				updateScrollToPos($scroll_to_pos);
			});
			
			/* scroll to pos */
			var $scroll_to_pos = $(".setup .scroll_to_pos");
			
			
			updateScrollToPos($scroll_to_pos);
			$scroll_to_pos.on("change", function(e){
				updateScrollToPos($(this));
				
			});
			//end scroll_to_pos
			
			/* sort by */
			var $default_sort_by = $('.setup .default_sort_by');
			var $sort_by_meta_container = $('.setup .sort_by_meta_container');
			$default_sort_by.change(function(e)
			{
				handleDefaultSortBy($(this), $sort_by_meta_container);
			});
			
			handleDefaultSortBy($default_sort_by, $sort_by_meta_container);
			
				
		}
		function updateScrollToPos($scrollToObject)
		{
			if($scrollToObject.prop("disabled")==false)
			{
				var $custom_scroll_to = $(".setup .custom_scroll_to");
				var $scroll_on_action = $(".setup .scroll_on_action");
				
				if($scrollToObject.val()=="custom")
				{
					$custom_scroll_to.show();
				}
				else
				{
					$custom_scroll_to.hide();
				}
				
				
				if($scrollToObject.val()=="0")
				{
					disableInput($scroll_on_action); 
				}
				else
				{
					enableInput($scroll_on_action);
				}
			}
			
			
		}
		function ajaxToggle($self)
		{
			var $inputs = $(".tpl_use_ajax_rows input, .tpl_use_ajax_rows select");
			
			if($self.is(":checked"))
			{
				//enable all fields
				$inputs.each(function(){
				
					var $input = $(this);
					enableInput($input);
					
				});
			}
			else
			{
				//disable all fields
				$inputs.each(function(){
				
					var $input = $(this);
					disableInput($input);
				});
				
			}
			
		}
		function disableInput($input)
		{
			//create a hidden version of the field, that is not disabled - so we can retain even disabled values in the DB
			var $inputClone = $('<input/>', {
				'type'						: 'hidden',
				'name'						: $input.attr("name"),
				'class'						: 'clone',
				'data-field-template-id'	: $input.attr("data-field-template-id"),
				'data-field-template-name'	: $input.attr("data-field-template-name"),
				'value'						: ''
			});
			
			if($input.get(0).tagName=="INPUT")
			{
				if(($input.attr("type")=="checkbox")||($input.attr("type")=="radio"))
				{
					$inputClone.val($input.prop("checked") ? 1 : "");
				}
				else if($input.attr("type")=="text")
				{
					$inputClone.val($input.val());
				}
				else
				{
					$inputClone.val($input.val());
				}
			}
			else if($input.get(0).tagName=="SELECT")
			{
				$inputClone.val($input.val());
			}
			else
			{
				$inputClone.val($input.val());
			}
			
			$input.after($inputClone);
			$input.attr("disabled","disabled");
		}
		
		function enableInput($input)
		{
			$input.removeAttr("disabled");
			
			var $inputClone = $input.parent().find(".clone");
			
			if($inputClone.length>0)
			{
				if($input.get(0).tagName=="INPUT")
				{
					if(($input.attr("type")=="checkbox")||($input.attr("type")=="radio"))
					{
						if($inputClone.val()==1)
						{
							$input.prop("checked", true);
						}
						else
						{
							$input.prop("checked", false);
						}
					}
					else if($input.attr("type")=="text")
					{
						$input.val($inputClone.val());
					}
					else
					{
						$input.val($inputClone.val());
					}
				}
				else if($input.get(0).tagName=="SELECT")
				{
					$input.val($inputClone.val());
				}
			
				$inputClone.remove();
			}
		}
		
		function handleDefaultSortBy($this, $sort_by_meta_container)
		{
			if($this.val()=="meta_value")
			{
				$sort_by_meta_container.show();
			}
			else
			{
				$sort_by_meta_container.hide();
			}
			
			
		}
		
		
		initSetupField();
		
		//load tooltips
		$('[data-hint]').live('mouseover', function() {
			
			$(this).qtip({
				overwrite: false, // Make sure another tooltip can't overwrite this one without it being explicitly destroyed
				content: {
					attr: 'data-hint' // Tell qTip2 to look inside this attr for its content
				},
				style: { classes: 'sf-tootlip' },
				position: {
					my: 'bottom left',
					at: 'top center',
					viewport: $(window),
					adjust: {
						method: 'shift none'
					}
				},
				show: {
				ready: true // Needed to make it show on first mouseover event
				}
			});
		});
		
		
		function setMetaSettingsFields($list)
		{
			var itemId = 0;
			$list.each(function(){
			
				if($(this).attr("data-remove")!=1)
				{
					
					var $self = $(this);
					var $inputFields = $self.find("input, select");
					var $inputLabels = $self.find("label");
					
					
					$inputFields.each(function()
					{
						//copy structure
						if(!$(this).hasAttr("data-field-template-id"))
						{
							$(this).attr("data-field-template-id", $(this).attr("id"))
						}
						
						if(!$(this).hasAttr("data-field-template-name"))
						{
							$(this).attr("data-field-template-name", $(this).attr("name"))
						}
						
						//rename
						$(this).attr("id",$(this).attr("data-field-template-id").parse(itemId));
						$(this).attr("name",$(this).attr("data-field-template-name").parse(itemId));
						
						
					});
					
					$inputLabels.each(function()
					{
						//copy structure
						if(!$(this).hasAttr("data-field-template-for"))
						{
							$(this).attr("data-field-template-for", $(this).attr("for"))
						}
						
						$(this).attr("for",$(this).attr("data-field-template-for").parse(itemId));
						
						
					});
					
					itemId++;
				}
			});
		}
		function removeMetaOption(e){
		
			e.preventDefault();
			
			$(this).closest("li").attr("data-remove","1");
			$(this).closest("li").slideUp("fast", function(){
				$(this).remove();
			});
			
			setMetaSettingsFields($("#search-filter-settings-box .sf_tab_content_post_meta ul.meta_list li:not(.template)"));
					
			return false;
		}
		function initSettingsMetabox()
		{
			/* init meta type radios */
			/* set up meta type radios */
			var $settings_tab_radio = $('#search-filter-settings-box .sf_settings_tabs input[type=radio]');
			var $settings_tab_label = $('#search-filter-settings-box .sf_settings_tabs label');
			var $checked_radio = $("#search-filter-settings-box .sf_settings_tabs input[data-radio-checked='1']");
			
			$settings_tab_radio.each(function(){
				this.checked = false;
				$(this).attr("data-radio-checked", 0);
			});
			
			
			$settings_tab_radio.change(function()
			{
				$settings_tab_radio.attr("data-radio-checked", 0);
				$(this).attr("data-radio-checked", 1);
				tabChange($(this));
				
			});
			
			
			var $meta_list = $("#search-filter-settings-box .sf_tab_content_post_meta ul.meta_list");
			var $meta_list_template = $meta_list.find(".template");
			var $add_condition_button = $("#search-filter-settings-box .sf_tab_content_post_meta .add-option-button");
			
			$meta_list.find(".option-remove").click(removeMetaOption);
			
			$meta_list.find(".meta_date_value_current_date").each(function(){
				metaSettingsCurrentDateChange($(this));
			});
			$meta_list.find(".meta_date_value_current_date").on("change", function(){
				metaSettingsCurrentDateChange($(this));
			});
			
			$meta_list.find(".meta_date_value_current_timestamp").each(function(){
				metaSettingsCurrentTimestampChange($(this));
			});
			$meta_list.find(".meta_date_value_current_timestamp").on("change", function(){
				metaSettingsCurrentTimestampChange($(this));
			});
			
			$meta_list.find(".meta_type").on("change", function(){
				metaSettingsFieldTypeChange($(this));
			});
			$meta_list.find(".meta_type").each(function(){
				metaSettingsFieldTypeChange($(this));
			});
			
			setMetaSettingsFields($("#search-filter-settings-box .sf_tab_content_post_meta ul.meta_list li:not(.template)"));
			
			$add_condition_button.click(function(){
			
				
				var $meta_option = $meta_list_template.clone();
				$meta_option.hide();
				$meta_option.removeClass("template");
				
				$meta_list.append($meta_option);
				
				$meta_option.find(".option-remove").click(removeMetaOption);
				
				$meta_option.find(".meta_type").on("change", function(){
					metaSettingsFieldTypeChange($(this));
				});
				
				$meta_option.find(".meta_date_value_current_date").on("change", function(){
					metaSettingsCurrentDateChange($(this));
				});				
				
				$meta_option.find(".meta_date_value_current_timestamp").on("change", function(){
					metaSettingsCurrentTimestampChange($(this));
				});
				
				setMetaSettingsFields($("#search-filter-settings-box .sf_tab_content_post_meta ul.meta_list li:not(.template)"));
				
				$meta_option.slideDown("fast");
				
				return false;
			
			});
		}
		function metaSettingsCurrentDateChange($field){
						
			var $datefields = $field.parent().parent().find("input[type='text']");
			
			if($field.is(":checked"))
			{
				$datefields.each(function(){
			
					var $input = $(this);
					disableInput($input);
				});
			}
			else
			{
				$datefields.each(function(){
			
				var $input = $(this);
					enableInput($input);
				});
				
			}
			
		}
		function metaSettingsCurrentTimestampChange($field){
						
			var $datefields = $field.parent().parent().find("input[type='text']");
			
			if($field.is(":checked"))
			{
				$datefields.each(function(){
			
					var $input = $(this);
					disableInput($input);
				});
			}
			else
			{
				$datefields.each(function(){
			
				var $input = $(this);
					enableInput($input);
				});
				
			}
			
		}
		function metaSettingsFieldTypeChange($field)
		{
			var val = $field.val();
			var $meta_value_c = $field.parent().parent().parent().find(".meta_value_c");
			var $meta_value_date_c = $field.parent().parent().parent().find(".meta_value_date_c");
			var $meta_value_timestamp_c = $field.parent().parent().parent().find(".meta_value_timestamp_c");
			var $meta_compare_field = $field.parent().parent().parent().find(".meta_compare");			
			
			
			if(val=="DATE")
			{
				$meta_value_c.hide();
				$meta_value_timestamp_c.hide();
				$meta_value_date_c.show();
				$meta_compare_field.find("option:not(.date-format-supported)").hide();
				
				var meta_compare_val = $meta_compare_field.val();
				var $meta_compare_option = $meta_compare_field.find(":selected");	
				if(!$meta_compare_option.hasClass('date-format-supported'))
				{
					$meta_compare_field.val("e");
				}
			}
			else if(val=="TIMESTAMP")
			{
				$meta_value_c.hide();
				$meta_value_date_c.hide();
				$meta_value_timestamp_c.show();
				$meta_compare_field.find("option:not(.date-format-supported)").hide();
				
				var meta_compare_val = $meta_compare_field.val();
				var $meta_compare_option = $meta_compare_field.find(":selected");	
				if(!$meta_compare_option.hasClass('date-format-supported'))
				{
					$meta_compare_field.val("e");
				}
			}
			else
			{
				if(($meta_value_date_c.is(":visible"))||($meta_value_timestamp_c.is(":visible")))
				{
					$meta_value_c.show();
					//$meta_compare_field.val("e");
					$meta_value_date_c.hide();
					$meta_value_timestamp_c.hide();
					$meta_compare_field.find("option").show();
				}
			}
		}
		
		
		function tabChange($radio_field)
		{
			
			var $tab_labels = $radio_field.parent().parent().find("label");
			var item = $radio_field.closest(".tabs-container");
			
			$tab_labels.removeClass("active");
			var $meta_type_label = $radio_field.closest("label");
			$meta_type_label.addClass("active");
			
			var radio_val = $radio_field.val();
			
			item.find(".sf_field_data").hide();
			
			item.find(".sf_field_data.sf_tab_content_"+radio_val).show();
		}
		initSettingsMetabox();
		
	});
	
	

}(jQuery));