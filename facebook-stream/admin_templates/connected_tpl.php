<div class="wrap">
    
    
    <div class="infodiv_fbstream">
        <h2>Configuration</h2>
        <table width="100%">
            <tr>
                <td>
                    <form action ="?page=facebook-stream&action=save_data" method="POST">
                        <table>
                            <tr>
                                <td><a href="https://developers.facebook.com/apps" target="_blank">APP ID:</a></td>
                                <td><input name="appID" type="text" value="<?php echo $app_id;?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <td><a href="https://developers.facebook.com/apps" target="_blank">APP Secret Code:</a></td>
                                <td><input name="appSecret" type="text" value="<?php echo $app_secret;?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <td>Facebook Page ID</td>
                                <td><input name="FBpageID" type="text" value="<?php echo $fb_page_id;?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="submit" class="button button-primary button-large" name="submit" value="Save"></td>
                            </tr>
                        </table>
                    </form>
                </td>
                <td><a href="http://wp-resources.com/facebook-stream-pro/" target="_blank"><img src="<?php echo plugin_dir_url( __FILE__ );?>banner.png"></a></td>
           </tr>
        </table>
    </div>
    
    <div class="infodiv_fbstream">
        <h2>Shortcode generator</h2>
        <table width="100%">
            <tr>
                <td width="40%">
                    <table width="90%">
                        <tr>
                            <td>
                                Facebook page ID:<br>
                                <input id="facebook_page_id" type="text" value="<?php echo $fb_page_id;?>" class="fbstream_fullfield"><br>
                                <small>You can change your default FB page!</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Show only posts with media (image, long text, video):<br>
                                <select id="only_media" class="regular-text fbstream_fullfield">
                                    <option value="1">Yes, show only posts with media</option>
                                    <option value="0">No, show all posts</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Stream theme:<br>
                                <select id="stream_theme" class="regular-text fbstream_fullfield">
                                    <option value="white">White theme</option>
                                    <option value="black">Black theme</option>
                                    <option value="modern" disabled>Modern white theme - only in PRO</option>
                                    <option value="modern_black" disabled>Modern black theme - only in PRO</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Only Owners posts:<br>
                                <select id="only_owner_posts" class="regular-text fbstream_fullfield">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <small>Show only posts created by page owner</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Number of posts on stream:<br>
                                <input id="stream_limit" type="number" value="25" class="fbstream_fullfield"><br>
                                <small>25 or less is recommended</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Number of columns on stream:<br>
                                <input id="cols_limit" type="number" value="3" class="fbstream_fullfield"><br>
                                <small>3 to 5 i recommended</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Bottom margin (in px):<br>
                                <input id="margin_bottom" type="number" value="50" class="fbstream_fullfield"><br>
                                <small>50 is recommended</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Boxes padding (in px):<br>
                                <input id="boxes_padding" type="number" value="10" class="fbstream_fullfield"><br>
                                <small>10 is recommended</small>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" id="generate_shortcode_button" class="button button-primary button-large fbstream_fullfield" name="submit" value="Generate"></td>
                        </tr>
                    </table>
                </td>
                <td width="60%" valign="top">
                    <p>
                        You can place generated shortcode inside any post or page. 
                        You can even generate shortcode for your site sidebar!
                        If you have any problem with using our plugin, or just want to suggest us new featured
                        feel free to <a href="http://wp-resources.com/contact-us/" target="_blank">contact us here</a>
                    </p>
                    
                    <h3>Shortcode:</h3>
                    <small>Copy code below:</small><br><br>

                    <textarea id="shortcode_text_area">[facebook-stream limit="25" cols="3" only_owners_posts="1" theme="white" padding="10" margin_bottom="50" fb_page_id="<?php echo $fb_page_id;?>" hide_no_media="1"]</textarea>
                    <br>
                    <p>
                        
                        <h3>DEMOS:</h3>
                        * Additional templates and widget support is available only in pro version.<br>
                        <ul>
                            <li>- <a href="http://demo.wp-resources.com/facebook-stream-full-width-modern/">Modern white theme demo</a></li>
                            <li>- <a href="http://demo.wp-resources.com/facebook-stream-full-width-modern-black/">Modern black theme demo</a></li>
                            <li>- <a href="http://demo.wp-resources.com/">Widget demo</a></li>
                        </ul>
                    </p>
                </td>
           </tr>
        </table>
    </div>
    
</div>
<?php 
echo $deletedFromCache . ' expired items erased from cache!'; 