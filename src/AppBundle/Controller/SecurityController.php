<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SecurityController extends Controller
{
    /**
     * @Route("/sign-in", name="login")
     * @Method("GET")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/sign-out", name="logout")
     * @Method("GET")
     */
    public function logoutAction()
    {

    }

    /**
     * @Route("/login-check", name="login_check")
     * @Method("POST")
     */
    public function loginCheckAction()
    {

    }
}
