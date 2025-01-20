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
class Plugin {

	/**
	 * Plugin root folder name.
	 *
	 * @var string
	 */
	private string $basename;

	/**
	 * Set the basename variable on initialization.
	 *
	 * @param string $basename Plugin directory path string.
	 */
	public function __construct( $basename = '' ) {
		$this->basename = $basename;
	}

	/**
	 * Run the plugin's hooks and things.
	 *
	 * @return void
	 */
	public function run(): void {
		add_action( 'init', array( $this, 'block_init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_wpa11y_manually' ) );

		$search = new \Cheffism\InteractiveBlockDemo\Search();
		$search->init();
	}

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 * Behind the scenes, it registers also all assets so they can be enqueued
	 * through the block editor in the corresponding context.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	public function block_init(): void {
		register_block_type_from_metadata( plugin_dir_path( __FILE__ ) . '/../build/block' );
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
