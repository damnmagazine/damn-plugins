=== Search & Filter Pro ===
Contributors: DesignsAndCode
Donate link: 
Tags: posts, custom posts, products, category, filter, taxonomy, post meta, custom fields, search, wordpress, post type, post date, author
Requires at least: 3.5
Tested up to: 4.2
Stable tag: 1.4.3

Search and Filtering for posts, products and custom posts. Allow your users to Search & Filter by taxonomies, custom fields and more.

== Description ==

Search & Filter Pro is a advanced search and filtering plugin for WordPress.  It allows you to Search & Filter your posts / custom posts / products by any number of parameters allowing your users to easily find what they are looking for on your site, whether it be a blog post, a product in an online shop and much more.

Users can filter by Categories, Tags, Taxonomies, Custom Fields, Post Meta, Post Dates, Post Types and Authors, or any combination of these easily.

Great for searching in your online shop, tested with: WooCommerce and WP eCommerce, Easy Digital Downloads


= Field types include: =

* dropdown selects
* checkboxes
* radio buttons
* multi selects
* range slider
* number range
* date picker
* single or multiselect comboboxes with autocomplete


== Installation ==


= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `search-filter-pro.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard


= Using FTP =

1. Download `search-filter-pro.zip`
2. Extract the `search-filter-pro` directory to your computer
3. Upload the `search-filter-pro` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard


== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

= 1.4.3 =
* Fix - more fixes for WP 4.2

= 1.4.2 =
* Fix - some bugs caused by WP 4.2

= 1.4.1 =
* New - Added IDs to search forms for easy css targeting - also renamed ID on results container to keep in line with naming conventions
* New - added reset button
* New - dropdown number range
* New - added options to use timestamps in post meta
* fix - a bug when sanitizing keys from post meta
* fix - a bug with autosuggest & encoding
* fix - issues with searching serialised post meta
* fix - throwing an error when trying to access the `all_items` label of a taxonomy when it does not exist
* fix - some dependencies with JS/CSS allowing them to be removed more easily
* fix - some tweaks to automatic updates
* fix - layout issues with search form UI and WP 4.1 
* fix - various fixes and improvements with compatibility and WPML

= 1.4.0 =
* New - search media/attachments
* New - added post meta defaults - now you can add constraints for meta data such as searching only products in stock, excluding featured posts or restricting all searches to specific meta data values
* New - scroll to top of page when updating results with ajax
* New - use the shortcode to display results without ajax too (results shortcode only worked with ajax setups previously)
* New - allow regular pagination when using a shortcode for results - (use wp next_posts_link & previous_posts_link, plus added support for wp_pagenavi plugin)
* New - added AND / OR operator to define relationships between tag, category and taxonomy fields
* New - optionally include children in parent searches (categories, hierarchical taxonomies)
* New - improvded UI - add taxonomy browser to help find IDs easily
* New - improved ajax/template UI
* New - minify CSS & JS - finally integrated grunt ;) - non minified versions still available
* New - duplicate search form - a link has been added to the main S&F admin screen underneath each form for easy duplicating!
* New - added support for Relevanssi when using shortcodes to display results
* New - add "today" for date comparisons in meta queries in post meta defaults
* Updated - the default results template (shortcode) to include new pagination options
* Fixed - an error when users are not using permalinks, and submitting the search form
* Fixed - "OR" operator for checkboxes with taxonomies was broken
* Fixed - a JS error when no terms were being shown for a checkbox/radio field
* Fixed - an error when using `maintain state` and getting 404 on results
* Fixed - an error when detecting if a meta field was serialised or not
* Fixed - an error when saving a post meta field with a poorly formatted name
* Fixed - ajax pagination without shortcode
* Fixed - meta fields with the value `0` being ignored
* Fixed - some updates to the plugin auto updater - some users weren't seeing udpates in the dashboard even when activated

= 1.3.0 =
* New - JavaScript rewrite - refactored - faster cleaner code
* New - add setting to allow the default sort order of results - check settings panel -> posts
* New - Speed improvements - searching usually caused 2 header requests (a POST and a redirect) - now uses only a single GET request
* New - play nice with other scripts - can now initialise the search form via JS if the form/html is loaded in dynamically
* New - mulitple search forms on the same page!
* New - add data to JS events for targeting individual forms on the same page
* New - maintain search state - keep user search settings while looking at results pages
* New - for Ajax w/ Shortcodes - Added results URL - this allows the widget to be placed anywhere in your site
* New - shortcode meta box - for easier access to shortcodes within the Search Form editor
* New - allow auto submit when ajax is not enabled
* New - shareable/bookmarkable URLs when using shortcodes (this was already available without)
* Fixed - an issue with auto submit
* Fixed - an issue with a significant delay to fetch initial results when using ajax (with shortcode) - initial results are now loaded server side on page load
* Fixed - bad html and "hide_empty" was not working as expected - it was disabling inputs rather than hiding them
* Fixed - i18n for "prev" and "next" in pagination
* Fixed - post date field was not working correctly when using ajax w/ shortcodes
* Improved - integration with WPML - better URLs and works fully with shortcodes
* Removed - *Beta* Auto Count - this feature is likely to be even more broken (it had plenty of bugs already) - it is recommended you disable this for now.  The next major update will inlcude a revised & working version of this.

= 1.2.7 =
* Fixed an issue with array_replace_recursive for older PHP version

= 1.2.6 =
* Fixed an issue with headers in admin when publishing a post

= 1.2.5 =
* Fixed a JS error in IE8
* Added new settings panel - set defaults search parameters
* Settings Panel - include/exclude categories
* Settings Panel - exclude posts by ID
* Settings Panel - choose to search by Post Status
* Settings Panel - Added Results Per Page for controlling the number of results you see
* Settings Panel - UI refinements
* Settings Panel - more to come (meta)!
* Category, Tag & Taxonomy fields - new option (advanced) to sync included/excluded posts with new settings parameters

= 1.2.4 =
* DO NOT UPGRADE IF YOU WERE HAVING ISSUES WITH AJAX FUNCTIONALITY AND WAITING FOR A PATCH, ONLY THE TWO UPDATES BELOW ARE INCLUDED IN THIS UPDATE:
* Fix - ajax shortcode functionality - search field is now working again!
* Fix - ajax shortcode functionality - fixed custom field/meta search
* Fix - ajax shortcode functionality - fixed a bug with categories

= 1.2.3 =
* DO NOT UPGRADE IF YOU WERE HAVING ISSUES WITH AJAX FUNCTIONALITY AND WAITING FOR A PATCH, ONLY THE TWO UPDATES BELOW ARE INCLUDED IN THIS UPDATE:
* Fix - ajax shortcode functionality - only displays published posts (it was also fetching drafts)
* Fix - ajax shortcode functionality - auto submit now working

= 1.2.2 =
* Fix - stopped using short syntax array in php (`[]`) which is only supported in php version 5.4+

= 1.2.1 =
* Fix - a JS error for older Ajax setups

= 1.2.0 =
* NEW - completely reworked how to use Ajax - simply use a shortcode to place where you want the results to display and you're set to go!
* Fix - allow paths in template names - S&F was previously stripping out slashes so couldn't access templates in sub directories
* Fix - various small bug fixes

= 1.1.8 =
* New - add new way to modify the main search query for individual forms
* New - added a new JS init event

= 1.1.7 =
* New - *beta* - Auto count for taxonomies - when using tag, category and taxonomies only in a search form, you can now enable a live update of fields, which means as users make filter selections, unavailable combinations will be hidden (this is beta and would love feedback especially from users with high numbers of posts/taxonomies)
* New - date picker for custom fields / post meta - dates must be stored as YYYYMMDD or as timestamps in order to use this field
* New - added JS events to capture start / end of ajax loading so you can add in your own custom loaders
* Fix - prefixed taxonomy and meta field names properly - there were collisions on the set defaults function, for example if a tax and meta share the same key there would be a collision
* Fix - errors with number ranges & range slider
* Fix - an error with detecting if a meta value is serialized
* Fix - scope issue with date fields auto submitting correctly


= 1.1.6 =
* **Notice** - dropped - `.postform` css class  this was redundant and left in by error - any users using this should update their CSS to use the new and improved options provided:
* New - class names added to all field list items for easy CSS styling + added classes to all options for form inputs for easy targeting of specific field values
* New - added a `<span class="sf-count">` wrapper to all fields where a count was being shown for easy styling
* Fix - removed all reference to `__DIR__` for PHP versions < 5.3
* Fix - Some general tweaks for WPML
* Fix - a bug when choosing all post types still adding "post_types" to the url

= 1.1.5 =
* **Notice** - this update breaks previous Sort Order fields, so make sure if you have a Sort Order Field to rebuild it once you've updated!
* New - Sort Order - in addition to sorting by Meta Value, users can now sort their results by ID, author, title, name, date, date modified, parent ID, random, comment count and menu order, users can also choose whether they they want only ASC or DESC directions - both are optional.
* New - Autocomplete Comboboxes - user friendly select boxes powered by Chosen - text input with auto-complete for selects and multiple selects - just tick the box when choosing a select or multiselect input type
* Fix - add a lower priority to `init` hook when parsing taxonomies - this helps ensure S&F runs after your custom taxonomies have been created
* Fix - add a lower priority to `pre_get_posts` - helps with modifying the main query after other plugins/custom code have run
* Fix - a problem with meta values having spaces

= 1.1.4 =
* New - Meta Suggestions - auto detect values for your custom fields / post meta
* Enhancement - improved Post Meta UI (admin)
* Fix - an error with displaying templates (there was a PHP error being thrown in some environments)
* Fix - an error where ajax enabled search forms were causing a refresh loop on some mobile browsers

= 1.1.3 =
* New - display meta data as dropdowns, checkboxes, radio buttons and multi selects
* New - added date formats to date field
* fix - auto submit & date picker issues
* fix - widget titles not displaying
* fix - missed a history.pushstate check for AJAX enabled search forms
* fix - dashboard menu conflict with other plugins
* fix - submit label was not updating
* fix - post count for authors was showing only for posts - now works with all post types
* compat - add fallback for `array_replace` for <= PHP 5.3 users

= 1.1.2 =
* New - customsise results URL - add a slug for your search results to display on (eg yousite.com/product-search)
* fix - js error when Ajax pagination links are undefined
* fix - date picker dom structure updated to match that of all other fields
* fix - scope issue when using auto submit on Ajax search forms

= 1.1.1 =
* fix - fixed an error where JS would hide the submit button :/
* fix - fixed an error where parent categories/taxonomies weren't showing their results

= 1.1.0 =
* New - AJAX - searches can be performed using Ajax
* fix - removed redundant js/css calls

= 1.0.0 =
* Initial release


== Upgrade Notice ==

= 1.0 =
Initial release

