<?php

namespace VG\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('VGCatalogBundle:Section');
        $arrayTree = $repo->getRootNodes();
        $treeOneLevel = $repo->children($arrayTree[0], true);

        return array(
            'treeOneLevel' =>$treeOneLevel,
        );
    }
}
