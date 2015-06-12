<?php
/*
 * @package NGS SAM Integrator
 * @since 0.1.0
 * 
 * Functions for NGS SAM Integrator
 */

include dirname( __FILE__ ).'/xmlfunctions.php';

/**
 * Build the Query to Display the Alphabetical Songlist
 * 
 * @global object $samdb
 * @param integer $start_at
 * @param integer $number_to_show
 * @param string $playlist_search_text
 * @return string
 */
function build_songlist_query($start_at, $number_to_show, $playlist_search_text) {
	global $samdb;
	$start_at = absint( $start_at );
	$number_to_show = absint( $number_to_show );
	$playlist_search_text = trim( $playlist_search_text );
	$where = " WHERE (songtype='S') AND (status=0) ";
	
	if( empty( $playlist_search_text ) )
	{
		$sanitized_sam_query = $samdb->prepare("SELECT id AS songid, artist, title, duration, picture 
			FROM songlist 
			$where 
			ORDER BY artist ASC, title ASC LIMIT %d, %d", $start_at,$number_to_show);
	} else {
		$sanitized_sam_query = $samdb->prepare("SELECT id AS songid, artist, title, duration, picture
			FROM songlist
			$where 
			AND ( artist LIKE '%%%s%%' OR title LIKE '%%%s%%' OR album LIKE '%%%s%%' )
			ORDER BY artist ASC, title ASC LIMIT %d, %d", 
				$playlist_search_text, $playlist_search_text, $playlist_search_text, 
				$start_at, $number_to_show);
	}
	return $sanitized_sam_query;
}

/**
 * Build query to count songs in Playlist
 * 
 * @param string $playlist_search_text
 * @return string
 */
function build_songlist_count_query( $playlist_search_text ) {
	global $samdb;
	$where = " WHERE (songtype='S') AND (status=0) ";
	$playlist_search_text = trim($playlist_search_text);
	if ( ! empty( $playlist_search_text ) )
		$where = "$where AND (artist LIKE '%%%s%%' OR title LIKE '%%%s%%' OR album LIKE '%%%s%%')";
	return $samdb->prepare("SELECT count(*) AS songcount FROM songlist $where",
		$playlist_search_text, $playlist_search_text, $playlist_search_text);
}

/**
 * Build the Query to Display the Top Rquest Page
 * 
 * @global object $samdb
 * @param integer $start_at
 * @param integer $number_to_show
 * @return string
 */
function build_top_request_query( $start_at, $number_to_show ) {
	global $samdb;
	$start_at = absint( $start_at );
	$number_to_show = absint( $number_to_show );
	$sanitized_sam_query = $samdb->prepare( "SELECT songlist.ID AS songid, songlist.title, 
				songlist.artist, songlist.duration, songlist.picture, count(songlist.ID) AS reqcnt, 
				max(requestlist.t_stamp) as mostrecent
            FROM requestlist, songlist 
			WHERE   requestlist.songID = songlist.ID AND
			        requestlist.code = 200 AND 
					songlist.songtype = 'S'
			GROUP BY songlist.ID 
			ORDER BY reqcnt DESC, mostrecent ASC LIMIT %d, %d", $start_at, $number_to_show );
	return $sanitized_sam_query;
}

/**
 * Build the Query to Count Entries in Top Requests List
 * 
 * This list will only include tracks which have been requested at least once,
 * therefore the total could be different than the total returned by the query
 * returned in buildSongCountQuery() function.
 * 
 * @return string
 */
function build_top_request_count_query( ) {
	global $samdb;
	$firstpass = "SELECT songlist.ID AS songid, songlist.title, songlist.artist, songlist.duration, count(songlist.ID) AS reqcnt, max(requestlist.t_stamp) as mostrecent
            FROM requestlist, songlist 
			WHERE   (requestlist.songID = songlist.ID) AND
			        (requestlist.code=200) AND 
					(songlist.songtype = 'S')
			GROUP BY songlist.ID";
	return "SELECT count(songid) AS songcount FROM ($firstpass) AS firstpass";
}


/**
 * Strips Query String from the URL
 * 
 * To ensure compatibility with other plug-ins, this function will only strip
 * those query arguments which are used by NGS Sam Integrator.  Any other 
 * query arguments will be left in place.
 * 
 * @param string $old_url
 * @return string
 */
function clean_playlist_url( $old_url )
{
	$new_url = add_query_arg( array( 'songid' => false, 
		'startat' => false, 
		'number' => false, 
		'songsearchtext' => false ), $old_url);
	return $new_url;
}


/**
 * Prepare song information for display in Playlist and Top Request list
 * 
 * $song is a single record from the SAM database.   Other parameters passed
 * are used to add query arguments to the URL for the request link.
 * 
 * @param object $song
 * @param integer $start
 * @param integer $limit
 * @param string $search
 * @return array
 */
function prepare_song( $song, $start=0, $limit=50, $search=null ) {
	$combine = '';
	$mmss = '';
	$mm = '';
	$ss = '';
	$reqlink = '';
	$reqcount = 0;
	$artist = '';
	$title = '';
	
	$artist = $song->artist;
	$title = $song->title;
	

//ADD ALBUM ART! 
	$album = '';
	$album = $song->picture;

	if ( empty( $album ) ) {
		$album = 'NA.gif';
	}

//End Album Art!

	if ( empty( $title ) ) {
		$combine = $artist;
	} else if ( empty( $artist ) ) {
		$combine = $title;
	} else {
		$combine = $artist.' - '.$title;
	}
	$ss = round($song->duration / 1000);
	$mm = (int)($ss/60);
	$ss = $ss % 60;
	if( 10 > $ss ) { 
		$ss = "0$ss"; 
	}
	$mmss = "$mm:$ss";
	$songid = $song->songid;
	$reqcount = $song->reqcnt;
	$reqlink = add_query_arg( array( 'songid' => $songid, 'startat' => $start, 'number' => $limit, 'songsearchtext' => $search ), clean_playlist_url( $_SERVER["REQUEST_URI"] ) );
	return array( 'artist' => $artist, 'title' => $title, 'artistandtitle' => $combine, 'formattedduration' => $mmss, 'requestlink' => $reqlink, 'requestcount' => $reqcount, 'album' => $album );
}
/**
 * Validates the value of the start position
 * 
 * Used for displaying both the Playlist and the Top Request List
 * 
 * @param integer $new_start
 * @param integer $song_count
 * @return integer
 */
function validate_start_position( $new_start, $song_count ) {
	$new_start = (int)$new_start;
	$song_count = absint( $song_count );
	
	if ( $new_start > $song_count ) 
		$new_start = $start_at;
	
	if ( 0 > $new_start )
		$new_start = 0;
	
	// After validation set the start position to the new start
	// position, typecasting it to an absolute integer one more time, just
	// to be sure.
	return absint($new_start);
}
/**
 * Generates a message giving an estiamted queue time
 * 
 * Since the SAM client does not store its own configuration options in a
 * database table, it is not possible to account for the configured "hold"
 * time on requests.
 * 
 * @global object $samdb
 * @return string
 */
function get_queue_line() {
	global $samdb;
	$duration_query = $samdb->get_row( 
		"SELECT sum(songlist.duration) AS totalduration
		 FROM queuelist, songlist 
		 WHERE queuelist.songid = songlist.id", ARRAY_A );
	$total_duration = $duration_query['totalduration'];
	$queue_minutes = round( $total_duration / 60000 );

	$queue_message = "Less Than One Hour";

	if ( 60 < $queue_minutes )
		$queue_message = "Over One Hour";

	if ( 120 < $queue_minutes )
		$queue_message = "Over Two Hours";

	if ( 180 < $queue_minutes )
		$queue_message = "Over Three Hours";

	if ( 240 < $queue_minutes )
		$queue_message = "Over Four Hours";

	if ( 300 < $queue_minutes )
		$queue_message = "Over Five Hours";

	if ( 360 < $queue_minutes )
		$queue_message = "Over Six Hours";
	
	$queue_line = sprintf( "Queue time is Currently %s",
			$queue_message );
	return $queue_line;
}

function build_upcoming_tracks_query( $number_to_show ) {
	global $samdb;
	$number_to_show = absint( $number_to_show );
	$sanitized_sam_query = $samdb->prepare( "SELECT songlist.ID AS songid, songlist.title, 
				songlist.artist, songlist.duration,songlist.picture, queuelist.requestID AS requestID
            FROM queuelist, songlist 
			WHERE   queuelist.songID = songlist.ID AND
					( songlist.songtype = 'S' OR songlist.songtype = 'N' )
			ORDER BY queuelist.sortID ASC LIMIT %d, %d", 0, $number_to_show );
	return $sanitized_sam_query;
}

function build_recently_played_query( $number_to_show ) {
	global $samdb;
	$number_to_show = absint( $number_to_show ) + 1;
	$sanitized_sam_query = $samdb->prepare( "SELECT songlist.ID AS songid, songlist.title, 
				songlist.artist, songlist.duration, songlist.picture, historylist.requestID AS requestID,
				historylist.date_played AS starttime
            FROM historylist, songlist 
			WHERE   historylist.songID = songlist.ID AND
					( songlist.songtype = 'S' OR songlist.songtype = 'N' )
			ORDER BY starttime DESC LIMIT %d, %d", 0, $number_to_show );
	return $sanitized_sam_query;
}

function build_playing_now_query( $number_to_show ) {
        global $samdb;
        $number_to_show = absint( $number_to_show ) + 1;
        $sanitized_sam_query = $samdb->prepare( "SELECT songlist.ID AS songid, songlist.title,               
                                songlist.artist, songlist.duration, songlist.picture, historylist.requestID AS requestID,
                                historylist.date_played AS starttime
            FROM historylist, songlist
                        WHERE   historylist.songID = songlist.ID AND
                                        ( songlist.songtype = 'S' OR songlist.songtype = 'N' )               
                        ORDER BY starttime DESC LIMIT %d, %d", 0, $number_to_show );
        return $sanitized_sam_query;
}

function prepare_widget_song( $song ) {
	$mmss = '';
	$mm = '';
	$ss = '';
	$artist = '';
	$title = '';
	
//ADD ALBUM ART!
	$album = '';
	$album = $song->picture;

	if ( empty( $album ) ) {
		$album = 'NA.gif';
	}

//End Album Art!

	$artist = $song->artist;
	$title = $song->title;
	$reqid = $song->requestID;
	
	if( 0 != $reqid )
		$title = $title.' ~Requested~';
	
	$ss = round($song->duration / 1000);
	$mm = (int)($ss/60);
	$ss = $ss % 60;
	if( 10 > $ss ) { 
		$ss = "0$ss"; 
	}
	$mmss = "$mm:$ss";
	
	return array( 'artist' => $artist, 'title' => $title, 'formattedduration' => $mmss, 'album' => $album );	
}

