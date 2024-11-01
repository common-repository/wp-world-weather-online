=== WP World Weather Online  ===
Plugin URI: tlonovoi@gmail.com
Contributors: Alex Kalinovski
Tags: world weather online, weather, forecast, api, widget
Requires at least: 2.8
Tested up to: 4.4.1


Displays a weather widget via widget or shortcode using the World Weather Online service API. * show today's weather
* show weather forecast for next 3 days
* choose between Celsius or Fahrenheit
* choose different color themes
* supports shortcodes for single pages or posts
* comes with predefined CSS style if themes are not enough for you
* valid XHTML output

== Description ==

WP World Weather Online displays a Weather Widget in your sidebar or on single pages/posts

= Features =

* show today's weather
* show weather forecast for next 3 days
* choose between Celsius or Fahrenheit
* choose different color themes
* supports shortcodes for single pages or posts
* comes with predefined CSS style if themes are not enough for you
* valid XHTML output

In this version some small bugs have been removed plus some functionality added.

== Credits ==

Copyright 2014 by Alex Kalinovski

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


== Installation ==
0. Get World Weather Online service API key here http://www.worldweatheronline.com/free-weather.aspx
1. Unzip and upload files the files to /wp-content/plugins/wp-world-weather-online/
2. Activate the plugin
3. Go to Themes > Widgets and drag WP World Weather Online widget to your sidebar
4. Specify title, city, API key, temperature as Celsius or Fahrenheit 
5. decide whether it should display only today's weather or also a 3 day forecast

To add the widget to posts and pages use the shortcode [wp_world_weather_online].
Example:  [wp_world_weather_online city="kiev" temperature="c" forecast="1" api_key="your api key"]
	
== Screenshots ==

1. Widget options 
2. Frontend view