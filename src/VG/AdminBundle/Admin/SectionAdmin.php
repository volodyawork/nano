<?php

namespace VG\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
//use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;


use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;


class SectionAdmin extends Admin
{
    protected $maxPerPage = 2500;
    protected $maxPageLinks = 2500;

    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'lft'
    );

    public function createQuery($context = 'list')
    {
        $em = $this->modelManager->getEntityManager('VG\CatalogBundle\Entity\Section');

        $queryBuilder = $em
            ->createQueryBuilder('s')
            ->select('s')
            ->from('VGCatalogBundle:Section', 's')
            ->where('s.parent IS NOT NULL');

        $query = new ProxyQuery($queryBuilder);

        return $query;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('up', 'text', array('template' => 'VGAdminBundle:Section:field_tree_up.html.twig', 'label' => ' '))
            ->add(
                'down',
                'text',
                array('template' => 'VGAdminBundle:Section:field_tree_down.html.twig', 'label' => ' ')
            )
            ->add('id', null, array('sortable' => false))
            ->addIdentifier('laveled_title', null, array('sortable' => false, 'label' => 'Название'))
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array(),
                    ),
                    'label' => 'Действия'
                )
            );
    }

    protected function configureFormFields(FormMapper $form)
    {
        $subject = $this->getSubject();
        $id = $subject->getId();

        $form
            ->add(
                'parent',
                null,
                array(
                    'label' => 'Родитель',
                    'required' => true,
                    'query_builder' => function ($er) use ($id) {
                            $qb = $er->createQueryBuilder('s');
                            if ($id) {
                                $qb
                                    ->where('s.id <> :id')
                                    ->setParameter('id', $id);
                            }
                            $qb
                                ->orderBy('s.root, s.lft', 'ASC');

                            return $qb;
                        }
                )
            )
            ->add('name', null, array('label' => 'Название'))
            ->add('description', 'redactor', array('redactor' => 'admin','label' => 'Описание категории', 'required' => false))
            ->end();
    }

    public function preRemove($object)
    {
        $em = $this->modelManager->getEntityManager($object);
        $repo = $em->getRepository("VGCatalogBundle:Section");
        $subtree = $repo->childrenHierarchy($object);
        foreach ($subtree AS $el) {
            $services = $em->getRepository('VGProductBundle:Product')
                ->findBy(array('section' => $el['id']));
            foreach ($services AS $s) {
                $em->remove($s);
            }
            $em->flush();
        }

        $repo->verify();
        $repo->recover();
        $em->flush();
    }

    public function postPersist($object)
    {
        $em = $this->modelManager->getEntityManager($object);
        $repo = $em->getRepository("VGCatalogBundle:Section");
        $repo->verify();
        $repo->recover();
        $em->flush();
    }

    public function postUpdate($object)
    {
        $em = $this->modelManager->getEntityManager($object);
        $repo = $em->getRepository("VGCatalogBundle:Section");
        $repo->verify();
        $repo->recover();
        $em->flush();
    }

    public function configure()
    {
        $this->setTemplate('edit', 'VGAdminBundle:CRUD:edit_add_for_redactor.html.twig');
    }
} 