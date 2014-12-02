<?php

namespace VG\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Choice;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', null, array('label' => 'Заголовок'))
            //->add('alias')
            ->add(
                'content',
                'ckeditor',
                array(
                    'config' => array(//'filebrowserUploadRoute' => 'my_route'
                    ),
                    'label' => 'Текст'

                )
            )
            /*->add(
                'content',
                'ckeditor',
                array(
                    #'transformers'                 => array('html_purifier'),
                    'transformers' => array(),
                    //'toolbar'                      => array('document','basicstyles'),
                    //'toolbar_groups'               => array(
                    // 'document' => array('Source')
                    //),
                    'ui_color' => '#fff',
                    'startup_outline_blocks' => false,
                    'width' => '100%',
                    'height' => '320',
                    'language' => 'ru-ru',
                    //'filebrowser_image_browse_url' => array(
                    //    'url' => 'relative-url.php?type=file',
                    //),
                    'filebrowser_upload_url' => array(
                        'route' => 'filebrowser_upload_url',
                        'route_parameters' => array(
                            'type' => 'image',
                        ),
                    ),
                    //'filebrowser_image_browse_url' => array('url'=>'b.php'),
                    'filebrowser_image_browse_url' => array(
                        'url' => 'relative-url.php?type=file'
                    ),
                    'label' => 'Текст',
                    'attr' => array(
                        'rich' => 'TinyMCE',
                    ),
                )
            )*/
            ->add(
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
            ->add(
                'showInList',
                'choice',
                array(
                    'label' => 'Показывать в списке всех материалов:',
                    'choices' => array(
                        0 => 'Нет',
                        1 => 'Да'
                    ),
                    'preferred_choices' => array(1),
                )
            )
            //->add('created')
            //->add('updated')
            ->add('category', null, array('label' => 'Категория'))// ->add('user')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'VG\ContentBundle\Entity\Article'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vg_contentbundle_article';
    }
}
