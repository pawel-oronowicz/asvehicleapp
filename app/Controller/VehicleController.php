<?php

namespace App\Controller;

use Domain\Service\VehicleDTO;
use Domain\Service\VehiclesBuilder;
use Domain\Service\VehiclesWriter;
use Persistence\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

class VehicleController extends BaseController
{
    public function index(): Response
    {
        ob_start();
        include __DIR__ . '/../views/index.php';
        return (new Response(ob_get_clean()))->send();
    }

    public function list(): JsonResponse
    {
        $results = (new VehiclesBuilder(new VehicleRepository()))->getList();

        return $this->toJsonResponse(['results' => $results]);
    }

    public function save(int $id, Request $request): JsonResponse
    {
        $content = json_decode($request->getContent());

        $vehicleDTO = new VehicleDTO();
        $vehicleDTO->id = $id;
        $vehicleDTO->registrationNumber = $content->registrationNumber;
        $vehicleDTO->brand = $content->brand;
        $vehicleDTO->model = $content->model;
        $vehicleDTO->type = $content->type;

        $vehicle = (new VehiclesWriter(new VehicleRepository()))->saveVehicle($vehicleDTO);
        
        return $this->toJsonResponse([$id, $vehicle]);
    }

    public function delete(int $id): JsonResponse
    {
        (new VehiclesWriter(new VehicleRepository()))->deleteById($id);

        return $this->toJsonResponse([$id]);
    }
}
