<?php
/*
Plugin Name: Dark Mode
Description: Plugin para gestionar una paleta de colores para un tema claro y otro para un tema oscuro.
Version: 1.0
Author: EIT
Text Domain: eit-dark-mode
Domain Path: /languages
*/

defined( 'ABSPATH' ) or die( 'No puedes pasar' );

define( 'EIT_DARK_PREFIX_ASSETS_URL', plugins_url( '/assets', __FILE__ ) );

// Función para cargar el archivo de traducción
function eit_dark_mode_load_textdomain() {
  load_plugin_textdomain( 'eit-dark-mode', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'eit_dark_mode_load_textdomain' );


  /*
  plugins_url()                                       ==> http://itplugins.local/wp-content/plugins
  plugins_url('css/eit-dark-mode.css', __FILE__)      ==> http://itplugins.local/wp-content/plugins/eit-dark-mode/css/eit-dark-mode.css

  plugin_dir_url()                                    ==> ERROR
  plugin_dir_url(__FILE__)                            ==> http://itplugins.local/wp-content/plugins/eit-dark-mode/
  plugin_dir_url(__FILE__) . 'css/eit-dark-mode.css'  ==>  http://itplugins.local/wp-content/plugins/eit-dark-mode/css/eit-dark-mode.css



  wp_register_script  ==> hace que el scrip esté disponible para su uso
  wp_enqueue_script   ==> carga el script previamente registrado, o lo registra automáticamente y lo carga
  wp_localize_script  ==> se usa para pasar datos entre servidor (PHP) y cliente (JS). Previsamente debe estar registrado con register o enqueue
  */


function eit_dark_mode_enqueue_scripts() {

  /**
   * Script para actualizar el color del body, llamado en el header
   */
  // registrar
  wp_register_script( 
    'eit-dark-mode-check', 
    plugins_url( '/js/eit-dark-mode-check.js', __FILE__ ), 
    array(),
    '1.0',
    true
  );
  
  // cargar
  // wp_enqueue_script('eit-dark-mode-check');


  // registrar y cargar
  wp_enqueue_script(
    'eit-dark-mode',
    plugin_dir_url( __FILE__ ) . 'js/eit-dark-mode.js',
    array( 'wp-i18n' ),
    '1.0',
    true
  );

  wp_set_script_translations( 
    'eit-dark-mode', 
    'eit-dark-mode', 
    plugin_dir_path( __FILE__ ) . 'languages' 
  );


  // registrar y cargar los estilos   
  wp_enqueue_style('eit-dark-mode-style', plugins_url('css/eit-dark-mode.css', __FILE__));  

}
add_action( 'wp_enqueue_scripts', 'eit_dark_mode_enqueue_scripts' );



// Función para generar el código del radio button
function eit_dark_mode_render_radiobutton() {

  $light_check = esc_html( __( 'Light', 'eit-dark-mode' ) ); 
  $dark_check = esc_html( __('Dark', 'eit-dark-mode') ); 
  $default_check = esc_html( __('System', 'eit-dark-mode') ); 

  // $out = '<form class="eit_radios" method="post" action="">';
  // $out.= '<label for="eit_radio_light">' . $light_check . '</label><input id="eit_radio_light" type="radio" name="eit_dark_mode" value="eit-light" >';
  // $out.= '<label for="eit_radio_dark">' . $dark_check . '</label><input id="eit_radio_dark" type="radio" name="eit_dark_mode" value="eit-dark" >';
  // $out.= '<label for="eit_radio_default">' . $default_check . '</label><input id="eit_radio_default" type="radio" name="eit_dark_mode" value="eit-system" >';
  // $out.= '</form>';


  /**
   * aplicar filtros
   */
  $out.= '<form class="eit_radios" method="post" action="">';
  $out.= '<label for="eit_radio_light">' . apply_filters( 'eit_light_icon', $light_check ) . '</label><input id="eit_radio_light" type="radio" name="eit_dark_mode" value="eit-light" >';
  $out.= '<label for="eit_radio_dark">' . apply_filters( 'eit_dark_icon', $dark_check ) . '</label><input id="eit_radio_dark" type="radio" name="eit_dark_mode" value="eit-dark" >';
  $out.= '<label for="eit_radio_default">' . apply_filters( 'eit_default_icon', $default_check ) . '</label><input id="eit_radio_default" type="radio" name="eit_dark_mode" value="eit-system" >';
  $out.= '</form>';


  return $out;
}


function eit_dark_mode_add_radiobutton_to_menu( $items, $args ) {

  if ( $args->theme_location === 'primary' ) {

      $radio_button .= '<li id="eit-dark-mode-menu-item" class="menu-item menu-item-type-custom menu-item-object-custom" >' . eit_dark_mode_render_radiobutton() . '</li>';

      $items .= $radio_button;
  }

  return $items;
}
add_filter( 'wp_nav_menu_items', 'eit_dark_mode_add_radiobutton_to_menu', 10, 2 );



/**
 * Comprobar estilo del body justo después de <body>
 */

 function custom_body_scripts() {
  ?>
  <script>
    const theme = localStorage.getItem('eitTheme')
    if ( theme )  document.querySelector('body').classList.add( theme );
  </script>
  <?php
}
 add_action( 'wp_body_open', 'custom_body_scripts', 10 );