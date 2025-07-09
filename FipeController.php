<?php

namespace App\Controller\Api;

use App\Entity\Fipe;
use App\Repository\FipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/fipes')]
class FipeController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(FipeRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $fipe = new Fipe();
        $fipe->setCodigoFipe($data['codigoFipe']);
        $fipe->setMarca($data['marca']);
        $fipe->setModelo($data['modelo']);
        $fipe->setAnoModelo($data['anoModelo']);
        $fipe->setPrecoMedio($data['precoMedio']);
        $fipe->setMesReferencia($data['mesReferencia']);

        $em->persist($fipe);
        $em->flush();

        return $this->json($fipe, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Fipe $fipe): JsonResponse
    {
        return $this->json($fipe);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Fipe $fipe, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $fipe->setCodigoFipe($data['codigoFipe']);
        $fipe->setMarca($data['marca']);
        $fipe->setModelo($data['modelo']);
        $fipe->setAnoModelo($data['anoModelo']);
        $fipe->setPrecoMedio($data['precoMedio']);
        $fipe->setMesReferencia($data['mesReferencia']);

        $em->flush();

        return $this->json($fipe);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Fipe $fipe, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($fipe);
        $em->flush();

        return $this->json(null, 204);
    }
}
