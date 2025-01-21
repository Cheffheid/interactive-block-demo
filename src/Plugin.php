<?php
/**
 * Plugin class file.
 *
 * @package InteractiveBlockDemo
 */

namespace Cheffism\InteractiveBlockDemo;

use Cheffism\InteractiveBlockDemo\Search;

/**
 * Class for plugin specific functionality and WordPress hooks.
 */
final class Plugin {

	/**
	 * Plugin root folder name.
	 *
	 * @var string
	 */
	private string $basename;

	/**
	 * Instance variable.
	 *
	 * @var null|self
	 */
	private static $instance = null;

	/**
	 * Private clone method to prevent cloning of the instance of this class.
	 *
	 * @return void
	 */
	private function __clone() {}

	/**
	 * Returns the singleton instance of this Plugin class.
	 *
	 * @param string $basename Plugin root folder name.
	 * @return self
	 */
	public static function get_instance( $basename = '' ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $basename );
		}

		return self::$instance;
	}

	/**
	 * Set the basename variable on initialization.
	 *
	 * @param string $basename Plugin directory path string.
	 */
	private function __construct( $basename = '' ) {
		$this->basename = $basename;
	}

	/**
	 * Run the plugin's hooks and things.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_wpa11y_manually' ) );

		Search::get_instance()->init();
	}

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 * Behind the scenes, it registers also all assets so they can be enqueued
	 * through the block editor in the corresponding context.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	public function register_block(): void {
		register_block_type_from_metadata( $this->basename . '/build/block' );
	}

	/**
	 * Interestingly, wp-a11y is not enqueued or recognized as a requirement of our view.js script module and needs to be enqueued manually.
	 * It would be more ideal if this is done when the block is actually on the page,
	 * but this is a tiny script that likely won't harm much in the vast majority of cases.
	 *
	 * @return void
	 */
	public function enqueue_wpa11y_manually(): void {
		wp_enqueue_script( 'wp-a11y' );
	}
}
