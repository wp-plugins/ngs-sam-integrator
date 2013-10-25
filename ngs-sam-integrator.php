<?php

/*
Plugin Name: NGS SAM Integrator
Plugin URI: http://www.netguysteve.com/sam-integrator/
Description: Plug-In to integrate SAM Broadcaster with WordPress
Version: 1.1.0
Author: Steve Williams
Author URI: http://www.netguysteve.com/
License: GPLv2
*/

/* 
Copyright (C) 2013 Steve Williams

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

/**
 * @package NGS SAM Integrator
 * @version 1.0.1
 */

if ( ! class_exists( "NGS_SAM_Integrator" ) ) {
	class NGS_SAM_Integrator {
		/**
		 * The name of the options array in the Wordpress Database
		 *
		 * @var string
		 */
		var $admin_options_name = "NGSSAMIntegratorAdminOptions";
		
		function NGS_SAM_Integrator( ) {
			// Constructor
		}
		
		/**
		 * Returns an array of the Admin Settings
		 * 
		 * - samhost: The IP or DNS address of the user's SAM Broadcaster client
		 * - samport: The port that should be used for the user's SAM Broadcaster Client
		 * - samdbhost: The IP or DNS address of the server for the SAM database
		 * - samdbname: The name of the user's SAM database
		 * - samdbuser: The user that should be used to connect to the SAM Database
		 * - samdbpwd: The password that should be used to connecct to the SAM Database
		 * 
		 * @return array
		 */
		function get_admin_options( ) {
			$ngs_admin_options = array(
				'samhost' => '0.0.0.0',
				'samport' => '1221',
				'samdbhost' => '0.0.0.0',
				'samdbport' => '3306',
				'samdbname' => 'SAMDB',
				'samdbuser' => '',
				'samdbpwd' => '',
				'byartistpageid' => null,
				'byrequestspageid' => null,
				'showqueuetime' => 'true',
				'defaultresults' => 50,
			);
			$saved_options = get_option( $this->admin_options_name );
			if ( ! empty( $saved_options ) ) {
				foreach ( $saved_options as $key => $option )
					$ngs_admin_options[$key] = $option;
			}				
			update_option( $this->admin_options_name, $ngs_admin_options );
			return $ngs_admin_options;
		}

		/**
		 * Initialize the Plug-In on Activation
		 * 
		 * @since 0.1.0
		 */
		function init( ) {
			$this->get_admin_options( );
		}
		
		/**
		 * Attaches scripts and styles to the header.  Triggered on 
		 * wp_enqueue_scripts hook.
		 * 
		 * @since 0.1.0
		 */
		function add_header_code( ) {
			wp_register_style( 'ngssamintegratorcss', plugins_url( 'css/ngs-sam-integrator.css', __FILE__ ), array( ), '1.0.0' );
			wp_enqueue_style( 'ngssamintegratorcss' );
		}
		
		/**
		 * Create Default SAM Integrator Pages
		 * 
		 * This function simply creates default pages for the Wordpress
		 * front end
		 * 
		 * @param array $ngs_options
		 * @return array
		 */
		function generate_sam_pages( $ngs_options )
		{
			$by_artist_page = $ngs_options['byartistpageid'];
			$by_requests_page = $ngs_options['byrequestspageid'];
			
			if( null == $by_artist_page 
			||  false == get_post_status( $by_artist_page )
			||  'trash' == get_post_status( $by_artist_page ) ) {
				$post_settings = array(
					'comment_status' => 'closed',
					'post_content' => '[samplaylist]',
					'post_title' => 'Requests',
					'post_type' => 'page',
					'post_status' => 'publish',
				);
				$by_artist_page = wp_insert_post( $post_settings );
			}
			
			if( null == $by_requests_page 
			||  false == get_post_status( $by_requests_page )
			||  'trash' == get_post_status( $by_requests_page ) ) {
				$post_settings = array(
					'comment_status' => 'closed',
					'post_content' => '[samtoprequests]',
					'post_title' => 'Top Requests',
					'post_type' => 'page',
					'post_parent' => $by_artist_page,
					'post_status' => 'publish',
				);
				$by_requests_page = wp_insert_post( $post_settings );
			}
			
			$ngs_options['byartistpageid'] = $by_artist_page;
			$ngs_options['byrequestspageid'] = $by_requests_page;
			
			return $ngs_options;
		}
		
		/**
		 * Admin configuration panel
		 * 
		 * Displays admin options configuration in the dashboard
		 * 
		 * @since 0.1.0
		 */
		function print_admin_page( ) {
			$ngs_options = $this->get_admin_options();
			
			if ( isset( $_POST['update_ngs_sam_integrator_settings'] ) ) {
				if ( isset( $_POST['ngs_sam_host'] ) )
					$ngs_options['samhost'] = $_POST['ngs_sam_host'];
				if ( isset( $_POST['ngs_sam_port'] ) )
					$ngs_options['samport'] = $_POST['ngs_sam_port'];
				if ( isset( $_POST['ngs_sam_db_host'] ) )
					$ngs_options['samdbhost'] = $_POST['ngs_sam_db_host'];
				if ( isset( $_POST['ngs_sam_db_port'] ) )
					$ngs_options['samdbport'] = $_POST['ngs_sam_db_port'];
				if ( isset( $_POST['ngs_sam_db_name'] ) )
					$ngs_options['samdbname'] = $_POST['ngs_sam_db_name'];
				if ( isset( $_POST['ngs_sam_db_user'] ) )
					$ngs_options['samdbuser'] = $_POST['ngs_sam_db_user'];
				if ( isset( $_POST['ngs_sam_db_pwd'] ) )
					$ngs_options['samdbpwd'] = $_POST['ngs_sam_db_pwd'];
				if ( isset( $_POST['ngs_show_queue_time'] ) )
					$ngs_options['showqueuetime'] = $_POST['ngs_show_queue_time'];
				if ( isset( $_POST['ngs_default_results'] ) )
					$ngs_options['defaultresults'] = absint( $_POST['ngs_default_results'] );
				$ngs_options = $this->generate_sam_pages( $ngs_options );
				update_option( $this->admin_options_name, $ngs_options );
			
				?>
				<div class="updated"><p><strong><?php _e("Settings Updated.", "NGS_SAM_Integrator");?></strong></p>
				<?php if ( strtolower( 'root' ) == $ngs_options['samdbuser'] ) { ?>
					<h2>STOP!</h2>
					<p>For security reasons, the 'root' account should never be used by an external
					   application to access your database.   Please create a database user with
					   only the required access rights.</p>
					<p>For your protection, this plug-in will not function if the user is set as 'root'.  For more 
					   information, please see: 
					   <a target="_BLANK" href="http://www.netguysteve.com/2013/05/ngs-sam-broadcaster-integrator-for-wordpress/">
					   this blog entry</a> or <a target="_BLANK" href="http://www.mysql.com/why-mysql/white-papers/a-guide-to-securing-mysql-on-windows/">
					   "A Guide to Securing MySQL on Windows"</a> from mysql.org.</p>
				<?php } // End check for Root User ?>
				</div>
			<?php } // End Check for Updated
			$ngs_sam_logo = plugins_url( 'images/NGSEmblemVer2_242x142.png', __FILE__ );
			require dirname( __FILE__ ).'/php/adminoptions.php'; 
		}
		
		/**
		 * Displays Song Library
		 * 
		 * This displays the regular playlist, sorted by artist, which is triggered
		 * using the [samplaylist] shortcode
		 * 
		 * @since 0.1.0
		 */
		function display_play_list( ) {
			$ngs_options = $this->get_admin_options( );
			if( 'root' == strtolower( $ngs_options['samdbuser'] ) )
				echo 'Please Check Your Configuration Options';
			else
				require dirname( __FILE__ ).'/php/playlist.php'; 
		}

		/**
		 * Displays Top Requests
		 * 
		 * This displays the top requests playlist, sorted by number of valid
		 * requests.  Triggered using the [samplaylist] shortcode.
		 * 
		 * @since 0.1.0
		 */
		function display_top_requests( ) {
			$ngs_options = $this->get_admin_options( );
			if( strtolower( 'root' == $ngs_options['samdbuser'] ) )
				echo 'Please Check Your Configuration Options';
			else
				require dirname(__FILE__).'/php/toprequests.php';
		}
		
		function generate_songsearch_tag( $atts, $content = null ) {
			extract( shortcode_atts( array( 
				'search' => $content,
			), $atts) );
			$ngs_options = $this->get_admin_options( );
			$songlistpst = $ngs_options['byartistpageid'];
			$songlistlink = false;
			if( null != $songlistpst )
				$songlistlink = get_permalink( $songlistpst );
			
			if( null != $content && false != $songlistlink ) {
				$songsearchlink = add_query_arg( array ('songsearchtext' => $search ), $songlistlink );
				return '<a href="'.$songsearchlink.'">'.$content.'</a>';
			}
		}
		
		function generate_toplist( $atts ) {
			$ngs_options = $this->get_admin_options();
			require_once dirname( __FILE__ ).'/php/samdbaccess.php';
			require_once dirname( __FILE__ ).'/php/functions.php';
			
			extract( shortcode_atts( array(
				'num' => '5',
			), $atts ) );
			
			global $samdb;
			
			$output = '<table border="0" width="98%" cellspacing="0" cellpadding="4">';
			$songlist = $samdb->get_results( build_top_request_query( '0', $num ) );
			$row_number = 1;
			if( empty( $songlist ) ) 
			{
				$output = $output."The List is Empty\n";
			} else {
				foreach ( $songlist as $song ) {
					$song_info = prepare_song( $song, 0, $numtoshow );
					$output = $output.'<tr>';
					$output = $output.'<td>'.$row_number++.'</td>';
					$output = $output.'<td>'.$song_info['artist'].'<br />'.$song_info['title'];
					$output = $output.'&nbsp;&nbsp;('.$song_info['requestcount'].')</td>';
					$output = $output.'<td>'.$song_info['formattedduration'].'</td>';
					$output = $output.'</tr>';
				}
			}
			$output = $output.'</table>';
			return $output;
		}
	}
} // End Class NGSSamRequests

if ( class_exists( "NGS_SAM_Integrator" ) ) {
	$ngs_sam_integrator = new NGS_SAM_Integrator( );
}

if ( ! function_exists( ngs_sam_integrator_ap ) ) {
	/**
	 * Add options page in dashboard
	 * 
	 * This function adds the options configuration screen to the 
	 * dashboard.   
	 * 
	 * @global object $ngs_sam_integrator
	 * @return null
	 */
	function ngs_sam_integrator_ap( ) {
		
		global $ngs_sam_integrator;
		if( ! isset( $ngs_sam_integrator ) ) {
			return;
		}
		if ( function_exists( 'add_options_page' ) ) {
			add_options_page( 'NGS SAM Integrator', 'NGS SAM Integrator', 'manage_options', basename(__FILE__), array(&$ngs_sam_integrator, 'print_admin_page' ) );
		}
  	}
}

if( isset( $ngs_sam_integrator ) ) {
	//Actions
	add_action( 'activate_ngs-sam-integrator/ngs-sam-integrator.php', 
			array( &$ngs_sam_integrator, 'init' ) );
	add_action( 'admin_menu', 'ngs_sam_integrator_ap' );
	add_action( 'wp_enqueue_scripts', array( &$ngs_sam_integrator, 'add_header_code' ) );

	//Filters
	
	//Shortcodes
	add_shortcode( 'samplaylist', array( &$ngs_sam_integrator, 'display_play_list' ) );
	add_shortcode( 'samtoprequests', array( &$ngs_sam_integrator, 'display_top_requests' ) );
	add_shortcode( 'songsearch', array( &$ngs_sam_integrator, 'generate_songsearch_tag' ) );
	add_shortcode( 'toplist', array ( &$ngs_sam_integrator, 'generate_toplist' ) );
	
	//Widgets
	require_once dirname( __FILE__ ).'/php/widgets.php';
	
}