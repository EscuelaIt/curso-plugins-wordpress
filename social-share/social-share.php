<?php
/*
Plugin Name: Plugin de Redes Sociales
Plugin URI: http://tudominio.com
Description: Plugin para generar etiquetas meta y enlaces de redes sociales.
Version: 1.0
Author: Tu Nombre
Author URI: http://tudominio.com
*/


// Basic security, prevents file from being loaded directly.
defined( 'ABSPATH' ) or die( 'No puedes pasar' );

define( 'EIT_SOCIAL_ASSETS_URL', plugins_url( '/assets', __FILE__ ) );

// Agregar etiquetas meta para las redes sociales
function agregar_etiquetas_meta_redes_sociales() {

    ob_start();

    $default_img = EIT_SOCIAL_ASSETS_URL . '/eit-logo.jpeg';

    // if (is_singular()) {
    if (is_single()) {

        $post = get_queried_object();
        $titulo = get_the_title($post->ID);
        // $imagen_destacada = get_the_post_thumbnail_url($post->ID, 'thumbnail');
        // $imagen_destacada = get_the_post_thumbnail_url($post->ID, 'social-thumb');


        $imagen_destacada = ( get_the_post_thumbnail_url($post->ID, 'social-thumb') ) ? get_the_post_thumbnail_url($post->ID, 'social-thumb') : $default_img;

        $url = get_permalink();
        $excerpt = get_the_excerpt($post->ID);
        $cut = wp_trim_words($excerpt, 15) . '...';
        $desc = $cut;
        $desc = $excerpt;
        ?>


    <meta name="title" property="og:title" content=" <?php esc_attr_e($titulo) ?>" />
    <meta name="type" property="og:type" content="website" />
    <meta name="image" property="og:image" content=" <?php esc_attr_e($imagen_destacada) ?>" />
    <meta name="url" property="og:url" content=" <?php esc_attr_e($url) ?>" />
    <meta name="description" property="og:description" content=" <?php esc_attr_e($desc) ?>" />

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php esc_attr_e($titulo);?>" />
    <meta name="twitter:image" content="<?php esc_attr_e($imagen_destacada);?>">
    <meta name="twitter:site" content="@EscuelaIT">
    <meta name="twitter:description" content="<?php esc_attr_e($desc);?>">
    
    <?php

    } else {

        $titulo = get_bloginfo( 'name' );
        $descripcion = get_bloginfo( 'description' );
        $url = get_bloginfo( 'url' );
        ?>
    
        <meta name="title" property="og:title" content="<?php esc_attr_e($titulo); ?>" />
        <meta name="type" property="og:type" content="website" />
        <meta name="image" property="og:image" content="<?php esc_attr_e($default_img); ?>" />
        <meta name="url" property="og:url" content="<?php esc_attr_e($url); ?>" />
        <meta name="description" property="og:description" content="<?php esc_attr_e($descripcion); ?>" />

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php esc_attr_e($titulo); ?>" />
        <meta name="twitter:image" content="<?php esc_attr_e($imagen_destacada); ?>"> 
        <meta name="twitter:site" content="@EscuelaIT">
        <meta name="twitter:description" content="<?php esc_attr_e($descripcion); ?>"> 

        <?php
    }

	echo ob_get_clean();

    
}
add_action('wp_head', 'agregar_etiquetas_meta_redes_sociales');

// Generar shortcode para compartir en redes sociales
function generar_shortcode_redes_sociales($atts) {
    $atts = shortcode_atts(array(
        'fb' => 1,
        'tw' => 1,
        'in' => 1
    ), $atts, 'redes_sociales');

    $html = '';

    if ($atts['fb'] == 1) {
        $html .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . get_permalink() . '" target="_blank"><img src="' . EIT_SOCIAL_ASSETS_URL . '/fb.svg" alt="facebook" width="24" height="24"></a>';
    }

    if ($atts['tw'] == 1) {
        $html .= '<a href="https://twitter.com/intent/tweet?url=' . get_permalink() . '&text=' . urlencode("Mira qué contenido tan interesante he encontrado:") . '"  target="_blank"><img src="' . EIT_SOCIAL_ASSETS_URL . '/tw.svg" alt="twitter" width="24" height="24"></a>';
    }

    if ($atts['in'] == 1) {
        $html .= '<a href="https://www.linkedin.com/shareArticle?url=' . get_permalink() . '&title=' . urlencode(get_the_title()) . '&summary=' . urlencode(get_the_excerpt()) . '&source=" target="_blank"><img src="' . EIT_SOCIAL_ASSETS_URL . '/in.svg" alt="linkedin" width="24" height="24"></a>';
    }

    return $html;
}
add_shortcode('redes_sociales', 'generar_shortcode_redes_sociales');




function wpdocs_theme_setup() {
	add_image_size( 'social-thumb', 120, 120, true ); // (cropped)
}
add_action( 'after_setup_theme', 'wpdocs_theme_setup' );



function caption_shortcode( $atts, $content = null ) {
    $atts = shortcode_atts(array(
        'color' => '',
    ), $atts );

	return '<span style="color:'.esc_attr($atts['color']).'">' . $content . '</span>';
}
add_shortcode( 'texto_color', 'caption_shortcode' );





