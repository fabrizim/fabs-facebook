<?php


/**
 * Latest Event widget class
 *
 * @since 2.8.0
 */
class Fabs_Facebook_Widget_Latest_Event extends WP_Widget {

	function Fabs_Facebook_Widget_Latest_Event() {
		$widget_ops = array('classname' => 'widget_fabs_facebook_latest_event', 'description' => __( "Display Latest Facebook Event") );
		$this->WP_Widget('fabs_facebook_latest_event', __('Facebook Latest Event'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		// check for fabs_facebook
        $id = @$instance['facebook_id'];
        if( !$id ) $id = null;
        fabs_facebook_latestevent($instance['facebook_id']);
		echo $after_widget;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
        $facebook_id = $instance['facebook_id'];
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('facebook_id'); ?>"><?php _e('Facebook ID:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('facebook_id'); ?>" name="<?php echo $this->get_field_name('facebook_id'); ?>" type="text" value="<?php echo esc_attr($facebook_id); ?>" />
			</label>
		</p>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => '', 'facebook_id' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['facebook_id'] = strip_tags($new_instance['facebook_id']);
		return $instance;
	}

}

/**
 * Events Widget Class
 *
 * @since 2.8.0
 */
class Fabs_Facebook_Widget_Events extends WP_Widget {

	function Fabs_Facebook_Widget_Events() {
		$widget_ops = array('classname' => 'widget_fabs_facebook_events', 'description' => __( "Display Facebook Events") );
		$this->WP_Widget('fabs_facebook_events', __('Facebook Events'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		// check for fabs_facebook
        $id = @$instance['facebook_id'];
		$limit = @$instance['limit'];
        if( !$id ) $id = null;
		$params = array();
		if( $limit && !empty($limit) ){
			$params['limit'] = $limit;
		}
        fabs_facebook_events($instance['facebook_id'], $params);
		echo $after_widget;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
        $facebook_id = $instance['facebook_id'];
		$limit = $instance['limit'];
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('facebook_id'); ?>"><?php _e('Facebook ID:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('facebook_id'); ?>" name="<?php echo $this->get_field_name('facebook_id'); ?>" type="text" value="<?php echo esc_attr($facebook_id); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
			</label>
		</p>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => '', 'facebook_id' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['facebook_id'] = strip_tags($new_instance['facebook_id']);
		$instance['limit'] = (int)$new_instance['limit'];
		return $instance;
	}

}

/**
 * Events Widget Class
 *
 * @since 2.8.0
 */
class Fabs_Facebook_Widget_Feed extends WP_Widget {

	function Fabs_Facebook_Widget_Feed() {
		$widget_ops = array('classname' => 'widget_fabs_facebook_feed', 'description' => __( "Display Facebook Feed") );
		$this->WP_Widget('fabs_facebook_feed', __('Facebook Feed'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		// check for fabs_facebook
        $id = @$instance['facebook_id'];
		$limit = @$instance['limit'];
        if( !$id ) $id = null;
		$params = array();
		if( $limit && !empty($limit) ){
			$params['limit'] = $limit;
		}
        fabs_facebook_feed($instance['facebook_id'], $params);
		echo $after_widget;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
        $facebook_id = $instance['facebook_id'];
		$limit = $instance['limit'];
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('facebook_id'); ?>"><?php _e('Facebook ID:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('facebook_id'); ?>" name="<?php echo $this->get_field_name('facebook_id'); ?>" type="text" value="<?php echo esc_attr($facebook_id); ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
			</label>
		</p>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => '', 'facebook_id' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['facebook_id'] = strip_tags($new_instance['facebook_id']);
		$instance['limit'] = (int)$new_instance['limit'];
		return $instance;
	}

}

add_action('widgets_init', 'fabs_facebook_widgets_init');
function fabs_facebook_widgets_init(){
    register_widget('Fabs_Facebook_Widget_Latest_Event');
	register_widget('Fabs_Facebook_Widget_Events');
	register_widget('Fabs_Facebook_Widget_Feed');
}