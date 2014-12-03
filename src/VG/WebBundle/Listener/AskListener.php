<?php
/**
 * Created by PhpStorm.
 * User: volodya
 * Date: 03.12.14
 * Time: 23:51
 */

namespace VG\WebBundle\Listener;


class AskListener
{

    private $contactHelper;

    public function onKernelRequest()
    {

    }

    /**
     * @param ContactHelper
     */
    public function setContactHelper($contactHelper)
    {
        $this->contactHelper = $contactHelper;
    }

    public function getAskForm()
    {
        $form = $this->contactHelper->getContactForm();

        return $form->createView();
    }
} 