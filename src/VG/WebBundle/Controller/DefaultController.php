<?php

namespace VG\WebBundle\Controller;

use Doctrine\Common\Collections\Criteria;
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

        //lastNews
        $category = $em->getRepository('VGContentBundle:Category')->findOneBy(
            array('alias' => 'novosti')
        );
        $criteria = new Criteria();
        $expr = $criteria->expr();
        $criteria
            ->where($expr->eq('category', $category))
            ->andWhere($expr->eq('status', 1))
            ->andWhere($expr->eq('showInList', 1))
            ->orderBy(array("created" => Criteria::DESC))
            ->setMaxResults(4);
        unset($category);
        $lastNews = $em->getRepository('VGContentBundle:Article')->matching($criteria);

        //bestProducts
        $criteria2 = new Criteria();
        $expr2 = $criteria2->expr();
        $criteria2
            ->where($expr2->eq('status', 1))
            ->andWhere($expr->eq('best', true))
            ->orderBy(array("id" => Criteria::DESC))
            ->setMaxResults(10);
        $bestProducts = $em->getRepository('VGProductBundle:Product')->matching($criteria2);

        //promo
        $promo = $em->getRepository('VGContentBundle:Article')->findOneBy(array('slug'=>'homepage'));

        return array(
            'lastNews' => $lastNews,
            'bestProducts' => $bestProducts,
            'promo' => $promo,
        );
    }
}
