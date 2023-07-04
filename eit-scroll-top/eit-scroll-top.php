<?php
/*
Plugin Name: Escuela IT Scroll Top
Description: Añade un botón flotante en el footer para desplazarse hacia arriba de la página.
Version: 1.0
Author: Escuela IT
Author URI: https://escuela.it
License: GPLv2 or later
*/



// Basic security, prevents file from being loaded directly.
defined( 'ABSPATH' ) or die( 'No puedes pasar!' );



define( 'PREFIX_ASSETS_URL', plugins_url( '/assets', __FILE__ ) );


// Enqueue archivos CSS y JS del plugin
function eit_scroll_top_enqueue_scripts() {
  // CSS
  wp_enqueue_style('eit-scroll-top-style', plugins_url('css/eit-scroll-top.css', __FILE__));
  
  // JS
  wp_enqueue_script('eit-scroll-top-script', plugins_url('js/eit-scroll-top.js', __FILE__), array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'eit_scroll_top_enqueue_scripts');




// Agrega el botón flotante en el footer
if( ! function_exists('eit_scroll_top_button')) {
  function eit_scroll_top_button() {
    echo '<a href="#" id="eit-scroll-top" class="eit-scroll-top" title="Go top"><img src="'.PREFIX_ASSETS_URL.'/arrow-up.svg"></a>';
  }
}

add_action('wp_footer', 'eit_scroll_top_button');