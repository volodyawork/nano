<?php

namespace VG\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends Admin
{
// setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'created'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('category', null, array('label' => 'Категория', 'required' => true))
            ->add('name', null, array('label' => 'Заголовок'))
            ->add('content', 'redactor', array('redactor' => 'admin', 'label' => 'Описание товара'))
            ->add(
                'showInList',
                'choice',
                array(
                    'choices' => array(0 => 'Нет', 1 => 'Да'),
                    'label' => 'Показывать на странице списка новостей/страниц?',
                    'preferred_choices' => array(1),
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
            ->addIdentifier('created')
            ->addIdentifier('status')
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

    public function configure()
    {
        $this->setTemplate('edit', 'VGAdminBundle:CRUD:edit_add_for_redactor.html.twig');
    }
} 