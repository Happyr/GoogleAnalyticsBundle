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
    public function trackPageview($url, $title = null)
    {
        $page=new Page($url);
        if($title!=null){
            $page->setTitle($title);
        }

        //nothing to validate...
        return;
    }

    public function trackEvent($category, $action, $label = null, $value = null, $nonInteraction = false)
    {
        $event=new Event($category, $action, $label, $value, $nonInteraction);
        $event->validate();
    }

    public function trackSocial($network = null, $action = null, $target = null)
    {
        $social=new SocialInteraction($network, $action, $target);
        $social->validate();
    }

    public function trackTransaction(Transaction $trans)
    {
        $trans->validate();
    }


}