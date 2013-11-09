<?php


namespace HappyR\Google\AnalyticsBundle\Services;

use HappyR\Google\AnalyticsBundle\Model\Transaction;
use Symfony\Component\HttpFoundation\Request;

use UnitedPrototype\GoogleAnalytics\Config;
use UnitedPrototype\GoogleAnalytics\Event;
use UnitedPrototype\GoogleAnalytics\Page;
use UnitedPrototype\GoogleAnalytics\Session;
use UnitedPrototype\GoogleAnalytics\SocialInteraction;
use UnitedPrototype\GoogleAnalytics\Visitor;

use UnitedPrototype\GoogleAnalytics\Tracker as api;

/**
 *
 * This class encapsulate the tracker from the lib
 */
class TrackerService
{
    protected $api;

    /**
     * @var Request request
     *
     *
     */
    protected $request;

    /**
     * @var Session session
     *
     *
     */
    protected $session;

    /**
     * @var Visistor visitor
     *
     *
     */
    protected $visitor;

    /**
     *
     * @param string $profileId might be "UA-12345678-9"
     * @param string $host might be "example.com"
     * @param array $config
     */
    public function __construct($profileId, $host, array $config = array())
    {
        $this->api = new Api($profileId, $host, new Config($config));

        $this->session = new Session();
        $this->visitor = new Visitor();
    }

    /**
     * Track page view
     *
     * @param sring $url
     * @param string $title
     *
     * @return mixed
     */
    public function trackPageview($url, $title = null)
    {
        $page = new Page($url);
        if ($title != null) {
            $page->setTitle($title);
        }

        return $this->api->trackPageview($page, $this->session, $this->visitor);
    }

    /**
     * Track an event
     *
     * @param string $category The name you supply for the group of objects you want to track.
     * @param string $action A string that is uniquely paired with each category, and commonly used to define
     * the type of user interaction for the web object.
     * @param string $label An optional string to provide additional dimensions to the event data.
     * @param string $value An integer that you can use to provide numerical data about the user event.
     * @param boolean $nonInteraction A boolean that when set to true, indicates that the event hit will
     * not be used in bounce-rate calculation.
     *
     *
     * @return mixed
     */
    public function trackEvent($category, $action, $label = null, $value = null, $nonInteraction = false)
    {
        $event = new Event($category, $action, $label, $value, $nonInteraction);

        return $this->api->trackEvent($event, $this->session, $this->visitor);
    }

    /**
     *
     *
     * @param null $network
     * @param null $action
     * @param null $target
     *
     * @return mixed
     */
    public function trackSocial($network = null, $action = null, $target = null)
    {
        $social = new SocialInteraction($network, $action, $target);

        return $this->api->trackSocial($social, $this->session, $this->visitor);
    }

    /**
     * Add a transaction
     *
     * @param Transaction $trans
     *
     * @return mixed
     */
    public function trackTransaction(Transaction $trans)
    {
        return $this->api->trackTransaction($trans, $this->session, $this->visitor);
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;

        //set the visitor ip
        $this->visitor->setIpAddress($request->getClientIp());

        return $this;
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}