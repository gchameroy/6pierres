<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Form\Type\ProjectInsertType;
use AppBundle\Form\Type\PhotoInsertType;

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
			->findBy(
				array(),
				array('orderId' => 'ASC')
			);

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
	
	/**
     * @Route("/management/projects/ajax/view", name="app_project_ajax_view")
     */
    public function projectViewAction(Request $request)
    {
		$id = $request->request->get('id');
		$project = $this->getDoctrine()->getManager()
			->getRepository('AppBundle:Project')
			->findOneById($id);

		return $this->render('app/project/ajax/view.html.twig', array(
			'project' => $project
		));
    }
    
    /**
     * @Route("/management/projects/{id}/photo/modal/add", name="app_project_photo_modal_add")
     */
    public function projectPhotoModalAddAction($id, Request $request)
    {
		$project = $this->getDoctrine()->getManager()
			->getRepository('AppBundle:Project')
			->findOneById($id);
			
		$photo = $this->get('app.photo.factory')->create();

		$form = $this->createForm(PhotoInsertType::class, $photo);
        $form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			
			$file = $photo->getFile();
			$fileName = $this->get('app.photo_uploader')->upload($file);
            $photo->setFile($fileName);
			$photo->setProject($project);
			
			$em->persist($photo);
			$em->flush();
			dump('testy');
            return $this->redirect($this->generateUrl('app_project_list'));
        }
		dump('Yoloo');
		return $this->render('app/project/modal/add_photo.html.twig', array(
			'form' => $form->createView(),
			'project' => $project
		));
    }
}
