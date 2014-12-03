<?php

namespace VG\WebBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\BrowserKit\Request;

class ContactController extends Controller
{
    /**
     * Thank you page controller
     *
     * @Route("/contact/thankyou", name="thankyou")
     */
    public function thankYouAction()
    {
        return $this->render('VGWebBundle:Contact:thankyou.html.twig');

    }

    /**
     *
     * @Route("/contact/ask_worker", name="ask_worker")
     */
    public function askWorkerAction()
    {

        $form = $this->get('util.contacthelper')->handleRequestContactForm();

        if ($response = $this->get('util.contacthelper')->sendContactForm($form)) {
            return $response;
        }

        return null;
    }
}