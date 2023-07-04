<?php
/*
Plugin Name: EIT Films Directory
Plugin URI: https://tudominio.com
Description: Use a shortcode to show a directory of films
Version: 1.0
Author: EIT
Author URI: https://escuela.it
Text Domain: eit-films
Domain Path: /languages
*/



defined( 'ABSPATH' ) || exit;

// Función para cargar el archivo de traducción
function eit_films_load_textdomain() {
  load_plugin_textdomain( 'eit-films', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'eit_films_load_textdomain' );

define( 'EIT_FILM_PATH', plugin_dir_path( __FILE__ ) );

function eit_films_enqueue_scripts() {
  // registrar y cargar los estilos   
  wp_enqueue_style('eit-dark-mode-style', plugins_url('css/eit-films.css', __FILE__));  
}
add_action( 'wp_enqueue_scripts', 'eit_films_enqueue_scripts' );

include_once EIT_FILM_PATH . '/inc/acf.php';
include_once EIT_FILM_PATH . '/inc/cpt.php';
include_once EIT_FILM_PATH . '/inc/shortcode.php';





// require_once(__DIR__ . '/includes/advanced-custom-fields/acf.php');
require_once( EIT_FILM_PATH . '/includes/acf/acf.php');

add_filter('acf/settings/show_admin', 'my_acf_show_admin');

function my_acf_show_admin( $show ) {

  // return current_user_can('manage_options');
  return current_user_can('administrator');

}




function eit_films_image_setup() {
	add_image_size( 'eit-films', 300, 400, true ); // (cropped)
}
add_action( 'after_setup_theme', 'eit_films_image_setup' );




// // Hook para cuando se desinstala el plugin
// register_uninstall_hook( __FILE__, 'eliminar_registros_films' );

// // Función para eliminar los registros del custom post type
// function eliminar_registros_films() {
//     global $wpdb;

//     // Eliminar los posts del custom post type "films"
//     $wpdb->query( "DELETE FROM $wpdb->posts WHERE post_type = 'films'" );

//     // Eliminar los metadatos asociados a los posts de "films"
//     $wpdb->query( "DELETE FROM $wpdb->postmeta WHERE post_id IN (SELECT ID FROM $wpdb->posts WHERE post_type = 'films')" );

//     // Eliminar los términos de taxonomía asociados a los posts de "films"
//     $wpdb->query( "DELETE FROM $wpdb->term_relationships WHERE object_id IN (SELECT ID FROM $wpdb->posts WHERE post_type = 'films')" );

//     // Vaciar la caché de términos de taxonomía
//     $wpdb->query( "DELETE FROM $wpdb->term_taxonomy WHERE term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM $wpdb->term_relationships)" );

//     // Vaciar la caché de términos
//     $wpdb->query( "DELETE FROM $wpdb->terms WHERE term_id NOT IN (SELECT term_id FROM $wpdb->term_taxonomy)" );
// }

  

