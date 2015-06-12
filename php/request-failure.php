<?php
/*
 * @package NGS SAM Integrator
 * @since 1.0.0
 */
	if ( null != $requested_song ) {
		$reqartist = $requested_song->artist;
		$reqtitle = $requested_song->title;
	?>
		<div id="reqfailure">
			Your request for "<?php echo $reqtitle; ?>" by <?php echo $reqartist ?> could not be processed.<br />
			<?php echo $request_status_message; ?>
		</div>
	<?php
	} else {
	?>
		<div id="reqfailure">
			<?php echo $request_status_message; ?>
		</div>
<?php
	}