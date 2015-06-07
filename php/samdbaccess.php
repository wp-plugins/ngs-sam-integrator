<?php
/*
 * @Package RG4 SAM Integrator
 * @Since 0.1.0
 * 
 * The purpose of this code is to create a connection to the SAM Database by
 * creating an instance of the wpdb object pointing to the SAM Database.
 * 
 */

global $samdb;
$samdbhost = $ngs_options['samdbhost'];
$samdbport = $ngs_options['samdbport'];
$samdbname = $ngs_options['samdbname'];
$samdbuser = $ngs_options['samdbuser'];
$samdbpwd = $ngs_options['samdbpwd'];
$samhost = $ngs_options['samhost'];
$samport = $ngs_options['samport'];

if( 'root' == strtolower($samdbuser) ){
	$samdb = null;
} else {
	$samdb = new wpdb( $samdbuser, $samdbpwd, $samdbname, "$samdbhost:$samdbport" );
	$samdb->show_errors( );
}
