# Interactive Block Demo

This is an example of a block I have built for a client. The original version of this block uses a proprietary API with proprietary data.
It seemed reasonable to me to remove and convert that piece of the block into something more generic for the sake of demonstration.

And so this block example will make use of the OpenLibrary API to search and present some basic details about the works the API returns from the query.

Otherwise, the general logic and functionality is the same. The main difference, since the amount of data is vastly different, is that the original version of the search would query for everything in the API and store that in a transient for 15 minutes. It would then use that data to filter based on the keyword.

For this example I have reworked that and stored the search results for a given query into a 15 minute transient instead.

## Changelog

### 1.0.0

First release. Includes:

- Block registration with interactivity enabled.
- OpenLibrary endpoint and search handling (limited to 10 results).
- Composer and PHPCS configuration.

## Future considerations

I might come back and make this more useful. The original did not have any pagination (since its endpoint did not provide that option), and so adding that would make for a nice first improvement.
