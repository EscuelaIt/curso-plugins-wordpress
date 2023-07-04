<?php
/*
Plugin Name: EIT Countdown Banner
Plugin URI: https://escuela.it
Description: Muestra un banner con una cuenta atrás de días, horas, minutos y segundos.
Version: 1.0
Author: EIT
Author URI: https://escuela.it
Text Domain: eit-countdown
Domain Path: /languages
*/



defined( 'ABSPATH' ) || exit;

// Función para cargar el archivo de traducción
function eit_countdown_load_textdomain() {
    load_plugin_textdomain( 'eit-countdown', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'eit_countdown_load_textdomain' );
  

// Incluir scripts y estilos necesarios
function eit_countdown_enqueue_scripts() {
  wp_enqueue_script('eit-countdown-script', plugins_url('js/eit-countdown.js', __FILE__), array('jquery', 'wp-i18n'), '1.0.0', true);
  
  wp_localize_script('eit-countdown-script', 'countdown_ajax_object', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('countdown-nonce'),
    'action' => 'countdown'
));

wp_enqueue_style('eit-countdown-style', plugins_url('css/eit-countdown.css', __FILE__) , array(), '1.0.0');


}
add_action('wp_enqueue_scripts', 'eit_countdown_enqueue_scripts');



/**
 * Obtener el tiempo inicial para pasarlo a la función de JavaScript para iniciar el timer
 */
function eit_countdown_ajax_handler(){

  $nonce = sanitize_text_field( $_POST['nonce'] );

  if ( ! wp_verify_nonce( $nonce, 'countdown-nonce' ) ) {
    die ( 'Pillado!');
  }

  // $days = get_option( 'countdown_banner_days', '7' );
  // $hours = get_option( 'countdown_banner_hours', '0' );
  // $minutes = get_option( 'countdown_banner_minutes', '0' );
  // $seconds = get_option( 'countdown_banner_seconds', '0' );

  $options = get_option('eit_banner_settings');

  $days = intval($options['eit_countdown_days']);
  $hours = intval($options['eit_countdown_hours']);
  $minutes = intval($options['eit_countdown_minutes']);
  $seconds = intval($options['eit_countdown_seconds']);

  // Cálculo del tiempo restante en segundos
  $remaining_time = ( $days * 24 * 60 * 60 ) + ( $hours * 60 * 60 ) + ( $minutes * 60 ) + $seconds;

  $return = array(
    'time' => $remaining_time,
    'ishidden' => $hidden
  );

  wp_send_json_success( $return );

}
add_action('wp_ajax_countdown', 'eit_countdown_ajax_handler');
add_action('wp_ajax_nopriv_countdown', 'eit_countdown_ajax_handler');





// Renderiza la página de opciones del plugin
function countdown_banner_options_page() {
  if (!current_user_can('manage_options')) {
      return;
  }

  if (isset($_GET['settings-updated'])) {
      add_settings_error('eit_banner_messages', 'eit_banner_message', __('Settings Saved', 'prefix'), 'updated');
  }
  ?>
  <div class="wrap">
      <h2><?php _e('Countdown Banner Options', 'eit-countdown');?></h2>
      <form method="post" action="options.php">
          <?php
          settings_fields( 'eit_countdown' );
          do_settings_sections( 'eit_countdown_options_page' );
          submit_button(__('Save Banner Settings', 'eit-countdown'));
          ?>
      </form>
  </div>
  <?php
}


// Registra las opciones del plugin
function eit_countdown_register_settings() {

  register_setting('eit_countdown', 'eit_banner_settings',  [
      'type'              => 'array',
      'sanitize_callback' => 'eit_countdown_sanitize_options',
  ]);

  add_settings_section( 
      'eit_countdown_general_section',             // id
      __('General Settings', 'eit-countdown'),        // title 
      'eit_countdown_general_section_callback',    // callback
      'eit_countdown_options_page'                 // page 
  );
  
  add_settings_field( 
      'eit_countdown_days',                // id
      __('Days', 'eit-countdown'),         // title
      'eit_countdown_days_cb',             // callback
      'eit_countdown_options_page',        // page
      'eit_countdown_general_section',     // section
      array(
          'label_for' => 'eit_countdown_days',
          'class' => 'eit_input',
      ) 
  );

  add_settings_field( 
    'eit_countdown_hours',
    __('Hours', 'eit-countdown'),
    'eit_countdown_hours_cb',
    'eit_countdown_options_page',
    'eit_countdown_general_section',
    array(
        'label_for' => 'eit_countdown_hours',
    ) 
);

add_settings_field( 
    'eit_countdown_minutes',
    __('Minutes', 'eit-countdown'),
    'eit_countdown_minutes_cb',
    'eit_countdown_options_page',
    'eit_countdown_general_section',
    array(
        'label_for' => 'eit_countdown_minutes',
    ) 
);

add_settings_field( 
    'eit_countdown_seconds',
    __('Seconds', 'eit-countdown'),
    'eit_countdown_seconds_cb',
    'eit_countdown_options_page',
    'eit_countdown_general_section',
    array(
        'label_for' => 'eit_countdown_seconds',
    ) 
);

add_settings_field( 
    'eit_countdown_bgcolor',
    __('Background Color', 'eit-countdown'),
    'eit_countdown_bgcolor_cb',
    'eit_countdown_options_page',
    'eit_countdown_general_section',
    array(
        'label_for' => 'eit_countdown_bgcolor',
    ) 
);

add_settings_field( 
    'eit_countdown_textcolor',
    __('Text Color', 'eit-countdown'),
    'eit_countdown_textcolor_cb',
    'eit_countdown_options_page',
    'eit_countdown_general_section',
    array(
        'label_for' => 'eit_countdown_textcolor',
    ) 
);

add_settings_field( 
    'eit_countdown_location',
    __('Banner Location', 'eit-countdown'),
    'eit_countdown_location_cb',
    'eit_countdown_options_page',
    'eit_countdown_general_section',
    array(
        'label_for' => 'eit_countdown_location',
    ) 
);

  

}
add_action( 'admin_init', 'eit_countdown_register_settings' );


// Callback para la sección de configuración general
function eit_countdown_general_section_callback() {
  _e('EIT Countdown Banner General Settings', 'eit-countdown');
}


function eit_countdown_days_cb( $args ) {
  $options = get_option('eit_banner_settings');
  ?>
      <input 
        class="<?php echo esc_attr($args['class']); ?>" 
        placeholder="7" 
        type="number" 
        id="<?php echo esc_attr($args['label_for']); ?>" 
        name="eit_banner_settings[<?php echo esc_attr($args['label_for']); ?>]" 
        value="<?php echo esc_attr($options[$args['label_for']]); ?>" 
        min="0">

      <p class="description">
          <?php esc_html_e('Please enter the number of days.', 'eit-countdown'); ?>
      </p>

  <?php
}


function eit_countdown_hours_cb($args) {
  $options = get_option('eit_banner_settings');
  ?>
      <input class="regular-text" placeholder="2" type="number" id="<?php echo esc_attr($args['label_for']); ?>" name="eit_banner_settings[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo esc_attr($options[$args['label_for']]); ?>" min="0" max="23">
      <p class="description">
          <?php esc_html_e('Please enter the number of hours.', 'eit-countdown'); ?>
      </p>
  <?php
}

function eit_countdown_minutes_cb($args) {
  $options = get_option('eit_banner_settings');
  ?>
      <input class="regular-text" placeholder="30" type="number" id="<?php echo esc_attr($args['label_for']); ?>" name="eit_banner_settings[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo esc_attr($options[$args['label_for']]); ?>" min="0" max="59">
      <p class="description">
          <?php esc_html_e('Please enter the number of minutes.', 'eit-countdown'); ?>
      </p>
  <?php
}

function eit_countdown_seconds_cb($args) {
  $options = get_option('eit_banner_settings');
  ?>
      <input class="regular-text" placeholder="30" type="number" id="<?php echo esc_attr($args['label_for']); ?>" name="eit_banner_settings[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo esc_attr($options[$args['label_for']]); ?>" min="0" max="59">
      <p class="description">
          <?php esc_html_e('Please enter the number of seconds.', 'eit-countdown'); ?>
      </p>
  <?php
}

function eit_countdown_bgcolor_cb($args) {
  $options = get_option('eit_banner_settings');
  ?>
      <input class="regular-text" placeholder="#000000" type="color" id="<?php echo esc_attr($args['label_for']); ?>" name="eit_banner_settings[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo esc_attr($options[$args['label_for']]); ?>">
      <p class="description">
          <?php esc_html_e('Please enter the background color.', 'eit-countdown'); ?>
      </p>
  <?php
}

function eit_countdown_textcolor_cb($args) {
  $options = get_option('eit_banner_settings');
  ?>
      <input class="regular-text" placeholder="#ffffff" type="color" id="<?php echo esc_attr($args['label_for']); ?>" name="eit_banner_settings[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo esc_attr($options[$args['label_for']]); ?>">
      <p class="description">
          <?php esc_html_e('Please enter the text color.', 'eit-countdown'); ?>
      </p>
  <?php
}

function eit_countdown_location_cb($args) {
  $options = get_option('eit_banner_settings');
  ?>
      <select id="<?php echo esc_attr($args['label_for']); ?>" name="eit_banner_settings[<?php echo esc_attr($args['label_for']); ?>]">
          <option value="header" <?php selected( esc_attr($options[$args['label_for']]) , 'header' ); ?>><?php _e('Header', 'eit-countdown');?></option>
          <option value="footer" <?php selected( esc_attr($options[$args['label_for']]) , 'footer' ); ?>><?php _e('Footer', 'eit-countdown');?></option>
      </select>
      <p class="description">
          <?php esc_html_e('Please select the banner location.', 'eit-countdown'); ?>
      </p>
  <?php
}




function eit_countdown_sanitize_options($data) {
    $old_options = get_option('eit_banner_settings');
    $has_errors = false;

    if ( empty($data['eit_countdown_days']) ) {
        add_settings_error('eit_banner_messages', 'eit_banner_messages', __('Days are required', 'eit-banner'), 'error');

        $has_errors = true;
    }

    if ($has_errors) {
        $data = $old_options;
    }

    return $data;
}


function eit_countdown_options_page() {
  add_menu_page(
      'EIT Banner',
      'EIT Banner Options',
      'manage_options',
      'eit_countdown',
      'countdown_banner_options_page'
 );

  //   add_options_page(
  //       'EIT Banner',
  //       'Banner Options',
  //       'manage_options',
  //       'eit_countdown',
  //       'countdown_banner_options_page'
  //  );

}
add_action('admin_menu', 'eit_countdown_options_page');




/*******************  Banner frontend */



// Función para mostrar el banner en el frontend
function countdown_banner_display() {

  // $days = get_option( 'countdown_banner_days', '7' );
  // $hours = get_option( 'countdown_banner_hours', '0' );
  // $minutes = get_option( 'countdown_banner_minutes', '0' );
  // $seconds = get_option( 'countdown_banner_seconds', '0' );
  // $bgcolor = get_option( 'countdown_banner_bgcolor', '0' );
  // $textcolor = get_option( 'countdown_banner_textcolor', '0' );

  $options = get_option('eit_banner_settings');

  $days = intval($options['eit_countdown_days']);
  $hours = intval($options['eit_countdown_hours']);
  $minutes = intval($options['eit_countdown_minutes']);
  $seconds = intval($options['eit_countdown_seconds']);
  $bgcolor = $options['eit_countdown_bgcolor'];
  $textcolor = $options['eit_countdown_textcolor'];


  $tdays = __("days", "eit-countdown");
  $thours = __("hours", "eit-countdown");
  $tminutes = __("minutes", "eit-countdown");
  $tseconds = __("seconds", "eit-countdown");
  $ttime = __("Remaining time: ", "eit-countdown");

  // Cálculo del tiempo restante en segundos
  $remaining_time = ( $days * 24 * 60 * 60 ) + ( $hours * 60 * 60 ) + ( $minutes * 60 ) + $seconds;

  // Verificar si el tiempo restante es mayor a cero
  if ( $remaining_time > 0 ) {
      // Código HTML para mostrar el banner con la cuenta regresiva
      echo '<div class="countdown-banner" style="background-color:' . $bgcolor . '; color:' . $textcolor .'">';
      echo '<span class="countdown-label">' . $ttime . '</span>';
      echo '<span class="countdown-timer">' . $days . ' ' . $tdays . ', ' . $hours . ' ' . $thours . ', ' . $minutes . ' ' . $tminutes . ', ' . $seconds . ' ' . $tseconds .'</span>';
      echo '</div>';
  }
}

// Hook para mostrar el banner en el header del frontend
function countdown_banner_display_header() {
  $options = get_option('eit_banner_settings');
  if ( $options['eit_countdown_location'] === 'header' ) {
      countdown_banner_display();
  }
}
add_action( 'wp_head', 'countdown_banner_display_header' );

// Hook para mostrar el banner en el footer del frontend
function countdown_banner_display_footer() {
  $options = get_option('eit_banner_settings');
  if ( $options['eit_countdown_location'] === 'footer' ) {
      countdown_banner_display();
  }
}
add_action( 'wp_footer', 'countdown_banner_display_footer' );



/** desinstalar plugin */



// Hook para ejecutar código cuando se desinstala el plugin
function countdown_banner_uninstall() {
  // Borra las opciones guardadas por el plugin
  delete_option('eit_banner_settings');

}
register_uninstall_hook( __FILE__, 'countdown_banner_uninstall' );
