<?php

namespace Domain\Service;

use Domain\Entity\Vehicle;
use Domain\Repository\VehicleRepositoryInterface;

class VehiclesWriter
{
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    public function saveVehicle(VehicleDTO $vehicleDTO)
    {
        $vehicle = $this->DTOToEntity($vehicleDTO);

        $item = $this->vehicleRepository->persist($vehicle);

        return $item;
    }

    public function deleteById($id)
    {
        $item = $this->vehicleRepository->deleteById($id);

        return $item;
    }

    private function DTOToEntity(VehicleDTO $vehicleDTO)
    {
        $vehicle = new Vehicle();
        $vehicle->setId($vehicleDTO->id);
        $vehicle->setRegistrationNumber(strtoupper($vehicleDTO->registrationNumber));
        $vehicle->setBrand($vehicleDTO->brand);
        $vehicle->setModel($vehicleDTO->model);
        $vehicle->setType($vehicleDTO->type);
        $vehicle->setCreatedAt($vehicleDTO->createdAt);
        $vehicle->setUpdatedAt($vehicleDTO->updatedAt);

        return $vehicle;
    }
}
