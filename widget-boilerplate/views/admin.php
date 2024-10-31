<!-- This file is used to markup the administration form of the widget. -->
<p>
	<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
<br><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
<br><small>The title of the widget.</small>
</p>
<p>
	<label for="<?php echo $this->get_field_id('max_height'); ?>">Title</label>
<br><input class="widefat" id="<?php echo $this->get_field_id('max_height'); ?>" name="<?php echo $this->get_field_name('max_height'); ?>" type="text" value="<?php echo $max_height; ?>" />
<br><small>The maximum height of the widget (in pixels). If the height of the widget exceeds this then there will be a scrollbar.</small>
</p>
<p>
	<label for="<?php echo $this->get_field_id('event_count'); ?>">Number of Events</label>
<br><input class="widefat" id="<?php echo $this->get_field_id('event_count'); ?>" name="<?php echo $this->get_field_name('event_count'); ?>" type="text" value="<?php echo $event_count; ?>" />
<br><small>Number of events to be shown in the front end. Place -1 to show all.</small>	
</p>
<p>
	<input class="checkbox" type="checkbox"  id="<?php echo $this->get_field_id("enable_category_filter"); ?>" name="<?php echo $this->get_field_name("enable_category_filter"); ?>" <?php checked( (bool) $instance["enable_category_filter"], true ); ?>> 
	<label for="<?php echo $this->get_field_id("enable_category_filter"); ?>">Enable cateogry filter</label>	
<br><small>If you want to shoe the events list form the chosen catrgory list then first check this.</small>	
</p>
<p>
	<label for="<?php echo $this->get_field_id("category"); ?>">Category</label>
	<?php $evt_cats = get_terms('event_cat',array('hide_empty'=>1));?>
	<select name="<?php echo $this->get_field_name("category"); ?>" id="<?php echo $this->get_field_id("category"); ?>">
		<?php foreach($evt_cats as $evt_cat):?>
			<option value="<?php echo $evt_cat->term_id;?>" <?php selected( $instance["category"], $evt_cat->term_id ); ?>><?php echo $evt_cat->name?></option>
		<?php endforeach;?>
	</select>
<br><small>If you want to display events from a specific category then choose it.</small>
</p>
<p>
	<input class="checkbox" type="checkbox"  id="<?php echo $this->get_field_id("enable_tag_filter"); ?>" name="<?php echo $this->get_field_name("enable_tag_filter"); ?>" <?php checked( (bool) $instance["enable_tag_filter"], true ); ?>> 
	<label for="<?php echo $this->get_field_id("enable_tag_filter"); ?>">Enable tag filter</label>	
<br><small>If you want to shoe the events list form the chosen tag list then first check this.</small>	
</p>
<p>
	<label for="<?php echo $this->get_field_id("tag"); ?>">Tag</label>
	<?php $evt_tags = get_terms('event_tag',array('hide_empty'=>1));?>
	<select name="<?php echo $this->get_field_name("tag"); ?>" id="<?php echo $this->get_field_id("tag"); ?>">
		<?php foreach($evt_tags as $evt_tag):?>
			<option value="<?php echo $evt_tag->term_id;?>" <?php selected( $instance["tag"], $evt_tag->term_id ); ?>><?php echo $evt_tag->name?></option>
		<?php endforeach;?>
	</select>
<br><small>If you want to display events from a specific tag then choose it.</small>
</p>
