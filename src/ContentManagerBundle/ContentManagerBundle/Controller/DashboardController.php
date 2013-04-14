<?php

namespace ContentManagerBundle\ContentManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * class DashboardController
 *
 * @Route("/contentmanager")
 */
class DashboardController extends DefaultController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @Template()
     */
    public function dashboardAction()
    {
        return array();
    }
}
