<?php
/**
 * Request handling code
 * 
 * All communication between the website and the SAM Broadcaster client 
 * software takes place here.
 * 
 * @package NGS SAM Integrator
 * @since 0.1.0
 */

/*
 * if the 'songid' parameter is found in the query string of the URL, we will
 * attempt to process it as a request.
 */
if ( isset( $_GET['songid'] ) ) {
    $song_id = absint( $_GET['songid'] ); 
    $requested_song = $samdb->get_row( $samdb->prepare( "SELECT artist, title FROM songlist WHERE id=%d", $song_id ) );
	$samerr = 0;
	
    /*
     *  Do some preliminary data validation and pass appropriate error codes if
     *  the data doesn't exist.
     */
	
	if ( empty( $samhost ) )
		$samerr =  800;
/*    if ( ( "127.0.0.1" == $samhost ) OR ( "localhost" == $samhost ) )
    	$samerr = 801; */
    if ( empty( $song_id ) )
		$samerr = 802;

    $client_host = $_SERVER["REMOTE_ADDR"]; 
    $request = "GET /req/?songID=$song_id&host=".urlencode($client_host)." HTTP\1.0\r\n\r\n";
    $request_response_xml = '';
    $request_file_handle = @fsockopen( $samhost,$samport, $errno, $errstr, 30 );
	
    if ( ! empty( $request_file_handle ) ) {
    	fwrite( $request_file_handle, $request );
	while ( ! ( "\r\n" == $line ) )
            $line = fgets( $request_file_handle, 128 ); 
	while ( $request_buffer = fgets( $request_file_handle, 4096 ) )
            $request_response_xml .= $request_buffer;
            fclose( $request_file_handle );
    } else { 
		$samerr = 803;
    }
	
    if ( empty( $request_response_xml ) )
		$samerr = 804;
	
    $request_xml_array = XMLtoArray($request_response_xml);
    $request_result = Keys2Lower($request_xml_array['REQUEST']);
    $request_status_code = $request_result['status']['code'];
    $request_status_message = $request_result['status']['message'];
    $request_status_id = $request_result['status']['requestid'];
	
    if ( empty( $request_status_code ) )
		$samerr = 804;
	
	if ( 0 != $samerr )
		ngs_sam_error( $samerr );
    else if ( 200 == $request_status_code  )
		require_once dirname( __FILE__ ).'/request-success.php';
 	else
		ngs_status_code( $request_status_code, $requested_song, $request_status_message, $ngs_options );

}
/**
 * 
 * @param integer $code
 * @param string $samhost
 * @param integer $samport
 * @param integer $errno
 * @param string $errstr
 */
function ngs_sam_error( $code )
{
    global $samhost, $samport, $errno, $errstr, $requested_song;
    switch( $code )
    {
	case 800 : $request_status_message = "SAM host must be specified"; break;
	case 801 : $request_status_message = "SAM host can not be 127.0.0.1 or localhost"; break;
	case 802 : $request_status_message = "Song ID must be valid";  break;
	case 803 : $request_status_message = "Unable to connect to $samhost:$samport. Station might be offline.<br>The error returned was $errstr ($errno).";  break;
	case 804 : $request_status_message = "Invalid data returned!";  break;
	default : $request_status_message = "Unknown SAM Error Code ($code) Returned"; break;
    }
    require dirname( __FILE__ ).'/request-failure.php';
}

function ngs_status_code( $code, $requested_song, $request_status_message, $ngs_options )
{
	switch( $code )
	{
		case 601 : $request_status_message = $ngs_options['status601']; break;
		case 602 : $request_status_message = $ngs_options['status601']; break;
		case 603 : $request_status_message = $ngs_options['status601']; break;
		case 604 : $request_status_message = $ngs_options['status601']; break;
		case 605 : $request_status_message = $ngs_options['status601']; break;
		case 606 : $request_status_message = $ngs_options['status601']; break;
		case 609 : $request_status_message = $ngs_options['status601']; break;
		default : break;
	}
	require dirname(__FILE__).'/request-failure.php';
}
