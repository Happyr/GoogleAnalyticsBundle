<?php


namespace Happyr\Google\AnalyticsBundle\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class ClientIdProvider
 *
 * @author Tobias Nyholm
 *
 */
class ClientIdProvider
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Return the value of a cookie
     *
     * @param string $name
     *
     * @return mixed|false
     */
    public function getClientIdFormCookie()
    {
        if ($this->request === null) {
            return false;
        }

        $cookies = $this->request->cookies;
        if (!$cookies->has('_ga')) {
            return false;
        }
        $value = $cookies->get('_ga');

        return $this->extractCookie($value);
    }

    /**
     * @link http://stackoverflow.com/a/16107194/1526789
     *
     * @param $cookieValue
     *
     */
    protected function extractCookie($cookieValue)
    {
        return 'abc';
    }
} 