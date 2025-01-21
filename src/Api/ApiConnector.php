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
abstract class ApiConnector {

	/**
	 * API endpoint.
	 *
	 * @var string
	 */
	private $endpoint;

	/**
	 * Search keyword.
	 *
	 * @var string
	 */
	private $keyword;

	/**
	 * Handles the results logic, including any caching you want to include.
	 *
	 * @param string $keyword Search keyword.
	 * @return array<mixed>
	 */
	abstract public function get_results( $keyword = '' );

	/**
	 * Runs a search on the API endpoint.
	 *
	 * @return string
	 */
	abstract protected function connect_api();
}
