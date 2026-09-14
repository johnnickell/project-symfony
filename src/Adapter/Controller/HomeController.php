<?php

declare(strict_types=1);

namespace App\Adapter\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 */
final class HomeController extends AbstractController
{
    /**
     * Renders the configured home page
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'applicationName' => $this->getParameter('project.application_name')
        ]);
    }
}
