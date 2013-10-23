<?php 
/**
 * HTML for Admin Options Panel
 * 
 * @package NGS SAM Integrator
 * @since 0.1.0
 */
?>
<div class=wrap>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<img src="<?php echo $ngs_sam_logo; ?>" />
<h2>Net Guy Steve's SAM Integrator</h2>
<h2>SAM Broadcaster Software Settings</h2>
<h4>These settings to where the SAM Broadcaster software is running.</h4>
<h3>SAM Broadcaster Host IP Address</h3>
<input type="text" name="ngs_sam_host" value="<?php echo $ngs_options['samhost']; ?>" />
<h3>SAM Broadcaster Host Port</h3>
<input type="text" name="ngs_sam_port" value="<?php echo $ngs_options['samport']; ?>" />
<hr />
<h2>SAM Database Settings</h2>
<h4>These settings pertain to where your SAM database is hosted.  This is usually NOT the same as your Wordpress Database.</h4>
<h3>SAM Database Host</h3>
<input type="text" name="ngs_sam_db_host" value="<?php echo $ngs_options['samdbhost']; ?>" />
<h3>SAM Database Port</h3>
<input type="text" name="ngs_sam_db_port" value="<?php echo $ngs_options['samdbport']; ?>" />
<h3>SAM Database Name</h3>
<input type="text" name="ngs_sam_db_name" value="<?php echo $ngs_options['samdbname']; ?>" />
<h3>SAM Database User</h3>
<input type="text" name="ngs_sam_db_user" value="<?php echo $ngs_options['samdbuser']; ?>" />
<h3>SAM Database Password</h3>
<input type="password" name="ngs_sam_db_pwd" value="<?php echo $ngs_options['samdbpwd']; ?>" />
<hr />
<h2>SAM Integrator Pages</h2>
<h3>Show Queue Time</h3>
	<label for="ngs_show_queue_time_yes">
		<input type="radio" id="ngs_show_queue_time_yes" name="ngs_show_queue_time" 
		   value="true" <?php if ( "true" == $ngs_options['showqueuetime'] ) echo 'checked="checked" '; ?> />
		Yes </label>&nbsp;
	 <label for="ngs_show_queue_time_no">
		 <input type="radio" id="ngs_show_queue_time_no" name="ngs_show_queue_time"
			value="false" <?php if ( "false" == $ngs_options['showqueuetime'] ) echo 'checked="checked" '; ?> />
		 No </label>
<h3>Default Number of Results to Show:</h3>
<select name="ngs_default_results" id="NumPlaylistResults" >
	<option id="List5Results" value="5" <?php if ( 5 == $ngs_options['defaultresults'] ) { echo 'selected="selected"'; } ?>>5</option>
	<option id="List10Results" value="10" <?php if ( 10 == $ngs_options['defaultresults'] ) { echo 'selected="selected"'; } ?>>10</option>
	<option id="List25Results" value="25" <?php if ( 25 == $ngs_options['defaultresults'] ) { echo 'selected="selected"'; } ?>>25</option>
	<option id="List50Results" value="50" <?php if ( 50 == $ngs_options['defaultresults'] ) { echo 'selected="selected"'; } ?>>50</option>
	<option id="List100Results" value="100" <?php if ( 100 == $ngs_options['defaultresults'] ) { echo 'selected="selected"'; } ?>>100</option>
</select>
<h4>If these are blank, new pages will be created and the ID's will be set automatically.<br />
	If you manually specify page ID's, the pages will not be updated automatically, and you will need
	to add the shortcodes into the existing pages yourself.</h4>
<h3>Songlist By Artist Page ID</h3>
<input type="text" name="ngs_sam_by_artist_page" value="<?php echo $ngs_options['byartistpageid']; ?>" />
<h3>Songlist By Top Requests Page ID</h3>
<input type="text" name="ngs_sam_by_requests_page" value="<?php echo $ngs_options['byrequestspageid']; ?>" />
<div class="submit">
<input type="submit" name="update_ngs_sam_integrator_settings" value="<?php _e('Update Settings', 'NGS_SAM_Integrator') ?>" /></div>
</form>

<?php // Paypal Donation Code ?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="9T8FRUACJJM9Y" />
<input type="image" alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" />
<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" border="0" /></form>
    
 </div>
