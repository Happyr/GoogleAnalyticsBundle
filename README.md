Google Analytics Bundle
=======================

GoogleAnalticsBundle is a Symfony2 bundle that helps you to communicate with Google Analytics. It will
push data to the server (like page visits and events) and it can fetch data from the server (like pageviews for a
specific url).


What is HappyR?
---------------
The HappyR namespace is developed by [HappyRecruiting][1]. We put some of our bundles here because we love to share.
Since we use a lot of open source libraries and bundles in our application it feels natural to give back something.
You will find all our Symfony2 bundles that we've created for the open source world at [developer.happyr.se][2]. You
will also find more documentation about each bundle and our API clients, WordPress plugins and more.



Installation
------------

### Step 1: Using Composer

Install it with Composer! Since this plugin have some dependencies on "non stable" PHP libraries you have to add these
following lines. I have contacted the authors of these libraries and are waiting for the response.

```js
// composer.json
{
    // ...
    require: {
        // ...
        "happyr/google-analytics-bundle": "1.0.*",
            "unitedprototype/php-ga": "@dev"
    }
}
```

Then, you can install the new dependencies by running Composer's ``update``
command from the directory where your ``composer.json`` file is located:

```bash
$ php composer.phar update
```

### Step 2: Register the bundle

The Google Analytics Bundle depends on [HappyRGoogleApiBundle][3] which will be installed with the Analytics Bundle but
you have to register both of them in your kernel. To register the bundles with your kernel:

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new HappyR\Google\ApiBundle\HappyRGoogleApiBundle(),
    new HappyR\Google\AnalyticsBundle\HappyRGoogleAnalyticsBundle(),
    // ...
);
```

### Step 3: Configure the bundle

``` yaml
# app/config/config.yml

happy_r_google_analytics:
    // ...
    profile_id: 64874768 # The google analytics profile id. This is not the same as the tracking code.
    token_file_path: %kernel.root_dir%/var/storage   #The path where to save a temporary token
```

You do also need to configure the HappyRGoogleApiBundle. I recommend you to have a look at [its configuration][3].


[1]: http://happyrecruiting.se
[2]: http://developer.happyr.se
[3]: http://developer.happyr.se/symfony2-bundles/google-api-bundle
