<!-- This file is used to markup the public-facing widget. -->

<?php 


	$title = apply_filters( 'widget_title', empty($instance['title']) ? 'Latest Events' : $instance['title'], $instance, $this->id_base);
	
	
		
		
		
	// Widget title
		
	echo $before_title;
	echo $title;
	echo $after_title;
	
	
	$arg = array();
	
	$arg['post_type'] = 'event';
	$arg['meta_key'] = '_event-description_event-date';
	$arg['orderby'] = 'meta_value';
	$arg['order'] = 'ASC';
	$arg['posts_per_page'] = (int) $instance['event_count'];
	
	if( isset($instance['enable_category_filter']) ){
		
		if( is_array($arg['tax_query']) ){
			$arg['tax_query'][] = array(
										'taxonomy' => 'event_cat',
										'field' => 'id',
										'terms' => $instance['category']					
								);
		}
		else{
			$arg['tax_query'] = array(
									array(
										'taxonomy' => 'event_cat',
										'field' => 'id',
										'terms' => $instance['category']
									)
								);
		}
	}
	
	if( isset($instance['enable_tag_filter']) ){
		if( is_array($arg['tax_query']) ){
			$arg['tax_query'][] = array(
										'taxonomy' => 'event_tag',
										'field' => 'id',
										'terms' => $instance['tag']					
								);
		}
		else{
			$arg['tax_query'] = array(
									array(
										'taxonomy' => 'event_tag',
										'field' => 'id',
										'terms' => $instance['tag']
									)
								);
		}
	}
	//var_dump($instance);
	/*
	 * 
	 * 'title' => string 'Latest Events' (length=13)
	   'event_count' => string '12' (length=2)
	   'enable_category_filter' => string 'on' (length=2)
	   'category' => string '11' (length=2)
	   'enable_tag_filter' => string 'on' (length=2)
	   'tag' => string '17' (length=2)
	 * */
	
	$query = new WP_Query( $arg );
	//var_dump($query);
	echo '<ul style="max-height:'.$instance['max_height'].'px;overflow:auto;">';
	
	if( $query->have_posts() ){
		while( $query->have_posts() ){
			$query->the_post();
			
			echo '<li>';
			echo '<p><span class="date">'.get_post_meta(get_the_ID(),'_event-description_event-date',true).'</span>&nbsp;<a href="'.get_permalink().'">'.get_the_title().'</a></p>';
			echo '</li>';
			
		}
	}
	else{
		echo '<li>No events found.</li>';
	}
	
	echo '</ul>';
	
	

?>