import { store, getElement, getContext } from "@wordpress/interactivity";

const { speak } = wp.a11y;
const { __ } = wp.i18n;

const isEmpty = (obj) =>
	[Object, Array].includes((obj || {}).constructor) &&
	!Object.entries(obj || {}).length;

const updateContext = async (keyword, context) => {
	context.hasResults = false;
	context.books = [];

	if ("" !== keyword) {
		const requestData = {
			action: "ibd_ajax_search_handler",
			nonce: state.nonce,
			keyword: keyword,
		};

		const showData = await fetch(state.ajaxurl, {
			method: "post",
			headers: { "Content-Type": "application/x-www-form-urlencoded" },
			body: new URLSearchParams(requestData).toString(),
		});

		const showJSON = await showData.json();

		if (0 < showJSON.length) {
			context.hasResults = true;
			context.books = showJSON;

			speak(
				__(
					`${showJSON.length} search results for ${keyword}`,
					"interactive-block-demo",
				),
			);
		}
	}

	context.isLoading = false;

	return context;
};

const updateURL = async (value) => {
	const url = new URL(window.location);

	if (!isEmpty(value)) {
		url.searchParams.set("keyword", value);
	} else {
		url.searchParams.delete("keyword");
	}

	history.pushState({}, "", url);
};

const { state } = store("interactivedemo", {
	state: {
		get displayResults() {
			const { books } = getContext();

			return state.keyword && books.length;
		},
		get displayNoResults() {
			const { books } = getContext();

			return state.keyword && 0 === books.length && !state.isLoading;
		},
		get isLoading() {
			return getContext().isLoading;
		},
	},
	actions: {
		*updateSearch() {
			const { ref } = getElement();
			const { value } = ref;
			let context = getContext();

			if (value === state.search) return;

			if (state.typingTimeout) {
				clearTimeout(state.typingTimeout);
			}

			state.typingTimeout = setTimeout(() => {
				state.keyword = value;

				context.isLoading = true;
				context = updateContext(value, context);
				updateURL(value);
			}, 500);
		},
	},
});
