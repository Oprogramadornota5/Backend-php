<?php

namespace App\Controller\Api;

use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/vehicle')]
class VehicleController extends AbstractController
{
    #[Route('', name: 'vehicle_index', methods: ['GET'])]
    public function index(VehicleRepository $repository): JsonResponse
    {
        $vehicles = $repository->findAll();

        $data = array_map(function (Vehicle $v) {
            return [
                'id' => $v->getId(),
                'marca' => $v->getMarca(),
                'modelo' => $v->getModelo(),
                'ano' => $v->getAno(),
                'preco' => $v->getPreco(),
                'descricao' => $v->getDescricao(),
                'disponivel' => $v->isDisponivel(),
            ];
        }, $vehicles);

        return $this->json($data);
    }

    #[Route('', name: 'vehicle_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $vehicle = new Vehicle();
        $vehicle->setMarca($data['marca']);
        $vehicle->setModelo($data['modelo']);
        $vehicle->setAno($data['ano']);
        $vehicle->setPreco($data['preco']);
        $vehicle->setDescricao($data['descricao'] ?? null);
        $vehicle->setDisponivel($data['disponivel'] ?? true);

        $em->persist($vehicle);
        $em->flush();

        return $this->json(['message' => 'Veículo criado com sucesso'], 201);
    }

    #[Route('/{id}', name: 'vehicle_show', methods: ['GET'])]
    public function show(Vehicle $vehicle): JsonResponse
    {
        return $this->json([
            'id' => $vehicle->getId(),
            'marca' => $vehicle->getMarca(),
            'modelo' => $vehicle->getModelo(),
            'ano' => $vehicle->getAno(),
            'preco' => $vehicle->getPreco(),
            'descricao' => $vehicle->getDescricao(),
            'disponivel' => $vehicle->isDisponivel(),
        ]);
    }

    #[Route('/{id}', name: 'vehicle_update', methods: ['PUT'])]
    public function update(Request $request, Vehicle $vehicle, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $vehicle->setMarca($data['marca']);
        $vehicle->setModelo($data['modelo']);
        $vehicle->setAno($data['ano']);
        $vehicle->setPreco($data['preco']);
        $vehicle->setDescricao($data['descricao'] ?? null);
        $vehicle->setDisponivel($data['disponivel']);

        $em->flush();

        return $this->json(['message' => 'Veículo atualizado']);
    }

    #[Route('/{id}', name: 'vehicle_delete', methods: ['DELETE'])]
    public function delete(Vehicle $vehicle, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($vehicle);
        $em->flush();

        return $this->json(null, 204);
    }
}
