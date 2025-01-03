<?php
/**
 * Plugin Name:       Interactive Block Demo
 * Description:       Interactive Block Demo
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           1.1.0
 * Author:            Jeffrey de Wit
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       interactive-block-demo
 *
 * @package InteractiveBlockDemo
 */

namespace Cheffism\InteractiveBlockDemo;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require plugin_dir_path( __FILE__ ) . 'functions.php';

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function block_init() {
	register_block_type_from_metadata( __DIR__ . '/build' );
}
add_action( 'init', __NAMESPACE__ . '\block_init' );
