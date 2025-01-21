<?php
/**
 * Search class file.
 *
 * @package InteractiveBlockDemo
 */

namespace Cheffism\InteractiveBlockDemo;

/**
 * Class for managing search functionality.
 */
class Search {

	/**
	 * Api Connector for the search.
	 *
	 * @var Api\ApiConnector
	 */
	private $api;

	/**
	 * Instance variable.
	 *
	 * @var null|self
	 */
	private static $instance = null;

	/**
	 * Returns the singleton instance of this Search class.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize the API Connector when the Search is initialized.
	 */
	public function __construct() {
		$this->api = new Api\OpenLibrary();
	}

	/**
	 * Initialize AJAX handlers for search.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'wp_ajax_ibd_ajax_search_handler', array( $this, 'ajax_ibd_search_handler' ), 10, 0 );
		add_action( 'wp_ajax_nopriv_ibd_ajax_search_handler', array( $this, 'ajax_ibd_search_handler' ), 10, 0 );
	}

	/**
	 * This is the entry point for the search that is called from render.php to create the context but is also called from the AJAX handler.
	 * It will return stored results if they are less than 15 minutes old, otherwise it will connect to the API to get fresh results.
	 * And then it will use the provided keyword to filter the shows from the API and return them.
	 *
	 * @param string $keyword Keyword to use as the search query.
	 * @return array
	 */
	public function get_ibd_results( $keyword ): array {
		if ( empty( $keyword ) ) {
			return array();
		}

		return $this->api->get_results( $keyword );
	}

	/**
	 * Query the FOL API with a keyword and return formatted data for the block to display search results.
	 *
	 * @author Jeffrey de Wit
	 * @since October 17, 2024
	 */
	public function ajax_ibd_search_handler() {
		if ( ! check_ajax_referer( 'ajax_nonce_ibd', 'nonce', false ) ) {
			wp_send_json( '{}', 403 );
			die;
		}

		$results = $this->get_ibd_results( $_POST['keyword'] );

		wp_send_json( $results, 200 );

		wp_die();
	}
}
