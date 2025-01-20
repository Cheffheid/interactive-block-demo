<?php
/**
 * Base API Connector class.
 *
 * @package InteractiveBlockDemo
 */

namespace Cheffism\InteractiveBlockDemo\Api;

/**
 * Base class definition for API endpoints.
 */
class ApiConnector {

	/**
	 * API endpoint.
	 *
	 * @var string
	 */
	private $endpoint;

	/**
	 * Handles the results logic, including any caching you want to include.
	 *
	 * @param string $keyword Search keyword.
	 * @return void
	 */
	public function get_results( $keyword = '' ) {
	}

	/**
	 * Runs a search on the API endpoint.
	 *
	 * @param string $keyword Search keyword.
	 * @return void
	 */
	private function connect_api( $keyword = '' ) {
	}
}
