<?php

namespace VG\WebBundle\Util;

use VG\WebBundle\Form\Type\ContactType;
use Swift_Message;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ContactHelper
{
    private $_container;

    public function __construct(
        Container $serviceContainer
    )
    {
        $this->_container = $serviceContainer;
    }

    public function getContactForm()
    {
        //$request = $this->_container->get('request');

        $contactData = [];
        $contactType = new ContactType();
        $contactForm = $this->_container->get('form.factory')->create($contactType, $contactData);

        //$contactForm->handleRequest($request);

        return $contactForm;
    }

    public function handleRequestContactForm()
    {
        $request = $this->_container->get('request');

        $contactForm = $this->getContactForm();

        $contactForm->handleRequest($request);

        return $contactForm;
    }

    /**
     * Sends email if form is valid
     *
     * @return null|RedirectResponse
     */
    public function sendContactForm($contactForm)
    {
        if ($contactForm->isValid()) {
            $data = $contactForm->getData();
            $this->_sendEmail($data);

            $url = $this->_container->get('router')->generate(
                'thankyou', [], UrlGeneratorInterface::ABSOLUTE_PATH
            );

            return new RedirectResponse($url, 301);
        }

        return null;
    }

    private function _sendEmail(array $data)
    {
        $view = 'VGWebBundle:Contact:email.html.twig';
        $renderedView = $this->_container->get('templating')->render($view, ['data' => $data]);

        $to = $this->_container->getParameter('admin_email');
        $message = Swift_Message::newInstance()
            ->setSubject('Вопрос из сайта Наносвет!')
            ->setFrom(array($data['email'] => $data['username']))
            ->setTo($to)
            ->setContentType("text/html")
            ->setBody($renderedView);
        $this->_container->get('mailer')->send($message);
    }
}