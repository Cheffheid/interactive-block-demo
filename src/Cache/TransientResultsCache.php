<?php
/**
 * API ResultsCache class.
 *
 * @package InteractiveBlockDemo
 */

namespace Cheffism\InteractiveBlockDemo\Cache;

/**
 * ResultsCache class definition. Caches API results into a transient as a base64 encoded string.
 */
class TransientResultsCache {

	/**
	 * Transient name prefix string.
	 *
	 * @var string
	 */
	private string $transient_prefix;

	/**
	 * Transient expiration
	 *
	 * @var integer
	 */
	private int $transient_expiration = 15 * MINUTE_IN_SECONDS;

	/**
	 * Class constructor. Stores the transient prefix to the instance.
	 *
	 * @param string $transient_prefix Set a custom transient name.
	 */
	public function __construct( string $transient_prefix = 'ibd_ol_search_results_' ) {
		$this->transient_prefix = $transient_prefix;
	}

	/**
	 * Get the cached results for the provided search keyword.
	 *
	 * @param string $keyword Keyword the search was for, to help distinguish the transient.
	 * @return string
	 */
	public function get_cached_results( string $keyword = '' ): string {
		$cached_results = get_transient( $this->transient_prefix . $keyword );

		return $cached_results ? base64_decode( $cached_results ) : ''; // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
	}

	/**
	 * Store the results into a transient.
	 *
	 * @param string $results Results that need to be stored in the transient.
	 * @param string $transient Transient suffix (likely to be search keyword).
	 * @return boolean
	 */
	public function set_cached_results( string $results, string $transient = '' ): bool {
		return set_transient( $this->transient_prefix . $transient, base64_encode( $results ), $this->transient_expiration ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
	}
}
