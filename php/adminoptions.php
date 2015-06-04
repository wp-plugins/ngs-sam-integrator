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
	
	<?php
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'connections';
	?>
	
	<h2 class="nav-tab-wrapper">
		<a href="?page=ngs-sam-integrator&tab=connections" class="nav-tab <?php echo $active_tab == 'connections' ? 'nav-tab-active' : ''; ?>">Connections</a>
		<a href="?page=ngs-sam-integrator&tab=pages" class="nav-tab <?php echo $active_tab == 'pages' ? 'nav-tab-active' : ''; ?>">Pages</a>
		<a href="?page=ngs-sam-integrator&tab=album_art" class="nav-tab <?php echo $active_tab == 'album_art' ? 'nav-tab-active' : ''; ?>">Album Art</a>
		<a href="?page=ngs-sam-integrator&tab=status_messages" class="nav-tab <?php echo $active_tab == 'status_messages' ? 'nav-tab-active' : ''; ?>">Status Messages</a>
		<a href="?page=ngs-sam-integrator&tab=request_throttle" class="nav-tab <?php echo $active_tab == 'request_throttle' ? 'nav-tab-active' : ''; ?>">Request Throttling</a>
	</h2>
<?php if ( 'connections' == $active_tab ) { ?>
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
<?php }
if ( 'pages' == $active_tab ) { ?>
<h2>SAM Integrator Pages</h2>
<h3>Show Queue Time</h3>
	<label for="ngs_show_queue_time_yes">
		<input type="radio" id="ngs_show_queue_time_yes" name="ngs_show_queue_time" value="true" <?php if ( "true" == $ngs_options['showqueuetime'] ) echo 'checked="checked" '; ?> /> Yes </label>&nbsp;
	<label for="ngs_show_queue_time_no">
		 <input type="radio" id="ngs_show_queue_time_no" name="ngs_show_queue_time" value="false" <?php if ( "false" == $ngs_options['showqueuetime'] ) echo 'checked="checked" '; ?> />
		 No </label>
<h3>Default Number of Results to Show:</h3>
<select name="ngs_default_results" id="NumPlaylistResults" >
	<option id="List5Results" value="5" <?php if ( 5 == $ngs_options['defaultresults'] ) { echo 'selected="selected"'; } ?>>5</option>
	<option id="List10Results" value="10" <?php if ( 10 == $ngs_options['defaultresults'] ) { echo 'selected="selected"'; } ?>>10</option>
	<option id="List25Results" value="25" <?php if ( 25 == $ngs_options['defaultresults'] ) { echo 'selected="selected"'; } ?>>25</option>
	<option id="List50Results" value="50" <?php if ( 50 == $ngs_options['defaultresults'] ) { echo 'selected="selected"'; } ?>>50</option>
	<option id="List100Results" value="100" <?php if ( 100 == $ngs_options['defaultresults'] ) { echo 'selected="selected"'; } ?>>100</option>
</select>
<h4>If these are blank or the pages do not exist, new pages will be created and the ID's will be set 
	automatically.<br />
	If you manually specify page ID's, the pages will not be updated automatically, and you will need
	to add the shortcodes into the existing pages yourself.</h4>
<h3>Songlist By Artist Page ID</h3>
<input type="text" name="ngs_sam_by_artist_page" value="<?php echo $ngs_options['byartistpageid']; ?>" />
<h3>Songlist By Top Requests Page ID</h3>
<input type="text" name="ngs_sam_by_requests_page" value="<?php echo $ngs_options['byrequestspageid']; ?>" />
<?php }

if ( 'album_art' == $active_tab ) { ?>
<h2>Widget Album Art:</h2>

<h3>Recently Played</h3>   
        <label for="ngs_show_album_art_played_yes"><?php _e('Enable: '); ?></label>
                <input type="radio" id="ngs_show_album_art_played_yes" name="ngs_show_album_art_played" value="true" <?php if ( "true" == $ngs_options['showartplayed'] ) echo 'checked="checked" '; ?> /> Yes </label>&nbsp;
        <label for="ngs_show_album_art_played_no">
                 <input type="radio" id="ngs_show_album_art_played_no" name="ngs_show_album_art_played" value="false" <?php if ( "false" == $ngs_options['showartplayed'] ) echo 'checked="checked" '; ?> /> No </label><br />
	<label for="ngs_show_album_art_played_height"><?php _e('Height'); ?></label>
		<input name="ngs_show_album_art_played_height" type="number" step="1" min="0" id="ngs_show_album_art_played_height" value="<?php echo $ngs_options['artph'];; ?>" class="small-text" />
	<label for="ngs_show_album_art_played_width"><?php _e('Width'); ?></label>
		<input name="ngs_show_album_art_played_width" type="number" step="1" min="0" id="ngs_show_album_art_played_width" value="<?php echo $ngs_options['artpw'];; ?>" class="small-text" /><br />

<h3>Coming Up</h3>   
        <label for="ngs_show_album_art_queued_yes"><?php _e('Enable: '); ?></label>
                <input type="radio" id="ngs_show_album_art_queued_yes" name="ngs_show_album_art_queued" value="true" <?php if ( "true" == $ngs_options['showartque'] ) echo 'checked="checked" '; ?> /> Yes </label>&nbsp;
        <label for="ngs_show_album_art_queued_no">
                 <input type="radio" id="ngs_show_album_art_queued_no" name="ngs_show_album_art_queued" value="false" <?php if ( "false" == $ngs_options['showartque'] ) echo 'checked="checked" '; ?> /> No </label><br />
	<label for="ngs_show_album_art_queued_height"><?php _e('Height'); ?></label>
		<input name="ngs_show_album_art_queued_height" type="number" step="1" min="0" id="ngs_show_album_art_queued_height" value="<?php echo $ngs_options['artqh'];; ?>" class="small-text" />
	<label for="ngs_show_album_art_queued_width"><?php _e('Width'); ?></label>
		<input name="ngs_show_album_art_queued_width" type="number" step="1" min="0" id="ngs_show_album_art_queued_width" value="<?php echo $ngs_options['artqw'];; ?>" class="small-text" /><br />

<h3>Requested</h3>   
        <label for="ngs_show_album_art_request_yes"><?php _e('Enable: '); ?></label>
                <input type="radio" id="ngs_show_album_art_request_yes" name="ngs_show_album_art_request" value="true" <?php if ( "true" == $ngs_options['showartreq'] ) echo 'checked="checked" '; ?> /> Yes </label>&nbsp;
        <label for="ngs_show_album_art_request_no">
                 <input type="radio" id="ngs_show_album_art_request_no" name="ngs_show_album_art_request" value="false" <?php if ( "false" == $ngs_options['showartreq'] ) echo 'checked="checked" '; ?> /> No </label><br />
	<label for="ngs_show_album_art_request_height"><?php _e('Height'); ?></label>
		<input name="ngs_show_album_art_request_height" type="number" step="1" min="0" id="ngs_show_album_art_request_height" value="<?php echo $ngs_options['artrh'];; ?>" class="small-text" />
	<label for="ngs_show_album_art_request_width"><?php _e('Width'); ?></label>
		<input name="ngs_show_album_art_request_width" type="number" step="1" min="0" id="ngs_show_album_art_request_width" value="<?php echo $ngs_options['artrw'];; ?>" class="small-text" /><br />
                                                                                                              


<h3>Album Art Location:</h3>  
<h4>If enabled above, you will need to create a folder called "sam" in your root directory.<br />
        You can set up Sam Broadcaster to upload images to this directory using FTP<br />
        Make sure the directory permissions are 775, or 777.<br />
        To do this, use the permissions option from your FTP program, or log into shell,<br />
        navigate to where your wordpress files are located, and within the wordpress<br />
        folder that contains the sam images folder, issue the following command:<br /><br />
        <strong>chmod -R 777 sam</strong>
        </h4>
<?php }

if ( 'status_messages' == $active_tab ) { ?>
<h2>Custom Status Messages</h2>
<h4>These settings will allow you to customize the messages displayed when a request can not be processed.</h4>
<h3>Title Recently Played</h3>
<input type="text" size="100" name="ngs_status_601" value="<?php echo $ngs_options['status601']; ?>" />
<h3>Artist Recently Played</h3>
<input type="text" size="100" name="ngs_status_602" value="<?php echo $ngs_options['status602']; ?>" />
<h3>Title Already In Queue</h3>
<input type="text" size="100" name="ngs_status_603" value="<?php echo $ngs_options['status603']; ?>" />
<h3>Artist Already In Queue</h3>
<input type="text" size="100" name="ngs_status_604" value="<?php echo $ngs_options['status604']; ?>" />
<h3>Title Already Requested</h3>
<input type="text" size="100" name="ngs_status_605" value="<?php echo $ngs_options['status605']; ?>" />
<h3>Artist Already Requested</h3>
<input type="text" size="100" name="ngs_status_606" value="<?php echo $ngs_options['status606']; ?>" />
<h3>Track Recently Played</h3>
<input type="text" size="100" name="ngs_status_609" value="<?php echo $ngs_options['status609']; ?>" />
<?php } 
if ( 'request_throttle' == $active_tab ) { ?>
<h2>Request Throttling</h2>
<h4>These settings will only affect users who are unregistered or not logged in.<br />
	Limits for users who are logged in are controlled through your SAM Broadcaster configuration settings.</h4>
<h3>Enable Throttling for Unregistered Users</h3>
	<label for="ngs_use_throttle_yes">
		<input type="radio" id="ngs_use_throttle_yes" name="ngs_use_throttle value="true" <?php if ( "true" == $ngs_options['enablethrottling'] ) echo 'checked="checked" '; ?> /> Yes </label>&nbsp;
	<label for="ngs_use_throttle_no">
		 <input type="radio" id="ngs_use_throttle_no" name="ngs_use_throttle" value="false" <?php if ( "false" == $ngs_options['enablethrottling'] ) echo 'checked="checked" '; ?> /> No </label>

<h3>The same user may request <input type="text" size="5" name="ngs_request_number_limit" value="<?php echo $ngs_options['throttlenumber']; ?>" /> song(s) 
	every <input type="text" size="5" name="ngs_request_time_limit" value="<?php echo $ngs_options['throttleminutes']; ?>" /> minute(s), but
	no more than <input type="text" size="5" name="ngs_request_daily_limit" value="<?php echo $ngs_options['throttledaily']; ?>" /> song(s)
	per day.</h3>
<h4>TIP: To disable requests for unregistered users, set the daily limit to zero.</h4>
<input type="hidden" name="ngs_throttle_settings" value="true" />
<?php } ?>
<div class="submit">
<input type="submit" name="update_ngs_sam_integrator_settings" value="<?php _e('Update Settings', 'NGS_SAM_Integrator') ?>" /></div>
</form>

<?php // Paypal Donation Code ?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="9T8FRUACJJM9Y" />
<input type="image" alt="PayPal - The safer, easier way to pay online!" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" />
<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" border="0" /></form>
    
 </div>
