Google Analytics Bundle
=======================

[![Latest Version](https://img.shields.io/github/release/Happyr/GoogleAnalyticsBundle.svg?style=flat-square)](https://github.com/Happyr/GoogleAnalyticsBundle/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/Happyr/GoogleAnalyticsBundle.svg?style=flat-square)](https://travis-ci.org/Happyr/GoogleAnalyticsBundle)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Happyr/GoogleAnalyticsBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/Happyr/GoogleAnalyticsBundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/Happyr/GoogleAnalyticsBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/Happyr/GoogleAnalyticsBundle)
[![Total Downloads](https://img.shields.io/packagist/dt/happyr/google-analytics-bundle.svg?style=flat-square)](https://packagist.org/packages/happyr/google-analytics-bundle)

GoogleAnalyticsBundle is a Symfony2 bundle that helps you to push data to Google Analytics.
It could be data like pageview, events etc. It is a bundle implementation of
the [Measurement Protocol][devguide]

This is not a library to pull data from Google analytics. That feature is deprecated from the `classic-analytics` branch.
The master branch and version 3 and above will be supporting Google analytics universal.

## Special feature

This bundle has a special feature. Say that you want to post data to Analytics. You want to post an event every time someone downloads a file. You may do that from the server like any other library. When looking at the reports you will find that you are missing the information about the actual user for the download event. You can not use a segment to find out which referal the user came from. 

This bundle helps you with just that. Before we submit any data we look at the `_ga` cookie to find the user's clientId. So now you may use segments and advanced queries to analytics and you will get the expected result. 

## Usage

Read the documentation of the [protocol][devguide].

``` php

//in some container aware class
$tracker = $this->get('happyr.google_analytics.tracker');
$data=array(
    'dh'=>'mydemo.com',
    'dp'=>'/home',
    'dt'=>'homepage',
);
$tracker->send($data, 'pageview');

```

## Installation

Install with composer.


``` bash
composer require happyr/google-analytics-bundle
```

### Step 2: Register the bundle

Register the bundle in the AppKernel.php

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Happyr\GoogleAnalyticsBundle\HappyrGoogleAnalyticsBundle(),
    // ...
);
```

### Step 3: Configure the bundle

``` yaml
# app/config/config.yml

happyr_google_analytics:
    // ...
    tracking_id: UA-XXXX-Y
```

### Step 4: Provide a HTTP client and message factory service. 

You need to provide two services to that know how to create and send message factories. The services must implement
`Http\Message\RequestFactory` and `Http\Client\HttpClient`. If you use [HTTPlugBundle](https://github.com/php-http/HttplugBundle)
 this will be taken care of automatically. You will aslo get some nice logging features. 

``` yaml
# app/config/config.yml

happyr_google_analytics:
    // ...
    http_client: 'httplug.client'
    http_message_factory: 'httplug.message_factory
```

## Fetching data

If you want to fetch data from Google Analytics you must install and configure [GoogleSiteAuthenticatorBundle][siteAuth]. Read its documentaion and then configure the analytics bundle with a `client service` and a `view id`. The `view id` is found in the admin section on Google analytics. Go to Admin > Account > Property > View > View settings. 

``` yaml
# app/config/config.yml

happyr_google_analytics:
    // ...
    tracking_id: UA-XXXX-Y
    fetching:
        client_service: 'google.client.tobias_gmail'
        view_id: 0123456789
        cache_service: 'cache.provider.my_memcached' # optinally a PSR6 cache service
        cache_lifetime: 3600 # default
```

You may then run the following code to get the page views for /example-page.

``` php
$fetcher = $this->get('happyr.google_analytics.data_fetcher');
$pv = $fetcher->getPageViews('/example-page');
```


[devguide]: https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide
[siteAuth]: https://github.com/Happyr/GoogleSiteAuthenticatorBundle
