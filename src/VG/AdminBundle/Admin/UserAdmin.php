<?php

namespace VG\AdminBundle\Admin;

use Gaufrette\Util\Path;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{

    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'id'
    );



    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add(
                'email',
                null,
                array('label' => 'E-mail', 'required' => true)
            )#            ->add('name', null, array('label' => 'Заголовок'))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add(
                'email',
                null,
                array('label' => 'email')
            );
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('email')
            ->add(
                '_action',
                'actions',
                array(
                    'actions' => array(
                   //     'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    )
                )
            );
    }

}