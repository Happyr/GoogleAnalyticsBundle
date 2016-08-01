<?php

namespace Happyr\GoogleAnalyticsBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseTestCase extends WebTestCase
{
    protected static function getKernelClass()
    {
        require_once __DIR__.'/app/AppKernel.php';

        return 'Happyr\GoogleAnalyticsBundle\Tests\Functional\app\AppKernel';
    }

    protected static function createKernel(array $options = [])
    {
        $class = self::getKernelClass();

        return new $class(
            isset($options['config']) ? $options['config'] : 'default.yml'
        );
    }
}
