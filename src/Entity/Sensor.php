<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SensorRepository")
 */
class Sensor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Unit", inversedBy="sensors")
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="sensors")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SensorData", mappedBy="sensor")
     */
    private $sensor_data;

    public function __construct()
    {
        $this->sensor_data = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|SensorData[]
     */
    public function getSensorData(): Collection
    {
        return $this->sensor_data;
    }

    public function addSensorData(SensorData $sensorData): self
    {
        if (!$this->sensor_data->contains($sensorData)) {
            $this->sensor_data[] = $sensorData;
            $sensorData->setSensor($this);
        }

        return $this;
    }

    public function removeSensorData(SensorData $sensorData): self
    {
        if ($this->sensor_data->contains($sensorData)) {
            $this->sensor_data->removeElement($sensorData);
            // set the owning side to null (unless already changed)
            if ($sensorData->getSensor() === $this) {
                $sensorData->setSensor(null);
            }
        }

        return $this;
    }

}
