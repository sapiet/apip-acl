<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="admin_home")
     * @Template("home.html.twig")
     */
    public function __invoke()
    {
        return [
            'title' => 'Employee home'
        ];
    }
}
