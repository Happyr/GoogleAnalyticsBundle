<?php

namespace HappyR\Google\AnalyticsBundle\Controller;

use HappyR\Google\AnalyticsBundle\Entity\GoogleApiReportToken;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin/google")
 */
class GoogleOAuthController extends Controller
{
    /**
     * @Route("/oauth2callback")
     *
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function oauth2callbackAction()
    {
        $em=$this->getDoctrine()->getManager();
        $client=$this->get('webfish.google.client');
        $client->authenticate();

        $token=$em->getRepository('HappyRGoogleAnalyticsBundle:GoogleApiReportToken')->findOne();
        if (!$token) {
            $token=new GoogleApiReportToken();
        }
        $token->setToken($client->getAccessToken());

        $em->persist($token);
        $em->flush();

        return $this->redirect($this->generateUrl('_admin_dashboard'));
    }

    /**
     * @Route("/authenticate/analytics")
     *
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function authenticateAction()
    {
        $em=$this->getDoctrine()->getManager();
        $client=$this->get('webfish.google.analytics')->client;

        $token = $em -> getRepository('HappyRGoogleAnalyticsBundle:GoogleApiReportToken') -> findOne();
        if ($token) {//if token is defined
            $this->get('session')->setFlash('success', 'applicare.profile.flash');

            return $this->redirect($this->generateUrl('_admin_dashboard'));
        }

        $authUrl = $client -> createAuthUrl();

        return $this->redirect($authUrl);
    }
}
