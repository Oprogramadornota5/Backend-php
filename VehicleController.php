<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    #[Route('/vehicle', name: 'vehicle_index')]
    public function index(): Response
    {
        return new Response('Página de veículos funcionando!');
    }
}
