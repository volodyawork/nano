<?php

namespace VG\ProductBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VG\CatalogBundle\Entity\Section;
use VG\ProductBundle\Entity\Product;
use Doctrine\ORM\QueryBuilder;

/**
 * ProductPublic controller.
 *
 * @Route("/product")
 */
class ProductPublicController extends Controller
{

    /**
     * Lists all Product entities.
     *
     * @Route("/section/{slug}/{page}", name="product_section", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($slug, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $section = $em->getRepository('VGCatalogBundle:Section')->findOneBy(array('slug' => $slug));

        $repo = $em->getRepository('VGCatalogBundle:Section');
        $arrayPath = $repo->getPath($section);

        $arrayTree = $repo->getChildren($section, true);


        $allChildren = $repo->getChildren($section, false);

        $in = array($section->getId());
        foreach ($allChildren as $child) {
            array_push($in, $child->getId());
        }

        $expr = Criteria::expr();
        $criteria = Criteria::create()
            ->where($expr->in('section', $in))
            ->andWhere($expr->eq('status', 1))
            ->orderBy(array("id" => Criteria::DESC));

        $countQuery = $em->getRepository('VGProductBundle:Product')->createQueryBuilder('Product');
        $countQuery->select('COUNT(Product)');
        $countQuery->addCriteria($criteria);

        $total = $countQuery->getQuery()->getSingleScalarResult();

        $per_page = $this->container->getParameter('max_products_on_listpage');
        if (0 == $section->getLvl()){
            $per_page = 20;
        }

        $last_page = ceil($total / $per_page);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;



        if ($per_page) {
            $criteria = $criteria->setMaxResults($per_page);
        }
        $offset = ($page - 1) * $per_page;
        if ($offset) {
            $criteria = $criteria->setFirstResult($offset);
        }

        $entities = $em->getRepository('VGProductBundle:Product')->matching($criteria);


        return array(
            'entities' => $entities,
            'arrayPath' => $arrayPath,
            'section' => $section,
            'arrayTree' => $arrayTree,
            'last_page' => $last_page,
            'previous_page' => $previous_page,
            'current_page' => $page,
            'next_page' => $next_page,
            'total' => $total

        );
    }


    /**
     * Finds and displays a Product entity.
     *
     * @Route("/detail/{slug}", name="product_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VGProductBundle:Product')->findOneBy(array('slug' => $slug));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }


        $repo = $em->getRepository('VGCatalogBundle:Section');
        $arrayPath = $repo->getPath($entity->getSection());

        $media = [];
        foreach ($entity->getImages() as $productImage){
            $media[] = $productImage->getImage();
        }

        return array(
            'entity' => $entity,
            'arrayPath' => $arrayPath,
            'media' => $media,
        );
    }

    /**
     * Sales Product entities.
     *
     * @Route("/sale/{page}", name="product_sale", requirements={"page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     */
    public function saleAction($page)
    {
        $em = $this->getDoctrine()->getManager();

        $expr = Criteria::expr();
        $criteria = Criteria::create()
            ->where($expr->eq('sale', 1))
            ->andWhere($expr->eq('status', 1))
            ->orderBy(array("id" => Criteria::DESC));

        $countQuery = $em->getRepository('VGProductBundle:Product')->createQueryBuilder('Product');
        $countQuery->select('COUNT(Product)');
        $countQuery->addCriteria($criteria);

        $total = $countQuery->getQuery()->getSingleScalarResult();

        $per_page = $this->container->getParameter('max_products_on_listpage');


        $last_page = ceil($total / $per_page);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;



        if ($per_page) {
            $criteria = $criteria->setMaxResults($per_page);
        }
        $offset = ($page - 1) * $per_page;
        if ($offset) {
            $criteria = $criteria->setFirstResult($offset);
        }

        $entities = $em->getRepository('VGProductBundle:Product')->matching($criteria);


        return $this->render('VGProductBundle:ProductPublic:sale.html.twig', array(
            'entities' => $entities,
            'last_page' => $last_page,
            'previous_page' => $previous_page,
            'current_page' => $page,
            'next_page' => $next_page,
            'total' => $total

        ));
    }

}
