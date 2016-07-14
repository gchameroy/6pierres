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
		$em = $this->getDoctrine()->getManager();
		$project = $em
			->getRepository('AppBundle:Project')
			->findOneById($id);

		$photos = $em
			->getRepository('AppBundle:Photo')
			->findBy(
				array('project' => $project),
				array('orderId' => 'ASC')
			);
		return $this->render('app/project/ajax/view.html.twig', array(
			'project' => $project,
			'photos' => $photos
		));
    }
    
    /**
     * @Route("/management/projects/{id}/photo/modal/add", name="app_project_photo_modal_add")
     */
    public function projectPhotoModalAddAction($id, Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$project = $em->getRepository('AppBundle:Project')
			->findOneById($id);
		
		$photo = $this->get('app.photo.factory')->create();

		$form = $this->createForm(PhotoInsertType::class, $photo);
        $form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
			
			$photos = $em->getRepository('AppBundle:Photo')
				->findAll(array(
					'project' => $project
				));
			
			$file = $photo->getFile();
			$response = $this->get('app.photo_uploader')->uploadFile($file);

            $photo->setFile($response->fileName)
				->setThumb($response->thumbName)
				->setOrder(count($photos) + 1)
				->setProject($project);

			$em->persist($photo);
			$em->flush();
            return $this->redirect($this->generateUrl('app_project_list'));
        }
		return $this->render('app/project/modal/add_photo.html.twig', array(
			'form' => $form->createView(),
			'project' => $project
		));
    }

	/**
     * @Route("/management/projects/view/ajax/orderPhoto", name="app_project_view_ajax_orderPhoto")
     */
    public function projectViewAjaxOrderPhotoAction(Request $request)
    {
		$photos = json_decode($request->request->get('photos'));
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('AppBundle:Photo');
		foreach($photos As $order => $id){
			$photo = $repository->findOneById($id);
			$photo->setOrder($order + 1);
			$em->persist($photo);
		}
		$em->flush();

		$response = new JsonResponse();
		$response->setData(array(
			'valid' => true
		));
		return $response;
    }

	/**
     * @Route("/management/projects/photo/ajax/delete", name="app_project_photo_ajax_delete")
     */
    public function projectPhotoAjaxDeleteAction(Request $request)
    {
		$id = $request->request->get('id');
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('AppBundle:Photo');
		$photo = $repository->findOneById($id);
		$project = $photo->getProject();
		$em->remove($photo);
		$em->flush();
		
		$photos = $repository->findBy(
			array('project' => $project),
			array('orderId' => 'ASC')
		);
		
		$i = 1;
		foreach($photos As $photo){
			$photo->setOrder($i);
			$em->persist($photo);
			$i++;
		}
		$em->flush();

		$response = new JsonResponse();
		$response->setData(array(
			'valid' => true
		));
		return $response;
    }
	
	/**
     * @Route("/management/projects/ajax/delete", name="app_project_ajax_delete")
     */
    public function projectAjaxDeleteAction(Request $request)
    {
		$id = $request->request->get('id');
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('AppBundle:Project');
		$project = $repository->findOneById($id);
		$em->remove($project);
		$em->flush();

		$response = new JsonResponse();
		$response->setData(array(
			'valid' => true
		));
		return $response;
    }
}
