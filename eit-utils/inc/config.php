<?php

/**
 * Configuraciones transversales del plugin
 */

/***********************/
/** SCRIPTS Y ESTILOS **/
/***********************/

/**
 * Pantalla administración (backoffice)
 */
function eit_utils_admin_style() {

    wp_enqueue_script('eit-utils-admin-script', EIT_UTILS_JS . '/eit-utils-admin.js', array('jquery', 'wp-i18n'), '1.0.0', true);
    wp_localize_script('eit-utils-admin-script', 'utils_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('eit-utils-nonce'),
        'action' => 'eit_utils_delete'
        ));

    wp_enqueue_style('eit-utils-admin-styles',  EIT_UTILS_CSS .'/eit-utils-admin.css');

}
add_action('admin_enqueue_scripts', 'eit_utils_admin_style');
  
  
  
  
/**
 * Incluir scripts y estilos para las utilidades activadas
 */
function eit_countdown_enqueue_scripts() {

    $utils = get_option('eit_utils_settings');

    // Scripts de countdown
    if ( 'activated' === $utils['eit_utils_countdown_activation'] ) {

        wp_enqueue_script('eit-countdown-script', EIT_UTILS_JS . '/eit-countdown.js', array('jquery', 'wp-i18n'), '1.0.0', true);
        wp_localize_script('eit-countdown-script', 'countdown_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('countdown-nonce'),
            'action' => 'countdown'
        ));
        
        wp_enqueue_style('eit-countdown-style', EIT_UTILS_CSS . '/eit-countdown.css' , array(), '1.0.0');
        
    }

    // Scripts de newsletter
    if ( 'activated' === $utils['eit_utils_newsletter_activation'] ) {
        wp_enqueue_script('eit-vue','https://unpkg.com/vue@next', null, null, false);

        wp_enqueue_script('eit-newsletter-script', EIT_UTILS_JS . '/eit-newsletter.js', array('jquery', 'wp-i18n'), '1.0.0', true);
        wp_localize_script('eit-newsletter-script', 'newsletter_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('eit-newsletter-nonce'),
            'action' => 'eit_newsletter'
        ));
    
        wp_enqueue_style('eit-newsletter-style', EIT_UTILS_CSS . '/eit-newsletter.css' , array(), '1.0.0');
    }

}
add_action('wp_enqueue_scripts', 'eit_countdown_enqueue_scripts');




/*******************/
/** MENÚ OPCIONES **/
/*******************/

 // Renderiza la página de opciones principales del plugin, para activar y desactivar las utilidades
function eit_utils_options_page_general() {
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <img src=" <?php echo EIT_UTILS_ASSETS . '/escuela-it-logo.svg';?>">
        <form method="post" action="options.php">
            <?php
            settings_fields( 'eit_utils' );
            do_settings_sections( 'eit_utils_options_page' );
            submit_button(__('Save General Settings', 'eit-utils'));
            ?>
        </form>
    </div>
    <?php
}

// Registra las opciones del plugin
function eit_utils_register_settings() {

  register_setting('eit_utils', 'eit_utils_settings',  ['type' => 'array',]);

  add_settings_section( 
      'eit_utils_general_section',             // id
      __('General Settings', 'eit-utils'),     // title 
      'eit_utils_general_section_callback',    // callback
      'eit_utils_options_page'                 // page 
  );
  
  // controles Coundown
  add_settings_field( 
      'eit_utils_countdown_activation',
      __('Countdown Activation', 'eit-utils'),
      'eit_utils_countdown_activation_cb',
      'eit_utils_options_page',
      'eit_utils_general_section',
      array(
          'label_for' => 'eit_utils_countdown_activation',
      ) 
  );
  
  // controles Newsletter
  add_settings_field( 
      'eit_utils_newsletter_activation',
      __('Newsletter Activation', 'eit-utils'),
      'eit_utils_newsletter_activation_cb',
      'eit_utils_options_page',
      'eit_utils_general_section',
      array(
          'label_for' => 'eit_utils_newsletter_activation',
      ) 
  );
  
}
add_action( 'admin_init', 'eit_utils_register_settings' );

// Callback para la sección de configuración general
function eit_utils_general_section_callback() {
  _e('EIT Utilities General Settings', 'eit-utils');
}

// controles Countdown
function eit_utils_countdown_activation_cb($args) {
  $options = get_option('eit_utils_settings');
  ?>
      <select id="<?php echo esc_attr($args['label_for']); ?>" name="eit_utils_settings[<?php echo esc_attr($args['label_for']); ?>]">
          <option value="activated" <?php selected( esc_attr($options[$args['label_for']]) , 'activated' ); ?>><?php _e('Activated', 'eit-utils');?></option>
          <option value="deactivated" <?php selected( esc_attr($options[$args['label_for']]) , 'deactivated' ); ?>><?php _e('Deactivated', 'eit-utils');?></option>
      </select>
      <p class="description">
          <?php esc_html_e('Please activate/deactivate the banner location functionality.', 'eit-utils'); ?>
      </p>
  <?php
}

// controles Newsletter
function eit_utils_newsletter_activation_cb($args) {
  $options = get_option('eit_utils_settings');
  ?>
      <select id="<?php echo esc_attr($args['label_for']); ?>" name="eit_utils_settings[<?php echo esc_attr($args['label_for']); ?>]">
          <option value="activated" <?php selected( esc_attr($options[$args['label_for']]) , 'activated' ); ?>><?php _e('Activated', 'eit-utils');?></option>
          <option value="deactivated" <?php selected( esc_attr($options[$args['label_for']]) , 'deactivated' ); ?>><?php _e('Deactivated', 'eit-utils');?></option>
      </select>
      <p class="description">
          <?php esc_html_e('Please activate/deactivate the newsletter register functionality.', 'eit-utils'); ?>
      </p>
  <?php
}

// Creación de la página de opciones
add_action('admin_menu', 'eit_utils_menu_page');
function eit_utils_menu_page() {
    add_menu_page(
        'EIT Utils General',                // Título de la página
        'EIT Utils',                        // Título del menú
        'manage_options',                   // Capability
        'eit-utils',                        // Slug del menú
        'eit_utils_options_page_general',   // Renderizado de la página principal
        'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzEiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCAzMSA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTAgMFYyOEgxNUwzMSA0MFYwSDBaTTEyLjEgMjFIOC45VjdIMTIuMVYyMVpNMjQuNCAxMC4ySDIwLjhWMjFIMTcuNlYxMC4ySDE0VjdIMjQuNFYxMC4yWiIgZmlsbD0iYmxhY2siLz4KPC9zdmc+Cg==',         // Icono
        20                                  // Posición en el menú
    );

    add_submenu_page(
        'eit-utils',                          // Slug del menú padre
        __('Newsletter Options', 'eit-utils'),// Título de la página
        __('Newsletter', 'eit-utils'),        // Título del submenú
        'manage_options',                     // Capability
        'eit-utils-newsletter',               // Slug del submenú
        'eit_utils_options_page_newsletter'   // Función que imprime el contenido de la página del submenú
    );

    add_submenu_page(
        'eit-utils',          
        __('Countdown Options', 'eit-utils'),
        __('Countdown', 'eit-utils'),
        'manage_options',    
        'eit-utils-countdown',  
        'eit_utils_options_page_countdown'
    );
}
