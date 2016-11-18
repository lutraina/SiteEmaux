<?php

namespace EmauxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EmauxBundle\Entity\Actualite;
use EmauxBundle\Form\ActualiteType;

/**
 * Actualite controller.
 *
 * @Route("/actualite")
 */
class ActualiteController extends Controller
{
    /**
     * Lists all Actualite entities.
     *
     * @Route("/", name="actualite_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $actualites = $em->getRepository('EmauxBundle:Actualite')->findAll();

        return $this->render('actualite/index.html.twig', array(
            'actualites' => $actualites,
        ));
    }

    /**
     * Creates a new Actualite entity.
     *
     * @Route("/new", name="actualite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $actualite = new Actualite();
        $form = $this->createForm('EmauxBundle\Form\ActualiteType', $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($actualite);
            $em->flush();

            return $this->redirectToRoute('actualite_show', array('id' => $actualite->getId()));
        }

        return $this->render('actualite/new.html.twig', array(
            'actualite' => $actualite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Actualite entity.
     *
     * @Route("/{id}", name="actualite_show")
     * @Method("GET")
     */
    public function showAction(Actualite $actualite)
    {
        $deleteForm = $this->createDeleteForm($actualite);

        return $this->render('actualite/show.html.twig', array(
            'actualite' => $actualite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Actualite entity.
     *
     * @Route("/{id}/edit", name="actualite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Actualite $actualite)
    {
        $deleteForm = $this->createDeleteForm($actualite);
        $editForm = $this->createForm('EmauxBundle\Form\ActualiteType', $actualite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($actualite);
            $em->flush();

            return $this->redirectToRoute('actualite_edit', array('id' => $actualite->getId()));
        }

        return $this->render('actualite/edit.html.twig', array(
            'actualite' => $actualite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Actualite entity.
     *
     * @Route("/{id}", name="actualite_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Actualite $actualite)
    {
        $form = $this->createDeleteForm($actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($actualite);
            $em->flush();
        }

        return $this->redirectToRoute('actualite_index');
    }

    /**
     * Creates a form to delete a Actualite entity.
     *
     * @param Actualite $actualite The Actualite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Actualite $actualite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('actualite_delete', array('id' => $actualite->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
