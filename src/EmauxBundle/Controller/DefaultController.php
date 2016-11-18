<?php

/*
 * Defaut gère : 
 *      la vue de l'admin avec le menu, 
 *      la page boutique, 
 *      la page les produits 
 *      et la page les créatrices
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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use EmauxBundle\Entity\Boutique;
use EmauxBundle\Form\BoutiqueType;
use EmauxBundle\Entity\Images;
use EmauxBundle\Entity\Actualite;
use EmauxBundle\Form\ActualiteType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="accueil_site")
     */
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getManager();

        $homepageSections = $em->getRepository('EmauxBundle:HomepageSections')->findAll();
        $produits = $em->getRepository('EmauxBundle:Images')->findBy(array(), array('id' => 'ASC'),4);
        $actualite = $em->getRepository('EmauxBundle:Actualite')->findBy(array(), array('id' => 'ASC'),1);

  
		$user = $this->getUser();

        
        return $this->render('EmauxBundle:Default:index.html.twig', array(
            'homepageSections' => $homepageSections,
            'produits' => $produits,
            'actualite' => $actualite,
            'user' => $user
        ));
    }
    
    
    
    
    /**
     * Lists all Boutique entities.
     *
     * @Route("/magasin", name="boutique_index")
     */
    public function boutiqueAction()
    {
        $em = $this->getDoctrine()->getManager();

        $boutiques = $em->getRepository('EmauxBundle:Boutique')->findAll();
        $produits = '';
        return $this->render('EmauxBundle:Boutique:index.html.twig', array(
            'boutiques' => $boutiques,
            'produits' => $produits,
        ));
    }
    
    /**
     * Lists all Boutique entities.
     *
     * @Route("/admin", name="admin_index")
     */
    public function adminAction()
    {
    	
    	if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
      return $this->render('EmauxBundle:Default:admin.html.twig');
    } else { return $this->render('OCUserBundle:Security:login.html.twig', array(
            'error' => '',
            'last_username' => '',
        ));
    	}
    
    
        //return $this->render('EmauxBundle:Default:admin.html.twig');
    }
    
    
     /**
     * Lists all Produits entities.
     *
     * @Route("/produits", name="produits_index")
     */
    public function produitsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('EmauxBundle:Images')->findAll();

        return $this->render('EmauxBundle:Default:produits.html.twig', array(
            'produits' => $produits,
        ));
    }
    
    /**
     * Lists all Produits entities.
     *
     * @Route("/creatrices", name="creatrices_index")
     */
    public function creatricesAction()
    {
         

        return $this->render('EmauxBundle:Default:creatrices.html.twig');
    }
    
    
    
    
}
