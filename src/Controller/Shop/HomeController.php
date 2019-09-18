<?php

namespace App\Controller\Shop;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="shop_home")
     * @Template("home.html.twig")
     */
    public function __invoke()
    {
        return [
            'title' => 'Customer home'
        ];
    }
}
