
<?php if (is_user_logged_in() && is_admin()) : ?>
    <?php
    if (isset($_POST['oasis_chart_url_update'])) { //save option changes
        $this->set_option($this->options);
    }
    ?>  
    <div class="wrap">     
        <div id="icon-options-general" class="icon32">          
            <br>           
        </div><h2>OASIS Chart Settings </h2>  
        <form method="post" action="options-general.php?page=oasis-charting-admin.php">            
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="oasis_chart_url">Oasis Chart Address (URL)</label></th>
                    <td><input name="oasis_chart_url" type="text" id="oasis_chart_url" value="<?php echo $this->options["oasis_chart_url"]; ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="advance_iframe_security_key">Advanced iframe Key</label></th>
                    <td><input name="advance_iframe_security_key" type="text" id="advance_iframe_security_key" value="<?php echo $this->options["advance_iframe_security_key"]; ?>" class="regular-text" /></td>
                </tr>
            </table>
            <p class="submit"><input type="submit" name="oasis_chart_url_update" id="oasis_chart_url_update" class="button button-primary" value="Update Settings"  /></p>
        </form>
    </div>
<?php endif; ?>

