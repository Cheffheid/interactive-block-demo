<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 *
 * @package InteractiveBlockDemo
 */

use function Cheffism\InteractiveBlockDemo\get_ibd_results;

$keyword = isset( $_GET['keyword'] ) ? $_GET['keyword'] : '';

wp_interactivity_state(
	'interactivedemo',
	array(
		'keyword'   => esc_attr( $keyword ),
		'nonce'     => wp_create_nonce( 'ajax_nonce_ibd' ),
		'ajaxurl'   => admin_url( 'admin-ajax.php' ),
		'isLoading' => false,
	)
);

$context = array(
	'books' => get_ibd_results( $keyword ),
);

?>
<div
	data-wp-interactive="interactivedemo"
	<?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>
	<?php echo wp_interactivity_data_wp_context( $context ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
>
	<form role="search" class="aligncenter">
		<label class="wp-block-ibd__label screen-reader-text" for="wp-block-ibd__input">
			<?php esc_html_e( 'Search', 'interactive-block-demo' ); ?>
		</label>
		<div class="wp-block-ibd__inside-wrapper">
			<input
				class="wp-block-ibd__input"
				id="interactivedemo"
				placeholder="<?php esc_attr_e( 'Enter your search query', 'interactive-block-demo' ); ?>"
				value="<?php echo esc_attr( $keyword ); ?>"
				type="search"
				name="keyword"
				data-wp-bind--value="state.keyword"
				data-wp-on--input="actions.updateSearch"
			/>
			<button aria-label="<?php esc_attr_e( 'Search', 'interactive-block-demo' ); ?>" class="wp-block-ibd__button has-icon wp-element-button" type="submit" data-wp-on--click="actions.submit">
				<svg class="search-icon" viewBox="0 0 24 24" width="24" height="24">
					<path d="M13 5c-3.3 0-6 2.7-6 6 0 1.4.5 2.7 1.3 3.7l-3.8 3.8 1.1 1.1 3.8-3.8c1 .8 2.3 1.3 3.7 1.3 3.3 0 6-2.7 6-6S16.3 5 13 5zm0 10.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5z"></path>
				</svg>
			</button>
		</div>
	</form>
	<h2 class="wp-block-ibd__heading" data-wp-bind--hidden="!state.keyword">
		<?php esc_html_e( 'Search results for:', 'interactive-block-demo' ); ?> <span data-wp-text="state.keyword"></span>
	</h2>
	<div class="wp-block-ibd__results">
		<div class="wp-block-ibd__spinner" data-wp-class--loading="state.isLoading">
			<span class="wp-block-ibd__spinner-icon" aria-label="<?php esc_attr_e( 'Loading...', 'interactive-block-demo' ); ?>"></span>
		</div>
		<div class="wp-block-ibd__noresults" data-wp-class--show="state.displayNoResults">
			<?php esc_html_e( 'Sorry, no books found for that keyword.', 'interactive-block-demo' ); ?>
		</div>
		<ul class="wp-block-ibd__search-results" data-wp-bind--hidden="!state.displayResults">
			<template data-wp-each--book="context.books" >
				<li class="wp-block-ibd__search-result">
					<img data-wp-bind--src="context.book.cover" alt="" class="wp-block-ibd__search-result-image" data-wp-bind--hidden="!context.book.has_cover" />
					<div>
						<h3 class="wp-block-ibd__search-result-header">
							<a data-wp-bind--href="context.book.link">
								<span data-wp-text="context.book.title"></span>
							</a>
						</h3>
						<div class="wp-block-ibd__search-result-text">
							<div class="wp-block-ibd__search-result-ebook" data-wp-bind--hidden="!context.book.has_ebook">
								<svg class="wp-block-ibd__icon wp-block-ibd__icon-book"><use xlink:href="#ibd-icon-book"></use></svg>
								<span data-wp-text="context.book.ebook"></span>
							</div>
							<p>
								<template data-wp-each="context.book.author">
									<span data-wp-text="context.item"></span><br />
								</template>
							</p>
							<p>
								<?php esc_html_e( 'First published: ', 'interactive-block-demo' ); ?>
								<span data-wp-text="context.book.first_published"></span>
							</p>
						</div>
					</div>
				</li>
				<hr class="wp-block-ibd__search-result-separator is-style-wide">
			</template>
		</ul>
	</div>
	<svg class="wp-block-ibd__icon-defs-only" xmlns="http://www.w3.org/2000/svg" >
		<symbol id="ibd-icon-book" viewBox="0 0 32 32">
			<path d="M28 4v26h-21c-1.657 0-3-1.343-3-3s1.343-3 3-3h19v-24h-20c-2.2 0-4 1.8-4 4v24c0 2.2 1.8 4 4 4h24v-28h-2z"></path>
			<path d="M7.002 26v0c-0.001 0-0.001 0-0.002 0-0.552 0-1 0.448-1 1s0.448 1 1 1c0.001 0 0.001-0 0.002-0v0h18.997v-2h-18.997z"></path>
		</symbol>
	</svg>
</div>
