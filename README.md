Google Analytics Bundle
=======================

GoogleAnalyticsBundle is a Symfony2 bundle that helps you to push data to Google Analytics.
It could be data like pageview, events etc. It is a bundle implementation of
the [Measurement Protocol][devguide]

This is not a library to pull data from Google analytics. That feature is deprecated from the `classic-analytics` branch.
The master branch and version 3 and above will be supporting Google analytics universal.

## Usage

Read the documentation of the [protocol][devguide].

``` php

//in some container aware class
$tracker = $this->get('happyr.google.analytics.tracker');
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
composer require happyr/google-analytics-bundle:3.0.*
```

### Step 2: Register the bundle

Register the bundle in the AppKernel.php

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Happyr\Google\AnalyticsBundle\HappyrGoogleAnalyticsBundle(),
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

[devguide]: https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide