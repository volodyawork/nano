<?php

namespace VG\WebBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('/contact/ask_worker') // todo generate url from name="ask_form"
            ->add(
                'message',
                'textarea',
                [
                    'attr' => [
                        'maxlength' => 500,
                        'placeholder' => ''
                    ]
                ]
            )
            ->add(
                'email',
                'email',
                [
                    'attr' => [
                        'maxlength' => 50,
                        'placeholder' => ''
                    ]
                ]
            )
            ->add(
                'username',
                'text',
                [
                    'attr' => [
                        'placeholder' => ''
                    ]
                ]
            )
            ->add(
                'save',
                'submit',
                [
                    'label' => 'Send',
                    'attr' => [
                        'class' => 'question_submit',
                        'value' => 'Отправить'
                    ]
                ]
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $collectionConstraint = new Collection(array(
            'email' => array(
                new NotBlank(array('message' => 'Email should not be blank.')),
                new Email(array('message' => 'Invalid email address.'))
            ),
            'username' => array(
                new NotBlank(array('message' => 'Email should not be blank.')),
            ),
            'message' => array(
                new NotBlank(array('message' => 'Message should not be blank.')),
                new Length(array('min' => 5, 'minMessage' => 'Please enter your message'))
            )
        ));

        $resolver->setDefaults(
            array(
                'constraints' => $collectionConstraint
            )
        );
    }

    public function getName()
    {
        return 'contact';
    }

} 