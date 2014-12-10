<?php
namespace VG\ProductBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    /**
     * for elastica
     *
     * @return QueryBuilder
     */
    public function createIsActiveQueryBuilder()
    {
        $queryBuilder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('VGProductBundle:Product', 'p')
            ->where('p.status=1')
            ->orderBy('p.id', 'DESC');
        return $queryBuilder;
    }
}