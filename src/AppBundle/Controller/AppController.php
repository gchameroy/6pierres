<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Form\Type\ProjectInsertType;

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
		$projects = $this->getDoctrine()->getManager()
			->getRepository('AppBundle:Project')
			->findAllOrdered();

        return $this->render('app/project/list.html.twig', array(
			'projects' => $projects
		));
    }

	/**
     * @Route("/management/projects/modal/add", name="app_project_modal_add")
     */
    public function projectModalAddAction(Request $request)
    {
		$project = $this->get('app.project.factory')->create();
        $form = $this->createForm(ProjectInsertType::class, $project);

        if($form->handleRequest($request)->isValid()){
			$em = $this->getDoctrine()->getManager();
			
			$projects = $em->getRepository('AppBundle:Project')->findAll();
			foreach($projects As $p){
				$p->setOrder($p->getOrder() + 1);
				$em->persist($p);
			}

            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('app_project_list');
        }

        return $this->render('app/project/modal/add.html.twig', array(
            'form' => $form->createView()
        ));
    }
	
	/**
     * @Route("/management/projects/ajax/order", name="app_project_ajax_order")
     */
    public function projectAjaxOrderAction(Request $request)
    {
		$projects = json_decode($request->request->get('projects'));
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('AppBundle:Project');
		foreach($projects As $order => $id){
			$project = $repository->findOneById($id);
			$project->setOrder($order + 1);
			$em->persist($project);
		}
		$em->flush();

		$response = new JsonResponse();
		$response->setData(array(
			'valid' => true
		));
		return $response;
    }
}
