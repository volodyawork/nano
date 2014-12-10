<?php

namespace VG\ProductBundle\Controller;

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

}
