<?php

namespace VG\CartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                    'attr' => array(
                        'placeholder' => 'Как Вас зовут?',
                        'pattern'     => '.{2,}' //minlength
                    )
                ))
            ->add('email', 'email', array(
                    'attr' => array(
                        'placeholder' => 'Введите ваш email'
                    )
                ))
            ->add('phone', 'text', array(
                    'attr' => array(
                        'placeholder' => 'Введите Ваш телефон',
                        'pattern'     => '.{6,}' //minlength
                    )
                ))
            ->add('message', 'textarea', array(
                    'attr' => array(
                        'cols' => 90,
                        'rows' => 10,
                        'placeholder' => 'Ваше сообщение'
                    )
                ))
            ->add('submit', 'submit', array('label' => 'Оформить заявку'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $resolver->setDefaults(array(
//                'validation_groups' => false,
            ));

    }

    public function getName()
    {
        return 'checkout';
    }
}