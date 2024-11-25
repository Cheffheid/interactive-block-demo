/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps } from "@wordpress/block-editor";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<div className="aligncenter">
				<label
					className="wp-block-ibd__label screen-reader-text"
					for="wp-block-ibd__input"
				>
					{__("Search", "interactive-block-demo")}
				</label>
				<div className="wp-block-ibd__inside-wrapper">
					<input
						className="wp-block-ibd__input"
						id="wp-block-ibd__input"
						placeholder={__(
							"Enter your search query",
							"interactive-block-demo",
						)}
						value=""
						type="search"
						name="showname"
						required=""
					/>
					<button
						aria-label={__("Search", "interactive-block-demo")}
						className="wp-block-ibd__button has-icon wp-element-button"
						type="submit"
					>
						<svg
							className="search-icon"
							viewBox="0 0 24 24"
							width="24"
							height="24"
						>
							<path d="M13 5c-3.3 0-6 2.7-6 6 0 1.4.5 2.7 1.3 3.7l-3.8 3.8 1.1 1.1 3.8-3.8c1 .8 2.3 1.3 3.7 1.3 3.3 0 6-2.7 6-6S16.3 5 13 5zm0 10.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5z"></path>
						</svg>
					</button>
				</div>
			</div>
		</div>
	);
}
