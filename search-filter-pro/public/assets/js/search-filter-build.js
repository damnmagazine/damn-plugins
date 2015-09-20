(function($){
	
	"use strict";
	
	$(function(){
		
		String.prototype.replaceAll = function(str1, str2, ignore) 
		{
			return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
		}

		jQuery.fn.searchFilterForm = function(options)
		{
			var defaults = {
				startOpened: false,
				action: ""
			};
			
			var opts = jQuery.extend(defaults, options);
			
			//loop through each item matched
			this.each(function()
			{
				
				//	----

				if(opts.action=="reset")
				{
					this.url_components = "";
					this.url_params = {};
					this.resetForm();
					return true;
				}
				
				var $this = $(this);
				var self = this;
				
				this.$fields = $this.find("> ul > li"); //a reference to each fields parent LI
				
				this.url_components = "";
				this.url_params = {};
				
				this.sfid = $this.attr("data-sf-form-id");
				
				this.skipSFID = false;
				
				this.template_is_loaded = $this.attr("data-template-loaded");
				this.use_ajax = $this.attr("data-ajax");
				this.display_results_as = $this.attr("data-display-results");
				this.$ajax_target_object = jQuery($this.attr("data-ajax-target"));
				this.ajax_results_url = $this.attr("data-results-url");
				this.debug_mode = $this.attr("data-debug-mode");
				this.update_ajax_url = $this.attr("data-update-ajax-url");
				this.scroll_to_pos = $this.attr("data-scroll-to-pos");
				this.custom_scroll_to = $this.attr("data-custom-scroll-to");
				this.scroll_on_action = $this.attr("data-scroll-on-action");
				this.lang_code = $this.attr("data-lang-code");
				this.ajax_action = "";
				
				if(typeof(this.scroll_to_pos)=="undefined")
				{
					this.scroll_to_pos = "";
				}
				
				if(typeof(this.scroll_on_action)=="undefined")
				{
					this.scroll_on_action = "";
				}
				if(typeof(this.custom_scroll_to)=="undefined")
				{
					this.custom_scroll_to = "";
				}
				this.$custom_scroll_to = jQuery(this.custom_scroll_to);
				
				if(typeof(this.update_ajax_url)=="undefined")
				{
					this.update_ajax_url = "";
				}
				
				if(typeof(this.ajax_results_url)=="undefined")
				{
					this.ajax_results_url = "";
				}
				
				if(typeof(this.debug_mode)=="undefined")
				{
					this.debug_mode = "";
				}
				
				if(typeof(this.ajax_target_object)=="undefined")
				{
					this.ajax_target_object = "";
				}
				
				if(typeof(this.template_is_loaded)=="undefined")
				{
					this.template_is_loaded = "0";
				}
				
				this.ajax_target_attr = $this.attr("data-ajax-target");
				this.use_history_api = $this.attr("data-use-history-api");
				
				this.ajax_links_selector = $this.attr("data-ajax-links-selector");
				
				this.page_slug = $this.attr("data-page-slug");
				
				if((!this.use_ajax)||(this.use_results_shortcode))
				{
					this.url_components = "&sfid="+this.sfid;
				}
				
				this.permalinks_enabled = true;
				if(typeof(this.page_slug)=="undefined")
				{
					this.page_slug = "";
					this.permalinks_enabled = false;
				}
				
				this.auto_update = $this.attr("data-auto-update");
				this.inputTimer = 0;
				
				
				/* functions */
				
				
				
				//	----
				
				this.processSearchField = function($container)
				{
					var $field = $container.find("input[name='_sf_search']");
					
					if($field.length>0)
					{
						var fieldName = $field.attr("name");
						var fieldVal = $field.val();
						
						if(fieldVal!="")
						{
							if(self.display_results_as=="shortcode")
							{
								self.url_components += "&_sf_s="+fieldVal;
								self.url_params['_sf_s'] = fieldVal;
							}
							else
							{
								self.url_components += "&s="+fieldVal;
								self.url_params['s'] = fieldVal;
							}
						}
					}
				}
				this.processSortOrderField = function($container)
				{
					var $field = $container.find("input[name='_sf_search']");
					
					$field = $container.find("select");
					var fieldName = $field.attr("name").replace('[]', '');
					
					if($field.val()!=0)
					{
						var fieldVal = $field.val();
						
						if(fieldVal!="")
						{
							self.url_components += "&sort_order="+fieldVal;
							self.url_params['sort_order'] = fieldVal;
						}
					}
					
				}
				
				this.getSelectVal = function($field){
					
					var fieldVal = "";
					
					if($field.val()!=0)
					{
						fieldVal = $field.val();
					}
					
					if(fieldVal==null)
					{
						fieldVal = "";
					}
					
					return fieldVal;
				}
				this.getMetaSelectVal = function($field){
					
					var fieldVal = "";
					
					fieldVal = $field.val();
									
					if(fieldVal==null)
					{
						fieldVal = "";
					}
					
					return fieldVal;
				}
				
				this.getMultiSelectVal = function($field, operator){
					
					var delim = "+";
					if(operator=="or")
					{
						delim = ",";
					}
					
					if(typeof($field.val())=="object")
					{
						if($field.val()!=null)
						{
							return $field.val().join(delim);
						}
					}
					
				}
				this.getMetaMultiSelectVal = function($field, operator){
					
					var delim = "-+-";
					if(operator=="or")
					{
						delim = "-,-";
					}
					
					if(typeof($field.val())=="object")
					{
						if($field.val()!=null)
						{
							return $field.val().join(delim);
						}
					}
					
				}
				
				this.getCheckboxVal = function($field, operator){
					
					
					var fieldVal = $field.map(function(){
						if($(this).prop("checked")==true)
						{
							return $(this).val();
						}
					}).get();
					
					var delim = "+";
					if(operator=="or")
					{
						delim = ",";
					}
					
					return fieldVal.join(delim);
				}
				this.getMetaCheckboxVal = function($field, operator){
					
					
					var fieldVal = $field.map(function(){
						if($(this).prop("checked")==true)
						{
							return $(this).val();
						}
					}).get();
					
					var delim = "-+-";
					if(operator=="or")
					{
						delim = "-,-";
					}
					
					return fieldVal.join(delim);
				}
				
				this.getRadioVal = function($field){
										
					var fieldVal = $field.map(function()
					{
						if($(this).prop("checked")==true)
						{
							return $(this).val();
						}
						
					}).get();
					
					
					if(fieldVal[0]!=0)
					{
						return fieldVal[0];
					}
				}				
				
				this.getMetaRadioVal = function($field){
										
					var fieldVal = $field.map(function()
					{
						if($(this).prop("checked")==true)
						{
							return $(this).val();
						}
						
					}).get();
					
					return fieldVal[0];
				}				
				
				this.processAuthor = function($container)
				{
					var fieldType = $container.attr("data-sf-field-type");
					var inputType = $container.attr("data-sf-field-input-type");
					
					var $field;
					var fieldName = "";
					var fieldVal = "";
					
					if(inputType=="select")
					{
						$field = $container.find("select");
						fieldName = $field.attr("name").replace('[]', '');
						
						fieldVal = self.getSelectVal($field); 
					}
					else if(inputType=="multiselect")
					{
						$field = $container.find("select");
						fieldName = $field.attr("name").replace('[]', '');
						var operator = $field.attr("data-operator");
						
						fieldVal = self.getMultiSelectVal($field, "or");
						
					}
					else if(inputType=="checkbox")
					{
						$field = $container.find("ul > li input:checkbox");
						
						if($field.length>0)
						{
							fieldName = $field.attr("name").replace('[]', '');
													
							var operator = $container.find("> ul").attr("data-operator");
							fieldVal = self.getCheckboxVal($field, "or");
						}
						
					}
					else if(inputType=="radio")
					{
						$field = $container.find("ul > li input:radio");
						
						if($field.length>0)
						{
							fieldName = $field.attr("name").replace('[]', '');
							
							fieldVal = self.getRadioVal($field);
						}
					}
					
					if(typeof(fieldVal)!="undefined")
					{
						if(fieldVal!="")
						{
							var fieldSlug = "";
							
							if(fieldName=="_sf_author")
							{
								fieldSlug = "authors";
							}
							else if(fieldName=="_sf_post_type")
							{
								fieldSlug = "post_types";
							}
							else
							{
							
							}
							
							if(fieldSlug!="")
							{
								self.url_components += "&"+fieldSlug+"="+fieldVal;
								self.url_params[fieldSlug] = fieldVal;
							}
						}
					}
					
				};
				
				this.processPostType = this.processAuthor;
				
				
				this.processPostMeta = function($container)
				{
					var fieldType = $container.attr("data-sf-field-type");
					var inputType = $container.attr("data-sf-field-input-type");
					var metaType = $container.attr("data-sf-meta-type");
					
					var fieldVal = "";
					var $field;
					var fieldName = "";
					
					
					
					if(metaType=="number")
					{
						if((inputType=="range-slider")||(inputType=="range-number"))
						{
							$field = $container.find(".meta-range input");
							
							var values = [];
							$field.each(function(){
								
								values.push($(this).val());
							
							});
							
							fieldVal = values.join("+");
							
						}
						else if(inputType=="range-radio")
						{
							$field = $container.find("ul > li input:radio");
							
							if($field.length>0)
							{
								fieldVal = self.getRadioVal($field);
							}
						}
						else if(inputType=="range-select")
						{
							$field = $container.find("select");
							
							if($field.length>0)
							{
								fieldVal = self.getSelectVal($field);
							}
						}
						else if(inputType=="range-checkbox")
						{
							$field = $container.find("ul > li input:checkbox");
							
							if($field.length>0)
							{
								fieldVal = self.getCheckboxVal($field, "and");
							}
						}
						
						if($field.length>0)
						{
							fieldName = $field.attr("name").replace('[]', '');
						}
					}
					else if(metaType=="choice")
					{
						if(inputType=="select")
						{
							$field = $container.find("select");
							
							fieldVal = self.getMetaSelectVal($field); 
						}
						else if(inputType=="multiselect")
						{
							$field = $container.find("select");
							var operator = $field.attr("data-operator");
							
							fieldVal = self.getMetaMultiSelectVal($field, operator);
						}
						else if(inputType=="checkbox")
						{
							$field = $container.find("ul > li input:checkbox");
							
							if($field.length>0)
							{
								var operator = $container.find("> ul").attr("data-operator");
								fieldVal = self.getMetaCheckboxVal($field, operator);
							}
						}
						else if(inputType=="radio")
						{
							$field = $container.find("ul > li input:radio");
							
							if($field.length>0)
							{
								fieldVal = self.getMetaRadioVal($field);
							}
						}
						
						if($field.length>0)
						{
							fieldName = $field.attr("name").replace('[]', '');
							
							//for those who insist on using & ampersands in the name of the custom field (!)
							fieldName = (fieldName);
						}
						
					}
					else if(metaType=="date")
					{
						self.processPostDate($container);
					}
					
					if(typeof(fieldVal)!="undefined")
					{
						if(fieldVal!="")
						{
							self.url_components += "&"+encodeURIComponent(fieldName)+"="+fieldVal;
							self.url_params[fieldName] = fieldVal;
						}
					}
				}
				
				
				this.processPostDate = function($container)
				{
					var fieldType = $container.attr("data-sf-field-type");
					var inputType = $container.attr("data-sf-field-input-type");
					
					var $field;
					var fieldName = "";
					var fieldVal = "";
					
					$field = $container.find("ul > li input:text");
					fieldName = $field.attr("name").replace('[]', '');
					
					var dates = [];
					$field.each(function(){
						
						dates.push($(this).val());
					
					});
					
					if($field.length==2)
					{
						if((dates[0]!="")||(dates[1]!=""))
						{
							fieldVal = dates.join("+");
							fieldVal = fieldVal.replace(/\//g,'');
						}
					}
					else if($field.length==1)
					{
						if(dates[0]!="")
						{
							fieldVal = dates.join("+");
							fieldVal = fieldVal.replace(/\//g,'');
						}
					}
					
					if(typeof(fieldVal)!="undefined")
					{
						if(fieldVal!="")
						{
							var fieldSlug = "";
							
							if(fieldName=="_sf_post_date")
							{
								fieldSlug = "post_date";
							}
							else
							{
								fieldSlug = fieldName;
							}
							
							if(fieldSlug!="")
							{
								self.url_components += "&"+fieldSlug+"="+fieldVal;
								self.url_params[fieldSlug] = fieldVal;
							}
						}
					}
					
				}
				
				this.processTaxonomy = function($container)
				{
					//if()					
					//var fieldName = $(this).attr("data-sf-field-name");
					var fieldType = $container.attr("data-sf-field-type");
					var inputType = $container.attr("data-sf-field-input-type");
					
					var $field;
					var fieldName = "";
					var fieldVal = "";
					
					if(inputType=="select")
					{
						$field = $container.find("select");
						fieldName = $field.attr("name").replace('[]', '');
						
						fieldVal = self.getSelectVal($field); 
					}
					else if(inputType=="multiselect")
					{
						$field = $container.find("select");
						fieldName = $field.attr("name").replace('[]', '');
						var operator = $field.attr("data-operator");
						
						fieldVal = self.getMultiSelectVal($field, operator);
					}
					else if(inputType=="checkbox")
					{
						$field = $container.find("ul > li input:checkbox");
						if($field.length>0)
						{
							fieldName = $field.attr("name").replace('[]', '');
													
							var operator = $container.find("> ul").attr("data-operator");
							fieldVal = self.getCheckboxVal($field, operator);
						}
					}
					else if(inputType=="radio")
					{
						$field = $container.find("ul > li input:radio");
						if($field.length>0)
						{
							fieldName = $field.attr("name").replace('[]', '');
							
							fieldVal = self.getRadioVal($field);
						}
					}
					
					if(typeof(fieldVal)!="undefined")
					{
						if(fieldVal!="")
						{
							self.url_components += "&"+fieldName+"="+fieldVal;
							self.url_params[fieldName] = fieldVal;
						}
					}
				};//	----

				
				this.handleLegacyAjaxUpdate = function(data, use_history_api, ajax_target_attr, $ajax_target_object)
				{
					var $data_obj = $(data);
					
					$ajax_target_object.html($data_obj.find(ajax_target_attr).html());
					
					
					if((self.update_ajax_url==1)&&(use_history_api==1))
					{
						var $ajaxed_search_form_post = $data_obj.find('*[data-sf-form-id='+self.sfid+']');
						var this_url = $ajaxed_search_form_post.attr('data-ajax-url');
						
						//now check if the browser supports history state push :)
						if (window.history && window.history.pushState)
						{
							history.pushState(null, null, this_url);
						}
					}
				};
		
		
				this.fetchLegacyAjaxResults = function(override_url)
				{
					var use_history_api = 0;
			
					if (window.history && window.history.pushState)
					{
						use_history_api = $this.attr("data-use-history-api");
					}
					
					var $ajax_target_object = jQuery(self.ajax_target_attr);
					
					$ajax_target_object.animate({ opacity: 0.5 }, "fast"); //loading
					
					var data = {};
					data.sfid = self.sfid;
					data.targetSelector = self.ajax_target_attr;
					$this.trigger("sf:ajaxstart", [ data ]);
					
					var ajaxLastTimestamp = 0;
					
					var url_string = self.getCleanURLwLang(SF_LDATA.home_url)
					
					if(typeof(override_url)!="undefined")
					{
						url_string = override_url;
					}
					
					var jqxhr = $.get(url_string, function(data, status, request)
					{
						//var $timestamp_input = $(data).find('.sf_ajax_timestamp');
						//var newTimestamp = 0;
						//if($timestamp_input.length>0)
						//{
						//	newTimestamp = $timestamp_input.val()
						//}
						
						//if(newTimestamp>ajaxLastTimestamp)
						//{
							//ajaxLastTimestamp = newTimestamp;
							
							self.handleLegacyAjaxUpdate(data, use_history_api, self.ajax_target_attr, $ajax_target_object);
							self.setupLegacyPagination();
							
						//}
						//else
						//{
							
						//}
						
						
						if((self.scroll_on_action==self.ajax_action)||(self.scroll_on_action=="all"))
						{
							self.scrollToPos(); //scroll the window if it has been set
							self.ajax_action = "";
						}
					
					}).fail(function()
					{
						
					}).always(function()
					{
						$ajax_target_object.stop(true,true).animate({ opacity: 1}, "fast"); //finished loading
						
						var data = {};
						data.sfid = self.sfid;
						data.targetSelector = self.ajax_target_attr;						
						$this.trigger("sf:ajaxfinish", [ data ]);
					});

				};
				this.stripQueryStringAndHashFromPath = function(url) {
				  return url.split("?")[0].split("#")[0];
				}
				this.gup = function( name, url ) {
				  if (!url) url = location.href
				  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
				  var regexS = "[\\?&]"+name+"=([^&#]*)";
				  var regex = new RegExp( regexS );
				  var results = regex.exec( url );
				  return results == null ? null : results[1];
				}
				
				this.getCleanURLwLang = function(the_url)
				{
					var langParam = self.gup("lang", the_url);
					var pageID = self.gup("page_id", the_url); //when using ajax & the shortcode method
					
					the_url = this.stripQueryStringAndHashFromPath(the_url);
					
					var components_url = jQuery.param(self.url_params).replaceAll("%2B", "+").replaceAll("%2C", ",");
					
					var returnUrl = self.stripTrailingSlash(the_url)+"/";
					
					//append slug to URL if necessary
					if((self.page_slug!="")&&(self.permalinks_enabled)&&(self.display_results_as=="archive"))
					{
						returnUrl += self.page_slug + "/";
					}
					
					//add url search params to URL
					if(components_url!="")
					{
						returnUrl += "?" + components_url;
					}
					
					//and if lang is set append that too
					if(typeof(langParam)!="undefined")
					{
						if(langParam!=null)
						{
							if(returnUrl.indexOf("?") != -1)
							{
								returnUrl += "&";
							}
							else
							{
								returnUrl += "?";
							}
							returnUrl += "lang="+langParam;
						}
					}
					
					if(!self.permalinks_enabled)
					{
						//and if get_results is set append that too
						//when using ajax & the shortcode method
						if(typeof(pageID)!="undefined")
						{
							if(pageID!=null)
							{
								if(returnUrl.indexOf("?") != -1)
								{
									returnUrl += "&";
								}
								else
								{
									returnUrl += "?";
								}
								returnUrl += "page_id="+pageID;
							}
						}
					}
					
					return returnUrl;
				}
				
				this.fetchShortcodeAjaxResults = function()
				{
					var pageNumber = self.$ajax_target_object.attr("data-paged");
					if(typeof(pageNumber)=="undefined")
					{
						pageNumber = 1;
					}
					
					var data = {};
					data.sfid = self.sfid;
					data.targetSelector = self.ajax_target_attr;
					$this.trigger("sf:ajaxstart", [ data ]);
					
					self.$ajax_target_object.animate({ opacity: 0.5 }, "fast"); //loading
					
					var components_url = jQuery.param(self.url_params).replaceAll("%2B", "+").replaceAll("%2C", ",");
					
					if(components_url!="")
					{
						components_url = "&" + components_url;
					}
					
					if(self.lang_code!="")
					{
						components_url += "&lang="+self.lang_code;
					}
					
					var jqxhr = $.get(SF_LDATA.ajax_url+"?action=get_results&paged="+pageNumber+"&sfid="+self.sfid+components_url, function(data, status, request)
					{
						self.$ajax_target_object.html(data);
						
						//setup pagination
						self.setupShortcodePagination();
						
						var use_history_api = 0;
						if (window.history && window.history.pushState)
						{
							use_history_api = $this.attr("data-use-history-api");
						}
						
						/*var pageNumber = self.$ajax_target_object.attr("data-paged");
						if(typeof(pageNumber)=="undefined")
						{
							pageNumber = 1;
						}*/
						
						var url_components = jQuery.param(self.url_params).replace(/^&/, '');
						
						if(pageNumber>1)
						{
							if(url_components=="")
							{
								url_components = "paged="+pageNumber;
							}
							else
							{
								url_components += "&paged="+pageNumber;
							}
						}
						if(url_components!="")
						{
							url_components = "?" + url_components;
						}
						
						if((self.update_ajax_url==1)&&(use_history_api==1))
						{
							if(self.ajax_results_url!="")
							{
								var this_url = self.getCleanURLwLang(self.ajax_results_url);
								
								//now check if the browser supports history state push :)
								if (window.history && window.history.pushState)
								{
									history.pushState(null, null, this_url);
								}
							}
						}
						
						if((self.scroll_on_action==self.ajax_action)||(self.scroll_on_action=="all"))
						{
							self.scrollToPos(); //scroll the window if it has been set
							self.ajax_action = "";
						}
						
					}).fail(function()
					{
						
					}).always(function()
					{
						self.$ajax_target_object.stop(true,true).animate({ opacity: 1}, "fast"); //finished loading
						
						var data = {};
						data.sfid = self.sfid;
						data.targetSelector = self.ajax_target_attr;						
						$this.trigger("sf:ajaxfinish", [ data ]);
					});
				};
				this.stripTrailingSlash = function (str) {
					if(str.substr(-1) == '/') {
						return str.substr(0, str.length - 1);
					}
					return str;
				}
				
				this.setupLegacyPagination = function()
				{
					if(typeof(self.ajax_links_selector)!="undefined")
					{
						var $ajax_links_object = jQuery(self.ajax_links_selector);
						
						if($ajax_links_object.length>0)
						{
							$ajax_links_object.on('click', function(e) {
								
								e.preventDefault();
								
								var link = jQuery(this).attr('href');
								self.ajax_action = "pagination";
								
								self.fetchLegacyAjaxResults(link);
								return false;
							});
						}
					}
				}
				this.setupShortcodePagination = function()
				{
					if(typeof(self.ajax_links_selector)!="undefined")
					{
						var $ajax_links_object = jQuery(self.ajax_links_selector);
						
						if($ajax_links_object.length>0)
						{
							$ajax_links_object.on('click', function(e) {
								
								e.preventDefault();
								
								var link = jQuery(this).attr('href');
								self.ajax_action = "pagination";
								
								var pageNumber = self.getPagedFromURL(link);
								self.$ajax_target_object.attr("data-paged", pageNumber);
								
								self.fetchShortcodeAjaxResults();
								return false;
							});
						}
					}
					
					
					/* will be deprecated - instead will use regular page nav */
					var $pagiLink =  self.$ajax_target_object.find(".pagi-prev, .pagi-next, .pagi-first, .pagi-last, .pagi-num")
					
					if($pagiLink.length>0)
					{
						$pagiLink.click(function(e){
							
							e.preventDefault();
							
							if(!$(this).hasClass("disabled"))
							{
								var pageNumber = $(this).attr("data-page-number");
								self.$ajax_target_object.attr("data-paged", pageNumber);
								self.ajax_action = "pagination";
								self.fetchShortcodeAjaxResults();
							}
							
							return false;
							
						});
					}
				};
				
				this.buildUrlComponents = function(){
						
					self.$fields.each(function(){
						
						var fieldName = $(this).attr("data-sf-field-name");
						var fieldType = $(this).attr("data-sf-field-type");
						
						
						if(fieldType=="search")
						{
							self.processSearchField($(this));
						}
						else if((fieldType=="tag")||(fieldType=="category")||(fieldType=="taxonomy"))
						{
							self.processTaxonomy($(this));
						}
						else if(fieldType=="sort_order")
						{
							self.processSortOrderField($(this));
						}
						else if(fieldType=="author")
						{
							self.processAuthor($(this));
						}
						else if(fieldType=="post_type")
						{
							self.processPostType($(this));
						}
						else if(fieldType=="post_date")
						{
							self.processPostDate($(this));
						}
						else if(fieldType=="post_meta")
						{
							self.processPostMeta($(this));
							
						}
						else
						{
							//console.log(self.$fields);
						}
						
					});
					
				};
				
				this.getPagedFromURL = function(URL){
					
					//first test to see if we have "/page/4/" in the URL
					var urlParts = URL.split("/");
					
					var pagedVal = 1;
					var foundPaged = false;
					for(var i=urlParts.length-1; i>0; i--)
					{
						if(urlParts[i]=="page")
						{
							//then we know then next part will have the page number
							if(typeof(urlParts[i+1])!="undefined")
							{
								foundPaged = true;
								pagedVal = urlParts[i+1];
								break;
							}
						}
					}
					
					if(!foundPaged)
					{//if we still havent found paged, look at the params "paged=4"
					
						var tpVal = self.getQueryParamFromURL("paged", URL);
						if((typeof(tpVal)=="string")||(typeof(tpVal)=="number"))
						{
							pagedVal = tpVal;
						}
					}
					
					return pagedVal;
				};
				
				this.getQueryParamFromURL = function(key, URL){
					
					var vars = [];
					
					var qstring = URL.split('?')[1];
					
					if(qstring != undefined){
						
						key = key.replace(/[*+?^$.\[\]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
						var match = qstring.match(new RegExp("[?&]"+key+"=([^&]+)(&|$)"));
						return match && decodeURIComponent(match[1].replace(/\+/g, " "));
					}
					
				};
				
				this.resetForm = function(e)
				{
					self.url_components = "";
					self.url_params = {};
					
					//unset all fields
					
					//unset select
					self.$fields.each(function(){
						
						var $field = $(this);
						$field.find("select:not([multiple='multiple']) > option:first-child").prop("selected", true);
						$field.find("select[multiple='multiple'] > option").prop("selected", false);
						
						$field.find("input[type='checkbox']").prop("checked", false);
						$field.find("li:first-child input[type='radio']").prop("checked", true);
						$field.find("input[type='text']").val("");
						
						
						//number range - 2 number input fields
						$field.find("input[type='number']").each(function(index){
							
							var $thisInput = $(this);
							
							if($thisInput.parent().hasClass("meta-range"))
							{
									if(index==0)
									{
										$thisInput.val($thisInput.attr("min"));
									}
									else if(index==1)
									{
										$thisInput.val($thisInput.attr("max"));
									}
							}
								
						});
												
						//number slider - noUiSlider
						$field.find(".meta-slider").each(function(index){
							
							var $thisSlider = $(this);
							var $sliderEl = $thisSlider.closest(".meta-range");
							var minVal = $sliderEl.attr("data-min");
							var maxVal = $sliderEl.attr("data-max");
							$thisSlider.val([minVal, maxVal]);
							
						});
						
						
						
						//need to see if any are combobox and act accordingly
						$field.find("select").trigger("chosen:updated"); //for chosen only
						
						
					});
					
					
					
					
					
					self.submitForm();
				};
				
				this.submitForm = function(e){
					
					self.url_components = "";
					self.url_params = {};
					
					if(self.display_results_as=="shortcode")
					{
						self.skipSFID = true;
						/*if(self.$ajax_target_object.length!=1)
						{//if the 
							if(self.ajax_results_url!="")
							{
								self.skipSFID = true; //when redirecting from ajax /w shortcode don't add sfid - otherwise the current page will be filtered and can result in a 404
							}
						}*/
					}
					
					
					if(self.page_slug!="")
					{
						self.skipSFID = true;
					}
					
					if(self.skipSFID==false)
					{
						self.url_components += "&sfid="+self.sfid;
						self.url_params['sfid'] = self.sfid;
						
					}
					
					self.buildUrlComponents();
					//loop through all the fields
					
					self.$ajax_target_object = $($this.attr("data-ajax-target"));
					self.$ajax_target_object.attr("data-paged", 1);
					
					
						if(self.display_results_as=="shortcode")
						{
							
							if(self.use_ajax==1)
							{
								//if we can find the container on the page
								if(self.$ajax_target_object.length==1)
								{
									self.ajax_action = "submit";
									self.fetchShortcodeAjaxResults();
								}
								else
								{//if the 
									if(self.ajax_results_url!="")
									{
										window.location.href = self.getCleanURLwLang(self.ajax_results_url);
									}
								}
							}
							else
							{
								window.location.href = self.getCleanURLwLang(self.ajax_results_url);
							}
						}
						else
						{
							if(self.use_ajax==1)
							{
								if(self.$ajax_target_object.length==1)
								{
									self.ajax_action = "submit";
									self.fetchLegacyAjaxResults();
								}
								else
								{//if the 
									if(SF_LDATA.home_url!="")
									{
										window.location.href = self.getCleanURLwLang(SF_LDATA.home_url);
									}
								}
								
							}
							else
							{
								window.location.href = self.getCleanURLwLang(SF_LDATA.home_url);
							}
						}
					
					return false;
				};//	----

				if(self.display_results_as=="shortcode")
				{
					self.setupShortcodePagination();
					self.buildUrlComponents();
				}
				else
				{
					self.setupLegacyPagination();
				}
				
				
		
				$this.submit(this.submitForm);
				
				
				
				
				
				this.inputUpdate = function(delayDuration)
				{
					if(typeof(delayDuration)=="undefined")
					{
						var delayDuration = 300;
					}
					
					self.resetTimer(delayDuration);
				}
				
				this.dateInputType = function()
				{
					var $thise = $(this);
					
					if(self.auto_update==1)
					{
						var $tf_date_pickers = $this.find(".datepicker");
						var no_date_pickers = $tf_date_pickers.length;
						
						if(no_date_pickers>1)
						{					
							//then it is a date range, so make sure both fields are filled before updating
							var dp_counter = 0;
							var dp_empty_field_count = 0;
							$tf_date_pickers.each(function(){
							
								if($(this).val()=="")
								{
									dp_empty_field_count++;
								}
								
								dp_counter++;
							});
							
							if(dp_empty_field_count==0)
							{
								self.inputUpdate(1200);
							}
						}
						else
						{
							self.inputUpdate(1200);
						}
					}
				}
				
				this.scrollToPos = function()
				{
					var offset = 0;
					if(self.use_ajax==1)
					{
						if(self.scroll_to_pos=="window")
						{
							offset = 0;
						}
						else if(self.scroll_to_pos=="form")
						{
							offset = $this.offset().top;
						}
						else if(self.scroll_to_pos=="results")
						{
							if(self.$ajax_target_object.length>0)
							{
								offset = self.$ajax_target_object.offset().top;
							}
						}
						else if(self.scroll_to_pos=="custom")
						{
							//custom_scroll_to
							if(self.$custom_scroll_to.length>0)
							{
								offset = self.$custom_scroll_to.offset().top;
							}
						}
						
						$("html, body").animate({
							  scrollTop: offset
							}, "normal", "easeOutQuad" );
					}
					
					//scroll_to_pos;
					//custom_scroll_to;
				}
				
				
				//if(self.use_ajax==1)
				//{
					if(self.auto_update==1)
					{
						
							$this.on('change', 'input[type=radio], input[type=checkbox], select', function(e)
							{
								self.inputUpdate(200);
							});
							$this.on('change', '.meta-slider', function(e)
							{
								self.inputUpdate(200);
							});
							$this.on('input', 'input[type=number]', function(e)
							{
								self.inputUpdate(800);
							});
							
							$this.on('input', 'input[type=text]:not(.datepicker)', function()
							{
								self.inputUpdate(1200);									
							});
							$this.on('input', 'input.datepicker', self.dateInputType);
						
					}
					
				//}
				
				
				this.resetTimer = function(delayDuration)
				{
					clearTimeout(self.inputTimer)
					self.inputTimer = setTimeout(self.submitForm, delayDuration);
					
				}
				
				this.addDatePickers = function()
				{
					var $date_picker = $this.find(".datepicker");
					
					if($date_picker.length>0)
					{
						$date_picker.each(function(){
						
							var $this = $(this);
							var dateFormat = $this.closest(".sf_date_field").attr("data-date-format");
							
							var $closest_date_wrap = $this.closest(".sf_date_field");
							if($closest_date_wrap.length>0)
							{
								dateFormat = $closest_date_wrap.attr("data-date-format");
							}
						
							$this.datepicker({
								inline: true,
								showOtherMonths: true,
								onSelect: self.dateSelect,
								dateFormat: dateFormat
							});
							
							
						});
						
						if($('.ll-skin-melon').length==0)
						{
							$date_picker.datepicker('widget').wrap('<div class="ll-skin-melon"/>');
						}
						
					}
				}
				
				this.dateSelect = function()
				{
					var $thise = $(this);
					
					if(self.auto_update==1)
					{
						var $tf_date_pickers = $this.find(".datepicker");
						var no_date_pickers = $tf_date_pickers.length;
						
						if(no_date_pickers>1)
						{					
							//then it is a date range, so make sure both fields are filled before updating
							var dp_counter = 0;
							var dp_empty_field_count = 0;
							$tf_date_pickers.each(function(){
							
								if($(this).val()=="")
								{
									dp_empty_field_count++;
								}
								
								dp_counter++;
							});
							
							if(dp_empty_field_count==0)
							{
								self.inputUpdate(1);
							}
						}
						else
						{
							self.inputUpdate(1);
						}
					}
				}
				
				this.addRangeSliders = function()
				{
					var $meta_range = $this.find(".meta-range");
					
					if($meta_range.length>0)
					{		
						$meta_range.each(function(){
							
							var $this = $(this);
							var min = $this.attr("data-min");
							var max = $this.attr("data-max");
							var smin = $this.attr("data-start-min");
							var smax = $this.attr("data-start-max");
							var step = $this.attr("data-step");
							var $start_val = $this.find('.range-min');
							var $end_val = $this.find('.range-max');
							
							$(this).find(".meta-slider").noUiSlider({
								range: [min,max],
								start: [smin,smax],
								handles: 2,
								connect: true,
								step: step,
								serialization: {
									 to: [ $start_val, $end_val],
									 resolution: 1
								},
								behaviour: 'extend-tap'
							});
							
						});
					}
				}
				
				
				this.addDatePickers();
				this.addRangeSliders();
				
				//init combo boxes
				var $chosen = $this.find("select[data-combobox=1]");
				
				if($chosen.length>0)
				{
					if (typeof $chosen.chosen != "undefined")
					{
						// safe to use the function
						$chosen.chosen();
					}
				}
				
				var data = {};
				data.sfid = self.sfid;
				data.targetSelector = self.ajax_target_attr;						
				$this.trigger("sf:init", [ data ]);
				
				
				
				if(this.debug_mode=="1")
				{//error logging
					
					if(self.use_ajax==1)
					{
						if(self.display_results_as=="shortcode")
						{
							if(self.$ajax_target_object.length==0)
							{
								console.log("Search & Filter | Form ID: "+self.sfid+": cannot find the results container on this page - ensure you use the shortcode on this page or provide a URL where it can be found (Results URL)");
							}
							if(self.ajax_results_url=="")
							{
								console.log("Search & Filter | Form ID: "+self.sfid+": No Results URL has been defined - ensure that you enter this in order to use the Search Form on any page)");
							}
						}
						else
						{
							if(self.$ajax_target_object.length==0)
							{
								console.log("Search & Filter | Form ID: "+self.sfid+": cannot find the results container on this page - ensure you use are using the right content selector");
							}
						}
					}
					else
					{
						
					}
					
				}//	----
			});
		}
		
		$(".searchandfilter").searchFilterForm();
		
		$(".search-filter-reset").on("click",function(e){
			
			e.preventDefault();
			
			var searchFormID = typeof($(this).attr("data-search-form-id"))!="undefined" ? $(this).attr("data-search-form-id") : "";
			
			var $linked = $("#search-filter-form-"+searchFormID).searchFilterForm({action: "reset"});
			
			return false;
			
		});
		
	});	
	$.easing.jswing=$.easing.swing;$.extend($.easing,{def:"easeOutQuad",swing:function(e,t,n,r,i){return $.easing[$.easing.def](e,t,n,r,i)},easeInQuad:function(e,t,n,r,i){return r*(t/=i)*t+n},easeOutQuad:function(e,t,n,r,i){return-r*(t/=i)*(t-2)+n},easeInOutQuad:function(e,t,n,r,i){if((t/=i/2)<1)return r/2*t*t+n;return-r/2*(--t*(t-2)-1)+n},easeInCubic:function(e,t,n,r,i){return r*(t/=i)*t*t+n},easeOutCubic:function(e,t,n,r,i){return r*((t=t/i-1)*t*t+1)+n},easeInOutCubic:function(e,t,n,r,i){if((t/=i/2)<1)return r/2*t*t*t+n;return r/2*((t-=2)*t*t+2)+n},easeInQuart:function(e,t,n,r,i){return r*(t/=i)*t*t*t+n},easeOutQuart:function(e,t,n,r,i){return-r*((t=t/i-1)*t*t*t-1)+n},easeInOutQuart:function(e,t,n,r,i){if((t/=i/2)<1)return r/2*t*t*t*t+n;return-r/2*((t-=2)*t*t*t-2)+n},easeInQuint:function(e,t,n,r,i){return r*(t/=i)*t*t*t*t+n},easeOutQuint:function(e,t,n,r,i){return r*((t=t/i-1)*t*t*t*t+1)+n},easeInOutQuint:function(e,t,n,r,i){if((t/=i/2)<1)return r/2*t*t*t*t*t+n;return r/2*((t-=2)*t*t*t*t+2)+n},easeInSine:function(e,t,n,r,i){return-r*Math.cos(t/i*(Math.PI/2))+r+n},easeOutSine:function(e,t,n,r,i){return r*Math.sin(t/i*(Math.PI/2))+n},easeInOutSine:function(e,t,n,r,i){return-r/2*(Math.cos(Math.PI*t/i)-1)+n},easeInExpo:function(e,t,n,r,i){return t==0?n:r*Math.pow(2,10*(t/i-1))+n},easeOutExpo:function(e,t,n,r,i){return t==i?n+r:r*(-Math.pow(2,-10*t/i)+1)+n},easeInOutExpo:function(e,t,n,r,i){if(t==0)return n;if(t==i)return n+r;if((t/=i/2)<1)return r/2*Math.pow(2,10*(t-1))+n;return r/2*(-Math.pow(2,-10*--t)+2)+n},easeInCirc:function(e,t,n,r,i){return-r*(Math.sqrt(1-(t/=i)*t)-1)+n},easeOutCirc:function(e,t,n,r,i){return r*Math.sqrt(1-(t=t/i-1)*t)+n},easeInOutCirc:function(e,t,n,r,i){if((t/=i/2)<1)return-r/2*(Math.sqrt(1-t*t)-1)+n;return r/2*(Math.sqrt(1-(t-=2)*t)+1)+n},easeInElastic:function(e,t,n,r,i){var s=1.70158;var o=0;var u=r;if(t==0)return n;if((t/=i)==1)return n+r;if(!o)o=i*.3;if(u<Math.abs(r)){u=r;var s=o/4}else var s=o/(2*Math.PI)*Math.asin(r/u);return-(u*Math.pow(2,10*(t-=1))*Math.sin((t*i-s)*2*Math.PI/o))+n},easeOutElastic:function(e,t,n,r,i){var s=1.70158;var o=0;var u=r;if(t==0)return n;if((t/=i)==1)return n+r;if(!o)o=i*.3;if(u<Math.abs(r)){u=r;var s=o/4}else var s=o/(2*Math.PI)*Math.asin(r/u);return u*Math.pow(2,-10*t)*Math.sin((t*i-s)*2*Math.PI/o)+r+n},easeInOutElastic:function(e,t,n,r,i){var s=1.70158;var o=0;var u=r;if(t==0)return n;if((t/=i/2)==2)return n+r;if(!o)o=i*.3*1.5;if(u<Math.abs(r)){u=r;var s=o/4}else var s=o/(2*Math.PI)*Math.asin(r/u);if(t<1)return-.5*u*Math.pow(2,10*(t-=1))*Math.sin((t*i-s)*2*Math.PI/o)+n;return u*Math.pow(2,-10*(t-=1))*Math.sin((t*i-s)*2*Math.PI/o)*.5+r+n},easeInBack:function(e,t,n,r,i,s){if(s==undefined)s=1.70158;return r*(t/=i)*t*((s+1)*t-s)+n},easeOutBack:function(e,t,n,r,i,s){if(s==undefined)s=1.70158;return r*((t=t/i-1)*t*((s+1)*t+s)+1)+n},easeInOutBack:function(e,t,n,r,i,s){if(s==undefined)s=1.70158;if((t/=i/2)<1)return r/2*t*t*(((s*=1.525)+1)*t-s)+n;return r/2*((t-=2)*t*(((s*=1.525)+1)*t+s)+2)+n},easeInBounce:function(e,t,n,r,i){return r-$.easing.easeOutBounce(e,i-t,0,r,i)+n},easeOutBounce:function(e,t,n,r,i){if((t/=i)<1/2.75){return r*7.5625*t*t+n}else if(t<2/2.75){return r*(7.5625*(t-=1.5/2.75)*t+.75)+n}else if(t<2.5/2.75){return r*(7.5625*(t-=2.25/2.75)*t+.9375)+n}else{return r*(7.5625*(t-=2.625/2.75)*t+.984375)+n}},easeInOutBounce:function(e,t,n,r,i){if(t<i/2)return $.easing.easeInBounce(e,t*2,0,r,i)*.5+n;return $.easing.easeOutBounce(e,t*2-i,0,r,i)*.5+r*.5+n}})	
			
})(window.jQuery);

/* noui slider */
(function(f){if(f.zepto&&!f.fn.removeData)throw new ReferenceError("Zepto is loaded without the data module.");f.fn.noUiSlider=function(C,D){function s(a,b){return 100*b/(a[1]-a[0])}function E(a,b){return b*(a[1]-a[0])/100+a[0]}function t(a){return a instanceof f||f.zepto&&f.zepto.isZ(a)}function n(a){return!isNaN(parseFloat(a))&&isFinite(a)}function r(a,b){f.isArray(a)||(a=[a]);f.each(a,function(){"function"===typeof this&&this.call(b)})}function F(a,b){return function(){var c=[null,null];c[b]=f(this).val();
a.val(c,!0)}}function G(a,b){a=a.toFixed(b.decimals);0===parseFloat(a)&&(a=a.replace("-0","0"));return a.replace(".",b.serialization.mark)}function u(a){return parseFloat(a.toFixed(7))}function p(a,b,c,d){var e=d.target;a=a.replace(/\s/g,h+" ")+h;b.on(a,function(a){var b=e.attr("disabled");if(e.hasClass("noUi-state-tap")||void 0!==b&&null!==b)return!1;var g;a.preventDefault();var b=0===a.type.indexOf("touch"),h=0===a.type.indexOf("mouse"),l=0===a.type.indexOf("pointer"),v,H=a;0===a.type.indexOf("MSPointer")&&
(l=!0);a.originalEvent&&(a=a.originalEvent);b&&(g=a.changedTouches[0].pageX,v=a.changedTouches[0].pageY);if(h||l)l||void 0!==window.pageXOffset||(window.pageXOffset=document.documentElement.scrollLeft,window.pageYOffset=document.documentElement.scrollTop),g=a.clientX+window.pageXOffset,v=a.clientY+window.pageYOffset;g=f.extend(H,{pointX:g,pointY:v,cursor:h});c(g,d,e.data("base").data("options"))})}function I(a){var b=this.target;if(void 0===a)return this.element.data("value");!0===a?a=this.element.data("value"):
this.element.data("value",a);void 0!==a&&f.each(this.elements,function(){if("function"===typeof this)this.call(b,a);else this[0][this[1]](a)})}function J(a,b,c){if(t(b)){var d=[],e=a.data("target");a.data("options").direction&&(c=c?0:1);b.each(function(){f(this).on("change"+h,F(e,c));d.push([f(this),"val"])});return d}"string"===typeof b&&(b=[f('<input type="hidden" name="'+b+'">').appendTo(a).addClass(g[3]).change(function(a){a.stopPropagation()}),"val"]);return[b]}function K(a,b,c){var d=[];f.each(c.to[b],
function(e){d=d.concat(J(a,c.to[b][e],b))});return{element:a,elements:d,target:a.data("target"),val:I}}function L(a,b){var c=a.data("target");c.hasClass(g[14])||(b||(c.addClass(g[15]),setTimeout(function(){c.removeClass(g[15])},450)),c.addClass(g[14]),r(a.data("options").h,c))}function w(a,b){var c=a.data("options");b=u(b);a.data("target").removeClass(g[14]);a.css(c.style,b+"%").data("pct",b);a.is(":first-child")&&a.toggleClass(g[13],50<b);c.direction&&(b=100-b);a.data("store").val(G(E(c.range,b),
c))}function x(a,b){var c=a.data("base"),d=c.data("options"),c=c.data("handles"),e=0,k=100;if(!n(b))return!1;if(d.step){var m=d.step;b=Math.round(b/m)*m}1<c.length&&(a[0]!==c[0][0]?e=u(c[0].data("pct")+d.margin):k=u(c[1].data("pct")-d.margin));b=Math.min(Math.max(b,e),0>k?100:k);if(b===a.data("pct"))return[e?e:!1,100===k?!1:k];w(a,b);return!0}function A(a,b,c,d){a.addClass(g[5]);setTimeout(function(){a.removeClass(g[5])},300);x(b,c);r(d,a.data("target"));a.data("target").change()}function M(a,b,c){var d=
b.a,e=a[b.d]-b.start[b.d],e=100*e/b.size;if(1===d.length){if(a=x(d[0],b.c[0]+e),!0!==a){0<=f.inArray(d[0].data("pct"),a)&&L(b.b,!c.margin);return}}else{var k,m;c.step&&(a=c.step,e=Math.round(e/a)*a);a=k=b.c[0]+e;e=m=b.c[1]+e;0>a?(e+=-1*a,a=0):100<e&&(a-=e-100,e=100);if(0>k&&!a&&!d[0].data("pct")||100===e&&100<m&&100===d[1].data("pct"))return;w(d[0],a);w(d[1],e)}r(c.slide,b.target)}function N(a,b,c){1===b.a.length&&b.a[0].data("grab").removeClass(g[4]);a.cursor&&y.css("cursor","").off(h);z.off(h);
b.target.removeClass(g[14]+" "+g[20]).change();r(c.set,b.target)}function B(a,b,c){1===b.a.length&&b.a[0].data("grab").addClass(g[4]);a.stopPropagation();p(q.move,z,M,{start:a,b:b.b,target:b.target,a:b.a,c:[b.a[0].data("pct"),b.a[b.a.length-1].data("pct")],d:c.orientation?"pointY":"pointX",size:c.orientation?b.b.height():b.b.width()});p(q.end,z,N,{target:b.target,a:b.a});a.cursor&&(y.css("cursor",f(a.target).css("cursor")),1<b.a.length&&b.target.addClass(g[20]),y.on("selectstart"+h,function(){return!1}))}
function O(a,b,c){b=b.b;var d,e;a.stopPropagation();c.orientation?(a=a.pointY,e=b.height()):(a=a.pointX,e=b.width());d=b.data("handles");var k=a,m=c.style;1===d.length?d=d[0]:(m=d[0].offset()[m]+d[1].offset()[m],d=d[k<m/2?0:1]);a=100*(a-b.offset()[c.style])/e;A(b,d,a,[c.slide,c.set])}function P(a,b,c){var d=b.b.data("handles"),e;e=c.orientation?a.pointY:a.pointX;a=(e=e<b.b.offset()[c.style])?0:100;e=e?0:d.length-1;A(b.b,d[e],a,[c.slide,c.set])}function Q(a,b){function c(a){if(2!==a.length)return!1;
a=[parseFloat(a[0]),parseFloat(a[1])];return!n(a[0])||!n(a[1])||a[1]<a[0]?!1:a}var d={f:function(a,b){switch(a){case 1:case 0.1:case 0.01:case 0.001:case 1E-4:case 1E-5:a=a.toString().split(".");b.decimals="1"===a[0]?0:a[1].length;break;case void 0:b.decimals=2;break;default:return!1}return!0},e:function(a,b,c){if(!a)return b[c].mark=".",!0;switch(a){case ".":case ",":return!0;default:return!1}},g:function(a,b,c){function d(a){return t(a)||"string"===typeof a||"function"===typeof a||!1===a||t(a[0])&&
"function"===typeof a[0][a[1]]}function g(a){var b=[[],[]];d(a)?b[0].push(a):f.each(a,function(a,e){1<a||(d(e)?b[a].push(e):b[a]=b[a].concat(e))});return b}if(a){var l,h;a=g(a);b.direction&&a[1].length&&a.reverse();for(l=0;l<b.handles;l++)for(h=0;h<a[l].length;h++){if(!d(a[l][h]))return!1;a[l][h]||a[l].splice(h,1)}b[c].to=a}else b[c].to=[[],[]];return!0}};f.each({handles:{r:!0,t:function(a){a=parseInt(a,10);return 1===a||2===a}},range:{r:!0,t:function(a,b,d){b[d]=c(a);return b[d]&&b[d][0]!==b[d][1]}},
start:{r:!0,t:function(a,b,d){if(1===b.handles)return f.isArray(a)&&(a=a[0]),a=parseFloat(a),b.start=[a],n(a);b[d]=c(a);return!!b[d]}},connect:{r:!0,t:function(a,b,c){if("lower"===a)b[c]=1;else if("upper"===a)b[c]=2;else if(!0===a)b[c]=3;else if(!1===a)b[c]=0;else return!1;return!0}},orientation:{t:function(a,b,c){switch(a){case "horizontal":b[c]=0;break;case "vertical":b[c]=1;break;default:return!1}return!0}},margin:{r:!0,t:function(a,b,c){a=parseFloat(a);b[c]=s(b.range,a);return n(a)}},direction:{r:!0,
t:function(a,b,c){switch(a){case "ltr":b[c]=0;break;case "rtl":b[c]=1;b.connect=[0,2,1,3][b.connect];break;default:return!1}return!0}},behaviour:{r:!0,t:function(a,b,c){b[c]={tap:a!==(a=a.replace("tap","")),extend:a!==(a=a.replace("extend","")),drag:a!==(a=a.replace("drag","")),fixed:a!==(a=a.replace("fixed",""))};return!a.replace("none","").replace(/\-/g,"")}},serialization:{r:!0,t:function(a,b,c){return d.g(a.to,b,c)&&d.f(a.resolution,b)&&d.e(a.mark,b,c)}},slide:{t:function(a){return f.isFunction(a)}},
set:{t:function(a){return f.isFunction(a)}},block:{t:function(a){return f.isFunction(a)}},step:{t:function(a,b,c){a=parseFloat(a);b[c]=s(b.range,a);return n(a)}}},function(c,d){var f=a[c],g=void 0!==f;if(d.r&&!g||g&&!d.t(f,a,c))throw console&&console.log&&console.group&&(console.group("Invalid noUiSlider initialisation:"),console.log("Option:\t",c),console.log("Value:\t",f),console.log("Slider(s):\t",b),console.groupEnd()),new RangeError("noUiSlider");})}function R(a){this.data("options",f.extend(!0,
{},a));a=f.extend({handles:2,margin:0,connect:!1,direction:"ltr",behaviour:"tap",orientation:"horizontal"},a);a.serialization=a.serialization||{};Q(a,this);a.style=a.orientation?"top":"left";return this.each(function(){var b=f(this),c,d=[],e,k=f("<div/>").appendTo(b);if(b.data("base"))throw Error("Slider was already initialized.");b.data("base",k).addClass([g[6],g[16+a.direction],g[10+a.orientation]].join(" "));for(c=0;c<a.handles;c++)e=f("<div><div/></div>").appendTo(k),e.addClass(g[1]),e.children().addClass([g[2],
g[2]+g[7+a.direction+(a.direction?-1*c:c)]].join(" ")),e.data({base:k,target:b,options:a,grab:e.children(),pct:-1}).attr("data-style",a.style),e.data({store:K(e,c,a.serialization)}),d.push(e);switch(a.connect){case 1:b.addClass(g[9]);d[0].addClass(g[12]);break;case 3:d[1].addClass(g[12]);case 2:d[0].addClass(g[9]);case 0:b.addClass(g[12])}k.addClass(g[0]).data({target:b,options:a,handles:d});b.val(a.start);if(!a.behaviour.fixed)for(c=0;c<d.length;c++)p(q.start,d[c].children(),B,{b:k,target:b,a:[d[c]]});
a.behaviour.tap&&p(q.start,k,O,{b:k,target:b});a.behaviour.extend&&(b.addClass(g[19]),a.behaviour.tap&&p(q.start,b,P,{b:k,target:b}));a.behaviour.drag&&(c=k.find("."+g[9]).addClass(g[18]),a.behaviour.fixed&&(c=c.add(k.children().not(c).data("grab"))),p(q.start,c,B,{b:k,target:b,a:d}))})}function S(){var a=f(this).data("base"),b=[];f.each(a.data("handles"),function(){b.push(f(this).data("store").val())});return 1===b.length?b[0]:a.data("options").direction?b.reverse():b}function T(a,b){f.isArray(a)||
(a=[a]);return this.each(function(){var c=f(this).data("base"),d,e=Array.prototype.slice.call(c.data("handles"),0),g=c.data("options");1<e.length&&(e[2]=e[0]);g.direction&&a.reverse();for(c=0;c<e.length;c++)if(d=a[c%2],null!==d&&void 0!==d){"string"===f.type(d)&&(d=d.replace(",","."));var h=g.range;d=parseFloat(d);d=s(h,0>h[0]?d+Math.abs(h[0]):d-h[0]);g.direction&&(d=100-d);!0!==x(e[c],d)&&e[c].data("store").val(!0);!0===b&&r(g.set,f(this))}})}function U(a){var b=[[a,""]];f.each(a.data("base").data("handles"),
function(){b=b.concat(f(this).data("store").elements)});f.each(b,function(){1<this.length&&this[0].off(h)});a.removeClass(g.join(" "));a.empty().removeData("base options")}function V(a){return this.each(function(){var b=f(this).val()||!1,c=f(this).data("options"),d=f.extend({},c,a);!1!==b&&U(f(this));a&&(f(this).noUiSlider(d),!1!==b&&d.start===c.start&&f(this).val(b))})}var z=f(document),y=f("body"),h=".nui",W=f.fn.val,g="noUi-base noUi-origin noUi-handle noUi-input noUi-active noUi-state-tap noUi-target -lower -upper noUi-connect noUi-horizontal noUi-vertical noUi-background noUi-stacking noUi-block noUi-state-blocked noUi-ltr noUi-rtl noUi-dragable noUi-extended noUi-state-drag".split(" "),
q=window.navigator.pointerEnabled?{start:"pointerdown",move:"pointermove",end:"pointerup"}:window.navigator.msPointerEnabled?{start:"MSPointerDown",move:"MSPointerMove",end:"MSPointerUp"}:{start:"mousedown touchstart",move:"mousemove touchmove",end:"mouseup touchend"};f.fn.val=function(){return this.hasClass(g[6])?arguments.length?T.apply(this,arguments):S.apply(this):W.apply(this,arguments)};return(D?V:R).call(this,C)}})(window.jQuery||window.Zepto);

