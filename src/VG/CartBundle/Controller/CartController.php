<?php

namespace VG\CartBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use Moltin\Cart\Cart;
use Moltin\Cart\Storage\Session;
use Moltin\Cart\Identifier\Cookie;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use VG\CartBundle\Form\Type\CheckoutType;

class CartController extends Controller
{
    /**
     * @Route("/cart/view", name="cart")
     * @Template()
     */
    public function viewAction(Request $request)
    {
        $item = false;

        if (null !== $request->request->get('id')) {
            $item = array(
                'id' => $request->request->get('id'),
                'name' => $request->request->get('name'),
                'price' => $request->request->get('price'),
                'quantity' => $request->request->get('quantity')
            );
        }


        $session = new Session();

        $cart = new Cart($session, new Cookie);

        $quantity = $request->request->get('quantity');
        if (is_array($request->request->get('quantity'))) {
            foreach ($cart->contents() as $product) {
                $product->update('quantity', $quantity[$product->id]);
            }
        }

        if ($item) {
            $cart->insert($item);
        }

        $session->save();

        $productsInCart = array();

        $em = $this->getDoctrine()->getManager();

        $productRepo = $em->getRepository('VGProductBundle:Product');

        foreach ($cart->contents() as $item) {
            $product = $item->toArray();
            $productsInCart[] = array_merge(
                $product,
                array('entity' => $productRepo->find($product['id']))
            );
        }

        $total = $cart->total();
        $totalItems = $cart->totalItems();

        return array(
            'productsInCart' => $productsInCart,
            'total' => $total,
            'totalItems' => $totalItems,
        );
    }

    /**
     * @Route("/cart/checkout", name="checkout")
     * @Template()
     */
    public function checkoutAction(Request $request)
    {
        $thank = false;

        $session = new Session();

        $cart = new Cart($session, new Cookie);
        $totalItems = $cart->totalItems();
        $total = $cart->total();
        $productsInCart = [];

        $em = $this->getDoctrine()->getManager();
        $productRepo = $em->getRepository('VGProductBundle:Product');
        foreach ($cart->contents() as $item) {
            $product = $item->toArray();
            $productsInCart[] = array_merge(
                $product,
                array('slug' => $productRepo->find($product['id'])->getSlug())
            );
        }

        $form = $this->createForm(new CheckoutType());

        $form
            ->add(
                'productsInCart',
                'hidden',
                array(
                    'data' => serialize($productsInCart),
                )
            )
            ->add(
                'totalItems',
                'hidden',
                array(
                    'data' => $totalItems,
                )
            )
            ->add(
                'total',
                'hidden',
                array(
                    'data' => $total,
                )
            );

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Оформлен заказ!')
                    ->setFrom($form->get('email')->getData())
                    ->setTo('contact@example.com')
                    ->setBody(
                        $this->renderView(
                            '@VGCart/Mail/mailToAdmin.html.twig',
                            array(
                                'ip' => $request->getClientIp(),
                                'name' => $form->get('name')->getData(),
                                'email' => $form->get('email')->getData(),
                                'phone' => $form->get('phone')->getData(),
                                'message' => $form->get('message')->getData(),
                                'productsInCart' => unserialize($form->get('productsInCart')->getData()),
                                'totalItems' => $form->get('totalItems')->getData(),
                                'total' => $form->get('total')->getData(),
                            )
                        )
                    );

                $this->get('mailer')->send($message);

                $cart->destroy();
                $session->save();
                $productsInCart = [];
                $totalItems = 0;
                $total = 0;
                $thank = true;
                //$request->getSession()->getFlashBag()->add('success', 'Заказ оформлен! Спасибо за покупку!');

                //return $this->redirect($this->generateUrl('th'));
            }
        }

        return array(
            'productsInCart' => $productsInCart,
            'totalItems' => $totalItems,
            'total' => $total,
            'form' => $form->createView(),
            'thank' => $thank,
        );

    }
}
