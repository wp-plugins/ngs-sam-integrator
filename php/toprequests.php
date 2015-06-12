<?php 

/*
 * @package NGS SAM Integrator
 * @since 0.1.0
 * 
 */
require_once dirname( __FILE__ ).'/samdbaccess.php';
require_once dirname( __FILE__ ).'/functions.php';

if( "true" == $ngs_options['showqueuetime'] )
	$queue_line = get_queue_line();
else 
	$queue_line = '';

$song_id = '0';
$song_count_query = $samdb->get_row(build_top_request_count_query( ), ARRAY_A);
$song_count = $song_count_query['songcount'];
$start_at = 0;
$number_to_show = $ngs_options['defaultresults'];

if ( isset( $_GET['startat'] ) && $_GET['startat'] != null ) {
 	$start_at = $_GET['startat'];
}
if ( isset( $_GET['number'] ) && $_GET['number'] != null ) {
 	$number_to_show = $_GET['number'];
}


if ( isset( $_POST['PlayListPrev'] ) 
||   isset( $_POST['PlayListNext'] )
||   isset( $_POST['PlayListJump'] ) ) {

	$new_start = $_POST['PlayListStart'];
	
	if ( isset( $_POST['PlayListNext'] ) )
		$new_start = $_POST['PlayListEnd'];
	
	if ( isset( $_POST['PlayListJump'] ) )
		$new_start = $_POST['PlayListJumpTo']-1;
	
	if ( isset( $_POST['NumPlaylistResults'] ) )
		$number_to_show = $_POST['NumPlaylistResults'];
	
	if ( isset( $_POST['PlayListPrev'] ) ) {
		$new_start = $_POST['PlayListStart'] - $number_to_show;
	}

	$start_at = validate_start_position( $new_start, $song_count );
}

?>
<div class="wrap">
	<div class="ngssamintegrator">
<?php
include dirname( __FILE__ ).'/request.php';
$start = $start_at + 1; // Used for display purposes only
$end = $start_at + $number_to_show; // Used for display purposes only
if($song_count < $end)
	$end = $song_count;
echo "Showing $start to $end of $song_count total songs<br />";
include dirname( __FILE__ ).'/toprequestnav.php';
echo $queue_line;
?>
		<table border="0" width="98%" cellspacing="0" cellpadding="4">
			<thead>
				<th colspan="3" nowrap align="left">Playlist Results:</th>
			</thead>
<?php

$playlist = $samdb->get_results( build_top_request_query( $start_at, $number_to_show ) );
$row_number = $start;
foreach ( $playlist as $song ) {
	$song_info = prepare_song( $song, $start_at, $number_to_show );
	?>
			<tr>
				<td> <?php echo $row_number++; ?></td>
				<td><img src="/sam/<?php echo $song_info['album']; ?>" height="60" width="60"></td>
				<td><a rel="nofollow" href="<?php echo $song_info['requestlink']; ?>"><?php echo $song_info['artistandtitle']; ?></a>
					&nbsp;&nbsp(<?php echo $song_info['requestcount']; ?>)</td>
				<td><?php echo $song_info['formattedduration']; ?></td>
			</tr>
<?php
}
?>
		</table>
<?php 
	echo $queue_line;
	include dirname( __FILE__ ).'/toprequestnav.php'; 
	echo "Showing $start to $end of $song_count total songs<br />";
?>
	</div>
</div>
