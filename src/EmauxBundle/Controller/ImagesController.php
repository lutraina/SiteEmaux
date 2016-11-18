<?php


/*
 * Images fait partie de l'admin
 * 
 * Principales routes de l'appli : 
 *      /login 
 *      /images
 *      /homepagesections
 *      /categorie
 *      /boutique
 *      /actualite
 * 
 */


namespace EmauxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmauxBundle\Entity\Images;
use EmauxBundle\Form\ImagesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
 
 

/**
 * Images controller.
 *
 * @Route("/images", name="accueil_images")
 */
class ImagesController extends Controller
{
    /**
     * Lists all Images entities du CRUD.
     *
     * @Route("/", name="images_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('EmauxBundle:Images')->findAll();

		//if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

	        return $this->render('images/index.html.twig', array(
	            'images' => $images,
	        ));
		/*} else { 
			return $this->render('OCUserBundle:Security:login.html.twig', array(
            	'error' => '',
            	'last_username' => '',
        	));
    	}  */
    }

    /**
     * Creates a new Images entity.
     *
     * @Route("/new", name="images_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $image = new Images();
        $form = $this->createForm('EmauxBundle\Form\ImagesType', $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('images_show', array('id' => $image->getId()));
        }

        return $this->render('images/new.html.twig', array(
            'image' => $image,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Images entity.
     *
     * @Route("/{id}", name="images_show")
     * @Method("GET")
     */
    public function showAction(Images $image)
    {
        $deleteForm = $this->createDeleteForm($image);

        return $this->render('images/show.html.twig', array(
            'image' => $image,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Images entity.
     *
     * @Route("/{id}/edit", name="images_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Images $image)
    {
        $deleteForm = $this->createDeleteForm($image);
        $editForm = $this->createForm('EmauxBundle\Form\ImagesType', $image);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('images_edit', array('id' => $image->getId()));
        }

        return $this->render('images/edit.html.twig', array(
            'image' => $image,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Images entity.
     *
     * @Route("/{id}", name="images_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Images $image)
    {
        $form = $this->createDeleteForm($image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
        }

        return $this->redirectToRoute('images_index');
    }

    /**
     * Creates a form to delete a Images entity.
     *
     * @param Images $image The Images entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Images $image)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('images_delete', array('id' => $image->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    /**
     * Creates a new Photos entity.
     *
     * @Route("/", name="photos_create")
     * @Method("POST")
     * @Template("EmauxBundle:Images:new.html.twig")
     */
    public function createAction(Request $request)
    {
    	
    	 

        $entity  = new Images();
        $form = $this->createForm(new ImagesType(), $entity);
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
            
            
             
            return $this->redirect($this->generateUrl('accueil_images', array('id' => $entity->getId())));
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
    	->getRepository('EmauxBundle:Images')
    	->find($id);
    	return array(
    			'image' => $entity
    			);
    	
    	
    }
    
    
    /**
     * Edits an existing Photos entity.
     *
     * @Route("/{id}", name="images_update")
     * @Method("POST")
     */
    public function updateAction(Request $request, $id)
    {
        
        
        $image2 = '';
        $ext = '';
        $em = $this->getDoctrine()->getManager();
        //$homepageSections = $em->getRepository('EmauxBundle:HomepageSections')->find($id);
        $Boutique = $em->getRepository('EmauxBundle:Images')->findOneBy(array('id' => $id));
        if($Boutique->getImage()){
          
            $image2 = $Boutique->getImage();
            //$path = $_FILES['image']['name'];
            $ext = pathinfo($image2, PATHINFO_EXTENSION);
            //return new Response($ext);
        }  
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EmauxBundle:Images')->find($id);
        $form = $this->createForm(new ImagesType(), $entity);
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
             
            return $this->redirect($this->generateUrl('images_index', array('id' => $entity->getId())));
            
            
    }
    
}
