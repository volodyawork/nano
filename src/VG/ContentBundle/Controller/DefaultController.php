<?php

namespace VG\ContentBundle\Controller;

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
     * @Route("/", name="article_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VGContentBundle:Article')->findAll();

        return array(
            'entities' => $entities,
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



        return array(
            'entity'      => $entity,
        );
    }
}
