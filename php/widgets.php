<?php
/**
 * Code for Widgets in the NGS Sam Integrator Plug-in
 * 
 * @since 1.1.0
 */

class ngs_top_req_widget extends WP_Widget
{
  var $admin_options_name = "NGSSAMIntegratorAdminOptions";

  function ngs_top_req_widget()
  {
    $widget_ops = array('classname' => 'ngs_top_req_widget', 'description' => 'Displays a short list of the top requests' );
    $this->WP_Widget('TopRequestsWiidget', 'Top Requests', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => 'Top 5 Requests', 'numtoshow' => '5' ) );
    $title = $instance['title'];
	$numtoshow = $instance['numtoshow'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: </label><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></p>
  <p><label for="<?php echo $this->get_field_id('numtoshow'); ?>">Number to Show: </label><input class="widefat" id="<?php echo $this->get_field_id('numtoshow'); ?>" name="<?php echo $this->get_field_name('numtoshow'); ?>" type="text" value="<?php echo attribute_escape($numtoshow); ?>" /></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['numtoshow'] = $new_instance['numtoshow'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
	global $samdb;
	$ngs_options = $this->get_admin_options();
	require_once dirname( __FILE__ ).'/samdbaccess.php';
	require_once dirname( __FILE__ ).'/functions.php';
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	$numtoshow = empty($instance['numtoshow']) ? '5' : $instance['numtoshow'];
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
			$output = '<table border="0" width="98%" cellspacing="0" cellpadding="4">';
			$songlist = $samdb->get_results( build_top_request_query( '0', $numtoshow ) );
			$row_number = 1;
			if( empty( $songlist ) ) 
			{
				$output = $output."The List is Empty\n";
			} else {
				foreach ( $songlist as $song ) {
					$song_info = prepare_song( $song, 0, $numtoshow );
					$output = $output.'<tr>';
					if ( $ngs_options['showartreq'] == 'false' ) {
						$output = $output.'<td>'.$row_number++.'  </td>';
						 $output = $output.'<td>'.$song_info['artist'].'<br />'.$song_info['title'];
					} else {
						$output = $output.'<td><img src="'.$ngs_options['ngsartdir'].$song_info['album'].'" height="'.$ngs_options['artrh'].'" width="'.$ngs_options['artrw'].'">'.$song_info['artist'].'<br />'.$song_info['title'];
					}
					$output = $output.'&nbsp;&nbsp;('.$song_info['requestcount'].')</td>';
					if ( $ngs_options['showtimereq'] == 'true' ) {
						$output = $output.'<td>'.$song_info['formattedduration'].'</td>';
					} else {
						null;
					}
					$output = $output.'</tr>';
				}
			}
			$output = $output.'</table>';
	echo $output;
    echo $after_widget;
  }
 
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
			'showartreq' => 'true',
			'artqh' => '60',
			'artqw' => '60',
			'ngsartdir' => '/sam/',
		);
		$saved_options = get_option( $this->admin_options_name );
		if ( ! empty( $saved_options ) ) {
			foreach ( $saved_options as $key => $option )
				$ngs_admin_options[$key] = $option;
		}				
		update_option( $this->admin_options_name, $ngs_admin_options );
		return $ngs_admin_options;
	}
}

class ngs_upcoming_tracks_widget extends WP_Widget
{
  var $admin_options_name = "NGSSAMIntegratorAdminOptions";

  function ngs_upcoming_tracks_widget()
  {
    $widget_ops = array('classname' => 'ngs_upcoming_tracks_widget', 'description' => 'Displays a list of upcoming tracks' );
    $this->WP_Widget('UpcomingTracksWiidget', 'Upcoming Tracks', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => 'Coming Up', 'numtoshow' => '5' ) );
    $title = $instance['title'];
	$numtoshow = $instance['numtoshow'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: </label><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></p>
  <p><label for="<?php echo $this->get_field_id('numtoshow'); ?>">Number to Show: </label><input class="widefat" id="<?php echo $this->get_field_id('numtoshow'); ?>" name="<?php echo $this->get_field_name('numtoshow'); ?>" type="text" value="<?php echo attribute_escape($numtoshow); ?>" /></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['numtoshow'] = $new_instance['numtoshow'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
	global $samdb;
	$ngs_options = $this->get_admin_options();
	require_once dirname( __FILE__ ).'/samdbaccess.php';
	require_once dirname( __FILE__ ).'/functions.php';
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	$numtoshow = empty($instance['numtoshow']) ? '5' : $instance['numtoshow'];
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
			$output = '<table border="0" width="98%" cellspacing="0" cellpadding="4">';
			$songlist = $samdb->get_results( build_upcoming_tracks_query( $numtoshow ) );
			$row_number = 1;
			if( empty( $songlist ) ) 
			{
				$output = "The List is Empty\n";
			} else {
				foreach ( $songlist as $song ) {
					$song_info = prepare_widget_song( $song );
					$output = $output.'<tr>';
					if (  $ngs_options['showartque'] == 'false' ) {
						$output = $output.'<td>'.$row_number++.'</td>';
						$output = $output.'<td><center><strong>'.$song_info['artist'].'</strong><br />'.$song_info['title'].'</center></td>';
					} else {
						$output = $output.'<td><center><img src="'.$ngs_options['ngsartdir'].$song_info['album'].'" height="'.$ngs_options['artqh'].'" width="'.$ngs_options['artqw'].'"><br /><strong>'.$song_info['artist'].'</strong><br />'.$song_info['title'].'</center></td>';
					}
					if ( $ngs_options['showtimeque'] == 'true' ) {
						$output = $output.'<td>'.$song_info['formattedduration'].'</td>';
					} else {
						null;
					}
					$output = $output.'</tr>';
				}
			}
			$output = $output.'</table>';
	echo $output;
    echo $after_widget;
  }
 
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
			'showartque' => 'true',
			'artqh' => '60',
			'artqw' => '60',
			'ngsartdir' => '/sam/',
		);
		$saved_options = get_option( $this->admin_options_name );
		if ( ! empty( $saved_options ) ) {
			foreach ( $saved_options as $key => $option )
				$ngs_admin_options[$key] = $option;
		}				
		update_option( $this->admin_options_name, $ngs_admin_options );
		return $ngs_admin_options;
	}
}

class ngs_recently_played_widget extends WP_Widget
{
  var $admin_options_name = "NGSSAMIntegratorAdminOptions";

  function ngs_recently_played_widget()
  {
    $widget_ops = array('classname' => 'ngs_recently_played_widget', 'description' => 'Displays a list of Recently Played Tracks' );
    $this->WP_Widget('RecentlyPlayedWiidget', 'Recently Played', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => 'Recently Played', 'numtoshow' => '5' ) );
    $title = $instance['title'];
	$numtoshow = $instance['numtoshow'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: </label><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></p>
  <p><label for="<?php echo $this->get_field_id('numtoshow'); ?>">Number to Show (before current track): </label><input class="widefat" id="<?php echo $this->get_field_id('numtoshow'); ?>" name="<?php echo $this->get_field_name('numtoshow'); ?>" type="text" value="<?php echo attribute_escape($numtoshow); ?>" /></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['numtoshow'] = $new_instance['numtoshow'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
	global $samdb;
	$ngs_options = $this->get_admin_options();
	require_once dirname( __FILE__ ).'/samdbaccess.php';
	require_once dirname( __FILE__ ).'/functions.php';
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	$numtoshow = empty($instance['numtoshow']) ? '5' : $instance['numtoshow'];
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
			$output = '<table border="0" width="98%" cellspacing="0" cellpadding="4">';
			$songlist = $samdb->get_results( build_recently_played_query( $numtoshow ) );
			$row_number = 0;
			if( empty( $songlist ) ) 
			{
				$output = $output."The List is Empty\n";
			} else {
				foreach ( $songlist as $song ) {
					$song_info = prepare_widget_song( $song, 0, $numtoshow );
					$output = $output.'<tr>';
					if ( 0 == $row_number )
						$output = $output.'<td><strong>Now</strong></td>';
					else {
					    if ( $ngs_options['showartplayed'] == 'false' ) {
						$output = $output.'<td>'.$row_number.'</td>';
					    } else {
						null;
					    }
					}
					if ( $ngs_options['showartplayed'] == 'false' ) {
					 $output = $output.'<td><center>'.( 0 == $row_number ? '<strong>' : '').$song_info['artist'].'<br />'.$song_info['title'].'</td>';
					} else {
					$output = $output.'<td><center><img src="'.$ngs_options['ngsartdir'].$song_info['album'].'" height="'.$ngs_options['artph'].'" width="'.$ngs_options['artpw'].'"><br /><strong>'.$song_info['artist'].'<br />'.$song_info['title'].'</strong></center></td>';
					}
					$output = $output.( 0 == $row_number ? '</strong>' : '').'</td>';
					if ( $ngs_options['showtimeplay'] == 'true' ) {
						$output = $output.'<td>'.$song_info['formattedduration'].'</td>';
					} else {
						null;
					}
					$row_number++;
					$output = $output.'</tr>';
				}
			}
			$output = $output.'</table>';
	echo $output;
    echo $after_widget;
  }
 
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
			'showartplayed' => 'true',
			'artph' => '60',
			'artpw' => '60',
			'ngsartdir' => '/sam/',
		);
		$saved_options = get_option( $this->admin_options_name );
		if ( ! empty( $saved_options ) ) {
			foreach ( $saved_options as $key => $option )
				$ngs_admin_options[$key] = $option;
		}				
		update_option( $this->admin_options_name, $ngs_admin_options );
		return $ngs_admin_options;
	}
}

class ngs_playing_now_widget extends WP_Widget
{
  var $admin_options_name = "NGSSAMIntegratorAdminOptions";

  function ngs_playing_now_widget()
  {
    $widget_ops = array('classname' => 'ngs_playing_now_widget', 'description' => 'Displays Current Track' );
    $this->WP_Widget('PlayingNowWiidget', 'Playing Now', $widget_ops);
  }

  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => 'Playing Now', 'numtoshow' => '0' ) );
    $title = $instance['title'];
        $numtoshow = $instance['numtoshow'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: </label><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></p> 
  <p><h5>Hint: Leaving this field empty hides the widget title for a sleeker look</h5></p>
  <p><label for="<?php echo $this->get_field_id('numtoshow'); ?>">Number to Show (before current track): </label><input class="widefat" id="<?php echo $this->get_field_id('numtoshow'); ?>" name="<?php echo $this->get_field_name('numtoshow'); ?>" type="text" value="<?php echo attribute_escape($numtoshow); ?>" /></p>
  <p><h5>Hint: Set this to 0 for current track only, set to 5 to show the next 5 tracks as well as the currently playing track</h5></p>
<?php
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
        $instance['numtoshow'] = $new_instance['numtoshow'];
    return $instance;
  }

  function widget($args, $instance)
  {
        global $samdb;
        $ngs_options = $this->get_admin_options();
        require_once dirname( __FILE__ ).'/samdbaccess.php';
        require_once dirname( __FILE__ ).'/functions.php';
    extract($args, EXTR_SKIP);

    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $numtoshow = empty($instance['numtoshow']) ? '0' : $instance['numtoshow'];

    if (!empty($title))
      echo $before_title . $title . $after_title;;

    // WIDGET CODE GOES HERE
                        $output = '<table border="0" width="98%" cellspacing="0" cellpadding="4">';
                        $songlist = $samdb->get_results( build_playing_now_query( $numtoshow ) );
                        $row_number = 0;
                        if( empty( $songlist ) )
                        {
                                $output = $output."The List is Empty\n";
                        } else {
				if ( $ngs_options['showlinks'] == 'true' ) {
					$logovlc = plugins_url( '../images/winamp.png', __FILE__ ); 
					$logowmp = plugins_url( '../images/wmp.png', __FILE__ ); 
					$logoqtl = plugins_url( '../images/quick.png', __FILE__ ); 
					$logoitns = plugins_url( '../images/itunes.png', __FILE__ ); 
					$output = $output.'<tr">';
					$output = $output.'<td width="40"><a href="'.$ngs_options['winvlc'].'"><img src="'.$logovlc.'" height="30" width="30" title="Listen in with VLC or Winamp" /></a></td>';
					$output = $output.'<td width="40"><a href="'.$ngs_options['winmp'].'"><img src="'.$logowmp.'" height="30" width="30" title="Listen in with Windows Media Player" /></a></td>';
					$output = $output.'<td width="40"><a href="'.$ngs_options['winqtl'].'"><img src="'.$logoqtl.'" height="30" width="30" title="Listen in with QuickTime or RealPlayer" /></a></td>';
					$output = $output.'<td width="40"><a href="'.$ngs_options['wintns'].'"><img src="'.$logoitns.'" height="30" width="30" title="Listen in with iTunes" /></a></td>';
					//$output = $output.'</tr>';
				} else {
					null;
				}
			 foreach ( $songlist as $song ) {
                                        $song_info = prepare_widget_song( $song, 0, $numtoshow );
                                        $output = $output.'<tr>';
                                        if ( 0 == $row_number && $ngs_options['showlinks'] == 'true' )
						null;
					else if ( 0 == $row_number )
                                                $output = $output.'<td><center><strong><h3 style="color:'.$ngs_options['pncolor'].';">Playing Now</h3></strong></center></td><tr>';
                                        else {
                                            if ( $ngs_options['showartplaynow'] == 'false' ) {
                                                $output = $output.'<td>'.$row_number.'</td></tr>';
                                            } else {
                                                null;
                                            }
                                        }
                                        if ( $ngs_options['showartplaynow'] == 'false' ) {
                                         $output = $output.'<td><center>'.( 0 == $row_number ? '<strong>' : '').$song_info['artist'].'<br />'.$song_info['title'].'</td>';
                                        } else {
                                        $output = $output.'<tdalign="right"><center><img src="'.$ngs_options['ngsartdir'].$song_info['album'].'" height="'.$ngs_options['artpnh'].'"width="'.$ngs_options['artpnw'].'"><br /><strong>'.$song_info['artist'].'<br />'.$song_info['title'].'</strong></center></td>';

                                        }
                                        $output = $output.( 0 == $row_number ? '</strong>' : '').'</td>';
                                        if ( $ngs_options['showtimeplay'] == 'true' ) {
                                                $output = $output.'<td>'.$song_info['formattedduration'].'</td>';
                                        } else {
                                                null;
                                        }
                                        $row_number++;
                                        $output = $output.'</tr>';
                                }
                        }
                        $output = $output.'</table>';
        echo $output;
    echo $after_widget;
  }

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
                        'showartplaynow' => 'true',
                        'artpnh' => '60',
                        'artpnw' => '60',
			'ngsartdir' => '/sam/',
                );
                $saved_options = get_option( $this->admin_options_name );
                if ( ! empty( $saved_options ) ) {
                        foreach ( $saved_options as $key => $option )
                                $ngs_admin_options[$key] = $option;
                }
                update_option( $this->admin_options_name, $ngs_admin_options );
                return $ngs_admin_options;
        }
}


add_action( 'widgets_init', create_function( '', 'register_widget("ngs_playing_now_widget");' ) );
add_action( 'widgets_init', create_function( '', 'register_widget("ngs_top_req_widget");' ) );
add_action( 'widgets_init', create_function( '', 'register_widget("ngs_upcoming_tracks_widget");' ) );
add_action( 'widgets_init', create_function( '', 'register_widget("ngs_recently_played_widget");' ) );

// Removed Anonymous Functions to Offer Wider Compatibility

//add_action( 'widgets_init', function() { register_widget("ngs_top_req_widget"); } );
//add_action( 'widgets_init', function() { register_widget("ngs_upcoming_tracks_widget"); } );
//add_action( 'widgets_init', function() { register_widget("ngs_recently_played_widget"); } );
