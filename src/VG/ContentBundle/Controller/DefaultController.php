<?php

namespace VG\ContentBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VG\ContentBundle\Entity\Article;
use VG\ContentBundle\Form\ArticleType;

/**
 * Article controller.
 *
 * @Route("/article")
 */
class DefaultController extends Controller
{

    /**
     * Lists all Article entities.
     *
     * @Route("/{category_name}/{page}", name="article_index", requirements={"category_name" = "novosti|pages", "page" = "\d+"}, defaults={"page" = 1})
     * @Method("GET")
     * @Template()
     */
    public function indexAction($category_name, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('VGContentBundle:Category')->findOneBy(
            array('alias' => $category_name)
        );


        $total = count(
            $em->getRepository('VGContentBundle:Article')->findBy(
                array('category' => $category, 'status' => 1, 'showInList' => 1)
            )
        ); // todo count


        $per_page = $this->container->getParameter('max_articles_on_listpage');

        $last_page = ceil($total / $per_page);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;

        $expr = Criteria::expr();
        $criteria = Criteria::create()
            ->where($expr->eq('category', $category))
            ->andWhere($expr->eq('status', 1))
            ->andWhere($expr->eq('showInList', 1))
            ->orderBy(array("created" => Criteria::DESC));

        if ($per_page) {
            $criteria = $criteria->setMaxResults($per_page);
        }
        $offset = ($page - 1) * $per_page;
        if ($offset) {
            $criteria = $criteria->setFirstResult($offset);
        }

        $entities = $em->getRepository('VGContentBundle:Article')->matching($criteria);

        return array(
            'entities' => $entities,
            'category_name' => $category_name,
            'last_page' => $last_page,
            'previous_page' => $previous_page,
            'current_page' => $page,
            'next_page' => $next_page,
            'total' => $total
        );
    }

    /**
     * Finds and displays a Article entity.
     *
     * @Route("/{id}", name="article_detail")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VGContentBundle:Article')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Article entity.');
        }

        $category_name = $entity->getCategory()->getAlias();

        return array(
            'entity' => $entity,
            'category_name' => $category_name
        );
    }
}
