<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SensorDataRepository")
 */
class SensorData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Sensor", cascade={"persist", "remove"})
     */
    private $sensor_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getSensorId(): ?Sensor
    {
        return $this->sensor_id;
    }

    public function setSensorId(?Sensor $sensor_id): self
    {
        $this->sensor_id = $sensor_id;

        return $this;
    }
}
