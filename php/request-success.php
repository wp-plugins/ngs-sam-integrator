<?php
/*
 * @package NGS SAM Integrator
 * @since 1.0.0
 */
	if( null != $requested_song ) {
		$reqartist = $requested_song->artist;
		$reqtitle = $requested_song->title;
	?>
			<div id="reqsuccess">
				Your request for "<?php echo $reqtitle; ?>" by <?php echo $reqartist ?> has been received and will be added the queue.
			</div>
	<?php
	$wpdb->insert( 
			$ngs_request_table, 
			array(
				'songID' => $song_id,
				't_stamp' => current_time( 'mysql', 0),
				'host' => $client_host,
				'userid' => $current_user
			),
			array(
				'%d',
				'%s',
				'%s',
				'%d'
			)
		);
	}