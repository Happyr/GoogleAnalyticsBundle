<?php


namespace HappyR\Google\AnalyticsBundle\Services;
use UnitedPrototype\GoogleAnalytics\Event;
use UnitedPrototype\GoogleAnalytics\Page;
use UnitedPrototype\GoogleAnalytics\SocialInteraction;
use HappyR\Google\AnalyticsBundle\Model\Transaction;


/**
 * This is a dummy class to simulate tracker service
 * 
 */
class TrackerDummyService extends TrackerService
{
    /**
     * Does not do anything
     *
     * @param sring $url
     * @param null $title
     *
     */
    public function trackPageview($url, $title = null)
    {
        $page=new Page($url);
        if($title!=null){
            $page->setTitle($title);
        }

    }

    /**
     * Validates the input
     *
     * @param string $category
     * @param string $action
     * @param string|null $label
     * @param string|null $value
     * @param bool $nonInteraction
     *
     */
    public function trackEvent($category, $action, $label = null, $value = null, $nonInteraction = false)
    {
        $event=new Event($category, $action, $label, $value, $nonInteraction);
        $event->validate();
    }

    /**
     * Validated the input
     *
     * @param null $network
     * @param null $action
     * @param null $target
     *
     */
    public function trackSocial($network = null, $action = null, $target = null)
    {
        $social=new SocialInteraction($network, $action, $target);
        $social->validate();
    }

    /**
     * Validated the input
     *
     * @param Transaction $trans
     *
     */
    public function trackTransaction(Transaction $trans)
    {
        $trans->validate();
    }


}