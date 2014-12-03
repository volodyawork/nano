<?php

namespace VG\WebBundle\Extension;

use VG\WebBundle\Listener\AskListener;

class AskExtension extends \Twig_Extension{
    protected $listener;

    public function __construct(AskListener $listener)
    {
        $this->listener = $listener;
    }

    public function getName()
    {
        return 'ask_form';
    }

    public function getFunctions()
    {
        return array(
            'askForm' => new \Twig_Function_Method($this, 'getAskForm')
        );
    }

    public function getAskForm()
    {
        return $this->listener->getAskForm();
    }
}