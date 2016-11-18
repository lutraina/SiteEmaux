<?php

namespace EmauxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmauxBundle\Entity\Boutique;
use EmauxBundle\Form\BoutiqueType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

 

/**
 * Boutique controller.
 *
 * @Route("/boutique")
 */
class BoutiqueController extends Controller
{
    
    
    /**
     * Lists all Boutique entities.
     *
     * @Route("/boutiqueAll", name="boutiqueAll_index")
     * @Method("GET")
     */
    public function boutiqueAllAction()
    {
        $logger = $this->get('logger');
        $logger = $this->get('logger')->info('testando logger');
        $em = $this->getDoctrine()->getManager();

        $boutiques = $em->getRepository('EmauxBundle:Boutique')->findAll();

        return $this->render('boutique/index.html.twig', array(
            'boutiques' => $boutiques,
        ));
    }
    

    /**
     * Creates a new Boutique entity.
     *
     * @Route("/new", name="boutique_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $boutique = new Boutique();
        $form = $this->createForm('EmauxBundle\Form\BoutiqueType', $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($boutique);
            $em->flush();

            return $this->redirectToRoute('boutique_show', array('id' => $boutique->getId()));
        }

        return $this->render('boutique/new.html.twig', array(
            'boutique' => $boutique,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Boutique entity.
     *
     * @Route("/{id}", name="boutique_show")
     * @Method("GET")
     */
    public function showAction(Boutique $boutique)
    {
        $deleteForm = $this->createDeleteForm($boutique);

        return $this->render('boutique/show.html.twig', array(
            'boutique' => $boutique,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Boutique entity.
     *
     * @Route("/{id}/edit", name="boutique_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Boutique $boutique)
    {

        
        
        $deleteForm = $this->createDeleteForm($boutique);
        $editForm = $this->createForm('EmauxBundle\Form\BoutiqueType', $boutique);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($boutique);
            $em->flush();

            return $this->redirectToRoute('boutique_edit', array('id' => $boutique->getId()));
        }

        return $this->render('boutique/edit.html.twig', array(
            'boutique' => $boutique,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Edits an existing Photos entity.
     *
     * @Route("/{id}", name="boutique_update")
     * @Method("POST")
     * @Template("EmauxBundle:Images:edit.html.twig")
     */
    /*public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EmauxBundle:Boutique')->find($id);
//        $entity  = new Images();
        $form = $this->createForm(new ImagesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            

            $uploadedDirectory = $this->container->getParameter('kernel.root_dir').'/../web/uploads';
            $image=$entity->getImage();
            /*@var $image \Symfony\Component\HttpFoundation\File\UploadedFile */
           /* $image->move($uploadedDirectory, $image->getClientOriginalName());
            $entity->setImage($image->getClientOriginalName());
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();*/
            
//            $this->get('session')->setFlash('notice','Uploaded');
            
        //}   
             
            //return $this->redirect($this->generateUrl('upload_confirm', array('id' => $entity->getId())));
            
            
    //}


    /**
     * Deletes a Boutique entity.
     *
     * @Route("/{id}", name="boutique_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Boutique $boutique)
    {
        $form = $this->createDeleteForm($boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($boutique);
            $em->flush();
        }

        return $this->redirectToRoute('boutique_index');
    }

    /**
     * Creates a form to delete a Boutique entity.
     *
     * @param Boutique $boutique The Boutique entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Boutique $boutique)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('boutique_delete', array('id' => $boutique->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
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
    	->getRepository('EmauxBundle:Boutique')
    	->find($id);
    	return array(
    			'image' => $entity
    			);
    	
    	
    }
    
    
    /**
     * Edits an existing Photos entity.
     *
     * @Route("/{id}", name="boutique_update")
     * @Method("POST")
     * @Template("EmauxBundle:Boutique:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        
        
        $image2 = '';
        $ext = '';
        $em = $this->getDoctrine()->getManager();
        //$homepageSections = $em->getRepository('EmauxBundle:HomepageSections')->find($id);
        $Boutique = $em->getRepository('EmauxBundle:Boutique')->findOneBy(array('id' => $id));
        if($Boutique->getImage()){
          
            $image2 = $Boutique->getImage();
            //$path = $_FILES['image']['name'];
            $ext = pathinfo($image2, PATHINFO_EXTENSION);
            //return new Response($ext);
        }  
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('EmauxBundle:Boutique')->find($id);
        $form = $this->createForm(new BoutiqueType(), $entity);
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
             
        return $this->redirect($this->generateUrl('boutiqueAll_index', array('id' => $entity->getId())));
            
            
    }
    
    
    /**
     * Creates a new Photos entity.
     *
     * @Route("/", name="boutique_create")
     * @Method("POST")
     * @Template("EmauxBundle:Boutique:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Boutique();
        $form = $this->createForm(new BoutiqueType(), $entity);
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
            
            
             
            return $this->redirect($this->generateUrl('upload_confirm', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
    
   

    
}
