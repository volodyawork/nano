<?php

namespace VG\WebBundle\Listener;

use Doctrine\ORM\EntityManager;

class CatalogMenuListener {

    protected $em;
    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onKernelRequest(){

    }
    public function getCatalogMenu(){

        $repo = $this->em->getRepository('VGCatalogBundle:Section');
        $arrayTree = $repo->getRootNodes();
        $treeOneLevel = $repo->children($arrayTree[0], true);
        return $treeOneLevel;
    }
} 