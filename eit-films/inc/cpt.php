<?php

function cptui_register_my_cpts() {

/**
 * Post Type: Films.
 */

$labels = [
  "name" => esc_html__( "Films", "eit-films" ),
  "singular_name" => esc_html__( "Film", "eit-films" ),
  "menu_name" => esc_html__( "Mis Films", "eit-films" ),
  "all_items" => esc_html__( "Todos los Films", "eit-films" ),
  "add_new" => esc_html__( "Añadir nuevo", "eit-films" ),
  "add_new_item" => esc_html__( "Añadir nuevo Film", "eit-films" ),
  "edit_item" => esc_html__( "Editar Film", "eit-films" ),
  "new_item" => esc_html__( "Nuevo Film", "eit-films" ),
  "view_item" => esc_html__( "Ver Film", "eit-films" ),
  "view_items" => esc_html__( "Ver Films", "eit-films" ),
  "search_items" => esc_html__( "Buscar Films", "eit-films" ),
  "not_found" => esc_html__( "No se ha encontrado Films", "eit-films" ),
  "not_found_in_trash" => esc_html__( "No se han encontrado Films en la papelera", "eit-films" ),
  "parent" => esc_html__( "Film superior", "eit-films" ),
  "featured_image" => esc_html__( "Imagen destacada para Film", "eit-films" ),
  "set_featured_image" => esc_html__( "Establece una imagen destacada para Film", "eit-films" ),
  "remove_featured_image" => esc_html__( "Eliminar la imagen destacada de Film", "eit-films" ),
  "use_featured_image" => esc_html__( "Usar como imagen destacada de Film", "eit-films" ),
  "archives" => esc_html__( "Archivos de Film", "eit-films" ),
  "insert_into_item" => esc_html__( "Insertar en Film", "eit-films" ),
  "uploaded_to_this_item" => esc_html__( "Subir a Film", "eit-films" ),
  "filter_items_list" => esc_html__( "Filtrar la lista de Films", "eit-films" ),
  "items_list_navigation" => esc_html__( "Navegación de la lista de Films", "eit-films" ),
  "items_list" => esc_html__( "Lista de Films", "eit-films" ),
  "attributes" => esc_html__( "Atributos de Films", "eit-films" ),
  "name_admin_bar" => esc_html__( "Film", "eit-films" ),
  "item_published" => esc_html__( "Film publicado", "eit-films" ),
  "item_published_privately" => esc_html__( "Film publicado como privado.", "eit-films" ),
  "item_reverted_to_draft" => esc_html__( "Film devuelto a borrador.", "eit-films" ),
  "item_scheduled" => esc_html__( "Film programado", "eit-films" ),
  "item_updated" => esc_html__( "Film actualizado.", "eit-films" ),
  "parent_item_colon" => esc_html__( "Film superior", "eit-films" ),
];

$args = [
  "label" => esc_html__( "Films", "eit-films" ),
  "labels" => $labels,
  "description" => "",
  "public" => true,
  "publicly_queryable" => true,
  "show_ui" => true,
  "show_in_rest" => true,
  "rest_base" => "",
  "rest_controller_class" => "WP_REST_Posts_Controller",
  "rest_namespace" => "wp/v2",
  "has_archive" => true,
  "show_in_menu" => true,
  "show_in_nav_menus" => true,
  "delete_with_user" => false,
  "exclude_from_search" => false,
  "capability_type" => "post",
  "map_meta_cap" => true,
  "hierarchical" => false,
  "can_export" => false,
  "rewrite" => [ "slug" => "film", "with_front" => true ],
  "query_var" => true,
  "menu_icon" => "dashicons-carrot",
  "supports" => [ "title", "editor", "thumbnail" ],
  "show_in_graphql" => false,
];

register_post_type( "film", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );



function cptui_register_my_taxes() {

	/**
	 * Taxonomy: Géneros.
	 */

	$labels = [
		"name" => esc_html__( "Géneros", "twentytwentyonechild" ),
		"singular_name" => esc_html__( "Género", "twentytwentyonechild" ),
		"menu_name" => esc_html__( "Géneros", "twentytwentyonechild" ),
		"all_items" => esc_html__( "Todos los Géneros", "twentytwentyonechild" ),
		"edit_item" => esc_html__( "Editar Género", "twentytwentyonechild" ),
		"view_item" => esc_html__( "Ver Género", "twentytwentyonechild" ),
		"update_item" => esc_html__( "Actualizar el nombre de Género", "twentytwentyonechild" ),
		"add_new_item" => esc_html__( "Añadir nuevo Género", "twentytwentyonechild" ),
		"new_item_name" => esc_html__( "Nombre del nuevo Género", "twentytwentyonechild" ),
		"parent_item" => esc_html__( "Género superior", "twentytwentyonechild" ),
		"parent_item_colon" => esc_html__( "Género superior", "twentytwentyonechild" ),
		"search_items" => esc_html__( "Buscar Géneros", "twentytwentyonechild" ),
		"popular_items" => esc_html__( "Géneros populares", "twentytwentyonechild" ),
		"separate_items_with_commas" => esc_html__( "Separar Géneros con comas", "twentytwentyonechild" ),
		"add_or_remove_items" => esc_html__( "Añadir o eliminar Géneros", "twentytwentyonechild" ),
		"choose_from_most_used" => esc_html__( "Escoger entre los Géneros más usandos", "twentytwentyonechild" ),
		"not_found" => esc_html__( "No se ha encontrado Géneros", "twentytwentyonechild" ),
		"no_terms" => esc_html__( "Ningún Géneros", "twentytwentyonechild" ),
		"items_list_navigation" => esc_html__( "Navegación de la lista de Géneros", "twentytwentyonechild" ),
		"items_list" => esc_html__( "Lista de Géneros", "twentytwentyonechild" ),
		"back_to_items" => esc_html__( "Volver a Géneros", "twentytwentyonechild" ),
		"name_field_description" => esc_html__( "El nombre es cómo aparecerá en tu sitio.", "twentytwentyonechild" ),
		"parent_field_description" => esc_html__( "Asigna un término superior para crear una jerarquía. El término jazz, por ejemplo, sería el superior de bebop y big band.", "twentytwentyonechild" ),
		"slug_field_description" => esc_html__( "El «slug» es la versión apta para URLs del nombre. Suele estar en minúsculas y sólo contiene letras, números y guiones.", "twentytwentyonechild" ),
		"desc_field_description" => esc_html__( "La descripción no suele mostrarse por defecto, pero puede que algunos temas la muestren.", "twentytwentyonechild" ),
	];

	
	$args = [
		"label" => esc_html__( "Géneros", "twentytwentyonechild" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'genero', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"show_tagcloud" => false,
		"rest_base" => "genero",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"rest_namespace" => "wp/v2",
		"show_in_quick_edit" => false,
		"sort" => false,
		"show_in_graphql" => false,
	];
	register_taxonomy( "genero", [ "film" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes' );
