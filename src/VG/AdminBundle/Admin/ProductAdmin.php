<?php

namespace VG\AdminBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Doctrine\ORM\EntityManager;


class ProductAdmin extends Admin
{
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }


// setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'id'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('section', null, array('label' => 'Раздел', 'required' => true))
            ->add('manufacturer', null, array('label' => 'Производитель', 'required' => false))
            ->add(
                'section',
                null,
                array(
                    'label' => 'Родитель (Раздел)',
                    'required' => true,
                    'query_builder' => function ($er) {
                        $qb = $er->createQueryBuilder('s');
                        $qb
                            ->where('s.lvl <> 0')
                            ->orderBy('s.root, s.lft', 'ASC');

                        return $qb;
                    }
                )
            )
            ->add('name', null, array('label' => 'Название товара'))
            ->add('marking', null, array('label' => 'Артикул'))
            ->add('description', 'redactor', array('redactor' => 'admin', 'label' => 'Описание товара'))
            ->add(
                'price',
                null,
                array(
                    'label' => 'Цена',
                )
            )
            ->add(
                'status',
                'choice',
                array(
                    'choices' => array(0 => 'Черновик', 1 => 'Опубликовано'),
                    'label' => 'Статус',
                    'preferred_choices' => array(0)
                )
            )
            ->add(
                'best',
                null,
                array(
                    'label' => 'Метка "Лучший товар"',
                )
            )
            ->add(
                'sale',
                null,
                array(
                    'label' => 'Акция',
                )
            )
            ->add('images', 'sonata_type_collection', array(
                'required' => false
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ))
            ->add(
                'slug',
                null,
                array('label' => 'Slug (alias). Для ЧПУ. Генерируется автоматически если не заполнить.')
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Название'))
            ->add('manufacturer', null, array('label' => 'Производитель'))
            //->add('section', null, array('label' => 'Раздел'))
            /*->add('section2', 'doctrine_orm_string', array(),
                'choice', array('choices' => [2=>'ss',3=>'aa'])
            )*/
            ->add('section', 'doctrine_orm_callback',
                array(
                    'callback' => function ($queryBuilder, $alias, $field, $value) {
                        if (!$value['value']) {
                            return;
                        }
                        $query = $queryBuilder;
                        $filter = $this->getRequest()->get('filter');
                        if ($filter && isset($filter['section']['value'])) {
                            $sectionId = $this->getRequest()->get('filter')['section']['value'];
                            $section = $this->em->getRepository('VGCatalogBundle:Section')->findOneBy(array('id' => $sectionId));
                            if ($section) {
                                $repo = $this->em->getRepository('VGCatalogBundle:Section');
                                $allChildren = $repo->getChildren($section, false);

                                $in = array($section->getId());
                                foreach ($allChildren as $child) {
                                    array_push($in, $child->getId());
                                }
                                $query->andWhere($query->getRootAlias() . '.section in (' . implode(',', $in) . ')');
                            }
                        }
                        return true;
                    },
                    'label' => 'Раздел',
                    'field_type' => 'choice',
                ),
                'choice',
                array('choices' => $this->getSelectTree()));
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name', null,
                ['label' => 'Название',]
            )
            ->add('manufacturer', null,
                ['label' => 'Производитель',]
            )
            ->add('section', null,
                ['label' => 'Раздел',]
            )
            ->addIdentifier(
                'status',
                'choice',
                array(
                    'label' => 'Статус',
                    'choices' => array(
                        0 => 'Черновик',
                        1 => 'Активный'
                    ),
                    'preferred_choices' => array(0),
                )
            )
            ->add('price', null,
                ['label' => 'Цена',]
            )
            ->add('best', null, array('editable' => true))
            ->add('sale', null, array('editable' => true))
            ->add(
                '_action',
                'actions',
                array(
                    'label' => 'Действия',
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    )
                )
            );
    }

    public function postPersist($object)
    {
        foreach ($object->getImages() as $image) {
            $image->setProduct($object);

            $this->em->persist($image);
            $this->em->flush();
        }
    }

    public function preUpdate($object)
    {
        foreach ($object->getImages() as $image) {
            $image->setProduct($object);
        }
    }

    public function configure()
    {
        $this->setTemplate('edit', 'VGAdminBundle:CRUD:edit_add_for_redactor.html.twig');
    }

    public function getSelectTree()
    {
        $root = $this->em->getRepository('VGCatalogBundle:Section')->find(1);
        $items = $this->em->getRepository('VGCatalogBundle:Section')->getChildren($root, false);
        $result = [];
        foreach ($items as $item) {
            $result[$item->getId()] = $item;
        }
        return $result;
    }
}