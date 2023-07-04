<?php
/**
 * Plugin Name: EIT Gutenberg Blocks Anime All
 * Plugin URI: https://github.com/WordPress/gutenberg-examples
 * Description: This is a demo plugin demonstrating how to register new blocks for the Gutenberg editor to render Anime.
 * Version: 1.1.0
 * Author: the Gutenberg Team
 *
 * @package gutenberg-examples
 */

defined( 'ABSPATH' ) || exit;

/**
 * Load all translations for our plugin from the MO file.
 */
function eit_gutenblocks_load_textdomain() {
	load_plugin_textdomain( 'eit-gutenblocks', false, basename( __DIR__ ) . '/languages' );
}
add_action( 'init', 'eit_gutenblocks_load_textdomain' );

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function eit_gutenblocks_register_all_block() {

	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
	}

	// __DIR__ is the current directory where block.json file is stored.
	register_block_type( __DIR__ . '/eit-anime-1' );
	register_block_type( __DIR__ . '/eit-anime-2' );
	register_block_type( __DIR__ . '/eit-anime-3' );
	register_block_type( __DIR__ . '/eit-anime-4' );
	register_block_type( __DIR__ . '/eit-anime-5' );

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'eit-anime', 'eit-gutenblocks', plugin_dir_path( __FILE__ ) . 'languages' );
	}

}
add_action( 'init', 'eit_gutenblocks_register_all_block' );