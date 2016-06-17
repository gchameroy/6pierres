<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AppController extends Controller
{
    /**
     * @Route("/management", name="app")
     */
    public function indexAction(Request $request)
    {
        return $this->render('app/index.html.twig');
    }
	
	/**
     * @Route("/management/projects", name="app_project_list")
     */
    public function projectListAction(Request $request)
    {
        return $this->render('app/project/list.html.twig');
    }
}
