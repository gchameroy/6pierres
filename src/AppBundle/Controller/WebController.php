<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WebController extends Controller
{
    /**
     * @Route("/", name="web")
     */
    public function indexAction(Request $request)
    {
		$projects = $this->getDoctrine()->getManager()
			->getRepository('AppBundle:Project')
			->findAll();
		
		for($i=0; $i<count($projects); $i++){
			if(count($projects[$i]->getPhotos()) == 0){
				unset($projects[$i]);
			}
		}
		
        return $this->render('web/index.html.twig', array(
			'projects' => $projects
		));
    }
}
