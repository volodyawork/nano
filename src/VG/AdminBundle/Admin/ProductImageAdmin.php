<?php

namespace VG\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class ProductImageAdmin extends Admin
{
// setup the default sort column and order
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'id'
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('image', 'sonata_type_model_list', array(
                    'required' => false
                ), array(
                    'link_parameters' => array(
                        'context' => 'product',
                        'provider' => 'sonata.media.provider.image',
                    )
                ))
            ->add('position', 'hidden')
        ;
    }


} 