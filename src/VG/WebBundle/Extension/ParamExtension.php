<?php

namespace VG\WebBundle\Extension;

use VG\WebBundle\Listener\ParamListener;

class ParamExtension extends \Twig_Extension{
    protected $listener;

    public function __construct(ParamListener $listener)
    {
        $this->listener = $listener;
    }

    public function getName()
    {
        return 'params';
    }

    public function getFunctions()
    {
        return array(
            'param' => new \Twig_Function_Method($this, 'getParams')
        );
    }

    public function getParams()
    {
        return $this->listener->getParams();
    }
}
