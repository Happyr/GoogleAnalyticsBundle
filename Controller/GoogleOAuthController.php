<?php

namespace HappyR\Google\AnalyticsBundle\Controller;

use HappyR\Google\AnalyticsBundle\Entity\GoogleApiReportToken;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/google/analytics")
 */
class GoogleOAuthController extends Controller
{
    /**
     * @Route("/", name="_happyr_google_analytics_index")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        $token = $this->get('happyr.google.analytics.token')->getToken();

        return array(
            'authenticated'=>$token!=null,
        );
    }

    /**
     * @Route("/oauth2callback", name="_happyr_google_analytics_callback")
     *
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function oauth2callbackAction()
    {
        //authenticate
        $client=$this->get('happyr.google.api.client');
        $client->authenticate();

        //save token
        $token = $this->get('happyr.google.analytics.token')->setToken($client->getAccessToken());

        return $this->redirect($this->generateUrl('_happyr_google_analytics_index'));
    }

    /**
     * @Route("/authenticate", name="_happyr_google_analytics_authenticate")
     *
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function authenticateAction()
    {
        $token = $this->get('happyr.google.analytics.token')->getToken();

        $client=$this->get('happyr.google.api.analytics')->client;
        $authUrl = $client -> createAuthUrl();

        return $this->redirect($authUrl);
    }
}
