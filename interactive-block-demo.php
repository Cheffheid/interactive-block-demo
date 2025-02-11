<?php
/**
 * Plugin Name:       Interactive Block Demo
 * Description:       Interactive Block Demo
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           2.0.0
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

if ( ! class_exists( Plugin::class ) && is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

if ( class_exists( Plugin::class ) ) {
	Plugin::get_instance( plugin_dir_path( __FILE__ ) )->init();
}
