<?php 

/**
 * @package NGS SAM Integrator
 * @since 0.1.0
 */

require_once dirname( __FILE__ ).'/samdbaccess.php';
require_once dirname( __FILE__ ).'/functions.php';

$playlist_search_text = '';
$number_to_show = $ngs_options['defaultresults'];
$start_at = 0;
$song_id = 0;

if( "true" == $ngs_options['showqueuetime'] )
	$queue_line = get_queue_line();
else 
	$queue_line = '';

/*
 * Check to see if parameters are being passed through a query argument.
 * This should only happen after a request link has been clicked. 
 * 
 * With the exception of the query argument, the query arguments are used
 * only for setting the default values for searching and paging through the
 * playlist
 */

if ( isset ( $_GET[ 'songsearchtext'] ) && null != $_GET['songsearchtext'] ){
	$playlist_search_text = $_GET['songsearchtext'];
}
if ( isset( $_GET['startat'] ) && null != $_GET['startat'] ) {
	$start_at = absint($_GET['startat']);
}
if ( isset( $_GET['number'] ) && null != $_GET['number'] ) {
	$number_to_show = absint( $_GET['number'] );
}

$song_count_query = $samdb->get_row( build_songlist_count_query( $playlist_search_text ), ARRAY_A );
$song_count = $song_count_query['songcount'];	

if ( isset( $_POST['SearchPlaylist'] )
||   isset( $_POST['PlayListPrev'] )
||   isset( $_POST['PlayListNext'] ) 
||   isset( $_POST['PlayListJump'] ) ) {

	$new_start = $_POST['PlayListStart'];
	
	if( isset( $_POST['PlaylistSearchText'] ) ) {
		$playlist_search_text = $_POST['PlaylistSearchText'];
		$song_count_query = $samdb->get_row( build_songlist_count_query( $playlist_search_text ), ARRAY_A);
		$song_count = $song_count_query['songcount'];
	}

	if ( isset( $_POST['PlayListNext'] ) )
		$new_start = $_POST['PlayListEnd'];

	if ( isset( $_POST['NumPlaylistResults'] ) )
		$number_to_show = absint( $_POST['NumPlaylistResults'] );

	if ( isset( $_POST['PlayListJump'] ) )
		$new_start = $_POST['PlayListJumpTo']-1;
	
	if ( isset($_POST['PlayListPrev'] ) )
		$new_start = $_POST['PlayListStart'] - $number_to_show;
	
	$start_at = validate_start_position( $new_start, $song_count );				
} 
?>
<div class="wrap">
	<div class="ngssamintegrator">
<?php
include dirname(__FILE__).'/request.php';  // Request handler
$first_song = $start_at + 1;
$last_song = $start_at + $number_to_show;
if ( $song_count < $last_song ) 
	$last_song = $song_count;
echo "Showing $first_song to $last_song of $song_count total songs<br />";
include dirname( __FILE__ ).'/search.php';
echo $queue_line;
?>
		<table border="0" width="98%" cellspacing="0" cellpadding="4">
			<thead>
				<th colspan="3" nowrap align="left">Playlist Results:</th>
			</thead>
<?php


$playlist = $samdb->get_results( build_songlist_query( $start_at, $number_to_show, $playlist_search_text ) );
$row_number = $first_song;

if ( $playlist ) {
	foreach ( $playlist as $song ) {
		$song_info = prepare_song( $song, $start_at, $number_to_show, $playlist_search_text );
		?>
			<tr>
				<td><img src="/sam/<?php echo $song_info['album']; ?>" height="60" width="60"></td>
				<td> <?php echo $row_number++; ?>  </td>
				<td><a rel="nofollow" href="<?php echo $song_info['requestlink'] ?>"><?php echo $song_info['artistandtitle']; ?></a></td>
				<td><?php echo $song_info['formattedduration']; ?></td>
			</tr>
		<?php
	}
} else {
	?>
		<tr>
			<td colspan="3">No Results Found</td>
		</tr>
	<?php
}
?>
		</table>
<?php 
	echo $queue_line;
	include dirname( __FILE__ ).'/search.php'; 
	echo "Showing $first_song to $last_song of $song_count total songs<br />";
?>
	</div>
</div>
