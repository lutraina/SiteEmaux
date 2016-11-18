<?php

namespace EmauxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmauxBundle\Entity\HomepageSections;
use EmauxBundle\Form\HomepageSectionsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * HomepageSections controller.
 *
 * @Route("/homepagesections")
 */
class HomepageSectionsController extends Controller
{
    /**
     * Lists all HomepageSections entities.
     *
     * @Route("/", name="homepagesections_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $homepageSections = $em->getRepository('EmauxBundle:HomepageSections')->findAll();

        return $this->render('homepagesections/index.html.twig', array(
            'homepageSections' => $homepageSections,
        ));
    }

    /**
     * Creates a new HomepageSections entity.
     *
     * @Route("/new", name="homepagesections_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        
        $homepageSection = new HomepageSections();
        $form = $this->createForm('EmauxBundle\Form\HomepageSectionsType', $homepageSection);
        $form->bind($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($homepageSection);
            $em->flush();

            return $this->redirectToRoute('images_show', array('id' => $homepageSection->getId()));
        }

        return $this->render('homepagesections/new.html.twig', array(
            'homepageSection' => $homepageSection,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a HomepageSections entity.
     *
     * @Route("/{id}", name="homepagesections_show")
     * @Method("GET")
     */
    public function showAction(HomepageSections $homepageSection)
    {
        $deleteForm = $this->createDeleteForm($homepageSection);

		if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
	        return $this->render('homepagesections/show.html.twig', array(
	            'homepageSection' => $homepageSection,
	            'delete_form' => $deleteForm->createView(),
	        ));
	    } 
    
    }

    /**
     * Displays a form to edit an existing HomepageSections entity.
     *
     * @Route("/{id}/edit", name="homepagesections_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, HomepageSections $homepageSection)
    {
        $deleteForm = $this->createDeleteForm($homepageSection);
        $editForm = $this->createForm('EmauxBundle\Form\HomepageSectionsType', $homepageSection);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($homepageSection);
            $em->flush();

            return $this->redirectToRoute('homepagesections_edit', array('id' => $homepageSection->getId()));
        }

        return $this->render('homepagesections/edit.html.twig', array(
            'homepageSection' => $homepageSection,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a HomepageSections entity.
     *
     * @Route("/{id}", name="homepagesections_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, HomepageSections $homepageSection)
    {
        $form = $this->createDeleteForm($homepageSection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($homepageSection);
            $em->flush();
        }

        return $this->redirectToRoute('homepagesections_index');
    }

    /**
     * Creates a form to delete a HomepageSections entity.
     *
     * @param HomepageSections $homepageSection The HomepageSections entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(HomepageSections $homepageSection)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('homepagesections_delete', array('id' => $homepageSection->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Creates a new Photos entity.
     *
     * @Route("/", name="homesection_create")
     * @Method("POST")
     * @Template("EmauxBundle:HomePageSections:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new HomePageSections();
        $form = $this->createForm(new HomePageSectionsType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            

            $uploadedDirectory = $this->container->getParameter('kernel.root_dir').'/../web/uploads';
            $image=$entity->getImage();
            /*@var $image \Symfony\Component\HttpFoundation\File\UploadedFile */
            $image->move($uploadedDirectory, $image->getClientOriginalName());
            $entity->setImage($image->getClientOriginalName());
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
//            $this->get('session')->setFlash('notice','Uploaded');
            
            
             
            return $this->redirect($this->generateUrl('homepagesections_index', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Creates a new Photos entity.
     *
     * @Route("/confirm/{id}", name="upload_confirm") //////chnger ça, le confirm de la route
     * @Template()
     */
    
    public function uploadConfirmAction($id)
    {
    	$entity = $this->getDoctrine()
    	->getEntityManager()
    	->getRepository('EmauxBundle:HomePageSections')
    	->find($id);
    	return array(
    			'image' => $entity
    			);
    	
    	
    }
    
    
    /**
     * Edits an existing Photos entity.
     *
     * @Route("/{id}", name="homepage_update")
     * @Method("POST")
     * @Template("EmauxBundle:HomepageSections:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $image2 = '';
        $ext = '';
        $em = $this->getDoctrine()->getManager();
        //$homepageSections = $em->getRepository('EmauxBundle:HomepageSections')->find($id);
        $homepageSections = $em->getRepository('EmauxBundle:HomepageSections')->findOneBy(array('id' => $id));
        if($homepageSections->getImage()){
          
            $image2 = $homepageSections->getImage();
            //$path = $_FILES['image']['name'];
            $ext = pathinfo($image2, PATHINFO_EXTENSION);
            //return new Response($ext);
        }    

        
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EmauxBundle:HomepageSections')->find($id);
//        $entity  = new Images();
        
        $form = $this->createForm(new HomepageSectionsType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            
                $uploadedDirectory = $this->container->getParameter('kernel.root_dir').'/../web/uploads';
                $image=$entity->getImage();
               
            if($image){
                //return new Response('tem nova img');
                
                /*@var $image \Symfony\Component\HttpFoundation\File\UploadedFile */
                $image->move($uploadedDirectory, $image->getClientOriginalName());
                $entity->setImage($image->getClientOriginalName());
                //return new Response($image);
                    
            } else {
                //return new Response('nao tem nova img');
                $entity->setImage($image2);
            }
                
            $em->persist($entity);
            $em->flush();
            
//            $this->get('session')->setFlash('notice','Uploaded');
            
        }
        

        return $this->redirect($this->generateUrl('homepagesections_index', array('id' => $entity->getId())));
                
          
//            $this->get('session')->setFlash('notice','Uploaded');
            
    }
    
    
}
