<?php

namespace VG\CatalogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use JMS\SecurityExtraBundle\Annotation\Secure;

class SectionTreeSortController extends Controller
{
    // @Secure(roles="ROLE_SUPER_ADMIN")
    /**
     * @Route(path="/admin/section_tree_up/{section_id}", name="section_tree_up")
     */
    public function upAction($section_id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('VGCatalogBundle:Section');
        $section = $repo->findOneById($section_id);
        if ($section->getParent()){
            $repo->moveUp($section);
        }
        return $this->redirect($this->container->get('request_stack')->getCurrentRequest()->headers->get('referer'));
    }

    /**
     * @Route(path="/admin/section_tree_down/{section_id}", name="section_tree_down")
     */
    public function downAction($section_id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('VGCatalogBundle:Section');
        $section = $repo->findOneById($section_id);
        if ($section->getParent()){
            $repo->moveDown($section);
        }
        return $this->redirect($this->container->get('request_stack')->getCurrentRequest()->headers->get('referer'));
    }
}