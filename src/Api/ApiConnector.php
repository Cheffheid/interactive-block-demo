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
	 * API search entry function. Expects a keyword string to search for.
	 * Also expected to handle the results logic, including any caching you may want to include.
	 *
	 * @param string $keyword Search keyword.
	 * @return array<mixed>
	 */
	abstract public function get_results( $keyword = '' );

	/**
	 * Make a request of the API. Expected to be called from the get_results method.
	 *
	 * @return string
	 */
	abstract protected function connect_api();
}
