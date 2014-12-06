<?php

namespace VG\CartBundle\Utils;


use Moltin\Cart\Cart;
use Moltin\Cart\Storage\Session;
use Moltin\Cart\Identifier\Cookie;

class CartUtils
{
    public function getInfo()
    {
        $cart = new Cart(new Session(), new Cookie());

        return array(
            'totalItems' => $cart->totalItems(),
            'total' => $cart->total(),
        );
    }
} 