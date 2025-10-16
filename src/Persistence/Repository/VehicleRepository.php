<?php

namespace Persistence\Repository;

use App\SQLiteConnection;
use Domain\Entity\Vehicle;
use Domain\Repository\VehicleRepositoryInterface;

class VehicleRepository implements VehicleRepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new SQLiteConnection())->connect();
    }

    public function getList()
    {
        $results = $this->pdo->query('SELECT * FROM vehicles');

        $items = [];
        foreach ($results as $row) {
            $items[] = $this->rowToEntity($row);
        }

        return $items;
    }

    public function getById($id)
    {

    }

    public function deleteById($id)
    {

    }

    public function persist(Vehicle $vehicle)
    {
        $data = [
            'registration_number' => $vehicle->getRegistrationNumber(),
            'brand' => $vehicle->getBrand(),
            'model' => $vehicle->getModel(),
            'type' => $vehicle->getType(),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($vehicle->getId() === 0) {
            $data['created_at'] = date('Y-m-d H:i:s');

            $sql = "INSERT INTO vehicles (registration_number, brand, model, type, created_at, updated_at) 
                VALUES (:registration_number, :brand, :model, :type, :created_at, :updated_at)";
        } else {
            $data['id'] = $vehicle->getId();

            $sql = "UPDATE vehicles 
                    SET registration_number=:registration_number, brand=:brand, model=:model, type=:type, updated_at=:updated_at
                    WHERE id=:id";
        }
        
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($data);

        return $result;
    }

    private function rowToEntity($row)
    {
        return (new Vehicle())
            ->setId($row['id'])
            ->setRegistrationNumber($row['registration_number'])
            ->setBrand($row['brand'])
            ->setModel($row['model'])
            ->setType($row['type'])
            ->setCreatedAt($row['created_at'])
            ->setUpdatedAt($row['updated_at'])
        ;
    }
}
