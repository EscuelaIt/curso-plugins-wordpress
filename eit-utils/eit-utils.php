<?php
/**
 * Plugin Name: aEIT Utilidades
 * Plugin URI: https://escuela.it
 * Description: Genera una página de control para distintas utilidades
 * Version: 1.0
 * Author: Escuela IT
 * Author URI: https://escuela.it
 * Text Domain: eit-utils
 * Domain Path: /languages
*/



defined( 'ABSPATH' ) || exit;

// Función para cargar el archivo de traducción
function eit_utils_load_textdomain() {
    load_plugin_textdomain( 'eit-utils', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  }
add_action( 'plugins_loaded', 'eit_utils_load_textdomain' );


define( 'EIT_UTILS', plugin_dir_path( __FILE__ ) );

define( 'EIT_UTILS_ASSETS', plugins_url( '/assets', __FILE__ ) );
define( 'EIT_UTILS_CSS', plugins_url( '/css', __FILE__ ) );
define( 'EIT_UTILS_JS', plugins_url( '/js', __FILE__ ) );


include_once EIT_UTILS . '/inc/config.php';       // parámetros generales
include_once EIT_UTILS . '/inc/countdown.php';    // plugin countdown
include_once EIT_UTILS . '/inc/newsletter.php';   // plugin registro a newsletter






/*****************************/
/** ACCIONES ACTIVAR PLUGIN **/
/*****************************/

// Activación del plugin: crea una nueva tabla
function eit_utils_install() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'eitinfo';
  $charset_collate = $wpdb->get_charset_collate();

  $sql = "CREATE TABLE $table_name (
      eit_id mediumint(9) NOT NULL AUTO_INCREMENT,
      eit_name VARCHAR(255) NOT NULL,
      eit_email VARCHAR(255) NOT NULL,
      PRIMARY KEY  (eit_id)
  ) $charset_collate;";

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
}

register_activation_hook(__FILE__, 'eit_utils_install');



/***********************/
/** REST API ENDPOINT **/
/***********************/

// Registrar la ruta de la REST API
function eit_register_rest_route() {
    register_rest_route(
        'eit/v1', // Namespace
        '/info', // Ruta
        [
            'methods' => 'GET',
            'callback' => 'eit_get_info',
            'permission_callback' => '__return_true' // Todo el mundo puede hacer esta petición
        ]
    );
}
add_action('rest_api_init', 'eit_register_rest_route');


// Función que devuelve la información de la tabla eitinfo
function eit_get_info() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'eitinfo';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    return rest_ensure_response($results);
}



/******************************/
/** ACCIONES ELIMINAR PLUGIN **/
/******************************/

// Eliminación del plugin: elimina la tabla y los registros
function eit_utils_uninstall() {

  // Borra la tabla eitinfo
  global $wpdb;
  $table_name = $wpdb->prefix . 'eitinfo';
  $wpdb->query("DROP TABLE IF EXISTS $table_name");

  // Borra las opciones guardadas por el plugin
  // Opciones generales
  delete_option('eit_utils_settings');
  // Opciones específicas del banner
  delete_option('eit_banner_settings');
}
register_uninstall_hook(__FILE__, 'eit_utils_uninstall');



