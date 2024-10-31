<?php


class Nom_Event_Management_Widget extends WP_Widget {

	public function __construct() {
				
		parent::__construct(
			'nom-event-management-widget',
			'Nom Event Management Widget',
			array(
				'classname'		=>	'nom-event-management-widget-class',
				'description'	=>	'Display a list of events in the sidebar based on the choosen parameters.'
			)
		);
		
		// Register admin styles and scripts
		//add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
	
		// Register site styles and scripts
		//add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );
		
	} // end constructor

	
	public function widget( $args, $instance ) {
	
		extract( $args, EXTR_SKIP );
		
		echo $before_widget;
    
		include( plugin_dir_path( __FILE__ ) . '/views/widget.php' );
		
		echo $after_widget;
		
	} // end widget
		
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['event_count'] = $new_instance['event_count'];
		$instance['enable_category_filter'] = $new_instance['enable_category_filter'];
		$instance['category'] = $new_instance['category'];
		$instance['enable_tag_filter'] = $new_instance['enable_tag_filter'];
		$instance['tag'] = $new_instance['tag'];		
		$instance['max_height'] = $new_instance['max_height'];		
				
		return $instance;
		
	} // end widget
	
	
	public function form( $instance ) {
	
    	
		$instance = wp_parse_args(
			(array) $instance,
				array(
					'title' => 'Latest Events',
					'event_count' => -1,
					'enable_category_filter' => 0,
					'category' => null,
					'enable_tag_filter' => 0,
					'tag' => null,
					'max_height' => 300
				)
		);
		extract($instance);
		// TODO:	Store the values of the widget in their own variable
		
		// Display the admin form
		include( plugin_dir_path(__FILE__) . '/views/admin.php' );	
		
	} // end form
	
	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
	
		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_style( 'widget-name-admin-styles', plugins_url( 'widget-name/css/admin.css' ) );
	
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */	
	public function register_admin_scripts() {
	
		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_script( 'widget-name-admin-script', plugins_url( 'widget-name/js/admin.js' ) );
		
	} // end register_admin_scripts
	
	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {
	
		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_style( 'nom-event-management-widget-styles', plugins_url( 'nom-event-management/widget-boilerplate/css/widget.css' ) );
		
	} // end register_widget_styles
	
	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {
	
		// TODO:	Change 'widget-name' to the name of your plugin
		wp_enqueue_script( 'nom-event-management-widget-script', plugins_url( 'nom-event-management/widget-boilerplate/js/widget.js' ) );
		
	} // end register_widget_scripts
	
} // end class

add_action( 'widgets_init', create_function( '', 'register_widget("Nom_Event_Management_Widget");' ) ); 