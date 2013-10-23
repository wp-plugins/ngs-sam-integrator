<?php
/**
 * Code for Widgets in the NGS Sam Integrator Plug-in
 * 
 * @since 1.0.1
 */

class ngs_top_req_widget extends WP_Widget
{
  function ngs_top_req_widget()
  {
    $widget_ops = array('classname' => 'ngs_top_req_widget', 'description' => 'Displays a short list of the top requests' );
    $this->WP_Widget('TopRequestsWiidget', 'Top Requests', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
    echo "<h1>This is my new widget!</h1>";
 
    echo $after_widget;
  }
 
}

add_action( 'widgets_init', function() { register_widget("ngs_top_req_widget"); } );
