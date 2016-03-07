<?php

namespace VG\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class ManufacturerAdmin extends Admin
{
// setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'id'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('label' => 'Название параметра'))
            ->add(
                'status',
                'choice',
                array(
                    'choices' => array(0 => 'Не активный', 1 => 'Активный'),
                    'label' => 'Статус',
                    'preferred_choices' => array(1)
                )
            )
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'Название'))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->addIdentifier(
                'status',
                'choice',
                array(
                    'choices' => array(0 => 'Не активный', 1 => 'Активный'),
                    'label' => 'Статус',
                    'preferred_choices' => array(1)
                )
            )
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                        'edit' => array(),
                    )
                )
            )
        ;
    }
} 