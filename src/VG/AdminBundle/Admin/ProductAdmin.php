<?php

namespace VG\AdminBundle\Admin;

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
            ->add('description', 'redactor', array('redactor' => 'admin','label' => 'Описание товара'))
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
            ->add('images', 'sonata_type_collection', array(
                    'required' => false
                ), array(
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable'  => 'position',
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
            ->add(
                'name',
                null,
                array('label' => 'Название')
            )//    ->add('created', 'doctrine_orm_datetime_range', array('input_type' => 'timestamp'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->addIdentifier('id')
            ->addIdentifier('status')
            ->addIdentifier('price')
            ->addIdentifier('best')
            ->add(
                '_action',
                'actions',
                array(
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

} 