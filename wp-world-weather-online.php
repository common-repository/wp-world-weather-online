<?php
/*
Plugin Name: WP  Wolrd Weather Online
Plugin URI: 
Description: Displays a weather widget using the Wolrd Weather Online API.  Shows today's weather. Shows weather forecast for next 3 days. You can choose between Celsius or Fahrenheit.  You can choose between different color themes. Supports shortcodes for single pages or posts.  Send your suggestions to <a href="mailto:tlonovoi@gmail.com">tlonovoi@gmail.com</a>
Version: 1.2
Author: Tlon
Author URI: tlonovoi@gmail.com
*/

require_once(ABSPATH . WPINC . '/formatting.php');

add_action( 'widgets_init', 'load_wp_wwo' );

 
function load_wp_wwo() {
	register_widget( 'wp_wwo' );
}

/*
      WP  Wolrd Weather Online Widget class.
 */
 
class wp_wwo extends WP_Widget {

 
	function wp_wwo() {
	
		$widget_ops = array( 'description' => __('A widget that displays the weather.', 'wp_wwo') );
		$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'wp_wwo' );
		$this->WP_Widget( 'wp_wwo', __('WP  Wolrd Weather Online', 'wp_wwo'), $widget_ops, $control_ops );
	}

 

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['api_key'] = strip_tags( $new_instance['api_key'] );
		$instance['city'] = strip_tags( $new_instance['city'] );
		$instance['show_city'] =  $new_instance['show_city']['select_value'];
	 	$instance['temp'] = $new_instance['temp']['select_value'];
		$instance['color_scheme'] = $new_instance['color_scheme']['select_value'];
		$instance['forecast'] = $new_instance['forecast'];
		$instance['alignment'] = $new_instance['alignment'];
		
		return $instance;
	}

 
	function form( $instance ) {
	
    ?>
 	
		<p>
			<?php $title_str='Title (won\'t show if empty):'; ?>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e($title_str, 'title'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" 
			placeholder="enter your title"
			value="<?php echo $instance['title']; ?>"  size="26"  />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'api_key' ); ?>"><?php _e('<u>WorldWeatherOnline <b>API key</b> (<i>required</i>):</u>', 'api_key'); ?></label>
			<input  id="<?php echo $this->get_field_id( 'api_key' ); ?>" name="<?php echo $this->get_field_name( 'api_key' ); ?>" 
			placeholder="enter World Weather Online API key"
			value="<?php echo $instance['api_key']; ?>" size="26"   />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'city' ); ?>"><?php _e('<u>City (<i>required</i>):</u>', 'city'); ?></label>
			<input   id="<?php echo $this->get_field_id( 'city' ); ?>" name="<?php echo $this->get_field_name( 'city' ); ?>" 
			placeholder="enter your city"
			value="<?php echo $instance['city']; ?>" size="26"   />
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php if($instance['show_city'] == true) echo 'checked'; ?> 
			id="<?php echo $this->get_field_id( 'show_city' ); ?>" name="<?php echo $this->get_field_name( 'show_city' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'show_city' ); ?>">
			<?php _e('Display city name', 'show_city'); ?></label>
		</p>
				
		<p>
			<label for="<?php echo $this->get_field_id( 'temp' ); ?>"><?php _e('Temperature:', 'temp'); ?></label>
			<select id="<?php echo $this->get_field_id( 'temp' ); ?>" name="<?php echo $this->get_field_name( 'temp' ); ?>[select_value]" value="<?php echo $instance['temp']; ?>" >
      			<option value="c" <?php if ($instance['temp'] == 'c') echo 'selected'; ?>>Celsius</option>
      			<option value="f" <?php if ($instance['temp'] == 'f') echo 'selected'; ?>>Fahrenheit</option>
    		</select>			
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'color_scheme' ); ?>"><?php _e('Color scheme:', 'color_scheme'); ?></label>
			<select id="<?php echo $this->get_field_id( 'color_scheme' ); ?>" name="<?php echo $this->get_field_name( 'color_scheme' ); ?>[select_value]" value="<?php echo $instance['color_scheme']; ?>" >
      			<option value="blue-and-yellow" <?php if ($instance['color_scheme'] == 'blue-and-yellow') echo 'selected'; ?>>Blue and Yellow</option>
      			<option value="red" <?php if ($instance['color_scheme'] == 'red') echo 'selected'; ?>>Red</option>
				<option value="pink" <?php if ($instance['color_scheme'] == 'pink') echo 'selected'; ?>>Pink</option>
				<option value="green" <?php if ($instance['color_scheme'] == 'green') echo 'selected'; ?>>Green</option>
				<option value="grey" <?php if ($instance['color_scheme'] == 'grey') echo 'selected'; ?>>Grey</option>
    		</select>			
		</p>
						
		<p>
			<input class="checkbox" type="checkbox" <?php if($instance['forecast'] == true) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'forecast' ); ?>" name="<?php echo $this->get_field_name( 'forecast' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'forecast' ); ?>"><?php _e('Display forecast', 'forecast'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php if($instance['alignment'] == true) echo 'checked'; ?> id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e('Center widget', 'alignment'); ?></label>
		</p>
	
 
		
	<?php
	}

	/**
	        display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		#Our variables from the widget settings
		$title = apply_filters('widget_title', $instance['title'] );
		$api_key  = $instance['api_key'];
		$city = $instance['city'];
		$show_city = $instance['show_city'];
		$temp = $instance['temp'];
		$color_scheme = $instance['color_scheme'];
		$forecast = $instance['forecast'];
		$alignment = $instance['alignment'];


		#Before widget (defined by themes)
		echo $before_widget;

		#Display the widget title if one was input (before and after defined by themes)
		if ( $title )
			echo $before_title . $title . $after_title;
			
		#Display name from widget settings if one was input		
		$this->buildWidget($api_key, $city, $show_city, $temp, $color_scheme, $forecast, $alignment);
				
		#After widget (defined by themes)
		echo $after_widget;
	}
	
	function getData($api_key, $city, $temp, $forecast) 
	{
		
		$cleancity = $city;
		$stripped = $this->replaceChars($cleancity);
		$city = remove_accents($stripped);
		
 					 
		$xmlUrl = 'http://api.worldweatheronline.com/free/v1/weather.ashx?q='.$city.'&num_of_days=5&format=xml&key='.$api_key;


		 #load resource into a xmldom
		$xmlData  = simplexml_load_file($xmlUrl);   
		
		
		#define conditions array
		$conditions = array();
		
		#get current conditions
		$conditions['current']['condition'] = $xmlData->current_condition->weatherDesc;
		$conditions['current']['icon'] = $xmlData->current_condition->weatherIconUrl;
		if($temp == 'c') {
			$conditions['current']['temp'] = $xmlData->current_condition->temp_C.'&deg;C';	
		}	else if($temp == 'f') {
			$conditions['current']['temp'] = $xmlData->current_condition->temp_F.'&deg;F';	
		}
		
		#get forecast conditions
	for($i=0; $i<=3; $i++) {		
			$conditions['forecast'][$i]['day'] = date("M d", strtotime($xmlData->weather[$i]->date)) ;
			$conditions['forecast'][$i]['icon'] = $xmlData->weather[$i]->weatherIconUrl;
			$conditions['forecast'][$i]['high'] = $xmlData->weather[$i]->tempMaxC;
			$conditions['forecast'][$i]['low'] = $xmlData->weather[$i]->tempMinC;
			$conditions['forecast'][$i]['condition'] = $xmlData->weather[$i]->weatherDesc;
		}
	
		
	 #convert Fahrenheit to Celsius if needed
	//	if($unit_system=='US' AND ($temp=='c')) {
	//		for($i=0; $i<=3; $i++) {
	//			$conditions['forecast'][$i]['high'] = intval(($conditions['forecast'][$i]['high']-32)*5/9);
	//			$conditions['forecast'][$i]['low'] = intval(($conditions['forecast'][$i]['low']-32)*5/9);
	//		}
	//   }
		
		return $conditions;
		
	}
	
	function buildWidget($api_key, $city, $show_city, $temp, $color_scheme, $forecast, $alignment) {
		$conditions = $this->getData($api_key, $city, $temp, $forecast, $color_scheme);	
		
		if($alignment == true) {
			$alignmentclass = 'centered';
		}
		
 
	 
		echo '<div class="wp_wwo">
		<dl class="'.$alignmentclass.'  wp_wwo_block">';
	   
	   
	   if( $show_city == true )  echo '<div class="wp_wwo_city">'.$city.'</div> <br>';
	   
	   echo'
	  		<dd class="today">
				<span class="condition">'.$conditions['current']['condition'].'</span>
				<span class="temperature">'.$conditions['current']['temp'].'</span>
				<img  style="width:46px;height:41px;" src="'.$conditions['current']['icon'].'" alt="'.$conditions['current']['condition'].'" />
			</dd>
		';	
		
	echo '<link rel="stylesheet" type="text/css" media="screen" href="'. WP_PLUGIN_URL . '/wp-world-weather-online/wp-wwo-'. $color_scheme .'.css"/>';	
		
		if($forecast == true) {
			echo '
				<dd class="day1">
					<span class="day">'.$conditions['forecast'][0]['day'].'</span>
					<img src="'.$conditions['forecast'][0]['icon'].'" alt="'.$conditions['forecast'][0]['condition'].'" title="'.$conditions['forecast'][0]['condition'].'" /><br />
					<span class="temperature">'.$conditions['forecast'][0]['high'].'/'.$conditions['forecast'][0]['low'].'</span><br /> 
				</dd>
				<dd class="day2">
					<span class="day">'.$conditions['forecast'][1]['day'].'</span>
					<img src="'.$conditions['forecast'][1]['icon'].'" alt="'.$conditions['forecast'][1]['condition'].'" title="'.$conditions['forecast'][1]['condition'].'" /><br />
					<span class="temperature">'.$conditions['forecast'][1]['high'].'/'.$conditions['forecast'][1]['low'].'</span><br /> 
				</dd>
				<dd class="day3">
					<span class="day">'.$conditions['forecast'][2]['day'].'</span>
					<img src="'.$conditions['forecast'][2]['icon'].'" alt="'.$conditions['forecast'][2]['condition'].'" title="'.$conditions['forecast'][2]['condition'].'" /><br />
					<span class="temperature">'.$conditions['forecast'][2]['high'].'/'.$conditions['forecast'][2]['low'].'</span><br /> 
				</dd>
				
				';	
			}
		echo '
		</dl>
		</div>
		<div style="clear: both;"></div>
		';
	}
	
	function replaceChars($data){
		$search = array('Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', ' ');
		$replace = array('Ae', 'Oe', 'Ue', 'ae', 'oe', 'ue', '%20');
		$output = str_replace($search, $replace, $data);
		
		return $output;
	}
}


/**
     Add function to load weather shortcode.
 */

add_shortcode('wp_world_weather_online', 'wp_wwo_shortcode');

function wp_wwo_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
	     "api_key" => '',
		"city" => 'Kiev',
		"temperature" => 'c',
		"forecast" => 'true'
		), $atts)
	);
	$wp_wwo = new wp_wwo();
	ob_start();
	$wp_wwo->buildWidget($api_key, $city, $show_city, $temperature, $forecast, $alignment);
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}

?>