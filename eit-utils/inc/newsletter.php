<?php

/**
 * Lógica de la Newsletter
 */


/*************************************/
/** PÁGINA DE CONTROL EN BACKOFFICE **/
/*************************************/


// Contenido de la página de opciones con pestañas
function eit_utils_options_page_newsletter() {
  if (!current_user_can('manage_options')) {
      return;
  }

  ?>
  <div class="wrap">
      <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
      
      <h2 class="nav-tab-wrapper">
          <a href="#" class="nav-tab nav-tab-active" id="tab1"><?php esc_html_e('View','eit-utils');?></a>
          <a href="#" class="nav-tab" id="tab2"><?php esc_html_e('Control','eit-utils');?></a>
      </h2>

      <div id="tab1-content" class="tab-content">
          <?php echo do_shortcode('[eit_newsletter_list]');?>
      </div>

      <div id="tab2-content" class="tab-content" style="display: none;">
          <?php echo do_shortcode('[eit_info_admin_table]');?>
      </div>
  </div>
  <?php
}




/********************************************/
/** SHORTCODES PARA MOSTRAR LA INFORMACIÓN **/
/********************************************/


/**
 * Shortcode para mostrar la tabla sólo con los datos
 */
function eit_utils_newsletter_shortcode_public() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'eitinfo';
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    if ( count( $results) > 0 ) {

        $output = '<table class="eit-utils-newsletter-table">';
        foreach ($results as $row) {
            $output .= '<tr>';
            $output .= '<td>' . esc_html($row->eit_name) . '</td>';
            $output .= '<td>' . esc_html($row->eit_email) . '</td>';
            $output .= '</tr>';
        }
        $output .= '</table>';
    
    } else {
        $output = '<p>' . esc_html__('There are no registered users yet','eit-utils') . '</p>';
    }

    return $output;

}
add_shortcode('eit_newsletter_list', 'eit_utils_newsletter_shortcode_public');


/**
 * Shortcode para mostrar la tabla sólo con funcionalidad de eliminar
 */
function eit_utils_newsletter_shortcode_admin() {

    // Obtener los datos desde el endpoint de la REST API
    $response = wp_remote_get( get_rest_url(null, '/eit/v1/info') );
    $data = json_decode( wp_remote_retrieve_body( $response ) );

    
    ob_start();

    if ( count( $data) > 0 ) {
    ?>
    <section id="eitInfoTable" class="eit-table-container">
        <table class="eit-utils-newsletter-table">
        <tr>
            <th><?php esc_html_e('Action','eit-utils');?></th>
            <th><?php esc_html_e('Name','eit-utils');?></th>
            <th><?php esc_html_e('Email','eit-utils');?></th>
        </tr>
        <?php
        foreach ($data as $row) { ?>
            <tr>
                <td><input type="checkbox" class="eit-row-selector" value="<?php esc_attr_e($row->eit_id);?>"></td>
                <td><?php esc_html_e($row->eit_name);?></td>
                <td><?php esc_html_e($row->eit_email);?></td>
            </tr>
        <?php
        }
        ?>
        </table>
        <div class="eit-table-controls">
            <button id="eitSelectAll"><?php esc_html_e('Check all','eit-utils');?></button>
            <button id="eitDeselectAll"><?php esc_html_e('Uncheck all','eit-utils');?></button>
            <button id="eitDeleteSelected"><?php esc_html_e('Delete selected','eit-utils');?></button>
        </div>
    </section>
    <?php
    } else { ?>
        <p><?php esc_html_e('There are no registered users yet','eit-utils');?></p>
    <?php
    }

    echo ob_get_clean();
}
add_shortcode('eit_info_admin_table', 'eit_utils_newsletter_shortcode_admin');

/**
 * Llamada por Ajax desde la tabla del backoffice para eliminar los registros de la tabla 'eitinfo' de la base de datos
 */
function eit_delete_info() {
    global $wpdb;

    $nonce = sanitize_text_field( $_POST['nonce'] );

	if ( ! wp_verify_nonce( $nonce, 'eit-utils-nonce' ) ) {
		die ( 'Pillado!');
	}

    if(isset($_POST['ids'])) {
        $ids = $_POST['ids'];
        $integerIDs = array_map( 'intval', explode( ',', $ids ) );
        $table_name = $wpdb->prefix . 'eitinfo';
        $result = 'ok';

        foreach($integerIDs as $id) {
            $res = $wpdb->delete($table_name, ['eit_id' => $id]);
            if ( false == $res) $result = 'error';
        }

        if ( $resul == 'ok' ){
            wp_send_json_success( $result );
        } else{
            wp_send_json_error( $result );
        }

    } else {
        wp_send_json_error();
    }

    wp_die(); // Terminar la ejecución del script
}
add_action('wp_ajax_eit_utils_delete', 'eit_delete_info');




/*******************************************/
/** MOSTRAR EL FORMULARIO EN EL FRONT-END **/
/*******************************************/

/**
 * Función para renderizar en formulario en el footer
 */
function eit_footer_form() {

    $utils = get_option('eit_utils_settings');
    if ( 'activated' !== $utils['eit_utils_newsletter_activation'] ) {
        return;
    }
        
    ?>
    <div id="eitForm">
        <h4><?php esc_html_e('Join our Newsletter','eit-utils');?></h4>
        <form id="eit_newsletter_form" action="" method="post">
            <label for="form_name"><?php esc_html_e('Name:','eit-utils');?></label>
            <input id="form_name" type="text" name="eit_name" placeholder="<?php esc_attr_e('Your name','eit-utils');?>">
            <label for="form_email"><?php esc_html_e('Email:','eit-utils');?></label>
            <input id="form_email" type="email" name="eit_email" placeholder="<?php esc_attr_e('Your email','eit-utils');?>">
            <input type="submit" name="eit_form_submit" value="<?php esc_attr_e('Join','eit-utils');?>">
        </form>
    </div>
    <?php
}
add_action('wp_footer', 'eit_footer_form');



/**
 * Llamada por Ajax desde el formulario para guardar los registros en la tabla 'eitinfo' de la base de datos
 */
function eit_save_form_newsletter() {

    $nonce = sanitize_text_field( $_POST['nonce'] );

	if ( ! wp_verify_nonce( $nonce, 'eit-newsletter-nonce' ) ) {
		die ( 'Pillado!');
	}

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_text_field($_POST['email']);

    global $wpdb;
    $table_name = $wpdb->prefix . 'eitinfo';

    $result = $wpdb->insert(
        $table_name,
        array('eit_name' => $name, 'eit_email' => $email),
        array('%s', '%s')
    );

    if ($result) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_eit_newsletter', 'eit_save_form_newsletter');
add_action('wp_ajax_nopriv_eit_newsletter', 'eit_save_form_newsletter');
