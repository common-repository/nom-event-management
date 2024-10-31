<?php
/*
Plugin Name: Nom Event Management
Plugin URI: http://wordpress.org/plugins/nom-event-management/
Description: A custom plugin to manage events and display them in sidebar and shortcode.
Author: MAK Joy
Version: 1.0
Author URI: http://nomfolio.com/me
*/


if( !defined('NOM_EVENT_MANAGEMENT_DIR') ){
	define('NOM_EVENT_MANAGEMENT_DIR',dirname(__FILE__));
}

/**
 * @author n0m
 *
 */
class Nom_Event_Management{
	
	/**
	 * @var string
	 */
	private $dirpath = '';
	/**
	 * @var string
	 */
	private $diruri = '';
	
	/**
	 * 
	 */
	public function __construct() {
		$this->set_dirpath(plugin_dir_path(__FIEL__));
		$this->set_diruri(plugin_dir_url(__FILE__));		
		
		$this->init();
		$this->filter();
		$this->widget();
		
	}
	
	/**
	 * @param string $path
	 */
	public function set_dirpath($path){
		$this->dirpath = $path;
	}
	/**
	 * @param string $uri
	 */
	public function set_diruri($uri){
		$this->diruri = $uri;
	}	
	/**
	 * @return string
	 */
	public function get_dirpath(){
		return $this->dirpath;
	}
	/**
	 * @return string
	 */
	public function get_diruri(){
		return $this->diruri;
	}
	
	
	/**
	 * 
	 */
	public function init(){
		add_action( 'init', array('Nom_Event_Management','event_custom_post_example'));
		add_action( 'init', array('Nom_Event_Management','initialize_cmb_meta_boxes'), 9999 );
		add_action( 'init', array('Nom_Event_Management','category'), 9999 );
		//add_action('pre_get_posts',array('Nom_Event_Management','custom_pre_get_posts'));			
	}
	
	/**
	 * 
	 */
	public function filter(){
		add_filter( 'cmb_meta_boxes', array('Nom_Event_Management','event_metaboxes') );
		add_filter( 'the_content', array('Nom_Event_Management','event_content') );
		add_filter('posts_clauses', array('Nom_Event_Management','custom_search_where'), 20, 1);
		add_filter( 'enter_title_here', array('Nom_Event_Management','change_default_title' ));		
	}
	
	/**
	 * 
	 */
	public function event_custom_post_example() {
		// creating (registering) the custom type
		register_post_type( 'event', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
			array('labels' => array(
			'name' => 'Events', /* This is the Title of the Group */
			'singular_name' =>'Event',
			'all_items' => 'All Events',
			'add_new' => 'Add New Event',
			'add_new_item' => 'Add New Event',
			'edit' =>  'Edit',
			'edit_item' => 'Edit Event',
			'new_item' => 'New Event',
			'view_item' => 'View Event',
			'search_items' => 'Search Event',
			'not_found' => 'Nothing found in the Database.',
			'not_found_in_trash' => 'Nothing found in Trash',
			'parent_item_colon' => ''
			), /* end of arrays */
		'description' => 'Event post type.',
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'show_ui' => true,
		'query_var' => true,
		'menu_position' => 7,
		//'menu_icon' => get_stylesheet_directory_uri() . '/images/artist.png',
		'rewrite'	=> array( 'slug' => 'event', 'with_front' => false ),
		'has_archive' => 'event',
		'capability_type' => 'post',
		'hierarchical' => false,
		
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'sticky')
		) /* end of options */
		); /* end of register post type */
				
	}
	/**
	 * 
	 */
	public function initialize_cmb_meta_boxes() {
		if ( !class_exists( 'cmb_Meta_Box' ) ) {
			require_once  NOM_EVENT_MANAGEMENT_DIR . '/custom-metabox/init.php';
		}
	}
	
	/**
	 * @param array $meta_boxes
	 * @return multitype:string boolean multitype:string  multitype:multitype:string   
	 */
	public function event_metaboxes( $meta_boxes ) {
		$prefix = '_event-description_'; // Prefix for all fields
		$meta_boxes[] = array(
				'id' => 'event-',
				'title' => 'Event Description',
				'pages' => array('event'), // post type
				'context' => 'normal',
				'priority' => 'high',
				'show_names' => true, // Show field names on the left
				'fields' => array(
						array(
								'name' => 'Date',
								'desc' => 'The date of the event.',
								'id' => $prefix . 'event-date',
								'type' => 'text_date'
						),
						array(
								'name' => 'Number of days',
								'desc' => 'The number of days of the event.',
								'id' => $prefix . 'event-date-count',
								'type' => 'text'
						),
						array(
								'name' => 'Official Website',
								'desc' => 'The official website link of the event.',
								'id' => $prefix . 'event-website',
								'type' => 'text_url'
						),
						array(
								'name' => 'City',
								'desc' => 'The name of the city where the event is taking place.',
								'id' => $prefix . 'event-city',
								'type' => 'text'
						),
						array(
								'name' => 'State',
								'desc' => 'The name of the state where the event is taking place.',
								'id' => $prefix . 'event-state',
								'type' => 'text'
						),
						array(
								'name' => 'Country',
								'desc' => 'The name of the country where the event is taking place.',
								'id' => $prefix . 'event-country',
								'type' => 'text'
						),
						array(
								'name' =>'Event Image Gallery',
								'desc' => 'Upload or add multiple images/attachments of the event (if necessary).',
								'id'   => $prefix . 'event-gallery',
								'type' => 'file_list',
						)
				),
		);
	
		return $meta_boxes;	
				
	}
	
	/**
	 * @param string $content
	 * @return string
	 */
	public function event_content($content){
		global $wp_query;
			
		if('event' == $wp_query->get('post_type')){
			
			$images = get_post_meta(get_the_ID(),'_event-description_event-gallery',true);
			
			$list = '';
			
			if( is_array($images) )	:
				foreach( $images as $img){
					
					$list .= '
					<p class="event_gallery_item event-gallery-items">
						<a href="'.$img.'"><img src="'.$img.'" class="event-gallery-item-image"></a>
					</p>';
				}
			endif;
			
			
			$event_details = '
				<div class="event_wrapper">
					<div class="event_inner">
						<div class="event_container">
							<div class="event_data">
								<p class="event-date"><span>Event Date:</span>
									<span class="event-date-content">'.get_post_meta(get_the_ID(),'_event-description_event-date',true).'</span></p>
								<p class="event-date-count"><span>Number of days:</span>
									<span class="event-official-website-content"><a href="'.get_post_meta(get_the_ID(),'_event-description_event-website',true).'">'.get_post_meta(get_the_ID(),'_event-description_event-website',true).'</a></span></p>
								<p class="event-website"><span>Event Official Website:</span>
									<span class="event-official-website-content">'.get_post_meta(get_the_ID(),'_event-description_event-website',true).'</span></p>
								<p class="event-city"><span>Event City:</span>
									<span class="event-city-content">'.get_post_meta(get_the_ID(),'_event-description_event-city',true).'</span></p>
								<p class="event-state"><span>Event State:</span>
									<span class="event-state-content">'.get_post_meta(get_the_ID(),'_event-description_event-state',true).'</span></p>
								<p class="event-country"><span>Event Country:</span>
									<span class="event-country-content">'.get_post_meta(get_the_ID(),'_event-description_event-country',true).'</span></p>
							</div>
							<div class="event_gallery">
								<div class="event_gallery_inner">
									<div class="event_gallery_container">
										<div class="event_gallery_items_list">
											'.$list.'
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
			
			
			$content .= $event_details;			
			return $content;
		}
		
		return $content;
	}
	
	/**
	 * 
	 */
	public function widget() {
		if ( !class_exists( 'Nom_Event_Management_Widget' ) ) {
			require_once  NOM_EVENT_MANAGEMENT_DIR . '/widget-boilerplate/plugin.php';
		}
	}
	
	/**
	 * 
	 */
	public function category(){
	
	    register_taxonomy( 'event_cat',
	    	array('event'), 
	    	array('hierarchical' => true,     
	    		'labels' => array(
	    			'name' => 'Event Categories',
	    			'singular_name' => 'Event Category',
	    			'search_items' =>  'Search Event Categories',
	    			'all_items' => 'All Event Categories', 
	    			'parent_item' =>'Parent Event Category',
	    			'parent_item_colon' => 'Parent Event Category:', 
	    			'edit_item' => 'Edit Event Category', 
	    			'update_item' => 'Update Event Category', 
	    			'add_new_item' => 'Add New Event Category', 
	    			'new_item_name' => 'New Event Category Name' 
	    		),
	    		'show_admin_column' => true,
	    		'show_ui' => true,
	    		'query_var' => true,
	    		'rewrite' => array( 'slug' => 'event_slug' ),
	    	)
	    );
		
		
	    register_taxonomy( 'event_tag',
	    	array('event'), 
	    	array('hierarchical' => false,    
	    		'labels' => array(
	    			'name' => 'Event Tags', 
	    			'singular_name' => 'Event Tag',  
	    			'search_items' =>  'Search Event Tags', 
	    			'all_items' =>  'All Event Tags', 
	    			'parent_item' =>  'Parent Event Tag', 
	    			'parent_item_colon' => 'Parent Event Tag:', 
	    			'edit_item' => 'Edit Event Tag', 
	    			'update_item' => 'Update Event Tag', 
	    			'add_new_item' => 'Add New Event Tag', 
	    			'new_item_name' => 'New Event Tag Name'
	    		),
	    		'show_admin_column' => true,
	    		'show_ui' => true,
	    		'query_var' => true,
	    	)
	    );
	}
	
	/**
	 * @param object $query
	 */
	function custom_pre_get_posts($query){
		//$m = new WP_Query();
	
		//var_dump($query);
	
// 		if($query->get('s') == $_REQUEST['s'] and $query->is_search() and !$query->is_posts_page){
			
			
// 			$query->set('meta_query',array(
// 					array(
// 						"key" => '_event-description_event-state',
// 						//"value" => explode(' ', get_query_var('s')),
// 						"value" => get_query_var('s'),
// 						"compare" => 'LIKE'								
// 					),
// 					array(
// 						"key" => '_event-description_event-country',
// 						//"value" => explode(' ', get_query_var('s')),
// 						"value" => get_query_var('s'),
// 						"compare" => 'LIKE'
// 					),
// 					array(
// 						"key" => '_event-description_event-city',
// 						//"value" => explode(' ', get_query_var('s')),
// 						"value" => get_query_var('s'),
// 						"compare" => 'LIKE'
// 					)
// 				));
// 		}

		
		//add_filter( '', array('Nom_Event_Management','posts_distinct' ));
		var_dump($query);
		return $query;
	} // ends
	
	/**
	 * @param array $pieces
	 * @return array
	 */
	public function custom_search_where($pieces) {	
		// filter to select search query
		if (is_search() && !is_admin()) {
			$pieces['distinct'] = 'distinct';
			global $wpdb;
			$custom_fields = array('_event-description_event-state','_event-description_event-country','_event-description_event-city');
			$keywords = explode(' ', get_query_var('s'));
			$query = "";
			foreach ($custom_fields as $field) {
				foreach ($keywords as $word) {
					$query .= "((mypm1.meta_key = '".$field."')";
					$query .= " AND (mypm1.meta_value  LIKE '%{$word}%')) OR ";
				}
			}
		
			if (!empty($query)) {
				// add to where clause
				$pieces['where'] = str_replace("(((wp_posts.post_title LIKE '%", "( {$query} ((wp_posts.post_title LIKE '%", $pieces['where']);
		
				$pieces['join'] = $pieces['join'] . " INNER JOIN {$wpdb->postmeta} AS mypm1 ON ({$wpdb->posts}.ID = mypm1.post_id)";
			}
		}
		
		
		return ($pieces);
		
	} // ends
	
	public function change_default_title( $title ){
		$screen = get_current_screen();
		switch($screen->post_type) {
			case 'event':
				$title = 'Please Specify Event Title';
				break;
		}
		return $title;
	}	//	ends
	
	public function posts_distinct($clause){
		var_dump($clause);
		remove_filter( current_filter(), __FUNCTION__ );
		$clause[0] = "DISTINCT";
		return $clause;
	}	//	ends
	
}



global $nom_event_management;

$nom_event_management = new Nom_Event_Management();


