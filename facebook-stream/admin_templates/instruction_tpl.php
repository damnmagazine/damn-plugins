<div class="wrap">
    <h1>Setup Plugin in 2 easy steps:</h1>

    <div class="infodiv_fbstream">
        <h2>1. Create Facebook Application</h2>
        <p>
            <a href="https://developers.facebook.com/apps" target="_blank">Click here</a> and create new application.<br>
        </p>
    </div>

    <div class="infodiv_fbstream">
        <h2>2. Enter application details</h2>
        <p>
            Please enter you application data and click save.
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
                        <td>Your facebook page ID:</td>
                        <td><input name="FBpageID" type="text" value="<?php echo $fb_page_id; ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" class="button button-primary button-large" name="submit" value="Save"></td>
                    </tr>
                </table>
            </form>
        </p>
    </div>

</div>