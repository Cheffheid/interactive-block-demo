# Interactive Block Demo

This is an example of a block I have built for a client. The original version of this block uses a proprietary API with proprietary data.
It seemed reasonable to me to remove and convert that piece of the block into something more generic for the sake of demonstration.

And so this block example will make use of the OpenLibrary API to search and present some basic details about the works the API returns from the query.

## Get Started

This block is packaged as a plugin, but you will need to run a few commands to actually get a build running for this after you download/clone it.

From the root folder, you will want to run the following to get a build going and allow the block to function in WordPress:

`npm i && npm run build`

You're free to use npm alternatives if you want, it _shouldn't_ make that much of a difference if you do.

If you wanted to make your own updates to this, you will need to either manually run `npm run build` after you're done or run `npm run start` so that it will automatically rebuild on file changes.

You will also need Composer to generate the necessary autoload file so that the plugin itself will run properly. Simply run either one of these commands:

`composer install` if you also want any dev packages that I've saved to composer.json (like phpcs).

`composer install --no-dev` if you just want to try out the plugin.

## Changelog

### 1.0.0

First release. Includes:

- Block registration with interactivity enabled.
- OpenLibrary endpoint and search handling (limited to 10 results).
- Composer and PHPCS configuration.

### 1.1.0

Added some tweaks and updates to this. Like:

- Use wp.a11y.speak() to announce the search result count for the keyword.
- Include book cover, openlibrary work link, ebook access with icon.

### 2.0.0

Rewrote the entire thing using a OOP approach, separating the "search" that the block wants, the API it searches, and the caching it uses.

## Future considerations

I might come back and make this more useful. The original did not have any pagination (since its endpoint did not provide that option), and so adding that would make for a nice first improvement.

The rewrite also opens up the option to hook up other APIs more easily.
