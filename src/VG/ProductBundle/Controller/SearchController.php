<?php

namespace VG\ProductBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use VG\ProductBundle\Entity\Product;
use VG\ProductBundle\Form\ProductType;


use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Response;
use Elastica\Document;
use Elastica\Facet\Query as FacetQuery;
use Elastica\Query;
use Elastica\Type;
use Elastica\Query\Term;
use Elastica\Test\Base as BaseTest;
use Elastica\Client;

//use Pagerfanta\Pagerfanta;

class SearchController extends Controller
{

    private $_nbresult = 15;

    /**
     *
     * @Route("/search", name="search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $query = $request->get('s', '*');
        if ('' === $query) {
            $query = '*';
        }
        $page = $request->get('page_num', 1);


        $index = $this->container->get('fos_elastica.index.nano.product');
        $finder = $this->container->get('fos_elastica.finder.nano.product');

        // Define a Query. We want a string query.
        $elasticaQueryString = new \Elastica\Query\QueryString();
        $elasticaQueryString->setQuery($query);
        // Create the actual search object with some data.
        $elasticaQuery = new \Elastica\Query();
        $elasticaQuery->setQuery($elasticaQueryString);


        // Pagination
        $elasticaQuery->setFrom(($page - 1) * $this->_nbresult); // Where to start?
        $elasticaQuery->setSize($this->_nbresult); // How many?

        // Search and get facets from elasticaResults
        $elasticaSearch = $index->search($elasticaQuery);

        // Add filters

        //Search on the finder.
        $elasticaResultSet = $finder->find($elasticaQuery);
        $data = $elasticaSearch->getResponse()->getData();
        $total = $data['hits']['total'];

        $last_page = ceil($total / $this->_nbresult);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;

        return
            array(
                'results' => $elasticaResultSet,
                'query' => $query,
                'page_num' => $page,
                'page_count' => $last_page,
                'last_page' => $last_page,
                'previous_page' => $previous_page,
                'current_page' => $page,
                'next_page' => $next_page,
                'total' => $total
            );
    }

    /**
     *
     * @Route("/search2", name="search2")
     * @Method("GET")
     * @Template()
     */
    public function search2Action()
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $query = $request->get('s', '*');
        if ('' === $query) {
            $query = '*';
        }
        $page = $request->get('page_num', 1);

        $em = $this->getDoctrine()->getManager();


        $countQuery = $em->getRepository('VGProductBundle:Product')->createQueryBuilder('p');
        $countQuery->select('COUNT(p)');
        if ($query != '*'){
            $countQuery = $countQuery->where('p.status = 1 AND p.name LIKE :input')
                ->setParameters(array(
                    'input' => "%".$query."%"));
        } else {
            $countQuery = $countQuery->where('p.status = 1');
        }

        $total = $countQuery->getQuery()->getSingleScalarResult();
        $per_page = $this->container->getParameter('max_products_on_listpage');
        $offset = ($page - 1) * $per_page;

        $qb = $em->getRepository('VGProductBundle:Product')->createQueryBuilder('p');
        if ($query != '*'){
            $qb = $qb->where('p.status = 1 AND p.name LIKE :input')
                ->setParameters(array(
                    ':input' => "%".$query."%"));
        } else {
            $qb = $qb->where('p.status = 1');
        }
        if ($per_page) {
            $qb = $qb->setFirstResult($offset);
            $qb = $qb->setMaxResults($per_page);
        }

            $qb = $qb->orderBy('p.id', 'DESC')
            ->getQuery();


        $entities = $qb->getResult();


        $last_page = ceil($total / $per_page);
        $previous_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $last_page ? $page + 1 : $last_page;

        return
            array(
                'results' => $entities,
                'query' => $query,
                'page_num' => $page,
                'page_count' => $last_page,
                'last_page' => $last_page,
                'previous_page' => $previous_page,
                'current_page' => $page,
                'next_page' => $next_page,
                'total' => $total
            );
    }

}
