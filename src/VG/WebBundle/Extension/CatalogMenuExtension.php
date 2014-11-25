<?php

namespace VG\WebBundle\Extension;

use VG\WebBundle\Listener\CatalogMenuListener;

class CatalogMenuExtension extends \Twig_Extension{
    protected $listener;

    public function __construct(CatalogMenuListener $listener)
    {
        $this->listener = $listener;
    }

    public function getName()
    {
        return 'catalog_menu';
    }

    public function getFunctions()
    {
        return array(
            'treeOneLevel' => new \Twig_Function_Method($this, 'getCatalogMenu')
        );
    }

    public function getCatalogMenu()
    {
        return $this->listener->getCatalogMenu();
    }
}
