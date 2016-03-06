<?php

namespace VG\WebBundle\Listener;

use Doctrine\ORM\EntityManager;

class ParamListener
{

    protected $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function onKernelRequest()
    {

    }

    public function getParams()
    {
        $repo = $this->em->getRepository('VGWebBundle:Param');
        $params = $repo->findAll();
        $param = [];
        foreach ($params as $p) {
            $param[$p->getSlug()] = $p->getValue();
        }
        return $param;
    }
} 