<?php
/**
 * Plugin Name: Me Gusta Plugin
 * Plugin URI: https://tudominio.com/me-gusta-plugin
 * Description: Plugin que añade un botón "Me gusta" a los posts.
 * Version: 1.0.0
 * Author: Escuela IT
 * Author URI: https://escuela.it
 * License: GPLv2 or later
 * Text Domain: eit-me-gusta
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define( 'EIT_LIKE_PREFIX_ASSETS_URL', plugins_url( '/assets', __FILE__ ) );

// Función para cargar el archivo de traducción
function eit_me_gusta_load_textdomain() {
  load_plugin_textdomain( 'eit-me-gusta', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'eit_me_gusta_load_textdomain' );


// Incluir scripts y estilos necesarios
function me_gusta_enqueue_scripts() {
  wp_enqueue_script('eit-me-gusta-script', plugins_url('js/eit-me-gusta.js', __FILE__), array('jquery'), '1.0.0', true);
  
  wp_localize_script('eit-me-gusta-script', 'me_gusta_ajax_object', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('me-gusta-nonce'),
    'action' => 'me_gusta'
));

wp_enqueue_style('eit-me-gusta-style', plugins_url('css/eit-me-gusta.css', __FILE__) , array(), '1.0.0');


}
add_action('wp_enqueue_scripts', 'me_gusta_enqueue_scripts');



// Añadir botón "Me gusta" al final de los posts
function eit_me_gusta_button($content) {

  // si el usuario no está logueado, no muestra el botón
  if( ! is_user_logged_in() ) return $content;

  // si se trata de un post, se añade el botón de me gusta.
  if (is_single()) {
      $post_id = get_the_ID();
      $user_id = get_current_user_id();
      $liked_posts = get_user_meta($user_id, 'liked_posts', true);

      if (!$liked_posts || !is_array($liked_posts)) {
          $liked_posts = array();
      }

      $class = in_array($post_id, $liked_posts) ? 'like' : '';

      $like_button = '<button class="me-gusta-button ' . $class . '" data-post-id="' . esc_attr($post_id) . '" data-user-id="'. esc_attr($user_id) .'">';
      $like_button .= esc_html( __( 'Like', 'eit-me-gusta' ) ) . '</button>';

      $content .= '<div class="me-gusta-container">' . $like_button . '</div>';
  }

  return $content;
}
add_filter('the_content', 'eit_me_gusta_button');



// Manejar la acción de hacer clic en el botón "Me gusta" mediante AJAX
function me_gusta_ajax_handler() {

  $nonce = sanitize_text_field( $_POST['nonce'] );

	if ( ! wp_verify_nonce( $nonce, 'me-gusta-nonce' ) ) {
		die ( 'Pillado!');
	}

  // Obtener los datos de la solicitud AJAX
  $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

  $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

  // if( $post_id != 0 && $user_id != 0)
  // Obtener la información del usuario
  $liked_posts = get_user_meta($user_id, 'liked_posts', true);

  // Verificar si el post ya está marcado como "Me gusta" por el usuario
  $like_exist = in_array($post_id, $liked_posts);


  // Actualizar los posts marcados como "Me gusta" en el perfil del usuario
  if ($like_exist) {

    // Si el post ya estaba marcado, significa que el usuario lo ha desmarcado. 
    // Hay que quitarlo de la lista.
      
    $liked_posts = array_diff($liked_posts, array($post_id));
    update_user_meta($user_id, 'liked_posts', $liked_posts);

    $return = array(
      'message' => __( 'Post deleted from your favourites', 'eit-me-gusta' ),
      'ID'      => $post_id,
      'css'   => 'disliked'
    );

    wp_send_json_success( $return );

  } else {
    // Si el post no está en la lista de favoritos, significa que el usuari lo ha marcado como favorito. 
    // Hay que añadirlo a la lista.
        
    // si es el primero, se crea un nuevo array
    if($liked_posts == '') {
      $liked_posts = array($post_id);
    } else {
      array_push($liked_posts, $post_id);
    }

    $res = update_user_meta($user_id, 'liked_posts', $liked_posts);

    $return = array(
      'message' => __( 'Post added to your favourites', 'eit-me-gusta' ),
      'ID'      => $post_id,
      'css'   => 'liked'
    );

    wp_send_json_success( $return );
  }
}
add_action('wp_ajax_me_gusta', 'me_gusta_ajax_handler');
add_action('wp_ajax_nopriv_me_gusta', 'me_gusta_ajax_handler');




// Registro del gancho (hook) para ejecutar la función al desinstalar el plugin
// register_uninstall_hook( __FILE__, 'mostrar_aviso_desinstalacion' );
// register_deactivation_hook( __FILE__, 'mostrar_aviso_desinstalacion' );

// Función para mostrar el aviso de desinstalación y ejecutar acciones según la respuesta
function mostrar_aviso_desinstalacion() {
  // Obtener todos los usuarios registrados en el sitio
  $users = get_users();
  
  // Recorrer cada usuario y eliminar el campo 'liked_posts'
  foreach ( $users as $user ) {
      delete_user_meta($user->ID, 'liked_posts');
      delete_user_meta($user->ID, 'user_phone');
  }
}







/**
* Añadir campos personalizados a perfil de usuario
*/

// Agregamos los campos adicionales al formulario de registro
function eit_campos_user_form() {
$user_phone = ( isset( $_POST['user_phone'] ) ) ? $_POST['user_phone'] : '';?>

<p>
  <label for="user_phone"><?php  _e( 'Phone number', 'eit-me-gusta' ); ?><br />
  <input type="number" id="user_phone" name="user_phone" class="input" size="25" value="<?php echo esc_attr($user_phone);?>"></label>
</p>

<?php }
// add_action('register_form', 'eit_campos_user_form' );

// Validamos los campos adicionales
function eit_validacion_campos_user_form ($errors, $sanitized_user_login, $user_email) {
if ( empty( $_POST['user_town'] ) ) {
  // $errors->add( 'user_town_error', __('<strong>ERROR</strong>: Por favor, introduce tu Población') );
  $errors->add( 'user_town_error',  __( '<strong>ERROR</strong>: Please, add your phone number', 'eit-me-gusta' ) );
}

return $errors;
}
// add_filter('registration_errors', 'eit_validacion_campos_user_form', 10, 3);

// Guardamos los campos adicionales en base de datos
function eit_save_user_fields ($user_id) {

if ( isset($_POST['user_phone']) ){
  update_user_meta($user_id, 'user_phone', sanitize_text_field($_POST['user_phone']));
}
}
// add_action('user_register', 'eit_save_user_fields');


// Agregamos los campos adicionales a Tu Perfil y Editar Usuario
function eit_add_custom_user_fields( $user ) {
$user_phone = esc_attr( get_the_author_meta( 'user_phone', $user->ID ) );?>

<h3><?php _e( 'Extra fields', 'eit-me-gusta' );?></h3>

<table class="form-table">
  <tr>
    <th><label for="user_phone"><?php  _e( 'Phone number', 'eit-me-gusta' ); ?></label></th>
    <td><input type="text" name="user_phone" id="user_phone" class="regular-text" value="<?php echo $user_phone;?>" /></td>
  </tr>
</table>
<?php }
// add_action( 'show_user_profile', 'eit_add_custom_user_fields' );
// add_action( 'edit_user_profile', 'eit_add_custom_user_fields' );

// add_action( 'personal_options_update', 'eit_save_user_fields' );
// add_action( 'edit_user_profile_update', 'eit_save_user_fields' );