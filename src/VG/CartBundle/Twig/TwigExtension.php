<?php

namespace VG\CartBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class TwigExtension extends \Twig_Extension
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'vgcart_head' => new \Twig_Function_Method($this, 'getCartHead', array('is_safe' => array('html'))),
            'vgcart_buy' => new \Twig_Function_Method($this, 'getBuy'),
            'vgcart_cart' => new \Twig_Function_Method($this, 'getCart'),
        );
    }

    public function getCartHead()
    {
        return $this->container->get('templating')->render('VGCartBundle::head.html.twig', array());
    }

    public function getBuy($parameters = false)
    {
        return $this->container->get('templating')->render('VGCartBundle::buy.html.twig', array('parameters'=>$parameters));
    }

    public function getCart($parameters = false)
    {
        $parameters = $this->container->get('vgcart.utils')->getInfo();
        return $this->container->get('templating')->render('VGCartBundle::cart.html.twig', array('cartInfo'=>$parameters));
    }

    public function getName()
    {
        return 'vgcart_extension';
    }
}