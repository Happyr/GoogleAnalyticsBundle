# Upgrade from 3.2.2 to 4.0

* Class parameters has been removed to comply with the new Symfony best practices.
* Updated namespace from `Happyr\Google\AnalyticsBundle` to `Happyr\GoogleAnalyticsBundle`.
* Updated service names to include an underscore. Previous: `happyr.google.analytics.data_fetcher` Now: `happyr.google_analytics.data_fetcher`
* New method signature for `DataFetcher::getPageViews`
* We do now use PSR6 cache and Httplug for HTTP messaging.
* Use PSR-4
* Not dependent on Guzzle. We use HTTPlug instead. 